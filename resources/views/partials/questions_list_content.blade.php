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
                <br>Try adjusting your filters or <a href="{{ route('home') }}" class="text-[var(--accent-tertiary)] hover:underline filter-clear-link">clear all filters</a>.
            </p>
        @else
            <p class="text-[var(--text-secondary)] mt-2">
                It seems there are no questions at the moment.
                Why not be the first to <a href="{{ route('askPage') }}" class="text-[var(--accent-tertiary)] hover:underline">ask a question</a>?
            </p>
        @endif
    </div>
@else
    @foreach ($questions as $question)
        <div class="question-card popular-question-card border-solid border-white rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[var(--accent-tertiary)] relative overflow-hidden"
             data-url="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}" style="cursor: pointer;">
            <div class="absolute inset-0 bg-pattern opacity-5"></div> {{-- Background pattern --}}
            
            @if (isset($question['vote']) && $question['vote'] > 50)
                <div class="absolute bottom-0 right-0">
                    <div class="bg-gradient-to-r from-[#f4ab24] to-[#ffd249] text-[var(--text-dark)] text-xs py-1 px-3 rounded-tl-lg rounded-tr-lg font-medium flex items-center">
                        <i class="fa-solid fa-fire-flame-curved mr-1.5"></i> Hot
                    </div>
                </div>
            @endif

            {{-- Save/Unsave Button --}}
              <div class="question-menu-container absolute top-2 right-2 z-20">
                <button type="button" class="question-menu-trigger p-2 w-8 h-8 flex items-center justify-center rounded-full hover:bg-[var(--bg-card-hover)] transition-colors duration-200">
                    <i class="fa-solid fa-ellipsis-vertical text-[var(--text-secondary)]"></i>
                </button>
                
                <div class="question-menu-dropdown absolute top-full right-0 mt-2 w-48 bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-lg shadow-xl hidden">
                    {{-- Panel Utama --}}
                    <div class="menu-panel menu-panel-main">
                        <ul>
                            <li>
                                <a href="#" class="menu-item" data-action="save" data-question-id="{{ $question['id'] }}">
                                    @if (isset($question['is_saved_by_request_user']) && $question['is_saved_by_request_user'])
                                        <i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>
                                        <span>Unsave</span>
                                    @else
                                        <i class="fa-regular fa-bookmark"></i>
                                        <span>Save</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="#" class="menu-item" data-action="show-report-panel">
                                    <i class="fa-regular fa-flag"></i>
                                    <span>Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
            
                    {{-- Panel Report (hidden by default) --}}
                    <div class="menu-panel menu-panel-report hidden">
                        <div class="flex items-center p-2 border-b border-[var(--border-color)]">
                            <button type="button" data-action="back-to-main" class="menu-back-button p-1 rounded-full hover:bg-[var(--bg-card-hover)]">
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                            </button>
                            <h4 class="text-sm font-semibold mx-auto text-[var(--text-primary)]">Report Content</h4>
                        </div>
                        <ul class="report-reason-list max-h-48 overflow-y-auto">
                            {{-- List alasan laporan akan diisi oleh JavaScript --}}
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Stats Column --}}
            <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]">
                <div class="stats-item flex flex-row items-center space-x-2">
                    <span class="text-sm font-medium">{{ $question['vote'] ?? 0 }}</span>
                    <i class="text-sm fa-solid fa-thumbs-up"></i>
                </div>
                <div class="stats-item flex flex-row items-center space-x-2">
                    <span class="text-sm font-medium">{{ $question['view'] ?? 0 }}</span>
                    <i class="text-sm fa-solid fa-eye"></i>
                </div>
                <div class="stats-item flex flex-row items-center space-x-2">
                    <span class="text-sm font-medium">{{ count($question['answer']) ?? 0 }}</span>
                    <i class="text-sm fa-solid fa-reply-all"></i>
                </div>
            </div>

            {{-- Question Content --}}
            <div class="flex-1 pt-0 mr-4 z-10">
                {{-- handle click by question box --}}
                <h2 class="text-xl font-medium text-[var(--text-highlight)] question-title transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2 pr-2">
                    {{ $question['title'] ?? 'Untitled Question' }}
                </h2>
                <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                    {{ \Str::limit(strip_tags($question['question'] ?? 'No content available.'), 150) }}
                </p>
            
                <div class="flex mt-2 flex-wrap gap-1 items-center tags-wrapper" data-question-id="{{ $question['id'] }}">
                    @php
                        $tags = $question['group_question'] ?? [];
                        $totalTags = count($tags);
                        $displayLimit = 3; // max 3 tags
                    @endphp

                    @if(!empty($tags) && is_array($tags))
                        @foreach ($tags as $index => $tag)
                            @if(isset($tag['subject']['name']))
                                <a href="{{ route('home', ['filter_tag' => $tag['subject']['name'], 'sort_by' => $currentSortBy ?? 'latest', 'page' => 1]) }}" {{-- Ensure $currentSortBy is available or use a default --}}
                                   class="question-tag-link @if($index >= $displayLimit) hidden extra-tag-{{ $question['id'] }} @endif">
                                    <span class="scale-100 transition-all duration-300 hover:font-normal font-light text-xs px-2 py-1 rounded-10 bg-[var(--bg-light)] text-[var(--text-tag)]">
                                        {{ $tag['subject']['name'] }}
                                    </span>
                                </a>
                            @endif
                        @endforeach

                        @if ($totalTags > $displayLimit)
                            <span class="text-xs text-[var(--accent-primary)] cursor-pointer ease-linear hover:underline underline-offset-2 more-tags-button"
                                  data-question-id="{{ $question['id'] }}"
                                  data-initial-text="+ {{ $totalTags - $displayLimit }} more">
                                 + {{ $totalTags - $displayLimit }} more
                            </span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endif