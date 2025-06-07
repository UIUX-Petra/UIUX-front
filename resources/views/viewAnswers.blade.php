@extends('layout')
@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
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

        /* New Modal Base Styles */
        #questionCommentsModal {
            /* Default to hidden: opacity-0 and non-interactive */
            /* Tailwind classes will handle this: opacity-0 pointer-events-none */
            /* Transition for the modal container (overlay) */
            transition: opacity 0.3s ease-in-out;
        }

        #questionCommentsModal .modal-content {
            /* Transition for the content pop-in effect */
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        /* When modal is hidden (initial state or via JS) */
        #questionCommentsModal.opacity-0 .modal-content {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }

        /* When modal is visible (JS adds opacity-100, removes pointer-events-none) */
        #questionCommentsModal.opacity-100 .modal-content {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    </style>

    @include('partials.nav')
    @php
        $isQuestionOwner = $question['user']['email'] === session('email');
    @endphp
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

                    <div id="answerCountAtas" class="flex items-center" title="Comments">
                        <i class="fa-solid fa-reply-all text-[var(--accent-tertiary)] mr-2"></i>
                        <span class="text-[var(--text-secondary)]">{{ count($question['answer']) }}</span>
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

                {{-- <div class="flex flex-col items-center mt-4" id="reply-count">
                    <button class="text-[var(--text-primary)] hover:text-yellow-100 focus:outline-none">
                        <i class="fa-solid fa-comments text-md"></i>
                    </button>
                    <small class="text-[var(--text-secondary)] text-xs mt-1 cursor-pointer">
                        {{ count($question['answer']) }}
                    </small>
                </div> --}}

                {{-- Save/Unsave Button --}}
                @if (isset($question['is_saved_by_request_user']) && $question['is_saved_by_request_user'])
                    <button type="button"
                            class="save-question-btn absolute top-3 right-3 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-hover)] bg-[var(--bg-card-hover)]"
                            data-question-id="{{ $question['id'] }}"
                            title="Unsave Question">
                        <i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>
                    </button>
                @else
                    <button type="button"
                            class="save-question-btn absolute top-3 right-3 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-hover)] bg-[var(--bg-card-hover)]"
                            data-question-id="{{ $question['id'] }}"
                            title="Save Question">
                        <i class="fa-regular fa-bookmark text-[var(--text-muted)] hover:text-[var(--accent-secondary)]"></i>
                    </button>
                @endif
            </div>

            <!-- Question Content -->
            <div class="flex flex-col flex-grow">
                <div class="flex items-center mb-3">
                    <div class="card-badge bg-transparent bg-opacity-20 text-[var(--accent-secondary)]">
                        <i class="fa-solid fa-circle-question mr-1"></i> Question
                    </div>
                    <!-- Add timestamp -->
                    <span class="text-xs text-[var(--text-muted)] ml-3">
                        Posted {{ \Carbon\Carbon::parse($question['timestamp'])->diffForHumans() }}
                    </span>
                </div>

                <div class="prose max-w-none text-[var(--text-primary)]">
                    <p class="text-md md:text-lg text-[var(--text-primary)]">
                        {!! nl2br(e($question['question'])) !!}
                    </p>

                    @if ($question['image'])
                        <div class="mt-4">
                            <img src="{{ asset('storage/' . $question['image']) }}" alt="Question Image"
                                class="rounded-lg shadow-md max-w-lg max-h-96 object-contain">
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('viewUser', ['email' => $question['user']['email']]) }}">
                        <div class="flex items-center text-sm text-[var(--text-muted)]">
                            <img src="{{ $image ? asset('storage/' . $image) : 'https://ui-avatars.com/api/?name=' . urlencode($username ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                                alt="User avatar" class="w-6 h-6 rounded-full mr-2">
                            <span>Asked by {{ $question['user']['username'] }}</span>
                        </div>
                    </a>
                    <button id="open-question-comments-modal-btn"
                        class="flex items-center text-[var(--text-secondary)] hover:text-[var(--accent-primary)] transition-colors">
                        <i class="fa-solid fa-comment-dots mr-2"></i>
                        <span id="question-card-comment-count-text">{{ $question['comment_count'] }} Comments</span>
                    </button>
                </div>

                {{-- <div class="comment-box hidden mt-4">
                    <textarea
                        class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none"
                        rows="2" placeholder="Write your comment here!"></textarea>
                    <button id="answer-comment"
                        class="mt-4 px-4 py-2 bg-[var(--bg-button)] text-[var(--text-button)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                        Submit Comment
                    </button>
                </div> --}}
            </div>

            {{-- Action Buttons --}}
            @if ($isQuestionOwner)
                @php
                    // Inisialisasi ini tetap penting untuk render awal sisi server
                    $hasAnswer = !empty($question['answer']);
                    // Pastikan $question['vote'] adalah numerik sebelum perbandingan
                    $currentVoteCount = isset($question['vote']) ? (int) $question['vote'] : 0;
                    $hasVote = $currentVoteCount !== 0;
                @endphp

                {{-- Beri ID pada container tombol aksi --}}
                <div id="question-action-buttons-container" class="flex justify-end space-x-3 mt-4">
                    @if (!$hasAnswer && !$hasVote)
                        <button data-question-id="{{ $question['id'] }}"
                            class="action-button edit-question-button inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                        <button data-question-id="{{ $question['id'] }}"
                            class="action-button delete-question-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-trash-alt mr-2"></i>Delete
                        </button>
                    @endif
                </div>
            @endif
        </div>
        {{-- </div> --}}

        <div id="questionCommentsModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 opacity-0 pointer-events-none">
            <div class="modal-content bg-[var(--bg-secondary)] rounded-lg shadow-xl max-w-2xl w-full mx-auto flex flex-col relative max-h-[85vh]">
                <div class="flex-shrink-0 flex justify-between items-center p-6 pb-3 border-b border-[var(--border-color)]">
                    <h3 class="text-xl font-semibold text-[var(--text-primary)]">
                        {{ $question['title'] }}
                        <span id="modal-question-comment-count" class="text-sm text-[var(--text-muted)] ml-2">({{ $question['comment_count'] }})</span>
                    </h3>
                    <button id="close-question-comments-modal-btn" class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-2xl">&times;</button>
                </div>

                <div class="overflow-y-auto flex-grow p-6 pt-2 space-y-3" id="question-comments-list-modal">
                    @if ($question['comment_count'] > 0)
                        @foreach ($question['comment'] as $comm)
                            <div class="comment bg-[var(--bg-card)] p-4 rounded-lg flex items-start">
                                <div class="flex-grow">
                                    <p class="text-[var(--text-primary)]">{!! nl2br(e($comm['comment'])) !!}</p>
                                    {{-- Ensure you have user data available consistently, e.g., $comm['user']['email'] or $comm['email'] --}}
                                    <a href="{{ route('viewUser', ['email' => $comm['user']['email'] ?? ($comm['email'] ?? '#')]) }}" class="hover:underline">
                                        <div class="mt-2 text-xs text-[var(--text-muted)] flex items-center">
                                            <img src="{{ $comm['user_image'] ?? (isset($comm['user']['image']) ? asset('storage/' . $comm['user']['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($comm['user']['username'] ?? ($comm['username'] ?? 'U')) . '&background=random&color=fff&size=128') }}"
                                                alt="{{ $comm['user']['username'] ?? ($comm['username'] ?? 'User') }}" class="w-5 h-5 rounded-full mr-2">
                                            <span>Posted by {{ $comm['user']['username'] ?? ($comm['username'] ?? 'User') }} -
                                                {{ \Carbon\Carbon::parse($comm['timestamp'])->diffForHumans() }}</span>
                                    </a>
                                </div>
                            </div>
                            
                        @endforeach
                    @else
                        <div id="no-question-comments-modal" class="bg-[var(--bg-card)] rounded-lg p-6 text-center">
                            <p class="text-[var(--text-primary)] mb-2">There are no comments yet</p>
                            <p class="text-[var(--text-muted)] text-sm">Be the first to share your thoughts!</p>
                        </div>
                    @endif
                </div>

                <div class="flex-shrink-0 p-6 pt-4 border-t border-[var(--border-color)] bg-[var(--bg-secondary)]">
                    <textarea id="question-comment-textarea"
                        class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-primary)]"
                        rows="3" placeholder="Write your comment here..."></textarea>
                    <button id="qComment-btn"
                        class="mt-3 w-full px-4 py-2 bg-[var(--accent-tertiary)] text-[var(--text-dark)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                        Submit Comment
                    </button>
                </div>
            </div>
        </div>


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
                    class="w-full bg-[var(--bg-input)] rounded-lg p-4 text-[var(--text-primary)] placeholder-[var(--text-muted)]  min-h-[150px] border-none"
                    placeholder="Write your detailed answer here..."></textarea>

                <div class="flex justify-end items-center space-x-2  mt-4">
                    <label tabindex="0"
                        class="flex items-center px-4 py-3 bg-[var(--accent-tertiary)] text-black rounded-lg transform hover:scale-105 transition-all duration-200 cursor-pointer">
                        <i class="fa-solid fa-file-upload"></i>
                        {{-- Upload Images --}}
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
                Answers <span
                    class="text-sm text-[var(--text-muted)] ml-2">({{ count($question['answer'] ?? []) }})</span>
            </h2>

            @if ($question['answer'])
                <div id="answerList" class="space-y-6">
                    @foreach ($question['answer'] as $ans)
                        @php
                            $isAnswerOwner = session('email') === ($ans['email'] ?? null);
                            $answerVoteCount = (int) ($ans['vote'] ?? 0);
                            $isVerified = (int) $ans['verified'] === 1;
                        @endphp
                        <div class="bg-[var(--bg-secondary)] rounded-lg p-6 shadow-lg"
                            id="answer-item-{{ $ans['id'] }}" data-answer-id="{{ $ans['id'] }}"
                            data-is-owner="{{ $isAnswerOwner ? 'true' : 'false' }}"
                            data-is-verified="{{ $isVerified ? 'true' : 'false' }}"
                            data-vote-count="{{ $answerVoteCount }}">
                            <div class="flex items-start">
                                <div class="interaction-section flex flex-col items-center mr-6">
                                    <button
                                        class="upVoteAnswer vote-btn mb-2 text-[var(--text-primary)] hover:text-[#633F92] focus:outline-none thumbs-up"
                                        data-answer-id="{{ $ans['id'] }}">
                                        <i class="text-2xl text-[#23BF7F] fa-solid fa-chevron-up"></i>
                                    </button>
                                    <span
                                        class="thumbs-up-count text-lg font-semibold text-[var(--text-secondary)] my-1">{{ $ans['vote'] }}</span>
                                    <button
                                        class="downVoteAnswer vote-btn mt-2 text-[var(--text-primary)] hover:text-gray-700 focus:outline-none thumbs-down"
                                        data-answer-id="{{ $ans['id'] }}">
                                        <i class="text-2xl text-[#FE0081] fa-solid fa-chevron-down"></i>
                                    </button>

                                    <div id="answer-verify-block-{{ $ans['id'] }}"
                                        class="mt-4 flex flex-col items-center">
                                        @if ($isQuestionOwner)
                                            <i id="verify-icon-{{ $ans['id'] }}"
                                                class="fa-{{ $isVerified ? 'solid' : 'regular' }} fa-check-circle text-[#23BF7F] text-lg {{ !$isVerified ? 'cursor-pointer verify-toggle-button' : '' }}"
                                                data-answer-id="{{ $ans['id'] }}"
                                                data-current-verified="{{ $ans['verified'] }}">
                                            </i>
                                            <span id="verify-text-{{ $ans['id'] }}"
                                                class="text-xs text-[#23BF7F] mt-1">
                                                {{ $isVerified ? 'Verified Answer' : 'Verify Answer' }}
                                            </span>
                                            @if (!$isVerified)
                                                <span class="text-xs text-gray-500 mt-1 verify-toggle-button"
                                                    data-answer-id="{{ $ans['id'] }}"
                                                    data-current-verified="{{ $ans['verified'] }}"
                                                    style="cursor:pointer;">
                                                    (Click icon or text to verify)
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500 mt-1 verify-toggle-button"
                                                    data-answer-id="{{ $ans['id'] }}"
                                                    data-current-verified="{{ $ans['verified'] }}"
                                                    style="cursor:pointer;">
                                                    (Click icon or text to unverify)
                                                </span>
                                            @endif
                                        @elseif ($isVerified)
                                            <i class="fa-solid fa-check-circle text-[#23BF7F] text-lg"></i>
                                            <span class="text-xs text-[#23BF7F] mt-1">Verified Answer</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col flex-grow">
                                    <div class="prose max-w-none text-[var(--text-primary)]">
                                        <p class="">{!! nl2br(e($ans['answer'])) !!}</p>
                                    </div>

                                    @if ($ans['image'])
                                        <div class="mt-4">
                                            <img src="{{ asset('storage/' . $ans['image']) }}" alt="Answer Image"
                                                class="max-w-lg max-h-96 object-contain rounded-lg border">
                                        </div>
                                    @endif

                                    <div class="mt-4 flex justify-between items-center">
                                        <a href="{{ route('viewUser', ['email' => $ans['username']]) }}">
                                            <div class="flex items-center text-sm text-[var(--text-muted)]">
                                                <img src="{{ $ans['user_image'] ? asset('storage/' . $ans['user_image']) : 'https://ui-avatars.com/api/?name=' . urlencode($ans['username'] ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                                                    alt="User avatar" class="w-6 h-6 rounded-full mr-2">
                                                <span class="hover:underline">Answered by {{ $ans['username'] }} -
                                                    {{ \Carbon\Carbon::parse($ans['timestamp'])->diffForHumans() }}</span>
                                            </div>
                                        </a>

                                        <button
                                            class="comment-btn flex items-center text-[var(--text-secondary)] hover:text-[var(--accent-primary)] transition-colors">
                                            <i class="fa-solid fa-comment-dots mr-2"></i>
                                            <span>{{ count($ans['comments'] ?? []) }} Comments</span>
                                        </button>
                                    </div>

                                    <!-- Comment input box -->
                                    <div class="comment-box hidden mt-4 w-full comment-animation">
                                        <textarea id="answer-comment-{{ $ans['id'] }}"
                                            class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-primary)]"
                                            rows="2" placeholder="Write your comment here!"></textarea>
                                        <button id="submit-comment-{{ $ans['id'] }}"
                                            data-answer-id="{{ $ans['id'] }}"
                                            class="mt-4 px-4 py-2 bg-[var(--bg-button)] text-[var(--text-button)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                                            Submit Comment
                                        </button>
                                    </div>

                                    <!-- Comments Display Section -->
                                    @if (isset($ans['comments']) && count($ans['comments']) > 0)
                                        <div
                                            class="answer-comments-section mt-6 pt-4 border-t border-[var(--border-color)]">
                                            <h4
                                                class="text-sm font-semibold text-[var(--text-primary)] mb-3 flex items-center">
                                                <i class="fa-solid fa-comments mr-2 text-[var(--accent-tertiary)]"></i>
                                                Comments ({{ count($ans['comments']) }})
                                            </h4>

                                            <div class="space-y-3">
                                                @foreach ($ans['comments'] as $comment)
                                                    <div
                                                        class="answer-comment bg-[var(--bg-card)] p-3 rounded-lg border-l-2 border-[var(--accent-tertiary)]">
                                                        <a
                                                            href="{{ route('viewUser', ['email' => $comment['user_email']]) }}">
                                                            <div class="flex items-start">
                                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment['username']) }}&background=random"
                                                                    alt="{{ $comment['username'] }}"
                                                                    class="w-6 h-6 rounded-full mr-3 mt-1">

                                                                <div class="flex-grow">
                                                                    <div class="flex items-center mb-1">
                                                                        <span
                                                                            class="hover:underline text-sm font-medium text-[var(--text-primary)]">
                                                                            {{ $comment['username'] }}
                                                                        </span>
                                                                        <span
                                                                            class="text-xs text-[var(--text-muted)] ml-2">
                                                                            {{ \Carbon\Carbon::parse($comment['timestamp'])->diffForHumans() }}
                                                                        </span>
                                                                    </div>

                                                                    <p
                                                                        class="text-sm text-[var(--text-primary)] leading-relaxed">
                                                                        {{ $comment['comment'] }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div id="answer-action-buttons-container-{{ $ans['id'] }}"
                                class="flex space-x-3 justify-end mt-6">
                                @if ($isAnswerOwner && !$isVerified && $answerVoteCount == 0)
                                    <a href="{{ route('user.answers.edit', ['answerId' => $ans['id']]) }}"
                                        class="action-button edit-answer-button inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <i class="fas fa-edit mr-2"></i>Edit
                                    </a>
                                    <button
                                        class="delete-answer-button action-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl"
                                        data-answer-id="{{ $ans['id'] }}">
                                        <i class="fas fa-trash-alt mr-2"></i>Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-[var(--bg-secondary)] rounded-lg p-8 shadow-lg text-center">
                    <i class="fa-solid fa-lightbulb text-4xl text-[var(--accent-tertiary)] mb-4"></i>
                    <h3 class="text-xl font-semibold text-[var(--text-primary)] mb-2">No Answers Yet</h3>
                    <p class="text-[var(--text-secondary)] mb-4">Be the first one to answer this question!</p>
                    <a href="#answer-textArea"
                        class="px-6 py-2 bg-[var(--accent-tertiary)] text-[var(--text-dark)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                        Write an Answer
                    </a>
                </div>
            @endif
        </div>
    </div>

     <script>
    document.addEventListener('DOMContentLoaded', () => {
// Modal for Question Comments
    const questionCommentsModal = document.getElementById('questionCommentsModal');
    const openQuestionCommentsModalBtn = document.getElementById('open-question-comments-modal-btn');
    const closeQuestionCommentsModalBtn = document.getElementById('close-question-comments-modal-btn');
    const questionCommentTextarea = document.getElementById('question-comment-textarea');

    function openModal() {
        if (questionCommentsModal) {
            questionCommentsModal.classList.remove('opacity-0', 'pointer-events-none');
            questionCommentsModal.classList.add('opacity-100', 'pointer-events-auto');
            if (questionCommentTextarea) {
                 questionCommentTextarea.focus();
            }
        }
    }

    function closeModal() {
        if (questionCommentsModal) {
            questionCommentsModal.classList.add('opacity-0', 'pointer-events-none');
            questionCommentsModal.classList.remove('opacity-100', 'pointer-events-auto');
        }
    }

    if (openQuestionCommentsModalBtn) {
        openQuestionCommentsModalBtn.addEventListener('click', openModal);
    }

    if (closeQuestionCommentsModalBtn) {
        closeQuestionCommentsModalBtn.addEventListener('click', closeModal);
    }

    if (questionCommentsModal) {
        // Close modal on backdrop click
        questionCommentsModal.addEventListener('click', (event) => {
            if (event.target === questionCommentsModal) {
                closeModal();
            }
        });
        // Close modal on Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && questionCommentsModal.classList.contains('opacity-100')) {
                closeModal();
            }
        });
    }

    // Toggle comment box for ANSWERS
    const answerCommentButtons = document.querySelectorAll('.answer-comment-btn'); // Ensure this class is on answer comment buttons
    answerCommentButtons.forEach(button => {
        button.addEventListener('click', () => {
            const answerId = button.dataset.answerId; // Assuming you have data-answer-id on these buttons
            const commentBox = document.getElementById(`answer-${answerId}-comment-box`); // Make sure this ID matches your answer comment boxes
            if (commentBox) {
                commentBox.classList.toggle('hidden');
                if (!commentBox.classList.contains('hidden')) {
                    commentBox.querySelector('textarea').focus();
                }
            }
        });
    });
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const API_BASE_URL = ("{{ env('API_URL', 'http://localhost:8001/api') }}" + '/').replace(/\/+$/, '/');
            const API_TOKEN = "{{ session('token') ?? '' }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
            // edit question
            document.querySelectorAll('.edit-question-button').forEach(button => {
                button.addEventListener('click', function() {
                    const questionId = this.dataset.questionId;
                    window.location.href = `{{ url('/ask') }}/${questionId}`;
                });
            });
            //delete question
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
                                        Swal.fire(
                                            'Deleted!',
                                            data.message ||
                                            'Your question has been deleted.',
                                            'success'
                                        );
                                        window.location.href = "{{ url('/') }}"
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

            const commentButtons = document.querySelectorAll('.comment-btn');
            commentButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const parentDiv = button.parentElement;
                    const commentBox = parentDiv?.nextElementSibling;
                    commentBox.classList.toggle('hidden');
                });
            });

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
            const commentCount = document.getElementById('reply-count');
            const answerTextArea = document.getElementById('answer-textArea');

            commentCount.addEventListener('click', (e) => {
                e.preventDefault();
                answerTextArea?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                answerTextArea?.focus();
            });

            const jsIsQuestionOwner = @json($isQuestionOwner);
            let currentAnswerCount = @json(count($question['answer'] ?? []));

            let jsHasAnswer = @json(!empty($question['answer']));
            let jsHasVote = @json(isset($question['vote']) && (int) $question['vote'] !== 0);
            const questionIdForActions = @json($question['id']);

            const questionActionButtonsContainer = document.getElementById('question-action-buttons-container');

            // Fungsi untuk menampilkan/menyembunyikan tombol aksi pertanyaan
            function updateQuestionActionButtonsVisibility() {
                if (!questionActionButtonsContainer || !jsIsQuestionOwner) {
                    if (questionActionButtonsContainer) questionActionButtonsContainer.innerHTML =
                        ''; // Kosongkan jika bukan owner
                    return;
                }

                if (!jsHasAnswer && !jsHasVote) {
                    // Jika tombol belum ada (karena kondisi awal false), buat dan tambahkan
                    if (!questionActionButtonsContainer.querySelector('.edit-question-button')) {
                        questionActionButtonsContainer.innerHTML = `
                    <button data-question-id="${questionIdForActions}"
                        class="action-button edit-question-button inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </button>
                    <button data-question-id="${questionIdForActions}"
                        class="action-button delete-question-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-trash-alt mr-2"></i>Delete
                    </button>
                `;
                        // Jika tombol edit/delete memiliki event listener sendiri, pasang lagi di sini
                        // attachEditButtonListener();
                        // attachDeleteButtonListener();
                    }
                } else {
                    // Jika kondisi tidak terpenuhi, kosongkan container tombol
                    questionActionButtonsContainer.innerHTML = '';
                }
            }

            if (document.getElementById('question-action-buttons-container')) {
                updateQuestionActionButtonsVisibility(); // Panggilan awal untuk pertanyaan
            }

            function updateAnswerActionButtonsVisibility(answerId) {
                const answerItemElement = document.getElementById(`answer-item-${answerId}`);
                // if (!answerItemElement) {
                //     console.error(`Answer item element with ID answer-item-${answerId} not found.`);
                //     return;
                // }

                const isOwner = answerItemElement.dataset.isOwner === 'true';
                const voteCount = parseInt(answerItemElement.dataset.voteCount, 10);
                const isVerified = answerItemElement.dataset.isVerified === 'true';

                const container = document.getElementById(`answer-action-buttons-container-${answerId}`);
                if (!container) {
                    console.error(`Action button container for answer ${answerId} not found.`);
                    return;
                }

                if (isOwner && !isVerified && voteCount === 0) {
                    // Cek apakah tombol sudah ada untuk menghindari duplikasi listener jika tidak menghapus total
                    if (!container.querySelector('.edit-answer-button')) {
                        // Tombol Edit: Menggunakan URL dari route Laravel
                        const editUrl = "{{ route('user.answers.edit', ['answerId' => ':answerId']) }}".replace(
                            ':answerId', answerId);

                        container.innerHTML = `
                    <a href="${editUrl}"
                       class="action-button edit-answer-button inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <button
                        class="delete-answer-button action-button inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl"
                        data-answer-id="${answerId}">
                        <i class="fas fa-trash-alt mr-2"></i>Delete
                    </button>
                `;
                        // Pasang listener untuk tombol delete yang baru dibuat
                        const newDeleteButton = container.querySelector('.delete-answer-button');
                        if (newDeleteButton) {
                            attachDeleteAnswerButtonListener(newDeleteButton);
                        }
                    }
                } else {
                    container.innerHTML = ''; // Kosongkan jika kondisi tidak terpenuhi
                }
            }

            // Fungsi untuk memasang listener ke tombol delete jawaban (bisa dipanggil untuk tombol awal & dinamis)
            function attachDeleteAnswerButtonListener(button) {
                button.addEventListener('click', function() {
                    const answerId = this.dataset.answerId;
                    const answerItemElement = document.getElementById(`answer-item-${answerId}`);

                    Swal.fire({
                        title: 'Delete Answer?',
                        text: "This action cannot be undone. Your answer will be permanently deleted.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        // ... (SweetAlert options lainnya)
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            // Ganti API_BASE_URL dan API_TOKEN jika menggunakan sistem API terpisah
                            // Jika tidak, gunakan route Laravel biasa untuk delete


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
                                        Swal.fire('Deleted!', data.message ||
                                            'Your answer has been deleted.', 'success');
                                        if (answerItemElement) {
                                            answerItemElement.style.animation =
                                                'fadeOutUp 0.5s ease forwards';
                                            setTimeout(() => {
                                                answerItemElement.remove();
                                                // Update hitungan jawaban jika perlu
                                            }, 500);
                                        }
                                    } else {
                                        Swal.fire('Error!', data.message ||
                                            'Could not delete the answer.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error deleting answer:', error);
                                    Swal.fire('Request Failed!', 'An error occurred.', 'error');
                                });
                        }
                    });
                });
            }

            // Pasang listener ke tombol delete jawaban yang sudah ada saat halaman dimuat
            document.querySelectorAll('.delete-answer-button').forEach(button => {
                attachDeleteAnswerButtonListener(button);
            });

            const submitButton = document.getElementById("submitAnswer-btn");
            const textArea = document.getElementById('answer-textArea');
            // const fileInput = document.getElementById("question-img");

            function generateVerifyBlockHtml(answerId, isQuestionOwnerLocal) {
                const isVerifiedForNewAnswer = false;
                const verifiedStatusForNewAnswer = 0;
                let verifyHtml = '';

                if (isQuestionOwnerLocal) {
                    verifyHtml = `
                <div id="answer-verify-block-${answerId}" class="mt-4 flex flex-col items-center">
                    <i id="verify-icon-${answerId}"
                        class="fa-regular fa-check-circle text-[#23BF7F] text-lg cursor-pointer verify-toggle-button"
                        data-answer-id="${answerId}"
                        data-current-verified="${verifiedStatusForNewAnswer}">
                    </i>
                    <span id="verify-text-${answerId}" class="text-xs text-[#23BF7F] mt-1">
                        Verify Answer
                    </span>
                    <span class="text-xs text-gray-500 mt-1 verify-toggle-button"
                        data-answer-id="${answerId}"
                        data-current-verified="${verifiedStatusForNewAnswer}" style="cursor:pointer;">
                        (Click icon or text to verify)
                    </span>
                </div>
            `;
                } else {
                    verifyHtml = '';
                }
                return verifyHtml;
            }

            if (submitButton && textArea) {
                submitButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    const answerText = textArea.value.trim();

                    // Show loading state
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.innerHTML =
                        '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Submitting...';
                    submitButton.disabled = true;

                    if (answerText === '') {
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please provide an answer!',
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('answer', answerText);

                    if (fileInput.files.length > 0) {
                        formData.append('image', fileInput.files[0]);
                    }

                    const questionId = @json($question['id']);

                    fetch(`{{ route('submitAnswer', ['questionId' => 'aaa']) }}`.replace('aaa',
                            questionId), {
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
                                // Clear form
                                textArea.value = '';
                                fileInput.value = '';
                                const imagePreviewsContainer = document.querySelector(
                                    ".image-previews");
                                if (imagePreviewsContainer) {
                                    imagePreviewsContainer.innerHTML = '';
                                    imagePreviewsContainer.classList.add('hidden');
                                }

                                jsHasAnswer =
                                    true; // Update state karena pertanyaan sekarang memiliki jawaban
                                updateQuestionActionButtonsVisibility
                                    (); // Perbarui visibilitas tombol Edit/Delete

                                currentAnswerCount++;

                                const timeAgo = formatTimeAgo(new Date(data.answer.timestamp));

                                const imageHtml = data.answer.image ?
                                    `<div class="mt-4">
                            <img src="/storage/${data.answer.image}" alt="Answer Image" 
                                 class="max-w-lg max-h-96 object-contain rounded-lg border">
                         </div>` : '';

                                const isOwnerForNewAnswer =
                                    true; // Pengguna yang submit adalah pemiliknya
                                const voteCountForNewAnswer = 0;
                                const isVerifiedForNewAnswer = false;
                                const verifyBlockForNewAnswer = generateVerifyBlockHtml(data.answer.id,
                                    jsIsQuestionOwner);

                                const htmlContent = `
                       <div class="bg-[var(--bg-secondary)] rounded-lg p-6 shadow-lg"
                 id="answer-item-${data.answer.id}"   увагу MODIFIED: Tambahkan ID dan data attributes
                 data-answer-id="${data.answer.id}"
                 data-is-owner="${isOwnerForNewAnswer ? 'true' : 'false'}"
                 data-is-verified="${isVerifiedForNewAnswer ? 'true' : 'false'}"
                 data-vote-count="${voteCountForNewAnswer}">

                <div class="flex items-start">
                            <div class="interaction-section flex flex-col items-center mr-6">
                                <button class="upVoteAnswer vote-btn mb-2 text-[var(--text-primary)] hover:text-[#633F92] focus:outline-none thumbs-up"
                                        data-answer-id="${data.answer.id}">
                                    <i class="text-2xl text-[#23BF7F] fa-solid fa-chevron-up"></i>
                                </button>
                                <span class="thumbs-up-count text-lg font-semibold text-[var(--text-secondary)] my-1">0</span>
                                <button class="downVoteAnswer vote-btn mt-2 text-[var(--text-primary)] hover:text-gray-700 focus:outline-none thumbs-down"
                                        data-answer-id="${data.answer.id}">
                                    <i class="text-2xl text-[#FE0081] fa-solid fa-chevron-down"></i>
                                </button>
                                ${verifyBlockForNewAnswer}
                            </div>

                            <div class="flex flex-col flex-grow">
                                <div class="prose max-w-none text-[var(--text-primary)]">
                                    <p>${escapeHtml(data.answer.answer)}</p>
                                </div>
                                ${imageHtml}
                                
                                <div class="mt-4 flex justify-between items-center">
                                    <div class="flex items-center text-sm text-[var(--text-muted)]">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.answer.username)}&background=random" 
                                             alt="User" class="w-6 h-6 rounded-full mr-2">
                                        <span>Answered by ${escapeHtml(data.answer.username)} - ${timeAgo}</span>
                                    </div>
                                    <button class="comment-btn flex items-center text-[var(--text-secondary)] hover:text-[var(--accent-primary)] transition-colors">
                                        <i class="fa-solid fa-comment-dots mr-2"></i>
                                        <span>Add Comment</span>
                                    </button>
                                </div>

                                <div class="comment-box hidden mt-4 w-full comment-animation">
                                    <textarea id="answer-comment-${data.answer.id}"
                                              class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-primary)]"
                                              rows="2" placeholder="Write your comment here!"></textarea>
                                    <button id="submit-comment-${data.answer.id}" data-answer-id="${data.answer.id}"
                                            class="mt-4 px-4 py-2 bg-[var(--bg-button)] text-[var(--text-button)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                                        Submit Comment
                                    </button>
                                </div>
                            </div>
                            </div>
                             <div id="answer-action-buttons-container-${data.answer.id}"
                                class="flex space-x-3 justify-end mt-6"></div>
                         </div>
                    `;

                                let answerList = document.getElementById('answerList');
                                if (!answerList) {
                                    const noAnswersSection = document.querySelector(
                                        '.answer-section .bg-\\[var\\(--bg-secondary\\)\\]');
                                    if (noAnswersSection) {
                                        answerList = document.createElement('div');
                                        answerList.id = 'answerList';
                                        answerList.className = 'space-y-6';
                                        noAnswersSection.parentNode.replaceChild(answerList,
                                            noAnswersSection);
                                    }
                                }

                                if (answerList) {
                                    answerList.insertAdjacentHTML('beforeend', htmlContent);
                                    const newAnswerElement = document.getElementById(
                                        `answer-item-${data.answer.id}`);

                                    // Setelah HTML jawaban baru ditambahkan:
                                    // 1. Pasang listener untuk tombol vote, comment, dll. pada jawaban baru
                                    attachAnswerEventListeners(data.answer.id);

                                    // 2. Pasang listener untuk tombol verifikasi BARU
                                    if (newAnswerElement) {
                                        const newVerifyToggleButtons = newAnswerElement
                                            .querySelectorAll('.verify-toggle-button');
                                        newVerifyToggleButtons.forEach(btn => {
                                            attachVerifyButtonListener(btn); // PENTING!
                                        });
                                    }
                                    const answerHeader = document.querySelector(
                                        '.answer-section h2 span');
                                    if (answerHeader) {
                                        answerHeader.textContent = `(${currentAnswerCount})`;
                                    }

                                    attachAnswerEventListeners(data.answer.id);
                                    updateAnswerActionButtonsVisibility(data.answer.id);


                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Answer Submitted!',
                                    text: 'Your answer has been successfully submitted.',
                                    confirmButtonText: 'Great!',
                                });

                                let replies = document.querySelector('#reply-count small')
                                let answerCountAtas = document.querySelector('#answerCountAtas span')
                                let count = parseInt(replies.textContent.trim(), 10) + 1;
                                replies.textContent = count;
                                answerCountAtas.textContent = count;
                                console.log('aman bgt bung 1');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Something went wrong',
                                });
                            }
                        })
                    // .catch(error => {
                    //     submitButton.innerHTML = originalButtonText;
                    //     submitButton.disabled = false;
                    //     console.error('Error:', error);

                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Error',
                    //         text: 'There was a network error. Please try again.',
                    //     });
                    // });
                });
            }
            // });

            function escapeHtml(text) {
                if (typeof text !== 'string') return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function formatTimeAgo(date) {
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) return `${diffInSeconds} seconds ago`;
                if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
                if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
                return `${Math.floor(diffInSeconds / 86400)} days ago`;
            }

            function attachAnswerEventListeners(answerId) {
                const commentBtn = document.querySelector(`#submit-comment-${answerId}`).parentElement
                    .previousElementSibling.lastElementChild;
                if (commentBtn && commentBtn.classList.contains('comment-btn')) {
                    commentBtn.addEventListener('click', () => {
                        const parentDiv = commentBtn.parentElement;
                        const commentBox = parentDiv?.nextElementSibling;
                        commentBox.classList.toggle('hidden');
                    });
                }

                const upVoteBtn = document.querySelector(`[data-answer-id="${answerId}"].upVoteAnswer`);
                const downVoteBtn = document.querySelector(`[data-answer-id="${answerId}"].downVoteAnswer`);

                if (upVoteBtn) {
                    upVoteBtn.addEventListener('click', () => handleVoteA(true, answerId));
                }
                if (downVoteBtn) {
                    downVoteBtn.addEventListener('click', () => handleVoteA(false, answerId));
                }

                const submitCommentButton = document.getElementById(`submit-comment-${answerId}`);
                if (submitCommentButton) {
                    submitCommentButton.addEventListener('click', (event) => {
                        event.preventDefault();

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
                            formData.append('commentable_id', answerId);
                            formData.append('commentable_type', 'answer');

                            fetch(`{{ route('comment.submit' ) }}`, {
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

                                        const commentBox = commentTextArea.closest('.comment-box');
                                        if (commentBox) {
                                            commentBox.classList.add('hidden');
                                        }
                                        commentTextArea.value = '';

                                        const answerContainer = document.querySelector(
                                            `[data-answer-id="${answerId}"]`).closest(
                                            '.bg-\\[var\\(--bg-secondary\\)\\]');

                                        let commentsSection = answerContainer.querySelector(
                                            '.answer-comments-section');

                                        if (!commentsSection) {
                                            commentsSection = document.createElement('div');
                                            commentsSection.className =
                                                'answer-comments-section mt-6 pt-4 border-t border-[var(--border-color)]';
                                            commentsSection.innerHTML = `
                                <h4 class="text-sm font-semibold text-[var(--text-primary)] mb-3 flex items-center">
                                    <i class="fa-solid fa-comments mr-2 text-[var(--accent-tertiary)]"></i>
                                    Comments (1)
                                </h4>
                                <div class="space-y-3"></div>
                            `;
                                            const flexGrowDiv = answerContainer.querySelector(
                                                '.flex-grow');
                                            flexGrowDiv.appendChild(commentsSection);
                                        }

                                        const timeAgo = formatTimeAgo(new Date(data.comment.timestamp));
                                        const commentDiv = document.createElement('div');
                                        commentDiv.className =
                                            'answer-comment bg-[var(--bg-card)] p-3 rounded-lg border-l-2 border-[var(--accent-tertiary)]';
                                        commentDiv.innerHTML = `
                            <div class="flex items-start">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.comment.username)}&background=random" 
                                     alt="${data.comment.username}"
                                     class="w-6 h-6 rounded-full mr-3 mt-1">
                                <div class="flex-grow">
                                    <div class="flex items-center mb-1">
                                        <span class="text-sm font-medium text-[var(--text-primary)]">
                                            ${data.comment.username}
                                        </span>
                                        <span class="text-xs text-[var(--text-muted)] ml-2">
                                            ${timeAgo}
                                        </span>
                                    </div>
                                    <p class="text-sm text-[var(--text-primary)] leading-relaxed">
                                        ${data.comment.comment}
                                    </p>
                                </div>
                            </div>
                        `;

                                        const commentsList = commentsSection.querySelector(
                                            '.space-y-3');
                                        commentsList.appendChild(commentDiv);

                                        const commentsHeader = commentsSection.querySelector('h4');
                                        const currentCount = commentsSection.querySelectorAll(
                                                '.answer-comment')
                                            .length;
                                        commentsHeader.innerHTML = `
                            <i class="fa-solid fa-comments mr-2 text-[var(--accent-tertiary)]"></i>
                            Comments (${currentCount})
                        `;

                                        const commentButton = answerContainer.querySelector(
                                            '.comment-btn span');
                                        if (commentButton) {
                                            commentButton.textContent = `${currentCount} Comments`;
                                        }

                                        commentDiv.style.opacity = '0';
                                        commentDiv.style.transform = 'translateY(-10px)';
                                        setTimeout(() => {
                                            commentDiv.style.transition =
                                                'all 0.3s ease-in-out';
                                            commentDiv.style.opacity = '1';
                                            commentDiv.style.transform = 'translateY(0)';
                                        }, 100);
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
                }
            }
            const submitCommentButton = document.getElementById("qComment-btn");

            submitCommentButton.addEventListener('click', (event) => {
                const commentTextArea = document.getElementById("question-comment-textarea");
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
                    formData.append('commentable_id', questionId);
                    formData.append('commentable_type', 'question');

                    // Send comment data to the server
                    fetch(`{{ route('comment.submit') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            },
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            
                            if (data.success) {
                                let commentList = document.getElementById('question-comments');
                                const timeAgo = formatTimeAgo(new Date(data.comment.timestamp));

                                const htmlContent = `
                            <div class="comment bg-[var(--bg-card)] p-4 rounded-lg flex items-start">
                                <div class="flex-grow">
                                    <p class="text-[var(--text-primary)]">${escapeHtml(data.comment.comment)}</p>
                                    <div class="mt-2 text-xs text-[var(--text-muted)]">
                                        <span>Posted by ${escapeHtml(data.comment.username)} - ${timeAgo}</span>
                                    </div>
                                </div>
                            </div>
                        `;

                                if (!commentList) {
                                    const noCommentsSection = document.querySelector(
                                        '#comments-section .bg-\\[var\\(--bg-card\\)\\]');

                                    if (noCommentsSection && noCommentsSection.textContent.includes(
                                            'There are no comments yet')) {
                                        commentList = document.createElement('div');
                                        commentList.id = 'question-comments';
                                        commentList.className = 'space-y-3';

                                        noCommentsSection.parentNode.replaceChild(commentList,
                                            noCommentsSection);
                                    }
                                }

                                if (commentList) {
                                    commentList.insertAdjacentHTML('beforeend', htmlContent);
                                }

                                // Update comment counts
                                const commentCount = document.querySelector('#comment-count span');
                                const commentSectionCount = document.querySelector(
                                    '#comments-section h3 span');

                                if (commentCount) {
                                    let count1 = parseInt(commentCount.textContent.trim(), 10);
                                    count1 += 1;
                                    commentCount.textContent = `${count1} Comments`;
                                }

                                if (commentSectionCount) {
                                    let count2 = parseInt(commentSectionCount.textContent.replace(
                                        /[()]/g, ''), 10);
                                    count2 += 1;
                                    commentSectionCount.textContent = `(${count2})`;
                                }

                                const commentBox = commentTextArea.closest('.comment-box');
                                if (commentBox) {
                                    commentBox.classList.add('hidden');
                                }
                                commentTextArea.value = '';

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
            // });

            // COMMENT DI ANSWERRRR

            // document.addEventListener('DOMContentLoaded', () => {
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
                        formData.append('commentable_id', answerId);
                        formData.append('commentable_type', 'answer');

                        fetch(`{{ route('comment.submit' ) }}`, {
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

                                    const commentBox = commentTextArea.closest('.comment-box');
                                    if (commentBox) {
                                        commentBox.classList.add('hidden');
                                    }
                                    commentTextArea.value = '';

                                    // Find the answer container
                                    const answerContainer = document.querySelector(
                                        `[data-answer-id="${answerId}"]`).closest(
                                        '.bg-\\[var\\(--bg-secondary\\)\\]');

                                    // Look for existing comments section
                                    let commentsSection = answerContainer.querySelector(
                                        '.answer-comments-section');

                                    if (!commentsSection) {
                                        // Create comments section if it doesn't exist
                                        commentsSection = document.createElement('div');
                                        commentsSection.className =
                                            'answer-comments-section mt-6 pt-4 border-t border-[var(--border-color)]';
                                        commentsSection.innerHTML = `
                                    <h4 class="text-sm font-semibold text-[var(--text-primary)] mb-3 flex items-center">
                                        <i class="fa-solid fa-comments mr-2 text-[var(--accent-tertiary)]"></i>
                                        Comments (1)
                                    </h4>
                                    <div class="space-y-3"></div>
                                `;

                                        // Insert it before the end of the answer container's flex-grow div
                                        const flexGrowDiv = answerContainer.querySelector(
                                            '.flex-grow');
                                        flexGrowDiv.appendChild(commentsSection);
                                    }

                                    // Create the new comment element
                                    const timeAgo = formatTimeAgo(new Date(data.comment
                                        .timestamp));

                                    const commentDiv = document.createElement('div');
                                    commentDiv.className =
                                        'answer-comment bg-[var(--bg-card)] p-3 rounded-lg border-l-2 border-[var(--accent-tertiary)]';

                                    commentDiv.innerHTML = `
                                <div class="flex items-start">
                                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.comment.username)}&background=random" 
                                         alt="${data.comment.username}"
                                         class="w-6 h-6 rounded-full mr-3 mt-1">
                                    
                                    <div class="flex-grow">
                                        <div class="flex items-center mb-1">
                                            <span class="text-sm font-medium text-[var(--text-primary)]">
                                                ${data.comment.username}
                                            </span>
                                            <span class="text-xs text-[var(--text-muted)] ml-2">
                                                ${timeAgo}
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm text-[var(--text-primary)] leading-relaxed">
                                            ${data.comment.comment}
                                        </p>
                                    </div>
                                </div>
                            `;

                                    const commentsList = commentsSection.querySelector(
                                        '.space-y-3');
                                    commentsList.appendChild(commentDiv);

                                    const commentsHeader = commentsSection.querySelector('h4');
                                    const currentCount = commentsSection.querySelectorAll(
                                        '.answer-comment').length;
                                    commentsHeader.innerHTML = `
                                <i class="fa-solid fa-comments mr-2 text-[var(--accent-tertiary)]"></i>
                                Comments (${currentCount})
                            `;

                                    const commentButton = answerContainer.querySelector(
                                        '.comment-btn span');
                                    if (commentButton) {
                                        commentButton.textContent =
                                            `${currentCount} Comments`;
                                    }

                                    commentDiv.style.opacity = '0';
                                    commentDiv.style.transform = 'translateY(-10px)';
                                    setTimeout(() => {
                                        commentDiv.style.transition =
                                            'all 0.3s ease-in-out';
                                        commentDiv.style.opacity = '1';
                                        commentDiv.style.transform = 'translateY(0)';
                                    }, 100);

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

            const apiBaseUrl = (("{{ env('API_URL') }}" || window.location.origin) + '/').replace(/\/+$/, '/');
            const apiToken = "{{ session('token') }}"

            function attachVerifyButtonListener(buttonElement) {
                const apiToken = "{{ session('token') }}"; // Pastikan ini juga sesuai

                buttonElement.addEventListener('click', function() {
                    const answerId = this.dataset.answerId;
                    const currentVerifiedStatus = parseInt(this.dataset.currentVerified);
                    const newVerifiedStatus = currentVerifiedStatus === 0 ? 1 : 0;

                    const actionText = newVerifiedStatus === 1 ? 'verify' : 'un-verify';
                    const iconElement = document.getElementById(`verify-icon-${answerId}`);
                    const textElement = document.getElementById(`verify-text-${answerId}`);
                    // const allToggleButtonsForThisAnswer = document.querySelectorAll( // Tidak digunakan lagi, bisa dihapus jika tidak ada referensi lain
                    //     `.verify-toggle-button[data-answer-id="${answerId}"]`);

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to ${actionText} this answer.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes, ${actionText} it!`
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`${apiBaseUrl}answers/${answerId}/updatePartial`, { // Gunakan apiBaseUrl
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}", // Gunakan CSRF_TOKEN global
                                        'Authorization': `Bearer ${apiToken}` // Gunakan apiToken
                                    },
                                    body: JSON.stringify({
                                        verified: newVerifiedStatus
                                    })
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
                                        const answerItemElement = document.getElementById(
                                            `answer-item-${answerId}`);
                                        if (answerItemElement) {
                                            // Ambil status verified baru dari response server, atau toggle jika tidak ada di response
                                            const actualNewVerifiedStatus = parseInt(data.answer
                                                ?.verified ?? (currentVerifiedStatus === 0 ?
                                                    1 : 0));
                                            answerItemElement.dataset.isVerified =
                                                actualNewVerifiedStatus === 1 ? 'true' :
                                                'false';

                                            // Update semua tombol toggle untuk answer ini
                                            document.querySelectorAll(
                                                `.verify-toggle-button[data-answer-id="${answerId}"]`
                                                ).forEach(btn => {
                                                btn.dataset.currentVerified =
                                                    actualNewVerifiedStatus;
                                            });
                                        }
                                        updateAnswerActionButtonsVisibility(
                                        answerId); // Update tombol edit/delete jika perlu
                                        Swal.fire(
                                            `${newVerifiedStatus === 1 ? 'Verified!' : 'Un-verified!'}`,
                                            `The answer has been ${newVerifiedStatus === 1 ? 'verified' : 'un-verified'}.`, // pastikan actionText sesuai
                                            'success'
                                        );

                                        if (iconElement) {
                                            if (newVerifiedStatus === 1) {
                                                iconElement.classList.remove('fa-regular');
                                                iconElement.classList.add('fa-solid');
                                                iconElement.classList.remove('cursor-pointer');
                                            } else {
                                                iconElement.classList.remove('fa-solid');
                                                iconElement.classList.add('fa-regular');
                                                iconElement.classList.add('cursor-pointer');
                                            }
                                        }
                                        if (textElement) {
                                            textElement.textContent = newVerifiedStatus === 1 ?
                                                'Verified Answer' : 'Verify Answer';
                                        }
                                        const helperTextElement = document.querySelector(
                                            `#answer-verify-block-${answerId} span.text-gray-500`
                                            );
                                        if (helperTextElement) {
                                            helperTextElement.textContent =
                                                newVerifiedStatus === 1 ?
                                                '(Click icon or text to unverify)' :
                                                '(Click icon or text to verify)';
                                        }
                                    } else {
                                        Swal.fire('Error!', data.message ||
                                            'Could not update verification status.', 'error'
                                            );
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    let errorMessage =
                                        'An error occurred while updating the answer.';
                                    if (error && error.message) {
                                        errorMessage = error.message;
                                    } else if (typeof error === 'string') {
                                        errorMessage = error;
                                    }
                                    Swal.fire('Request Failed!', errorMessage, 'error');
                                });
                        }
                    });
                });
            }

            // Pasang listener ke tombol verifikasi yang SUDAH ADA saat halaman dimuat
            document.querySelectorAll('.verify-toggle-button').forEach(button => {
                attachVerifyButtonListener(button);
            });
            if (document.getElementById('answerList')) {
                document.querySelectorAll('[id^="answer-item-"]').forEach(answerElement => {
                    updateAnswerActionButtonsVisibility(answerElement.dataset.answerId);
                });
            }

            const questionId = @json($question['id']);

            const upVoteButtonQ = document.getElementById('upVoteQuestion');
            const downVoteButtonQ = document.getElementById('downVoteQuestion');

            const handleVoteQ = (voteType) => {
                const formData = new FormData();
                formData.append('vote', voteType);
                formData.append('question_id', questionId);
                Swal.fire({
                    title: '',
                    background: 'transparent',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                fetch(`{{ route('question.vote') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,

                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            const voteTotal = document.getElementById('voteTotal');
                            voteTotal.textContent = `${data.voteUpdated}`;
                            // 1. Perbarui state jsHasVote
                            jsHasVote = (parseInt(data.voteUpdated) !==
                                0); // Jika voteUpdated tidak 0, berarti ada vote

                            // 2. Panggil fungsi untuk mengevaluasi ulang visibilitas tombol
                            updateQuestionActionButtonsVisibility();
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

            upVoteButtonQ.addEventListener('click', () => handleVoteQ(true));
            downVoteButtonQ.addEventListener('click', () => handleVoteQ(false));

            // Vote Answer
            const upVoteButtonsA = document.querySelectorAll('.upVoteAnswer');
            const downVoteButtonsA = document.querySelectorAll('.downVoteAnswer');

            const handleVoteA = (voteType, id) => {

                const formData = new FormData();
                formData.append('vote', voteType);
                formData.append('answer_id', id);
                Swal.fire({
                    title: '',
                    background: 'transparent',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                fetch(`{{ route('answer.vote') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            const answerItemElement = document.getElementById(`answer-item-${id}`);
                            // Update the vote count
                            const voteCountElement = document.querySelector(`[data-answer-id="${id}"]`)
                                .nextElementSibling;
                            if (answerItemElement) {
                                const voteCountElement = answerItemElement.querySelector(
                                    '.thumbs-up-count');
                                if (voteCountElement) {
                                    voteCountElement.textContent = data.voteAnswerUpdated;
                                    answerItemElement.dataset.voteCount = data
                                        .voteAnswerUpdated; // Update data attribute
                                }
                            }
                            updateAnswerActionButtonsVisibility(id);
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Vote Submitted!',
                            //     text: 'Your vote has been successfully recorded.',
                            // });
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

            upVoteButtonsA.forEach(button => {
                button.addEventListener('click', () => {
                    const answerId = button.getAttribute('data-answer-id');
                    handleVoteA(true, answerId);
                });
            });

            downVoteButtonsA.forEach(button => {
                button.addEventListener('click', () => {
                    const answerId = button.getAttribute('data-answer-id');
                    handleVoteA(false, answerId);
                });
            });
        });
    </script>
@endsection
