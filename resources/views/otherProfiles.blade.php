@extends('layout')

@section('head')
    <style>
        /* Core styling variables that match the homepage */
        :root {
            --profile-primary: #7494ec;
            --profile-secondary: #5f83c8;
            --profile-accent: #38A3A5;
            --profile-accent-hover: #80ED99;
            --tag-bg: #7494ec;
            --tag-bg-hover: #5f83c8;
        }

        /* Profile specific styles */
        .profile-container {
            background-color: var(--bg-secondary);
            transition: background-color var(--transition-speed);
        }

        .profile-card {
            background-color: var(--bg-card);
            border-radius: 1rem;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .profile-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .profile-section {
            transition: all 0.3s ease;
        }

        .profile-section:hover {
            transform: translateY(-2px);
        }

        .profile-tag {
            background-color: var(--tag-bg);
            transition: all 0.2s ease;
        }

        .profile-tag:hover {
            background-color: var(--tag-bg-hover);
            transform: translateY(-1px);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .btn-primary {
            background: linear-gradient(to right, var(--profile-primary), var(--profile-secondary));
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, var(--profile-secondary), var(--profile-primary));
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-accent {
            background: linear-gradient(to right, var(--profile-accent), var(--profile-accent-hover));
            transition: all 0.3s ease;
        }

        .btn-accent:hover {
            background: linear-gradient(to right, var(--profile-accent-hover), var(--profile-accent));
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .achievement-card {
            transition: all 0.3s ease;
        }

        .achievement-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .tag-score {
            transition: all 0.2s ease;
        }

        .tag-score:hover {
            background-color: var(--bg-card-hover);
            border-radius: 0.5rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .profile-post {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-post:hover {
            background-color: var(--bg-card-hover);
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .profile-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection

@section('content')
    @include('partials.nav')
    @include('utils.background3')

    <div class="text-[var(--text-primary)] min-h-screen p-4 sm:p-6 lg:p-8 max-w-[70rem] mx-auto profile-container">
        <!-- Main Content -->
        <div class="w-full rounded-xl shadow-lg overflow-hidden profile-card">
            <div class="bg-gradient-to-r from-[#38A3A5] to-[#80ED99] h-32 sm:h-48 relative">
                <div
                    class="absolute -bottom-16 left-1/2 transform -translate-x-1/2 sm:-bottom-16 sm:left-8 sm:transform-none">
                    <img src="{{ $userViewed['image'] ? asset('storage/' . $userViewed['image']) : 'https://via.placeholder.com/100' }}"
                        alt="Profile Picture" class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover">
                </div>
            </div>

            <!-- Profile Info Section -->
            <div class="pt-20 sm:pt-6 sm:pl-48 px-4 sm:px-8 pb-6">
                <div class="flex flex-col sm:flex-row justify-between items-center sm:items-start">
                    <div class="text-center sm:text-left mb-4 sm:mb-0">
                        <h2 class="text-[var(--text-primary)] text-3xl font-bold cal-sans-regular">{{ $userViewed['username'] }}
                        </h2>
                    </div>

                    <!-- Actions -->
                </div>
            </div>

            <!-- Stats Bar -->
            <div
                class="bg-[var(--bg-card-hover)] px-6 py-4 flex justify-around border-t border-b border-[var(--border-color)]">
                <div class="text-center">
                    <h3 id="countFollowers" class="text-[var(--text-primary)] text-xl font-bold">
                        {{ $userViewed['followers_count'] }}
                    </h3>
                     <a href="{{ route('user.connections', ['email' => $userViewed['email'], 'type' => 'followers']) }}#followers"
                        class="hover:underline">
                        <p class="text-[var(--text-muted-dark)] text-sm">Followers</p>
                    </a>
                </div>
                <div class="text-center">
                    <h3 class="text-[var(--text-primary)] text-xl font-bold"> {{ $userViewed['followings_count'] }}</h3>
                     <a href="{{ route('user.connections', ['email' => $userViewed['email'], 'type' => 'following']) }}#following"
                        class="hover:underline">
                        <p class="text-[var(--text-muted-dark)] text-sm">Followings</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-8">
            <!-- Left Sidebar -->
            <div class="col-span-1 lg:col-span-3 space-y-6">
                <!-- About Section -->
                <div class="profile-card p-6">
                    <h3 class="text-[var(--profile-primary)] text-xl font-bold mb-4 cal-sans-regular">About</h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            class="profile-tag px-3 py-1 text-white bg-[var(--bg-shadow)] text-sm rounded cursor-pointer">angular</span>
                        <span
                            class="profile-tag px-3 py-1 text-white bg-[var(--bg-shadow)] text-sm rounded cursor-pointer">html</span>
                        <span
                            class="profile-tag px-3 py-1 text-white bg-[var(--bg-shadow)] text-sm rounded cursor-pointer">css</span>
                        <span
                            class="profile-tag px-3 py-1 text-white bg-[var(--bg-shadow)] text-sm rounded cursor-pointer">javascript</span>
                    </div>
                </div>

                <!-- Stats Section -->
                 <div class="profile-card p-6">
                    <h3 class="text-[var(--text-primary)] text-xl font-bold mb-4 cal-sans-regular">Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-[var(--text-secondary)]">Reputation</span>
                            <span class="font-bold text-[var(--text-primary)]"> {{ $userViewed['reputation'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[var(--text-secondary)]">Answers</span>
                            <span class="font-bold text-[var(--text-primary)]">{{ $userViewed['answers_count'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('user.questions.list', ['id' => $userViewed['id']]) }}"><span
                                    class="text-[var(--text-secondary)]">Questions</span></a>
                            <span class="font-bold text-[var(--text-primary)]">{{ $userViewed['questions_count'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Top Tags Section -->
                <div class="profile-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[var(--profile-primary)] text-xl font-bold cal-sans-regular">Top Tags</h3>
                        <a href="#" class="text-[var(--profile-accent)] text-sm hover:underline">View All</a>
                    </div>
                    <ul class="space-y-3">
                        @foreach ($userViewed['top_subjects'] as $subjectName => $subjectCount)
                            <li class="flex justify-between items-center tag-score p-1">
                                <span class="text-[var(--text-secondary)]">{{ $subjectName }}</span>
                                <span class="text-[var(--text-secondary)] font-medium">{{ $subjectCount }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-span-1 lg:col-span-9 space-y-6">
                <!-- Achievements Section -->
                <div class="profile-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[var(--profile-primary)] text-xl font-bold cal-sans-regular">Achievements</h3>
                        <a href="#" class="text-[var(--profile-accent)] text-sm hover:underline">View All</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div
                            class="achievement-card bg-gradient-to-br from-[#FFA500] to-[#FFD700] rounded-xl p-5 text-center">
                            <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-medal text-3xl text-[#FFA500]"></i>
                            </div>
                            <h4 class="text-white text-xl font-bold">5</h4>
                            <p class="text-white text-sm">Gold Badges</p>
                        </div>
                        <div
                            class="achievement-card bg-gradient-to-br from-[#A9A9A9] to-[#D3D3D3] rounded-xl p-5 text-center">
                            <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-medal text-3xl text-[#A9A9A9]"></i>
                            </div>
                            <h4 class="text-white text-xl font-bold">5</h4>
                            <p class="text-white text-sm">Silver Badges</p>
                        </div>
                        <div
                            class="achievement-card bg-gradient-to-br from-[#CD7F32] to-[#E6BE8A] rounded-xl p-5 text-center">
                            <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-medal text-3xl text-[#CD7F32]"></i>
                            </div>
                            <h4 class="text-white text-xl font-bold">5</h4>
                            <p class="text-white text-sm">Bronze Badges</p>
                        </div>
                    </div>
                </div>

                <!-- Top Posts Section -->
                <div class="profile-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[var(--profile-primary)] text-xl font-bold cal-sans-regular">Top Posts</h3>
                        <a href="#" class="text-[var(--profile-accent)] text-sm hover:underline">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="profile-post p-4 rounded-lg">
                            <div class="flex items-start">
                                 @if ($userViewed['top_question_post'])
                                    <div class="flex-1">
                                        <a href="{{ route('user.viewQuestions', ['questionId' => $userViewed['top_question_post']['id']]) }}"
                                            class="text-[var(--text-primary)] font-medium hover:underline text-lg">
                                            {{ $userViewed['top_question_post']['title'] }}
                                        </a>
                                        <div class="flex flex-wrap gap-2 mt-2 mb-3">
                                            @foreach ($userViewed['top_question_post']['group_question'] as $groupQuestion)
                                                <span
                                                    class="profile-tag px-2 py-0.5 text-white bg-[var(--bg-shadow)] text-xs rounded">{{ $groupQuestion['subject']['name'] }}</span>
                                            @endforeach
                                        </div>
                                        <div class="flex items-center mt-3 space-x-6">
                                            <div class="flex items-center">
                                                <div class="flex items-center">
                                                    <i class="fa-regular fa-thumbs-up text-[var(--text-muted)] mr-1"></i>
                                                    <span
                                                        class="text-sm text-[var(--text-muted)]">{{ $userViewed['top_question_post']['vote'] }}
                                                        Likes</span>
                                                </div>

                                            </div>
                                            <div class="flex items-center">
                                                <i class="fa-regular fa-comment text-[var(--text-muted)] mr-1"></i>
                                                <span
                                                    class="text-sm text-[var(--text-muted)]">{{ $userViewed['top_question_post']['comment_count'] }}
                                                    Comments</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-eye text-[var(--text-muted)] mr-1"></i>
                                                <span
                                                    class="text-sm text-[var(--text-muted)]">{{ $userViewed['top_question_post']['view'] }}
                                                    Views</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex-1">
                                        You haven't posted anything yet!
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Empty state for demonstration -->
                        <div class="text-center py-8 bg-[var(--bg-card-hover)] rounded-lg">
                            <i class="fa-solid fa-file-circle-plus text-4xl text-[var(--text-muted)] mb-3"></i>
                            <h4 class="text-[var(--text-secondary)] font-medium">No more posts to show</h4>
                            <p class="text-[var(--text-muted)] text-sm mt-1 mb-4">Write a new question to share your
                                knowledge</p>
                            <a href="{{ route('askPage') }}"
                                class="ask-question-btn {{ request()->routeIs('askPage') ? 'active-ask' : '' }} bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium text-[0.75rem] p-2 rounded-lg items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                                <i class="fa-solid fa-question-circle mr-2"></i> Ask a Question
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Activity Feed -->
                <div class="profile-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[var(--profile-primary)] text-xl font-bold cal-sans-regular">Recent Activity</h3>
                        <a href="#" class="text-[var(--profile-accent)] text-sm hover:underline">View All</a>
                    </div>

                    <!-- Activity Timeline -->
                    <div
                        class="relative pl-8 space-y-8 before:absolute before:inset-0 before:h-full before:w-[2px] before:bg-[var(--border-color)] before:left-3">
                        <!-- Empty state -->
                        <div class="text-center py-8">
                            <i class="fa-solid fa-chart-line text-4xl text-[var(--text-muted)] mb-3"></i>
                            <h4 class="text-[var(--text-secondary)] font-medium">No recent activity</h4>
                            <p class="text-[var(--text-muted)] text-sm mt-1">Your activity will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Enhance interactive elements on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Profile tag hover effects
            const profileTags = document.querySelectorAll('.profile-tag');
            profileTags.forEach(tag => {
                tag.addEventListener('mouseover', () => {
                    tag.classList.add('scale-105');
                });
                tag.addEventListener('mouseout', () => {
                    tag.classList.remove('scale-105');
                });
            });

            // Achievement cards effects
            const achievementCards = document.querySelectorAll('.achievement-card');
            achievementCards.forEach(card => {
                card.addEventListener('mouseover', () => {
                    card.querySelector('i').classList.add('fa-bounce');
                });
                card.addEventListener('mouseout', () => {
                    card.querySelector('i').classList.remove('fa-bounce');
                });
            });

            // Adapt to theme changes
            const themeObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        // Theme has changed, update any theme-dependent elements
                        updateThemeElements();
                    }
                });
            });

            themeObserver.observe(document.documentElement, {
                attributes: true
            });

            function updateThemeElements() {
                const isLightMode = document.documentElement.classList.contains('light-mode');
                // Update elements based on theme if needed
            }

            // Initial call
            updateThemeElements();
        });
    </script>
@endsection
