<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\StoryArchive;

class StoryGenerator
{
    /**
     * Generate the initial opening segment of the story.
     */
    public function generateInitialStory($userName, $gender, $genre, $location): array
    {
        $systemPrompt = $this->getSystemPrompt();

        $userPrompt = "Mulai cerita pembuka petualangan gunung untuk petualang cilik bernama {$userName} ({$gender}) dengan genre {$genre} di lokasi {$location}. 
Gunakan panduan NARASI AWAL dari peta alur cerita. Jangan lupa sebutkan Nana dan Beni.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        // Using temperature 0.8 for creative storytelling
        $result = $this->callAI($messages, 0.8);

        // Check if API call failed, try to get archive fallback
        if ($result['generation_mode'] === 'archive' && isset($result['fallback_reason'])) {
            $archiveStory = $this->getArchiveFallback($genre, $location);
            if ($archiveStory) {
                $result['content'] = $archiveStory['node_content'];
                $result['choices'] = $archiveStory['choices_json'];
                $archiveStory->incrementUsageCount();
            }
        }

        // Include the initial background image based on location
        $result['image'] = $this->getInitialImage($genre, $location);

        return $result;
    }

    /**
     * Generate the next story segment based on history and chosen action.
     */
    public function generateNextNode($accumulatedStory, $chosenAction, $genre = null, $location = null): array
    {
        $systemPrompt = $this->getSystemPrompt();

        $userPrompt = "Riwayat Cerita Sebelumnya:\n{$accumulatedStory}\n\nAksi Terakhir yang Dipilih Pemain:\n{$chosenAction}\n\nTolong buat kelanjutan cerita berikutnya berdasarkan aksi tersebut dengan merujuk ke PETA ALUR CERITA di System Prompt. Jika alurnya adalah Ending, atur 'is_ending' ke true dan kosongkan array 'choices'.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        $result = $this->callAI($messages, 0.8);

        // Check if API call failed, try to get archive fallback
        if ($result['generation_mode'] === 'archive' && isset($result['fallback_reason']) && $genre && $location) {
            $archiveStory = $this->getArchiveFallback($genre, $location);
            if ($archiveStory) {
                $result['content'] = $archiveStory['node_content'];
                $result['choices'] = $archiveStory['choices_json'];
                $archiveStory->incrementUsageCount();
            }
        }

        return $result;
    }

