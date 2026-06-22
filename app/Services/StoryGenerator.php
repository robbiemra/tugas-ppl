<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StoryGenerator
{
    /**
     * Generate the initial opening segment of the story.
     */
    public function generateInitialStory($userName, $gender, $genre, $location, $mountain = 'Argopuro'): array
    {
        $systemPrompt = $this->getSystemPrompt();

        $userPrompt = "Mulai cerita pembuka petualangan gunung untuk petualang cilik bernama {$userName} ({$gender}) dengan genre {$genre} di lokasi {$location}. 
Gunakan panduan NARASI AWAL dari peta alur cerita. Jangan lupa sebutkan Nana dan Beni.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        // Try AI first
        $result = $this->callAI($messages, 0.8);

        // Fallback to local story database if AI fails
        if (!$result) {
            $result = $this->getLocalStory($genre, $location, null, $userName, $mountain);
        }

        // Include the initial background image based on location
        $result['image'] = $this->getInitialImage($genre, $location);

        return $result;
    }

    /**
     * Generate the next story segment based on history and chosen action.
     */
    public function generateNextNode($accumulatedStory, $chosenAction, $genre = 'Horror', $location = 'Pendakian', $userName = 'Kamu', $mountain = 'Argopuro', $nextNodeId = null): array
    {
        $systemPrompt = $this->getSystemPrompt();

        $userPrompt = "Riwayat Cerita Sebelumnya:\n{$accumulatedStory}\n\nAksi Terakhir yang Dipilih Pemain:\n{$chosenAction}\n\nTolong buat kelanjutan cerita berikutnya berdasarkan aksi tersebut dengan merujuk ke PETA ALUR CERITA di System Prompt. Jika alurnya adalah Ending, atur 'is_ending' ke true dan kosongkan array 'choices'.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        // Try AI first
        $result = $this->callAI($messages, 0.8);

        // Fallback to local story database if AI fails
        if (!$result) {
            $result = $this->getLocalStory($genre, $location, $chosenAction, $userName, $mountain, $nextNodeId);
        }

        return $result;
    }

    /**
     * Send HTTP POST request to OpenAI API with a 4 second limit.
     */
    private function callAI(array $messages, float $temperature): ?array
    {
        $apiKey = config('services.openai.key');
        $apiUrl = config('services.openai.url', 'https://api.openai.com/v1/chat/completions');

        if (empty($apiKey)) {
            Log::warning('OpenAI API Key is missing in services config. Using local fallback.');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(12) // Limit to 12 seconds per scene
            ->post($apiUrl, [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => $messages,
                'temperature' => $temperature,
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->failed()) {
                Log::warning('OpenAI API Call Failed. Status: ' . $response->status() . '. Using local fallback.');
                return null;
            }

            $responseData = $response->json();
            $rawJsonText = $responseData['choices'][0]['message']['content'] ?? '';

            // Clean the response from potential markdown formatting
            $cleanJsonText = $this->cleanJsonString($rawJsonText);
            $decoded = json_decode($cleanJsonText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to decode OpenAI JSON response. Using local fallback.');
                return null;
            }

            return [
                'content' => $decoded['content'] ?? 'Cerita tidak dapat dimuat.',
                'choices' => $decoded['choices'] ?? [],
                'is_ending' => (bool) ($decoded['is_ending'] ?? false),
            ];

        } catch (\Exception $e) {
            Log::warning('Exception occurred during OpenAI API request: ' . $e->getMessage() . '. Using local fallback.');
            return null;
        }
    }

    /**
     * Local story database and router that acts as an instant fallback when AI is unavailable.
     */
    private function getLocalStory($genre, $location, $chosenAction = null, $userName = 'Kamu', $mountain = 'Argopuro', $nextNodeId = null): array
    {
        $genre = strtoupper($genre);
        $location = strtoupper($location);

        if ($genre === 'HORROR' && $location === 'PENDAKIAN') {
            $node = $nextNodeId ?: 'NARASI_AWAL';

            $jsonPath = storage_path('app/horror_story_nodes.json');
            if (file_exists($jsonPath)) {
                $nodes = json_decode(file_get_contents($jsonPath), true);
                if (isset($nodes[$node]) && isset($nodes[$node][$mountain])) {
                    $selected = $nodes[$node][$mountain];
                    $selected['content'] = str_replace('namamu', $userName, $selected['content']);
                    $selected['node_id'] = $node;
                    return $selected;
                }
            }

            return [
                'content' => "Maaf, rute cerita ini (Node: $node, Gunung: $mountain) sedang dalam pengembangan atau file cerita tidak lengkap.",
                'choices' => [],
                'is_ending' => true,
                'node_id' => $node
            ];
        }

        if ($genre === 'ADVENTURE' && $location === 'PENDAKIAN') {
            $node = 'NARASI_AWAL';

            if ($chosenAction) {
                $actionLower = strtolower(trim($chosenAction));
                if (str_contains($actionLower, 'ambil peta') || 
                    str_contains($actionLower, 'ikuti burung') || 
                    str_contains($actionLower, 'menyeberangi jembatan')) {
                    $node = 'ALUR_1_IKUTI_PETUNJUK';
                }
            }

            $alternatives = [];
            switch ($node) {
                case 'NARASI_AWAL':
                    $alternatives = [
                        [
                            'content' => "Matahari bersinar cerah di langit Gunung Gede Pangrango. Kamu, Nana, dan Beni melangkah penuh semangat menyusuri jalan setapak yang dikelilingi bunga edelweis. Tiba-tiba, Beni menunjuk sebuah peta tua yang tergeletak di dekat akar pohon tua.",
                            'choices' => [["choice_text" => "Ambil peta tua itu"], ["choice_text" => "Abaikan peta dan ikuti jalur resmi"]],
                            'is_ending' => false
                        ],
                        [
                            'content' => "Udara pagi yang sejuk menyambut langkah petualangan kalian. Di tengah perjalanan mendaki, Nana melihat seekor burung langka berwarna emas terbang rendah seolah ingin menunjukkan sesuatu di balik semak-semak.",
                            'choices' => [["choice_text" => "Ikuti burung emas itu"], ["choice_text" => "Tetap fokus mendaki ke puncak"]],
                            'is_ending' => false
                        ],
                        [
                            'content' => "Gemuruh air terjun terdengar dari kejauhan jalur pendakian. Beni mengusulkan untuk mengambil rute memotong melewati jembatan gantung tua yang terlihat menantang namun rapuh.",
                            'choices' => [["choice_text" => "Nekat menyeberangi jembatan tua"], ["choice_text" => "Putar balik cari jalan memutar"]],
                            'is_ending' => false
                        ]
                    ];
                    break;
                case 'ALUR_1_IKUTI_PETUNJUK':
                    $alternatives = [
                        [
                            'content' => "Petualangan membawamu menemukan keindahan tersembunyi! Kamu berhasil melewati tantangan pertama bersama Nana dan Beni. Sebuah pemandangan lembah hijau yang luar biasa terbentang di depan mata kalian.",
                            'choices' => [],
                            'is_ending' => true
                        ]
                    ];
                    break;
            }

            $selectedIdx = rand(0, count($alternatives) - 1);
            return $alternatives[$selectedIdx];
        }

        return [
            'content' => "Kabut misterius menyelimuti jalan setapak... Kamu, Nana, dan Beni merasakan hembusan angin dingin yang tidak biasa.",
            'choices' => [
                ['choice_text' => 'Teruskan langkah kaki'],
                ['choice_text' => 'Mencari perlindungan terdekat']
            ],
            'is_ending' => false
        ];
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
        return "[ROLE & SYSTEM OBJECTIVE]
Kamu adalah sebuah AI Game Master dan Data Formatter untuk game cerita interaktif multi-genre.
Tugas utamamu BUKAN menciptakan cerita baru secara bebas (Full AI), melainkan bertindak sebagai \"Router\" yang:
1. Mendeteksi parameter input: GENRE (Horror / Adventure) dan LOKASI (Pendakian / [Lokasi Lain]).
2. Menerima \"Riwayat Cerita Sebelumnya\" dan \"Aksi Terakhir\" dari pemain.
3. Mencocokkan input tersebut dengan PETA PERCABANGAN CERITA yang sesuai di bawah berdasarkan GENRE dan LOKASI yang aktif.
4. MEMILIH SECARA ACAK (RANDOM) satu dari 3 Alternatif cerita yang sudah disediakan untuk alur tersebut.
5. Mengembalikan hasilnya dalam format JSON mentah (Raw JSON) tanpa teks pengantar tambahan.

---

[PETA PERCABANGAN CERITA & ALTERNATIF PILIHAN]

=========================================
GENRE: HORROR | LOKASI: PENDAKIAN
=========================================

=== NODE: NARASI_AWAL ===
(Dipicu jika ini adalah awal permainan)
- Alternatif 1:
  Content: \"Kabut tebal bergulung lambat menyelubungi jalur pendakian Gunung Argopuro... Tepat di samping telingamu, terdengar sebuah bisikan lirih bergaung, suara perempuan yang mendayu-dayu memanggil namamu...\"
  Choices: [{\"choice_text\": \"Ikuti bisikan perempuan itu\"}, {\"choice_text\": \"Abaikan bisikan dan terus berjalan\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Kabut hitam pekat mendadak turun, menelan sisa sisa cahaya bulan... Sayup-sayup dari balik dinding kabut, terdengar sebuah bisikan massal seperti suasana pasar malam kuno...\"
  Choices: [{\"choice_text\": \"Ikuti suara pasar malam gaib itu\"}, {\"choice_text\": \"Abaikan suara itu dan dirikan tenda\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Hujan rintik-rintik mulai membasahi jalur pendakian yang kian curam... Dari balik rimbunnya semak belukar yang gelap, terdengar sebuah bisikan lirih serak, menyerupai suara kakek tua...\"
  Choices: [{\"choice_text\": \"Ikuti rintihan kakek tua itu\"}, {\"choice_text\": \"Abaikan suara kakek itu dan jalan terus\"}]
  Is_Ending: false

=== NODE: ALUR_1_IKUTI_BISIKAN ===
(Dipicu jika aksi terakhir adalah: \"Ikuti bisikan perempuan itu\", \"Ikuti suara pasar malam gaib itu\", atau \"Ikuti rintihan kakek tua itu\")
- Alternatif 1:
  Content: \"Kamu memilih pilihan 1 untuk ikuti bisikan perempuan misterius itu... Tiba-tiba, tanah yang kalian pijak amblas. Kalian bertiga jatuh ke jurang yang curam... Kamu menemukan sebuah ransel pendaki usang...\"
  Choices: [{\"choice_text\": \"Gunakan persediaan makanan misterius\"}, {\"choice_text\": \"Jangan sentuh makanan tersebut\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Kamu memilih pilihan 1 untuk ikuti bisikan riuh rendah dari 'Pasar Setan'... Kalian bertiga jatuh ke jurang berbatu yang sangat dalam... Kalian menemukan sebuah ransel pendaki yang sudah robek...\"
  Choices: [{\"choice_text\": \"Gunakan persediaan biskuit dan air\"}, {\"choice_text\": \"Abaikan dan biarkan makanan itu\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Kamu memilih pilihan 1 untuk ikuti bisikan kakek tua... Di ujung semak, kaki kalian menginjak ruang kosong hingga kalian bertiga jatuh ke jurang... Kamu menemukan sebuah ransel pendaki merah usang...\"
  Choices: [{\"choice_text\": \"Gunakan kaleng makanan tersebut\"}, {\"choice_text\": \"Tinggalkan ransel merah tersebut\"}]
  Is_Ending: false

=== NODE: ALUR_1_1_GUNAKAN_PERSEDIAAN ===
(Dipicu jika aksi terakhir adalah: \"Gunakan persediaan makanan misterius\", \"Gunakan persediaan biskuit dan air\", atau \"Gunakan kaleng makanan tersebut\")
- Alternatif 1:
  Content: \"Kamu memilih pilihan 1 untuk gunakan persediaan misterius... Secara perlahan, tubuh kalian merasa sedikit pulih... Namun, keganjilan mulai terasa saat rasa kenyang itu diikuti oleh denyut aneh di tengkukmu...\"
  Choices: [{\"choice_text\": \"Lanjut perjalanan naik tebing\"}, {\"choice_text\": \"Tetap diam menunggu bantuan\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Kamu memilih pilihan 1 untuk gunakan persediaan dari ransel pembawa petaka... Fisik kalian terasa sedikit pulih secara instan... Namun, pemulihan gaib ini harus dibayar mahal dengan bau kemenyan...\"
  Choices: [{\"choice_text\": \"Gunakan kekuatan gaib untuk memanjat\"}, {\"choice_text\": \"Bertahan di posisi sekarang dan menunggu\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Kamu memilih pilihan 1 untuk gunakan persediaan dari ransel merah usang... Tubuh kalian merasa sedikit pulih secara instan... Namun, kulit kalian perlahan berubah menjadi pucat pasi...\"
  Choices: [{\"choice_text\": \"Memanfaatkan energi baru untuk merayap naik\"}, {\"choice_text\": \"Berdiam diri di ceruk batu menunggu bantuan\"}]
  Is_Ending: false

=== NODE: ALUR_1_1_1_ENDING_SELAMAT ===
(Dipicu jika aksi terakhir adalah: \"Lanjut perjalanan naik tebing\", \"Gunakan kekuatan gaib untuk memanjat\", atau \"Memanfaatkan energi baru untuk merayap naik\")
- Alternatif 1:
  Content: \"Kamu memilih pilihan 1 untuk lanjut perjalanan... Beruntung, di ujung batas kesadaran, kamu akhirnya bertemu tim SAR... Kamu, Nana, dan Beni dinyatakan selamat namun luka parah...\"
  Choices: []
  Is_Ending: true
- Alternatif 2:
  Content: \"Kamu memilih pilihan 1 untuk lanjut perjalanan... Beberapa sorot lampu senter membelah kabut, dan kalian akhirnya bertemu tim SAR... Kalian berhasil lolos namun dengan trauma mendalam...\"
  Choices: []
  Is_Ending: true
- Alternatif 3:
  Content: \"Kamu memilih pilihan 1 untuk lanjut perjalanan... Di detik-detik terakhir sebelum tubuhmu ambruk, kalian akhirnya bertemu tim SAR... Jiwa kalian selamat, raga kalian cacat abadi...\"
  Choices: []
  Is_Ending: true

=== NODE: ALUR_1_1_2_ENDING_MATI ===
(Dipicu jika aksi terakhir adalah: \"Tetap diam menunggu bantuan\", \"Bertahan di posisi sekarang dan menunggu\", atau \"Berdiam diri di ceruk batu menunggu bantuan\")
- Alternatif 1:
  Content: \"Kamu memilih pilihan 2 untuk menunggu bantuan... Luka semakin parah dan infeksi. Luka robek di kepalamu mulai mengeluarkan bau busuk... Kamu akhirnya meninggal karena luka infeksi...\"
  Choices: []
  Is_Ending: true
- Alternatif 2:
  Content: \"Kamu memilih pilihan 2 untuk menunggu bantuan... Energi dari biskuit lenyap total. Luka semakin parah dan infeksi... Kamu akhirnya meninggal, menjadi barang dagangan baru Pasar Setan...\"
  Choices: []
  Is_Ending: true
- Alternatif 3:
  Content: \"Kamu memilih pilihan 2 untuk menunggu bantuan... Efek pemulihan menguap, luka semakin parah dan infeksi agresif menyerang... Kamu akhirnya meninggal, ragamu membusuk di dasar bumi...\"
  Choices: []
  Is_Ending: true

=== NODE: ALUR_2_ABAIKAN_BISIKAN ===
(Dipicu jika aksi terakhir adalah: \"Abaikan bisikan dan terus berjalan\", \"Abaikan suara itu dan dirikan tenda\", atau \"Abaikan suara kakek itu dan jalan terus\", atau \"Abaikan bisikan\")
- Alternatif 1:
  Content: \"Kamu memilih pilihan 2 untuk mengabaikan bisikan gaib tersebut. Kalian bergegas mendirikan tenda darurat di bawah pohon beringin raksasa untuk berlindung. Tiba-tiba, Beni menemukan sebuah peti kayu kuno berdebu...\"
  Choices: [{\"choice_text\": \"Buka peti kayu kuno itu\"}, {\"choice_text\": \"Abaikan peti dan cari jalan lain\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Kamu memilih pilihan 2 untuk mengabaikan suara gaib tersebut dan berjalan cepat. Di bawah kegelapan malam, kalian terhenti di depan sebuah pohon beringin angker. Di bawah celah akarnya yang besar, tergeletak peti kayu kuno berdebu...\"
  Choices: [{\"choice_text\": \"Buka peti kayu kuno itu\"}, {\"choice_text\": \"Abaikan peti dan cari jalan lain\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Kamu memilih pilihan 2 untuk mengabaikan suara kakek itu dan jalan terus. Langkah kalian terhenti di dekat pohon beringin tua yang sangat besar. Tepat di bawah akar pohon, terdapat peti kayu kuno berselimut debu tebal...\"
  Choices: [{\"choice_text\": \"Buka peti kayu kuno itu\"}, {\"choice_text\": \"Abaikan peti dan cari jalan lain\"}]
  Is_Ending: false

=== NODE: ALUR_2_1_BUKA_PETI ===
(Dipicu jika aksi terakhir adalah: \"Buka peti kayu kuno itu\")
- Alternatif 1:
  Content: \"Kamu memilih untuk membuka peti kayu kuno itu. Tutup peti terbuka dengan suara derit keras. Di dalamnya terbaring sebuah keris pusaka berlapis emas yang memancarkan aura dingin yang menusuk...\"
  Choices: [{\"choice_text\": \"Ambil keris pusaka tersebut\"}, {\"choice_text\": \"Tinggalkan keris pusaka itu\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Kamu memilih untuk membuka peti kayu kuno itu. Debu tebal beterbangan saat peti terbuka. Di dalamnya, kalian melihat sebilah keris pusaka kuno yang berkilau di kegelapan malam...\"
  Choices: [{\"choice_text\": \"Ambil keris pusaka tersebut\"}, {\"choice_text\": \"Tinggalkan keris pusaka itu\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Kamu memilih untuk membuka peti kayu kuno itu. Di dalam peti yang berbau kayu tua, tersimpan sebilah keris pusaka mistis dengan ukiran naga di gagangnya...\"
  Choices: [{\"choice_text\": \"Ambil keris pusaka tersebut\"}, {\"choice_text\": \"Tinggalkan keris pusaka itu\"}]
  Is_Ending: false

=== NODE: ALUR_2_1_1_AMBIL_KERIS ===
(Dipicu jika aksi terakhir adalah: \"Ambil keris pusaka tersebut\" atau \"Kembali untuk mengambil keris\")
- Alternatif 1:
  Content: \"Kamu memutuskan mengambil keris pusaka itu. Seketika bulu kuduk kalian berdiri tegak. Dari balik kegelapan pohon beringin, sesosok hantu raksasa dengan mata merah membara muncul menghadang langkah kalian!\"
  Choices: [{\"choice_text\": \"Lawan hantu dengan keris\"}, {\"choice_text\": \"Lari ketakutan menyelamatkan diri\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Kamu memutuskan mengambil keris pusaka itu. Energi gaib merayapi tanganmu. Tiba-tiba sesosok bayangan hitam besar dengan mata merah menyala berdiri tegak di depan kalian sambil menggeram marah!\"
  Choices: [{\"choice_text\": \"Lawan hantu dengan keris\"}, {\"choice_text\": \"Lari ketakutan menyelamatkan diri\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Kamu memutuskan mengambil keris pusaka itu. Hawa dingin menjalar di punggungmu. Detik berikutnya, hantu raksasa bermata merah menyala menembus kabut dan menggeram keras menuntut pusakanya dikembalikan!\"
  Choices: [{\"choice_text\": \"Lawan hantu dengan keris\"}, {\"choice_text\": \"Lari ketakutan menyelamatkan diri\"}]
  Is_Ending: false

=== NODE: ALUR_2_1_1_1_ENDING_MENANG ===
(Dipicu jika aksi terakhir adalah: \"Lawan hantu dengan keris\")
- Alternatif 1:
  Content: \"Kamu memilih melawan hantu dengan keris. Dengan keberanian terakhir, kamu mengacungkan keris pusaka. Cahaya keemasan menyembur keluar, menghancurkan sosok hantu hingga sirna. Kalian selamat dan dihormati penunggu hutan!\"
  Choices: []
  Is_Ending: true
- Alternatif 2:
  Content: \"Kamu memilih melawan hantu dengan keris. Kamu menghujamkan keris ke udara. Kilatan cahaya gaib menyambar hantu raksasa itu hingga menjerit keras dan lenyap. Kalian berhasil selamat dengan memegang pusaka pelindung!\"
  Choices: []
  Is_Ending: true
- Alternatif 3:
  Content: \"Kamu memilih melawan hantu dengan keris. Keris di tanganmu bersinar terang. Hantu raksasa itu terbakar oleh energi pusaka dan musnah menjadi kabut putih. Kalian selamat dan diakui sebagai pemberani di gunung ini!\"
  Choices: []
  Is_Ending: true

=== NODE: ALUR_2_1_1_2_ENDING_TRAUMA ===
(Dipicu jika aksi terakhir adalah: \"Lari ketakutan menyelamatkan diri\")
- Alternatif 1:
  Content: \"Kamu memilih lari ketakutan. Kalian melempar keris itu dan berlari tanpa menoleh ke belakang. Kalian berhasil mencapai pos bawah dengan selamat, namun setiap malam kamu terus bermimpi buruk tentang mata merah dalam gelap.\"
  Choices: []
  Is_Ending: true
- Alternatif 2:
  Content: \"Kamu memilih lari ketakutan. Kalian lari kencang menerobos semak-semak berduri. Meskipun berhasil selamat sampai ke basecamp, ingatan akan mata merah gaib itu membuatmu trauma seumur hidup.\"
  Choices: []
  Is_Ending: true
- Alternatif 3:
  Content: \"Kamu memilih lari ketakutan. Dengan napas memburu kalian berlari menyelamatkan diri. Raga kalian berhasil pulang dengan selamat, namun jiwa kalian terus dibayangi ketakutan akan teror malam itu.\"
  Choices: []
  Is_Ending: true

=== NODE: ALUR_2_1_2_TINGGALKAN_KERIS ===
(Dipicu jika aksi terakhir adalah: \"Tinggalkan keris pusaka itu\")
- Alternatif 1:
  Content: \"Kamu memilih untuk meninggalkan keris pusaka itu dan menutup peti. Namun saat melangkah pergi, rasa bimbang menyelimuti hatimu. Seolah-olah ada kekuatan gaib yang membisikkanmu untuk kembali...\"
  Choices: [{\"choice_text\": \"Kembali untuk mengambil keris\"}, {\"choice_text\": \"Tetap abaikan dan lanjutkan perjalanan\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Kamu memilih untuk meninggalkan keris itu. Kalian berjalan menjauh, namun pikiranmu terus tertuju pada keris di dalam peti tersebut. Apakah kalian akan kembali mengambilnya?\"
  Choices: [{\"choice_text\": \"Kembali untuk mengambil keris\"}, {\"choice_text\": \"Tetap abaikan dan lanjutkan perjalanan\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Kamu memilih untuk meninggalkan keris pusaka itu. Setelah berjalan beberapa meter, Beni mengusulkan untuk kembali karena merasa keris itu mungkin satu-satunya pelindung kalian...\"
  Choices: [{\"choice_text\": \"Kembali untuk mengambil keris\"}, {\"choice_text\": \"Tetap abaikan dan lanjutkan perjalanan\"}]
  Is_Ending: false

=== NODE: ALUR_2_2_ABAIKAN_PETI_PERSIMPANGAN ===
(Dipicu jika aksi terakhir adalah: \"Abaikan peti dan cari jalan lain\" atau \"Tetap abaikan dan lanjutkan perjalanan\")
- Alternatif 1:
  Content: \"Kalian memutuskan untuk mengabaikan peti dan melanjutkan perjalanan. Kabut malam kian tebal menyelimuti jalan setapak hingga kalian tiba di sebuah persimpangan jalan setapak yang gelap gulita...\"
  Choices: [{\"choice_text\": \"Pilih Jalur Kiri\"}, {\"choice_text\": \"Pilih Jalur Kanan\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Kalian terus melangkah tanpa mempedulikan peti kayu itu. Setelah berjalan cukup lama menerobos kegelapan, kalian terhenti di sebuah percabangan jalan misterius yang tidak ada di peta...\"
  Choices: [{\"choice_text\": \"Pilih Jalur Kiri\"}, {\"choice_text\": \"Pilih Jalur Kanan\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Kalian tetap berjalan menjauh. Langkah kaki kalian membawa kalian ke sebuah persimpangan jalan di tengah hutan mati. Kiri atau kanan, jalan mana yang akan menuntun kalian pulang?\"
  Choices: [{\"choice_text\": \"Pilih Jalur Kiri\"}, {\"choice_text\": \"Pilih Jalur Kanan\"}]
  Is_Ending: false

=== NODE: ALUR_2_2_1_ENDING_SILUMAN ===
(Dipicu jika aksi terakhir adalah: \"Pilih Jalur Kiri\")
- Alternatif 1:
  Content: \"Kamu memilih Jalur Kiri. Suara gamelan kuno terdengar kian jelas menuntun langkah kalian. Tiba-tiba kalian masuk ke sebuah pasar gaib yang ramai. Kalian terjebak di sana menjadi pelayan Ratu Ular selamanya.\"
  Choices: []
  Is_Ending: true
- Alternatif 2:
  Content: \"Kamu memilih Jalur Kiri. Kabut di depan membentuk sebuah desa kuno dengan aroma melati yang pekat. Begitu masuk, penduduk desa berubah menjadi siluman ular dan menawan jiwa kalian untuk selamanya.\"
  Choices: []
  Is_Ending: true
- Alternatif 3:
  Content: \"Kamu memilih Jalur Kiri. Alunan musik mistis menyambut langkah kalian di ujung jalan. Kalian terhipnotis masuk ke wilayah terlarang siluman ular dan tidak pernah bisa kembali lagi ke dunia nyata.\"
  Choices: []
  Is_Ending: true

=== NODE: ALUR_2_2_2_ENDING_RAJA_HANTU ===
(Dipicu jika aksi terakhir adalah: \"Pilih Jalur Kanan\")
- Alternatif 1:
  Content: \"Kamu memilih Jalur Kanan. Ribuan kunang-kunang gaib bercahaya perak menuntun jalan kalian hingga tiba di depan sebuah istana megah. Para penunggu gaib menyambut dan menobatkan kalian sebagai penguasa baru istana hantu.\"
  Choices: []
  Is_Ending: true
- Alternatif 2:
  Content: \"Kamu memilih Jalur Kanan. Angin hangat berembus menuntun kalian ke sebuah taman indah di tengah hutan yang dihuni para roh leluhur. Kalian ditawari kedamaian abadi dan memilih tinggal bersama mereka selamanya.\"
  Choices: []
  Is_Ending: true
- Alternatif 3:
  Content: \"Kamu memilih Jalur Kanan. Di ujung jalur, terpancar cahaya keperakan dari istana gaib yang sangat megah. Jiwa kalian terpikat oleh keindahan dunia roh, dan kalian memutuskan untuk menetap di sana selamanya.\"
  Choices: []
  Is_Ending: true


=========================================
GENRE: ADVENTURE | LOKASI: PENDAKIAN
=========================================
*(Catatan: Bagian ini adalah draf alur cerita petualangan yang belum sepenuhnya lengkap)*

=== NODE: NARASI_AWAL ===
(Dipicu jika ini adalah awal permainan genre Adventure di Pendakian)
- Alternatif 1:
  Content: \"Matahari bersinar cerah di langit Gunung Gede Pangrango. Kamu, Nana, dan Beni melangkah penuh semangat menyusuri jalan setapak yang dikelilingi bunga edelweis. Tiba-tiba, Beni menunjuk sebuah peta tua yang tergeletak di dekat akar pohon tua.\"
  Choices: [{\"choice_text\": \"Ambil peta tua itu\"}, {\"choice_text\": \"Abaikan peta dan ikuti jalur resmi\"}]
  Is_Ending: false
- Alternatif 2:
  Content: \"Udara pagi yang sejuk menyambut langkah petualangan kalian. Di tengah perjalanan mendaki, Nana melihat seekor burung langka berwarna emas terbang rendah seolah ingin menunjukkan sesuatu di balik semak-semak.\"
  Choices: [{\"choice_text\": \"Ikuti burung emas itu\"}, {\"choice_text\": \"Tetap fokus mendaki ke puncak\"}]
  Is_Ending: false
- Alternatif 3:
  Content: \"Gemuruh air terjun terdengar dari kejauhan jalur pendakian. Beni mengusulkan untuk mengambil rute memotong melewati jembatan gantung tua yang terlihat menantang namun rapuh.\"
  Choices: [{\"choice_text\": \"Nekat menyeberangi jembatan tua\"}, {\"choice_text\": \"Putar balik cari jalan memutar\"}]
  Is_Ending: false

=== NODE: ALUR_1_IKUTI_PETUNJUK ===
(Dipicu jika aksi terakhir adalah: \"Ambil peta tua itu\", \"Ikuti burung emas itu\", atau \"Nekat menyeberangi jembatan tua\")
- Alternatif 1:
  Content: \"Petualangan membawamu menemukan keindahan tersembunyi! Kamu berhasil melewati tantangan pertama bersama Nana dan Beni. Sebuah pemandangan lembah hijau yang luar biasa terbentang di depan mata kalian.\"
  Choices: []
  Is_Ending: true

---

[RULES FOR OUTPUT GENERATION]
1. Identifikasi GENRE dan LOKASI yang aktif terlebih dahulu.
2. Identifikasi aksi terakhir user dan tentukan NODE yang aktif di bawah GENRE & LOKASI tersebut.
3. Pilih secara acak (gunakan pengacakan internalmu 1, 2, atau 3) salah satu Alternatif cerita di dalam NODE yang aktif tersebut.
4. Jalankan prinsip fallback jika salah satu genre/node belum terisi lengkap (arahkan ke alternatif terdekat yang tersedia).
5. Sesuaikan teks \"Content\" dengan nama pemain yang dikirimkan di dalam chat jika ada, namun jaga agar alur ceritanya tetap sama persis dengan Alternatif terpilih.
6. Format output HARUS selalu berupa JSON mentah dengan struktur berikut:
{
  \"content\": \"[Teks cerita dari alternatif yang terpilih secara acak]\",
  \"choices\": [
    {\"choice_text\": \"[Pilihan A dari alternatif terpilih]\"},
    {\"choice_text\": \"[Pilihan B dari alternatif terpilih]\"}
  ],
  \"is_ending\": [true/false sesuai alternatif terpilih]
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
