
@extends('layout')

@section('style')
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
        .answer-card {
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .answer-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--button-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .answer-card:hover::before {
            transform: scaleX(1);
        }

        .answer-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .answer-content {
            position: relative;
        }

        .answer-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .answer-content img:hover {
            transform: scale(1.02);
        }

        .stats-badge {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
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

        .question-link {
            background: rgba(56, 163, 165, 0.1);
            color: var(--text-highlight);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(56, 163, 165, 0.2);
        }

        .question-link:hover {
            background: rgba(56, 163, 165, 0.2);
            transform: scale(1.05);
            text-decoration: none;
        }

        .header-gradient {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .fade-in:nth-child(1) {
            animation-delay: 0.1s;
        }

        .fade-in:nth-child(2) {
            animation-delay: 0.2s;
        }

        .fade-in:nth-child(3) {
            animation-delay: 0.3s;
        }

        .fade-in:nth-child(4) {
            animation-delay: 0.4s;
        }

        .fade-in:nth-child(5) {
            animation-delay: 0.5s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOutUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-30px);
            }
        }

        .loading-skeleton {
            background: linear-gradient(90deg, var(--bg-card) 25%, var(--bg-card-hover) 50%, var(--bg-card) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .no-answers-illustration {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
        }

        .floating-button {
            background: var(--button-primary);
            color: var(--button-text);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(56, 163, 165, 0.3);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .floating-button:hover {
            transform: translateY(-4px) scale(1.1);
            box-shadow: 0 12px 40px rgba(56, 163, 165, 0.4);
        }

        .answer-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .vote-indicator {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .vote-indicator.positive {
            color: var(--accent-secondary);
            border-color: var(--accent-secondary);
        }

        .vote-indicator.negative {
            color: #ef4444;
            border-color: #ef4444;
        }

        .stats-item {
            transition: all 0.2s ease;
        }

        .stats-item:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .floating-action {
                bottom: 1rem;
                right: 1rem;
            }

            .floating-button {
                width: 50px;
                height: 50px;
            }
        }
        .action-menu-dropdown {
            transition: opacity 0.2s ease, transform 0.2s ease;
            transform-origin: top right;
        }
    </style>
@endsection

@section('content')
    @include('partials.nav')
    @include('utils.background')

    <!-- Header Section -->
    <div class="w-full bg-transparent rounded-lg p-6 px-8 max-w-5xl justify-start mt-6 mb-6 flex items-start space-x-5 popular-container backdrop-blur-sm relative overflow-hidden">
        <div class="text-3xl relative p-4 rounded-full bg-[var(--bg-primary)] z-10">
            <i class="fa-solid fa-comments bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent leading-tight p-0.5"></i>
        </div>

        <div class="flex flex-col z-10">
            <div>
                <h1 class="cal-sans-regular text-4xl lg:text-5xl bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent leading-tight py-1">
                    @if (session('email') === $user['email'])
                        My Answers
                    @else
                        {{ $user['username'] }}'s Answers
                    @endif
                </h1>
            </div>
            <p class="text-[var(--text-muted)] text-lg leading-relaxed max-w-3xl mt-2">
                @if (session('email') === $user['email'])
                    Manage and track your answers
                @else
                    Track {{ $user['username'] }}'s answers
                @endif
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="justify-start items-start max-w-8xl px-4 flex space-x-6">
        <!-- Answers List -->
        <div class="w-full bg-transparent rounded-lg p-6 shadow-lg max-w-3xl justify-start items-start">
            @if (session('success'))
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg mb-6 shadow-lg"
                    data-aos="fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-4 rounded-lg mb-6 shadow-lg"
                    data-aos="fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if (isset($apiError))
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-black p-4 rounded-lg mb-6 shadow-lg"
                    data-aos="fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                        <span class="font-medium">{{ $apiError }}</span>
                    </div>
                </div>
            @endif

            @if (!empty($answers) && count($answers) > 0)
                <div class="space-y-4" id="answers-container">
                    @foreach ($answers as $index => $answer)
                        <div id="answer-item-{{ $answer['id'] }}"
                            class="answer-card rounded-lg mb-4 p-5 transition-all duration-200 relative overflow-hidden fade-in"
                            data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            
                            <div class="absolute inset-0 bg-pattern opacity-5"></div>

                            <!-- Action Buttons (for owner only) -->
                            @if (session('email') === ($user['email'] ?? null))
                                @if (isset($answer['user_id']) && ($answer['votes_count'] ?? 0) == 0)
                                    {{-- New Action Menu Container --}}
                                    <div class="action-menu-container absolute top-3 right-3 z-20">
                                        {{-- Three-dot button to toggle the menu --}}
                                        <button class="action-menu-button w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-secondary)] text-[var(--text-primary)]"
                                                title="More options">
                                            <i class="fa-solid fa-ellipsis-v"></i>
                                        </button>

                                        {{-- Dropdown menu with the original actions --}}
                                        <div class="action-menu-dropdown hidden absolute right-0 mt-2 w-40 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-lg shadow-xl py-2">
                                            <a href="{{ route('user.answers.edit', ['answerId' => $answer['id']]) }}"
                                            class="w-full flex items-center px-4 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--bg-secondary)]">
                                                <i class="fa-solid fa-edit text-sm w-6 mr-2"></i>
                                                Edit
                                            </a>
                                            <button data-answer-id="{{ $answer['id'] }}"
                                                    class="delete-answer-button w-full flex items-center px-4 py-2 text-sm text-red-500 hover:bg-[var(--bg-secondary)]">
                                                <i class="fa-solid fa-trash text-sm w-6 mr-2"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- Answer Content -->
                            <div class="flex-1 pt-0 mr-20 z-10">
                                <!-- Answer Meta -->
                                <div class="answer-meta mb-4">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ \Carbon\Carbon::parse($answer['created_at'])->format('M d, Y') }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ \Carbon\Carbon::parse($answer['created_at'])->format('H:i') }}</span>
                                    </div>

                                    {{-- <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)] float-right"> --}}
                                        <div class="meta-item">
                                            <i class="fas fa-heart text-[var(--text-highlight)]"></i>
                                            <span class="text-sm font-medium">{{ $answer['vote'] ?? 0 }}</span>
                                        </div>
                                    {{-- </div> --}}
                                    @if (isset($answer['question']) && $answer['question'])
                                        <div class="meta-item">
                                            <i class="fas fa-question-circle"></i>
                                            <a href="{{ route('user.viewQuestions', ['questionId' => $answer['question']['id']]) }}"
                                                class="question-link">
                                                {{ Str::limit($answer['question']['title'] ?? 'Question', 40) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Answer Content -->
                                <div class="answer-content prose prose-lg max-w-none text-[var(--text-primary)] mb-6">
                                    <div class="text-[var(--text-secondary)] text-md leading-relaxed">
                                        {!! nl2br(e($answer['answer'])) !!}
                                    </div>
                                </div>

                                <!-- Answer Image -->
                                @if (!empty($answer['image']))
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $answer['image']) }}" alt="Answer image"
                                            class="rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer max-w-full h-auto"
                                            onclick="openImageModal(this.src)">
                                    </div>
                                @endif
                            </div>

                            <div class="clear-both"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div id="no-answers-message" class="text-center py-8">
                    <i class="fa-regular fa-folder-open text-4xl text-[var(--text-muted)] mb-3"></i>
                    <h3 class="text-xl font-medium text-[var(--text-primary)] mb-2">
                        @if (session('email') === $user['email'])
                            You haven't answered any questions yet.
                        @else
                            {{ $user['username'] }} has not answered any questions yet.
                        @endif
                    </h3>
                    <p class="text-[var(--text-muted)] mb-4">
                        Start contributing to the community by answering questions. Your knowledge can help others!
                    </p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium rounded-lg hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Browse Questions
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="w-72 mt-6 ml-6 hidden md:flex flex-col space-y-6 sticky top-24 h-fit">
            <!-- User Profile Card -->
            <div class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.15)] to-[rgba(128,237,153,0.15)]"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)]"></div>

                <div class="flex flex-col items-center text-center relative z-10">
                    <img class="w-16 h-16 rounded-full ring-4 ring-[var(--accent-primary)] ring-opacity-20 mb-4"
                        src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['username'] ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                        alt="{{ $user['username'] ?? 'User' }}'s avatar">
                    
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
                                <span class="text-[var(--text-secondary)]">Answers</span>
                                <span class="font-medium text-[var(--text-primary)]">{{ count($answers ?? []) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-[var(--text-secondary)]">Total Votes</span>
                                <span class="font-medium text-[var(--text-primary)]">
                                    {{ collect($answers ?? [])->sum('vote') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.15)] to-[rgba(128,237,153,0.15)]"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)]"></div>
                
                <div class="flex flex-col items-center text-center relative z-10">
                    <div class="mb-5 bg-[var(--bg-accent-subtle)] p-3 rounded-full">
                        <i class="fa-solid fa-lightbulb text-3xl text-[var(--accent-tertiary)]"></i>
                    </div>
                    <h2 class="text-xl font-bold text-[var(--text-primary)] mb-3">
                        Keep Contributing!
                    </h2>
                    <p class="text-[var(--text-muted)] mb-6 text-md leading-relaxed">
                        Help fellow Petranesian Informates by sharing your knowledge and insights!
                    </p>
                    <a href="{{ route('home') }}"
                        class="w-full ask-question-btn bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium py-2.5 text-md px-4 rounded-lg flex items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-search mr-2"></i> Browse Questions
                    </a>
                    
                    <div class="w-full mt-5 pt-5 border-t border-[var(--border-color)]">
                        <h3 class="font-medium mb-3 text-sm">Quick Links</h3>
                        <ul class="space-y-2 text-left">
                            {{-- <li class="flex items-center text-sm">
                                <i class="fa-solid fa-question-circle mr-2 text-[var(--accent-primary)]"></i>
                                <a href="{{ route('user.questions.index', ['userId' => $user['id'] ?? session('user_id')]) }}"
                                    class="text-[var(--text-secondary)] hover:text-[var(--text-highlight)] transition-colors">My Questions</a>
                            </li> --}}
                            <li class="flex items-center text-sm">
                                <i class="fa-solid fa-plus mr-2 text-[var(--accent-secondary)]"></i>
                                <a href="{{ route('askPage') }}"
                                    class="text-[var(--text-secondary)] hover:text-[var(--text-highlight)] transition-colors">Ask Question</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-full rounded-lg">
            <button onclick="closeImageModal()"
                class="absolute top-4 right-4 bg-white bg-opacity-20 text-white p-2 rounded-full hover:bg-opacity-30 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const API_BASE_URL = ("{{ env('API_URL', 'http://localhost:8001/api') }}" + '/').replace(/\/+$/, '/');
            const API_TOKEN = "{{ session('token') ?? '' }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";

            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 100
            });

            initActionMenus(); // <-- ADD THIS LINE HERE

            // Search functionality
            const searchInput = document.getElementById('searchAnswers');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const answerCards = document.querySelectorAll('[id^="answer-item-"]');

                    answerCards.forEach(card => {
                        const content = card.textContent.toLowerCase();
                        if (content.includes(searchTerm)) {
                            card.style.display = 'block';
                            card.style.animation = 'fadeInUp 0.5s ease forwards';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            // Filter functionality
            const filterButtons = document.querySelectorAll('.filter-button');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    const answerCards = document.querySelectorAll('[id^="answer-item-"]');

                    answerCards.forEach((card, index) => {
                        let show = true;

                        if (filter === 'recent') {
                            const date = new Date(card.dataset.answerDate);
                            const now = new Date();
                            const daysDiff = (now - date) / (1000 * 60 * 60 * 24);
                            show = daysDiff <= 7;
                        } else if (filter === 'popular') {
                            const votes = parseInt(card.dataset.answerVotes) || 0;
                            show = votes > 0;
                        }

                        if (show) {
                            card.style.display = 'block';
                            setTimeout(() => {
                                card.style.animation =
                                    'fadeInUp 0.5s ease forwards';
                            }, index * 100);
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Delete functionality with enhanced UX
            document.querySelectorAll('.delete-answer-button').forEach(button => {
                button.addEventListener('click', function() {
                    const answerId = this.dataset.answerId;
                    const answerItemElement = document.getElementById(`answer-item-${answerId}`);

                    Swal.fire({
                        title: 'Delete Answer?',
                        text: "This action cannot be undone. Your answer will be permanently deleted.",
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
                            // Show loading state
                            this.innerHTML =
                                '<i class="fas fa-spinner fa-spin mr-2"></i>';
                            this.disabled = true;

                            const headers = {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${API_TOKEN}`,
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            };

                            fetch(`${API_BASE_URL}answers/${answerId}`, {
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
                                    if (data.success) {
                                        Toastify({
                                            text: data.message ||
                                                'Your answer has been deleted successfully.',
                                            duration: 3000,
                                            close: true,
                                            gravity: "top",
                                            position: "right",
                                            style: {
                                                background: "linear-gradient(to right, #00b09b, #96c93d)"
                                            }
                                        }).showToast();

                                        // Swal.fire({
                                        //     title: 'Deleted!',
                                        //     text: data.message ||
                                        //         'Your answer has been deleted successfully.',
                                        //     icon: 'success',
                                        //     background: 'var(--bg-card)',
                                        //     color: 'var(--text-primary)',
                                        //     confirmButtonColor: 'var(--accent-secondary)',
                                        //     customClass: {
                                        //         popup: 'rounded-2xl',
                                        //         confirmButton: 'rounded-xl px-6 py-3'
                                        //     }
                                        // });

                                        // Animate removal
                                        if (answerItemElement) {
                                            answerItemElement.style.animation =
                                                'fadeOutUp 0.5s ease forwards';
                                            setTimeout(() => {
                                                answerItemElement.remove();
                                                checkEmptyState();
                                            }, 500);
                                        }
                                    } else {
                                        throw new Error(data.message ||
                                            'Could not delete the answer.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error details:', error);
                                    let errorMessage =
                                        'An error occurred while deleting the answer.';
                                    if (error && error.message) {
                                        errorMessage = error.message;
                                    } else if (error && typeof error === 'object' &&
                                        error.errors) {
                                        errorMessage = Object.values(error.errors)
                                            .flat().join(' ');
                                    }

                                    Toastify({
                                        text: errorMessage ||
                                            'An error occurred while deleting the answer.',
                                        duration: 3000,
                                        close: true,
                                        gravity: "top",
                                        position: "right",
                                        style: {
                                            background: "#e74c3c"
                                        }
                                    }).showToast();

                                    // Swal.fire({
                                    //     title: 'Error!',
                                    //     text: errorMessage,
                                    //     icon: 'error',
                                    //     background: 'var(--bg-card)',
                                    //     color: 'var(--text-primary)',
                                    //     confirmButtonColor: '#ef4444',
                                    //     customClass: {
                                    //         popup: 'rounded-2xl',
                                    //         confirmButton: 'rounded-xl px-6 py-3'
                                    //     }
                                    // });

                                    // Reset button state
                                    this.innerHTML =
                                        '<i class="fas fa-trash-alt mr-2"></i>Delete';
                                    this.disabled = false;
                                });
                        }
                    });
                });
            });

            // Utility functions
            function checkEmptyState() {
                const answersContainer = document.getElementById('answers-container');
                if (answersContainer && answersContainer.children.length === 0) {
                    // Show empty state
                    const emptyState = `
                        <div class="text-center py-20" data-aos="fade-up">
                            <div class="max-w-md mx-auto">
                                <div class="mb-8">
                                    <i class="fas fa-comments no-answers-illustration text-8xl mb-4"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-4">
                                    No answers yet
                                </h3>
                                <p class="text-[var(--text-muted)] text-lg mb-8">
                                    Start contributing to the community by answering questions. Your knowledge can help others!
                                </p>
                                <a href="{{ route('home') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[var(--accent-primary)] to-[var(--accent-secondary)] text-white font-medium rounded-xl hover:scale-105 transition-all duration-300 shadow-lg">
                                    <i class="fas fa-search mr-2"></i>
                                    Browse Questions
                                </a>
                            </div>
                        </div>
                    `;
                    answersContainer.innerHTML = emptyState;

                    // Hide search bar
                    const searchBar = document.querySelector('.search-filter-bar');
                    if (searchBar) {
                        searchBar.style.display = 'none';
                    }
                }
            }
        });

        // Global functions
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = src;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on click outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Add fadeOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOutUp {
                to {
                    opacity: 0;
                    transform: translateY(-30px);
                }
            }
            
            /* Enhanced hover effects */
            .answer-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .answer-card:hover {
                transform: translateY(-8px) scale(1.02);
            }
            
            /* Smooth scrolling for better UX */
            html {
                scroll-behavior: smooth;
            }
            
            /* Loading states */
            .btn-loading {
                position: relative;
                pointer-events: none;
            }
            
            .btn-loading::after {
                content: '';
                position: absolute;
                width: 16px;
                height: 16px;
                margin: auto;
                border: 2px solid transparent;
                border-top-color: currentColor;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            /* Enhanced responsive design */
            @media (max-width: 640px) {
                .answer-card {
                    padding: 1.5rem;
                    margin: 0 -0.5rem;
                }
                
                .stats-badge {
                    font-size: 0.75rem;
                    padding: 0.125rem 0.5rem;
                }
                
                .action-button {
                    padding: 0.5rem 1rem;
                    font-size: 0.875rem;
                }
            }
            
            /* Improved accessibility */
            .answer-card:focus-within {
                outline: 2px solid var(--accent-primary);
                outline-offset: 2px;
            }
            
            /* Enhanced image gallery effect */
            .answer-content img {
                cursor: zoom-in;
                position: relative;
            }
            
            .answer-content img::after {
                content: '🔍';
                position: absolute;
                top: 10px;
                right: 10px;
                background: rgba(0,0,0,0.7);
                color: white;
                padding: 0.25rem 0.5rem;
                border-radius: 4px;
                font-size: 0.75rem;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .answer-content img:hover::after {
                opacity: 1;
            }
        `;
        document.head.appendChild(style);




        // Add intersection observer for better performance
        const observerOptions = {
            root: null,
            rootMargin: '50px 0px',
            threshold: 0.1
        };

        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        // Observe all answer cards
        document.querySelectorAll('.answer-card').forEach(card => {
            cardObserver.observe(card);
        });

        // Add touch gestures for mobile
        let touchStartY = 0;
        let touchEndY = 0;

        document.addEventListener('touchstart', e => {
            touchStartY = e.changedTouches[0].screenY;
        });

        document.addEventListener('touchend', e => {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartY - touchEndY;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe up - could trigger some action
                    console.log('Swiped up');
                } else {
                    // Swipe down - could refresh or go back
                    console.log('Swiped down');
                }
            }
        }

        // Add loading states for better UX
        function addLoadingState(button, text = 'Loading...') {
            const originalText = button.innerHTML;
            button.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i>${text}`;
            button.disabled = true;
            button.classList.add('btn-loading');

            return () => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.classList.remove('btn-loading');
            };
        }

        // Enhanced error handling
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transition-all transform translate-x-full`;

            const bgColors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            toast.classList.add(bgColors[type] || bgColors.info);
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'} mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Add context menu for answers (right-click options)
        document.querySelectorAll('.answer-card').forEach(card => {
            card.addEventListener('contextmenu', function(e) {
                e.preventDefault();

                // Remove existing context menus
                document.querySelectorAll('.context-menu').forEach(menu => menu.remove());

                const contextMenu = document.createElement('div');
                contextMenu.className =
                    'context-menu fixed bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 py-2';
                contextMenu.style.left = e.pageX + 'px';
                contextMenu.style.top = e.pageY + 'px';

                const answerId = card.id.replace('answer-item-', '');

                contextMenu.innerHTML = `
                    <button onclick="copyAnswerLink('${answerId}')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                        <i class="fas fa-link mr-2"></i>Copy Link
                    </button>
                    <button onclick="shareAnswer('${answerId}')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                        <i class="fas fa-share mr-2"></i>Share
                    </button>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <button onclick="reportAnswer('${answerId}')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-red-600">
                        <i class="fas fa-flag mr-2"></i>Report
                    </button>
                `;

                document.body.appendChild(contextMenu);

                // Position menu if it goes off screen
                const rect = contextMenu.getBoundingClientRect();
                if (rect.right > window.innerWidth) {
                    contextMenu.style.left = (e.pageX - rect.width) + 'px';
                }
                if (rect.bottom > window.innerHeight) {
                    contextMenu.style.top = (e.pageY - rect.height) + 'px';
                }

                // Close menu on click outside
                setTimeout(() => {
                    document.addEventListener('click', function closeMenu() {
                        contextMenu.remove();
                        document.removeEventListener('click', closeMenu);
                    });
                }, 100);
            });
        });

        // Context menu functions
        function copyAnswerLink(answerId) {
            const url = `${window.location.origin}/answers/${answerId}`;
            navigator.clipboard.writeText(url).then(() => {
                showToast('Answer link copied to clipboard!', 'success');
            }).catch(() => {
                showToast('Failed to copy link', 'error');
            });
        }

        function shareAnswer(answerId) {
            if (navigator.share) {
                navigator.share({
                    title: 'Check out this answer',
                    url: `${window.location.origin}/answers/${answerId}`
                });
            } else {
                copyAnswerLink(answerId);
            }
        }

        function reportAnswer(answerId) {
            Swal.fire({
                title: 'Report Answer',
                text: 'Why are you reporting this answer?',
                input: 'select',
                inputOptions: {
                    spam: 'Spam',
                    inappropriate: 'Inappropriate content',
                    harassment: 'Harassment',
                    misinformation: 'Misinformation',
                    other: 'Other'
                },
                showCancelButton: true,
                confirmButtonText: 'Report',
                confirmButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    showToast('Report submitted. Thank you for helping keep our community safe.', 'success');
                }
            });
        }

        // Add print functionality
        function printAnswers() {
            const printWindow = window.open('', '_blank');
            const answersHTML = document.getElementById('answers-container').innerHTML;

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>My Answers</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .answer-card { margin-bottom: 30px; padding: 20px; border: 1px solid #ccc; }
                        .answer-content { margin: 15px 0; }
                        img { max-width: 100%; height: auto; }
                        @media print {
                            .action-button { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <h1>My Answers</h1>
                    ${answersHTML}
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.print();
        }

        // Add export functionality
        function exportAnswers() {
            const answers = Array.from(document.querySelectorAll('.answer-card')).map(card => {
                return {
                    content: card.querySelector('.answer-content').textContent.trim(),
                    date: card.dataset.answerDate,
                    votes: card.dataset.answerVotes
                };
            });

            const dataStr = JSON.stringify(answers, null, 2);
            const dataBlob = new Blob([dataStr], {
                type: 'application/json'
            });

            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = 'my-answers.json';
            link.click();

            showToast('Answers exported successfully!', 'success');
        }

        function initActionMenus() {
            const menuButtons = document.querySelectorAll('.action-menu-button');

            menuButtons.forEach(button => {
                // Prevent adding multiple listeners
                if (button.dataset.menuInitialized) return;
                button.dataset.menuInitialized = 'true';

                button.addEventListener('click', (event) => {
                    event.stopPropagation(); // Prevents other click events on the card

                    const dropdown = button.nextElementSibling;

                    // Close all other open dropdowns
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
    </script>
@endsection