    /**
     * Send HTTP POST request to OpenAI API with timeout & error tracking.
     */
    private function callAI(array $messages, float $temperature): array
    {
        $apiKey = config('services.openai.key');
        $apiUrl = config('services.openai.url', 'https://api.openai.com/v1/chat/completions');
        $startTime = microtime(true);
        $responseTime = 0;
        $fallbackReason = null;

        if (empty($apiKey)) {
            Log::error('OpenAI API Key is missing in services config.');
            return [
                'content' => 'Error: API Key OpenAI belum diatur di server.',
                'choices' => [],
                'is_ending' => true,
                'generation_mode' => 'error',
                'api_response_time' => 0,
                'fallback_reason' => 'api_key_missing',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(10) // 10 second timeout
            ->post($apiUrl, [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => $messages,
                'temperature' => $temperature,
                'response_format' => ['type' => 'json_object']
            ]);

            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            // Check for token error (429 or 401)
            if ($response->status() === 429) {
                Log::warning('OpenAI API Rate Limited (Token Exhausted).');
                $fallbackReason = 'token_error';
                return $this->createErrorResponse('Token API Gemini telah habis. Menggunakan cerita dari arsip.', $responseTime, $fallbackReason);
            }

            if ($response->status() === 401) {
                Log::warning('OpenAI API Unauthorized (Invalid Token).');
                $fallbackReason = 'token_error';
                return $this->createErrorResponse('Token tidak valid. Menggunakan cerita dari arsip.', $responseTime, $fallbackReason);
            }

            if ($response->failed()) {
                Log::error('OpenAI API Call Failed. Status: ' . $response->status() . ' Body: ' . $response->body());
                $fallbackReason = 'api_error';
                return $this->createErrorResponse('Gagal menghubungi server AI untuk membuat cerita. Menggunakan cerita dari arsip.', $responseTime, $fallbackReason);
            }

            $responseData = $response->json();
            $rawJsonText = $responseData['choices'][0]['message']['content'] ?? '';

            // Clean the response from potential markdown formatting
            $cleanJsonText = $this->cleanJsonString($rawJsonText);
            $decoded = json_decode($cleanJsonText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode OpenAI JSON response. Raw: ' . $rawJsonText . ' Error: ' . json_last_error_msg());
                $fallbackReason = 'api_error';
                return $this->createErrorResponse('Gagal memproses format cerita dari AI. Menggunakan cerita dari arsip.', $responseTime, $fallbackReason);
            }

            // Success - save to archive for future fallback
            $result = [
                'content' => $decoded['content'] ?? 'Cerita tidak dapat dimuat.',
                'choices' => $decoded['choices'] ?? [],
                'is_ending' => (bool) ($decoded['is_ending'] ?? false),
                'generation_mode' => 'realtime',
                'api_response_time' => $responseTime,
                'fallback_reason' => null,
            ];

            return $result;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);
            Log::warning('Connection error during OpenAI API request: ' . $e->getMessage());
            $fallbackReason = 'network_error';
            return $this->createErrorResponse('Koneksi internet lemot. Menggunakan cerita dari arsip.', $responseTime, $fallbackReason);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);
            if (strpos($e->getMessage(), 'timeout') !== false) {
                Log::warning('Timeout during OpenAI API request: ' . $e->getMessage());
                $fallbackReason = 'timeout';
                return $this->createErrorResponse('API timeout. Menggunakan cerita dari arsip.', $responseTime, $fallbackReason);
            }
            Log::warning('Request error during OpenAI API: ' . $e->getMessage());
            $fallbackReason = 'api_error';
            return $this->createErrorResponse('Terjadi kesalahan saat menghubungi AI. Menggunakan cerita dari arsip.', $responseTime, $fallbackReason);

        } catch (\Exception $e) {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);
            Log::error('Exception occurred during OpenAI API request: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            $fallbackReason = 'api_error';
            return $this->createErrorResponse('Terjadi kesalahan sistem saat memproses cerita. Menggunakan cerita dari arsip.', $responseTime, $fallbackReason);
        }
    }

    /**
     * Create a structured error response for fallback handling
     */
    private function createErrorResponse(string $message, int $responseTime, string $fallbackReason): array
    {
        return [
            'content' => $message,
            'choices' => [],
            'is_ending' => false,
            'generation_mode' => 'archive',
            'api_response_time' => $responseTime,
            'fallback_reason' => $fallbackReason,
        ];
    }

    /**
     * Get a random archived story for fallback
     */
    private function getArchiveFallback($genre, $location)
    {
        return StoryArchive::getRandomByGenreAndLocation($genre, $location);
    }

    /**
     * Save successful AI generation to archive for future fallback
     */
    public function saveToArchive($genre, $location, $content, $choices, $imageUrl = null): void
    {
        // Only save if not already exists (avoid duplicates)
        $existing = StoryArchive::where('genre', $genre)
            ->where('location', $location)
            ->where('node_content', $content)
            ->first();

        if (!$existing) {
            StoryArchive::create([
                'genre' => $genre,
                'location' => $location,
                'node_content' => $content,
                'choices_json' => $choices,
                'image_url' => $imageUrl,
            ]);
        }
    }

    /**
     * Clean any markdown code blocks wrapper from AI response string.
     */
    private function cleanJsonString(string $content): string
    {
        $content = preg_replace('/^```(?:json)?\s*/i', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);
        return trim($content);
    }

    /**
     * Get the core System Prompt containing the style guidelines and full plot reference map.
     */
    private function getSystemPrompt(): string
    {
        return "Kamu adalah AI Game Master profesional untuk permainan petualangan interaktif pilihan ganda berbahasa Indonesia.
Tugasmu adalah menghasilkan segmen cerita berdasarkan pilihan pemain dengan mengikuti peta alur cerita petualangan gunung berikut ini:

--- PETA ALUR CERITA (REFERENSI ENGINE) ---
* NARASI AWAL: Berkemah di gunung, malam hari, terdengar suara bisikan lembut (\"Ssttt... kemari...\"). -> Opsi: [1. Ikuti bisikan lembut itu, 2. Abaikan saja dan lanjut tidur]
* ALUR 1 (Ikuti Bisikan): Terpisah dari Nana & Beni, terpeleset jatuh ke jurang empuk, lutut luka, lapar/haus, menemukan ransel besar. -> Opsi: [1. Buka dan gunakan persediaan, 2. Jangan disentuh (jujur)]
* ALUR 1.1 (Gunakan Persediaan): Makan biskuit/susu, tubuh pulih kuat seperti pahlawan super. -> Opsi: [1. Lanjut berjalan mencari jalan keluar, 2. Duduk manis menunggu bantuan]
* ALUR 1.1.1 [ENDING 1]: Lanjut jalan, bertemu Paman SAR, Nana, dan Beni. Selamat meskipun kaki diperban. (is_ending: true)
* ALUR 1.1.2 [ENDING 2]: Menunggu bantuan, kuman nakal buat luka bengkak, tertidur selamanya sebelum bantuan datang. (is_ending: true)
* ALUR 1.2 (Tidak Gunakan Persediaan): Tubuh lemas, nemu pintu gua gelap tapi hangat. -> Opsi: [1. Masuk ke dalam gua, 2. Lewati saja gua itu]
* ALUR 1.2.1 [ENDING 3]: Masuk gua, ketemu Kakek Pertapa gaib berjanggut panjang, diajari sihir terbang, tinggal selamanya jadi muridnya. (is_ending: true)
* ALUR 1.2.2 [ENDING 4]: Lewati gua, energi habis, tertidur selamanya di tengah hutan sunyi karena terlalu lelah. (is_ending: true)

* ALUR 2 (Abaikan Bisikan): Anggap suara angin, lanjut jalan bersama Nana & Beni. Nemu peti kayu kuno berkunci emas di bawah pohon beringin besar. -> Opsi: [1. Abaikan peti misterius itu, 2. Buka peti cantiknya]
* ALUR 2.1 (Abaikan Peti): Jalan tertib melewati peti, ketemu persimpangan jalan kembar berkabut awan putih. -> Opsi: [1. Pilih Jalan yang Kiri, 2. Pilih Jalan yang Kanan]
* ALUR 2.1.1 [ENDING 5]: Jalan Kiri, ketemu pasar malam gaib ramai lampion, diculik penari cantik Badarawuhi, menari selamanya di sana. (is_ending: true)
* ALUR 2.1.2 [ENDING 6]: Jalan Kanan, ketemu istana emas berkilau, dipakaikan mahkota lewat ritual misterius, jadi Permaisuri cilik penguasa gunung. (is_ending: true)
* ALUR 2.2 (Buka Peti): Klik! Peti terbuka, nemu keris pusaka kecil berkilau yang dingin. -> Opsi: [1. Ambil keris indahnya, 2. Tinggalkan keris itu di dalam peti]
* ALUR 2.2.1 (Ambil Keris): Mata batin terbuka ajaib, dihadang hantu raksasa berbulu hitam seperti monster. -> Opsi: [1. Lawan hantu monster dengan keris, 2. Kabur lari sekencang-kencangnya!]
* ALUR 2.2.1.1 [ENDING 7]: Lawan hantu, keris keluar cahaya pelangi terang, hantu takut dan lari, pulang selamat sambil tertawa gembira. (is_ending: true)
* ALUR 2.2.1.2 [ENDING 8]: Kabur, lari tunggang-langgang sampai ke desa, selamat pulang ke rumah tapi tiap malam mimpi dikejar monster hitam. (is_ending: true)
* ALUR 2.2.2 (Tinggalkan Keris): Pergi tapi pikiran penasaran setengah mati. -> Opsi: [1. Tahan rasa penasaran dan tidak kembali, 2. Berbalik arah untuk mengambil kerisnya]
* ALUR 2.2.2.1 (Tidak Kembali): Tetap jalan lurus menembus hutan hingga ketemu persimpangan jalan kembar lagi. -> Opsi: [1. Pilih Jalan yang Kiri -> Memicu ENDING 5, 2. Pilih Jalan yang Kanan -> Memicu ENDING 6]
* ALUR 2.2.2.2 (Kembali Ambil Keris): Balik arah sendirian, ambil keris, BUM! Dihadang hantu monster besar sendirian. -> Opsi: [1. Lawan hantu monster -> Memicu ENDING 7, 2. Kabur lari sekencang-kencangnya -> Memicu ENDING 8]

Aturan Gaya Bahasa & Konten:
1. Gaya Penulisan: WAJIB menggunakan gaya buku cerita / dongeng anak-anak yang jenaka, polos, penuh kalimat seru (seperti \"Wah!\", \"Uh-oh!\", \"Aduh!\"), berima, mudah dipahami, namun tetap membawa elemen misteri dari genre Horor Pendakian.
2. Sudut Pandang: Menggunakan orang kedua (\"Kamu\") sebagai petualang cilik.
3. Karakter: Narasi HARUS konsisten melibatkan 'Kamu' (user), 'Nana', dan 'Beni'.
4. Batasan Panjang: Narasi cerita pada setiap node MINIMAL 200 kata dan MAKSIMAL 250 kata (singkat dan padat).
5. Output format: Kamu HARUS merespons HANYA dengan JSON mentah berstruktur:
{
  \"content\": \"[Isi narasi cerita dongeng pendek...]\",
  \"choices\": [
    {\"choice_text\": \"[Pilihan aksi pendek A]\"},
    {\"choice_text\": \"[Pilihan aksi pendek B]\"}
  ],
  \"is_ending\": false/true
}";
    }

    /**
     * Map the genre and location to the corresponding initial image asset.
     */
    private function getInitialImage($genre, $location): string
    {
        if ($genre === 'Horror') {
            if ($location === 'Pendakian') {
                return 'Horror/Pendakian/intro awal.png';
            }
            if ($location === 'Rumah Sakit') {
                return 'wpkakek.jpeg';
            }
        } elseif ($genre === 'Adventure') {
            if ($location === 'Pulau Terpencil') {
                return 'japan.jpg';
            }
        }
        return 'wallpaperhorror.jpeg';
    }
}
