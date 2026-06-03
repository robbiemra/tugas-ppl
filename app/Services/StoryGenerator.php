<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        // Include the initial background image based on location
        $result['image'] = $this->getInitialImage($genre, $location);

        return $result;
    }

    /**
     * Generate the next story segment based on history and chosen action.
     */
    public function generateNextNode($accumulatedStory, $chosenAction): array
    {
        $systemPrompt = $this->getSystemPrompt();

        $userPrompt = "Riwayat Cerita Sebelumnya:\n{$accumulatedStory}\n\nAksi Terakhir yang Dipilih Pemain:\n{$chosenAction}\n\nTolong buat kelanjutan cerita berikutnya berdasarkan aksi tersebut dengan merujuk ke PETA ALUR CERITA di System Prompt. Jika alurnya adalah Ending, atur 'is_ending' ke true dan kosongkan array 'choices'.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        return $this->callAI($messages, 0.8);
    }

    /**
     * Send HTTP POST request to OpenAI API.
     */
    private function callAI(array $messages, float $temperature): array
    {
        $apiKey = config('services.openai.key');
        $apiUrl = config('services.openai.url', 'https://api.openai.com/v1/chat/completions');

        if (empty($apiKey)) {
            Log::error('OpenAI API Key is missing in services config.');
            return [
                'content' => 'Error: API Key OpenAI belum diatur di server.',
                'choices' => [],
                'is_ending' => true,
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(45)
            ->post($apiUrl, [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => $messages,
                'temperature' => $temperature,
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->failed()) {
                Log::error('OpenAI API Call Failed. Status: ' . $response->status() . ' Body: ' . $response->body());
                return [
                    'content' => 'Gagal menghubungi server AI untuk membuat cerita. Silakan coba sesaat lagi.',
                    'choices' => [],
                    'is_ending' => true,
                ];
            }

            $responseData = $response->json();
            $rawJsonText = $responseData['choices'][0]['message']['content'] ?? '';

            // Clean the response from potential markdown formatting
            $cleanJsonText = $this->cleanJsonString($rawJsonText);
            $decoded = json_decode($cleanJsonText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode OpenAI JSON response. Raw: ' . $rawJsonText . ' Error: ' . json_last_error_msg());
                return [
                    'content' => 'Gagal memproses format cerita dari AI. Silakan coba kembali.',
                    'choices' => [],
                    'is_ending' => true,
                ];
            }

            return [
                'content' => $decoded['content'] ?? 'Cerita tidak dapat dimuat.',
                'choices' => $decoded['choices'] ?? [],
                'is_ending' => (bool) ($decoded['is_ending'] ?? false),
            ];

        } catch (\Exception $e) {
            Log::error('Exception occurred during OpenAI API request: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'content' => 'Terjadi kesalahan sistem saat memproses cerita.',
                'choices' => [],
                'is_ending' => true,
            ];
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
