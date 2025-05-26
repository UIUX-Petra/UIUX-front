@if ($questions->isEmpty())
    <div class="popular-question-card rounded-lg p-8 text-center">
        <i class="fa-solid fa-folder-open text-4xl text-[var(--text-muted)] mb-4"></i>
        <p class="text-xl font-semibold text-[var(--text-primary)]">No Questions Found</p>
        @php
            $activeFilterTag = $currentFilterTag ?? request('filter_tag');
            $activeSearchTerm = $currentSearchTerm ?? request('search_term');
            $hasActiveFilters = $activeFilterTag || $activeSearchTerm;
        @endphp
        @if ($hasActiveFilters)
            <p class="text-[var(--text-secondary)] mt-2">
                No questions match your current filters.
                @if ($activeFilterTag)
                    <br>Tag: "<span class="font-semibold">{{ $activeFilterTag }}</span>".
                @endif
                @if ($activeSearchTerm)
                    <br>Search: "<span class="font-semibold">{{ $activeSearchTerm }}</span>".
                @endif
                <br>Try adjusting your filters or <a href="{{ route('popular') }}" class="text-amber-500 hover:underline filter-clear-link">clear all filters</a>.
            </p>
        @else
            <p class="text-[var(--text-secondary)] mt-2">
                It seems there are no popular questions at the moment.
                Why not be the first to <a href="{{ route('askPage') }}" class="text-amber-500 hover:underline">ask a question</a>?
            </p>
        @endif
    </div>
@else
    @foreach ($questions as $question)
        <div class="question-card popular-question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[#f59e0b] relative overflow-hidden">
            @if (isset($question['vote']) && $question['vote'] > 50)
                <div class="absolute bottom-0 right-0">
                    <div class="bg-gradient-to-r from-amber-500 to-amber-400 text-white text-xs py-1 px-3 rounded-tl-lg rounded-tr-lg font-medium flex items-center">
                        <i class="fa-solid fa-fire-flame-curved mr-1.5"></i> Hot
                    </div>
                </div>
            @endif
            @if (isset($question['is_saved_by_request_user']) && $question['is_saved_by_request_user'])
            <button onclick="unsaveQuestion(this)" type="button"
                class="save-question-btn absolute top-3 right-3 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-hover)] bg-[var(--bg-card-hover)]"
                data-question-id="{{ $question['id'] }}"
                title="Unsave Question">
                <i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>
            </button>
        @else
            <button onclick="saveQuestion(this)" type="button"
                class="save-question-btn absolute top-3 right-3 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[var(--bg-hover)] bg-[var(--bg-card-hover)]"
                data-question-id="{{ $question['id'] }}"
                title="Save Question">
                <i class="fa-regular fa-bookmark text-[var(--text-muted)] hover:text-[var(--accent-secondary)]"></i>
            </button>
        @endif

            {{-- Stats Column --}}
            <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]">
                <div class="stats-item flex flex-row items-center space-x-2" title="Votes">
                    <i class="text-sm fa-regular fa-thumbs-up"></i>
                    <span class="text-sm font-medium mt-1">{{ $question['vote'] ?? 0 }}</span>
                </div>
                <div class="stats-item flex flex-row items-center space-x-2" title="Views">
                    <i class="text-sm fa-solid fa-eye"></i>
                    <span class="text-sm font-medium mt-1">{{ $question['view'] ?? 0 }}</span>
                </div>
                <div class="stats-item flex flex-row items-center space-x-2" title="Comments">
                    <i class="text-sm fa-regular fa-comment"></i>
                    <span class="text-sm font-medium mt-1">{{ $question['comments_count'] ?? 0 }}</span>
                </div>
            </div>

            {{-- Question Content --}}
            <div class="flex-1 p-0 mr-4 z-10">
                <h2 class="text-xl font-medium text-[var(--text-highlight)] question-title cursor-pointer transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2">
                    <a href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] ?? 'Untitled Question' }}</a>
                </h2>
                <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                    {{ \Str::limit(strip_tags($question['question'] ?? 'No content available.'), 150) }}
                </p>

                <div class="flex mt-4 flex-wrap gap-2 items-center">
                    @if(isset($question['group_question']) && is_array($question['group_question']))
                        @foreach ($question['group_question'] as $tagItem)
                            @if(isset($tagItem['subject']) && isset($tagItem['subject']['name']))
                             <a href="{{ route('popular', ['filter_tag' => $tagItem['subject']['name'], 'sort_by' => 'latest', 'page' => 1]) }}"><span class="hover:border-white hover:border-2 text-xs px-2 py-1 font-bold rounded-full bg-[var(--bg-light)] text-[var(--text-tag)]">
                                {{ $tagItem['subject']['name'] }}
                            </span></a>  
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endif