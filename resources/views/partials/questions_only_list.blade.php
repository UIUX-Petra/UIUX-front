{{-- resources/views/partials/questions_only_list.blade.php --}}

@forelse ($questions as $question)
    <div class="question-card border-solid border-white rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[var(--accent-tertiary)] relative overflow-hidden"
         data-url="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}" style="cursor: pointer;">
        <div class="absolute inset-0 bg-pattern opacity-5"></div> {{-- Asumsi class .bg-pattern ada di CSS global Anda --}}

        {{-- Tombol Save/Unsave --}}
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

        {{-- Kolom Stats --}}
        <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]">
            <div class="stats-item flex flex-row items-center space-x-2">
                <span class="text-sm font-medium">{{ $question['vote'] ?? 0 }}</span>
                <i class="text-sm fa-regular fa-thumbs-up"></i>
            </div>
            <div class="stats-item flex flex-row items-center space-x-2">
                <span class="text-sm font-medium">{{ $question['view'] ?? 0 }}</span>
                <i class="text-sm fa-solid fa-eye"></i>
            </div>
            <div class="stats-item flex flex-row items-center space-x-2">
                <span class="text-sm font-medium">{{ count($question['answer']) ?? 0 }}</span>
                <i class="text-sm fa-solid fa-reply"></i>
            </div>
        </div>

        {{-- Konten Pertanyaan Utama --}}
        <div class="flex-1 pt-0 mr-4 z-10">
            {{-- handle click by question box --}}
            <h2 class="text-xl font-medium text-[var(--text-highlight)] question-title transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2">
                {{ $question['title'] ?? 'Untitled Question' }}
            </h2>
            <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                {{ \Str::limit(strip_tags($question['question'] ?? 'No content available.'), 150) }}
            </p>
            <div class="flex mt-2 flex-wrap gap-1 items-center tags-wrapper" data-question-id="{{ $question['id'] }}">
                @php
                    $tags = $question['group_question'] ?? [];
                    $totalTags = count($tags);
                    $displayLimit = 3;
                @endphp

                @if(isset($question['group_question']) && is_array($question['group_question']))
                    @foreach ($tags as $index => $tag)
                        @if(isset($tag['subject']['name']))
                            <a href="{{ route('home', ['filter_tag' => $tag['subject']['name'], 'sort_by' => 'latest', 'page' => 1]) }}"
                               class="question-tag-link @if($index >= $displayLimit) hidden extra-tag-{{ $question['id'] }} @endif">
                                <span class="hover:border-[var(--accent-primary)] border-1 font-light text-xs px-2 py-1 rounded-10 bg-[var(--bg-light)] text-[var(--text-tag)]">
                                    {{ $tag['subject']['name'] }}
                                </span>
                            </a>
                        @endif
                    @endforeach

                    @if ($totalTags > $displayLimit)
                        <span class="text-xs text-[var(--accent-secondary)] undeline cursor-pointer hover:underline more-tags-button"
                              data-question-id="{{ $question['id'] }}"
                              data-initial-text="+ {{ $totalTags - $displayLimit }} more">
                             + {{ $totalTags - $displayLimit }} more
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-10 text-[var(--text-muted)]">
        <i class="fa-regular fa-folder-open text-4xl mb-3"></i>
        <p class="text-lg">No questions found for the current criteria.</p>
        {{-- Anda bisa menambahkan link untuk membuat pertanyaan baru di sini jika relevan --}}
    </div>
@endforelse