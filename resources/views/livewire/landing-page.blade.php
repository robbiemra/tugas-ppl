<div class="min-h-screen p-6 md:p-12 flex flex-col justify-between relative overflow-hidden"
     style="background-image: linear-gradient(180deg, rgba(10,10,20,0.72) 0%, rgba(14,15,30,0.78) 40%, rgba(6,7,16,0.94) 100%), url('{{ asset('landing page bg2.png') }}'); background-size: cover; background-position: center; background-attachment: fixed; color: #e8d9b5;">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,500;9..144,600;9..144,700;9..144,900&family=Work+Sans:wght@300;400;500;600;700&display=swap');

        /* Scoped style overrides — a story-agnostic, literary aesthetic */
        .landing-card {
            background: linear-gradient(135deg, rgba(22, 24, 40, 0.5) 0%, rgba(8, 9, 18, 0.68) 100%) !important;
            backdrop-filter: blur(12px) saturate(120%);
            -webkit-backdrop-filter: blur(12px) saturate(120%);
            border: 1px solid rgba(212, 169, 96, 0.18) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.55), inset 0 0 20px rgba(212, 169, 96, 0.03) !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .genre-card-horror {
            background: linear-gradient(135deg, rgba(30, 8, 8, 0.22) 0%, rgba(10, 3, 3, 0.45) 100%) !important;
            border: 1px solid rgba(227, 93, 93, 0.14) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .genre-card-horror:hover {
            border-color: rgba(227, 93, 93, 0.45) !important;
            box-shadow: 0 0 30px rgba(227, 93, 93, 0.12), inset 0 0 15px rgba(227, 93, 93, 0.05) !important;
            transform: translateY(-2px);
        }

        .genre-card-adventure {
            background: linear-gradient(135deg, rgba(6, 28, 24, 0.22) 0%, rgba(2, 12, 10, 0.45) 100%) !important;
            border: 1px solid rgba(52, 211, 153, 0.14) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .genre-card-adventure:hover {
            border-color: rgba(52, 211, 153, 0.45) !important;
            box-shadow: 0 0 30px rgba(52, 211, 153, 0.12), inset 0 0 15px rgba(52, 211, 153, 0.05) !important;
            transform: translateY(-2px);
        }

        .landing-title {
            font-family: 'Fraunces', serif !important;
            font-weight: 600 !important;
            font-style: italic;
            font-optical-sizing: auto;
            letter-spacing: 0.01em !important;
            background: linear-gradient(135deg, #fdf8ec 0%, #e8d9b5 50%, #c7a568 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.5) !important;
        }

        .landing-subtitle {
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 500 !important;
            letter-spacing: 0.28em !important;
            text-transform: uppercase;
        }

        .landing-desc {
            font-family: 'Work Sans', sans-serif !important;
            font-size: 1.05rem !important;
            font-weight: 400 !important;
            line-height: 1.7 !important;
            color: #ecdfc2 !important;
        }

        .genre-desc {
            font-family: 'Work Sans', sans-serif !important;
            line-height: 1.5 !important;
            color: rgba(236, 223, 194, 0.65) !important;
        }

        .landing-btn {
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 600 !important;
            background: linear-gradient(90deg, #7a5a24 0%, #c7a568 50%, #7a5a24 100%) !important;
            border: 1px solid rgba(212, 169, 96, 0.4) !important;
            box-shadow: 0 10px 30px rgba(199, 165, 104, 0.22), inset 0 0 15px rgba(212, 169, 96, 0.15) !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .landing-btn:hover {
            background: linear-gradient(90deg, #8c6a2c 0%, #d9b87a 50%, #8c6a2c 100%) !important;
            box-shadow: 0 15px 40px rgba(199, 165, 104, 0.4), 0 0 15px rgba(212, 169, 96, 0.3), inset 0 0 15px rgba(212, 169, 96, 0.2) !important;
            transform: translateY(-2px) scale(1.02);
        }

        /* Signature element: branching "threads of fate" — genre-agnostic,
           reads as fantasy/adventure just as easily as horror */
        .fate-threads { opacity: 0.35; }
        .fate-threads circle { animation: node-glow 4s ease-in-out infinite; }
        .fate-threads circle:nth-child(2) { animation-delay: 0.6s; }
        .fate-threads circle:nth-child(3) { animation-delay: 1.2s; }
        .fate-threads circle:nth-child(4) { animation-delay: 1.8s; }
        .fate-threads circle:nth-child(5) { animation-delay: 2.4s; }

        @keyframes node-glow {
            0%, 100% { opacity: 0.35; r: 3; }
            50% { opacity: 1; r: 4.5; }
        }

        @media (prefers-reduced-motion: reduce) {
            .fate-threads circle, .status-dot { animation: none !important; }
        }

        @keyframes fog-drift {
            0%, 100% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(40px) translateY(-20px); }
        }

        .status-dot { animation: dot-pulse 2.4s ease-in-out infinite; }
        @keyframes dot-pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
    </style>

    {{-- Ambient background: soft drifting light, not tied to any one genre --}}
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute w-[600px] h-[200px] rounded-full top-[20%] -left-[10%] opacity-10"
             style="background: radial-gradient(ellipse, rgba(199,165,104,0.3), transparent); animation: fog-drift 14s ease-in-out infinite;"></div>
        <div class="absolute w-[500px] h-[150px] rounded-full top-[60%] -right-[10%] opacity-10"
             style="background: radial-gradient(ellipse, rgba(199,165,104,0.2), transparent); animation: fog-drift 17s ease-in-out infinite reverse;"></div>
    </div>

    <div class="relative z-10 w-full max-w-6xl mx-auto flex flex-col justify-between min-h-[85vh]">
        <div class="h-6"></div>

        {{-- Main Hero --}}
        <main class="flex-grow flex flex-col items-center justify-center text-center px-4 max-w-4xl mx-auto my-auto relative z-20">
            <div class="space-y-6 mb-8 relative">

                {{-- Signature: branching threads of fate, sits behind the title --}}
                <svg class="fate-threads absolute left-1/2 -translate-x-1/2 -top-10 w-[420px] h-[140px] pointer-events-none" viewBox="0 0 420 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M210 10 L210 55 L120 90 L60 130" stroke="#c7a568" stroke-width="1" stroke-linecap="round"/>
                    <path d="M210 55 L300 90 L360 130" stroke="#c7a568" stroke-width="1" stroke-linecap="round"/>
                    <path d="M210 55 L210 90 L210 130" stroke="#c7a568" stroke-width="1" stroke-linecap="round"/>
                    <circle cx="210" cy="10" r="3" fill="#fdf8ec"/>
                    <circle cx="210" cy="55" r="3" fill="#e8d9b5"/>
                    <circle cx="60" cy="130" r="3" fill="#e35d5d"/>
                    <circle cx="360" cy="130" r="3" fill="#34d399"/>
                    <circle cx="210" cy="130" r="3" fill="#e8d9b5"/>
                </svg>

                <h1 class="landing-title text-5xl md:text-8xl leading-none relative">
                    NARRATECH
                </h1>
                <p class="landing-subtitle text-lg md:text-xl" style="color: #c7a568;">
                    Pilih Takdirmu. Tulis Ceritamu.
                </p>
                <div class="w-48 h-[2px] mx-auto bg-gradient-to-r from-transparent to-transparent" style="--tw-gradient-stops: transparent, rgba(199,165,104,0.5), transparent;"></div>
            </div>

            <div class="landing-card max-w-2xl rounded-2xl p-6 md:p-8 mb-8 shadow-[0_0_30px_rgba(0,0,0,0.7)]">
                <p class="landing-desc leading-relaxed drop-shadow-[0_2px_4px_rgba(0,0,0,0.7)]">
                    Setiap pilihan adalah benang yang membentuk kisahmu. Jelajahi dunia yang berbeda-beda  dari lorong tua yang mencekam hingga pulau tak berpenghuni yang menantang  dan tentukan sendiri bagaimana ceritamu berakhir.
                </p>
            </div>

            {{-- Genre Preview Showcase --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl w-full mb-10 text-left">
                <!-- Horror Card -->
                <div class="genre-card-horror rounded-xl p-5 flex items-start gap-4 shadow-lg">
                    <span class="text-3xl p-3 bg-red-950/40 rounded-lg border border-red-950/70 shadow-inner">💀</span>
                    <div>
                        <h3 class="landing-subtitle text-sm text-red-400">GENRE: HORROR</h3>
                        <p class="genre-desc text-xs font-semibold mt-1" style="color: #c7a568;">Gunung Misterius & Rumah Sakit Kosong</p>
                        <p class="genre-desc text-[11px] mt-1">Rasakan ketegangan dengan alur cerita psikologis yang menegangkan dan atmosfer menyeramkan.</p>
                    </div>
                </div>

                <!-- Adventure Card -->
                <div class="genre-card-adventure rounded-xl p-5 flex items-start gap-4 shadow-lg">
                    <span class="text-3xl p-3 bg-emerald-950/25 rounded-lg border border-emerald-900/40 shadow-inner">⚔️</span>
                    <div>
                        <h3 class="landing-subtitle text-sm text-emerald-400">GENRE: ADVENTURE</h3>
                        <p class="genre-desc text-xs font-semibold mt-1" style="color: #c7a568;">Pulau Terpencil & Gua Bawah Tanah</p>
                        <p class="genre-desc text-[11px] mt-1">Bertahan hidup di alam liar yang tidak bersahabat, hadapi rintangan, dan temukan jalan pulang.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button wire:click="startAdventure"
                        class="landing-btn group relative px-12 py-5 font-bold rounded-xl tracking-[0.25em] text-sm uppercase transition-all duration-300" style="color: #fdf8ec;">
                    <span class="relative z-10 flex items-center gap-3">
                        Mulai Petualangan <span class="group-hover:translate-x-2 transition-transform duration-300">→</span>
                    </span>
                </button>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="w-full mt-6 py-4 border-t flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-mono" style="border-color: rgba(199,165,104,0.1); color: rgba(199,165,104,0.5);">
            <div>
                &copy; 2026 Bhinneka Nusantara University Project &bull; NarraTech Engine v1.0
            </div>
            <div class="flex items-center gap-4">
                <span>Created by Team PPL</span>
                <span>•</span>
                <span class="status-dot" style="color: #c7a568;">● ONLINE</span>
            </div>
        </footer>
    </div>
</div>