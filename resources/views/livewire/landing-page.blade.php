<div class="min-h-screen p-6 md:p-12 flex flex-col justify-between relative overflow-hidden"
     style="background-image: linear-gradient(180deg, rgba(15,10,5,0.75) 0%, rgba(20,15,10,0.8) 40%, rgba(10,5,2,0.95) 100%), url('{{ asset('wallpaperhorror.jpeg') }}'); background-size: cover; background-position: center; background-attachment: fixed; color: #d4c5a9;">

    {{-- Fog particles background --}}
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute w-[600px] h-[200px] rounded-full top-[20%] -left-[10%] opacity-10"
             style="background: radial-gradient(ellipse, rgba(217,119,6,0.3), transparent); animation: fog-drift 12s ease-in-out infinite;"></div>
        <div class="absolute w-[500px] h-[150px] rounded-full top-[60%] -right-[10%] opacity-10"
             style="background: radial-gradient(ellipse, rgba(217,119,6,0.2), transparent); animation: fog-drift 15s ease-in-out infinite reverse;"></div>
    </div>

    <div class="relative z-10 w-full max-w-6xl mx-auto flex flex-col justify-between min-h-[85vh]">
        {{-- Header / Logo --}}
        <header class="flex justify-center items-center py-6 mb-4">
            <div class="flex flex-col items-center gap-2">
                <img src="{{ asset('images/Gemini_Generated_Image_p9lajmp9lajmp9la.png') }}" alt="NarraTech Logo" class="h-20 md:h-28 w-auto filter drop-shadow-[0_0_15px_rgba(217,119,6,0.4)] transition-transform duration-500 hover:scale-105" />
                <span class="text-xs uppercase text-amber-500/50 tracking-[0.3em] font-sans">AI-Powered Interactive Engine</span>
            </div>
        </header>

        {{-- Main Hero --}}
        <main class="flex-grow flex flex-col items-center justify-center text-center px-4 max-w-4xl mx-auto my-auto">
            <div class="space-y-6 mb-8">
                <h1 class="horror-title text-5xl md:text-8xl tracking-widest leading-none">
                    NARRATECH
                </h1>
                <p class="horror-subtitle text-lg md:text-2xl text-amber-500 tracking-[0.2em] uppercase">
                    Pilih Takdirmu. Hadapi Ketakutanmu.
                </p>
                <div class="w-48 h-[2px] mx-auto bg-gradient-to-r from-transparent via-amber-700/50 to-transparent"></div>
            </div>

            <div class="horror-card max-w-2xl rounded-2xl p-6 md:p-8 backdrop-blur-md border border-amber-900/40 shadow-[0_0_30px_rgba(0,0,0,0.8)] mb-8">
                <p class="story-text text-base md:text-lg leading-relaxed text-[#c4b599]">
                    Setiap pilihan adalah benang merah takdirmu. Hadapi ketakutan di Rumah Sakit tua, pecahkan misteri Pimon, dan tentukan akhir ceritamu sendiri.
                </p>
            </div>

            {{-- Genre Preview Showcase --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-3xl w-full mb-10 text-left">
                <!-- Horror Card -->
                <div class="horror-card rounded-xl p-5 border border-amber-900/20 bg-black/40 backdrop-blur-sm flex items-start gap-4">
                    <span class="text-3xl p-3 bg-red-950/40 rounded-lg border border-red-900/30">👁️</span>
                    <div>
                        <h3 class="horror-subtitle text-sm text-red-500 tracking-wider">GENRE: HORROR</h3>
                        <p class="story-text text-xs text-amber-400/60 mt-1">Gunung Misterius & Rumah Sakit Kosong</p>
                        <p class="text-[11px] text-amber-200/40 mt-1 leading-relaxed">Rasakan ketegangan dengan alur cerita psikologis yang menegangkan dan atmosfer menyeramkan.</p>
                    </div>
                </div>

                <!-- Adventure Card -->
                <div class="horror-card rounded-xl p-5 border border-amber-900/20 bg-black/40 backdrop-blur-sm flex items-start gap-4">
                    <span class="text-3xl p-3 bg-amber-950/40 rounded-lg border border-amber-900/30">⚔️</span>
                    <div>
                        <h3 class="horror-subtitle text-sm text-amber-500 tracking-wider">GENRE: ADVENTURE</h3>
                        <p class="story-text text-xs text-amber-400/60 mt-1">Pulau Terpencil & Gua Bawah Tanah</p>
                        <p class="text-[11px] text-amber-200/40 mt-1 leading-relaxed">Bertahan hidup di alam liar yang tidak bersahabat, hadapi rintangan, dan temukan jalan pulang.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button wire:click="startAdventure" 
                        class="horror-btn group relative px-12 py-5 bg-gradient-to-r from-amber-950 via-red-950 to-amber-950 text-amber-100 font-bold rounded-xl shadow-2xl border border-amber-700/50 tracking-[0.25em] text-sm uppercase transition-all duration-300">
                    <span class="relative z-10 flex items-center gap-3">
                        Mulai Petualangan <span class="group-hover:translate-x-2 transition-transform duration-300">→</span>
                    </span>
                </button>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="w-full mt-12 py-6 border-t border-amber-900/10 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-mono text-amber-500/40">
            <div>
                &copy; 2026 BINUS University Project &bull; NarraTech Engine v1.0
            </div>
            <div class="flex items-center gap-4">
                <span>Created by Team PPL</span>
                <span>•</span>
                <span class="animate-pulse text-red-500/70">🔴 ONLINE</span>
            </div>
        </footer>
    </div>
</div>