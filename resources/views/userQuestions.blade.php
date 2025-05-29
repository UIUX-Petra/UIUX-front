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
        </div>
        <hr class="my-6 border-gray-700">


        @if (!empty($user['question']) && count($user['question']) > 0)
            @foreach ($user['question'] as $question)
                <div id="question-item-{{ $question['id'] }}"
                    class="question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[var(--accent-tertiary)] relative overflow-hidden">
                    <div class="absolute inset-0 bg-pattern opacity-5"></div>
                    {{-- Kolom Stats --}}
                    @if (session('email') === $user['email'])
                        <div
                            class="flex flex-col items-end justify-start mr-4 pt-1 space-y-12 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]">
                        @else
                            <div
                                class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]">
                    @endif

                    <div class="stats-item flex flex-row items-center space-x-2">
                        <span class="text-sm font-medium">{{ $question['vote'] ?? 0 }}</span>
                        <i class="text-sm fa-regular fa-thumbs-up"></i>
                    </div>
                    <div class="stats-item flex flex-row items-center space-x-2">
                        <span class="text-sm font-medium">{{ $question['view'] ?? 0 }}</span>
                        <i class="text-sm fa-solid fa-eye"></i>
                    </div>
                    <div class="stats-item flex flex-row items-center space-x-2">
                        <span class="text-sm font-medium">{{ $question['comments_count'] ?? 0 }}</span>
                        <i class="text-sm fa-regular fa-comment"></i>
                    </div>
                </div>

                {{-- Konten Pertanyaan Utama --}}
                <div class="flex-1 pt-0 mr-4 z-10">
                    <h2
                        class="text-xl font-medium text-[var(--text-highlight)] question-title cursor-pointer transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2">
                        <a
                            href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] ?? 'Untitled Question' }}</a>
                    </h2>
                    @if (isset($question['question_content']) && is_string($question['question_content']))
                        <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                            {{ Str::limit($question['question_content'], 200) }}</p>
                    @elseif (isset($question['question']) && is_string($question['question']))
                        <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                            {{ Str::limit($question['question'], 200) }}</p>
                    @endif
                    <div class="flex mt-2 flex-wrap gap-1">
                        @if (isset($question['group_question']) && is_array($question['group_question']))
                            @foreach ($question['group_question'] as $tag)
                                @if (isset($tag['subject']['name']))
                                    <a
                                        href="{{ route('popular', ['filter_tag' => $tag['subject']['name'], 'sort_by' => 'latest', 'page' => 1]) }}"><span
                                            class="hover:border-white hover:border-2 text-xs px-2 py-1 font-bold rounded-full bg-[var(--bg-light)] text-[var(--text-tag)]">
                                            {{ $tag['subject']['name'] }}
                                        </span></a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="mt-4 text-sm text-gray-500">
                        @if (isset($question['created_at']))
                            <span>Posted on:
                                {{ \Carbon\Carbon::parse($question['created_at'])->format('M d, Y') }}</span>
                        @endif
                    </div>




                    @if (session('email') === ($user['email'] ?? null))
                        @if (empty($question['answer']) && (isset($question['vote']) && $question['vote'] === 0))
                            <div class="flex space-x-3 justify-end">
                                <button data-question-id="{{ $question['id'] }}"
                                    class="edit-question-button action-button inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">

                                    <i class="fas fa-edit mr-2"></i>Edit
                                </button>
                                <button
                                    class="delete-question-button action-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl"
                                    data-question-id="{{ $question['id'] }}">
                                    <i class="fas fa-trash-alt mr-2"></i>Delete
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
    </div>
    @endforeach
@else
    <div id="no-questions-message" class="bg-white shadow-md rounded-lg p-6 text-center">
        <p class="text-gray-600 text-lg">
            @if (session('email') === $user['email'])
                You have
            @else
                {{ $user['username'] }} has
            @endif
            not posted any questions yet.
        </p>
    </div>
    @endif

    <div class="mt-10 text-center">
        @if (session('email') === $user['email'])
            <div class="mt-16 text-center" data-aos="fade-up">
                <a href="{{ route('seeProfile') }}"
                    class="inline-flex items-center text-[var(--text-highlight)] hover:text-[var(--accent-primary)] font-medium text-lg transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to My Profile
                </a>
            </div>
        @else
            <div class="mt-16 text-center" data-aos="fade-up">
                <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                    class="inline-flex items-center text-[var(--text-highlight)] hover:text-[var(--accent-primary)] font-medium text-lg transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to {{ $user['username'] }}'s Profile
                </a>
            </div>
        @endif
    </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection

@section('script')
    <script>
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

            document.querySelectorAll('.delete-question-button').forEach(button => {
                button.addEventListener('click', function() {
                    const questionId = this.dataset.questionId;
                    const questionItemElement = document.getElementById(
                        `question-item-${questionId}`);

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
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
                                        Swal.fire(
                                            'Deleted!',
                                            data.message ||
                                            'Your question has been deleted.',
                                            'success'
                                        );
                                        if (questionItemElement) {
                                            questionItemElement.remove();
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
                                        Swal.fire(
                                            'Error!',
                                            data.message ||
                                            'Could not delete the question.',
                                            'error'
                                        );
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
                                    Swal.fire(
                                        'Request Failed!',
                                        errorMessage,
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });
        });
    </script>
@endsection
