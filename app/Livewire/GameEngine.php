<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserHistory;
use App\Services\StoryGenerator;
use Illuminate\Support\Facades\Http;
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
    public $mountain;
    public $mountains = ['Argopuro', 'Lawu', 'Slamet', 'Merapi', 'Ciremai'];
    public $storyStep = 0;
    public $maxSteps = 6;

    // Authentication States
    public $showAuthModal = false;
    public $authMode = 'login'; // 'login' or 'register'
    public $loginEmail, $loginPassword;
    public $registerName, $registerEmail, $registerPassword;

    public function saveBiodata()
    {
        $this->validate([
            'userName' => 'required|min:3',
            'userAge' => 'required|numeric|min:1',
            'gender' => 'required|in:Laki-laki,Perempuan',
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

        if (empty($this->mountain)) {
            $this->mountain = $this->mountains[array_rand($this->mountains)];
        }

        $aiStory = $generator->generateInitialStory(
            $this->userName, 
            $this->gender, 
            $this->selectedGenre, 
            $this->selectedLocation,
            $this->mountain
        );

        if (!$aiStory || !isset($aiStory['content'])) {
            session()->flash('error', "Gagal menghasilkan cerita AI. Silakan coba lagi.");
            return;
        }

        $this->currentNode = [
            'content' => $aiStory['content'],
            'choices' => $aiStory['choices'],
            'is_ending' => false,
            'mountain' => $this->mountain,
            'node_id' => $this->currentNodeId
        ];

        $rawImagePath = $aiStory['image'] ?? 'wallpaperhorror.jpeg';
        $this->aiImageUrl = asset($rawImagePath); 
        $this->bgImageUrl = $this->aiImageUrl;

        $history = UserHistory::create([
            'user_name' => $this->userName,
            'selected_genre' => $this->selectedGenre,
            'accumulated_story' => $this->currentNode['content'],
            'character_visual' => $this->aiImageUrl,
            'gender' => $this->gender,
            'user_id' => Auth::check() ? Auth::id() : null,
            'is_finished' => false,
            'selected_location' => $this->selectedLocation,
            'current_node_json' => $this->currentNode,
            'story_step' => 1,
        ]);

        $this->historyId = $history->id;
        $this->storyStep = 1;
        
        $history->full_story_json = [[
            'content' => $this->currentNode['content'],
            'image' => $this->aiImageUrl,
            'timestamp' => now()->toDateTimeString()
        ]];
        $history->save();
        $this->step = 'gameplay';
    }



    public function selectChoice($choiceText, StoryGenerator $generator)
    {
        session()->forget('error');
        $history = UserHistory::find($this->historyId);

        // Cari index pilihan dan cek apakah itu ending
        $isEndingChoice = false;
        $nextNodeId = null;
        foreach ($this->currentNode['choices'] ?? [] as $idx => $choice) {
            if ($choice['choice_text'] === $choiceText) {
                $choiceIndex = $idx;
                if (isset($choice['is_ending']) && $choice['is_ending']) {
                    $isEndingChoice = true;
                }
                if (isset($choice['next'])) {
                    $nextNodeId = $choice['next'];
                }
                break;
            }
        }
        
        // Update the existing last page with the chosen option
        if ($history) {
            $storyPages = $history->full_story_json ?: [];
            if (count($storyPages) > 0) {
                $storyPages[count($storyPages) - 1]['choice'] = $choiceText;
            }
            $history->full_story_json = $storyPages;
            $history->save();
        }

        // Incremen step
        $this->storyStep++;

        // Generate next segment via AI
        $nextSegment = $generator->generateNextNode(
            $history->accumulated_story, 
            $choiceText,
            $this->selectedGenre,
            $this->selectedLocation,
            $this->userName,
            $this->mountain,
            $nextNodeId
        );

        if (!$nextSegment || !isset($nextSegment['content'])) {
            session()->flash('error', "Gagal melanjutkan cerita. Silakan coba lagi.");
            return;
        }

        $this->currentNode = [
            'content' => $nextSegment['content'],
            'choices' => $nextSegment['choices'] ?? [],
            'is_ending' => $nextSegment['is_ending'] ?? (empty($nextSegment['choices']) ? true : false),
            'mountain' => $this->mountain,
            'node_id' => $this->currentNodeId
        ];
        
        if (isset($nextSegment['node_id'])) {
            $this->currentNodeId = $nextSegment['node_id'];
        }

        // Hard Cap: Jika sudah melebihi batas, paksa ending
        if ($this->storyStep > $this->maxSteps) {
            $this->currentNode['is_ending'] = true;
            $this->currentNode['choices'] = [];
        }

        // Get the mapped image for the new scene
        $sceneImage = $this->getNextSceneImage($choiceText, $this->currentNode['content']);
        $imageUrl = asset($sceneImage);

        // Append the newly generated content to the history
        if ($history) {
            $storyPages = $history->full_story_json ?: [];

            $storyPages[] = [
                'content' => $this->currentNode['content'],
                'image' => $imageUrl,
                'timestamp' => now()->toDateTimeString()
            ];
            $history->full_story_json = $storyPages;
            $history->accumulated_story = $history->accumulated_story . "\n\n> " . $choiceText . "\n\n" . $this->currentNode['content'];
            $history->character_visual = $imageUrl;
            $history->current_node_json = $this->currentNode;
            $history->story_step = $this->storyStep;
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
            $this->historyId = $history->id;
            $this->userName = $history->user_name;
            $this->gender = $history->gender;
            $this->selectedGenre = $history->selected_genre;
            $this->selectedLocation = $history->selected_location;
            $this->currentNode = $history->current_node_json;
            $this->mountain = $this->currentNode['mountain'] ?? 'Argopuro';
            $this->currentNodeId = $this->currentNode['node_id'] ?? 'NARASI_AWAL';
            $this->storyStep = $history->story_step;
            $this->aiImageUrl = $history->character_visual;
            $this->bgImageUrl = $history->character_visual;
            $this->step = 'gameplay';
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
        foreach ($storyPages as &$page) {
            if (isset($page['image']) && $page['image']) {
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

    private function getNextSceneImage($choiceText, $currentNodeContent = '')
    {
        if ($this->selectedGenre === 'Horror' && $this->selectedLocation === 'Pendakian') {
            $choiceTextLower = strtolower($choiceText);
            
            if (str_contains($choiceTextLower, 'ikuti bisikan') || 
                str_contains($choiceTextLower, 'ikuti suara') || 
                str_contains($choiceTextLower, 'ikuti rintihan')) {
                return 'Horror/Pendakian/alur 1 ikuti bisikan.png';
            }
            if (str_contains($choiceTextLower, 'abaikan') || 
                str_contains($choiceTextLower, 'jangan sentuh') || 
                str_contains($choiceTextLower, 'tinggalkan ransel')) {
                return 'Horror/Pendakian/alur 1.2 menemukan goa.png';
            }
            if (str_contains($choiceTextLower, 'gunakan persediaan') || 
                str_contains($choiceTextLower, 'gunakan biskuit') || 
                str_contains($choiceTextLower, 'gunakan kaleng')) {
                return 'Horror/Pendakian/alur 1.1 mati memakan persediaan.png';
            }
            if (str_contains($choiceTextLower, 'lanjut perjalanan') || 
                str_contains($choiceTextLower, 'kekuatan gaib') || 
                str_contains($choiceTextLower, 'memanfaatkan energi')) {
                return 'Horror/Pendakian/alur 2.2.1.2.1 melawan hantu dan menang.png';
            }
            if (str_contains($choiceTextLower, 'tetap diam') || 
                str_contains($choiceTextLower, 'bertahan di posisi') || 
                str_contains($choiceTextLower, 'berdiam diri')) {
                return 'Horror/Pendakian/alur 1.2 meninggal akibat kelelahan.png';
            }

            $contentLower = strtolower($currentNodeContent);
            if (str_contains($contentLower, 'sar') || str_contains($contentLower, 'selamat') || str_contains($contentLower, 'lolos')) {
                return 'Horror/Pendakian/intro awal.png';
            }
            if (str_contains($contentLower, 'meninggal') || str_contains($contentLower, 'mati') || str_contains($contentLower, 'membusuk') || str_contains($contentLower, 'pasar setan')) {
                return 'Horror/Pendakian/alur 1.2 meninggal akibat kelelahan.png';
            }
        }
        
        if ($this->selectedGenre === 'Horror' && $this->selectedLocation === 'Rumah Sakit') {
            return 'wpkakek.jpeg';
        }
        if ($this->selectedGenre === 'Adventure' && $this->selectedLocation === 'Pulau Terpencil') {
            return 'japan.jpg';
        }
        return 'wallpaperhorror.jpeg';
    }

    public function render()
    {
        return view('livewire.game-engine')->layout('livewire.components.layouts.app');
    }
}