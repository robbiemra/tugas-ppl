<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Pendakian Horor' }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Creepster&family=Nosifer&family=Special+Elite&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* Horror theme base styles */
            :root {
                --blood: #8b0000;
                --blood-dark: #5c0000;
                --blood-glow: #ff2222;
                --fog: #1a1a2e;
                --fog-light: #16213e;
                --bone: #d4c5a9;
                --bone-dim: #a89a7e;
                --shadow: #0a0a0f;
                --ember: #e25822;
            }

            body {
                background-color: var(--shadow);
                overflow-x: hidden;
            }

            body.gameplay-active {
                overflow: hidden;
            }

            /* Flickering text animation */
            @keyframes flicker {
                0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% { opacity: 1; }
                20%, 24%, 55% { opacity: 0.4; }
            }

            @keyframes pulse-blood {
                0%, 100% { box-shadow: 0 0 5px rgba(217,119,6,0.3), 0 0 10px rgba(217,119,6,0.1); }
                50% { box-shadow: 0 0 15px rgba(217,119,6,0.5), 0 0 30px rgba(217,119,6,0.2); }
            }

            @keyframes fog-drift {
                0% { transform: translateX(-10%); opacity: 0.3; }
                50% { transform: translateX(10%); opacity: 0.15; }
                100% { transform: translateX(-10%); opacity: 0.3; }
            }

            @keyframes drip {
                0% { height: 0; opacity: 1; }
                70% { height: 20px; opacity: 1; }
                100% { height: 25px; opacity: 0; }
            }

            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-2px); }
                75% { transform: translateX(2px); }
            }

            .horror-title {
                font-family: 'Nosifer', cursive;
                color: var(--blood);
                text-shadow: 0 0 10px rgba(217,119,6,0.5), 0 0 40px rgba(217,119,6,0.2);
                animation: flicker 3s infinite;
            }

            .horror-subtitle {
                font-family: 'Creepster', cursive;
                letter-spacing: 3px;
            }

            .story-text {
                font-family: 'Special Elite', cursive;
            }

            .horror-btn {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .horror-btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(217,119,6,0.15), transparent);
                transition: left 0.5s;
            }

            .horror-btn:hover::before {
                left: 100%;
            }

            .horror-btn:hover {
                transform: scale(1.02);
                box-shadow: 0 0 20px rgba(217,119,6,0.4);
            }

            .fog-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: radial-gradient(ellipse at center, transparent 0%, rgba(10,10,15,0.4) 100%);
                pointer-events: none;
                z-index: 1;
            }

            .blood-drip::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 20%;
                width: 3px;
                background: var(--blood);
                border-radius: 0 0 3px 3px;
                animation: drip 2s ease-in infinite;
            }

            .horror-card {
                background: linear-gradient(145deg, rgba(26,26,46,0.95), rgba(10,10,15,0.98));
                border: 1px solid rgba(217,119,6,0.3);
                box-shadow: inset 0 0 30px rgba(0,0,0,0.5), 0 0 15px rgba(217,119,6,0.1);
            }

            .horror-input {
                background: rgba(10,10,15,0.8) !important;
                border: 1px solid rgba(217,119,6,0.3) !important;
                color: var(--bone) !important;
                font-family: 'Special Elite', cursive;
            }

            .horror-input:focus {
                border-color: var(--blood) !important;
                box-shadow: 0 0 10px rgba(217,119,6,0.3) !important;
                outline: none;
            }

            .horror-input::placeholder {
                color: rgba(212,197,169,0.3);
            }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: var(--shadow); }
            ::-webkit-scrollbar-thumb { background: var(--blood-dark); border-radius: 3px; }
        </style>
    </head>
    <body class="antialiased h-full">
        <div class="fog-overlay"></div>
        <div class="relative z-10 h-full">
            {{ $slot }}
        </div>
        @livewireScripts
    </body>
</html>