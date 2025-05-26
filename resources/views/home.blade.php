@extends('layout')
@section('content')
    <style>
        .welcome {
            /* color: var(--text-primary); */
            background: -webkit-linear-gradient(#eee, #333);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .main-content {
            background-color: var(--bg-secondary);
        }

        .question-card {
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            transition: box-shadow 0.2s, background-color 0.2s;
        }

        .question-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            background-color: var(--bg-card-hover);
        }

        /*
            .question-title {
                color: var(--text-primary);
            }

            .question-title:hover {
                color: var(--text-primary);
            } */

        .interaction-icons i {
            color: var(--text-muted);
        }

        .interaction-icons span {
            color: var(--text-secondary);
        }


        .welcome-container {
            background-color: var(--bg-card);
            border-radius: 1rem;
            transition: background-color var(--transition-speed);
        }

        .ask-question-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .ask-question-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-5px);
        }

        .stats-item {
            transition: transform 0.2s;
        }

        .stats-item:hover {
            transform: translateY(-2px);
        }

        .question-card {
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            transition: all 0.3s ease;
        }

        .question-card:hover {
            border-color: var(--accent-tertiary);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        /* Define some additional theme variables */
        :root {
            --bg-tag: rgba(56, 163, 165, 0.15);
            /* --text-tag: rgba(56, 163, 165, 1); */
            --bg-accent-subtle: rgba(128, 237, 153, 0.1);
            --transition-speed-fast: 0.2s;
        }

        .light-mode {
            --bg-tag: rgba(56, 163, 165, 0.2);
            --text-tag: rgba(56, 163, 165, 1);
            --bg-accent-subtle: rgba(128, 237, 153, 0.15);
        }

        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Ccircle cx='10' cy='10' r='1'/%3E%3C/g%3E%3C/svg%3E");
            background-size: 20px 20px;
        }

        .light-mode .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23000000' fill-opacity='0.03' fill-rule='evenodd'%3E%3Ccircle cx='10' cy='10' r='1'/%3E%3C/g%3E%3C/svg%3E");
        }

        .section-heading {
            position: relative;
            padding-left: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-heading::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: linear-gradient(to bottom, #38A3A5, #80ED99);
            border-radius: 2px;
        }

        /* Skeleton loading animation for questions */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        .skeleton {
            background: linear-gradient(to right, var(--bg-secondary) 8%, var(--bg-card-hover) 18%, var(--bg-secondary) 33%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
        }

        /* CSS untuk Loading Overlay dan Skeleton */
        #questions-container {
            position: relative;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(var(--bg-secondary-rgb), 0.6);
            /* Overlay transparan */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 990;
            /* Di bawah elemen UI penting, di atas konten */
            transition: opacity 0.3s ease-in-out;
            pointer-events: none;
            opacity: 0;
        }

        .loading-overlay.visible {
            opacity: 1;
            pointer-events: auto;
        }

        .loader {
            border: 5px solid var(--border-color);
            border-top: 5px solid var(--accent-primary);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        .skeleton-question-card {
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            padding: 1.25rem;
            display: flex;
            overflow: hidden;
        }

        .skeleton-shimmer-bg {
            background: linear-gradient(to right, var(--bg-card) 8%, var(--bg-card-hover) 38%, var(--bg-card) 54%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
            border-radius: 0.25rem;
        }

        .skeleton-stats-area {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-start;
            margin-right: 1rem;
            padding-right: 0.75rem;
            border-right: 1px solid var(--border-color);
            gap: 0.9rem;
            padding-top: 0.25rem;
        }

        .skeleton-stats-area .stat-line {
            height: 0.875rem;
            width: 2.5rem;
        }

        .skeleton-main-content {
            flex: 1;
        }

        .skeleton-main-content .title-line {
            height: 1.25rem;
            width: 75%;
            margin-bottom: 0.75rem;
        }

        .skeleton-main-content .text-line {
            height: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .skeleton-main-content .text-line.short {
            width: 90%;
        }

        .skeleton-main-content .text-line.long {
            width: 100%;
            margin-bottom: 0.75rem;
        }

        .skeleton-tags-area {
            display: flex;
            margin-top: 0.5rem;
            flex-wrap: wrap;
            gap: 0.3rem;
        }

        .skeleton-tags-area .tag-line {
            height: 1.25rem;
            width: 4.5rem;
            border-radius: 9999px;
        }

        /* Styling untuk pagination (sesuaikan jika menggunakan Tailwind atau Bootstrap) */
        .pagination-links nav {
            display: flex;
            justify-content: center;
        }

        .pagination-links ul.pagination {
            /* Bootstrap default */
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
            list-style: none;
            display: inline-block;
        }

        .pagination-links .page-item {
            display: inline;
        }

        .pagination-links .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: var(--accent-primary);
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            transition: all 0.2s ease-in-out;
        }

        .pagination-links .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: .25rem;
            border-bottom-left-radius: .25rem;
        }

        .pagination-links .page-item:last-child .page-link {
            border-top-right-radius: .25rem;
            border-bottom-right-radius: .25rem;
        }

        .pagination-links .page-link:hover {
            z-index: 2;
            color: var(--accent-secondary);
            background-color: var(--bg-card-hover);
            border-color: var(--accent-primary);
        }

        .pagination-links .page-item.active .page-link {
            z-index: 3;
            color: var(--text-dark);
            background-color: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .pagination-links .page-item.disabled .page-link {
            color: var(--text-muted);
            pointer-events: none;
            background-color: var(--bg-card);
            border-color: var(--border-color);
        }


        /* Save button styles */
        .save-question-btn {
            opacity: 0.7;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid var(--border-color);
        }

        .save-question-btn:hover {
            opacity: 1;
            transform: scale(1.05);
        }

        .save-question-btn i {
            transition: all 0.2s ease;
        }

        .question-card:hover .save-question-btn {
            opacity: 1;
        }

        /* Animation for saved state */
        @keyframes savedPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .saved-animation i {
            animation: savedPulse 0.3s ease-in-out;
        }

        .save-question-btn:focus {
            outline: none;
        }
    </style>
    {{-- @endsection --}}
    {{-- @section('content') --}}
    @include('partials.nav')
    @if (session()->has('Error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('Error') }}'
            });
        </script>
    @endif
    {{-- @include('utils.background2') --}}

    <!-- Main content -->
    <div
        class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.2)] to-[rgba(128,237,153,0.2)] blur-2xl">
    </div>
    <div
        class="w-full bg-transparent rounded-lg p-6 px-8 max-w-8xl mx-auto my-6 flex flex-col md:flex-row md:items-center md:space-x-6 welcome-container backdrop-blur-sm relative overflow-hidden">
        {{-- <!-- Decorative element -->
        <div class="text-5xl hidden md:flex z-10">
            <img id="theme-logo" src="{{ asset('assets/p2p logo - white.svg') }}" alt="Logo"
                class="h-12 lg:h-14 w-auto theme-logo">
        </div> --}}

        <div class="flex flex-col pl-3 z-10">
            @if (session()->has('email'))
                <h1 class="cal-sans-regular welcome lg:text-4xl text-2xl mb-2 font-bold">
                    Welcome, {{ $username }}!
                </h1>
                <p class="text-[var(--text-muted)] text-lg pl-0.5 leading-relaxed max-w-xl">
                    Ask questions, share answers, and learn together with fellow Petranesian Informates.
                </p>
                <!-- Stats summary -->
                {{-- <div class="flex space-x-6 mt-4 text-sm">
                    <div class="flex items-center">
                        <i class="fa-solid fa-question-circle mr-2 text-[var(--accent-primary)]"></i>
                        <span>23 Questions</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fa-solid fa-comment mr-2 text-[var(--accent-secondary)]"></i>
                        <span>42 Answers</span>
                    </div>
                </div> --}}
            @endif
            <a href="{{ route('askPage') }}"
                class="ask-question-btn {{ request()->routeIs('askPage') ? 'active-ask' : '' }} md:hidden flex mt-5 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium text-[0.85rem] p-2.5 rounded-lg items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-question-circle mr-2"></i> Ask a Question
            </a>
        </div>
    </div>


    <h3 class="cal-sans-regular lg:text-xl text-lg pl-12 mt-10 mb-4">Newest Questions</h3>

    <!-- Questions and Ask Question Section -->
    <div class="justify-start items-start max-w-8xl px-4 flex space-x-6">
        <div id="questions-container"
            class="w-full bg-transparent rounded-lg p-6 shadow-lg max-w-3xl justify-start items-start">
            {{-- Loading Overlay --}}
            {{-- <div class="loading-overlay">
                <div class="loader"></div>
            </div> --}}

            {{-- Wrapper untuk daftar pertanyaan (diisi oleh skeleton atau konten AJAX) --}}
            <div id="questions-list-wrapper" class="bg-transparent rounded-lg">
                @include('partials.questions_only_list', ['questions' => $questions])
            </div>

            {{-- Wrapper untuk pagination (diisi oleh AJAX) --}}
            <div class="mt-8 pagination-links">
                @if ($questions->hasPages())
                    {{ $questions->links() }}
                @endif
            </div>
        </div>

        <div class="w-72 mt-6 ml-6 hidden md:flex sticky top-24 h-fit">
            <div
                class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden">
                <!-- Decorative elements -->
                <div
                    class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.15)] to-[rgba(128,237,153,0.15)]">
                </div>
                <div
                    class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)]">
                </div>

                <div class="flex flex-col items-center text-center relative z-10">
                    <div class="mb-5 bg-[var(--bg-accent-subtle)] p-3 rounded-full">
                        <i class="fa-solid fa-lightbulb text-3xl text-[var(--accent-tertiary)]"></i>
                    </div>
                    <h2 class="text-xl font-bold text-[var(--text-primary)] mb-3">
                        Have a Question?
                    </h2>
                    <p class="text-[var(--text-muted)] mb-6 text-md leading-relaxed">
                        Connect with fellow Petranesian Informates and get insights from your peers!
                    </p>

                    <a href="{{ route('askPage') }}"
                        class="w-full ask-question-btn bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium py-2.5 text-md px-4 rounded-lg flex items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-plus mr-2"></i> Ask a Question
                    </a>

                    <!-- Quick links -->
                    <div class="w-full mt-5 pt-5 border-t border-[var(--border-color)]">
                        <h3 class="font-medium mb-3 text-sm">Quick Links</h3>
                        <ul class="space-y-2 text-left">
                            <li class="flex items-center text-sm">
                                <i class="fa-solid fa-fire-flame-curved mr-2 text-amber-500"></i>
                                <a href="#"
                                    class="text-[var(--text-secondary)] hover:text-[var(--accent-secondary)] transition-colors">Popular
                                    Questions</a>
                            </li>
                            <li class="flex items-center text-sm">
                                <i class="fa-solid fa-star mr-2 text-yellow-500"></i>
                                <a href="#"
                                    class="text-[var(--text-secondary)] hover:text-[var(--accent-secondary)] transition-colors">Unanswered
                                    Questions</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializePageFunctions();

            const themeObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        updateThemeIcons();
                        updateIconColors();
                        updateSavedIcons();
                    }
                });
            });
            themeObserver.observe(document.documentElement, {
                attributes: true
            });

            const questionsContainer = document.getElementById('questions-container');
            if (questionsContainer) {
                questionsContainer.addEventListener('click', function(event) {
                    let target = event.target;
                    const url = target ? target.getAttribute('href') :
                        null;
                    console.log("URL yang Ditemukan:", url);

                    if (url && url !== '#') {
                        event.preventDefault();
                        loadQuestions(url);
                    } else {
                        console.log("Kondisi URL TIDAK terpenuhi. Navigasi default akan terjadi.");
                    }
                });
            }
        });

        function initializePageFunctions() {
            updateThemeIcons();
            updateIconColors();
            lazyLoadImages();
            initSmoothScroll();
            initSaveButtons();
            updateSavedIcons();
        }

        function showLoadingIndicator() {
            const overlay = document.querySelector('#questions-container .loading-overlay');
            if (overlay) {
                console.log("Attempting to show global loading indicator (overlay)");
                overlay.classList.add('visible');
            }
        }

        function hideLoadingIndicator() {
            const overlay = document.querySelector('#questions-container .loading-overlay');
            if (overlay) {
                console.log("Attempting to hide global loading indicator (overlay)");
                overlay.classList.remove('visible');
            }
        }

        function showSkeletonPlaceholder(count = 3) {
            console.log("Showing skeleton placeholder");
            const listContainer = document.getElementById('questions-list-wrapper');
            const paginationContainer = document.querySelector('#questions-container .pagination-links');

            if (listContainer) {
                listContainer.innerHTML = '';
                let skeletonHTML = '';
                for (let i = 0; i < count; i++) {
                    skeletonHTML += `
                <div class="skeleton-question-card">
                    <div class="skeleton-stats-area">
                        <div class="stat-line skeleton-shimmer-bg"></div>
                        <div class="stat-line skeleton-shimmer-bg"></div>
                        <div class="stat-line skeleton-shimmer-bg"></div>
                    </div>
                    <div class="skeleton-main-content">
                        <div class="title-line skeleton-shimmer-bg"></div>
                        <div class="text-line long skeleton-shimmer-bg"></div>
                        <div class="text-line short skeleton-shimmer-bg"></div>
                        <div class="skeleton-tags-area">
                            <div class="tag-line skeleton-shimmer-bg"></div>
                            <div class="tag-line skeleton-shimmer-bg" style="width: 5.5rem;"></div>
                        </div>
                    </div>
                </div>
            `;
                }
                listContainer.innerHTML = skeletonHTML;
            }
            if (paginationContainer) {
                paginationContainer.innerHTML = '';
            }
        }

        function loadQuestions(url) {
            console.log("loadQuestions called for URL:", url);

            showSkeletonPlaceholder();

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log("Fetch response status:", response.status);
                    if (!response.ok) {
                        return response.json().catch(() => {
                            throw new Error(
                                `HTTP error ${response.status} - ${response.statusText}. Server did not return a valid JSON error response.`
                            );
                        }).then(errData => {
                            throw new Error(errData.message ||
                                `HTTP error ${response.status} - ${response.statusText}.`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Data received from AJAX:", data);

                    if (data.error) {
                        throw new Error(data.message || 'An error occurred while fetching data from the server.');
                    }

                    const questionsListWrapper = document.getElementById('questions-list-wrapper');
                    const paginationContainer = document.querySelector('#questions-container .pagination-links');

                    if (questionsListWrapper && data.questions_html !== undefined) {
                        questionsListWrapper.innerHTML = data.questions_html;
                    } else if (questionsListWrapper) {
                        questionsListWrapper.innerHTML =
                            '<p class="text-center text-[var(--text-muted)] py-5">Could not load questions content (missing data).</p>';
                        console.warn(
                            "Data format warning: 'questions_html' was missing or undefined in the AJAX response.",
                            data);
                    }

                    if (paginationContainer && data.pagination_html !== undefined) {
                        paginationContainer.innerHTML = data.pagination_html;
                    } else if (paginationContainer) {
                        paginationContainer.innerHTML = '';
                    }

                    history.pushState({
                        path: url
                    }, '', url);
                    initializePageFunctions();

                    const containerElement = document.getElementById('questions-container');
                    if (containerElement) {
                        const offsetTop = containerElement.offsetTop;
                        const headerOffset = document.querySelector('nav.is-fixed-top, .fixed-header-class')
                            ?.offsetHeight || 80;
                        window.scrollTo({
                            top: offsetTop - headerOffset,
                            behavior: 'smooth'
                        });
                    }
                })
                .catch(error => {
                    const questionsListWrapper = document.getElementById('questions-list-wrapper');
                    if (questionsListWrapper) {
                        questionsListWrapper.innerHTML = `
                <div class="text-center py-10 text-red-500">
                    <i class="fa-solid fa-triangle-exclamation text-4xl mb-3"></i>
                    <p class="text-lg">Sorry, an error occurred: ${error.message}. Please try refreshing the page.</p>
                </div>`;
                    }
                    const paginationContainer = document.querySelector('#questions-container .pagination-links');
                    if (paginationContainer) paginationContainer.innerHTML = '';

                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: `Error: ${error.message}`,
                            duration: 7000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#e74c3c"
                            }
                        }).showToast();
                    } else {
                        alert(`Error: ${error.message}`);
                    }
                })
                .finally(() => {
                    // console.log("Fetch process finished for URL:", url); 

                });
        }

        function updateIconColors() {
            const statsItems = document.querySelectorAll('.stats-item');
            const isLightMode = document.documentElement.classList.contains('light-mode');
            if (statsItems) {
                statsItems.forEach((item, index) => {
                    const icon = item.querySelector('i');
                    if (!icon) return;
                    if (index % 3 === 0) {
                        icon.style.color = isLightMode ? '#10b981' : '#23BF7F';
                    } else if (index % 3 === 1) {
                        icon.style.color = isLightMode ? '#f59e0b' : '#ffd249';
                    } else {
                        icon.style.color = isLightMode ? '#3b82f6' : '#909ed5';
                    }
                });
            }
        }

        function updateThemeIcons() {
            const isLightMode = document.documentElement.classList.contains('light-mode');
            const themeToggleIcon = document.getElementById('theme-toggle-icon');
            const mobileThemeToggleIcon = document.getElementById('mobile-theme-toggle-icon');
            const themeLogoToggle = document.getElementById('theme-logo');

            if (themeToggleIcon) themeToggleIcon.className = isLightMode ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
            if (mobileThemeToggleIcon) mobileThemeToggleIcon.className = isLightMode ? 'fa-solid fa-moon' :
                'fa-solid fa-sun';
            if (themeLogoToggle) themeLogoToggle.src = isLightMode ? "{{ asset('assets/p2p logo.svg') }}" :
                "{{ asset('assets/p2p logo - white.svg') }}";
        }

        // Pastikan theme toggle di mobile juga memanggil fungsi yang benar
        const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
        if (mobileThemeToggle && typeof toggleTheme === 'function') { // Asumsi `toggleTheme` adalah fungsi global Anda
            mobileThemeToggle.addEventListener('click', toggleTheme);
        }


        function lazyLoadImages() {
            const lazyImages = document.querySelectorAll('.lazy-image');
            if ("IntersectionObserver" in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const image = entry.target;
                            image.src = image.dataset.src;
                            image.classList.remove("lazy-image");
                            imageObserver.unobserve(image);
                        }
                    });
                });
                lazyImages.forEach(image => imageObserver.observe(image));
            } else {
                lazyImages.forEach(image => {
                    image.src = image.dataset.src;
                    image.classList.remove("lazy-image");
                });
            }
        }

        function initSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }

        function initSaveButtons() {
            const saveButtons = document.querySelectorAll('.save-question-btn');
            saveButtons.forEach(button => {
                const newButton = button.cloneNode(true);
                newButton.removeAttribute('onclick'); // <--- PENTING: Hapus atribut onclick dari kloningan
                button.parentNode.replaceChild(newButton, button);

                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Tentukan aksi berdasarkan kondisi tombol saat ini (misalnya, kelas ikonnya)
                    const icon = this.querySelector('i');
                    // Periksa apakah ikon saat ini adalah ikon "tersimpan" (solid bookmark)
                    if (icon && icon.classList.contains('fa-solid') && icon.classList.contains(
                            'fa-bookmark')) {
                        unsaveQuestion(this);
                    } else {
                        saveQuestion(this);
                    }
                });
            });
        }

        function updateSavedIcons() {
            const savedIcons = document.querySelectorAll('.save-question-btn i.fa-solid.fa-bookmark');
            const isLightMode = document.documentElement.classList.contains('light-mode');
            savedIcons.forEach(icon => {
                icon.style.color = isLightMode ? 'var(--accent-secondary)' :
                    'var(--accent-secondary)';
            });
        }

        function unsaveQuestion(btn) {
            const id = btn.getAttribute('data-question-id');
            let formData = new FormData();
            formData.append("question_id", id);

            let loadingToast = Toastify({
                text: "Unsaving...",
                duration: -1,
                close: false,
                gravity: "top",
                position: "right",
                style: {
                    background: "#444"
                }
            });
            loadingToast.showToast();

            fetch("{{ route('unsaveQuestion') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            }).then(response => response.json()).then(res => {
                loadingToast.hideToast();
                if (res.success) {
                    Toastify({
                        text: res.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    btn.innerHTML =
                        `<i class="fa-regular fa-bookmark text-[var(--text-muted)] hover:text-[var(--accent-secondary)]"></i>`;
                    // btn.setAttribute("onclick", "saveQuestion(this)");
                    btn.setAttribute("title", "Save Question");
                } else {
                    Toastify({
                        text: res.message || "Failed to unsave.",
                        duration: 3000,
                    }).showToast();
                }
            }).catch(err => {
                loadingToast.hideToast();
                Toastify({
                    text: "Something went wrong",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#e74c3c"
                    }
                }).showToast();
            });
        }

        function saveQuestion(btn) {
            const id = btn.getAttribute('data-question-id');
            let formData = new FormData();
            formData.append("question_id", id);

            let loadingToast = Toastify({
                text: "Saving...",
                duration: -1,
                close: false,
                gravity: "top",
                position: "right",
                style: {
                    background: "#444"
                }
            });
            loadingToast.showToast();

            fetch("{{ route('saveQuestion') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            }).then(response => response.json()).then(res => {
                loadingToast.hideToast();
                if (res.success) {
                    Toastify({
                        text: res.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    btn.innerHTML =
                        `<i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>`;
                    // btn.setAttribute("onclick", "unsaveQuestion(this)");
                    btn.setAttribute("title", "Unsave Question");
                    updateSavedIcons();
                    btn.classList.add('saved-animation');
                    setTimeout(() => btn.classList.remove('saved-animation'), 300);
                } else {
                    Toastify({
                        text: res.message || "Failed to save.",
                        duration: 3000,
                    }).showToast();
                }
            }).catch(err => {
                loadingToast.hideToast();
                Toastify({
                    text: "Something went wrong",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#e74c3c"
                    }
                }).showToast();
            });
        }
    </script>
@endsection
