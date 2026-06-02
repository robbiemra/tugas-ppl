<div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-6 font-sans">
    <div class="max-w-4xl text-center">
        <p class="text-blue-600 font-bold tracking-[0.2em] text-xs mb-4 uppercase">
            Narrative Engine v1.0 
        </p>
        
        <h1 class="text-6xl md:text-8xl font-black text-slate-900 mb-6 tracking-tighter">
            PERJALANAN <br> <span class="text-blue-600"> Terseru</span>
        </h1>

        <p class="text-lg md:text-xl text-slate-600 mb-12 max-w-2xl mx-auto leading-relaxed">
            Setiap pilihan adalah benang merah takdirmu. Hadapi ketakutan di Rumah Sakit tua, pecahkan misteri Pimon, dan tentukan akhir ceritamu sendiri.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button wire:click="startAdventure" 
                    class="group relative px-12 py-5 bg-blue-600 text-white font-bold rounded-xl shadow-xl hover:bg-blue-700 transition-all transform hover:-translate-y-1 active:scale-95">
                MULAI PERTUALANGAN
            </button>
        </div>
    </div>

    <div class="absolute bottom-10 w-full px-10 flex justify-between items-end">
        <div class="text-[10px] font-mono text-slate-400">
            &copy; 2026 BINUS University Project
        </div>
    </div>
</div>