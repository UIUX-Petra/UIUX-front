<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('assets/logo2425-white.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    {{-- Insert CDN links below --}}

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

    {{-- TW Elements --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/tw-elements.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/css/tw-elements.min.css" />
    <script src="https://cdn.tailwindcss.com/3.3.0"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    {{-- Animate On Scroll (AOS) --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    {{-- JQuery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- Datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />

    {{-- SwiperJS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />   

    {{-- google icon --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/p2p logo.svg') }}" type="image/svg+xml">

    {{-- google font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">

    {{-- Alphine JS --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

    <script>
        (function() {
            const isLight = localStorage.getItem('color-theme') === 'light' || 
                            (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: light)').matches);
            document.documentElement.classList.add(isLight ? 'light-mode' : 'dark-mode');
        })();
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            font-family: 'Jost', sans-serif;
            --bblue: #7494ec;
            --purple: #633F92;
            --neutral: #292A67;
            --yellow: #F6CE3E;
            --red: #E62D63;
            --red-lg: #ff6e9a;
            --magenta: #902680;
            --purple-dark: #5500a4;
            --tosca: #4CB79C;
            --tosca-lg: rgb(74, 231, 192);
            --yellow-grad: linear-gradient(45deg, var(--yellow) 0%, var(--magenta) 100%);
            --tosca-grad: linear-gradient(45deg, var(--tosca) 0%, var(--purple) 100%);
            --ig-grad: linear-gradient(45deg, #ffde85 0%, #f7792a 30%, #f7504f 40%, #d82b81 60%, #d82b81 75%, #9536c2 100%);
            --line-grad: linear-gradient(45deg, #1a6c2a, #4cc764);
            --yt-grad: linear-gradient(45deg, #f76161, #dc2626);
            --spotify-grad: linear-gradient(45deg, #1DB954, #191414);
            --tiktok-grad: linear-gradient(45deg, #ff0050, #191414 40%, #191414 60%, #00f2ea);

            --
        }
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        body {
            top: 0;
            left: 0;
            margin: 0;
            padding: 0;
            min-width: 100vw;
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
            background-size: 200% 200%;
            background-repeat: no-repeat;
            overflow-x: hidden;
            animation: gradient 30s ease infinite;
            /* opacity: 50%; */
            z-index:1 ;
        }


        @font-face {
            font-family: 'Runtoe';
            src: url('{{ asset('assets/Runtoe.ttf') }}') format('truetype'),
                url('{{ asset('assets/Runtoe.otf') }}') format('opentype');
            font-weight: normal;
            font-style: normal;
        }
        
         /* Chrome, Edge and Safari */
        *::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }

        *::-webkit-scrollbar-track {
            border-radius: 6px;
            background-color: #22577A;
        }

        *::-webkit-scrollbar-track:hover {
            background-color: #38A3A5;
        }

        *::-webkit-scrollbar-track:active {
            background-color: #57CC99;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, transparent, #80ED99);
        }


        .swal2-confirm {
            background: rgb(46, 143, 255) !important;
        }

        .swal2-deny,
        .swal2-cancel {
            background: rgb(255, 79, 79) !important;
        }

        @keyframes animateText {
            0% {
                background-position: 0%;
            }

            100% {
                background-position: 500%;
            }
        }

        @-webkit-keyframes animateText {
            0% {
                background-position: 0%;
            }

            100% {
                background-position: 500%;
            }
        }

        .cal-sans-regular {
            font-family: "Cal Sans", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        
        .main-content {
            background-color: var(--bg-secondary);
        }
        .question-card {
            border: 1px solid var(--border-color);
            background-color: var(--bg-primary);

        }

        .question-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            background-color: var(--bg-card-hover);
        }

        .question-title {
            color: var(--text-primary);
        }

        /* .question-title:hover {
            color: var(--text-primary);
        } */

        .interaction-icons i {
            color: var(--text-muted);
        }

        .interaction-icons span {
            color: var(--text-secondary);
        }

        /* Additional theme-responsive styles */
        .bg-wave {
            position: absolute;
            width: 100%;
            min-height: 100vh;
            top: 0;
            left: 0;
            z-index: -1;
            opacity: 0.5;
            transition: opacity var(--transition-speed);
        }
        
        .light-mode .bg-wave {
            opacity: 0.2;
        }
    </style>
    @yield('style')

    <script>
        // Check for saved theme preference or use system preference
        function initTheme() {
            if (localStorage.getItem('color-theme') === 'light' || 
                (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: light)').matches)) {
                document.documentElement.classList.add('light-mode');
            } else {
                document.documentElement.classList.add('dark-mode');
            }

            applyTheme();
        }
        
        // Toggle theme
        function toggleTheme() {
            // Toggle theme in localStorage
            if (localStorage.getItem('color-theme') === 'light') {
                localStorage.setItem('color-theme', 'dark');
                document.documentElement.classList.remove('light-mode');
                document.documentElement.classList.add('dark-mode');
            } else {
                localStorage.setItem('color-theme', 'light');
                document.documentElement.classList.remove('dark-mode');
                document.documentElement.classList.add('light-mode');
            }
            
            // Apply updated theme
            applyTheme();
        }
        
        // Apply theme to the page
        function applyTheme() {
            const isLightMode = document.documentElement.classList.contains('light-mode');
            
            // Update the theme toggle button icon
            const themeToggleIcon = document.getElementById('theme-toggle-icon');
            if (themeToggleIcon) {
                themeToggleIcon.classList.remove(isLightMode ? 'fa-sun' : 'fa-moon');
                themeToggleIcon.classList.add(isLightMode ? 'fa-moon' : 'fa-sun');
            }
        }
    </script>

    <!-- Add these CSS variables to your <style> in the head section -->
    <style>
        :root {
            /* Base theme variables (used by both modes) */
            --transition-speed: 0.3s;
        }
        
        /* Dark mode variables (your current theme) */
        .dark-mode {
            /* Background colors */
            --bg-primary: #1C2245;
            --bg-secondary: #232753;
            --bg-tertiary: #32386E;
            --bg-shadow: #32386E;
            --bg-card: #1c2246;
            --bg-card-hover: #232753;
            
            /* Text colors */
            --text-primary: #ffffff;
            --text-secondary: #d0d9ff;
            --text-muted: #929fd3;
            --text-dark: #101838;
            
            
            /* Accent colors */
            --accent-primary: #7494ec;
            --accent-secondary: #23BF7F;
            --accent-tertiary: #ffd249;
            
            /* Border colors */
            --border-color: rgba(9, 15, 56, 0.604);
            
            /* Button colors */
            --button-primary: linear-gradient(to right, #38A3A5, #80ED99);
            --button-text: #111111;
        }
        
        /* Light mode variables */
        .light-mode {
            /* Background colors */
            --bg-primary: #f3f6fb;
            --bg-secondary: #fff;
            --bg-tertiary: #fff;
            --bg-shadow: #cdd4e7;
            --bg-card: #f6f7ff;
            --bg-card-hover: #EDF2FB;
            
            /* Text colors */
            --text-primary: #12192c;
            --text-secondary: #2e406b;
            --text-muted: #1a2e5c;
            --text-dark: #101838;
            
            /* Accent colors */
            --accent-primary: #5477c8;
            --accent-secondary: #10b981;
            --accent-tertiary: #f4ab24;
            
            /* Border colors */
            --border-color: rgba(90, 198, 198, 0.612);
            
            /* Button colors */
            --button-primary: linear-gradient(to right, #38A3A5, #80ED99);
            --button-text: #000000;
        }
        
        /* Apply theme variables to elements */
        body {
            background: var(--bg-primary);
            background: linear-gradient(0deg, var(--bg-primary) 0%, var(--bg-tertiary) 100%);
            color: var(--text-primary);
            transition: background var(--transition-speed), color var(--transition-speed);
        }
        
        a, button, div, span, p, h1, h2, h3, h4, h5, h6 {
            transition: background-color var(--transition-speed), 
                        color var(--transition-speed), 
                        border-color var(--transition-speed),
                        box-shadow var(--transition-speed);
        }
        
        .question-card, .bg-\[\#1c2246\] {
            background-color: var(--bg-card) !important;
        }
        
        .question-card:hover, .hover\:bg-\[\#232753\]:hover {
            background-color: var(--bg-card-hover) !important;
        }
        
        .text-white {
            color: var(--text-primary) !important;
        }
        
        .text-\[\#d0d9ff\] {
            color: var(--text-secondary) !important;
        }
        
        .text-\[\#909ed5\] {
            color: var(--text-muted) !important;
        }
        
        .theme-toggle {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: var(--text-primary);
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover {
            background-color: var(--border-color);
        }
        
        .theme-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--accent-primary);
        }
    </style>
</head>
{{-- @include('partials.nav') --}}
<body>
    <div class="lg:ml-[20rem] md:ml-64 pt-16 p-4"> 
        @yield('content')
        {{-- Insert <script> CDN below --}}

        {{-- TW Elements --}}
        <script src="https://cdn.jsdelivr.net/npm/tw-elements/js/tw-elements.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>

        {{-- GSAP, ScrollTrigger --}}
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

        {{-- SwiperJS --}}
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        {{-- Datatables --}}
        <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

        @yield('script')
    </div>
</body>

</html>
