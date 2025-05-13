@extends('layout')

@section('head')
    <style>
        /* Add these to your existing CSS */
        .popular-title {
            background: -webkit-linear-gradient(#ffb700, #ff8c00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .popular-container {
            background-color: var(--bg-card);
            border-radius: 1rem;
            transition: all var(--transition-speed);
            border: 1px solid var(--border-color);
        }

        .popular-container:hover {
            border-color: rgba(245, 158, 11, 0.3);
        }

        .popular-question-card {
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            transition: all 0.3s ease;
        }

        .popular-question-card:hover {
            border-color: #f59e0b;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.08);
            transform: translateY(-2px);
        }

        /* Stats items in question cards */
        .stats-item {
            transition: transform 0.2s;
        }

        .stats-item:hover {
            transform: translateY(-2px);
        }

        /* Pagination styling */
        .pagination-container nav {
            display: flex;
            justify-content: center;
        }

        .pagination-container nav > div {
            @apply bg-[var(--bg-card)];
            @apply rounded-lg;
            @apply shadow-sm;
            @apply border;
            @apply border-[var(--border-color)];
            @apply overflow-hidden;
        }

        .pagination-container .relative.inline-flex {
            @apply px-3;
            @apply py-2;
            @apply text-sm;
            @apply bg-transparent;
            @apply text-[var(--text-secondary)];
            @apply transition-colors;
            @apply duration-200;
        }

        .pagination-container .relative.inline-flex:hover {
            @apply bg-[rgba(245,158,11,0.1)];
            @apply text-amber-500;
        }

        .pagination-container span[aria-current="page"] span {
            @apply bg-[rgba(245,158,11,0.2)];
            @apply text-amber-500;
        }

        /* Improved accessibility focus styles */
        button:focus-visible, 
        a:focus-visible, 
        input:focus-visible, 
        select:focus-visible {
            outline: 2px solid #f59e0b;
            outline-offset: 2px;
        }

        /* Skeleton loading animation */
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
        /* Mobile-specific improvements */
        @media (max-width: 768px) {
            .popular-container {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.25rem;
            }
            
            .popular-container .text-3xl {
                margin-bottom: 1rem;
            }
            
            .popular-container .flex.space-x-4 {
                margin-top: 1rem;
                overflow-x: auto;
                width: 100%;
                padding-bottom: 0.5rem;
            }
            
            .popular-question-card {
                padding: 1rem;
            }
            
            .stats-item {
                padding-right: 0.5rem;
            }
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

   <!-- Header Section with improved visual appeal -->
    <div class="w-full bg-transparent rounded-lg p-6 px-8 max-w-7xl mx-auto mt-6 mb-6 flex items-center space-x-5 popular-container backdrop-blur-sm relative overflow-hidden">
        <!-- Decorative fire elements -->
        <div class="absolute -right-20 -bottom-28 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(245,158,11,0.15)] to-[rgba(250,204,21,0.15)] blur-2xl"></div>
        <div class="absolute -left-10 -top-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(245,158,11,0.1)] to-[rgba(250,204,21,0.1)] blur-xl"></div>
        
        <div class="text-4xl relative p-3 rounded-full bg-[rgba(245,158,11,0.15)] z-10">
            <i class="fa-solid fa-fire text-[#f59e0b]"></i>
        </div>
        
        <div class="flex flex-col z-10">
            <h1 class="cal-sans-regular popular-title lg:text-3xl text-2xl mb-2 font-bold">
                Popular Questions
            </h1>
            <p class="text-[var(--text-secondary)] text-lg pl-0.5 font-regular max-w-xl">
                Hottest discussions voted by the community. These questions have received the most engagement.
            </p>
            
            <!-- Add time filter options -->
            <div class="flex space-x-4 mt-4">
                <button class="px-3 py-1 rounded-full bg-[rgba(245,158,11,0.2)] text-[#f59e0b] text-sm font-medium hover:bg-[rgba(245,158,11,0.3)] transition-colors">Today</button>
                <button class="px-3 py-1 rounded-full bg-transparent text-[var(--text-muted)] text-sm font-medium hover:bg-[rgba(245,158,11,0.1)] transition-colors">This Week</button>
                <button class="px-3 py-1 rounded-full bg-transparent text-[var(--text-muted)] text-sm font-medium hover:bg-[rgba(245,158,11,0.1)] transition-colors">This Month</button>
                <button class="px-3 py-1 rounded-full bg-transparent text-[var(--text-muted)] text-sm font-medium hover:bg-[rgba(245,158,11,0.1)] transition-colors">All Time</button>
            </div>
        </div>
    </div>

    <!-- Add this below the header section -->
    <div class="max-w-7xl mx-auto px-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[var(--bg-card)] rounded-lg p-5 text-center border border-[var(--border-color)] hover:border-[#f59e0b] transition-all duration-200">
                <div class="flex justify-center mb-3">
                    <div class="p-3 rounded-full bg-[rgba(245,158,11,0.15)]">
                        <i class="fa-solid fa-trophy text-2xl text-amber-500"></i>
                    </div>
                </div>
                <h4 class="text-lg font-medium mb-1">Top Contributor</h4>
                <p class="text-sm text-[var(--text-muted)] mb-2">This Month</p>
                <div class="flex items-center justify-center">
                    <img src="{{ asset('assets/default-avatar.png') }}" class="w-8 h-8 rounded-full mr-2">
                    <span class="font-medium">User123</span>
                </div>
            </div>
            
            <div class="bg-[var(--bg-card)] rounded-lg p-5 text-center border border-[var(--border-color)] hover:border-[#f59e0b] transition-all duration-200">
                <div class="flex justify-center mb-3">
                    <div class="p-3 rounded-full bg-[rgba(245,158,11,0.15)]">
                        <i class="fa-solid fa-star text-2xl text-amber-500"></i>
                    </div>
                </div>
                <h4 class="text-lg font-medium mb-1">Most Upvoted Question</h4>
                <p class="text-sm text-[var(--text-muted)] mb-2">This Week</p>
                <a href="#" class="font-medium hover:text-[#f59e0b] transition-colors">How to implement sorting algorithms?</a>
            </div>
            
            <div class="bg-[var(--bg-card)] rounded-lg p-5 text-center border border-[var(--border-color)] hover:border-[#f59e0b] transition-all duration-200">
                <div class="flex justify-center mb-3">
                    <div class="p-3 rounded-full bg-[rgba(245,158,11,0.15)]">
                        <i class="fa-solid fa-fire-flame-curved text-2xl text-amber-500"></i>
                    </div>
                </div>
                <h4 class="text-lg font-medium mb-1">Trending Topic</h4>
                <p class="text-sm text-[var(--text-muted)] mb-2">Right Now</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <span class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.25)] text-amber-500">Machine Learning</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main content area with questions list and sidebar -->
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Questions List with enhanced design -->
            <div class="w-full md:w-3/4 bg-transparent rounded-lg">
                @foreach ($questions as $question)
                    <div class="question-card popular-question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[#f59e0b] relative overflow-hidden">
                        <!-- Hot indicator for extremely popular questions -->
                        @if($question['vote'] > 50)
                            <div class="absolute top-0 right-0">
                                <div class="bg-gradient-to-r from-amber-500 to-amber-400 text-white text-xs py-1 px-3 rounded-bl-lg rounded-tr-lg font-medium flex items-center">
                                    <i class="fa-solid fa-fire-flame-curved mr-1.5"></i> Hot
                                </div>
                            </div>
                        @endif
                        
                        <!-- Stats Column -->
                        <div class="flex flex-col items-center justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)]">
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
                            <h2 class="text-xl font-medium question-title cursor-pointer transition-colors duration-200 hover:underline decoration-[#f59e0b] decoration-2 underline-offset-2">
                                <a href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] }}</a>
                            </h2>

                            <!-- Question Snippet with better readability -->
                            <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">{{ \Str::limit($question['question'], 150) }}</p>
                            
                            <!-- Tags and engagement indicator -->
                            <div class="flex mt-3 flex-wrap gap-2 items-center">
                                <span class="text-xs px-2 py-1 rounded-full bg-[var(--bg-tag)] text-[var(--text-tag)]">tag1</span>
                                <span class="text-xs px-2 py-1 rounded-full bg-[var(--bg-tag)] text-[var(--text-tag)]">tag2</span>
                                
                                <!-- Engagement indicator -->
                                <span class="ml-auto text-xs text-[var(--text-muted)] flex items-center">
                                    <i class="fa-solid fa-chart-line text-amber-500 mr-1.5"></i>
                                    {{ rand(50, 95) }}% engagement rate
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination with enhanced styling -->
                <div class="pagination-container mt-8">
                    {{ $questions->links() }}
                </div>
            </div>

            <!-- Sidebar with Ask Question Card -->
            <div class="md:w-1/4 w-full">
                <div class="sticky top-24">
                    <div class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden">
                        <!-- Decorative elements -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.15)] to-[rgba(128,237,153,0.15)]"></div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)]"></div>
                        
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
                            
                            <a href="{{ route('askPage') }}" class="w-full ask-question-btn bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium py-2.5 text-md px-4 rounded-lg flex items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                                <i class="fa-solid fa-plus mr-2"></i> Ask a Question
                            </a>
                            
                            <!-- Popular topics -->
                            <div class="w-full mt-5 pt-5 border-t border-[var(--border-color)]">
                                <h3 class="font-medium mb-3 text-sm">Popular Topics</h3>
                                <div class="flex flex-wrap gap-2">
                                    <a href="#" class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.15)] text-amber-500 hover:bg-[rgba(245,158,11,0.25)] transition-colors">Programming</a>
                                    <a href="#" class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.15)] text-amber-500 hover:bg-[rgba(245,158,11,0.25)] transition-colors">Design</a>
                                    <a href="#" class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.15)] text-amber-500 hover:bg-[rgba(245,158,11,0.25)] transition-colors">Data Science</a>
                                    <a href="#" class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.15)] text-amber-500 hover:bg-[rgba(245,158,11,0.25)] transition-colors">Algorithms</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Enhanced theme and interaction handling
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
        
        // Time period selection handling
        const timeButtons = document.querySelectorAll('.popular-container button');
        if (timeButtons) {
            timeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    timeButtons.forEach(btn => {
                        btn.classList.remove('bg-[rgba(245,158,11,0.2)]');
                        btn.classList.add('bg-transparent');
                        btn.classList.remove('text-[#f59e0b]');
                        btn.classList.add('text-[var(--text-muted)]');
                    });
                    
                    // Add active class to clicked button
                    this.classList.remove('bg-transparent');
                    this.classList.add('bg-[rgba(245,158,11,0.2)]');
                    this.classList.remove('text-[var(--text-muted)]');
                    this.classList.add('text-[#f59e0b]');
                    
                    // Here you would typically fetch data for the selected time period
                    // For demonstration, we'll just show a loading state
                    showLoadingState();
                    
                    // Simulate loading completion after 1 second
                    setTimeout(removeLoadingState, 1000);
                });
            });
        }
        
        // Show skeleton loading animation
        function showLoadingState() {
            const questionContainer = document.querySelector('.questions-list');
            if (!questionContainer) return;
            
            // Save current content
            questionContainer.dataset.originalContent = questionContainer.innerHTML;
            
            // Clear and add skeletons
            questionContainer.innerHTML = '';
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
        
        // Remove skeleton loading animation
        function removeLoadingState() {
            const questionContainer = document.querySelector('.questions-list');
            if (!questionContainer || !questionContainer.dataset.originalContent) return;
            
            questionContainer.innerHTML = questionContainer.dataset.originalContent;
            updateIconColors();
        }
        
        // Initialize
        updateIconColors();
        
        // Watch for theme changes
        const themeObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    updateIconColors();
                }
            });
        });
        
        themeObserver.observe(document.documentElement, { attributes: true });
        
        // Add hover effects to stats cards
        const communityCards = document.querySelectorAll('.grid > div');
        if (communityCards) {
            communityCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 10px 25px rgba(245, 158, 11, 0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });
        }
    });
</script>
@endsection