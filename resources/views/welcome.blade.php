<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        </style>
    </head>
    <body class="bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black text-slate-200 font-sans min-h-screen flex items-center justify-center p-6 selection:bg-indigo-500/30">
        @php
            $genre = request('genre');
            $choice = request('choice');
        @endphp

        <div class="max-w-2xl w-full backdrop-blur-xl bg-slate-900/40 rounded-3xl shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] p-10 border border-white/10 ring-1 ring-white/5 animate-fade-in">
            <header class="text-center mb-10">
                <h1 class="text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400 mb-2">
                    Narra Tech
                </h1>
                <div class="h-1 w-20 bg-indigo-500 mx-auto rounded-full opacity-50"></div>
            </header>

            @if (!$genre)
                <!-- Tampilan Pilih Genre -->
                <div class="text-center">
                    <p class="mb-8 text-slate-400 text-lg">Tentukan takdirmu. Pilih dunia yang ingin kamu jelajahi:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="?genre=scifi" class="group relative overflow-hidden p-8 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 hover:border-indigo-500/50 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <span class="block text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">🚀</span>
                            <span class="relative font-bold text-xl text-indigo-300">Adventure</span>
                            <p class="relative text-xs text-indigo-400/70 mt-2">Petualangan seru menanti di depan</p>
                        </a>
                        <a href="?genre=fantasy" class="group relative overflow-hidden p-8 rounded-2xl bg-emerald-600/10 border border-emerald-500/20 hover:border-emerald-500/50 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <span class="block text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">🧙‍♂️</span>
                            <span class="relative font-bold text-xl text-emerald-300">Horror</span>
                            <p class="relative text-xs text-emerald-400/70 mt-2">sihir misteri kuno</p>
                        </a>
                    </div>
                </div>

            @elseif ($genre === 'scifi')
                @if (!$choice)
                    <div class="animate-fade-in">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold uppercase tracking-wider border border-indigo-500/30">Sci-Fi</span>
                        </div>
                        <p class="text-xl leading-relaxed mb-8 text-slate-300">Tahun 2077. Kamu adalah hacker freelance di gang sempit Glodok. Sebuah chip rahasia ada di tanganmu, dan drone patroli mulai mendekat. Langkahmu?</p>
                        <div class="grid gap-4">
                            <a href="?genre=scifi&choice=hack" class="group flex items-center p-4 bg-white/5 hover:bg-indigo-500/10 border border-white/10 hover:border-indigo-500/50 rounded-xl transition-all">
                                <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-800 text-indigo-400 mr-4 group-hover:bg-indigo-500 group-hover:text-white transition-colors">1</span>
                                <span class="text-slate-300 group-hover:text-white">Retas terminal keamanan terdekat.</span>
                            </a>
                            <a href="?genre=scifi&choice=run" class="group flex items-center p-4 bg-white/5 hover:bg-indigo-500/10 border border-white/10 hover:border-indigo-500/50 rounded-xl transition-all">
                                <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-800 text-indigo-400 mr-4 group-hover:bg-indigo-500 group-hover:text-white transition-colors">2</span>
                                <span class="text-slate-300 group-hover:text-white">Lari ke kerumunan pasar bawah tanah.</span>
                            </a>
                            <a href="?genre=scifi&choice=fight" class="group flex items-center p-4 bg-white/5 hover:bg-indigo-500/10 border border-white/10 hover:border-indigo-500/50 rounded-xl transition-all">
                                <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-800 text-indigo-400 mr-4 group-hover:bg-indigo-500 group-hover:text-white transition-colors">3</span>
                                <span class="text-slate-300 group-hover:text-white">Aktifkan lengan cybernetic & lawan!</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <div class="inline-block p-4 rounded-full bg-indigo-500/10 mb-6">
                            <span class="text-4xl">🎬</span>
                        </div>
                        @if($choice === 'hack')
                            <p class="text-2xl font-medium mb-8 leading-snug">"Akses Diterima." Drone menabrak gedung korporat. Kamu berhasil menjual chip seharga jutaan credit!</p>
                        @elseif($choice === 'run')
                            <p class="text-2xl font-medium mb-8 leading-snug">Kerumunan Glodok melindungimu. Kamu kini bergabung dengan resistance untuk menjatuhkan korporat.</p>
                        @else
                            <p class="text-2xl font-medium mb-8 leading-snug text-red-400">Pertempuran brutal! Drone jatuh, tapi chip rusak. Kamu kini jadi buronan nomor satu.</p>
                        @endif
                        <a href="/" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-cyan-600 rounded-full font-bold hover:shadow-[0_0_20px_rgba(79,70,229,0.4)] transition-all transform hover:-translate-y-1">Mulai Baru</a>
                    </div>
                @endif

            @elseif ($genre === 'fantasy')
                @if (!$choice)
                    <div class="animate-fade-in">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider border border-emerald-500/30">Fantasy</span>
                        </div>
                        <p class="text-xl leading-relaxed mb-8 text-slate-300">Kamu berdiri di depan gerbang Kastil Arcania. Kabut ungu menyelimuti sekitar. Tiga pintu misterius menantimu. Pilihanmu?</p>
                        <div class="grid gap-4">
                            <a href="?genre=fantasy&choice=magic" class="group flex items-center p-4 bg-white/5 hover:bg-emerald-500/10 border border-white/10 hover:border-emerald-500/50 rounded-xl transition-all">
                                <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-800 text-emerald-400 mr-4 group-hover:bg-emerald-500 group-hover:text-white transition-colors">✨</span>
                                <span class="text-slate-300 group-hover:text-white">Gunakan mantra cahaya di pintu utama.</span>
                            </a>
                            <a href="?genre=fantasy&choice=garden" class="group flex items-center p-4 bg-white/5 hover:bg-emerald-500/10 border border-white/10 hover:border-emerald-500/50 rounded-xl transition-all">
                                <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-800 text-emerald-400 mr-4 group-hover:bg-emerald-500 group-hover:text-white transition-colors">🌿</span>
                                <span class="text-slate-300 group-hover:text-white">Lewati taman tanaman karnivora.</span>
                            </a>
                            <a href="?genre=fantasy&choice=dungeon" class="group flex items-center p-4 bg-white/5 hover:bg-emerald-500/10 border border-white/10 hover:border-emerald-500/50 rounded-xl transition-all">
                                <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-800 text-emerald-400 mr-4 group-hover:bg-emerald-500 group-hover:text-white transition-colors">💀</span>
                                <span class="text-slate-300 group-hover:text-white">Turun ke penjara bawah tanah.</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <div class="inline-block p-4 rounded-full bg-emerald-500/10 mb-6">
                            <span class="text-4xl">🔮</span>
                        </div>
                        @if($choice === 'magic')
                            <p class="text-2xl font-medium mb-8 leading-snug">Kutukan kastil sirna! Kamu menemukan Tahta Kristal dan dinobatkan sebagai Penyihir Agung.</p>
                        @elseif($choice === 'garden')
                            <p class="text-2xl font-medium mb-8 leading-snug">Tanaman itu bicara! Mereka memberimu buah ajaib untuk memahami bahasa alam.</p>
                        @else
                            <p class="text-2xl font-medium mb-8 leading-snug text-amber-300">Ada naga tidur! Kamu memberinya makan, dan sekarang kamu memiliki peliharaan naga.</p>
                        @endif
                        <a href="/" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-full font-bold hover:shadow-[0_0_20px_rgba(16,185,129,0.4)] transition-all transform hover:-translate-y-1">Mulai Baru</a>
                    </div>
                @endif
            @endif
        </div>

        <footer class="fixed bottom-6 text-slate-500 text-sm">
            Laravel Narrative System &bull; Crafted with Tailwind
        </footer>
    </body>
</html>
