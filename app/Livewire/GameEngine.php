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

        $aiStory = $generator->generateInitialStory(
            $this->userName, 
            $this->gender, 
            $this->selectedGenre, 
            $this->selectedLocation
        );

        if (!$aiStory || !isset($aiStory['content'])) {
            session()->flash('error', "Gagal menghasilkan cerita AI. Silakan coba lagi.");
            return;
        }

        $this->currentNode = [
            'content' => $aiStory['content'],
            'choices' => $aiStory['choices'],
            'is_ending' => false
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
        $choiceIndex = 0;
        foreach ($this->currentNode['choices'] ?? [] as $idx => $choice) {
            if ($choice['choice_text'] === $choiceText) {
                $choiceIndex = $idx;
                if (isset($choice['is_ending']) && $choice['is_ending']) {
                    $isEndingChoice = true;
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
        $nextSegment = $generator->generateNextSegment(
            $history->accumulated_story, 
            $choiceText, 
            $isEndingChoice ? $this->maxSteps : $this->storyStep, // Paksa ke maxSteps jika pilihan ending
            $this->maxSteps,
            $this->selectedGenre,
            $this->selectedLocation
        );

        if (!$nextSegment || !isset($nextSegment['content'])) {
            session()->flash('error', "Gagal melanjutkan cerita. Silakan coba lagi.");
            return;
        }

        $this->currentNode = [
            'content' => $nextSegment['content'],
            'choices' => $nextSegment['choices'] ?? [],
            'is_ending' => $nextSegment['is_ending'] ?? (empty($nextSegment['choices']) ? true : false)
        ];

        // Hard Cap: Jika sudah melebihi batas, paksa ending
        if ($this->storyStep > $this->maxSteps) {
            $this->currentNode['is_ending'] = true;
            $this->currentNode['choices'] = [];
        }

        // Append the newly generated content to the history
        if ($history) {
            $storyPages = $history->full_story_json ?: [];
            
            $rawImagePath = $nextSegment['image'] ?? 'wallpaperhorror.jpeg';
            $imageUrl = asset($rawImagePath);

            $storyPages[] = [
                'content' => $this->currentNode['content'],
                'image' => $imageUrl,
                'timestamp' => now()->toDateTimeString()
            ];
            $history->full_story_json = $storyPages;
            $history->accumulated_story = $history->accumulated_story . "\n\n> " . $choiceText . "\n\n" . $this->currentNode['content'];
            $history->current_node_json = $this->currentNode;
            $history->story_step = $this->storyStep;
            $history->save();
        }

        $rawImagePath = $nextSegment['image'] ?? 'wallpaperhorror.jpeg';
        $this->aiImageUrl = asset($rawImagePath);
        $this->bgImageUrl = $this->aiImageUrl;

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
    
    // Ambil semua halaman cerita (sudah ter-cast ke array oleh model)
    $storyPages = $history->full_story_json;
    
    // Jika tidak ada, gunakan accumulated story
    if (!$storyPages) {
        $storyPages = [[
            'content' => $history->accumulated_story,
            'image' => $history->character_visual,
        ]];
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

    public function render()
    {
        return view('livewire.game-engine')->layout('components.layouts.app');
    }
}