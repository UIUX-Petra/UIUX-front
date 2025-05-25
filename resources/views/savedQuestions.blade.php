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
    {{-- @endsection --}}

    {{-- @section('content') --}}
    @include('partials.nav')
    @if (session()->has('Error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('Error') }}'
            });
        </script>
    @endif
    <div
        class="w-full bg-transparent rounded-lg p-6 px-8 max-w-7xl mx-auto mt-6 mb-6 flex items-center space-x-5 popular-container backdrop-blur-sm relative overflow-hidden">
        <!-- Decorative fire elements -->
        <div
            class="absolute -right-20 -bottom-28 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(245,158,11,0.15)] to-[rgba(250,204,21,0.15)] blur-2xl">
        </div>
        <div
            class="absolute -left-10 -top-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(245,158,11,0.1)] to-[rgba(250,204,21,0.1)] blur-xl">
        </div>

        <div class="text-4xl relative p-3 rounded-full bg-[rgba(245,158,11,0.15)] z-10">
            <i class="fa-solid fa-bookmark text-[#80ED99]"></i>
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
                    <div id="{{ $question['id'] }}"
                        class="question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[var(--accent-tertiary)] relative overflow-hidden">
                        <div class="absolute inset-0 bg-pattern opacity-5"></div>

                        <button onclick="unsaveQuestion(this)" type="submit"
                            class="save-question-btn absolute top-3 right-3 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-hover)] bg-[var(--bg-card-hover)]"
                            data-question-title="{{ $question['title'] }}" data-question-id="{{ $question['id'] }}"
                            title="Unsave Question">
                            <i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>
                        </button>

                        <!-- Stats Column -->
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
                                <span class="text-sm font-medium">{{ $question['comment_count'] ?? 0 }}</span>
                                <i class="text-sm fa-regular fa-comment"></i>
                            </div>
                        </div>

                        <div class="flex-1 pt-0 mr-4 z-10">
                            <!-- Question Title -->
                            <h2
                                class="text-xl font-medium text-[var(--text-highlight)] question-title cursor-pointer transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2">
                                <a
                                    href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] }}</a>
                            </h2>

                            <!-- Question Snippet -->
                            <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                                {{ \Str::limit($question['question'], 150) }}</p>

                            <!-- Tags -->
                            @if ($question['group_question'])
                                <div class="flex mt-2 flex-wrap gap-1">

                                    @foreach ($question['group_question'] as $tag)
                                        <a
                                            href="{{ route('popular', ['filter_tag' => $tag['subject']['name'], 'sort_by' => 'latest', 'page' => 1]) }}"><span
                                                class="hover:border-white hover:border-2 text-xs px-2 py-1 font-bold rounded-full bg-[var(--bg-light)] text-[var(--text-tag)]">
                                                {{ $tag['subject']['name'] }}
                                            </span></a>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <p class="text-lg text-[var(--text-muted)]">You haven't saved any questions yet.</p>
                </div>
            @endif
        </div>

        <div class="w-72 mt-6 ml-6 hidden md:flex sticky top-24 h-fit">
            <div
                class="ask-question-card rounded-lg p-6 shadow-md bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden">
                <!-- Decorative elements -->
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

                    <!-- Quick links -->
                    <div class="w-full mt-5 pt-5 border-t border-[var(--border-color)]">
                        <h3 class="font-medium mb-3 text-sm">Quick Links</h3>
                        <ul class="space-y-2 text-left">
                            <li class="flex items-center text-sm">
                                <i class="fa-solid fa-fire-flame-curved mr-2 text-amber-500"></i>
                                <a href="#"
                                    class="text-[var(--text-secondary)] hover:text-[var(--accent-secondary)] transition-colors">Popular
                                    Questions</a>
                            </li>
                            <li class="flex items-center text-sm">
                                <i class="fa-solid fa-star mr-2 text-yellow-500"></i>
                                <a href="#"
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
        function unsaveQuestion(btn) {
            const id = btn.getAttribute('data-question-id');
            let formData = new FormData();
            formData.append("question_id", id);

            let loadingToast = Toastify({
                text: "Unsaving...",
                duration: -1,
                close: false,
                gravity: "top",
                position: "right",
                style: {
                    background: "#444",
                },
            });
            loadingToast.showToast();

            fetch("{{ route('unsaveQuestion') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
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
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        }
                    }).showToast();
                    btn.innerHTML =
                        `<i class="fa-regular fa-bookmark text-[var(--text-muted)] hover:text-[var(--accent-secondary)]"></i>`;
                    btn.setAttribute("onclick", "saveQuestion(this)");
                    btn.setAttribute("title", "Save Question");
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
                        background: "#e74c3c",
                    }
                }).showToast();
            });
        }

        function saveQuestion(btn) {
            const id = btn.getAttribute('data-question-id');
            let formData = new FormData();
            formData.append("question_id", id);

            let loadingToast = Toastify({
                text: "Saving...",
                duration: -1,
                close: false,
                gravity: "top",
                position: "right",
                style: {
                    background: "#444",
                },
            });
            loadingToast.showToast();

            fetch("{{ route('saveQuestion') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
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
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        }
                    }).showToast();
                    btn.innerHTML = `<i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>`;
                    btn.setAttribute("onclick", "unsaveQuestion(this)");
                    btn.setAttribute("title", "Unsave Question");
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
                        background: "#e74c3c",
                    }
                }).showToast();
            });
        }
    </script>
@endsection
