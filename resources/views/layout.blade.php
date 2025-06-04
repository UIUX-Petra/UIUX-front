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

    {{-- google icon --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=light_mode" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=dark_mode" />
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/p2p logo.svg') }}" type="image/svg+xml">

    {{-- google font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">

    {{-- Alphine JS --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

    {{-- Toastify JS --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        (function() {
            const isLight = localStorage.getItem('color-theme') === 'light' ||
                (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: light)').matches);
            document.documentElement.classList.add(isLight ? 'light-mode' : 'dark-mode');
        })();
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap');

        html {
            scroll-behavior: smooth;
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
            z-index: 1;
        }


        @font-face {
            font-family: 'Runtoe';
            src: url('{{ asset('assets/Runtoe.ttf') }}') format('truetype'),
                url('{{ asset('assets/Runtoe.otf') }}') format('opentype');
            font-weight: normal;
            font-style: normal;
        }

        /* Chrome, Edge and Safari */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #f0eff4;
            border-radius: 8px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #dbdadf;
        }

        ::-webkit-scrollbar-track {
            background-color: #000000;
        }


        .swal2-confirm {
            background: rgb(46, 143, 255) !important;
        }

        .swal2-deny,
        .swal2-cancel {
            background: rgb(255, 79, 79) !important;
        }

        .material-symbols-rounded {
            font-variation-settings:
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
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

    <style>
        :root {
            --transition-speed: 0.3s;
        }

        .dark-mode {
            /* Background colors */
            --bg-a: rgba(5, 13, 36, 1);
            --bg-b: rgba(16, 26, 61, 1);
            --bg-c: rgba(43, 88, 120, 1);
            --bg-primary: #1C2245;
            --bg-secondary: #232753;
            --bg-tertiary: #32386E;
            --bg-shadow: #32386E;
            --bg-light: #494e73;
            --bg-card: #1c2246;
            --bg-card-hover: #232753;
            --bg-input: #0e1337;

            /* Text colors */
            --text-primary: #ffffff;
            --text-secondary: #d0d9ff;
            --text-muted: #929fd3;
            --text-highlight: #80ED99;
            --text-dark: #101838;
            --text-tag: #ffffff;

            /* Accent colors */
            --accent-primary: #7494ec;
            --accent-secondary: #19b675;
            --accent-tertiary: #ffd249;
            --accent-neg: #cf2c5a;

            /* Border colors */
            --border-color: rgba(9, 15, 56, 0.604);

            /* Button colors */
            --button-primary: linear-gradient(to right, #38A3A5, #80ED99);
            --button-primary-trf: linear-gradient(to left, #38A3A5, #80ED99);
            --button-text: #111111;
        }

        .light-mode {
            /* Background colors */
            --bg-c: rgb(193, 231, 236);
            --bg-primary: #f3f6fb;
            --bg-secondary: #fff;
            --bg-tertiary: #fff;
            --bg-shadow: #cdd4e7;
            --bg-light: #e0e2ea;
            --bg-card: #f6f7ff;
            --bg-card-hover: #EDF2FB;
            --bg-input: #ffffff;

            /* Text colors */
            --text-primary: #12192c;
            --text-secondary: #2e406b;
            --text-muted: #1a2e5c;
            --text-dark: #101838;
            --text-highlight: #298b8d;
            --text-tag: #2e406b;

            /* Accent colors */
            --accent-primary: #5477c8;
            --accent-secondary: #10b981;
            --accent-tertiary: #f4ab24;
            --accent-neg: #ee1150;

            /* Border colors */
            --border-color: rgba(90, 198, 198, 0.612);

            /* Button colors */
            --button-primary: linear-gradient(to right, #38A3A5, #80ED99);
            --button-text: #000000;
        }

        /* Apply theme variables to elements */
        body {
            background: var(--bg-primary);
            background: linear-gradient(180deg, var(--bg-primary) 0%, var(--bg-c) 100%);
            color: var(--text-primary);
            transition: background var(--transition-speed), color var(--transition-speed);
        }

        a,
        button,
        div,
        span,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            transition: background-color var(--transition-speed),
                color var(--transition-speed),
                border-color var(--transition-speed),
                box-shadow var(--transition-speed);
        }

        .question-card,
        .bg-\[\#1c2246\] {
            background-color: var(--bg-card) !important;
        }

        .question-card:hover,
        .hover\:bg-\[\#232753\]:hover {
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
    @include('partials.loader')
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
