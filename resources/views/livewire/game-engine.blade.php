<div class="{{ $step === 'gameplay' ? 'h-screen overflow-hidden flex flex-col p-4 md:p-6' : 'min-h-screen p-4 md:p-8' }}"
     style="background-image: linear-gradient(180deg, rgba(10,10,20,0.72) 0%, rgba(14,15,30,0.78) 40%, rgba(6,7,16,0.94) 100%), url('{{ $bgImageUrl ?? asset('landing page bg2.png') }}'); background-size: cover; background-position: center; background-attachment: fixed; color: #e8d9b5;">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,500;9..144,600;9..144,700;9..144,900&family=Work+Sans:wght@300;400;500;600;700&display=swap');

        /* Scoped overrides — synchronized with landing page (Fraunces + Work Sans) */
        .horror-title {
            font-family: 'Fraunces', serif !important;
            font-weight: 600 !important;
            font-style: italic;
            font-optical-sizing: auto;
            letter-spacing: 0.01em !important;
            background: linear-gradient(135deg, #fdf8ec 0%, #e8d9b5 50%, #c7a568 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.5) !important;
            animation: none !important;
        }

        .horror-subtitle {
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 500 !important;        
            letter-spacing: 0.28em !important;
            text-transform: uppercase;
        }

        .story-text {
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 400 !important;
            line-height: 1.7 !important;
        }

        .horror-card {
            background: linear-gradient(135deg, rgba(22, 24, 40, 0.5) 0%, rgba(8, 9, 18, 0.68) 100%) !important;
            backdrop-filter: blur(12px) saturate(120%);
            -webkit-backdrop-filter: blur(12px) saturate(120%);
            border: 1px solid rgba(212, 169, 96, 0.18) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.55), inset 0 0 20px rgba(212, 169, 96, 0.03) !important;
        }

        .horror-btn {
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 600 !important;
            background: rgba(20, 16, 12, 0.25) !important;
            border: 1px solid rgba(212, 169, 96, 0.12) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.45), inset 0 0 15px rgba(212, 169, 96, 0.02) !important;
            color: rgba(236, 223, 194, 0.8) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            transform: none !important;
        }
        .horror-btn:hover {
            background: rgba(212, 169, 96, 0.12) !important;
            border-color: rgba(212, 169, 96, 0.4) !important;
            color: #ffffff !important;
            box-shadow: 0 12px 30px rgba(212, 169, 96, 0.15), inset 0 0 15px rgba(212, 169, 96, 0.05) !important;
            transform: translateY(-2px) scale(1.01) !important;
        }

        .horror-input {
            font-family: 'Work Sans', sans-serif !important;
            background: rgba(10, 8, 6, 0.85) !important;
            border: 1px solid rgba(212, 169, 96, 0.25) !important;
            color: #ecdfc2 !important;
            transition: all 0.3s ease;
        }
        .horror-input:focus {
            border-color: #c7a568 !important;
            box-shadow: 0 0 12px rgba(212, 169, 96, 0.25) !important;
        }

        /* Scrollbar override */
        ::-webkit-scrollbar-thumb {
            background: #c7a568 !important;
        }

        /* Hide scrollbar for narrative content */
        .custom-scrollbar::-webkit-scrollbar {
            display: none !important;
        }
        .custom-scrollbar {
            -ms-overflow-style: none !important;  /* IE and Edge */
            scrollbar-width: none !important;  /* Firefox */
        }

        .landing-btn {
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 600 !important;
            background: linear-gradient(90deg, #7a5a24 0%, #c7a568 50%, #7a5a24 100%) !important;
            border: 1px solid rgba(212, 169, 96, 0.4) !important;
            box-shadow: 0 10px 30px rgba(199, 165, 104, 0.22), inset 0 0 15px rgba(212, 169, 96, 0.15) !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .landing-btn:hover {
            background: linear-gradient(90deg, #8c6a2c 0%, #d9b87a 50%, #8c6a2c 100%) !important;
            box-shadow: 0 15px 40px rgba(199, 165, 104, 0.4), 0 0 15px rgba(212, 169, 96, 0.3), inset 0 0 15px rgba(212, 169, 96, 0.2) !important;
            transform: translateY(-2px) scale(1.02) !important;
        }

        .landing-btn-subtle {
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 600 !important;
            background: linear-gradient(90deg, #3d2b0e 0%, #7a5a24 50%, #3d2b0e 100%) !important;
            border: 1px solid rgba(212, 169, 96, 0.25) !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4), inset 0 0 10px rgba(212, 169, 96, 0.05) !important;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            color: rgba(236, 223, 194, 0.85) !important;
        }
        .landing-btn-subtle:hover {
            background: linear-gradient(90deg, #4d3714 0%, #8c6a2c 50%, #4d3714 100%) !important;
            border-color: rgba(212, 169, 96, 0.4) !important;
            box-shadow: 0 10px 25px rgba(199, 165, 104, 0.15), inset 0 0 15px rgba(212, 169, 96, 0.1) !important;
            color: #ffffff !important;
            transform: translateY(-1px) scale(1.01) !important;
        }
    </style>

    <div class="max-w-6xl mx-auto w-full {{ $step === 'gameplay' ? 'flex-1 flex flex-col overflow-hidden min-h-0' : '' }}">

        {{-- Fog particles background --}}
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute w-[600px] h-[200px] rounded-full top-[20%] -left-[10%] opacity-10"
                 style="background: radial-gradient(ellipse, rgba(212,169,96,0.3), transparent); animation: fog-drift 12s ease-in-out infinite;"></div>
            <div class="absolute w-[500px] h-[150px] rounded-full top-[60%] -right-[10%] opacity-10"
                 style="background: radial-gradient(ellipse, rgba(212,169,96,0.2), transparent); animation: fog-drift 15s ease-in-out infinite reverse;"></div>
        </div>

        <div class="relative z-10 {{ $step === 'gameplay' ? 'flex-1 flex flex-col overflow-hidden min-h-0' : '' }}">
            {{-- Top Navbar for Auth --}}
            <div class="flex justify-between items-center {{ $step === 'gameplay' ? 'mb-4' : 'mb-8' }} bg-black/40 p-4 rounded-xl border border-amber-900/30 backdrop-blur-sm shrink-0">
                <div class="text-amber-500 font-bold tracking-widest uppercase text-sm flex items-center gap-3">
                    <img src="{{ asset('images/Gemini_Generated_Image_p9lajmp9lajmp9la.png') }}" alt="NarraTech" class="h-6 md:h-7 w-auto transition-all duration-300" />
                    <span class="hidden sm:inline">NarraTech Engine</span>
                </div>
                <div>
                    @auth
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-amber-200/80">Halo, {{ Auth::user()->name }}</span>
                            <button wire:click="logoutUser" class="text-red-400 hover:text-red-300 transition-colors">Keluar</button>
                        </div>
                    @else
                        <div class="flex items-center gap-4 text-sm">
                            <button wire:click="openAuthModal('login')" class="text-amber-400 hover:text-amber-300 transition-colors">Masuk</button>
                            <span class="text-amber-900/50">|</span>
                            <button wire:click="openAuthModal('register')" class="text-amber-500 hover:text-amber-400 transition-colors">Daftar</button>
                        </div>
                    @endauth
                </div>
            </div>

            <div wire:ignore class="fixed top-4 right-4 z-50">
                <button id="musicToggleBtn" type="button"
                        class="inline-flex items-center justify-center w-12 h-12 rounded-full text-xl bg-amber-900/80 text-amber-100 hover:bg-amber-800/90 border border-amber-700 shadow-lg backdrop-blur-sm transition"
                        aria-label="Toggle music"
                        title="Toggle music">
                    🔇
                </button>
                <audio id="backgroundMusic" src="{{ asset('audio/sound fix.mp3') }}" preload="auto" loop></audio>
            </div>

            @if (session()->has('error'))
                <div class="bg-amber-900/30 border border-amber-800/60 p-4 rounded-lg mb-6 text-amber-300 font-bold text-center story-text backdrop-blur-sm shrink-0">
                    ⚠ {{ session('error') }}
                </div>
            @endif

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- 1. BIODATA --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            @if($step === 'biodata')
                <div class="flex flex-col items-center justify-center min-h-[80vh] text-center px-4">

                    {{-- Title --}}
                    <div class="mb-12">
                        <h1 class="horror-title text-4xl md:text-6xl mb-4">NarraTech v1.0</h1>
                        <p class="horror-subtitle text-lg md:text-xl text-amber-400/60 uppercase">Memulai petualangan cerita secara otomatis</p>
                        <div class="w-32 h-[2px] mx-auto mt-4 bg-gradient-to-r from-transparent via-amber-900 to-transparent"></div>
                    </div>

                    {{-- Form --}}
                    <div class="horror-card rounded-xl p-8 md:p-10 w-full max-w-md space-y-5 text-left">
                        @if ($errors->any())
                            <div class="bg-red-950/50 border border-red-500/30 p-4 rounded-lg text-red-300 text-sm font-semibold text-center mb-2">
                                ⚠️ Nama Pemain wajib diisi sebelum memulai petualangan!
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs uppercase text-amber-400/70 tracking-[0.2em] font-sans mb-2">Nama Pemain</label>
                            <input type="text" wire:model="userName" placeholder="Siapa namamu..."
                                   class="horror-input w-full p-4 rounded-lg text-lg">
                            @error('userName') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button wire:click="saveBiodata"
                                class="landing-btn w-full py-4 rounded-lg font-bold uppercase tracking-widest text-lg mt-4 transition-all duration-300">
                            Mulai Petualangan Baru
                        </button>

                        @auth
                            @php
                                $hasActiveSession = \App\Models\UserHistory::where('user_id', Auth::id())->where('is_finished', false)->exists();
                            @endphp
                            @if($hasActiveSession)
                                <div class="mt-4 pt-4 border-t border-amber-900/30">
                                    <p class="text-xs text-amber-500/50 text-center mb-3">Anda memiliki cerita yang belum tamat</p>
                                    <button wire:click="resumeStory"
                                            class="w-full py-3 rounded-lg font-bold tracking-widest text-sm
                                                   bg-amber-900/20 hover:bg-amber-800/40 text-amber-300
                                                   transition-all duration-300 border border-amber-600/30">
                                        Lanjutkan Cerita Sebelumnya
                                    </button>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- 2. PILIH GENRE --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            @elseif($step === 'select_genre')
                <div class="flex flex-col items-center justify-center min-h-[80vh] text-center px-4">
                    <h2 class="horror-subtitle text-3xl md:text-4xl text-amber-400 mb-4">Pilih Alur Ceritamu</h2>
                    <p class="text-sm text-amber-400/40 mb-12 story-text">Setiap jalan membawa takdir yang berbeda...</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl w-full">
                        {{-- Horror --}}
                        <button wire:click="pickGenre('Horror')"
                                class="horror-btn horror-card group rounded-xl p-10 flex flex-col items-center
                                       border border-amber-900/30 hover:border-amber-700/60 transition-all duration-500">
                            <span class="text-7xl mb-5 group-hover:animate-bounce transition-all">🔍</span>
                            <span class="horror-subtitle text-2xl text-amber-500 group-hover:text-amber-400">Horror</span>
                            <span class="text-xs text-amber-400/30 mt-2 story-text">Rahasia menanti di setiap langkah</span>
                        </button>

                        {{-- Adventure --}}
                        <button wire:click="pickGenre('Adventure')"
                                class="horror-btn horror-card group rounded-xl p-10 flex flex-col items-center
                                       border border-amber-900/30 hover:border-amber-700/60 transition-all duration-500">
                            <span class="text-7xl mb-5 group-hover:animate-bounce transition-all">⚔️</span>
                            <span class="horror-subtitle text-2xl text-amber-500 group-hover:text-amber-400">Adventure</span>
                            <span class="text-xs text-amber-400/30 mt-2 story-text">Bahaya mengintai di kegelapan</span>
                        </button>
                    </div>
                </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- 3. PILIH LOKASI --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            @elseif($step === 'select_location')
                <div class="flex flex-col items-center justify-center min-h-[80vh] px-4">
                    <h2 class="horror-subtitle text-3xl text-amber-400 mb-2 text-center">Area Ekspedisi</h2>
                    <p class="text-sm text-amber-400/40 mb-10 story-text text-center">Genre: {{ $selectedGenre }}</p>

                    <div class="w-full max-w-lg space-y-4">
                        @if($selectedGenre === 'Horror')
                            <button type="button" wire:click="startStory('Pendakian')" onclick="window.playBackgroundMusicGesture?.()"
                                    class="horror-btn horror-card group w-full p-6 rounded-xl
                                           border border-amber-900/30 hover:border-amber-600/60
                                           flex justify-between items-center transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <span class="text-4xl">🏔️</span>
                                    <div class="text-left">
                                        <span class="horror-subtitle text-lg text-amber-400 group-hover:text-amber-300 block">Gunung Misterius</span>
                                        <span class="text-xs text-amber-400/30 story-text">Pendakian ke puncak yang terkutuk</span>
                                    </div>
                                </div>
                                <span class="text-2xl text-amber-700 group-hover:text-amber-500 group-hover:translate-x-2 transition-all">→</span>
                            </button>

                            <button type="button" wire:click="startStory('Rumah Sakit')" onclick="window.playBackgroundMusicGesture?.()"
                                    class="horror-btn horror-card group w-full p-6 rounded-xl
                                           border border-amber-900/30 hover:border-amber-600/60
                                           flex justify-between items-center transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <span class="text-4xl">🏥</span>
                                    <div class="text-left">
                                        <span class="horror-subtitle text-lg text-amber-400 group-hover:text-amber-300 block">Rumah Sakit Kosong</span>
                                        <span class="text-xs text-amber-400/30 story-text">Gema tangisan di lorong gelap</span>
                                    </div>
                                </div>
                                <span class="text-2xl text-amber-700 group-hover:text-amber-500 group-hover:translate-x-2 transition-all">→</span>
                            </button>
                        @elseif($selectedGenre === 'Adventure')
                            <button type="button" wire:click="startStory('Pulau Terpencil')" onclick="window.playBackgroundMusicGesture?.()"
                                    class="horror-btn horror-card group w-full p-6 rounded-xl
                                           border border-amber-900/30 hover:border-amber-600/60
                                           flex justify-between items-center transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <span class="text-4xl">🏝️</span>
                                    <div class="text-left">
                                        <span class="horror-subtitle text-lg text-amber-400 group-hover:text-amber-300 block">Pulau Tanpa Penghuni</span>
                                        <span class="text-xs text-amber-400/30 story-text">Bertahan hidup di alam liar yang buas</span>
                                    </div>
                                </div>
                                <span class="text-2xl text-amber-700 group-hover:text-amber-500 group-hover:translate-x-2 transition-all">→</span>
                            </button>

                            <button type="button" wire:click="startStory('Gua Misterius')" onclick="window.playBackgroundMusicGesture?.()"
                                    class="horror-btn horror-card group w-full p-6 rounded-xl
                                           border border-amber-900/30 hover:border-amber-600/60
                                           flex justify-between items-center transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <span class="text-4xl">🕳️</span>
                                    <div class="text-left">
                                        <span class="horror-subtitle text-lg text-amber-400 group-hover:text-amber-300 block">Gua Bawah Tanah</span>
                                        <span class="text-xs text-amber-400/30 story-text">Menjelajahi kedalaman yang belum terjamah</span>
                                    </div>
                                </div>
                                <span class="text-2xl text-amber-700 group-hover:text-amber-500 group-hover:translate-x-2 transition-all">→</span>
                            </button>
                        @endif
                    </div>

                    <button wire:click="$set('step', 'select_genre')"
                            class="landing-btn-subtle mt-10 px-6 py-3 rounded-lg text-sm tracking-wider uppercase">
                        ← Kembali Pilih Genre
                    </button>
                </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- 4. GAMEPLAY --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @elseif($step === 'gameplay')
                @php
                    $storyText   = trim($this->currentNode['content'] ?? '');
                    // Split into paragraphs by one or more newlines, preserving structure
                    $rawParagraphs = preg_split('/\n+/', $storyText);
                    $rawParagraphs = array_values(array_filter(
                        array_map('trim', $rawParagraphs),
                        fn($p) => $p !== ''
                    ));

                    // Count total words and build displayParagraphs with 250-word cap
                    $totalWords      = 0;
                    $displayParagraphs = [];
                    foreach ($rawParagraphs as $para) {
                        $paraWords = preg_split('/\s+/', strip_tags($para), -1, PREG_SPLIT_NO_EMPTY);
                        $available = 250 - $totalWords;
                        if ($available <= 0) break;
                        if (count($paraWords) <= $available) {
                            $displayParagraphs[] = $para;
                            $totalWords += count($paraWords);
                        } else {
                            $displayParagraphs[] = implode(' ', array_slice($paraWords, 0, $available)) . '…';
                            $totalWords = 250;
                            break;
                        }
                    }
                    $wordCount = $totalWords;
                @endphp

                <div class="max-w-2xl mr-auto flex-1 flex flex-col gap-4 overflow-hidden min-h-0 w-full">

                    {{-- Konten Cerita --}}
                    <div class="z-30 flex-1 min-h-0">
                        <div class="horror-card rounded-xl p-4 md:p-5 flex flex-col h-full relative backdrop-blur-md overflow-hidden">
                            {{-- Loading Overlay for AI Generation --}}
                            <div wire:loading wire:target="startStory, selectChoice"
                                 class="absolute inset-0 bg-black/80 backdrop-blur-sm flex flex-col items-center justify-center z-20 rounded-xl">
                                <div class="relative">
                                    <div class="w-14 h-14 border-4 border-amber-900 border-t-amber-500 rounded-full animate-spin"></div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-xl">👁️</span>
                                    </div>
                                </div>
                                <p class="mt-4 text-amber-400/60 font-mono text-xs uppercase tracking-[0.3em] animate-pulse">
                                    Membisikkan cerita...
                                </p>
                            </div>

                            <div class="flex flex-col gap-4 mb-4 shrink-0">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <h3 class="text-xs uppercase text-amber-500/50 tracking-[0.2em] font-sans flex items-center gap-2">
                                            <span class="text-amber-700">📜</span> Narasi
                                        </h3>
                                        <p class="text-sm text-amber-400/40 story-text mt-1">Narasi ditampilkan di sini; pilih jalan setelah selesai membaca.</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-mono text-amber-500/40">{{ $wordCount }} kata</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 min-h-0">
                                @foreach($displayParagraphs as $paragraph)
                                    <p class="story-text text-base md:text-lg text-[#ecdfc2] mb-4"
                                       style="text-align: justify; text-indent: 1.5em; line-height: 1.9; hyphens: auto;">
                                        {{ $paragraph }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Pilihan Cerita --}}
                    <div class="z-40 shrink-0">
                        <div class="space-y-2 horror-card rounded-xl p-3 md:p-4 backdrop-blur-md">
                            <div class="mb-4">
                                <p class="text-xs text-amber-400/20 story-text italic mb-2">Setiap pilihan menentukan nasibmu...</p>
                                <h4 class="horror-subtitle text-lg text-amber-500/80 flex items-center gap-2">
                                    <span class="text-amber-700">▸</span> Pilih Jalanmu:
                                </h4>
                            </div>

                            <div class="space-y-3">
                                @foreach($this->currentNode['choices'] ?? [] as $choice)
                                    <button wire:click="selectChoice('{{ addslashes($choice['choice_text']) }}')"
                                            class="horror-btn w-full p-4 rounded-lg text-left flex justify-between items-center
                                                   bg-gradient-to-r from-[#1a0a0a] to-[#0d0a15]
                                                   border border-amber-900/30 hover:border-amber-600/60
                                                   text-amber-200/80 hover:text-amber-100
                                                   transition-all duration-300 group relative overflow-hidden">
                                        <div wire:loading wire:target="selectChoice" class="absolute inset-0 bg-black/50 z-10"></div>
                                        <span class="story-text text-base flex items-center gap-3 relative z-20">
                                            <span class="text-amber-700 group-hover:text-amber-500 transition text-lg">✦</span>
                                            {{ $choice['choice_text'] }}
                                        </span>
                                        <span class="text-amber-800 group-hover:text-amber-500 group-hover:translate-x-1 transition-all relative z-20">→</span>
                                    </button>
                                @endforeach
                            </div>

                            @if(count($this->currentNode['choices'] ?? []) > 0 && empty($this->currentNode['is_ending']))
                                <div class="mt-6 pt-4 border-t border-amber-900/30 flex flex-col gap-3 lg:flex-row">
                                    @auth
                                        <button type="button" wire:click="saveAndExit"
                                                class="w-full p-3 rounded-lg text-center
                                                       bg-amber-900/20 hover:bg-amber-900/40
                                                       border border-amber-900/50 hover:border-amber-600
                                                       text-amber-400 hover:text-amber-300
                                                       transition-all duration-300 story-text text-sm flex items-center justify-center gap-2 relative overflow-hidden">
                                            <div wire:loading wire:target="saveAndExit" class="absolute inset-0 bg-black/50 z-10 rounded-lg"></div>
                                            <span class="relative z-20">💾</span>
                                            <span class="relative z-20">Simpan</span>
                                        </button>
                                    @endauth
                                    <button type="button" wire:click="exitGame"
                                            class="w-full p-3 rounded-lg text-center
                                                   bg-red-900/20 hover:bg-red-900/40
                                                   border border-red-900/50 hover:border-red-600
                                                   text-red-400 hover:text-red-300
                                                   transition-all duration-300 story-text text-sm flex items-center justify-center gap-2 relative overflow-hidden">
                                        <div wire:loading wire:target="exitGame" class="absolute inset-0 bg-black/50 z-10 rounded-lg"></div>
                                        <span class="relative z-20">⏏️</span>
                                        <span class="relative z-20">Akhiri</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- 5. ENDING --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            @elseif($step === 'ending')
                <div class="flex flex-col items-center justify-center min-h-[80vh] px-4">
                    <div class="horror-card rounded-2xl p-10 md:p-16 max-w-2xl w-full text-center space-y-8
                                border border-amber-900/40"
                         style="animation: pulse-blood 3s ease-in-out infinite;">

                        <div class="relative inline-block blood-drip">
                            <h1 class="horror-title text-5xl md:text-7xl">TAMAT</h1>
                        </div>

                        <div class="w-24 h-[1px] mx-auto bg-gradient-to-r from-transparent via-amber-700 to-transparent"></div>

                        <p class="story-text text-lg md:text-xl text-[#ecdfc2] leading-relaxed italic px-4">
                            {{ $this->currentNode['content'] ?? '' }}
                        </p>

                        <div class="w-24 h-[1px] mx-auto bg-gradient-to-r from-transparent via-amber-700 to-transparent"></div>

                        <div class="flex flex-col gap-4 items-center pt-4">
                            <button wire:click="exportToPdf"
                                    class="horror-btn px-10 py-4 rounded-lg font-bold uppercase tracking-widest text-sm
                                           bg-gradient-to-r from-amber-900 to-amber-800 text-amber-100
                                           hover:from-amber-800 hover:to-amber-700
                                           border border-amber-700/50 transition-all duration-300">
                                📜 Unduh Ringkasan (PDF)
                            </button>

                            <button onclick="window.location.reload()"
                                    class="horror-btn px-8 py-3 rounded-lg font-bold uppercase tracking-wider text-sm
                                           bg-transparent text-amber-400/60 hover:text-amber-300
                                           border border-amber-900/30 hover:border-amber-700/50
                                           transition-all duration-300">
                                🔄 Mulai Ulang Petualangan
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Auth Modal --}}
    @if($showAuthModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="horror-card rounded-xl p-8 max-w-md w-full relative border border-amber-900/50 bg-[#140f0a]">
                <button wire:click="closeAuthModal" class="absolute top-4 right-4 text-amber-500/50 hover:text-amber-400 text-xl">&times;</button>
                
                <div class="flex flex-col items-center justify-center mb-6">
                    <img src="{{ asset('images/Gemini_Generated_Image_p9lajmp9lajmp9la.png') }}" alt="NarraTech" class="h-8 md:h-10 w-auto mb-3" />
                    <h3 class="horror-subtitle text-2xl text-amber-500 text-center">
                        {{ $authMode === 'login' ? 'Masuk ke Akun Anda' : 'Daftar Ritual Baru' }}
                    </h3>
                </div>

                @if($authMode === 'login')
                    <form wire:submit.prevent="loginUser" class="space-y-4">
                        <div>
                            <label class="block text-amber-500/80 text-sm mb-1 story-text">Email</label>
                            <input type="email" wire:model="loginEmail" class="w-full bg-[#1a1410] border border-amber-900/30 rounded px-4 py-2 text-amber-100 focus:outline-none focus:border-amber-600">
                            @error('loginEmail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-amber-500/80 text-sm mb-1 story-text">Password</label>
                            <input type="password" wire:model="loginPassword" class="w-full bg-[#1a1410] border border-amber-900/30 rounded px-4 py-2 text-amber-100 focus:outline-none focus:border-amber-600">
                            @error('loginPassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-amber-900/40 hover:bg-amber-800/60 border border-amber-700/50 text-amber-200 py-3 rounded-lg transition-all mt-6 horror-subtitle">
                            Masuk
                        </button>
                        <p class="text-center text-xs text-amber-500/50 mt-4 story-text">
                            Belum punya akun? <a href="#" wire:click.prevent="openAuthModal('register')" class="text-amber-400 hover:underline">Daftar di sini</a>
                        </p>
                    </form>
                @else
                    <form wire:submit.prevent="registerUser" class="space-y-4">
                        <div>
                            <label class="block text-amber-500/80 text-sm mb-1 story-text">Nama</label>
                            <input type="text" wire:model="registerName" class="w-full bg-[#1a1410] border border-amber-900/30 rounded px-4 py-2 text-amber-100 focus:outline-none focus:border-amber-600">
                            @error('registerName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-amber-500/80 text-sm mb-1 story-text">Email</label>
                            <input type="email" wire:model="registerEmail" class="w-full bg-[#1a1410] border border-amber-900/30 rounded px-4 py-2 text-amber-100 focus:outline-none focus:border-amber-600">
                            @error('registerEmail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-amber-500/80 text-sm mb-1 story-text">Password</label>
                            <input type="password" wire:model="registerPassword" class="w-full bg-[#1a1410] border border-amber-900/30 rounded px-4 py-2 text-amber-100 focus:outline-none focus:border-amber-600">
                            @error('registerPassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-amber-900/40 hover:bg-amber-800/60 border border-amber-700/50 text-amber-200 py-3 rounded-lg transition-all mt-6 horror-subtitle">
                            Daftar
                        </button>
                        <p class="text-center text-xs text-amber-500/50 mt-4 story-text">
                            Sudah punya akun? <a href="#" wire:click.prevent="openAuthModal('login')" class="text-amber-400 hover:underline">Masuk di sini</a>
                        </p>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>