<div class="min-h-screen p-4 md:p-8"
     style="background-image: linear-gradient(180deg, rgba(30,30,25,0.6) 0%, rgba(40,35,30,0.65) 40%, rgba(20,15,10,0.8) 100%), url('{{ $bgImageUrl ?? asset('wallpaperhorror.jpeg') }}'); background-size: cover; background-position: center; background-attachment: fixed; color: #d4c5a9;">

    <div class="max-w-6xl mx-auto">

        {{-- Fog particles background --}}
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute w-[600px] h-[200px] rounded-full top-[20%] -left-[10%] opacity-10"
                 style="background: radial-gradient(ellipse, rgba(217,119,6,0.3), transparent); animation: fog-drift 12s ease-in-out infinite;"></div>
            <div class="absolute w-[500px] h-[150px] rounded-full top-[60%] -right-[10%] opacity-10"
                 style="background: radial-gradient(ellipse, rgba(217,119,6,0.2), transparent); animation: fog-drift 15s ease-in-out infinite reverse;"></div>
        </div>

        <div class="relative z-10">
            {{-- Top Navbar for Auth --}}
            <div class="flex justify-between items-center mb-8 bg-black/40 p-4 rounded-xl border border-amber-900/30 backdrop-blur-sm">
                <div class="text-amber-500 font-bold tracking-widest uppercase text-sm flex items-center gap-3">
                    <img src="{{ asset('images/Gemini_Generated_Image_p9lajmp9lajmp9la.png') }}" alt="NarraTech" class="h-9 w-auto" />
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
                <audio id="backgroundMusic" src="{{ asset('audio/horror-pendakian.mp3') }}" preload="auto" loop></audio>
            </div>

            @if (session()->has('error'))
                <div class="bg-amber-900/30 border border-amber-800/60 p-4 rounded-lg mb-6 text-amber-300 font-bold text-center story-text backdrop-blur-sm">
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
                    <div class="horror-card rounded-xl p-8 md:p-10 w-full max-w-md space-y-5">
                        <div>
                            <label class="block text-xs uppercase text-amber-400/70 tracking-[0.2em] font-sans mb-2">Nama Pemain</label>
                            <input type="text" wire:model="userName" placeholder="Siapa namamu..."
                                   class="horror-input w-full p-4 rounded-lg text-lg">
                        </div>

                        <div>
                            <label class="block text-xs uppercase text-amber-400/70 tracking-[0.2em] font-sans mb-2">Usia</label>
                            <input type="number" wire:model="userAge" placeholder="Berapa usiamu..."
                                   class="horror-input w-full p-4 rounded-lg text-lg">
                        </div>

                        <div>
                            <label class="block text-xs uppercase text-amber-400/70 tracking-[0.2em] font-sans mb-2">Gender</label>
                            <select wire:model="gender" class="horror-input w-full p-4 rounded-lg text-lg">
                                <option value="">Pilih Gender...</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <button wire:click="saveBiodata"
                                class="horror-btn w-full py-4 rounded-lg font-bold uppercase tracking-widest text-lg mt-4
                                       bg-gradient-to-r from-amber-900 to-amber-800 text-amber-100
                                       hover:from-amber-800 hover:to-amber-700 transition-all duration-300
                                       border border-amber-700/50">
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
                            class="mt-10 text-amber-400/40 hover:text-amber-400 transition text-sm story-text">
                        ← Kembali Pilih Genre
                    </button>
                </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- 4. GAMEPLAY --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            @elseif($step === 'gameplay')
                <div class="relative py-4 pb-48"> {{-- Added padding bottom to prevent overlap with choices --}}

                    {{-- Sisi Kiri: Konten Cerita (Fixed di Kiri Atas Layar) --}}
                    <div class="fixed top-32 left-4 md:left-8 w-full max-w-lg lg:max-w-2xl z-30">
                        <div class="horror-card rounded-xl p-6 md:p-8 flex flex-col relative overflow-y-auto custom-scrollbar bg-black/70 backdrop-blur-md border border-amber-900/50 shadow-[0_0_20px_rgba(0,0,0,0.8)]" style="max-height: 65vh;">
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
                            
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-xs uppercase text-amber-500/50 tracking-[0.2em] font-sans flex items-center gap-2">
                                    <span class="text-amber-700">📜</span> Narasi
                                </h3>
                                <div class="flex items-center gap-2">
                                    @for($i = 1; $i <= $maxSteps; $i++)
                                        <div class="w-2.5 h-2.5 rounded-full {{ $i <= $storyStep ? 'bg-amber-600 shadow-[0_0_6px_rgba(217,119,6,0.5)]' : 'bg-amber-900/30' }} transition-all"></div>
                                    @endfor
                                    <span class="text-xs font-mono text-amber-500/40 ml-1">{{ $storyStep }}/{{ $maxSteps }}</span>
                                </div>
                            </div>
                            <div class="flex-1 space-y-4">
                                @foreach(explode("\n", $this->currentNode['content'] ?? '') as $paragraph)
                                    @if(trim($paragraph))
                                        <p class="story-text text-base md:text-lg leading-loose text-[#d4c5a9]"
                                           style="text-indent: 1.5em; line-height: 1.9;">
                                            {{ trim($paragraph) }}
                                        </p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Pilihan Cerita (Fixed di Kiri Bawah Layar) --}}
                    <div class="fixed bottom-4 left-4 md:bottom-8 md:left-8 w-full max-w-sm lg:max-w-md z-40">
                        <div class="space-y-3 horror-card rounded-xl p-5 md:p-6 bg-black/70 backdrop-blur-md border border-amber-900/50 shadow-[0_0_20px_rgba(0,0,0,0.8)]">
                            <div class="mb-4">
                                <p class="text-xs text-amber-400/20 story-text italic mb-2">Setiap pilihan menentukan nasibmu...</p>
                                <h4 class="horror-subtitle text-lg text-amber-500/80 flex items-center gap-2">
                                    <span class="text-amber-700">▸</span> Pilih Jalanmu:
                                </h4>
                            </div>

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

                            @if(count($this->currentNode['choices'] ?? []) > 0 && empty($this->currentNode['is_ending']))
                            <div class="mt-6 pt-4 border-t border-amber-900/30 flex flex-row gap-4">
                                @auth
                                    <button type="button" wire:click="saveAndExit"
                                            class="w-full p-3 rounded-lg text-center
                                                   bg-amber-900/20 hover:bg-amber-900/40
                                                   border border-amber-900/50 hover:border-amber-600
                                                   text-amber-400 hover:text-amber-300
                                                   transition-all duration-300 story-text text-sm flex items-center justify-center gap-2">
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
                                               transition-all duration-300 story-text text-sm flex items-center justify-center gap-2">
                                    <div wire:loading wire:target="exitGame" class="absolute inset-0 bg-black/50 z-10 rounded-lg"></div>
                                    <span class="relative z-20">⏏️</span> 
                                    <span class="relative z-20">Akhiri</span>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                    {{-- Backsound Musik Pendakian Horor --}}
                    
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

                        <p class="story-text text-lg md:text-xl text-[#c4b599] leading-relaxed italic px-4">
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
                
                <h3 class="horror-subtitle text-2xl text-amber-500 mb-6 text-center">
                    {{ $authMode === 'login' ? 'Masuk ke Dunia Gelap' : 'Daftar Ritual Baru' }}
                </h3>

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