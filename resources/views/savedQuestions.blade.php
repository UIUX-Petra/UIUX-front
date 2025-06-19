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

        .question-card:hover .save-question-btn {
            opacity: 1;
        }
    </style>

    @include('partials.nav')
    @if (session()->has('Error'))
        <script>
            Toastify({
                text: "{{ session('Error') }}" || "An unexpected error occurred from the server.",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "#e74c3c"
                }
            }).showToast();
        </script>
    @endif
    <div
        class="w-full bg-transparent rounded-lg p-6 px-8 max-w-5xl justify-start mt-6 mb-6 flex items-start space-x-5 popular-container backdrop-blur-sm relative overflow-hidden">
        <div class="text-3xl relative p-4 rounded-full bg-[var(--bg-primary)] z-10">
            <i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>
        </div>

        <div class="flex flex-col z-10">
            <h1 class="cal-sans-regular popular-title lg:text-3xl text-2xl mb-2 font-bold">
                Saved Questions
            </h1>
            <p class="text-[var(--text-secondary)] text-lg pl-0.5 font-regular max-w-xl">
                Questions saved by you
            </p>
        </div>
    </div>
    <div class="justify-start items-start max-w-8xl px-4 flex space-x-6">
        <div class="w-full bg-transparent rounded-lg p-6 shadow-lg max-w-3xl justify-start items-start">

            @if (isset($questions) && count($questions) > 0)
                @foreach ($questions as $question)
                    <div id="question-card-{{ $question['id'] }}" {{-- Ensure IDs are unique if $question['id'] could clash --}}
                        class="question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[var(--accent-tertiary)] relative overflow-hidden"
                        data-url="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}"
                        style="cursor: pointer;">
                        <div class="absolute inset-0 bg-pattern opacity-5"></div>

                        <button type="button" {{-- Changed from type="submit" --}}
                            class="save-question-btn absolute top-3 right-3 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-hover)] bg-[var(--bg-card-hover)]"
                            data-question-title="{{ $question['title'] }}" data-question-id="{{ $question['id'] }}"
                            title="Unsave Question">
                            <i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>
                        </button>

                        <div
                            class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]">
                            <div class="stats-item flex flex-row items-center space-x-2">
                                <span class="text-sm font-medium">{{ $question['vote'] ?? 0 }}</span>
                                <i class="text-sm fa-regular fa-thumbs-up"></i>
                            </div>
                            <div class="stats-item flex flex-row items-center space-x-2">
                                <span class="text-sm font-medium">{{ $question['view'] ?? 0 }}</span>
                                <i class="text-sm fa-solid fa-eye"></i>
                            </div>
                            <div class="stats-item flex flex-row items-center space-x-2">
                                <span
                                    class="text-sm font-medium">{{ $question['comment_count'] ?? ($question['answer_count'] ?? count($question['answer'] ?? [])) }}</span>
                                {{-- Using more common keys --}}
                                <i class="text-sm fa-regular fa-comment"></i>
                            </div>
                        </div>

                        <div class="flex-1 pt-0 mr-4 z-10">
                            <h2
                                class="text-xl font-medium text-[var(--text-highlight)] question-title transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2">
                                {{ $question['title'] }}
                            </h2>

                            <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                                {{ \Str::limit(strip_tags($question['question']), 150) }}</p>

                            @if (!empty($question['group_question']) && is_array($question['group_question']))
                                <div class="flex mt-2 flex-wrap gap-1 items-center tags-wrapper"
                                    data-question-id="{{ $question['id'] }}">
                                    @php
                                        $tags = $question['group_question'];
                                        $totalTags = count($tags);
                                        $displayLimit = 3;
                                    @endphp

                                    @foreach ($tags as $index => $tag)
                                        @if (isset($tag['subject']['name']))
                                            <a href="{{ route('home', ['filter_tag' => $tag['subject']['name'], 'sort_by' => 'latest', 'page' => 1]) }}"
                                                class="question-tag-link @if ($index >= $displayLimit) hidden extra-tag-{{ $question['id'] }} @endif">
                                                <span
                                                    class="hover:border-[var(--accent-secondary)] lowercase font-semibold hover:border-2 text-xs px-2 py-1 rounded-10 bg-[var(--bg-light)] text-[var(--text-tag)]">
                                                    {{ $tag['subject']['name'] }}
                                                </span>
                                            </a>
                                        @endif
                                    @endforeach

                                    @if ($totalTags > $displayLimit)
                                        <span
                                            class="text-xs text-[var(--accent-secondary)] cursor-pointer hover:underline more-tags-button"
                                            data-question-id="{{ $question['id'] }}"
                                            data-initial-text="+ {{ $totalTags - $displayLimit }} more">
                                            + {{ $totalTags - $displayLimit }} more
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <i class="fa-regular fa-folder-open text-4xl text-[var(--text-muted)] mb-3"></i>
                    <p class="text-lg text-[var(--text-muted)]">You haven't saved any questions yet.</p>
                </div>
            @endif
        </div>

        {{-- Sidebar for "Ask Question" etc. --}}
        <div class="w-72 mt-6 ml-6 hidden md:flex sticky top-24 h-fit">
            <div
                class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden">
                <div
                    class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.15)] to-[rgba(128,237,153,0.15)]">
                </div>
                <div
                    class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)]">
                </div>
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
                            <li class="flex items-center text-sm">
                                <i class="fa-solid fa-fire-flame-curved mr-2 text-amber-500"></i>
                                <a href="{{ route('home') }}" {{-- Updated link --}}
                                    class="text-[var(--text-secondary)] hover:text-[var(--accent-secondary)] transition-colors">Popular
                                    Questions</a>
                            </li>
                            <li class="flex items-center text-sm">
                                <i class="fa-solid fa-star mr-2 text-yellow-500"></i>
                                <a href="{{ route('home', ['sort_by' => 'unanswered']) }}" {{-- Updated link for unanswered --}}
                                    class="text-[var(--text-secondary)] hover:text-[var(--accent-secondary)] transition-colors">Unanswered
                                    Questions</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function initClickableQuestionCards() {
            document.querySelectorAll('.question-card').forEach(card => {
                if (card.dataset.clickableInitialized === 'true') return;
                card.addEventListener('click', function(event) {
                    if (event.target.closest('.save-question-btn') ||
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
                    const isCurrentlyHidden = extraTags.length > 0 && extraTags[0].classList.contains(
                        'hidden');
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
                    if (index % 3 === 0) icon.style.color = isLightMode ?
                        'var(--stats-icon-color-1-light, #10b981)' : 'var(--stats-icon-color-1-dark, #23BF7F)';
                    else if (index % 3 === 1) icon.style.color = isLightMode ?
                        'var(--stats-icon-color-2-light, #f59e0b)' : 'var(--stats-icon-color-2-dark, #ffd249)';
                    else icon.style.color = isLightMode ? 'var(--stats-icon-color-3-light, #3b82f6)' :
                        'var(--stats-icon-color-3-dark, #909ed5)';
                });
            }
        }

        function updateSavedIcons() {
            const savedIcons = document.querySelectorAll('.save-question-btn i.fa-solid.fa-bookmark');
            savedIcons.forEach(icon => {
                icon.style.color = 'var(--accent-secondary)';
            });
            const unsavedIcons = document.querySelectorAll('.save-question-btn i.fa-regular.fa-bookmark');
            unsavedIcons.forEach(icon => {});
        }

        function initSaveButtons() {
            document.querySelectorAll('.save-question-btn').forEach(button => {
                if (button.dataset.saveBtnInitialized === 'true') return;
                button.removeAttribute('onclick');

                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const icon = this.querySelector('i');
                    if (icon && icon.classList.contains('fa-solid') && icon.classList.contains(
                            'fa-bookmark')) {
                        unsaveQuestion(this);
                    } else {
                        saveQuestion(this);
                    }
                });
                button.dataset.saveBtnInitialized = 'true';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initSaveButtons();
            initClickableQuestionCards();
            initTagToggles();
            updateIconColors();
            updateSavedIcons();

            if (typeof window.pageThemeObserver === 'undefined' && typeof MutationObserver !== 'undefined') {
                window.pageThemeObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class' && mutation.target === document
                            .documentElement) {
                            updateIconColors();
                            updateSavedIcons();
                        }
                    });
                });
                window.pageThemeObserver.observe(document.documentElement, {
                    attributes: true
                });
            }
        });

        function unsaveQuestion(btn) {
            const questionId = btn.getAttribute('data-question-id');
            const questionCard = document.getElementById(`question-card-${questionId}`); // Get the card
            let formData = new FormData();
            formData.append("question_id", questionId);

            let loadingToast = Toastify({
                close: false,
                gravity: "top",
                position: "right",
                duration: -1,
                text: "Unsaving...",
                style: {
                    background: "#444"
                }
            });
            loadingToast.showToast();

            fetch("{{ route('unsaveQuestion') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            }).then(response => response.json()).then(res => {
                loadingToast.hideToast();
                if (res.success) {
                    Toastify({
                        text: res.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();

                    if (questionCard) {
                        questionCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        questionCard.style.opacity = '0';
                        questionCard.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            questionCard.remove();
                            // Check if no more questions are left
                            const remainingCards = document.querySelectorAll('.question-card');
                            if (remainingCards.length === 0) {
                                const container = document.querySelector(
                                    '.max-w-3xl.justify-start.items-start'); // Adjust selector if needed
                                if (container) {
                                    container.innerHTML = `<div class="text-center py-8">
                                        <i class="fa-regular fa-folder-open text-4xl text-[var(--text-muted)] mb-3"></i>
                                        <p class="text-lg text-[var(--text-muted)]">You haven't saved any questions yet.</p>
                                     </div>`;
                                }
                            }
                        }, 500);
                    }
                } else {
                    Toastify({
                        text: res.message || "Failed to unsave.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                }
            }).catch(err => {
                loadingToast.hideToast();
                Toastify({
                    text: "Something went wrong",
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

        function saveQuestion(btn) {
            const questionId = btn.getAttribute('data-question-id');
            const questionCard = document.getElementById(`question-card-${questionId}`);
            let formData = new FormData();
            formData.append("question_id", questionId);

            let loadingToast = Toastify({
                close: false,
                gravity: "top",
                position: "right",
                duration: -1,
                text: "Saving...",
                style: {
                    background: "#444"
                }
            });
            loadingToast.showToast();

            fetch("{{ route('saveQuestion') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            }).then(response => response.json()).then(res => {
                loadingToast.hideToast();
                if (res.success) {
                    Toastify({
                        text: res.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    btn.innerHTML = `<i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>`;
                    btn.setAttribute("title", "Unsave Question");
                    updateSavedIcons();
                } else {
                    Toastify({
                        text: res.message || "Failed to save.",
                        close: true,
                        gravity: "top",
                        position: "right",
                        duration: 3000,
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                }
            }).catch(err => {
                loadingToast.hideToast();
                Toastify({
                    text: "Something went wrong",
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
    </script>
@endsection
