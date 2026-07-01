<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class StoryGenerator
{
    /**
     * Generate the initial opening segment of the story (Pure Offline).
     */
    public function generateInitialStory($userName, $gender, $genre, $location, $locationName = 'Gunung Slamet', $variationIndex = 0): array
    {
        // Langsung mengambil dari database lokal sesuai dengan pemetaan file
        $result = $this->getLocalStory($genre, $location, $userName, $locationName, 'NARASI_AWAL', $variationIndex);
        
        $result['image'] = $result['image'] ?? $this->getInitialImage($genre, $location);

        return $result;
    }

    /**
     * Generate the next story segment based on chosen node (Pure Offline).
     */
    public function generateNextNode($genre, $location, $userName, $locationName, $nextNodeId, $variationIndex = 0): array
    {
        // Melewati semua proses AI dan langsung membaca file JSON lokal
        return $this->getLocalStory($genre, $location, $userName, $locationName, $nextNodeId, $variationIndex);
    }

    /**
     * Memetakan input genre dan lokasi ke nama file JSON yang spesifik.
     */
    private function getJsonFilename(string $genre, string $location): string
    {
        $genreLower = strtolower(trim($genre));
        $locClean = strtolower(str_replace(' ', '', trim($location)));

        if ($genreLower === 'horror') {
            if (str_contains($locClean, 'hike') || str_contains($locClean, 'pendakian') || str_contains($locClean, 'gunung')) {
                return 'horror_hike_nodes.json';
            }
            if (str_contains($locClean, 'hospital') || str_contains($locClean, 'rumahsakit') || str_contains($locClean, 'rs')) {
                return 'horror_hospital_nodes.json';
            }
        } elseif ($genreLower === 'adventure') {
            if (str_contains($locClean, 'cave') || str_contains($locClean, 'gua')) {
                return 'adventure_cave_nodes.json';
            }
            if (str_contains($locClean, 'island') || str_contains($locClean, 'pulau')) {
                return 'adventure_island_nodes.json';
            }
        }

        // Jalur cadangan otomatis jika ada input yang tidak terduga
        return "{$genreLower}_{$locClean}_nodes.json";
    }

    /**
     * Membaca file JSON di storage/app/ dan mengembalikan konten dinamis.
     * Mendukung dua format:
     *  - Format BARU: node["variations"][n] (horror_hospital, adventure_cave, adventure_island)
     *  - Format LAMA: node["Argopuro"|"Lawu"|...] (horror_hike_nodes.json warisan)
     */
    private function getLocalStory($genre, $location, $userName = 'Kamu', $locationName = 'Lokasi Misterius', $nodeId = 'NARASI_AWAL', $variationIndex = 0): array
    {
        $jsonFileName = $this->getJsonFilename($genre, $location);
        $jsonPath = storage_path("app/{$jsonFileName}");

        if (file_exists($jsonPath)) {
            $fileContent = file_get_contents($jsonPath);
            $storyDatabase = json_decode($fileContent, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($storyDatabase[$nodeId])) {
                $nodeData = $storyDatabase[$nodeId];

                // --- FORMAT BARU: cek apakah node memiliki key "variations" ---
                if (isset($nodeData['variations']) && is_array($nodeData['variations'])) {
                    $variations = $nodeData['variations'];
                    $selectedVariation = $variations[$variationIndex] ?? ($variations[0] ?? null);

                    if ($selectedVariation) {
                        $content = str_replace(
                            ['$NAME', '$LOCATION', '$MOUNTAIN'],
                            [$userName, $locationName, $locationName],
                            $selectedVariation['content']
                        );

                        $choices = array_map(function ($choice) {
                            return [
                                'choice_text' => $choice['choice_text'] ?? ($choice['text'] ?? ''),
                                'next_node'   => $choice['next_node'] ?? ($choice['next'] ?? '')
                            ];
                        }, $selectedVariation['choices'] ?? []);

                        return [
                            'content'   => $content,
                            'choices'   => $choices,
                            'is_ending' => (bool) ($selectedVariation['is_ending'] ?? false),
                            'node_id'   => $nodeId,
                            'image'     => $selectedVariation['image'] ?? null
                        ];
                    }
                }

                // --- FORMAT LAMA: node keyed by mountain name (horror_hike warisan) ---
                // Ambil semua key yang tersedia (Argopuro, Lawu, Slamet, ...) dan pilih berdasarkan index
                $mountainKeys = array_keys($nodeData);
                if (!empty($mountainKeys)) {
                    $selectedKey = $mountainKeys[$variationIndex % count($mountainKeys)];
                    $selectedVariation = $nodeData[$selectedKey] ?? null;

                    if ($selectedVariation) {
                        $content = str_replace(
                            ['$NAME', '$LOCATION', '$MOUNTAIN', 'namamu'],
                            [$userName, $locationName, $locationName, $userName],
                            $selectedVariation['content']
                        );

                        $choices = array_map(function ($choice) {
                            return [
                                'choice_text' => $choice['choice_text'] ?? ($choice['text'] ?? ''),
                                'next_node'   => $choice['next_node'] ?? ($choice['next'] ?? '')
                            ];
                        }, $selectedVariation['choices'] ?? []);

                        return [
                            'content'   => $content,
                            'choices'   => $choices,
                            'is_ending' => (bool) ($selectedVariation['is_ending'] ?? false),
                            'node_id'   => $nodeId
                        ];
                    }
                }

                Log::error("Format JSON tidak valid atau tidak ada variasi di Node '{$nodeId}' dalam {$jsonFileName}");
            } else {
                Log::error("Node '{$nodeId}' tidak ditemukan di {$jsonFileName}. JSON error: " . json_last_error_msg());
            }
        } else {
            Log::error("File database cerita luring tidak ditemukan di path: {$jsonPath}");
        }

        // Skenario darurat mutlak jika file JSON tidak sengaja terhapus atau rusak
        return [
            'content'  => "Gagal memuat adegan [{$nodeId}] untuk {$genre} di {$locationName}. Pastikan file {$jsonFileName} berada di folder storage/app/.",
            'choices'  => [
                ['choice_text' => 'Coba Muat Ulang', 'next_node' => 'NARASI_AWAL']
            ],
            'is_ending' => false,
            'node_id'   => 'ERROR_FALLBACK'
        ];
    }

    /**
     * Memetakan gambar pendukung berdasarkan skenario permainan.
     */
    private function getInitialImage($genre, $location): string
    {
        $genreLower = strtolower(trim($genre));
        $locClean = strtolower(str_replace(' ', '', trim($location)));

        if ($genreLower === 'horror') {
            if (str_contains($locClean, 'hike') || str_contains($locClean, 'pendakian')) {
                return 'horror_hike_intro.png';
            }
            return 'horror_hospital_intro.png';
        }

        return 'adventure_generic_intro.png';
    }
}