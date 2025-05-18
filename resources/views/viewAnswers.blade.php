@extends('layout')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .hover\:shadow-glow:hover {
            box-shadow: 0 0 10px rgba(255, 223, 0, 0.6), 0 0 20px rgba(255, 223, 0, 0.4);
            transition: box-shadow 0.3s ease-in-out;
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

        .interaction-icons i {
            color: var(--text-muted);
        }

        .interaction-icons span {
            color: var(--text-secondary);
        }
        
        .page-title-container {
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
        }
        
        .card-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        
        .vote-btn {
            transition: transform 0.15s ease;
        }
        
        .vote-btn:hover {
            transform: scale(1.15);
        }
        
        .answer-section {
            position: relative;
        }
        
        .answer-section::before {
            content: '';
            position: absolute;
            top: -40px;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--border-color), transparent);
        }
        
        .comment-animation {
            transition: all 0.3s ease-in-out;
        }
        
        /* Style for verified answer */
        .verified-answer {
            border-left: 4px solid #23BF7F;
        }

    </style>
@endsection
@section('content')
    @include('partials.nav')

    <!-- Main content container -->
    <div class="max-w-5xl justify-start items-start px-4 py-8">
        <!-- Question Title Section - Moved outside the card to make it more pronounced -->
        <div class="page-title-container">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl my-3 ml-2 md:text-3xl font-bold text-[var(--text-primary)]">
                    {{ $question['title'] }}
                </h1>
                
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center" title="Views">
                        <i class="fa-solid fa-eye text-[var(--accent-tertiary)] mr-2"></i>
                        <span class="text-[var(--text-secondary)]">{{ $question['view'] }}</span>
                    </div>
                    
                    <div class="flex items-center" title="Comments">
                        <i class="fa-solid fa-reply text-[var(--accent-tertiary)] mr-2"></i>
                        <span class="text-[var(--text-secondary)]">{{ $question['comment_count'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="question-card rounded-lg p-6 mb-6 flex items-start">
            <div class="interaction-section flex flex-col items-center mr-6">
                <button id="upVoteQuestion" 
                    class="mb-2 vote-btn text-[var(--text-primary)] hover:text-[#633F92] focus:outline-none thumbs-up">
                    <i class="text-2xl text-[#23BF7F] fa-solid fa-chevron-up"></i>
                </button>

                <span id="voteTotal" class="text-lg font-semibold text-[var(--text-secondary)] my-1">
                    {{ $question['vote'] }}
                </span>

                <button id="downVoteQuestion"
                    class="mt-2 text-[var(--text-primary)] hover:text-gray-700 focus:outline-none thumbs-down">
                    <i class="text-2xl text-[#FE0081] fa-solid fa-chevron-down"></i>
                </button>

                <div class="flex flex-col items-center mt-4">
                    <i class="text-md text-[var(--accent-tertiary)] fa-solid fa-eye"></i>
                    <small class="text-[var(--text-secondary)] text-xs mt-1">
                        {{ $question['view'] }}
                    </small>
                </div>

                <div class="flex flex-col items-center mt-4" id="comment-count">
                    <button class="text-[var(--text-primary)] hover:text-yellow-100 focus:outline-none">
                        <i class="fa-solid fa-reply text-md"></i>
                    </button>
                    <small class="text-[var(--text-secondary)] text-xs mt-1 cursor-pointer">
                        {{ $question['comment_count'] }}
                    </small>
                </div>
            </div>

            <!-- Question Content -->
            <div class="flex flex-col flex-grow">
                <div class="flex items-center mb-3">
                    <div class="card-badge bg-transparent bg-opacity-20 text-[var(--accent-secondary)]">
                        <i class="fa-solid fa-circle-question mr-1"></i> Question
                    </div>
                    <!-- Add timestamp -->
                    <span class="text-xs text-[var(--text-muted)] ml-3">Posted 3 days ago</span>
                </div>
                
                <div class="prose max-w-none text-[var(--text-primary)]">
                    <p class="text-md md:text-lg text-[var(--text-primary)]">
                        {{ $question['question'] }}
                    </p>
                    
                    @if ($question['image'])
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $question['image']) }}" alt="Question Image"
                                class="rounded-lg shadow-md max-w-lg max-h-96 object-contain">
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <div class="flex items-center text-sm text-[var(--text-muted)]">
                        <img src="https://ui-avatars.com/api/?name=User&background=random" alt="User" 
                             class="w-6 h-6 rounded-full mr-2">
                        <span>Asked by Anonymous</span>
                    </div>
                    
                    <button id="comment-count" class="flex items-center text-[var(--text-secondary)] hover:text-[var(--accent-primary)] transition-colors">
                        <i class="fa-solid fa-comment-dots mr-2"></i>
                        <span>{{ $question['comment_count'] }} Comments</span>
                    </button>
                </div>

                <div class="comment-box hidden mt-4">
                    <textarea
                        class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none"
                        rows="2" placeholder="Write your comment here!"></textarea>
                    <button id="answer-comment"
                        class="mt-4 px-4 py-2 bg-[var(--bg-button)] text-[var(--text-button)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                        Submit Comment
                    </button>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        @if ($question['comment_count'] > 0 || true)
            <div id="comments-section" class="mt-2 p-6 bg-[var(--bg-secondary)] rounded-lg mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-[var(--text-primary)]">
                        Comments
                        <span class="text-sm text-[var(--text-muted)] ml-2">({{ $question['comment_count'] }})</span>
                    </h3>
                    
                    <button class="comment-btn text-[var(--text-primary)] bg-[var(--bg-button)] bg-opacity-80 px-3 py-1 rounded-md hover:bg-opacity-100 flex items-center space-x-2 focus:outline-none transition-all">
                        <i class="fa-solid fa-reply text-sm mr-2"></i>
                        Add Comment
                    </button>
                </div>

                <!-- Comment Input Box -->
                <div class="comment-box hidden mb-4">
                    <textarea id="question-comment"
                        class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-primary)]" 
                        rows="2"
                        placeholder="Write your comment here!"></textarea>
                    <button id="qComment-btn"
                        class="mt-4 px-4 py-2 bg-[var(--bg-button)] text-[var(--text-button)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                        Submit Comment
                    </button>
                </div>

                <!-- Comments List -->
                @if ($question['comment_count'] > 0)
                    <div class="space-y-3">
                        @foreach ($question['comment'] as $comm)
                            <div class="comment bg-[var(--bg-card)] p-4 rounded-lg flex items-start">
                                <div class="flex flex-col items-center mr-4">
                                    <button class="vote-btn text-[var(--text-primary)] hover:text-[#633F92] focus:outline-none thumbs-up mb-1">
                                        <i class="text-sm text-[#23BF7F] fa-solid fa-chevron-up"></i>
                                    </button>
                                    <span class="text-xs text-[var(--text-secondary)]">0</span>
                                    <button class="vote-btn text-[var(--text-primary)] hover:text-gray-700 focus:outline-none thumbs-down mt-1">
                                        <i class="text-sm text-[#FE0081] fa-solid fa-chevron-down"></i>
                                    </button>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-[var(--text-primary)]">{{ $comm['comment'] }}</p>
                                    <div class="mt-2 text-xs text-[var(--text-muted)]">
                                        <span>Posted by Anonymous • 2 days ago</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-[var(--bg-card)] rounded-lg p-6 text-center">
                        <p class="text-[var(--text-primary)] mb-2">There are no comments yet</p>
                        <p class="text-[var(--text-muted)] text-sm">Be the first to share your thoughts!</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Answer Input Section -->
        <div class="mt-10">
            <h2 class="text-xl font-bold text-[var(--text-primary)] mb-4 flex items-center">
                <i class="fa-solid fa-pen-to-square mr-2 text-[var(--accent-primary)]"></i>
                Your Answer
            </h2>
            <div class="question-card rounded-lg p-6 mb-6 bg-[var(--bg-primary)] border-2 border-[var(--border-color)]">
                <!-- Image previews -->
                <div class="image-previews hidden mb-4">
                    <!-- Dynamically added previews will go here -->
                </div>

                <textarea id="answer-textArea"
                    class="w-full bg-[var(--bg-input)] rounded-lg p-4 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-2 focus:outline-yellow-400 min-h-[150px]"
                    placeholder="Write your detailed answer here..."></textarea>

                <div class="flex justify-between items-center mt-4">
                    <label
                        class="flex items-center px-4 py-2 bg-[var(--bg-button)] text-[var(--text-button)] rounded-lg transition-all hover:bg-opacity-90 cursor-pointer">
                        <i class="fa-solid fa-file-upload mr-2"></i>
                        Upload Images
                        <input type="file" id="question-img" class="hidden image-upload" accept="image/*">
                    </label>

                    <button id="submitAnswer-btn"
                        class="px-6 py-2 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium rounded-lg 
                               hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] 
                               transform hover:scale-105 transition-all duration-200 
                               flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane mr-2"></i>
                        Submit Answer
                    </button>
                </div>
            </div>
        </div>

        <!-- Answers Section -->
        <div class="mt-10 answer-section pt-6">
            <h2 class="text-xl font-bold text-[var(--text-primary)] mb-4 flex items-center">
                <i class="fa-solid fa-list-check mr-2 text-[var(--accent-primary)]"></i>
                Answers <span class="text-sm text-[var(--text-muted)] ml-2">({{ count($question['answer'] ?? []) }})</span>
            </h2>
            
            @if ($question['answer'])
                <div class="space-y-6">
                    @foreach ($question['answer'] as $ans)
                        <div class="bg-[var(--bg-secondary)] rounded-lg p-6 shadow-lg flex items-start {{ $loop->first ? 'verified-answer' : '' }}">
                            <div class="interaction-section flex flex-col items-center mr-6">
                                <button class="upVoteAnswer vote-btn mb-2 text-[var(--text-primary)] hover:text-[#633F92] focus:outline-none thumbs-up" data-answer-id="{{ $ans['id'] }}">
                                    <i class="text-2xl text-[#23BF7F] fa-solid fa-chevron-up"></i>
                                </button>
                                <span class="thumbs-up-count text-lg font-semibold text-[var(--text-secondary)] my-1">0</span>
                                <button class="downVoteAnswer vote-btn mt-2 text-[var(--text-primary)] hover:text-gray-700 focus:outline-none thumbs-down" data-answer-id="{{ $ans['id'] }}">
                                    <i class="text-2xl text-[#FE0081] fa-solid fa-chevron-down"></i>
                                </button>
                                
                                @if ($loop->first)
                                    <div class="mt-4 flex flex-col items-center">
                                        <i class="fa-solid fa-check-circle text-[#23BF7F] text-lg"></i>
                                        <span class="text-xs text-[#23BF7F] mt-1">Best Answer</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col flex-grow">
                                <div class="prose max-w-none text-[var(--text-primary)]">
                                    <p>{{ $ans['answer'] }}</p>
                                </div>
                                
                                @if ($ans['image'])
                                    <div class="mt-4">
                                        <img src="{{ asset('storage/' . $ans['image']) }}" alt="Answer Image"
                                            class="max-w-lg max-h-96 object-contain rounded-lg border">
                                    </div>
                                @endif

                                <div class="mt-4 flex justify-between items-center">
                                    <div class="flex items-center text-sm text-[var(--text-muted)]">
                                        <img src="https://ui-avatars.com/api/?name=User&background=random" alt="User" 
                                             class="w-6 h-6 rounded-full mr-2">
                                        <span>Answered by Anonymous • 1 day ago</span>
                                    </div>
                                    
                                    <button class="comment-btn flex items-center text-[var(--text-secondary)] hover:text-[var(--accent-primary)] transition-colors">
                                        <i class="fa-solid fa-comment-dots mr-2"></i>
                                        <span>Add Comment</span>
                                    </button>
                                </div>
                            
                                <!-- comment input box -->
                                <div class="comment-box hidden mt-4 w-full comment-animation">
                                    <textarea id="answer-comment-{{ $ans['id'] }}"
                                        class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-primary)]" rows="2"
                                        placeholder="Write your comment here!"></textarea>
                                    <button id="submit-comment-{{ $ans['id'] }}" data-answer-id="{{ $ans['id'] }}"
                                        class="mt-4 px-4 py-2 bg-[var(--bg-button)] text-[var(--text-button)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                                        Submit Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-[var(--bg-secondary)] rounded-lg p-8 shadow-lg text-center">
                    <i class="fa-solid fa-lightbulb text-4xl text-[var(--accent-secondary)] mb-4"></i>
                    <h3 class="text-xl font-semibold text-[var(--text-primary)] mb-2">No Answers Yet</h3>
                    <p class="text-[var(--text-secondary)] mb-4">Be the first one to answer this question!</p>
                    <a href="#answer-textArea" class="px-6 py-2 bg-[var(--bg-button)] text-[var(--text-button)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                        Write an Answer
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Toggle comment box
            const commentButtons = document.querySelectorAll('.comment-btn');
            commentButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const commentBox = button.nextElementSibling;
                    commentBox.classList.toggle('hidden');
                });
            });
        });
    </script>

    <!-- file upload and preview -->
    <script>
        // Handle image file upload and preview
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById("question-img");
            const imagePreviewsContainer = document.querySelector(".image-previews");

            fileInput.addEventListener('change', (event) => {
                const files = event.target.files;
                imagePreviewsContainer.innerHTML = ''; // Clear any existing previews

                if (files.length > 0) {
                    const file = files[0]; // Get the first file
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const imgPreview = document.createElement('div');
                        imgPreview.classList.add('image-preview', 'mb-2');
                        imgPreview.innerHTML = `
                    <img src="${e.target.result}" alt="Image Preview" class="w-full max-w-md rounded-lg shadow-md">
                    <span class="file-name text-[var(--text-primary)]">${file.name}</span>
                `;
                        imagePreviewsContainer.appendChild(imgPreview);
                    };
                    reader.readAsDataURL(file);
                }

                imagePreviewsContainer.classList.remove('hidden'); // Show the preview section
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Get the comment count element and comments section
            const commentCount = document.getElementById('comment-count');
            const commentsSection = document.getElementById('comments-section');

            // Toggle visibility of the comments section when the "comments" link is clicked
            commentCount.addEventListener('click', () => {
                commentsSection.classList.toggle('hidden');
            });
        });
    </script>

    {{-- uploading answer to question in database: --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const submitButton = document.getElementById("submitAnswer-btn");
            const textArea = document.getElementById('answer-textArea');
            const fileInput = document.getElementById("question-img");

            if (submitButton && textArea) {
                submitButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    const answerText = textArea.value.trim();
                    
                    // Show loading state
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Submitting...';
                    submitButton.disabled = true;

                    if (answerText === '') {
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please provide an answer!',
                        });
                    } else {
                        const formData = new FormData();
                        formData.append('answer', answerText);

                        // Check if there is an image selected
                        if (fileInput.files.length > 0) {
                            formData.append('image', fileInput.files[0]);
                        }
                        
                        const questionId = @json($question['id']);

                        fetch(`/submitAnswer/${questionId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                },
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                submitButton.innerHTML = originalButtonText;
                                submitButton.disabled = false;
                                
                                if (data.success) {
                                    // Clear form after successful submission
                                    textArea.value = '';
                                    fileInput.value = '';
                                    const imagePreviewsContainer = document.querySelector(".image-previews");
                                    if (imagePreviewsContainer) {
                                        imagePreviewsContainer.innerHTML = '';
                                        imagePreviewsContainer.classList.add('hidden');
                                    }
                                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Answer Submitted!',
                                        text: 'Your answer has been successfully submitted.',
                                        confirmButtonText: 'Great!',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Optionally refresh to see the new answer
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message || 'Something went wrong',
                                    });
                                }
                            })
                            .catch(error => {
                                submitButton.innerHTML = originalButtonText;
                                submitButton.disabled = false;
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'There was a network error. Please try again.',
                                });
                            });
                    }
                });
            }
        });
    </script>

    {{-- logic for submitting comment for question --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const submitCommentButton = document.getElementById("qComment-btn");

            submitCommentButton.addEventListener('click', (event) => {
                const commentTextArea = document.getElementById("question-comment");
                const questionId = @json($question['id']);
                event.preventDefault();

                const commentText = commentTextArea.value.trim();

                if (commentText === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please write a comment!',
                    });
                } else {
                    const formData = new FormData();
                    formData.append('comment', commentText);
                    formData.append('question_id', questionId);

                    // Send comment data to the server
                    fetch(`/submit/question/comment/${questionId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            },
                            body: formData,

                        })
                        .then(response => response.json()) // Handle the response
                        .then(data => {
                            console.log(data);

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Comment Submitted!',
                                    text: 'Your comment has been successfully posted.',
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                });
                            }
                        })
                        .catch(error => { // Handle any errors that occur during the fetch
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An unexpected error occurred.',
                            });
                            console.log(error);

                        });
                }
            });
        });

        // COMMENT DI ANSWERRRR

        document.addEventListener('DOMContentLoaded', () => {

            const submitCommentButtons = document.querySelectorAll('[id^="submit-comment-"]');

            submitCommentButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();

                    const answerId = button.getAttribute('data-answer-id');

                    const commentTextArea = document.getElementById(`answer-comment-${answerId}`);
                    const commentText = commentTextArea.value.trim();

                    if (commentText === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please write a comment!',
                        });
                    } else {
                        const formData = new FormData();
                        formData.append('comment', commentText);
                        formData.append('answer_id', answerId);

                        fetch(`/submit/question/comment/${answerId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                },
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Comment Submitted!',
                                        text: 'Your comment has been successfully posted.',
                                    });

                                    commentTextArea.value = '';
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message,
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An unexpected error occurred.',
                                });
                                console.log(error);
                            });
                    }
                });
            });
        });

        // Vote Question
        document.addEventListener('DOMContentLoaded', () => {
            const questionId = @json($question['id']);

            const upVoteButton = document.getElementById('upVoteQuestion');
            const downVoteButton = document.getElementById('downVoteQuestion');

            const handleVote = (voteType) => {
                const formData = new FormData();
                formData.append('vote', voteType);
                formData.append('question_id', questionId);

                fetch(`/question/vote`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const voteTotal = document.getElementById('voteTotal');
                            voteTotal.textContent = `${data.voteUpdated}`;

                            Swal.fire({
                                icon: 'success',
                                title: 'Vote Submitted!',
                                text: 'Your vote has been successfully recorded.',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                            });
                        }
                    })
                // .catch(error => {
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Unexpected Error',
                //         text: 'An unexpected error occurred.',
                //     });
                // });
            };

            upVoteButton.addEventListener('click', () => handleVote(true));
            downVoteButton.addEventListener('click', () => handleVote(false));
        });

        // Vote Answer
        document.addEventListener('DOMContentLoaded', () => {
            const upVoteButtons = document.querySelectorAll('.upVoteAnswer');
            const downVoteButtons = document.querySelectorAll('.downVoteAnswer');

            const handleVote = (voteType, id) => {

                const formData = new FormData();
                formData.append('vote', voteType);
                formData.append('answer_id', id);

                fetch(`/answer/vote`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the vote count
                            const voteCountElement = document.querySelector(`[data-answer-id="${id}"]`)
                                .nextElementSibling;
                            if (voteCountElement) {
                                voteCountElement.textContent = `${data.voteAnswerUpdated}`;
                            }

                            // Display success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Vote Submitted!',
                                text: 'Your vote has been successfully recorded.',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Unexpected Error',
                            text: 'An unexpected error occurred.',
                        });
                    });
            };

            upVoteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const answerId = button.getAttribute('data-answer-id');
                    handleVote(true, answerId);
                });
            });

            downVoteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const answerId = button.getAttribute('data-answer-id');
                    handleVote(false, answerId);
                });
            });
        });
    </script>
@endsection
