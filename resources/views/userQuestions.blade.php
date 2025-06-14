@extends('layout')

@section('content')
    <style>
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

        .header-gradient {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .action-button {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .action-button:hover::before {
            left: 100%;
        }

        .stats-item {
            transition: all 0.2s ease;
        }

        .stats-item:hover {
            transform: scale(1.05);
        }
        .action-menu-dropdown {
            transition: opacity 0.2s ease, transform 0.2s ease;
            transform-origin: top right;
        }
</style>
    </style>
    
    @include('partials.nav')
    @include('utils.background')

    <!-- Header Section -->
    <div class="w-full bg-transparent rounded-lg p-6 px-8 max-w-5xl justify-start mt-6 mb-6 flex items-start space-x-5 popular-container backdrop-blur-sm relative overflow-hidden">
        <div class="text-3xl relative p-4 rounded-full bg-[var(--bg-primary)] z-10">
            <i class="fa-solid fa-question-circle bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent leading-tight p-0.5"></i>
        </div>

        <div class="flex flex-col z-10">
            <div>
                <h1 class="cal-sans-regular text-4xl lg:text-5xl bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent leading-tight py-1">
                    @if (session('email') === $user['email'])
                    My Questions
                @else
                    {{ $user['username'] }}'s Questions
                @endif
                </h1>
                {{-- <div class="h-1 w-24 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] rounded-full mt-2"></div> --}}
            </div>
            <p class="text-[var(--text-muted)] text-lg leading-relaxed max-w-3xl mt-2">
                @if (session('email') === $user['email'])
                    Manage and track your questions
                @else
                    Track {{ $user['username'] }}'s questions
                @endif
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="justify-start items-start max-w-8xl px-4 flex space-x-6">
        <!-- Questions List -->
        <div class="w-full bg-transparent rounded-lg p-6 shadow-lg max-w-3xl justify-start items-start">
            @if (!empty($user['question']) && count($user['question']) > 0)
                @foreach ($user['question'] as $index => $question)
                    <div id="question-item-{{ $question['id'] }}"
                        class="question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[var(--accent-tertiary)] relative overflow-hidden"
                        data-url="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}" style="cursor: pointer;">
                        
                        <div class="absolute inset-0 bg-pattern opacity-5"></div>

                        <!-- Action Buttons (for owner only) -->
                        @if (session('email') === ($user['email'] ?? null))
                            @php
                                $hasAnswer = !empty($question['answer']);
                                $hasVote = isset($question['vote']) && $question['vote'] !== 0;
                            @endphp
                            @if (!$hasAnswer && !$hasVote)
                                <div class="action-menu-container absolute top-3 right-3 z-20">
                                    <button class="action-menu-button w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-secondary)] text-[var(--text-primary)]"
                                            title="More options">
                                        <i class="fa-solid fa-ellipsis-v"></i>
                                    </button>

                                    <div class="action-menu-dropdown hidden absolute right-0 mt-2 w-40 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-lg shadow-xl py-2">
                                        <button data-question-id="{{ $question['id'] }}"
                                                class="edit-question-button w-full flex items-center px-4 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--bg-secondary)]">
                                            <i class="fa-solid fa-edit text-sm w-6 mr-2"></i>
                                            Edit
                                        </button>
                                        <button data-question-id="{{ $question['id'] }}"
                                                class="delete-question-button w-full flex items-center px-4 py-2 text-sm text-[var(--accent-neg)] hover:bg-[var(--bg-secondary)]">
                                            <i class="fa-solid fa-trash text-sm w-6 mr-2"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Stats Section -->
                        <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]">
                            <div class="stats-item flex flex-row items-center space-x-2">
                                <span class="text-sm font-medium">{{ $question['vote'] ?? 0 }}</span>
                                <i class="text-sm fa-regular fa-thumbs-up"></i>
                            </div>
                            <div class="stats-item flex flex-row items-center space-x-2">
                                <span class="text-sm font-medium">{{ $question['view'] ?? 0 }}</span>
                                <i class="text-sm fa-solid fa-eye"></i>
                            </div>
                            <div class="stats-item flex flex-row items-center space-x-2">
                                <span class="text-sm font-medium">{{ count($question['answer'] ?? []) }}</span>
                                <i class="text-sm fa-regular fa-comment"></i>
                            </div>
                        </div>

                        <!-- Question Content -->
                        <div class="flex-1 pt-0 mr-4 z-10">
                            <h2 class="text-xl font-medium text-[var(--text-highlight)] question-title transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2">
                                {{ $question['title'] ?? 'Untitled Question' }}
                            </h2>

                            @if (isset($question['question_content']) && is_string($question['question_content']))
                                <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                                    {{ \Str::limit(strip_tags($question['question_content']), 150) }}
                                </p>
                            @elseif (isset($question['question']) && is_string($question['question']))
                                <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                                    {{ \Str::limit(strip_tags($question['question']), 150) }}
                                </p>
                            @endif

                            <!-- Tags -->
                            @if (!empty($question['group_question']) && is_array($question['group_question']))
                                <div class="flex mt-2 flex-wrap gap-1 items-center tags-wrapper" data-question-id="{{ $question['id'] }}">
                                    @php
                                        $tags = $question['group_question'];
                                        $totalTags = count($tags);
                                        $displayLimit = 3;
                                    @endphp

                                    @foreach ($tags as $index => $tag)
                                        @if(isset($tag['subject']['name']))
                                            <a href="{{ route('home', ['filter_tag' => $tag['subject']['name'], 'sort_by' => 'latest', 'page' => 1]) }}"
                                               class="question-tag-link @if($index >= $displayLimit) hidden extra-tag-{{ $question['id'] }} @endif">
                                                <span class="hover:border-[var(--accent-secondary)] lowercase font-semibold hover:border-2 text-xs px-2 py-1 rounded-10 bg-[var(--bg-light)] text-[var(--text-tag)]">
                                                    {{ $tag['subject']['name'] }}
                                                </span>
                                            </a>
                                        @endif
                                    @endforeach

                                    @if ($totalTags > $displayLimit)
                                        <span class="text-xs text-[var(--accent-secondary)] cursor-pointer hover:underline more-tags-button"
                                              data-question-id="{{ $question['id'] }}"
                                              data-initial-text="+ {{ $totalTags - $displayLimit }} more">
                                             + {{ $totalTags - $displayLimit }} more
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Created Date -->
                            @if (isset($question['created_at']))
                                <div class="mt-3 text-sm text-[var(--text-muted)]">
                                    Posted on {{ \Carbon\Carbon::parse($question['created_at'])->format('M d, Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div id="no-questions-message" class="text-center py-8">
                    <i class="fa-regular fa-folder-open text-4xl text-[var(--text-muted)] mb-3"></i>
                    <h3 class="text-xl font-medium text-[var(--text-primary)] mb-2">
                        @if (session('email') === $user['email'])
                            You haven't asked any questions yet.
                        @else
                            {{ $user['username'] }} has not posted any questions yet.
                        @endif
                    </h3>
                    <p class="text-[var(--text-muted)] mb-4">
                        Start contributing by asking your first question!
                    </p>
                    <a href="{{ route('askPage') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium rounded-lg hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Ask Question
                    </a>
                </div>
            @endif
        </div>

        <div class="w-72 mt-6 ml-6 hidden md:flex flex-col space-y-6 sticky top-24 h-fit">
            <!-- User Profile Card -->
            <div class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.15)] to-[rgba(128,237,153,0.15)]"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)]"></div>

                <div class="flex flex-col items-center text-center relative z-10">
                    <img class="w-16 h-16 rounded-full ring-4 ring-[var(--accent-primary)] ring-opacity-20 mb-4"
                        src="{{ $image ? asset('storage/' . $image) : 'https://ui-avatars.com/api/?name=' . urlencode($user['username'] ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                        alt="{{ $user['username'] }}'s avatar">
                    
                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-2">
                        {{ $user['username'] ?? 'User' }}
                    </h3>
                    
                    <div class="w-full mb-4">
                        @if (session('email') === $user['email'])
                            <a href="{{ route('viewUser', ['email' => session('email')]) }}"
                                class="w-full inline-flex items-center justify-center text-[var(--text-highlight)] hover:text-[var(--accent-primary)] font-medium text-sm transition-colors duration-300 py-2">
                                <i class="fas fa-arrow-left mr-2"></i>
                                View My Profile
                            </a>
                        @else
                            <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                class="w-full inline-flex items-center justify-center text-[var(--text-highlight)] hover:text-[var(--accent-primary)] font-medium text-sm transition-colors duration-300 py-2">
                                <i class="fas fa-user mr-2"></i>
                                View {{ $user['username'] }}'s Profile
                            </a>
                        @endif
                    </div>

                    <div class="w-full pt-4 border-t border-[var(--border-color)]">
                        <h4 class="font-medium mb-3 text-sm">Quick Stats</h4>
                        <div class="space-y-2 text-left">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-[var(--text-secondary)]">Questions</span>
                                <span class="font-medium text-[var(--text-primary)]">{{ count($user['question'] ?? []) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-[var(--text-secondary)]">Total Views</span>
                                <span class="font-medium text-[var(--text-primary)]">
                                    {{ collect($user['question'] ?? [])->sum('view') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-[var(--text-secondary)]">Total Votes</span>
                                <span class="font-medium text-[var(--text-primary)]">
                                    {{ collect($user['question'] ?? [])->sum('vote') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ask Question Card -->
            <div class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden">
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
                    <a href="{{ route('askPage') }}"
                        class="w-full ask-question-btn bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium py-2.5 text-md px-4 rounded-lg flex items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-plus mr-2"></i> Ask a Question
                    </a>
                    
                    <div class="w-full mt-5 pt-5 border-t border-[var(--border-color)]">
                        <h3 class="font-medium mb-3 text-sm">Quick Links</h3>
                        <ul class="space-y-2 text-left">
                            {{-- <li class="flex items-center text-sm">
                                <i class="fa-solid fa-bookmark mr-2 text-[var(--accent-primary)]"></i>
                                <a href="{{ route('user.saved.questions', ['id' => $user['id'] ?? session('user_id')]) }}"
                                    class="text-[var(--text-secondary)] hover:text-[var(--text-highlight)] transition-colors">Saved Questions</a>
                            </li> --}}
                            <li class="flex items-center text-sm">
                                <i class="fa-solid fa-comments mr-2 text-[var(--accent-secondary)]"></i>
                                <a href="{{ route('user.answers.index', ['userId' => $user['id'] ?? session('user_id')]) }}"
                                    class="text-[var(--text-secondary)] hover:text-[var(--text-highlight)] transition-colors">My Answers</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('script')
    <script>
        function initClickableQuestionCards() {
            document.querySelectorAll('.question-card').forEach(card => {
                if (card.dataset.clickableInitialized === 'true') return;
                card.addEventListener('click', function(event) {
                    if (event.target.closest('.edit-question-button') ||
                        event.target.closest('.delete-question-button') ||
                        event.target.closest('.question-tag-link') ||
                        event.target.closest('.more-tags-button')) {
                        return;
                    }
                    const url = this.dataset.url;
                    if (url) {
                        window.location.href = url;
                    }
                });
                card.dataset.clickableInitialized = 'true';
            });
        }

        function initTagToggles() {
            document.querySelectorAll('.more-tags-button').forEach(button => {
                if (button.dataset.toggleInitialized === 'true') return;
                button.addEventListener('click', function(event) {
                    event.stopPropagation();
                    const questionId = this.dataset.questionId;
                    const extraTags = document.querySelectorAll(`.extra-tag-${questionId}`);
                    const isCurrentlyHidden = extraTags.length > 0 && extraTags[0].classList.contains('hidden');
                    extraTags.forEach(tag => tag.classList.toggle('hidden', !isCurrentlyHidden));
                    this.textContent = isCurrentlyHidden ? 'show less' : this.dataset.initialText;
                });
                button.dataset.toggleInitialized = 'true';
            });
        }

        function updateIconColors() {
            const statsItems = document.querySelectorAll('.stats-item');
            const isLightMode = document.documentElement.classList.contains('light-mode');
            if (statsItems) {
                statsItems.forEach((item, index) => {
                    const icon = item.querySelector('i');
                    if (!icon) return;
                    if (index % 3 === 0) icon.style.color = isLightMode ? 'var(--stats-icon-color-1-light, #10b981)' : 'var(--stats-icon-color-1-dark, #23BF7F)';
                    else if (index % 3 === 1) icon.style.color = isLightMode ? 'var(--stats-icon-color-2-light, #f59e0b)' : 'var(--stats-icon-color-2-dark, #ffd249)';
                    else icon.style.color = isLightMode ? 'var(--stats-icon-color-3-light, #3b82f6)' : 'var(--stats-icon-color-3-dark, #909ed5)';
                });
            }
        }

        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        document.addEventListener('DOMContentLoaded', function() {
            const API_BASE_URL = (("{{ env('API_URL') }}" || window.location.origin) + '/').replace(/\/+$/, '/');
            const API_TOKEN = "{{ session('token') ?? '' }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";

            // Initialize all functions
            initClickableQuestionCards();
            initTagToggles();
            updateIconColors();
            initActionMenus();

            // Edit button functionality
            document.querySelectorAll('.edit-question-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const questionId = this.dataset.questionId;
                    window.location.href = `{{ url('/ask') }}/${questionId}`;
                });
            });

            // Delete button functionality
            function checkEmptyQuestionState() {
                const questionContainer = document.querySelectorAll('[id^="question-item-"]');
                if (questionContainer.length === 0) {
                    const container = document.querySelector('.max-w-3xl.justify-start.items-start');
                    if (container) {
                        container.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fa-regular fa-folder-open text-4xl text-[var(--text-muted)] mb-3"></i>
                                <h3 class="text-xl font-medium text-[var(--text-primary)] mb-2">
                                    @if (session('email') === $user['email'])
                                        You haven't asked any questions yet.
                                    @else
                                        {{ $user['username'] }} has not posted any questions yet.
                                    @endif
                                </h3>
                                <p class="text-[var(--text-muted)] mb-4">
                                    Start contributing by asking your first question!
                                </p>
                                <a href="{{ route('askPage') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium rounded-lg hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                                    <i class="fa-solid fa-plus mr-2"></i>
                                    Ask Question
                                </a>
                            </div>
                        `;
                    }
                    location.reload();
                }
            }

            document.querySelectorAll('.delete-question-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const questionId = this.dataset.questionId;
                    const questionItemElement = document.getElementById(`question-item-${questionId}`);

                    Swal.fire({
                        title: 'Delete Question?',
                        text: "This action cannot be undone. Your question will be permanently deleted.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        background: 'var(--bg-card)',
                        color: 'var(--text-primary)',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-6 py-3',
                            cancelButton: 'rounded-xl px-6 py-3'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const headers = {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            };
                            if (API_TOKEN) {
                                headers['Authorization'] = `Bearer ${API_TOKEN}`;
                            }

                            fetch(`${API_BASE_URL}questions/${questionId}`, {
                                    method: 'DELETE',
                                    headers: headers
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        return response.json().then(err => {
                                            throw err;
                                        });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success || data.status === 'success') {
                                        Toastify({
                                            text: 'Your question has been deleted.',
                                            duration: 3000,
                                            style: {
                                                background: "linear-gradient(to right, #00b09b, #96c93d)"
                                            }
                                        }).showToast();
                                        if (questionItemElement) {
                                            questionItemElement.style.animation =
                                                'fadeOutUp 0.5s ease forwards';
                                            setTimeout(() => {
                                                questionItemElement.remove();
                                                checkEmptyQuestionState();
                                            }, 500);
                                        }
                                        const questionsContainer = document
                                            .getElementById('questions-container');
                                        const noQuestionsMessage = document
                                            .getElementById('no-questions-message');
                                        if (questionsContainer && noQuestionsMessage &&
                                            questionsContainer.children.length === 0) {
                                            noQuestionsMessage.style.display = 'block';
                                            if (questionsContainer.parentElement
                                                .contains(noQuestionsMessage)) {
                                                // If it was previously hidden, make sure it's visible
                                            } else {
                                                // If it was removed, you might need to re-add or just unhide
                                            }
                                            questionsContainer.style.display = 'none';
                                        }
                                    } else {
                                        Toastify({
                                            text: data.message ||
                                                'Could not delete the question.',
                                            duration: 3000,
                                            style: {
                                                background: "#e74c3c"
                                            }
                                        }).showToast();
                                    }
                                })
                                .catch(error => {
                                    console.error('Error details:', error);
                                    let errorMessage =
                                        'An error occurred while deleting the question.';
                                    if (error && error.message) {
                                        errorMessage = error.message;
                                    } else if (typeof error === 'object' && error !==
                                        null && error.toString && error.toString()
                                        .includes('Failed to fetch')) {
                                        errorMessage =
                                            'Network error or API is unreachable. Please check your connection and the API URL.';
                                    } else if (typeof error === 'string') {
                                        errorMessage = error;
                                    }
                                    Toastify({
                                        text: errorMessage ||
                                            'Something went wrong',
                                        duration: 3000,
                                        style: {
                                            background: "#e74c3c"
                                        }
                                    }).showToast();
                                });
                        }
                    });
                });
            });

            // Theme observer
            if (typeof window.pageThemeObserver === 'undefined' && typeof MutationObserver !== 'undefined') {
                window.pageThemeObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class' && mutation.target === document.documentElement) {
                            updateIconColors();
                        }
                    });
                });
                window.pageThemeObserver.observe(document.documentElement, { attributes: true });
            }

            // ... inside the <script> tag ...

            function initActionMenus() {
                const menuButtons = document.querySelectorAll('.action-menu-button');

                menuButtons.forEach(button => {
                    button.addEventListener('click', (event) => {
                        event.stopPropagation(); // Prevents the card's click event from firing

                        // Find the dropdown associated with this button
                        const dropdown = button.nextElementSibling;

                        // Close all other open dropdowns first
                        document.querySelectorAll('.action-menu-dropdown').forEach(d => {
                            if (d !== dropdown) {
                                d.classList.add('hidden');
                            }
                        });

                        // Toggle the current dropdown
                        dropdown.classList.toggle('hidden');
                    });
                });

                // Add a global click listener to close menus when clicking outside
                window.addEventListener('click', (event) => {
                    if (!event.target.closest('.action-menu-container')) {
                        document.querySelectorAll('.action-menu-dropdown').forEach(dropdown => {
                            dropdown.classList.add('hidden');
                        });
                    }
                });
            }
        });
    </script>
@endsection