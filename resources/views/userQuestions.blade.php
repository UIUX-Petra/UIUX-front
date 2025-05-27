@extends('layout')

@section('content')
    @include('partials.nav')
    @include('utils.background')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex space-x-3 text-white items-center">
            <img class="size-10 rounded-full"
                src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/150' }}"
                alt="{{ $user['username'] }}'s avatar">
            <h1 class="text-3xl font-bold text-white">
                @if (session('email') === $user['email'])
                    My Questions
                @else
                    {{ $user['username'] }}'s Questions
                @endif
            </h1>
        </div>
        <hr class="my-6 border-gray-700">


        @if (!empty($user['question']) && count($user['question']) > 0)
            <div class="space-y-6" id="questions-container">
                @foreach ($user['question'] as $question)
                    <div id="question-item-{{ $question['id'] }}"
                        class="bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300 flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-semibold text-[#7494ec] mb-2">
                                <a href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}"
                                    class="hover:underline">
                                    {{ $question['title'] ?? 'No Title Provided' }}
                                </a>
                            </h2>

                            @if (isset($question['question_content']) && is_string($question['question_content']))
                                <p class="text-gray-700 mb-3">{{ Str::limit($question['question_content'], 200) }}</p>
                            @elseif (isset($question['question']) && is_string($question['question']))
                                <p class="text-gray-700 mb-3">{{ Str::limit($question['question'], 200) }}</p>
                            @endif

                            <div class="text-sm text-gray-500">
                                @if (isset($question['created_at']))
                                    <span>Posted on:
                                        {{ \Carbon\Carbon::parse($question['created_at'])->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>

                        @if (session('email') === ($user['email'] ?? null))
                            @if (empty($question['answer']) && (isset($question['vote']) && $question['vote'] < 10))
                                <div class="flex space-x-2 text-sm">
                                    <button
                                        class="edit-question-button text-blue-600 hover:text-blue-800 font-medium py-1 px-3 rounded"
                                        data-question-id="{{ $question['id'] }}">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                    <button
                                        class="delete-question-button text-red-600 hover:text-red-800 font-medium py-1 px-3 rounded"
                                        data-question-id="{{ $question['id'] }}">
                                        <i class="fas fa-trash-alt mr-1"></i>Delete
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
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
                <a href="{{ route('seeProfile') }}" class="text-blue-500 hover:text-blue-700 hover:underline">
                    ← Back to My Profile
                </a>
            @else
                <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                    class="text-blue-500 hover:text-blue-700 hover:underline">
                    ← Back to {{ $user['username'] }}'s Profile
                </a>
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
