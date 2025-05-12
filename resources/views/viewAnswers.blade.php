@extends('layout')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')

    @include('utils.background4')
    <style>
        body {
            /* top: 0;
                                                                                        left: 0;
                                                                                        margin: 0;
                                                                                        padding: 0;
                                                                                        min-width: 100vw;
                                                                                        min-height: 100vh;
                                                                                        font-family: 'Montserrat', sans-serif;
                                                                                        background-color: #F4DEB5; */
            background-image:
                radial-gradient(at 93% 100%, #7494ec 0px, transparent 50%),
                radial-gradient(at 0% 0%, #633F92 0px, transparent 50%),
                radial-gradient(at 38% 60%, #7494ec 0px, transparent 50%),
                radial-gradient(at 100% 0%, #633F92 0px, transparent 50%),
                radial-gradient(at 80% 50%, #7494ec 0px, transparent 50%),
                radial-gradient(at 0% 100%, #633F92 0px, transparent 50%);
            background-size: 200% 200%;
            /* background-repeat: no-repeat;
                                                                                        overflow-x: hidden;
                                                                                        animation: gradient 30s ease infinite;
                                                                                        z-index:1 ; */
        }

        .hover\:shadow-glow:hover {
            box-shadow: 0 0 10px rgba(255, 223, 0, 0.6), 0 0 20px rgba(255, 223, 0, 0.4);
            transition: box-shadow 0.3s ease-in-out;
        }
    </style>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Display the question -->
        <div class="bg-opacity-50 bg-[#633F92] shadow-md p-6 text-white">
            <div class="flex flex-col">
                @if ($question['image'])
                    <img src="{{ asset('storage/' . $question['image']) }}" alt="Question Image"
                        class="w-full max-w-md rounded-lg shadow-md w-[50%] h-[50%] md:w-[30%] md:h-[30%]">
                @endif
                <h1 class="mt-4 text-xl md:text-2xl font-semibold text-white uppercase">{{ $question['title'] }}</h2>
                    <p class="text-md md:text-lg text-white">
                        {{ $question['question'] }}
                    </p>
                    <div class="text-sm flex items-center space-x-4">
                        <small class="text-gray-200">{{ $question['view'] }} views</small>
                        <div class="flex items-center">
                            <button id="upVoteQuestion"
                                class="text-white hover:text-[#633F92] focus:outline-none thumbs-up">
                                <i class="fas fa-thumbs-up"></i>
                            </button>
                            <button id="downVoteQuestion"
                                class="ml-2 text-white hover:text-gray-700 focus:outline-none thumbs-down">
                                <i class="fas fa-thumbs-down"></i>
                            </button>
                            <small class="text-gray-200 ml-2">{{ $question['vote'] }} votes</small>


                        </div>
                        <div class="flex items-center" id="comment-count">
                            <button class="text-white hover:text-yellow-100 flex items-center space-x-2 focus:outline-none">
                                <i class="fa-solid fa-reply text-lg"></i>
                            </button>
                            <small class="text-gray-200 ml-2 cursor-pointer">
                                {{ $question['comment_count'] }} comments
                            </small>
                        </div>

                    </div>

                    <div class="comment-box hidden mt-2">
                        <textarea class="w-full bg-gray-100 rounded-lg p-3 text-gray-800 placeholder-gray-400 focus:outline-none" rows="2"
                            placeholder="Write your comment here!"></textarea>
                        <button id="answer-comment"
                            class="mt-4 px-4 py-2 bg-white text-[--purple] rounded-lg border-2 border-[--bblue] transition-all duration-300 font-semibold hover:shadow-glow">
                            Submit Comment
                        </button>
                    </div>

                    <!-- Input for answering -->
                    <div class="mt-4">
                        <!-- btn upload img file -->
                        <button id="upImg-btn" class="flex flex-col space-y-2 mb-4">
                            <label
                                class="px-2 py-1 bg-white bg-opacity-50 text-white rounded-lg transition-all hover:bg-white hover:text-[#633F92] cursor-pointer">
                                <i class="fa-solid fa-file-upload"></i> Upload Images
                                <input type="file" id="question-img" class="hidden image-upload" accept="image/*">
                            </label>
                        </button>

                        <!-- Image previews -->
                        <div class="image-previews hidden mb-4">
                            <!-- Dynamically added previews will go here -->
                        </div>

                        <textarea id="answer-textArea"
                            class="w-full bg-white bg-opacity-10 rounded-lg p-3 text-white placeholder-white focus:outline-2 focus:outline-yellow-400"
                            rows="2" placeholder="Write your answer here!"></textarea>
                        <button id="submitAnswer-btn"
                            class="mt-2 px-4 py-2 bg-white hover:shadow-glow transition-all hover:font-bold text-[#633F92] font-semibold rounded-lg">
                            Submit Answer
                        </button>
                    </div>
            </div>
        </div>
        <!-- Comment section (hidden by default) -->
        @if ($question['comment_count'] > 0)
            <div id="comments-section" class="hidden mt-0 p-4 bg-gray-800">
                <div>
                    <button
                        class="comment-btn text-white hover:text-yellow-100 flex items-center space-x-2 focus:outline-none">
                        <i class="fa-solid fa-reply text-lg"></i>
                        <span>Comment</span>
                    </button>
                    <!-- comment input box -->
                    <div class="comment-box hidden mt-2">
                        <textarea id="question-comment"
                            class="w-full bg-gray-100 rounded-lg p-3 text-gray-800 placeholder-gray-400 focus:outline-none" rows="2"
                            placeholder="Write your comment here!"></textarea>
                        <button id="qComment-btn"
                            class="mt-4 px-4 py-2 bg-white text-[--purple] rounded-lg border-2 border-[--bblue] transition-all duration-300 font-semibold hover:shadow-glow">
                            Submit Comment
                        </button>
                    </div>
                </div>
                <h3 class="text-white font-semibold">Comments:</h3>

                @foreach ($question['comment'] as $comm)
                    <div class="comment bg-gray-700 p-4 mb-2 rounded-lg">
                        {{-- <a href="{{ route('viewUser', ['email' => $comm['user']['email']]) }}"><h2 class="text-white font-semibold hover:underline">{{ $comm['username'] }}</h2></a> --}}
                        <p class="text-white"> {{ $comm['comment'] }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div id="comments-section" class="hidden mt-0 p-4 bg-gray-800">
                <div>
                    <button
                        class="comment-btn text-white hover:text-yellow-100 flex items-center space-x-2 focus:outline-none">
                        <i class="fa-solid fa-reply text-lg"></i>
                        <span>Comment</span>
                    </button>
                    <!-- comment input box -->
                    <div class="comment-box hidden mt-2">
                        <textarea id="question-comment"
                            class="w-full bg-gray-100 rounded-lg p-3 text-gray-800 placeholder-gray-400 focus:outline-none" rows="2"
                            placeholder="Write your comment here!"></textarea>
                        <button id="qComment-btn"
                            class="mt-4 mb-4 px-4 py-2 bg-white text-[--purple] rounded-lg border-2 border-[--bblue] transition-all duration-300 font-semibold hover:shadow-glow">
                            Submit Comment
                        </button>
                    </div>
                </div>
                <p class="text-white">There are no comments yet. Be the first to comment!</p>
            </div>
        @endif

        {{-- Display the answers --}}
        @if ($question['answer'])
            @foreach ($question['answer'] as $ans)
                <div class="mt-4 answers bg-[--purple] bg-opacity-10 rounded-lg p-6 shadow-lg space-y-6">

                    <div class="answer flex flex-col p-4">
                        <p class="text-white ">
                            {{ $ans['answer'] }}
                        </p>
                        @if ($ans['image'])
                            <img src="{{ asset('storage/' . $ans['image']) }}" alt="Question Image"
                                class="w-full max-w-md max-h-64 object-contain mb-4 rounded-lg border">
                        @endif

                        <!-- vote buttons -->
                        <div class="mt-2 flex items-center space-x-4">
                            <!-- Comment button -->
                            <div>
                                <button
                                    class="comment-btn text-white hover:text-yellow-100 flex items-center space-x-2 focus:outline-none">
                                    <i class="fa-solid fa-reply text-lg"></i>
                                    <span>Comment</span>
                                </button>
                                <!-- comment input box -->
                                <div class="comment-box hidden mt-2">
                                    <textarea id="answer-comment-{{ $ans['id'] }}"
                                        class="w-full bg-gray-100 rounded-lg p-3 text-gray-800 placeholder-gray-400 focus:outline-none" rows="2"
                                        placeholder="Write your comment here!"></textarea>
                                    <button id="submit-comment-{{ $ans['id'] }}" data-answer-id="{{ $ans['id'] }}"
                                        class="mt-4 px-4 py-2 bg-white text-[--purple] rounded-lg border-2 border-[--bblue] transition-all duration-300 font-semibold hover:shadow-glow">
                                        Submit Comment
                                    </button>
                                </div>
                            </div>
                            <!-- Thumbs up -->
                            <div class="flex items-center space-x-2">
                                <button class="upVoteAnswer text-white hover:text-[#633F92] focus:outline-none thumbs-up" data-answer-id="{{ $ans['id'] }}"
                                    class="text-white hover:text-[#633F92] focus:outline-none thumbs-up">
                                    <i class="fas fa-thumbs-up text-lg"></i>
                                </button>
                                <span class="thumbs-up-count text-white font-medium">0</span>
                            </div>
                            <!-- Thumbs down -->
                            <div class="flex items-center space-x-2">
                                <button class="downVoteAnswer text-white  hover:text-gray-700 focus:outline-none thumbs-down" data-answer-id="{{ $ans['id'] }}"
                                    class="text-white hover:text-gray-700 focus:outline-none thumbs-down">
                                    <i class="fas fa-thumbs-down text-lg"></i>
                                </button>
                                <span class="thumbs-down-count text-white font-medium">0</span>
                            </div>
                        </div>

                    </div>
                    <hr class="border-t border-gray-300">
                    {{-- @endforeach --}}
                </div>
            @endforeach
        @else
            <div class="mt-4 answers bg-[--purple] bg-opacity-10 rounded-lg p-6 shadow-lg space-y-6 text-white">
                There are no answers for this question yet. Be the first one to answer!
            </div>
        @endif

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
                    <span class="file-name text-white">${file.name}</span>
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

            submitButton.addEventListener('click', (event) => {
                event.preventDefault();
                const answerText = textArea.value.trim();

                if (answerText === '') {
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
                        formData.append('image', fileInput.files[0]); // Only append the first image
                    }
                    const questionId = @json($question['id']);
                    // Send form data

                    fetch(`/submitAnswer/${questionId}`, {
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
                                    title: 'Answer Submitted!',
                                    text: 'Your answer has been successfully submitted.',
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
                                title: 'Error',
                                text: 'There was a network error. Please try again.',
                            });
                        });
                }
            });
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

                            const voteCountElement = document.querySelector('.text-gray-200.ml-2');
                            voteCountElement.textContent = `${data.newVoteCount} votes`;

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
                                voteCountElement.textContent = `${data.newVoteCount} votes`;
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