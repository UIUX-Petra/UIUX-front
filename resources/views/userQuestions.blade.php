@extends('layout')

@section('content')
    <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Ccircle cx='10' cy='10' r='1'/%3E%3C/g%3E%3C/svg%3E");
            background-size: 20px 20px;
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

        .header-gradient {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    @include('partials.nav')
    @include('utils.background')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex space-x-5 text-white">

            <img class="w-16 h-16 rounded-full ring-4 ring-[var(--accent-primary)] ring-opacity-20"
                src="{{ $image ? asset('storage/' . $image) : 'https://ui-avatars.com/api/?name=' . urlencode($user['username'] ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                alt="{{ $user['username'] }}'s avatar">
            <div class="flex-col md:flex-row flex w-full justify-between items-center">
                <div class="flex flex-col">

                    @if (session('email') === $user['email'])
                        <h1 class="text-4xl font-bold header-gradient mb-2">
                            My Questions
                        </h1>
                        <p class="text-[var(--text-muted)]">
                            Manage and track your questions
                        </p>
                    @else
                        <h1 class="text-4xl font-bold header-gradient mb-2">
                            {{ $user['username'] }}'s Questions
                        </h1>

                        <p class="text-[var(--text-muted)]">
                            Track {{ $user['username'] }}'s questions
                        </p>
                    @endif

                </div>

                <div class="mt-4">
                    @if (session('email') === $user['email'])
                        <div class="text-center" data-aos="fade-up">
                            <a href="{{ route('seeProfile') }}"
                                class="inline-flex items-center text-[var(--text-highlight)] hover:text-[var(--accent-primary)] font-medium text-lg transition-colors duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Go to My Profile
                            </a>
                        </div>
                    @else
                        <div class="text-center" data-aos="fade-up">
                            <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                class="inline-flex items-center text-[var(--text-highlight)] hover:text-[var(--accent-primary)] font-medium text-lg transition-colors duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Go to {{ $user['username'] }}'s Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
        <hr class="my-6 border-gray-700">


        @if (!empty($user['question']) && count($user['question']) > 0)
            <div class="space-y-8" id="questions-container">
                @foreach ($user['question'] as $index => $question)
                    <div id="question-item-{{ $question['id'] }}"
                        class="question-card shadow-xl rounded-2xl p-8 transition-all duration-500 fade-in"
                        data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                        {{-- Header stats --}}
                        <div class="flex items-start mb-4 space-x-6">
                            <div class="flex flex-col items-start text-[var(--text-primary)] space-y-2">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-regular fa-thumbs-up"></i>
                                    <span class="text-sm font-medium">{{ $question['vote'] ?? 0 }} votes</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-eye"></i>
                                    <span class="text-sm font-medium">{{ $question['view'] ?? 0 }} views</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-reply"></i>
                                    <span class="text-sm font-medium">{{ count($question['answer']) ?? 0 }} answers</span>
                                </div>
                            </div>

                            {{-- Konten Pertanyaan --}}
                            <div class="flex-1">
                                <h2
                                    class="text-xl font-semibold text-[var(--text-highlight)] hover:underline underline-offset-2 decoration-[var(--accent-secondary)]">
                                    <a href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">
                                        {{ $question['title'] ?? 'Untitled Question' }}
                                    </a>
                                </h2>

                                @if (isset($question['question_content']) && is_string($question['question_content']))
                                    <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                                        {{ Str::limit($question['question_content'], 200) }}
                                    </p>
                                @elseif (isset($question['question']) && is_string($question['question']))
                                    <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                                        {{ Str::limit($question['question'], 200) }}
                                    </p>
                                @endif

                                {{-- Tags --}}
                                <div class="flex mt-2 flex-wrap gap-2">
                                    @foreach ($question['group_question'] ?? [] as $tag)
                                        @if (isset($tag['subject']['name']))
                                            <a href="{{ route('home', ['filter_tag' => $tag['subject']['name'], 'sort_by' => 'latest', 'page' => 1]) }}"
                                                class="text-xs px-2 py-1 font-bold rounded-full bg-[var(--bg-light)] text-[var(--text-tag)] hover:border-white hover:border-2">
                                                {{ $tag['subject']['name'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Created at --}}
                                <div class="mt-4 text-sm text-gray-500">
                                    @if (isset($question['created_at']))
                                        <span>Posted on:
                                            {{ \Carbon\Carbon::parse($question['created_at'])->format('M d, Y') }}</span>
                                    @endif
                                </div>

                                {{-- Action Buttons --}}
                                @if (session('email') === ($user['email'] ?? null))
                                    @php
                                        $hasAnswer = !empty($question['answer']);
                                        $hasVote = isset($question['vote']) && $question['vote'] !== 0;
                                        // $tooltipMessage =
                                        //     $hasAnswer && $hasVote
                                        //         ? 'Your question has been answered and voted.'
                                        //         : ($hasVote
                                        //             ? 'Your question has been voted.'
                                        //             : ($hasAnswer
                                        //                 ? 'Your question has been answered.'
                                        //                 : ''));
                                    @endphp

                                    <div class="flex justify-end space-x-3 mt-4">
                                        @if (!$hasAnswer && !$hasVote)
                                            <button data-question-id="{{ $question['id'] }}"
                                                class="edit-question-button inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                                <i class="fas fa-edit mr-2"></i>Edit
                                            </button>
                                            <button data-question-id="{{ $question['id'] }}"
                                                class="delete-question-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                                <i class="fas fa-trash-alt mr-2"></i>Delete
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div id="no-questions-message" class="text-center py-20" data-aos="fade-up">
                <div class="max-w-md mx-auto">
                    <div class="mb-8">
                        <i class="fas fa-question-circle text-8xl text-[var(--text-muted)] mb-4"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-4">
                        @if (session('email') === $user['email'])
                            You haven't asked any questions yet.
                        @else
                            {{ $user['username'] }} has not posted any questions yet.
                        @endif
                    </h3>
                    <p class="text-[var(--text-muted)] text-lg mb-8">
                        Start contributing by asking your first question!
                    </p>
                    <a href="{{ route('askPage') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[var(--accent-primary)] to-[var(--accent-secondary)] text-white font-medium rounded-xl hover:scale-105 transition-all duration-300 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Ask Question
                    </a>
                </div>
            </div>
        @endif


    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection

@section('script')
    <script>
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

            AOS.init({
                duration: 800,
                once: true,
                offset: 100
            });

            document.querySelectorAll('.edit-question-button').forEach(button => {
                button.addEventListener('click', function() {
                    const questionId = this.dataset.questionId;
                    window.location.href = `{{ url('/ask') }}/${questionId}`;
                });
            });

            function checkEmptyQuestionState() {
                const questionContainer = document.querySelectorAll('[id^="question-item-"]');
                if (questionContainer.length === 0) {
                    const emptyStateHTML = `
                <div class="text-center py-20" data-aos="fade-up">
                    <div class="max-w-md mx-auto">
                        <div class="mb-8">
                            <i class="fas fa-question-circle no-answers-illustration text-8xl mb-4"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-4">
                            No questions yet
                        </h3>
                        <p class="text-[var(--text-muted)] text-lg mb-8">
                            Ask something to start a discussion or learn something new.
                        </p>
                        <a href="{{ route('askPage') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[var(--accent-primary)] to-[var(--accent-secondary)] text-white font-medium rounded-xl hover:scale-105 transition-all duration-300 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Ask a Question
                        </a>
                    </div>
                </div>
            `;
                    const container = document.getElementById('no-questions-message');
                    if (container) {
                        container.innerHTML = emptyStateHTML;
                    }
                    location.reload();
                }
            }
            document.querySelectorAll('.delete-question-button').forEach(button => {
                button.addEventListener('click', function() {
                    const questionId = this.dataset.questionId;
                    const questionItemElement = document.getElementById(
                        `question-item-${questionId}`);

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
                                            close: true,
                                            gravity: "top",
                                            position: "right",
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
                                            close: true,
                                            gravity: "top",
                                            position: "right",
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
                                        close: true,
                                        gravity: "top",
                                        position: "right",
                                        style: {
                                            background: "#e74c3c"
                                        }
                                    }).showToast();
                                });
                        }
                    });
                });
            });



            const style = document.createElement('style');
            style.textContent = `
            @keyframes fadeOutUp {
                to {
                    opacity: 0;
                    transform: translateY(-30px);
                }
            }
            
            /* Enhanced hover effects */
            .question-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .question-card:hover {
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
                .question-card {
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
            
            /* Dark mode enhancements */
            .dark-mode .question-card {
                backdrop-filter: blur(20px);
                background: rgba(28, 34, 70, 0.8);
            }
            
            .light-mode .question-card {
                backdrop-filter: blur(20px);
                background: rgba(246, 247, 255, 0.9);
            }
            
            /* Improved accessibility */
            .question-card:focus-within {
                outline: 2px solid var(--accent-primary);
                outline-offset: 2px;
            }
            
            /* Enhanced image gallery effect */
            .question-content img {
                cursor: zoom-in;
                position: relative;
            }
            
            .question-content img::after {
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
            
            .question-content img:hover::after {
                opacity: 1;
            }
        `;
            document.head.appendChild(style);
        });
    </script>
@endsection
