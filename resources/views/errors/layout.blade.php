<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ config('app.env') === 'local' ? 'debug' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Zam Zam Import and EXport Inc</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.08), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }
        
        .dark .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.4), inset 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(5%, 5%) scale(1.05); }
            100% { transform: translate(0, 0) scale(1); }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.39);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.23);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: white;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .dark .btn-secondary {
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
        }
        
        .dark .btn-secondary:hover {
            background: rgba(51, 65, 85, 0.9);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        .animate-pulse-glow {
            animation: pulseGlow 4s ease-in-out infinite;
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        .animate-float-slow {
            animation: floatSlow 6s ease-in-out infinite;
        }

        /* Error code hero – refined, striking */
        @keyframes codeGlow {
            0%, 100% { opacity: 0.6; filter: blur(40px); transform: scale(1); }
            50% { opacity: 0.85; filter: blur(50px); transform: scale(1.08); }
        }
        @keyframes codeFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        .error-code-hero {
            animation: codeFloat 5s ease-in-out infinite;
        }
        .error-code-glow {
            animation: codeGlow 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased font-sans bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 min-h-screen flex flex-col items-center justify-center relative overflow-hidden selection:bg-red-500 selection:text-white">


    <div class="relative z-10 w-full max-w-3xl px-6 py-12 lg:px-8">
        
        <!-- Branding Header -->
        <div class="flex flex-col items-center justify-center mb-10 text-center animate-fade-in-down">
            <img src="{{ asset('images/Zam_logo-120x99.png') }}" alt="Zam Zam Logo" class="h-20 sm:h-24 w-auto mb-5 drop-shadow-md hover:scale-105 transition-transform duration-300">
            <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-widest uppercase">
                Zam Zam Import <span class="text-red-600 dark:text-red-500">and</span> EXport Inc
            </h2>
        </div>

        <div class="glass-panel p-10 sm:p-14 rounded-[2.5rem] text-center transition-all duration-500 hover:shadow-2xl">
            
            <!-- Error code hero – bold, minimal, striking -->
            <div class="mb-14 flex justify-center">
                <div class="relative error-code-hero">
                    <!-- Soft ambient glow behind -->
                    <div class="error-code-glow absolute inset-0 w-48 h-48 sm:w-56 sm:h-56 -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 rounded-full bg-[#C41E3A] pointer-events-none" aria-hidden="true"></div>

                    <!-- Main card: single clean surface -->
                    <div class="relative w-32 h-32 sm:w-40 sm:h-40 rounded-3xl overflow-hidden bg-gradient-to-br from-[#C41E3A] via-[#a01930] to-[#7a1225] dark:from-[#C41E3A] dark:via-[#8b1a2e] dark:to-[#5c0f1f] shadow-[0_25px_50px_-12px_rgba(196,30,58,0.35),0_0_0_1px_rgba(255,255,255,0.08)_inset] dark:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5),0_0_0_1px_rgba(255,255,255,0.06)_inset] transition-all duration-500 hover:shadow-[0_32px_64px_-14px_rgba(196,30,58,0.4)] hover:scale-[1.02] flex items-center justify-center group">
                        <!-- Top-edge highlight -->
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent" aria-hidden="true"></div>
                        <!-- Subtle inner vignette -->
                        <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-transparent to-black/20 pointer-events-none" aria-hidden="true"></div>

                        <!-- The number: bold, crisp, with subtle shine -->
                        <span class="relative z-10 text-6xl sm:text-7xl font-black tabular-nums text-white tracking-tighter select-none drop-shadow-[0_2px_8px_rgba(0,0,0,0.25)] group-hover:drop-shadow-[0_4px_16px_rgba(0,0,0,0.3)] transition-all duration-300">
                            @yield('code')
                        </span>
                    </div>
                </div>
            </div>
            
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-5 text-slate-900 dark:text-white drop-shadow-sm">
                @yield('short_message')
            </h1>
            
            <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-400 mb-12 leading-relaxed font-medium max-w-xl mx-auto">
                @yield('message')
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-5 sm:gap-6">
                <!-- Go Back Button -->
                <button onclick="window.history.back()" class="btn-secondary w-full sm:w-48 px-6 py-4 text-base font-bold rounded-2xl transition-all duration-300 flex items-center justify-center group focus:outline-none focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-700">
                    <svg class="w-6 h-6 mr-3 text-slate-500 group-hover:-translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back
                </button>

                <!-- Back to Home Button -->
                <a href="{{ url('/') }}" class="btn-primary w-full sm:w-56 px-6 py-4 text-base font-bold text-white rounded-2xl transition-all duration-300 flex items-center justify-center group focus:outline-none focus:ring-4 focus:ring-red-500/40">
                    <svg class="w-6 h-6 mr-3 text-white/90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Home
                </a>
            </div>
            
            @if(config('app.debug') && isset($exception) && $exception->getMessage())
                <div class="mt-14 text-left bg-white/80 dark:bg-black/40 p-6 rounded-2xl text-sm overflow-auto max-h-56 border border-red-100 dark:border-red-900/30 hidden md:block shadow-inner backdrop-blur-sm">
                    <p class="font-bold text-red-600 dark:text-red-400 mb-3 flex items-center uppercase tracking-wider text-xs">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Debug Information
                    </p>
                    <p class="text-slate-700 dark:text-slate-300 font-mono whitespace-pre-wrap leading-relaxed">{{ $exception->getMessage() }}</p>
                </div>
            @endif
        </div>
        
    </div>

    <!-- Footer attached to bottom -->
    <div>
        &copy; {{ date('Y') }} Zam Zam Import and Export Inc.<br class="sm:hidden" /> All rights reserved.
    </div>
</body>
</html>
