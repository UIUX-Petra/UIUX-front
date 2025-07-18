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
            background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-secondary) 100%);
            border: 2px solid var(--border-color);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .question-card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
            border-color: var(--accent-primary);
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
            padding: 0.25rem 0;
            border-radius: 9999px;
            font-weight: 600;
            display: inline-flex;
            align-items: start;
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

        #questionCommentsModal {
            transition: opacity 0.3s ease-in-out;
        }

        #questionCommentsModal .modal-content {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        #questionCommentsModal.opacity-0 .modal-content {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }

        /* When modal is visible (JS adds opacity-100, removes pointer-events-none) */
        #questionCommentsModal.opacity-100 .modal-content {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .dropdown-menu {
            transition: opacity 0.2s ease-out, transform 0.2s ease-out;
        }

        #answer-input-section {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top;
        }

        #answer-input-section.hidden {
            opacity: 0;
            transform: scaleY(0) translateY(-20px);
            max-height: 0;
            overflow: hidden;
        }

        #answer-input-section:not(.hidden) {
            opacity: 1;
            transform: scaleY(1) translateY(0);
            max-height: none;
        }

        /* Answer card styling */
        .answer-input-card {
            background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-secondary) 100%);
            border: 2px solid var(--border-color);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .answer-input-card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
            border-color: var(--accent-primary);
        }

        /* Floating button animation */
        #show-answer-input-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #show-answer-input-btn.active {
            transform: rotate(45deg) scale(1.1);
            background: linear-gradient(135deg, #FE0081, #FF6B6B);
        }

        /* Image preview improvements */
        .image-preview {
            position: relative;
            display: inline-block;
            max-width: 200px;
        }

        .image-preview img {
            width: 100%;
            height: auto;
            max-height: 150px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .remove-image-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background: var(--accent-neg);
            color: white;
            border: 2px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s ease;
        }

        .remove-image-btn:hover {
            transform: scale(1.1);
            background: var(--accent-neg; )
        }

        #answer-textArea:focus {
            outline: none;
            ring: 2px;
            ring-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(56, 163, 165, 0.1);
        }

        .your-question-indicator {
            box-shadow: 0 0 15px rgba(56, 161, 105, 0.6), 0 0 30px rgba(56, 161, 105, 0.4);
            border-color: var(--accent-primary);
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
                        <span class="text-[var(--text-secondary)]">{{ count($question['answers']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="question-card rounded-lg p-6 mb-6 flex items-start">
            {{-- Vote Section (No Changes) --}}
            <div class="interaction-section flex flex-col items-center mr-6">
                <button id="upVoteQuestion"
                    class="mb-2 vote-btn text-[var(--text-primary)] hover:text-[#633F92] focus:outline-none thumbs-up">
                    <i class="text-2xl text-[#23BF7F] fa-solid fa-thumbs-up"></i>
                </button>
                <span id="voteTotal" class="text-lg font-semibold text-[var(--text-secondary)] my-1">
                    {{ $question['vote'] }}
                </span>
                <button id="downVoteQuestion"
                    class="mt-2 text-[var(--text-primary)] hover:text-gray-700 focus:outline-none thumbs-down">
                    <i class="text-2xl text-[#FE0081] fa-solid fa-thumbs-down"></i>
                </button>


                <div class="flex flex-col items-center mt-4" id="reply-count">
                    <button class="text-[var(--text-primary)] hover:text-yellow-100 focus:outline-none">
                        {{-- <i class="fa-solid fa-comments text-md"></i> --}}
                    </button>
                    <small class="text-[var(--text-secondary)] text-xs mt-1 cursor-pointer">
                        {{-- {{ count($question['answer']) }} --}}
                    </small>
                </div>
            </div>

            {{-- Main Question Content --}}
            <div class="flex flex-col flex-grow">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center">
                        <div class="card-badge bg-transparent bg-opacity-20 text-[var(--accent-secondary)]">
                            <i class="fa-solid fa-circle-question mr-1"></i> Question
                        </div>
                        <span class="text-xs text-[var(--text-muted)] ml-3">
                            Posted {{ \Carbon\Carbon::parse($question['timestamp'])->diffForHumans() }}
                        </span>
                    </div>

                    @if ($isQuestionOwner)
                        <div class="relative">
                            <button id="question-actions-toggle"
                                class="w-8 h-8 flex items-center justify-center rounded-full text-[var(--text-muted)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)] transition-colors"
                                title="More options">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>

                            <div id="question-actions-menu"
                                class="dropdown-menu absolute right-0 mt-2 w-48 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-lg shadow-xl z-10 hidden"
                                style="opacity: 0; transform: translateX(10px);">
                                @php
                                    $hasAnswer = !empty($question['answers']);
                                    $currentVoteCount = isset($question['vote']) ? (int) $question['vote'] : 0;
                                    $hasVote = $currentVoteCount !== 0;
                                @endphp
                                @if (!$hasAnswer && !$hasVote)
                                    <a href="{{ url('/ask') }}/${questionId}"
                                        class="edit-question-link flex items-center px-4 py-2 text-sm text-[var(--text-primary)]  hover:bg-[var(--accent-tertiary)] hover:text-[var(--text-dark)]">
                                        <i class="fa-solid fa-edit w-6 mr-2"></i>
                                        Edit
                                    </a>
                                    <button data-question-id="{{ $question['id'] }}"
                                        class="delete-question-button flex items-center w-full px-4 py-2 text-sm text-[var(--accent-neg)] hover:bg-[var(--accent-neg)] hover:text-white">
                                        <i class="fa-solid fa-trash w-6 mr-2"></i>
                                        Delete
                                    </button>
                                @else
                                    <p class="px-4 py-3 text-sm text-center text-[var(--text-muted)]">
                                        Actions are disabled once the question has votes or answers.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="prose max-w-none text-[var(--text-primary)]">
                    <p class="text-md md:text-lg text-[var(--text-primary)]">
                        {!! nl2br(e($question['question'])) !!}
                    </p>
                    @if ($question['image'])
                        <div class="mt-4">
                            <img src="{{ env('IMAGE_PATH', 'http://localhost:8001/storage') . '/' . $question['image'] }}"
                                alt="Question Image" class="rounded-lg shadow-md max-w-lg max-h-96 object-contain">
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

                    <div class="flex items-center space-x-4">
                        <button
                            class="open-report-modal-btn flex items-center space-x-2 text-sm text-[var(--text-secondary)] hover:text-[var(--accent-neg)] transition-colors duration-200"
                            data-reportable-id="{{ $question['id'] }}" data-reportable-type="question"
                            title="Report this question">
                            <i class="fa-solid fa-flag"></i>
                            <span>Report</span>
                        </button>

                        <button id="open-question-comments-modal-btn"
                            class="flex items-center space-x-2 text-sm text-[var(--text-secondary)] hover:text-[var(--accent-primary)] bg-[var(--bg-secondary)] hover:bg-[var(--bg-card-hover)] px-3 py-1.5 border border-[var(--border-color)] rounded-full transition-all duration-200"
                            title="View or add comments">
                            <i class="fa-solid fa-comment-dots"></i>
                            <span id="question-card-comment-count-text">{{ $question['comment_count'] }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Answer Input Section -->
        <div id="answer-input-section" class="mt-10 hidden">
            <div class="answer-input-card rounded-xl p-6 mb-6 backdrop-blur-sm">
                <div class="flex items-center mb-6 pb-4 border-b border-[var(--border-color)]">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] rounded-full mr-4">
                        <i class="fa-solid fa-pen-to-square text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-[var(--text-primary)] flex items-center">
                            Write an Answer
                        </h2>
                        <p class="text-sm text-[var(--text-muted)] mt-1">Share your knowledge and help others</p>
                    </div>
                </div>

                <div class="image-previews hidden mb-6">
                    <div class="flex items-center mb-3">
                        <i class="fa-solid fa-image text-[var(--accent-tertiary)] mr-2"></i>
                        <span class="text-sm font-medium text-[var(--text-primary)]">Image Preview</span>
                    </div>
                    <div class="image-preview-container flex flex-wrap gap-3">
                    </div>
                </div>

                <div class="relative mb-6">
                    <textarea id="answer-textArea"
                        class="w-full bg-[var(--bg-input)] rounded-xl p-4 text-[var(--text-primary)] placeholder-[var(--text-muted)] min-h-[160px] border-2 border-transparent focus:border-[var(--accent-primary)] resize-none transition-all duration-300"
                        placeholder="Write your detailed answer here... Be specific and helpful!"></textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-[var(--text-muted)]">
                        <i class="fa-solid fa-keyboard mr-1"></i>
                        Press Ctrl+Enter to submit
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="relative group">
                            <label tabindex="0"
                                class="flex items-center px-4 py-2.5 bg-[var(--accent-tertiary)] hover:bg-[var(--bg-secondary)] border-2 border-[var(--accent-tertiary)] hover:border-[var(--border-color)] text-[var(--text-dark)] hover:text-[var(--text-primary)] rounded-lg cursor-pointer transition-all duration-300 group">
                                <i class="fa-solid fa-image text-lg"></i>
                                <input type="file" id="question-img" class="hidden image-upload" accept="image/*">
                            </label>

                            <div
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-3 px-3 py-2 bg-[var(--bg-shadow)] text-[var(--text-light)] text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-10 shadow-lg">
                                JPG, PNG, JPEG (Max 5MB)
                                <div
                                    class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900">
                                </div>
                            </div>
                        </div>

                        <span class="text-xs text-[var(--text-muted)] flex items-center">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            Optional image attachment
                        </span>
                    </div>

                    <button id="submitAnswer-btn"
                        class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-white rounded-full hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-110 transition-all duration-300 group relative overflow-hidden">
                        <i
                            class="fa-solid fa-paper-plane text-lg group-hover:scale-110 transition-transform duration-200"></i>

                        <div
                            class="absolute inset-0 rounded-full bg-white opacity-0 group-active:opacity-20 transition-opacity duration-150">
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Answers Section -->
        <div class="mt-10 answer-section pt-6">
            <h2 class="text-xl font-bold text-[var(--text-primary)] mb-4 flex items-center">
                <i class="fa-solid fa-list-check mr-2 text-[var(--accent-primary)]"></i>
                Answers <span
                    class="text-sm text-[var(--text-muted)] ml-2">({{ count($question['answers'] ?? []) }})</span>
            </h2>

            @if ($question['answers'])
                <div id="answerList" class="space-y-6">
                    @foreach ($question['answers'] as $ans)
                        @php
                            $isAnswerOwner = session('email') === ($ans['email'] ?? null);
                            $answerVoteCount = (int) ($ans['vote'] ?? 0);
                            $isVerified = (int) $ans['verified'] === 1;
                        @endphp
                        <div class="relative bg-[var(--bg-secondary)] rounded-lg p-6 shadow-lg"
                            id="answer-item-{{ $ans['id'] }}" data-answer-id="{{ $ans['id'] }}"
                            data-is-owner="{{ $isAnswerOwner ? 'true' : 'false' }}"
                            data-is-verified="{{ $isVerified ? 'true' : 'false' }}"
                            data-vote-count="{{ $answerVoteCount }}">
                            <div class="flex items-start">
                                <div class="interaction-section flex flex-col items-center mr-6">
                                    <button
                                        class="upVoteAnswer vote-btn mb-2 text-[var(--text-primary)] hover:text-[#633F92] focus:outline-none thumbs-up"
                                        data-answer-id="{{ $ans['id'] }}">
                                        <i class="text-2xl text-[#23BF7F] fa-solid fa-thumbs-up"></i>
                                    </button>
                                    <span
                                        class="thumbs-up-count text-lg font-semibold text-[var(--text-secondary)] my-1">{{ $ans['vote'] }}</span>
                                    <button
                                        class="downVoteAnswer vote-btn mt-2 text-[var(--text-primary)] hover:text-gray-700 focus:outline-none thumbs-down"
                                        data-answer-id="{{ $ans['id'] }}">
                                        <i class="text-2xl text-[#FE0081] fa-solid fa-thumbs-down"></i>
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
                                                class="w-full max-w-lg max-h-96 object-contain rounded-lg border">
                                        </div>
                                    @endif

                                    <div class="mt-4 flex justify-between items-center">
                                        <a href="{{ route('viewUser', ['email' => $ans['user']['email']]) }}"
                                            class="flex items-center text-sm text-[var(--text-muted)]">
                                            {{-- <div class="flex items-center text-sm text-[var(--text-muted)]"> --}}
                                            <img src="{{ $ans['user']['image'] ? asset('storage/' . $ans['user']['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($ans['username'] ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                                                alt="User avatar" class="w-6 h-6 rounded-full mr-2 flex-shrink-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center">

                                                <span class="hover:underline">Answered by
                                                    {{ $ans['user']['username'] }}</span>

                                                <span class="hidden sm:inline-block mx-1">-</span>
                                                <span class="text-xs text-[var(--text-muted)] sm:text-sm">
                                                    {{ \Carbon\Carbon::parse($ans['timestamp'])->diffForHumans() }}
                                                </span>
                                            </div>
                                        </a>

                                        <div class="flex items-center space-x-4 text-sm">
                                            <button
                                                class="open-report-modal-btn flex items-center space-x-2 text-[var(--text-secondary)] hover:text-[var(--accent-neg)] transition-colors duration-200"
                                                data-reportable-id="{{ $ans['id'] }}" data-reportable-type="answer"
                                                title="Report this answer">
                                                <i class="fa-solid fa-flag"></i>
                                                <span>Report</span>
                                            </button>

                                            <button
                                                class="open-answer-comments-modal-btn flex items-center text-[var(--text-secondary)] hover:text-[var(--accent-primary)] transition-colors"
                                                data-answer-id="{{ $ans['id'] }}"
                                                data-comments="{{ json_encode($ans['comments'] ?? []) }}"
                                                data-answer-owner-username="{{ $ans['user']['username'] }}">
                                                <i class="fa-solid fa-comment-dots mr-2"></i>
                                                <span>{{ count($ans['comments'] ?? []) }}</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Comment input box -->
                                    <div class="comment-box hidden mt-4 w-full comment-animation">
                                        <textarea id="answer-comment-{{ $ans['id'] }}"
                                            class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-primary)]"
                                            rows="2" placeholder="Write your comment here!"></textarea>
                                        <button id="submit-comment-{{ $ans['id'] }}"
                                            data-answer-id="{{ $ans['id'] }}"
                                            class="mt-4 px-4 py-2 bg-[var(--accent-tertiary)] text-[var(--text-button)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                                            Submit Comment
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="absolute top-4 right-4">
                                @if ($isAnswerOwner)
                                    <div class="relative">
                                        <button id="answer-actions-toggle-{{ $ans['id'] }}"
                                            class="answer-actions-toggle w-8 h-8 flex items-center justify-center rounded-full text-[var(--text-muted)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)] transition-colors"
                                            title="More options" data-answer-id="{{ $ans['id'] }}">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div id="answer-actions-menu-{{ $ans['id'] }}"
                                            class="dropdown-menu absolute right-0 mt-2 w-48 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-lg shadow-xl z-10 hidden"
                                            style="opacity: 0; transform: translateX(10px);">
                                            @if (!$isVerified && $answerVoteCount == 0)
                                                <a href="{{ route('user.answers.edit', ['answerId' => $ans['id']]) }}"
                                                    class="flex items-center px-4 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--accent-tertiary)] hover:text-[var(--text-dark)]">
                                                    <i class="fa-solid fa-edit w-6 mr-2"></i>
                                                    Edit
                                                </a>
                                                <button data-answer-id="{{ $ans['id'] }}"
                                                    class="delete-answer-button flex items-center w-full px-4 py-2 text-sm text-[var(--accent-neg)] hover:bg-[var(--accent-neg)] hover:text-white">
                                                    <i class="fa-solid fa-trash w-6 mr-2"></i>
                                                    Delete
                                                </button>
                                            @else
                                                <p class="px-4 py-3 text-sm text-center text-[var(--text-muted)]">
                                                    Actions are disabled once the answer has been verified or has votes.
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div id="no-answers-block"
                    class="bg-[var(--bg-card)] rounded-lg shadow-lg border border-[var(--border-color)] relative overflow-hidden">
                    <div
                        class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.15)] to-[rgba(128,237,153,0.15)]">
                    </div>
                    <div
                        class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)]">
                    </div>

                    <div class="relative z-10 py-12 px-8 text-center">
                        <div
                            class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-full bg-[var(--bg-accent-subtle)]">
                            <i
                                class="fa-solid fa-lightbulb text-3xl bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-3">
                            No Answers Yet
                        </h3>
                        <p class="text-[var(--text-secondary)] text-lg leading-relaxed mb-6 max-w-md mx-auto">
                            Be the first to share your knowledge and help the community!
                        </p>
                        <a href="#answer-textArea" id="write-answer-placeholder-btn"
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-semibold rounded-lg transition-all duration-300 hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105">
                            <i class="fa-solid fa-pen-to-square mr-2"></i>
                            Write an Answer
                        </a>
                        <div class="mt-6 pt-6 border-t border-[var(--border-color)]">
                            <p class="text-sm text-[var(--text-muted)] flex items-center justify-center">
                                <i class="fa-solid fa-star mr-2 text-[var(--accent-tertiary)]"></i>
                                Your answer could be the solution someone is looking for
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div id="questionCommentsModal"
            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 opacity-0 pointer-events-none">
            <div
                class="modal-content bg-[var(--bg-secondary)] rounded-lg shadow-xl max-w-2xl w-full mx-auto flex flex-col relative max-h-[85vh]">
                <div
                    class="flex-shrink-0 flex justify-between items-center p-6 pb-3 border-b border-[var(--border-color)]">
                    <h3 class="text-xl font-semibold text-[var(--text-primary)]">
                        {{ $question['title'] }}
                        <span id="modal-question-comment-count"
                            class="text-sm text-[var(--text-muted)] ml-2">({{ $question['comment_count'] }})</span>
                    </h3>
                    <button id="close-question-comments-modal-btn"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-2xl">&times;</button>
                </div>

                <div class="overflow-y-auto flex-grow p-6 pt-2 space-y-3 flex flex-col" id="question-comments-list-modal">
                    {{-- Konten komentar tetap sama --}}
                    @if ($question['comment_count'] > 0)
                        @foreach ($question['comments'] as $comm)
                            <div class="comment bg-[var(--bg-card)] p-4 rounded-lg flex items-start">
                                <div class="flex-grow">
                                    <p class="text-[var(--text-primary)]">{!! nl2br(e($comm['comment'])) !!}</p>
                                    <a href="{{ route('viewUser', ['email' => $comm['user']['email'] ?? ($comm['email'] ?? '#')]) }}"
                                        class="hover:underline">
                                        <div class="mt-2 text-xs text-[var(--text-muted)] flex items-center">
                                            <img src="{{ $comm['image'] ?? (isset($comm['user']['image']) ? asset('storage/' . $comm['user']['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($comm['user']['username'] ?? ($comm['username'] ?? 'U')) . '&background=random&color=fff&size=128') }}"
                                                alt="{{ $comm['user']['username'] ?? ($comm['username'] ?? 'User') }}"
                                                class="w-5 h-5 rounded-full mr-2">
                                            <span>Posted by
                                                {{ $comm['user']['username'] ?? ($comm['username'] ?? 'User') }} -
                                                {{ \Carbon\Carbon::parse($comm['timestamp'])->diffForHumans() }}</span>

                                            <button
                                                class="open-report-modal-btn text-[var(--text-muted)] hover:text-[var(--accent-neg)] ml-3"
                                                data-reportable-id="{{ $comm['id'] }}" data-reportable-type="comment"
                                                title="Report Comment">
                                                <i class="fa-solid fa-flag text-xs"></i>
                                            </button>
                                        </div>
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
    </div>
    </div>
    <button id="show-answer-input-btn" title="Write an Answer"
        class="fixed bottom-8 right-8 z-50 h-16 w-16 rounded-full bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-200">
        <i class="fa-solid fa-pen-to-square text-2xl"></i>
    </button>

    @once
        <div id="answerCommentsModal"
            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 opacity-0 pointer-events-none"
            style="transition: opacity 0.3s ease-in-out;">
            <div class="modal-content bg-[var(--bg-secondary)] rounded-lg shadow-xl max-w-2xl w-full mx-auto flex flex-col relative max-h-[85vh]"
                style="transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out; transform: translateY(-20px) scale(0.95);">
                <div class="flex-shrink-0 flex justify-between items-center p-6 pb-3 border-b border-[var(--border-color)]">
                    <h3 id="answer-modal-title" class="text-xl font-semibold text-[var(--text-primary)]">
                        Comments on Answer
                    </h3>
                    <button id="close-answer-comments-modal-btn"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-2xl">&times;</button>
                </div>

                <div class="overflow-y-auto flex-grow p-6 pt-2 space-y-3 flex flex-col" id="answer-comments-list-modal">
                    {{-- Comments will be dynamically injected here --}}
                </div>

                <div class="flex-shrink-0 p-6 pt-4 border-t border-[var(--border-color)] bg-[var(--bg-secondary)]">
                    <textarea id="answer-comment-textarea"
                        class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-primary)]"
                        rows="3" placeholder="Write your comment here..."></textarea>
                    <button id="submit-answer-comment-btn"
                        class="mt-3 w-full px-4 py-2 bg-[var(--accent-tertiary)] text-[var(--text-dark)] rounded-lg transition-all duration-300 font-semibold hover:shadow-glow">
                        Submit Comment
                    </button>
                </div>
            </div>
        </div>
    @endonce


    @once
        <div id="reportModal"
            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div id="reportModalContent"
                class="modal-content bg-[var(--bg-secondary)] rounded-lg shadow-xl max-w-lg w-full mx-auto flex flex-col relative max-h-[90vh] transform scale-95 opacity-0 transition-all duration-300">
                <div class="flex-shrink-0 flex justify-between items-center p-5 border-b border-[var(--border-color)]">
                    <h3 class="text-xl font-semibold text-[var(--text-primary)] flex items-center">
                        <i class="fa-solid fa-flag text-[var(--accent-neg)] mr-3"></i>
                        Report Content
                    </h3>
                    <button id="closeReportModalBtn"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-2xl">&times;</button>
                </div>

                <div class="overflow-y-auto flex-grow p-5">
                    <form id="reportForm">
                        <input type="hidden" id="reportable_id" name="reportable_id">
                        <input type="hidden" id="reportable_type" name="reportable_type">

                        <p class="text-[var(--text-secondary)] mb-4">Please select a reason for reporting this content. Your
                            report
                            is anonymous.</p>

                        <div class="space-y-3" id="reportReasonsContainer">
                            @if (!empty($reportReasons))
                                @foreach ($reportReasons as $reason)
                                    <label
                                        class="flex items-center p-3 bg-[var(--bg-card)] rounded-lg cursor-pointer hover:bg-[var(--bg-card-hover)] border-2 border-transparent has-[:checked]:border-[var(--accent-primary)] transition-all">
                                        <input type="radio" name="report_reason_id" value="{{ $reason['id'] }}"
                                            data-reason-text="{{ $reason['title'] }}"
                                            class="h-4 w-4 text-[var(--accent-primary)] bg-[var(--bg-input)] border-[var(--border-color)] focus:ring-[var(--accent-primary)]">
                                        <span
                                            class="ml-3 text-[var(--text-primary)] font-medium">{{ $reason['title'] }}</span>
                                    </label>
                                @endforeach
                            @else
                                <p class="text-[var(--text-muted)]">Could not load report reasons.</p>
                            @endif
                        </div>

                        <div id="additionalNotesContainer" class="hidden mt-4">
                            <label for="additional_notes"
                                class="block mb-2 text-sm font-medium text-[var(--text-primary)]">Additional
                                Notes (Optional)</label>
                            <textarea id="additional_notes" name="additional_notes" rows="3"
                                class="w-full bg-[var(--bg-input)] rounded-lg p-3 text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-primary)]"
                                placeholder="Provide more details here..."></textarea>
                        </div>
                    </form>
                </div>

                <div class="flex-shrink-0 flex justify-end items-center p-5 border-t border-[var(--border-color)] space-x-3">
                    <button type="button" id="cancelReportBtn"
                        class="px-5 py-2.5 text-sm font-medium text-[var(--text-secondary)] bg-[var(--bg-card)] hover:bg-[var(--bg-card-hover)] rounded-lg border border-[var(--border-color)] transition-all">
                        Cancel
                    </button>
                    <button type="button" id="submitReportBtn"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-[var(--accent-neg)] hover:bg-red-700 rounded-lg transition-all flex items-center justify-center">
                        <span class="btn-text">Submit Report</span>
                        <i class="fa-solid fa-spinner fa-spin hidden ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    @endonce

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const API_BASE_URL = ("{{ env('API_URL', 'http://localhost:8001/api') }}" + '/').replace(/\/+$/, '/');
            const API_TOKEN = "{{ session('token') ?? '' }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";

            // report logic
            const reportModal = document.getElementById('reportModal');
            const reportModalContent = document.getElementById('reportModalContent');
            const closeReportModalBtn = document.getElementById('closeReportModalBtn');
            const cancelReportBtn = document.getElementById('cancelReportBtn');
            const submitReportBtn = document.getElementById('submitReportBtn');
            const reportForm = document.getElementById('reportForm');
            const reportableIdInput = document.getElementById('reportable_id');
            const reportableTypeInput = document.getElementById('reportable_type');
            const additionalNotesContainer = document.getElementById('additionalNotesContainer');
            const additionalNotesTextarea = document.getElementById('additional_notes');
            const reportReasonsContainer = document.getElementById('reportReasonsContainer');

            // --- Open Modal Handler ---
            // Using event delegation to catch clicks on buttons that might be added dynamically
            document.body.addEventListener('click', function(event) {
                const target = event.target.closest('.open-report-modal-btn');
                if (target) {
                    event.preventDefault();
                    const reportableId = target.dataset.reportableId;
                    const reportableType = target.dataset.reportableType;

                    if (reportableId && reportableType) {
                        reportableIdInput.value = reportableId;
                        reportableTypeInput.value = reportableType;
                        openReportModal();
                    } else {
                        console.error(
                            'Report button is missing data-reportable-id or data-reportable-type');
                        Toastify({
                            text: 'Cannot open report form. Missing content ID.',
                            duration: 3000,
                            style: {
                                background: "#e74c3c"
                            }
                        }).showToast();
                    }
                }
            });


            // --- Modal Open/Close Functions ---
            function openReportModal() {
                if (!reportModal) return;
                reportForm.reset(); // Clear previous selection
                additionalNotesContainer.classList.add('hidden'); // Hide notes field
                reportModal.classList.remove('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    reportModalContent.classList.remove('scale-95', 'opacity-0');
                }, 10);
            }

            function closeReportModal() {
                if (!reportModal) return;
                reportModalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    reportModal.classList.add('opacity-0', 'pointer-events-none');
                }, 300);
            }

            // --- Event Listeners for Modal ---
            if (reportModal) {
                closeReportModalBtn.addEventListener('click', closeReportModal);
                cancelReportBtn.addEventListener('click', closeReportModal);
                reportModal.addEventListener('click', (event) => {
                    if (event.target === reportModal) {
                        closeReportModal();
                    }
                });
            }

            if (reportReasonsContainer) {
                reportReasonsContainer.addEventListener('change', (event) => {
                    if (event.target.type === 'radio') {
                        const selectedReasonText = event.target.dataset.reasonText || '';
                        if (selectedReasonText.toLowerCase() === 'others') {
                            additionalNotesContainer.classList.remove('hidden');

                            setTimeout(() => {
                                additionalNotesTextarea.focus();
                            }, 100);

                        } else {
                            additionalNotesContainer.classList.add('hidden');
                            additionalNotesTextarea.value = ''; // Clear value when hidden
                        }
                    }
                });
            }

            // --- AJAX Submit Report ---
            if (submitReportBtn) {
                submitReportBtn.addEventListener('click', () => {
                    const reportableId = reportableIdInput.value;
                    const reportableType = reportableTypeInput.value;
                    const selectedReason = reportForm.querySelector(
                        'input[name="report_reason_id"]:checked');

                    if (!selectedReason) {
                        Toastify({
                            text: 'Please select a reason for the report.',
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#f39c12"
                            }
                        }).showToast();
                        return;
                    }

                    const formData = new FormData();
                    formData.append('reportable_id', reportableId);
                    formData.append('reportable_type', reportableType);
                    formData.append('report_reason_id', selectedReason.value);

                    // Only append additional notes if the container is visible
                    if (!additionalNotesContainer.classList.contains('hidden')) {
                        formData.append('additional_notes', additionalNotesTextarea.value);
                    }

                    // UI loading state
                    submitReportBtn.disabled = true;
                    submitReportBtn.querySelector('.btn-text').textContent = 'Submitting...';
                    submitReportBtn.querySelector('i').classList.remove('hidden');


                    fetch(`{{ route('submitReport') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN, // Pastikan CSRF_TOKEN sudah didefinisikan secara global
                                'Accept': 'application/json',
                            },
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toastify({
                                    text: data.message || 'Report submitted successfully!',
                                    duration: 3000,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                                    }
                                }).showToast();
                                closeReportModal();
                            } else {
                                Toastify({
                                    text: data.message || 'An error occurred.',
                                    duration: 4000, // Longer duration for errors
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#e74c3c"
                                    }
                                }).showToast();
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting report:', error);
                            Toastify({
                                text: 'A network error occurred. Please try again.',
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#e74c3c"
                                }
                            }).showToast();
                        })
                        .finally(() => {
                            // Restore button state
                            submitReportBtn.disabled = false;
                            submitReportBtn.querySelector('.btn-text').textContent = 'Submit Report';
                            submitReportBtn.querySelector('i').classList.add('hidden');
                        });
                });
            }

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
                                        Toastify({
                                            text: data.message ||
                                                'Your question has been deleted.',
                                            duration: 3000,
                                            close: true,
                                            gravity: "top",
                                            position: "right",
                                            style: {
                                                background: "linear-gradient(to right, #00b09b, #96c93d)"
                                            }
                                        }).showToast();

                                        setTimeout(() => {
                                            window.location.href =
                                                "{{ url('/') }}"
                                        }, 2000);
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
                                            'An Unexpected Error Occurred.',
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
                const previewContainer = imagePreviewsContainer.querySelector('.image-preview-container');
                previewContainer.innerHTML = ''; // Clear any existing previews

                if (files.length > 0) {
                    const file = files[0]; // Get the first file
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const imgPreview = document.createElement('div');
                        imgPreview.classList.add('image-preview', 'relative', 'group');
                        imgPreview.innerHTML = `
                            <img src="${e.target.result}" alt="Image Preview" class="rounded-lg shadow-lg">
                            <button type="button" class="remove-image-btn">
                                <i class="fa-solid fa-times"></i>
                            </button>
                            <div class="mt-2 p-2 bg-[var(--bg-secondary)] rounded-lg">
                                <span class="text-xs text-[var(--text-primary)] font-medium">${file.name}</span>
                                <div class="text-xs text-[var(--text-muted)] mt-1">
                                    ${(file.size / 1024 / 1024).toFixed(2)} MB
                                </div>
                            </div>
                        `;
                        previewContainer.appendChild(imgPreview);
                    };
                    reader.readAsDataURL(file);
                }

                imagePreviewsContainer.classList.remove('hidden');
            });

            const imagePreviewContainer = document.querySelector('.image-preview-container');

            if (imagePreviewContainer) {
                imagePreviewContainer.addEventListener('click', function(event) {
                    const removeButton = event.target.closest('.remove-image-btn');

                    if (removeButton) {
                        const previewContainer = document.querySelector('.image-preview-container');
                        const imagePreviewsContainer = document.querySelector('.image-previews');
                        const fileInput = document.getElementById("question-img");

                        removeButton.closest('.image-preview').remove();

                        fileInput.value = '';

                        if (previewContainer.children.length === 0) {
                            imagePreviewsContainer.classList.add('hidden');
                        }
                    }
                });
            }

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
            let currentAnswerCount = @json(count($question['answers'] ?? []));

            let jsHasAnswer = @json(!empty($question['answers']));
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
                if (!answerItemElement) {
                    // The element might have been deleted, so we can safely exit.
                    return;
                }

                const isOwner = answerItemElement.dataset.isOwner === 'true';
                const voteCount = parseInt(answerItemElement.dataset.voteCount, 10);
                const isVerified = answerItemElement.dataset.isVerified === 'true';

                // Find the dropdown menu and the main toggle button container
                const menuContainer = document.getElementById(`answer-actions-menu-${answerId}`);
                const toggleContainer = document.getElementById(`answer-actions-toggle-${answerId}`)?.parentElement
                    .parentElement;

                if (!menuContainer || !toggleContainer) {
                    // If the menu or its container doesn't exist, something is wrong, but we can exit.
                    // console.error(`Action menu or toggle container for answer ${answerId} not found.`);
                    return;
                }

                // Always show the three-dot button if the user is the owner
                if (isOwner) {
                    toggleContainer.classList.remove('hidden');
                } else {
                    toggleContainer.classList.add('hidden');
                    return; // Not the owner, so nothing more to do
                }

                // Now, determine the *content* of the dropdown menu
                if (!isVerified && voteCount === 0) {
                    // Conditions met: show Edit and Delete actions
                    const editUrl = "{{ route('user.answers.edit', ['answerId' => ':answerId']) }}".replace(
                        ':answerId', answerId);

                    menuContainer.innerHTML = `
                            <a href="${editUrl}"
                            class="flex items-center px-4 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--accent-tertiary)] hover:text-[var(--text-dark)]">
                                <i class="fa-solid fa-edit w-6 mr-2"></i>
                                Edit
                            </a>
                            <button data-answer-id="${answerId}"
                                    class="delete-answer-button flex items-center w-full px-4 py-2 text-sm text-[var(--accent-neg)] hover:bg-[var(--accent-neg)] hover:text-white">
                                <i class="fa-solid fa-trash w-6 mr-2"></i>
                                Delete
                            </button>
                        `;
                    // IMPORTANT: Find the newly created delete button and attach its listener
                    const newDeleteButton = menuContainer.querySelector('.delete-answer-button');
                    if (newDeleteButton) {
                        attachDeleteAnswerButtonListener(newDeleteButton);
                    }
                } else {
                    // Conditions not met: show the disabled message
                    menuContainer.innerHTML = `
                            <p class="px-4 py-3 text-sm text-center text-[var(--text-muted)]">
                                Actions are disabled once the answer has been verified or has votes.
                            </p>
                        `;
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
                                        Toastify({
                                            text: data.message ||
                                                'Your answer has been deleted.',
                                            duration: 3000,
                                            close: true,
                                            gravity: "top",
                                            position: "right",
                                            style: {
                                                background: "linear-gradient(to right, #00b09b, #96c93d)"
                                            }
                                        }).showToast();
                                        if (answerItemElement) {
                                            answerItemElement.style.transition =
                                                'opacity 0.5s ease, transform 0.5s ease';
                                            answerItemElement.style.opacity = '0';
                                            answerItemElement.style.transform = 'scale(0.9)';

                                            setTimeout(() => {
                                                const answerList = answerItemElement
                                                    .parentElement;
                                                answerItemElement.remove();

                                                if (answerList && answerList.children
                                                    .length === 0) {
                                                    const answerSection = document
                                                        .querySelector(
                                                            '.answer-section');
                                                    if (answerSection) {
                                                        answerList.remove();
                                                        answerSection
                                                            .insertAdjacentHTML(
                                                                'beforeend',
                                                                getNoAnswersHtml());

                                                        const newWriteBtn = document
                                                            .getElementById(
                                                                'write-answer-placeholder-btn'
                                                            );
                                                        if (newWriteBtn) {
                                                            newWriteBtn
                                                                .addEventListener(
                                                                    'click', (
                                                                        event) => {
                                                                        event
                                                                            .preventDefault();
                                                                        showAnswerInput
                                                                            ();
                                                                    });
                                                        }
                                                    }
                                                    // 
                                                    // to be fixed

                                                    // location.reload();
                                                }

                                                currentAnswerCount--;
                                                const answerCountTop = document
                                                    .querySelector(
                                                        '#answerCountAtas span');
                                                const answerHeader = document
                                                    .querySelector(
                                                        '.answer-section h2 span');
                                                if (answerCountTop) answerCountTop
                                                    .textContent = currentAnswerCount;
                                                if (answerHeader) answerHeader
                                                    .textContent =
                                                    `(${currentAnswerCount})`;

                                            }, 500);
                                        }
                                    } else {
                                        Toastify({
                                            text: data.message ||
                                                'Could not delete the answer.',
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
                                    console.error('Error deleting answer:', error);
                                    Toastify({
                                        text: error ||
                                            'An Unexpected Error Occurred.',
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
                        '<i class="fa-solid fa-spinner fa-spin"></i>';
                    submitButton.disabled = true;

                    if (answerText === '') {
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;

                        Toastify({
                            text: 'Please provide an answer!',
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#e74c3c"
                            }
                        }).showToast();

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
                                const imagePreviewsWrapper = document.querySelector(".image-previews");
                                const imagePreviewContentContainer = document.querySelector(
                                    '.image-preview-container');
                                if (imagePreviewContentContainer) {
                                    imagePreviewContentContainer.innerHTML = '';
                                }
                                if (imagePreviewsWrapper && !imagePreviewsWrapper.classList.contains(
                                        'hidden')) {
                                    imagePreviewsWrapper.classList.add('hidden');
                                }

                                const answerInputSection = document.getElementById(
                                    'answer-input-section');
                                const showAnswerBtn = document.getElementById('show-answer-input-btn');
                                if (answerInputSection && showAnswerBtn) {
                                    answerInputSection.classList.add('hidden');
                                    showAnswerBtn.classList.remove('active');
                                    showAnswerBtn.querySelector('i').className =
                                        'fa-solid fa-pen-to-square text-2xl';
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
                       <div class="relative bg-[var(--bg-secondary)] rounded-lg p-6 shadow-lg"
                 id="answer-item-${data.answer.id}"   увагу MODIFIED: Tambahkan ID dan data attributes
                 data-answer-id="${data.answer.id}"
                 data-is-owner="${isOwnerForNewAnswer ? 'true' : 'false'}"
                 data-is-verified="${isVerifiedForNewAnswer ? 'true' : 'false'}"
                 data-vote-count="${voteCountForNewAnswer}">

                <div class="flex items-start">
                            <div class="interaction-section flex flex-col items-center mr-6">
                                <button class="upVoteAnswer vote-btn mb-2 text-[var(--text-primary)] hover:text-[#633F92] focus:outline-none thumbs-up"
                                        data-answer-id="${data.answer.id}">
                                    <i class="text-2xl text-[#23BF7F] fa-solid fa-thumbs-up"></i>
                                </button>
                                <span class="thumbs-up-count text-lg font-semibold text-[var(--text-secondary)] my-1">0</span>
                                <button class="downVoteAnswer vote-btn mt-2 text-[var(--text-primary)] hover:text-gray-700 focus:outline-none thumbs-down"
                                        data-answer-id="${data.answer.id}">
                                    <i class="text-2xl text-[#FE0081] fa-solid fa-thumbs-down"></i>
                                </button>
                                ${verifyBlockForNewAnswer}
                            </div>

                            <div class="flex flex-col flex-grow">
                                <div class="prose max-w-none text-[var(--text-primary)]">
                                    <p>${escapeHtml(data.answer.answer)}</p>
                                </div>
                                ${imageHtml}
                                
                                <div class="mt-4 flex justify-between items-center">
                                    <a href="/viewUser/${@json($username ?? null)}">
                                    <div class="flex items-center text-sm text-[var(--text-muted)]">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.answer.username)}&background=random" 
                                             alt="User" class="w-6 h-6 rounded-full mr-2">
                                        <span class="hover:underline">Answered by ${escapeHtml(data.answer.username)} - ${timeAgo}</span>
                                    </div>
                                    </a>
                                    <button class="comment-btn flex items-center text-[var(--text-secondary)] hover:text-[var(--accent-primary)] transition-colors">
                                        <i class="fa-solid fa-comment-dots mr-2"></i>
                                        <span>{{ count($ans['comments'] ?? []) }}</span>
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
                        <div class="absolute top-4 right-4">
                            <div class="relative">
                                <button id="answer-actions-toggle-${data.answer.id}"
                                        class="answer-actions-toggle w-8 h-8 flex items-center justify-center rounded-full text-[var(--text-muted)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)] transition-colors"
                                        title="More options"
                                        data-answer-id="${data.answer.id}">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div id="answer-actions-menu-${data.answer.id}"
                                    class="dropdown-menu absolute right-0 mt-2 w-48 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-lg shadow-xl z-10 hidden"
                                    style="opacity: 0; transform: translateX(10px);">
                                    </div>
                            </div>
                        </div>
                    `;

                                let answerList = document.getElementById('answerList');
                                const noAnswersBlock = document.getElementById('no-answers-block');
                                if (noAnswersBlock) {
                                    answerList = document.createElement('div');
                                    answerList.id = 'answerList';
                                    answerList.className = 'space-y-6';

                                    noAnswersBlock.parentNode.replaceChild(answerList, noAnswersBlock);
                                }

                                if (answerList) {
                                    const newAnswerElement = document.createElement('div');
                                    newAnswerElement.innerHTML = htmlContent.trim();
                                    newAnswerElement.firstElementChild.style.opacity = '0';
                                    newAnswerElement.firstElementChild.style.transform =
                                        'translateY(20px)';
                                    answerList.appendChild(newAnswerElement.firstElementChild);

                                    setTimeout(() => {
                                        const addedElement = document.getElementById(
                                            `answer-item-${data.answer.id}`);
                                        if (addedElement) {
                                            addedElement.style.transition =
                                                'opacity 0.5s ease, transform 0.5s ease';
                                            addedElement.style.opacity = '1';
                                            addedElement.style.transform = 'translateY(0)';
                                        }
                                    }, 50);
                                    const newToggleBtn = document.getElementById(
                                        `answer-actions-toggle-${data.answer.id}`);
                                    if (newToggleBtn) {
                                        newToggleBtn.addEventListener('click', (event) => {
                                            event.stopPropagation();
                                            const answerId = newToggleBtn.dataset.answerId;
                                            const answerMenu = document.getElementById(
                                                `answer-actions-menu-${answerId}`);

                                            if (answerMenu) {
                                                document.querySelectorAll('.dropdown-menu')
                                                    .forEach(menu => {
                                                        if (menu.id !== answerMenu.id) {
                                                            menu.classList.add('hidden');
                                                            menu.style.opacity = '0';
                                                            menu.style.transform =
                                                                'translateY(-10px)';
                                                        }
                                                    });

                                                const isHidden = answerMenu.classList.contains(
                                                    'hidden');
                                                if (isHidden) {
                                                    answerMenu.classList.remove('hidden');
                                                    setTimeout(() => {
                                                        answerMenu.style.opacity = '1';
                                                        answerMenu.style.transform =
                                                            'translateY(0)';
                                                    }, 10);
                                                } else {
                                                    answerMenu.style.opacity = '0';
                                                    answerMenu.style.transform =
                                                        'translateY(-10px)';
                                                    setTimeout(() => {
                                                        answerMenu.classList.add(
                                                            'hidden');
                                                    }, 200);
                                                }
                                            }
                                        });
                                    }

                                    // Setelah HTML jawaban baru ditambahkan:
                                    // 1. Pasang listener untuk tombol vote, comment, dll. pada jawaban baru
                                    attachAnswerEventListeners(data.answer.id);
                                    updateAnswerActionButtonsVisibility(data.answer.id);

                                    // 2. Pasang listener untuk tombol verifikasi BARU
                                    const addedElement = document.getElementById(
                                        `answer-item-${data.answer.id}`);
                                    if (addedElement) {
                                        const newVerifyToggleButtons = addedElement.querySelectorAll(
                                            '.verify-toggle-button');
                                        newVerifyToggleButtons.forEach(btn => {
                                            attachVerifyButtonListener(btn);
                                        });
                                    }
                                    const answerHeader = document.querySelector(
                                        '.answer-section h2 span');
                                    if (answerHeader) {
                                        answerHeader.textContent = `(${currentAnswerCount})`;
                                    }

                                    updateAnswerActionButtonsVisibility(data.answer.id);
                                }

                                Toastify({
                                    text: 'Your answer has been successfully submitted.',
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                                    }
                                }).showToast();

                                const answerCountTop = document.querySelector('#answerCountAtas span');
                                const answerCountHeader = document.querySelector(
                                    '.answer-section h2 span');

                                if (answerCountTop && answerCountHeader) {
                                    const currentCount = parseInt(answerCountTop.textContent.trim(),
                                        10);
                                    const newCount = currentCount + 1;

                                    answerCountTop.textContent = newCount;
                                    answerCountHeader.textContent =
                                        `(${newCount})`; // Note the parentheses
                                }
                            } else {
                                Toastify({
                                    text: data.message || 'Something went wrong',
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
                            Toastify({
                                text: 'Please write a comment!',
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#e74c3c"
                                }
                            }).showToast();
                        } else {
                            const formData = new FormData();
                            formData.append('comment', commentText);
                            formData.append('commentable_id', answerId);
                            formData.append('commentable_type', 'answer');

                            fetch(`{{ route('comment.submit') }}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    },
                                    body: formData,
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Toastify({
                                            text: 'Your comment has been successfully posted.',
                                            duration: 3000,
                                            close: true,
                                            gravity: "top",
                                            position: "right",
                                            style: {
                                                background: "linear-gradient(to right, #00b09b, #96c93d)"
                                            }
                                        }).showToast();

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
                                        <a href="/viewUser/${@json($username ?? null)}">
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
                        </a>`;

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
                                        Toastify({
                                            text: data.message ||
                                                'An Unexpected Error Occurred.',
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
                                    Toastify({
                                        text: error || 'An Unexpected Error Occurred.',
                                        duration: 3000,
                                        close: true,
                                        gravity: "top",
                                        position: "right",
                                        style: {
                                            background: "#e74c3c"
                                        }
                                    }).showToast();
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
                    Toastify({
                        text: 'Please write a comment!',
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
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
                                const noCommentsModal = document.getElementById(
                                    'no-question-comments-modal');
                                if (noCommentsModal) {
                                    noCommentsModal.remove();
                                }
                                let commentList = document.getElementById(
                                    'question-comments-list-modal');
                                const newComment = data.comment;
                                const timeAgo = formatTimeAgo(new Date(data.comment.timestamp));

                                const userImage = newComment.image ?
                                    `/storage/${newComment.image}` :
                                    `https://ui-avatars.com/api/?name=${encodeURIComponent(newComment.username)}&background=random&color=fff&size=128`;

                                const htmlContent = `
                            <div class="comment bg-[var(--bg-card)] p-4 rounded-lg flex items-start" style="opacity: 0; transform: translateY(20px);">
            <div class="flex-grow">
                <p class="text-[var(--text-primary)]">${escapeHtml(newComment.comment)}</p>
                <a href="/viewUser/${newComment.email}" class="hover:underline">
                    <div class="mt-2 text-xs text-[var(--text-muted)] flex items-center">
                        <img src="${userImage}" alt="${newComment.username}" class="w-5 h-5 rounded-full mr-2">
                        <span>Posted by ${escapeHtml(newComment.username)} - ${timeAgo}</span>
                    </div>
                </a>
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
                                const newCommentElement = commentList.lastElementChild;
                                setTimeout(() => {
                                    newCommentElement.style.transition = 'all 0.3s ease';
                                    newCommentElement.style.opacity = '1';
                                    newCommentElement.style.transform = 'translateY(0)';
                                }, 50);

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

                                Toastify({
                                    text: 'Your comment has been successfully posted.',
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                                    }
                                }).showToast();

                            } else {
                                Toastify({
                                    text: data.message ||
                                        'An Unexpected Error Occurred.',
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
                        .catch(error => { // Handle any errors that occur during the fetch
                            Toastify({
                                text: error || 'An Unexpected Error Occurred.',
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#e74c3c"
                                }
                            }).showToast();
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
                        Toastify({
                            text: 'Please write a comment!',
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#e74c3c"
                            }
                        }).showToast();
                    } else {
                        const formData = new FormData();
                        formData.append('comment', commentText);
                        formData.append('commentable_id', answerId);
                        formData.append('commentable_type', 'answer');

                        fetch(`{{ route('comment.submit') }}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                },
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {

                                    Toastify({
                                        text: 'Your comment has been successfully posted.',
                                        duration: 3000,
                                        close: true,
                                        gravity: "top",
                                        position: "right",
                                        style: {
                                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                                        }
                                    }).showToast();

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
                                    <a href="/viewUser/${@json($username ?? null)}">
                                <div class="flex items-start">
                                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.comment.username)}&background=random" 
                                         alt="${data.comment.username}"
                                         class="w-6 h-6 rounded-full mr-3 mt-1">
                                    
                                    <div class="flex-grow">
                                        <div class="flex items-center mb-1">
                                            <span class="text-sm hover:underline font-medium text-[var(--text-primary)]">
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
                                    </a>
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
                                    Toastify({
                                        text: data.message ||
                                            'An Unexpected Error Occurred.',
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
                                Toastify({
                                    text: error || 'An Unexpected Error Occurred.',
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#e74c3c"
                                    }
                                }).showToast();
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
                                        Toastify({
                                            text: `The answer has been ${newVerifiedStatus === 1 ? 'verified' : 'un-verified'}.`,
                                            duration: 3000,
                                            close: true,
                                            gravity: "top",
                                            position: "right",
                                            style: {
                                                background: "linear-gradient(to right, #00b09b, #96c93d)"
                                            }
                                        }).showToast();

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
                                        Toastify({
                                            text: data.message ||
                                                'Could not update verification status.',
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
                                    console.error('Error:', error);
                                    let errorMessage =
                                        'An error occurred while updating the answer.';
                                    if (error && error.message) {
                                        errorMessage = error.message;
                                    } else if (typeof error === 'string') {
                                        errorMessage = error;
                                    }
                                    Toastify({
                                        text: errorMessage ||
                                            'An Unexpected Error Occurred.',
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
                            Toastify({
                                text: data.message ||
                                    'An Unexpected Error Occurred.',
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
                        } else {
                            Toastify({
                                text: data.message ||
                                    'An Unexpected Error Occurred.',
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

        const questionActionsToggle = document.getElementById('question-actions-toggle');
        const questionActionsMenu = document.getElementById('question-actions-menu');

        if (questionActionsToggle && questionActionsMenu) {
            questionActionsToggle.addEventListener('click', (event) => {
                event.stopPropagation();
                const isHidden = questionActionsMenu.classList.contains('hidden');
                if (isHidden) {
                    questionActionsMenu.classList.remove('hidden');
                    setTimeout(() => {
                        questionActionsMenu.style.opacity = '1';
                        questionActionsMenu.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    questionActionsMenu.style.opacity = '0';
                    questionActionsMenu.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        questionActionsMenu.classList.add('hidden');
                    }, 200);
                }
            });

            window.addEventListener('click', (event) => {
                if (!questionActionsMenu.contains(event.target) && !questionActionsToggle.contains(event.target)) {
                    if (!questionActionsMenu.classList.contains('hidden')) {
                        questionActionsMenu.style.opacity = '0';
                        questionActionsMenu.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            questionActionsMenu.classList.add('hidden');
                        }, 200);
                    }
                }
            });

            const editLink = questionActionsMenu.querySelector('.edit-question-link');
            if (editLink) {
                editLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const questionId = '{{ $question['id'] }}';
                    window.location.href = `{{ url('/ask') }}/${questionId}`;
                });
            }
        }

        document.querySelectorAll('.answer-actions-toggle').forEach(toggleBtn => {
            toggleBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                const answerId = toggleBtn.dataset.answerId;
                const answerMenu = document.getElementById(`answer-actions-menu-${answerId}`);

                if (answerMenu) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu.id !== `answer-actions-menu-${answerId}`) {
                            menu.classList.add('hidden');
                            menu.style.opacity = '0';
                            menu.style.transform = 'translateY(-10px)';
                        }
                    });

                    const isHidden = answerMenu.classList.contains('hidden');
                    if (isHidden) {
                        answerMenu.classList.remove('hidden');
                        setTimeout(() => {
                            answerMenu.style.opacity = '1';
                            answerMenu.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        answerMenu.style.opacity = '0';
                        answerMenu.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            answerMenu.classList.add('hidden');
                        }, 200);
                    }
                }
            });
        });

        window.addEventListener('click', (event) => {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                const toggleId = menu.id.replace('menu', 'toggle');
                const toggleButton = document.getElementById(toggleId);
                if (toggleButton && !menu.contains(event.target) && !toggleButton.contains(event.target)) {
                    if (!menu.classList.contains('hidden')) {
                        menu.style.opacity = '0';
                        menu.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            menu.classList.add('hidden');
                        }, 200);
                    }
                }
            });
        });

        function getNoAnswersHtml() {
            return `
            <div id="no-answers-block" class="bg-[var(--bg-card)] rounded-lg shadow-lg border border-[var(--border-color)] relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.15)] to-[rgba(128,237,153,0.15)]"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)]"></div>
                <div class="relative z-10 py-12 px-8 text-center">
                    <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-full bg-[var(--bg-accent-subtle)]">
                        <i class="fa-solid fa-lightbulb text-3xl bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-3">
                        No Answers Yet
                    </h3>
                    <p class="text-[var(--text-secondary)] text-lg leading-relaxed mb-6 max-w-md mx-auto">
                        Be the first to share your knowledge and help the community!
                    </p>
                    <a href="#answer-textArea" id="write-answer-placeholder-btn" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-semibold rounded-lg transition-all duration-300 hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105">
                        <i class="fa-solid fa-pen-to-square mr-2"></i>
                        Write an Answer
                    </a>
                    <div class="mt-6 pt-6 border-t border-[var(--border-color)]">
                        <p class="text-sm text-[var(--text-muted)] flex items-center justify-center">
                            <i class="fa-solid fa-star mr-2 text-[var(--accent-tertiary)]"></i>
                            Your answer could be the solution someone is looking for
                        </p>
                    </div>
                </div>
            </div>`;
        }

        const questionCommentsModal = document.getElementById('questionCommentsModal');
        const openQuestionCommentsModalBtn = document.getElementById('open-question-comments-modal-btn');
        const closeQuestionCommentsModalBtn = document.getElementById('close-question-comments-modal-btn');
        const questionCommentTextarea = document.getElementById('question-comment-textarea');

        const answerCommentsModal = document.getElementById('answerCommentsModal');
        const closeAnswerCommentsModalBtn = document.getElementById('close-answer-comments-modal-btn');
        const answerCommentsListModal = document.getElementById('answer-comments-list-modal');
        const submitAnswerCommentBtn = document.getElementById('submit-answer-comment-btn');
        const answerCommentTextarea = document.getElementById('answer-comment-textarea');
        let currentAnswerId = null;

        const showAnswerBtn = document.getElementById('show-answer-input-btn');
        const answerInputSection = document.getElementById('answer-input-section');
        const answerTextArea = document.getElementById('answer-textArea');
        const writeAnswerPlaceholderBtn = document.getElementById('write-answer-placeholder-btn');

        const showAnswerInput = () => {
            if (answerInputSection.classList.contains('hidden')) {
                answerInputSection.classList.remove('hidden');
                showAnswerBtn.classList.add('active');
                showAnswerBtn.querySelector('i').className = 'fa-solid fa-times text-2xl';

                setTimeout(() => {
                    answerInputSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    setTimeout(() => {
                        answerTextArea.focus();
                    }, 500);
                }, 100);
            }
        };

        if (writeAnswerPlaceholderBtn) {
            writeAnswerPlaceholderBtn.addEventListener('click', (event) => {
                event.preventDefault();
                showAnswerInput();
            });
        }

        if (showAnswerBtn && answerInputSection && answerTextArea) {
            showAnswerBtn.addEventListener('click', () => {
                const isHidden = answerInputSection.classList.contains('hidden');

                if (isHidden) {
                    answerInputSection.classList.remove('hidden');
                    showAnswerBtn.classList.add('active');

                    showAnswerBtn.querySelector('i').className = 'fa-solid fa-times text-2xl';

                    setTimeout(() => {
                        answerInputSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        setTimeout(() => {
                            answerTextArea.focus();
                        }, 500);
                    }, 100);

                } else {
                    answerInputSection.classList.add('hidden');
                    showAnswerBtn.classList.remove('active');

                    showAnswerBtn.querySelector('i').className = 'fa-solid fa-pen-to-square text-2xl';
                }
            });

            answerTextArea.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('submitAnswer-btn').click();
                }
            });
        }

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
            questionCommentsModal.addEventListener('click', (event) => {
                if (event.target === questionCommentsModal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && questionCommentsModal.classList.contains('opacity-100')) {
                    closeModal();
                }
            });
        }

        function openAnswerModal() {
            if (answerCommentsModal) {
                answerCommentsModal.classList.remove('opacity-0', 'pointer-events-none');
                answerCommentsModal.classList.add('opacity-100', 'pointer-events-auto');
                answerCommentsModal.querySelector('.modal-content').style.opacity = '1';
                answerCommentsModal.querySelector('.modal-content').style.transform = 'translateY(0) scale(1)';
                answerCommentTextarea.focus();
            }
        }

        function closeAnswerModal() {
            if (answerCommentsModal) {
                answerCommentsModal.querySelector('.modal-content').style.opacity = '0';
                answerCommentsModal.querySelector('.modal-content').style.transform = 'translateY(-20px) scale(0.95)';
                setTimeout(() => {
                    answerCommentsModal.classList.add('opacity-0', 'pointer-events-none');
                    answerCommentsModal.classList.remove('opacity-100', 'pointer-events-auto');
                }, 300);
            }
        }

        document.querySelectorAll('.open-answer-comments-modal-btn').forEach(button => {
            button.addEventListener('click', function() {
                currentAnswerId = this.dataset.answerId;
                const comments = JSON.parse(this.dataset.comments);
                const answerOwnerUsername = this.dataset.answerOwnerUsername;

                document.getElementById('answer-modal-title').innerHTML =
                    `Comments on ${answerOwnerUsername}'s Answer <span class="text-sm text-[var(--text-muted)] ml-2">(${comments.length})</span>`;
                answerCommentsListModal.innerHTML = ''; // Clear previous comments

                if (comments.length > 0) {
                    comments.forEach(comment => {
                        const commentElement = document.createElement('div');
                        commentElement.className =
                            'comment bg-[var(--bg-card)] p-4 rounded-lg flex items-start';

                        // Pastikan Anda memiliki data yang diperlukan (id, comment, user_email, username, timestamp)
                        // Di sini saya mengasumsikan strukturnya dari kode Anda
                        const commentId = comment.id; // ASUMSI: pastikan 'id' ada di data comment
                        const userEmail = comment.user_email || comment.user.email; // Sesuaikan
                        const username = comment.username || comment.user.username; // Sesuaikan
                        const timeAgo = formatTimeAgo(new Date(comment
                            .timestamp)); // Gunakan fungsi formatTimeAgo yang sudah ada

                        commentElement.innerHTML = `
                    <div class="flex-grow">
                        <p class="text-[var(--text-primary)]">${comment.comment.replace(/\n/g, '<br>')}</p>
                        <div class="mt-2 text-xs text-[var(--text-muted)] flex items-center">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(username)}&background=random&color=fff&size=128"
                                alt="${username}" class="w-5 h-5 rounded-full mr-2">
                            <a href="/viewUser/${userEmail}" class="hover:underline">
                                <span>Posted by ${username} - ${timeAgo}</span>
                            </a>
                            
                            <button class="open-report-modal-btn text-[var(--text-muted)] hover:text-[var(--accent-neg)] ml-3"
                                    data-reportable-id="${commentId}" 
                                    data-reportable-type="comment" 
                                    title="Report Comment">
                                <i class="fa-solid fa-flag text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
                        answerCommentsListModal.appendChild(commentElement);
                    });
                } else {
                    answerCommentsListModal.innerHTML = `
                            <div class="bg-[var(--bg-card)] rounded-lg p-6 text-center">
                                <p class="text-[var(--text-primary)] mb-2">There are no comments yet</p>
                                <p class="text-[var(--text-muted)] text-sm">Be the first to share your thoughts!</p>
                            </div>
                        `;
                }

                openAnswerModal();
            });
        });

        if (closeAnswerCommentsModalBtn) {
            closeAnswerCommentsModalBtn.addEventListener('click', closeAnswerModal);
        }

        if (answerCommentsModal) {
            answerCommentsModal.addEventListener('click', (event) => {
                if (event.target === answerCommentsModal) {
                    closeAnswerModal();
                }
            });
        }

        if (submitAnswerCommentBtn) {
            submitAnswerCommentBtn.addEventListener('click', () => {
                const commentText = answerCommentTextarea.value.trim();
                if (commentText === '' || !currentAnswerId) {
                    Toastify({
                        text: 'Please write a comment!',
                        duration: 3000,
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                    return;
                }

                const formData = new FormData();
                formData.append('comment', commentText);
                formData.append('commentable_id', currentAnswerId);
                formData.append('commentable_type', 'answer');

                fetch(`{{ route('comment.submit') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toastify({
                                text: 'Your comment has been successfully posted.',
                                duration: 3000,
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                                }
                            }).showToast();

                            location.reload();
                            closeAnswerModal();

                        } else {
                            Toastify({
                                text: data.message || 'An unexpected error occurred.',
                                duration: 3000,
                                style: {
                                    background: "#e74c3c"
                                }
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toastify({
                            text: 'A network error occurred. Please try again.',
                            duration: 3000,
                            style: {
                                background: "#e74c3c"
                            }
                        }).showToast();
                    });
            });
        }

        document.querySelectorAll('.comment-box.hidden.mt-4.w-full.comment-animation').forEach(el => {
            el.style.display = 'none';
        });
    </script>

@endsection
