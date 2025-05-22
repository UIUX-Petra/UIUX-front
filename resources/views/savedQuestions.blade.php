@extends('layout')
@section('head')
@endsection

@section('content')
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

    <div class="justify-start items-start max-w-8xl px-4 flex space-x-6">
        <div class="w-full bg-transparent rounded-lg p-6 shadow-lg max-w-3xl justify-start items-start">
            <!-- Loop through questions -->
            @if (isset($questions) && count($questions) > 0)
                @foreach ($questions as $question)
                    <div
                        class="question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[var(--accent-tertiary)] relative overflow-hidden">
                        <div class="absolute inset-0 bg-pattern opacity-5"></div>

                        {{-- Since these are saved questions, we know they're saved by the user --}}
                        <form action="{{ route('unsaveQuestion') }}" method="POST">
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $question['id'] }}">
                            <button type="submit"
                                class="save-question-btn absolute top-3 right-3 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-hover)] bg-[var(--bg-card-hover)]"
                                data-question-id="{{ $question['id'] }}" title="Unsave Question">
                                <i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>
                            </button>
                        </form>

                        <!-- Stats Column -->
                        <div
                            class="flex flex-col items-center justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)]">
                            <div class="stats-item flex flex-col items-center">
                                <i class="text-lg fa-regular fa-thumbs-up"></i>
                                <span class="text-sm font-medium mt-1">{{ $question['vote'] ?? 0 }}</span>
                            </div>
                            <div class="stats-item flex flex-col items-center">
                                <i class="text-lg fa-solid fa-eye"></i>
                                <span class="text-sm font-medium mt-1">{{ $question['view'] ?? 0 }}</span>
                            </div>
                            <div class="stats-item flex flex-col items-center">
                                <i class="text-lg fa-regular fa-comment"></i>
                            <span class="text-sm font-medium mt-1">{{ $question['comment_count'] ?? 0 }}</span>
                            </div>
                        </div>

                        <div class="flex-1 z-10">
                            <!-- Question Title -->
                            <h2
                                class="text-xl font-medium question-title cursor-pointer transition-colors duration-200 hover:underline decoration-[var(--accent-tertiary)] decoration-2 underline-offset-2">
                                <a
                                    href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] }}</a>
                            </h2>

                            <!-- Question Snippet -->
                            <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                                {{ \Str::limit($question['question'], 150) }}</p>

                            <!-- Tags -->
                            <div class="flex mt-3 flex-wrap gap-1">
                                @foreach ($question['group_question'] as $tag)
                                    <span
                                        class="text-xs px-2 py-1 rounded-full bg-[var(--bg-tag)] text-[var(--text-tag)]">{{ $tag['subject']['name'] }}</span>
                                @endforeach
                            </div>

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
@endsection
