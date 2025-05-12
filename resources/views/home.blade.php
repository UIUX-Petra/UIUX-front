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
    <div class="w-full bg-transparent rounded-lg p-6 px-6 max-w-8xl mx-auto my-5 flex items-center space-x-4 welcome-container">
        <div class="text-5xl">
            <img src="{{ asset('assets/p2p logo - white.svg') }}" alt="Logo" class="h-8 lg:h-10 w-auto theme-logo">
        </div>
        <div class="flex flex-col">
            @if (session()->has('email'))
                <h1 class="cal-sans-regular welcome lg:text-3xl text-xl mb-1">
                    Welcome, {{ $username }}!
                </h1>
                <p class="text-[var(--text-secondary)] text-lg pl-0.5 font-regular">
                    Ask questions, share answers, and learn together.
                </p>
            @endif
        </div>

        <a href="{{ route('askPage') }}" class="ask-question-btn {{ request()->routeIs('askPage') ? 'active-ask' : '' }} md:hidden flex bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium my-10 py-2 text-md px-4 rounded-lg items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-question-circle mr-2"></i> Ask a Question
        </a>
    </div>
    
    <!-- Questions and Ask Question Section -->
    <div class="justify-start items-start max-w-8xl px-4 flex space-x-6">
        <!-- Questions Section -->
        <div class="w-full bg-transparent rounded-lg p-6 shadow-lg max-w-3xl justify-start items-start">
            <h3 class="cal-sans-regular lg:text-xl text-lg ml-2 mt-4 mb-4">Newest Questions</h3>
            <!-- Loop through questions -->
            @foreach ($questions as $question)
                <div class="question-card rounded-lg mb-2 p-4 pb-8 transition-all duration-200 flex">
                    <div class="flex flex-col items-end justify-end mr-4 pt-1 space-y-2 pl-6">
                        <div class="p-0 font-semibold inline-flex flex-row items-center space-x-1 cursor-auto">
                            <i class="text-sm fa-regular fa-thumbs-up bg-transparent pr-0.5"></i>
                            <span class="text-[0.70rem] question-interaction text-[var(--text-secondary)]">{{ $question['vote'] }}</span>
                        </div>
                        <div class="p-0 font-semibold inline-flex flex-row items-center space-x-1 cursor-auto">
                            <i class="text-sm fa-solid fa-eye bg-transparent pr-0.5"></i>
                            <span class="text-[0.70rem] text-[var(--text-secondary)]">{{ $question['view'] }}</span>
                        </div>
                        <div class="p-0 font-semibold inline-flex flex-row items-center space-x-1 cursor-auto">
                            <i class="text-sm fa-regular fa-comment bg-transparent pr-0.5"></i>
                            <span class="text-[0.70rem] text-[var(--text-secondary)]">{{ $question['comments_count'] }}</span>
                        </div>
                    </div>

                    <div class="flex-1">
                        <!-- Question Title -->
                        <h2 class="text-xl question-title cursor-pointer transition-colors duration-200">
                            <a href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] }}</a>
                        </h2>

                        <!-- Question Snippet -->
                        <p class="text-[var(--text-muted)] text-md text-justify mt-0.5">{{ \Str::limit($question['question'], 150) }}</p>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            {{ $questions->links() }}
        </div>

        <!-- Ask Question Card -->
        <div class="w-64 mt-12 ml-6 hidden md:flex relative">
            <div class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)]">
                <div class="flex flex-col items-center text-center">
                    <div class="mb-4">
                        <i class="fa-solid fa-question-circle text-4xl text-[var(--accent-tertiary)] mb-3"></i>
                    </div>
                    <h2 class="text-xl font-bold text-[var(--text-primary)] mb-2">
                        Stuck on a Question?
                    </h2>
                    <p class="text-[var(--text-muted)] mb-6 text-md">
                        Ask fellow Petranesian Informates and get insights from your peers!
                    </p>
                    
                    <a href="{{ route('askPage') }}" class="w-full ask-question-btn bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium py-2 text-md px-4 rounded-lg flex items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-plus mr-2"></i> Ask a Question
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Update interaction icon colors based on theme
    function updateIconColors() {
        const icons = document.querySelectorAll('.question-card i');
        const isLightMode = document.documentElement.classList.contains('light-mode');
        
        if (icons) {
            icons.forEach((icon, index) => {
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
    
    // Run on page load and when theme changes
    document.addEventListener('DOMContentLoaded', updateIconColors);
    
    // Watch for theme changes
    const themeObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                updateIconColors();
            }
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        themeObserver.observe(document.documentElement, { attributes: true });
    });
</script>
@endsection