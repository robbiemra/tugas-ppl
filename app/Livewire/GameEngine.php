<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserHistory;
use App\Services\StoryGenerator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class GameEngine extends Component
{
    public $step = 'biodata'; 
    public $userName, $userAge, $gender; 
    public $selectedGenre, $selectedLocation;
    public $currentNode = [], $historyId, $aiImageUrl, $bgImageUrl;
    public $currentNodeId = 'NARASI_AWAL';
    public $locationName = 'Lokasi Misterius'; // Nama spesifik lokasi dari input user
    public $storyStep = 0;
    public $maxSteps = 8;

    // Authentication States
    public $showAuthModal = false;
    public $authMode = 'login'; // 'login' or 'register'
    public $loginEmail, $loginPassword;
    public $registerName, $registerEmail, $registerPassword;

    public function saveBiodata()
    {
        $this->validate([
            'userName' => 'required|min:3',
        ], [
            'userName.required' => 'Nama Pemain harus diisi.',
            'userName.min' => 'Nama Pemain minimal harus 3 karakter.',
        ]);
        $this->step = 'select_genre';
    }

    public function openAuthModal($mode = 'login')
    {
        $this->authMode = $mode;
        $this->showAuthModal = true;
        $this->resetValidation();
    }

    public function closeAuthModal()
    {
        $this->showAuthModal = false;
        $this->reset(['loginEmail', 'loginPassword', 'registerName', 'registerEmail', 'registerPassword']);
        $this->resetValidation();
    }

    public function loginUser()
    {
        $this->validate([
            'loginEmail' => 'required|email',
            'loginPassword' => 'required',
        ], [
            'loginEmail.required' => 'Email harus diisi.',
            'loginEmail.email' => 'Format email tidak valid.',
            'loginPassword.required' => 'Password harus diisi.',
        ]);

        if (Auth::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword])) {
            $this->closeAuthModal();
            $this->userName = Auth::user()->name;
        } else {
            $this->addError('loginEmail', 'Kredensial tidak valid.');
        }
    }

    public function registerUser()
    {
        $this->validate([
            'registerName' => 'required|string|max:255',
            'registerEmail' => 'required|string|email|max:255|unique:users,email',
            'registerPassword' => 'required|string|min:8',
        ], [
            'registerName.required' => 'Nama harus diisi.',
            'registerEmail.required' => 'Email harus diisi.',
            'registerEmail.email' => 'Format email tidak valid.',
            'registerEmail.unique' => 'Email sudah terdaftar.',
            'registerPassword.required' => 'Password harus diisi.',
            'registerPassword.min' => 'Password minimal harus 8 karakter.',
        ]);

        $user = User::create([
            'name' => $this->registerName,
            'email' => $this->registerEmail,
            'password' => Hash::make($this->registerPassword),
        ]);

        Auth::login($user);
        $this->closeAuthModal();
        $this->userName = Auth::user()->name;
    }

    public function logoutUser()
    {
        Auth::logout();
        $this->step = 'biodata';
        $this->reset(['historyId', 'currentNode', 'storyStep', 'selectedGenre', 'selectedLocation']);
    }

    public function pickGenre($genre)
    {
        $this->selectedGenre = $genre;
        $this->step = 'select_location';
    }

    public function startStory($location, StoryGenerator $generator)
    {
        $this->selectedLocation = $location;
        $this->currentNodeId = 'NARASI_AWAL';

        // Tentukan locationName berdasarkan genre dan lokasi yang dipilih
        $this->locationName = $this->resolveLocationName($this->selectedGenre, $location);

        // Pilih variasi secara acak untuk setiap sesi baru
        $variationIndex = rand(0, 4);

        $story = $generator->generateInitialStory(
            $this->userName,
            $this->gender,
            $this->selectedGenre,
            $this->selectedLocation,
            $this->locationName,
            $variationIndex
        );

        if (!$story || !isset($story['content'])) {
            session()->flash('error', "Gagal memuat cerita. Pastikan file database JSON tersedia.");
            return;
        }

        $this->currentNodeId = $story['node_id'] ?? 'NARASI_AWAL';

        $this->currentNode = [
            'content'     => $story['content'],
            'choices'     => $story['choices'],
            'is_ending'   => false,
            'location_name' => $this->locationName,
            'node_id'     => $this->currentNodeId,
        ];

        $rawImagePath = $story['image'] ?? 'landing page bg2.png';
        $this->aiImageUrl = asset($rawImagePath);
        $this->bgImageUrl = $this->aiImageUrl;

        $history = UserHistory::create([
            'user_name'        => $this->userName,
            'selected_genre'   => $this->selectedGenre,
            'accumulated_story'=> $this->currentNode['content'],
            'character_visual' => $this->aiImageUrl,
            'gender'           => $this->gender,
            'user_id'          => Auth::check() ? Auth::id() : null,
            'is_finished'      => false,
            'selected_location'=> $this->selectedLocation,
            'current_node_json'=> $this->currentNode,
            'story_step'       => 1,
        ]);

        $this->historyId = $history->id;
        $this->storyStep = 1;

        $history->full_story_json = [[
            'content'   => $this->currentNode['content'],
            'image'     => $this->aiImageUrl,
            'timestamp' => now()->toDateTimeString()
        ]];
        $history->save();
        $this->step = 'gameplay';
    }



    public function selectChoice($choiceText, StoryGenerator $generator)
    {
        session()->forget('error');
        $history = UserHistory::find($this->historyId);

        // Ambil next_node ID dari pilihan yang dipilih pemain
        $nextNodeId = null;
        foreach ($this->currentNode['choices'] ?? [] as $choice) {
            if ($choice['choice_text'] === $choiceText) {
                $nextNodeId = $choice['next_node'] ?? null;
                break;
            }
        }

        // Tandai pilihan di halaman cerita terakhir
        if ($history) {
            $storyPages = $history->full_story_json ?: [];
            if (count($storyPages) > 0) {
                $storyPages[count($storyPages) - 1]['choice'] = $choiceText;
            }
            $history->full_story_json = $storyPages;
            $history->save();
        }

        $this->storyStep++;

        // Jika tidak ada next_node, paksa ending dengan pesan darurat
        if (!$nextNodeId) {
            $this->currentNode['is_ending'] = true;
            $this->currentNode['choices'] = [];
            $this->step = 'ending';
            return;
        }

        // Pilih variasi secara acak untuk setiap scene
        $variationIndex = rand(0, 4);

        // Muat scene berikutnya langsung dari file JSON berdasarkan next_node
        $nextSegment = $generator->generateNextNode(
            $this->selectedGenre,
            $this->selectedLocation,
            $this->userName,
            $this->locationName,
            $nextNodeId,
            $variationIndex
        );

        if (!$nextSegment || !isset($nextSegment['content'])) {
            session()->flash('error', "Gagal memuat scene [{$nextNodeId}]. Periksa file JSON di storage/app/.");
            return;
        }

        $this->currentNodeId = $nextSegment['node_id'] ?? $nextNodeId;

        $this->currentNode = [
            'content'       => $nextSegment['content'],
            'choices'       => $nextSegment['choices'] ?? [],
            'is_ending'     => $nextSegment['is_ending'] ?? (empty($nextSegment['choices']) ? true : false),
            'location_name' => $this->locationName,
            'node_id'       => $this->currentNodeId,
            'image'         => $nextSegment['image'] ?? null,
        ];

        // Hard Cap: paksa ending jika step melampaui batas maksimal
        if ($this->storyStep > $this->maxSteps) {
            $this->currentNode['is_ending'] = true;
            $this->currentNode['choices'] = [];
        }

        $sceneImage = $nextSegment['image'] ?? $this->getNextSceneImage($this->selectedGenre, $this->selectedLocation, $this->currentNodeId);
        $imageUrl = asset($sceneImage);

        if ($history) {
            $storyPages = $history->full_story_json ?: [];
            $storyPages[] = [
                'content'   => $this->currentNode['content'],
                'image'     => $imageUrl,
                'timestamp' => now()->toDateTimeString()
            ];
            $history->full_story_json   = $storyPages;
            $history->accumulated_story = $history->accumulated_story . "\n\n> " . $choiceText . "\n\n" . $this->currentNode['content'];
            $history->character_visual  = $imageUrl;
            $history->current_node_json = $this->currentNode;
            $history->story_step        = $this->storyStep;
            $history->save();
        }

        $this->aiImageUrl = $imageUrl;
        $this->bgImageUrl = $imageUrl;

        if ($this->currentNode['is_ending']) {
            $this->step = 'ending';
        }
    }

    public function exitGame()
    {
        $this->currentNode['content'] = $this->currentNode['content'] . "\n\n[Pemain memilih untuk mengakhiri petualangan lebih awal dan kembali ke tempat aman.]";
        $this->currentNode['is_ending'] = true;
        $this->currentNode['choices'] = [];
        $this->step = 'ending';
        
        $history = UserHistory::find($this->historyId);
        if ($history) {
            $storyPages = $history->full_story_json ?: [];
            if (count($storyPages) > 0) {
                // Tandai bahwa pemain keluar secara paksa
                $storyPages[count($storyPages) - 1]['content'] = $this->currentNode['content'];
                $storyPages[count($storyPages) - 1]['choice'] = 'Akhiri Cerita';
            }
            $history->full_story_json = $storyPages;
            $history->is_finished = true;
            $history->save();
        }
    }

    public function saveAndExit()
    {
        // Menyimpan game sementara bagi user yang login
        if (Auth::check()) {
            $this->step = 'biodata';
            $this->reset(['historyId', 'currentNode', 'storyStep', 'selectedGenre', 'selectedLocation']);
        }
    }

    public function resumeStory()
    {
        if (!Auth::check()) return;

        $history = UserHistory::where('user_id', Auth::id())
                    ->where('is_finished', false)
                    ->latest()
                    ->first();

        if ($history) {
            $this->historyId     = $history->id;
            $this->userName      = $history->user_name;
            $this->gender        = $history->gender;
            $this->selectedGenre = $history->selected_genre;
            $this->selectedLocation = $history->selected_location;
            $this->currentNode   = $history->current_node_json;
            $this->locationName  = $this->currentNode['location_name'] ?? $this->resolveLocationName($this->selectedGenre, $this->selectedLocation);
            $this->currentNodeId = $this->currentNode['node_id'] ?? 'NARASI_AWAL';
            $this->storyStep     = $history->story_step;
            $this->aiImageUrl    = $history->character_visual;
            $this->bgImageUrl    = $history->character_visual;
            $this->step          = 'gameplay';
        }
    }

    // Fungsi untuk Export ke PDF
    public function exportToPdf()
    {
        $history = UserHistory::find($this->historyId);
        if (!$history) {
            session()->flash('error', 'Riwayat perjalanan tidak ditemukan.');
            return;
        }
        
        $storyPages = $history->full_story_json ?: [];
        
        if (empty($storyPages)) {
            $storyPages = [[
                'content' => $history->accumulated_story,
                'image' => $history->character_visual,
            ]];
        }

        // Convert image URLs to base64 so DomPDF can render them locally without networking issues
        // Hanya lakukan jika ekstensi GD tersedia untuk mencegah error "PHP GD extension is required"
        $gdInstalled = extension_loaded('gd') && function_exists('imagecreatefrompng');
        
        foreach ($storyPages as &$page) {
            if ($gdInstalled && isset($page['image']) && $page['image']) {
                $urlPath = parse_url($page['image'], PHP_URL_PATH);
                $relativeFilePath = ltrim($urlPath, '/');
                
                if (preg_match('/public\/(.*)$/', $relativeFilePath, $matches)) {
                    $relativeFilePath = $matches[1];
                }
                
                $localPath = public_path($relativeFilePath);
                
                if (file_exists($localPath)) {
                    $type = pathinfo($localPath, PATHINFO_EXTENSION);
                    $data = @file_get_contents($localPath);
                    if ($data !== false) {
                        $page['image_base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } else {
                        $page['image_base64'] = null;
                    }
                } else {
                    $page['image_base64'] = null;
                }
            } else {
                $page['image_base64'] = null;
                $page['image'] = null; // Cegah pemanggilan image langsung di view blade jika GD mati
            }
        }

        $pdf = PDF::loadView('pdf.story-summary', [
            'history' => $history,
            'storyPages' => $storyPages
        ]);
        
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'ringkasan-cerita-' . strtolower(str_replace(' ', '-', $history->user_name)) . '.pdf'
        );
    }

    /**
     * Memetakan gambar scene berdasarkan genre, lokasi, dan node_id yang aktif.
     */
    private function getNextSceneImage(string $genre, string $location, string $nodeId = ''): string
    {
        $genreLower = strtolower($genre);
        $locClean   = strtolower(str_replace(' ', '', $location));
        $nodeUpper  = strtoupper($nodeId);

        if ($genreLower === 'horror') {
            if (str_contains($locClean, 'pendakian') || str_contains($locClean, 'gunung')) {
                // Pemetaan gambar berdasarkan node aktif untuk skenario Horror Pendakian
                if (str_contains($nodeUpper, 'ENDING') && str_contains($nodeUpper, 'SELAMAT')) {
                    return 'Horror/Pendakian/alur 2.2.1.2.1 melawan hantu dan menang.png';
                }
                if (str_contains($nodeUpper, 'ENDING') && (str_contains($nodeUpper, 'MATI') || str_contains($nodeUpper, 'TRAUMA'))) {
                    return 'Horror/Pendakian/alur 1.2 meninggal akibat kelelahan.png';
                }
                if (str_contains($nodeUpper, 'BISIKAN') || str_contains($nodeUpper, 'ALUR_1')) {
                    return 'Horror/Pendakian/alur 1 ikuti bisikan.png';
                }
                if (str_contains($nodeUpper, 'PETI') || str_contains($nodeUpper, 'ALUR_2')) {
                    return 'Horror/Pendakian/alur 1.2 menemukan goa.png';
                }
                return 'Horror/Pendakian/intro awal.png';
            }
            if (str_contains($locClean, 'rumahsakit') || str_contains($locClean, 'rs')) {
                return 'Horror/rumahSakit/landing page rumah sakit.png';
            }
        }

        if ($genreLower === 'adventure') {
            if (str_contains($locClean, 'pulau')) {
                return 'Adventure/Pulau/pulau terpencil ( awal ).png';
            }
            if (str_contains($locClean, 'gua') || str_contains($locClean, 'cave')) {
                return 'Adventure/Gua/landing page gua misterius.png';
            }
        }

        return 'landing page bg2.png';
    }

    /**
     * Menentukan nama lokasi spesifik berdasarkan genre dan kategori lokasi.
     */
    private function resolveLocationName(string $genre, string $location): string
    {
        $genreLower = strtolower($genre);
        $locClean   = strtolower(str_replace(' ', '', $location));

        $horrorHikeNames    = ['Gunung Slamet', 'Gunung Lawu', 'Gunung Argopuro', 'Gunung Merapi', 'Gunung Ciremai'];
        $horrorHospitalNames = ['RS Kariadi', 'RS Parikesit', 'RS Sitanala', 'RS Sumber Waras', 'RS Cipto'];
        $adventureIslandNames = ['Pulau Kumala', 'Pulau Bintan', 'Pulau Morotai', 'Pulau Weh', 'Pulau Nias'];
        $adventureCaveNames  = ['Gua Jatijajar', 'Gua Pindul', 'Gua Lawa', 'Gua Petruk', 'Gua Surupan'];

        if ($genreLower === 'horror') {
            if (str_contains($locClean, 'pendakian') || str_contains($locClean, 'gunung')) {
                return $horrorHikeNames[array_rand($horrorHikeNames)];
            }
            return $horrorHospitalNames[array_rand($horrorHospitalNames)];
        }

        if ($genreLower === 'adventure') {
            if (str_contains($locClean, 'gua') || str_contains($locClean, 'cave')) {
                return $adventureCaveNames[array_rand($adventureCaveNames)];
            }
            return $adventureIslandNames[array_rand($adventureIslandNames)];
        }

        return 'Lokasi Misterius';
    }

    public function render()
    {
        return view('livewire.game-engine')->layout('livewire.components.layouts.app');
    }
}