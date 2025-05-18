@extends('layout')
@section('head')
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

        .question-title {
            color: var(--text-primary);
        }

        .question-title:hover {
            color: var(--text-primary);
        }

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

        /* Add these to your existing CSS */
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
            --text-tag: rgba(56, 163, 165, 1);
            --bg-accent-subtle: rgba(128, 237, 153, 0.1);
            --transition-speed-fast: 0.2s;
        }

        .light-mode {
            --bg-tag: rgba(56, 163, 165, 0.2);
            --text-tag: rgba(56, 163, 165, 1);
            --bg-accent-subtle: rgba(128, 237, 153, 0.15);
        }

        /* Create a subtle pattern background */
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Ccircle cx='10' cy='10' r='1'/%3E%3C/g%3E%3C/svg%3E");
            background-size: 20px 20px;
        }

        .light-mode .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23000000' fill-opacity='0.03' fill-rule='evenodd'%3E%3Ccircle cx='10' cy='10' r='1'/%3E%3C/g%3E%3C/svg%3E");
        }

        /* Improve section headings */
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

        /* Add this to your CSS styles */

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

        /* Make sure the button doesn't interfere with card click */
        .save-question-btn:focus {
            outline: none;
        }
    </style>
@endsection
@section('content')
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

    <!-- Main content with proper margin for sidebar -->
    <div
        class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.2)] to-[rgba(128,237,153,0.2)] blur-2xl">
    </div>
    <div
        class="w-full bg-transparent rounded-lg p-6 px-8 max-w-8xl mx-auto my-6 flex flex-col md:flex-row md:items-center md:space-x-6 welcome-container backdrop-blur-sm relative overflow-hidden">
        <!-- Decorative element -->
        <div class="text-5xl hidden md:flex z-10">
            <img id="theme-logo" src="{{ asset('assets/p2p logo - white.svg') }}" alt="Logo"
                class="h-12 lg:h-14 w-auto theme-logo">
        </div>

        <div class="flex flex-col z-10">
            @if (session()->has('email'))
                <h1 class="cal-sans-regular welcome lg:text-4xl text-2xl mb-2 font-bold">
                    Welcome, {{ $username }}!
                </h1>
                <p class="text-[var(--text-muted)] text-lg pl-0.5 leading-relaxed max-w-xl">
                    Ask questions, share answers, and learn together with fellow Petranesian Informates.
                </p>
                <!-- Stats summary -->
                <div class="flex space-x-6 mt-4 text-sm">
                    <div class="flex items-center">
                        <i class="fa-solid fa-question-circle mr-2 text-[var(--accent-primary)]"></i>
                        <span>{{ $user['questions_count'] }} Questions</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fa-solid fa-comment mr-2 text-[var(--accent-secondary)]"></i>
                        <span>{{ $user['answers_count'] }} Answers</span>
                    </div>
                </div>
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
        <!-- Questions Section -->
        <div class="w-full bg-transparent rounded-lg p-6 shadow-lg max-w-3xl justify-start items-start">
            <!-- Loop through questions -->
            @foreach ($questions as $question)
                <div
                    class="question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[var(--accent-tertiary)] relative overflow-hidden">
                    <!-- Add subtle pattern background -->
                    <div class="absolute inset-0 bg-pattern opacity-5"></div>

                    <!-- Save button - added to top right -->
                    <button
                        class="save-question-btn absolute top-3 right-3 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-hover)] bg-[var(--bg-card-hover)]"
                        data-question-id="{{ $question['id'] }}">
                        <i class="fa-regular fa-bookmark text-[var(--text-muted)] hover:text-[var(--accent-secondary)]"></i>
                    </button>

                    <!-- Stats Column -->
                    <div
                        class="flex flex-col items-center justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)]">
                        <div class="stats-item flex flex-col items-center">
                            <i class="text-lg fa-regular fa-thumbs-up"></i>
                            <span class="text-sm font-medium mt-1">{{ $question['vote'] }}</span>
                        </div>
                        <div class="stats-item flex flex-col items-center">
                            <i class="text-lg fa-solid fa-eye"></i>
                            <span class="text-sm font-medium mt-1">{{ $question['view'] }}</span>
                        </div>
                        <div class="stats-item flex flex-col items-center">
                            <i class="text-lg fa-regular fa-comment"></i>
                            <span class="text-sm font-medium mt-1">{{ $question['comments_count'] }}</span>
                        </div>
                    </div>

                    <div class="flex-1 z-10">
                        <!-- Question Title with improved typography -->
                        <h2
                            class="text-xl font-medium question-title cursor-pointer transition-colors duration-200 hover:underline decoration-[var(--accent-tertiary)] decoration-2 underline-offset-2">
                            <a
                                href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] }}</a>
                        </h2>

                        <!-- Question Snippet with better readability -->
                        <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                            {{ \Str::limit($question['question'], 150) }}</p>

                        <!-- Tags -->
                        <div class="flex mt-3 flex-wrap gap-1">
                            @foreach ($question['group_question'] as $groupQuestion)
                                <span
                                    class="text-xs px-2 py-1 rounded-full bg-[var(--bg-tag)] text-[var(--text-tag)]">{{ $groupQuestion['subject']['name'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            {{ $questions->links() }}
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
        // Enhanced theme and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Update interaction icon colors based on theme
            function updateIconColors() {
                const statsItems = document.querySelectorAll('.stats-item');
                const isLightMode = document.documentElement.classList.contains('light-mode');

                if (statsItems) {
                    statsItems.forEach((item, index) => {
                        const icon = item.querySelector('i');
                        if (!icon) return;

                        // First icon (thumbs up) - green
                        if (index % 3 === 0) {
                            icon.style.color = isLightMode ? '#10b981' : '#23BF7F';
                        }
                        // Second icon (eye) - amber/yellow
                        else if (index % 3 === 1) {
                            icon.style.color = isLightMode ? '#f59e0b' : '#ffd249';
                        }
                        // Third icon (comment) - blue/purple
                        else {
                            icon.style.color = isLightMode ? '#3b82f6' : '#909ed5';
                        }
                    });
                }
            }

            // Theme toggling with icon change
            const themeToggle = document.getElementById('theme-toggle');
            const themeToggleIcon = document.getElementById('theme-toggle-icon');
            const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
            const mobileThemeToggleIcon = document.getElementById('mobile-theme-toggle-icon');
            const themeLogoToggle = document.getElementById('theme-logo');

            function updateThemeIcons() {
                const isLightMode = document.documentElement.classList.contains('light-mode');

                if (themeToggleIcon) {
                    themeToggleIcon.className = isLightMode ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
                }

                if (mobileThemeToggleIcon) {
                    mobileThemeToggleIcon.className = isLightMode ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
                }

                if (themeToggleIcon) {
                    themeToggleIcon.className = isLightMode ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
                }

                if (themeLogoToggle) {
                    themeLogoToggle.src = isLightMode ?
                        "{{ asset('assets/p2p logo.svg') }}" :
                        "{{ asset('assets/p2p logo - white.svg') }}";
                }

            }

            // Run on page load and when theme changes
            updateThemeIcons();

            // Watch for theme changes
            const themeObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        updateThemeIcons();
                    }
                });
            });

            themeObserver.observe(document.documentElement, {
                attributes: true
            });

            // Ensure both theme toggles work
            if (mobileThemeToggle) {
                mobileThemeToggle.addEventListener('click', function() {
                    if (typeof toggleTheme === 'function') {
                        toggleTheme();
                    }
                });
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

                    lazyImages.forEach(image => {
                        imageObserver.observe(image);
                    });
                } else {
                    // Fallback for browsers without IntersectionObserver
                    lazyImages.forEach(image => {
                        image.src = image.dataset.src;
                        image.classList.remove("lazy-image");
                    });
                }
            }

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

            updateIconColors();
            lazyLoadImages();

            // Watch for theme changes
            const themeObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        updateIconColors();
                    }
                });
            });

            themeObserver.observe(document.documentElement, {
                attributes: true
            });

            function showSkeletonLoaders() {
                const questionContainer = document.querySelector('.question-container');
                if (!questionContainer) return;

                const questionCards = questionContainer.querySelectorAll('.question-card');

                if (questionCards.length === 0) {
                    for (let i = 0; i < 3; i++) {
                        const skeletonCard = document.createElement('div');
                        skeletonCard.className = 'question-card skeleton rounded-lg mb-4 p-5 flex';
                        skeletonCard.innerHTML = `
                    <div class="flex flex-col items-center mr-4 space-y-3 px-3 border-r border-[var(--border-color)]">
                        <div class="w-8 h-8 rounded-full bg-gray-300"></div>
                        <div class="w-8 h-8 rounded-full bg-gray-300"></div>
                        <div class="w-8 h-8 rounded-full bg-gray-300"></div>
                    </div>
                    <div class="flex-1">
                        <div class="h-6 bg-gray-300 rounded w-3/4 mb-3"></div>
                        <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                    </div>
                `;
                        questionContainer.appendChild(skeletonCard);
                    }
                }
            }

            function removeSkeletonLoaders() {
                const skeletons = document.querySelectorAll('.skeleton');
                skeletons.forEach(skeleton => {
                    skeleton.classList.remove('skeleton');
                });
            }

            showSkeletonLoaders();
            window.addEventListener('load', removeSkeletonLoaders);
        });

        const saveButtons = document.querySelectorAll('.save-question-btn');

        saveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const questionId = this.getAttribute('data-question-id');
                const icon = this.querySelector('i');
                const isSaved = icon.classList.contains('fa-solid');

                if (isSaved) {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    icon.style.color = '';
                } else {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');

                    const isLightMode = document.documentElement.classList.contains('light-mode');
                    icon.style.color = isLightMode ? '#38A3A5' : '#80ED99';

                    button.classList.add('saved-animation');
                    setTimeout(() => {
                        button.classList.remove('saved-animation');
                    }, 300);
                }

                console.log('Save question:', questionId, !isSaved);

                return false;
            });

        });

        function updateSavedIcons() {
            const savedIcons = document.querySelectorAll('.save-question-btn i.fa-solid');
            const isLightMode = document.documentElement.classList.contains('light-mode');

            savedIcons.forEach(icon => {
                icon.style.color = isLightMode ? '#38A3A5' : '#80ED99';
            });
        }

        updateSavedIcons();
    </script>
@endsection
