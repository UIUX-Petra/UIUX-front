@extends('layout')

@section('content')
    <style>
        /* Add these to your existing CSS */
        .popular-title {
            background: -webkit-linear-gradient(#ffb700, #ff8c00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .popular-container {
            background-color: var(--bg-card);
            border-radius: 1rem;
            transition: all var(--transition-speed);
            border: 1px solid var(--border-color);
        }

        .popular-container:hover {
            border-color: rgba(245, 158, 11, 0.3);
        }

        .popular-question-card {
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            transition: all 0.3s ease;
        }

        .popular-question-card:hover {
            border-color: #f59e0b;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.08);
            transform: translateY(-2px);
        }

        /* Stats items in question cards */
        .stats-item {
            transition: transform 0.2s;
        }

        .stats-item:hover {
            transform: translateY(-2px);
        }

        /* Pagination styling */
        .pagination-container nav {
            display: flex;
            justify-content: center;
        }

        .pagination-container nav>div {
            @apply bg-[var(--bg-card)];
            @apply rounded-lg;
            @apply shadow-sm;
            @apply border;
            @apply border-[var(--border-color)];
            @apply overflow-hidden;
        }

        .pagination-container .relative.inline-flex {
            @apply px-3;
            @apply py-2;
            @apply text-sm;
            @apply bg-transparent;
            @apply text-[var(--text-secondary)];
            @apply transition-colors;
            @apply duration-200;
        }

        .pagination-container .relative.inline-flex:hover {
            @apply bg-[rgba(245, 158, 11, 0.1)];
            @apply text-amber-500;
        }

        .pagination-container span[aria-current="page"] span {
            @apply bg-[rgba(245, 158, 11, 0.2)];
            @apply text-amber-500;
        }

        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible {
            outline: 2px solid #f59e0b;
            outline-offset: 2px;
        }

        /* Skeleton loading animation */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        .skeleton {
            background: linear-gradient(to right, var(--bg-secondary) 8%, var(--bg-card-hover) 18%, var(--bg-secondary) 33%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
        }

        @media (max-width: 768px) {
            .popular-container {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.25rem;
            }

            .popular-container .text-3xl {
                margin-bottom: 1rem;
            }

            .popular-container .flex.space-x-4 {
                margin-top: 1rem;
                overflow-x: auto;
                width: 100%;
                padding-bottom: 0.5rem;
            }

            .popular-question-card {
                padding: 1rem;
            }

            .stats-item {
                padding-right: 0.5rem;
            }
        }

        .titleGradient {
            /* Style dari contoh Anda */
            background: linear-gradient(90deg, #ffb700, #ff8c00);
            /* Disesuaikan dengan tema popular questions */
            background-size: 200%;
            font-weight: 700;
            -webkit-text-fill-color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

       .question-search-bar {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px; /* Sesuai referensi */
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            padding: 0.60rem 1rem; /* Disesuaikan agar tinggi mirip tombol */
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .question-search-bar:focus-within {
            border-color: #f59e0b; /* Tema popular */
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.15); /* Tema popular */
        }
        .question-search-bar input {
            background-color: transparent;
            color: var(--text-primary);
            outline: none;
            width: 100%;
            font-size: 0.9rem;
        }
        .question-search-bar input::placeholder {
            color: var(--text-secondary);
        }
        .question-search-bar i {
            color: var(--text-secondary);
            margin-right: 0.75rem;
        }


        /* MODIFIKASI: Tombol filter/sort dan select, disesuaikan dengan style tab referensi */
        .filter-button { /* Untuk <a> tag (Sort by) */
            background-color: var(--bg-card);
            color: var(--text-muted);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 10px 20px; /* Sesuai referensi tabs */
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .filter-button:hover {
            border-color: #f59e0b;
            color: #f59e0b;
        }
        .filter-button.active {
            background-color: #f59e0b; /* Warna tema popular, bukan var(--accent-tertiary) dari referensi */
            color: var(--text-dark, #1a202c);
            font-weight: 600; /* Sesuai referensi tabs */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Sesuai referensi tabs */
            border-color: #f59e0b;
        }

        .tag-filter-select { /* Untuk <select> element */
            background-color: var(--bg-card);
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 16px; /* Mirip tombol, tinggi sama, padding horizontal sedikit beda utk arrow */
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem; /* Samakan dengan search input jika perlu */
            line-height: 1.5; /* Sesuaikan dengan padding vertikal */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25em 1.25em;
            padding-right: 2.5rem;
            min-width: 150px; /* Lebar minimal agar "All Tags" terbaca */
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            cursor: pointer;
        }
        .tag-filter-select:hover {
            border-color: #f59e0b;
            color: #f59e0b;
        }
        .tag-filter-select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.15);
            outline: none;
            color: #f59e0b;
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
    {{-- @include('utils.background2') --}}

    <!-- Header Section -->
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
            <i class="fa-solid fa-fire text-[#f59e0b]"></i>
        </div>

        <div class="flex flex-col z-10">
            <h1 class="cal-sans-regular popular-title lg:text-3xl text-2xl mb-2 font-bold">
                Popular Questions
            </h1>
            <p class="text-[var(--text-secondary)] text-lg pl-0.5 font-regular max-w-xl">
                Hottest discussions voted by the community. These questions have received the most engagement.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-8 mt-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="question-search-bar w-full md:w-auto md:flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input id="questionSearchInput" type="text" placeholder="Search questions by title..."
                       oninput="searchQuestions()">
            </div>

            <div class="flex flex-wrap items-center gap-x-3 gap-y-3">
                {{-- Label "Sort by:" bisa dihilangkan jika desain tab sudah cukup jelas --}}
                {{-- <span class="text-[var(--text-secondary)] font-medium text-sm mr-1">Sort by:</span> --}}
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'latest', 'page' => 1, 'search_term' => request('search_term')]) }}"  {{-- Pertahankan search_term jika ada --}}
                    class="filter-button {{ (request('sort_by', 'latest') == 'latest') ? 'active' : '' }}">
                    <i class="fa-solid fa-bolt"></i> New Questions
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'views', 'page' => 1, 'search_term' => request('search_term')]) }}"
                    class="filter-button {{ request('sort_by') == 'views' ? 'active' : '' }}">
                    <i class="fa-solid fa-eye"></i> Views
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'votes', 'page' => 1, 'search_term' => request('search_term')]) }}"
                    class="filter-button {{ request('sort_by') == 'votes' ? 'active' : '' }}">
                    <i class="fa-solid fa-thumbs-up"></i> Votes
                </a>

                <form method="GET" action="{{ request()->url() }}" id="filterTagForm" class="contents">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'latest') }}">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="search_term" value="{{ request('search_term') }}"> {{-- Pertahankan search_term --}}
                    @foreach (request()->except(['filter_tag', 'page', 'sort_by', 'search_term']) as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $subValue)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $subValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    
                    <select id="filter_tag_select" name="filter_tag"
                            class="tag-filter-select"
                            onchange="this.form.submit();">
                        <option value="">All Tags</option>
                        @if(isset($tags) && count($tags) > 0)
                            @foreach ($tags as $tag)
                                <option value="{{ $tag['name']}}" {{ request('filter_tag') == $tag['name'] ? 'selected' : '' }}>
                                    {{ $tag['name'] }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>No tags available</option> 
                        @endif
                    </select>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
                class="bg-[var(--bg-card)] rounded-lg p-5 text-center border border-[var(--border-color)] hover:border-[#f59e0b] transition-all duration-200">
                <div class="flex justify-center mb-3">
                    <div class="p-3 rounded-full bg-[rgba(245,158,11,0.15)]">
                        <i class="fa-solid fa-trophy text-2xl text-amber-500"></i>
                    </div>
                </div>
                <h4 class="text-lg font-medium mb-1">Top Contributor</h4>
                <p class="text-sm text-[var(--text-muted)] mb-2">This Month</p>
                <div class="flex items-center justify-center">
                    <img src="{{ asset('assets/default-avatar.png') }}" class="w-8 h-8 rounded-full mr-2">
                    <span class="font-medium">User123</span>
                </div>
            </div>

            <div
                class="bg-[var(--bg-card)] rounded-lg p-5 text-center border border-[var(--border-color)] hover:border-[#f59e0b] transition-all duration-200">
                <div class="flex justify-center mb-3">
                    <div class="p-3 rounded-full bg-[rgba(245,158,11,0.15)]">
                        <i class="fa-solid fa-star text-2xl text-amber-500"></i>
                    </div>
                </div>
                <h4 class="text-lg font-medium mb-1">Most Upvoted Question</h4>
                <p class="text-sm text-[var(--text-muted)] mb-2">This Week</p>
                <a href="#" class="font-medium hover:text-[#f59e0b] transition-colors">How to implement sorting
                    algorithms?</a>
            </div>

            <div
                class="bg-[var(--bg-card)] rounded-lg p-5 text-center border border-[var(--border-color)] hover:border-[#f59e0b] transition-all duration-200">
                <div class="flex justify-center mb-3">
                    <div class="p-3 rounded-full bg-[rgba(245,158,11,0.15)]">
                        <i class="fa-solid fa-fire-flame-curved text-2xl text-amber-500"></i>
                    </div>
                </div>
                <h4 class="text-lg font-medium mb-1">Trending Topic</h4>
                <p class="text-sm text-[var(--text-muted)] mb-2">Right Now</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <span class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.25)] text-amber-500">Machine
                        Learning</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content area with questions list and sidebar -->
    <div class="max-w-7xl justify-start items-start px-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Questions List with enhanced design -->
            <div class="w-full md:w-3/4 bg-transparent rounded-lg">
                @if ($questions->isEmpty())
                    <div class="popular-question-card rounded-lg p-8 text-center">
                        <i class="fa-solid fa-folder-open text-4xl text-[var(--text-muted)] mb-4"></i>
                        <p class="text-xl font-semibold text-[var(--text-primary)]">No Questions Found</p>
                        @if (request('filter_tag'))
                            <p class="text-[var(--text-secondary)] mt-2">There are no questions matching the tag
                                "{{ request('filter_tag') }}". Try a different tag or clear the filter.</p>
                        @else
                            <p class="text-[var(--text-secondary)] mt-2">It seems there are no questions yet. Why not be the
                                first to ask?</p>
                        @endif
                        @if (request('filter_tag'))
                            <a href="{{ request()->fullUrlWithQuery(['filter_tag' => null, 'page' => 1]) }}"
                                class="mt-4 inline-block px-4 py-2 text-sm font-medium text-white bg-[#f59e0b] rounded-md hover:bg-amber-600 transition-colors">
                                Clear Tag Filter
                            </a>
                        @endif
                    </div>
                @else
                    @foreach ($questions as $question)
                        <div
                            class="question-card popular-question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[#f59e0b] relative overflow-hidden">
                            <!-- Hot indicator for extremely popular questions -->
                            @if ($question['vote'] > 50)
                                <div class="absolute top-0 right-0">
                                    <div
                                        class="bg-gradient-to-r from-amber-500 to-amber-400 text-white text-xs py-1 px-3 rounded-bl-lg rounded-tr-lg font-medium flex items-center">
                                        <i class="fa-solid fa-fire-flame-curved mr-1.5"></i> Hot
                                    </div>
                                </div>
                            @endif

                            <!-- Stats Column -->
                            <div
                                class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]">
                                <div class="stats-item flex flex-row items-center space-x-2">
                                    <i class="text-sm fa-regular fa-thumbs-up"></i>
                                    <span class="text-sm font-medium mt-1">{{ $question['vote'] }}</span>
                                </div>
                                <div class="stats-item flex flex-row items-center space-x-2">
                                    <i class="text-sm fa-solid fa-eye"></i>
                                    <span class="text-sm font-medium mt-1">{{ $question['view'] }}</span>
                                </div>
                                <div class="stats-item flex flex-row items-center space-x-2">
                                    <i class="text-sm fa-regular fa-comment"></i>
                                    <span class="text-sm font-medium mt-1">{{ $question['comments_count'] }}</span>
                                </div>
                            </div>

                            <div class="flex-1  p-0 mr-4 z-10">
                                <!-- Question Titl -->
                                <h2
                                    class="text-xl font-medium text-[var(--text-highlight)] question-title cursor-pointer transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2">
                                    <a
                                        href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] }}</a>
                                </h2>

                                <!-- Question Snippet -->
                                <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">
                                    {{ \Str::limit($question['question'], 150) }}</p>

                                <!-- Tags and engagement indicator -->
                                <div class="flex mt-4 flex-wrap gap-2 items-center">
                                    @foreach ($question['group_question'] as $tag)
                                        <span
                                            class="text-xs px-2 py-1 rounded-md font-bold bg-[var(--bg-light)] text-[var(--text-tag)]">{{ $tag['subject']['name'] }}</span>
                                    @endforeach

                                    <!-- Engagement indicator -->
                                    {{-- <span class="ml-auto text-xs text-[var(--text-muted)] flex items-center">
                                        <i class="fa-solid fa-chart-line text-amber-500 mr-1.5"></i>
                                        {{ rand(50, 95) }}% engagement rate
                                    </span> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Pagination-->
                <div class="pagination-container mt-8">
                    {{ $questions->appends(request()->query())->links() }}
                </div>
            </div>

            <!-- Sidebar with Ask Question Card -->
            <div class="md:w-1/4 w-full">
                <div class="sticky top-24">
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

                            <!-- Popular topics -->
                            <div class="w-full mt-5 pt-5 border-t border-[var(--border-color)]">
                                <h3 class="font-medium mb-3 text-sm">Popular Topics</h3>
                                <div class="flex flex-wrap gap-2">
                                    <a href="#"
                                        class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.15)] text-amber-500 hover:bg-[rgba(245,158,11,0.25)] transition-colors">Programming</a>
                                    <a href="#"
                                        class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.15)] text-amber-500 hover:bg-[rgba(245,158,11,0.25)] transition-colors">Design</a>
                                    <a href="#"
                                        class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.15)] text-amber-500 hover:bg-[rgba(245,158,11,0.25)] transition-colors">Data
                                        Science</a>
                                    <a href="#"
                                        class="text-xs px-2 py-1 rounded-full bg-[rgba(245,158,11,0.15)] text-amber-500 hover:bg-[rgba(245,158,11,0.25)] transition-colors">Algorithms</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    @include('utils.trie') {{-- Pastikan ini di-include SEBELUM script di bawahnya --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Pemeriksaan Definisi Kelas Trie
            if (typeof Trie === 'undefined') {
                console.error('FATAL ERROR: Trie class is not defined. Make sure utils.trie.blade.php is included correctly and defines the Trie class globally.');
                // Anda bisa menghentikan eksekusi script lebih lanjut atau menampilkan pesan error ke pengguna
                const questionsListOutputContainer = document.getElementById('questionsListOutput');
                if(questionsListOutputContainer) {
                    questionsListOutputContainer.innerHTML = '<p style="color:red; text-align:center; padding:20px;">Search functionality is currently unavailable due to a configuration error. Please contact support.</p>';
                }
                return; 
            }

            // 2. Fungsi updateIconColors (didefinisikan sekali)
            function updateIconColors() {
                const statsItems = document.querySelectorAll('.stats-item');
                const isLightMode = document.documentElement.classList.contains('light-mode');
                if (statsItems) {
                    statsItems.forEach((item, index) => {
                        const icon = item.querySelector('i');
                        if (!icon) return;
                        if (index % 3 === 0) icon.style.color = isLightMode ? '#10b981' : '#23BF7F';
                        else if (index % 3 === 1) icon.style.color = isLightMode ? '#f59e0b' : '#ffd249';
                        else icon.style.color = isLightMode ? '#3b82f6' : '#909ed5';
                    });
                }
            }
            updateIconColors(); // Panggil saat load

            if (typeof window.pageThemeObserver === 'undefined') {
                window.pageThemeObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') updateIconColors();
                    });
                });
                window.pageThemeObserver.observe(document.documentElement, { attributes: true });
            }


            let allQuestionsData = [];
            try {
                const jsonData = @json($questions->items() ?? []);
                if (Array.isArray(jsonData)) {
                    allQuestionsData = jsonData;
                } else {
                    console.warn('Parsed questions data is not an array. Initializing as empty. Data:', jsonData);
                }
            } catch (e) {
                console.error('Error parsing questions data from Blade:', e);
            }
            
            let baseUrlQuestionView = "{{ route('user.viewQuestions', ['questionId' => '_QUESTION_ID_PLACEHOLDER_']) }}";
            baseUrlQuestionView = baseUrlQuestionView.replace('_QUESTION_ID_PLACEHOLDER_', ':questionId');

            let questionTitleTrie;
            try {
                questionTitleTrie = new Trie(); 
                if (Array.isArray(allQuestionsData)) {
                    allQuestionsData.forEach(question => {
                        if (question && typeof question.title === 'string' && question.title.trim() !== '') {
                            questionTitleTrie.insert(question.title.toLowerCase());
                        } else if (question && question.title) {
                             questionTitleTrie.insert(String(question.title).toLowerCase());
                        }
                    });
                }
                // console.log("Trie initialized with titles:", questionTitleTrie);
            } catch (e) {
                console.error('Error initializing or populating Trie:', e);
                return; // Hentikan jika Trie gagal diinisialisasi
            }
            
            const searchInputElement = document.getElementById('questionSearchInput');
            const questionsListOutputContainer = document.getElementById('questionsListOutput');
            const noSearchResultsMessageElement = document.getElementById('noSearchResultsMessage');
            const paginationContainerElement = document.querySelector('.pagination-container');
            const noQuestionsInitialElement = questionsListOutputContainer ? questionsListOutputContainer.querySelector('.no-questions-initial') : null;
            
            // Helper functions
            function escapeHtml(unsafe) { if (unsafe === null || typeof unsafe === 'undefined') return ''; return unsafe.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;"); }
            function stripTags(input) { if (input === null || typeof input === 'undefined') return ''; return input.toString().replace(/<\/?[^>]+(>|$)/g, ""); }
            function strLimit(text, limit = 150, end = '...') { if (text === null || typeof text === 'undefined') return ''; let strippedText = stripTags(text.toString()); if (strippedText.length <= limit) return escapeHtml(strippedText); return escapeHtml(strippedText.substring(0, limit - end.length)) + end; }

            function createQuestionCardHTML(question) {
                // ... (Implementasi createQuestionCardHTML dari respons sebelumnya)
                let hotIndicatorHtml = ''; if (question.vote > 50) { hotIndicatorHtml = `<div class="absolute top-0 right-0"><div class="bg-gradient-to-r from-amber-500 to-amber-400 text-white text-xs py-1 px-3 rounded-bl-lg rounded-tr-lg font-medium flex items-center"><i class="fa-solid fa-fire-flame-curved mr-1.5"></i> Hot</div></div>`; }
                let tagsHtml = ''; if (question.group_question && Array.isArray(question.group_question)) { tagsHtml = question.group_question.map(tagItem => (tagItem && tagItem.subject && tagItem.subject.name) ? `<span class="text-xs px-2 py-1 rounded-md font-bold bg-[var(--bg-light)] text-[var(--text-tag)]">${escapeHtml(tagItem.subject.name)}</span>` : '').join(''); }
                const questionUrl = baseUrlQuestionView.replace(':questionId', question.id); const engagementRate = Math.floor(Math.random() * (95 - 50 + 1)) + 50;
                const title = question.title ? escapeHtml(question.title) : 'Untitled Question'; const snippet = question.question ? strLimit(question.question, 150) : 'No content available.';
                return `<div class="question-card popular-question-card rounded-lg mb-4 p-5 transition-all duration-200 flex hover:border-[#f59e0b] relative overflow-hidden"> ${hotIndicatorHtml} <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)] text-[var(--text-primary)]"> <div class="stats-item flex flex-row items-center space-x-2"> <i class="text-sm fa-regular fa-thumbs-up"></i> <span class="text-sm font-medium mt-1">${question.vote || 0}</span> </div> <div class="stats-item flex flex-row items-center space-x-2"> <i class="text-sm fa-solid fa-eye"></i> <span class="text-sm font-medium mt-1">${question.view || 0}</span> </div> <div class="stats-item flex flex-row items-center space-x-2"> <i class="text-sm fa-regular fa-comment"></i> <span class="text-sm font-medium mt-1">${question.comments_count || 0}</span> </div> </div> <div class="flex-1 p-0 mr-4 z-10"> <h2 class="text-xl font-medium text-[var(--text-highlight)] question-title cursor-pointer transition-colors duration-200 hover:underline decoration-[var(--accent-secondary)] decoration-[1.5px] underline-offset-2"> <a href="${questionUrl}">${title}</a> </h2> <p class="text-[var(--text-secondary)] text-md leading-relaxed mt-2">${snippet}</p> <div class="flex mt-8 flex-wrap gap-2 items-center"> ${tagsHtml} <span class="ml-auto text-xs text-[var(--text-muted)] flex items-center"> <i class="fa-solid fa-chart-line text-amber-500 mr-1.5"></i> ${engagementRate}% engagement rate </span> </div> </div> </div>`;
            }
            
            // 4. Jadikan searchQuestions dan searchQuestionsDebounced global
            window.searchQuestionsInternal = function() { // Ubah nama agar tidak konflik jika ada 'searchQuestions' lain
                if (!searchInputElement || !questionsListOutputContainer || !questionTitleTrie) {
                    console.warn('Search dependencies not ready for searchQuestionsInternal call.');
                    return;
                }
                const searchTerm = searchInputElement.value.toLowerCase().trim();
                let filteredQuestions = [];

                if (searchTerm === "") {
                    filteredQuestions = allQuestionsData;
                } else {
                    if (typeof questionTitleTrie.search !== 'function') {
                        console.error('CRITICAL: questionTitleTrie.search is still not a function! Trie object:', questionTitleTrie);
                        return; 
                    }
                    const matchingTitles = questionTitleTrie.search(searchTerm);
                    if (!Array.isArray(matchingTitles)) {
                        console.error('Trie search did not return an array:', matchingTitles);
                        return;
                    }
                    filteredQuestions = allQuestionsData.filter(question => 
                        question.title && matchingTitles.includes(question.title.toLowerCase())
                    );
                }

                let newHtml = '';
                if (filteredQuestions.length > 0) {
                    filteredQuestions.forEach(question => {
                        newHtml += createQuestionCardHTML(question);
                    });
                    if (noSearchResultsMessageElement) noSearchResultsMessageElement.style.display = 'none';
                    if (noQuestionsInitialElement) noQuestionsInitialElement.style.display = 'none';
                } else {
                     if (searchTerm !== "" && noSearchResultsMessageElement) {
                        noSearchResultsMessageElement.style.display = 'block';
                    } else if (searchTerm === "" && noQuestionsInitialElement) { 
                        noQuestionsInitialElement.style.display = 'block'; // atau 'flex' atau 'grid' sesuai display aslinya
                    }
                    if (noQuestionsInitialElement && searchTerm !== "") noQuestionsInitialElement.style.display = 'none';
                }
                questionsListOutputContainer.innerHTML = newHtml || (noSearchResultsMessageElement ? '' : '<p>No questions available.</p>'); // Fallback jika noSearchResultsMessageElement juga tidak ada

                if (paginationContainerElement) {
                    let showPagination = false;
                    const itemsPerPage = {{ $questions->perPage() }}; // Ambil perPage dari paginator
                    const totalServerItems = {{ $questions->total() }}; // Total item dari server untuk filter saat ini

                    if (searchTerm === "") {
                        showPagination = totalServerItems > itemsPerPage;
                    } else {
                        // Untuk client-side search, pagination biasanya tetap berdasarkan jumlah total dari server
                        // karena link pagination akan load ulang dari server.
                        // Kecuali jika kita mau menyembunyikan jika hasil filter client < 1 halaman penuh.
                        // Untuk konsistensi, kita biarkan server yang menentukan paginasi.
                        // Namun, jika filteredQuestions 0, maka sembunyikan.
                        showPagination = filteredQuestions.length > 0 && totalServerItems > itemsPerPage;
                    }
                    paginationContainerElement.style.display = showPagination ? 'flex' : 'none';
                }
                updateIconColors();
            };
            
            let searchDebounceTimer;
            window.searchQuestionsDebounced = function() {
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(window.searchQuestionsInternal, 300);
            }

            const urlParams = new URLSearchParams(window.location.search);
            const initialSearchTerm = urlParams.get('search_term');
            if (initialSearchTerm && searchInputElement) {
                searchInputElement.value = initialSearchTerm;
            }
            
            if (typeof Trie !== 'undefined' && questionsListOutputContainer && Array.isArray(allQuestionsData)) {
                 window.searchQuestionsInternal(); 
            }

            // Show skeleton loading animation
            function showLoadingState() {
                const questionContainer = document.querySelector('.questions-list');
                if (!questionContainer) return;

                // Save current content
                questionContainer.dataset.originalContent = questionContainer.innerHTML;

                // Clear and add skeletons
                questionContainer.innerHTML = '';
                for (let i = 0; i < 3; i++) {
                    const skeletonCard = document.createElement('div');
                    skeletonCard.className = 'question-card skeleton rounded-lg mb-4 p-5 flex';
                    skeletonCard.innerHTML = `
                    <div class="flex flex-col items-center mr-4 space-y-3 px-3 border-r border-[var(--border-color)]">
                        <div class="w-8 h-8 rounded-full bg-gray-300"></div>
                        <div class="w-8 h-8 rounded-full bg-gray-300"></div>
                        <div class="w-8 h-8 rounded-full bg-gray-300"></div>
                    </div>
                    <div class="flex-1">
                        <div class="h-6 bg-gray-300 rounded w-3/4 mb-3"></div>
                        <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                    </div>
                `;
                    questionContainer.appendChild(skeletonCard);
                }
            }

            // Remove skeleton loading animation
            function removeLoadingState() {
                const questionContainer = document.querySelector('.questions-list');
                if (!questionContainer || !questionContainer.dataset.originalContent) return;

                questionContainer.innerHTML = questionContainer.dataset.originalContent;
                updateIconColors();
            }

            // Initialize
            updateIconColors();

            // Watch for theme changes
            const themeObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        updateIconColors();
                    }
                });
            });

            themeObserver.observe(document.documentElement, {
                attributes: true
            });

            // Add hover effects to stats cards
            const communityCards = document.querySelectorAll('.grid > div');
            if (communityCards) {
                communityCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-5px)';
                        this.style.boxShadow = '0 10px 25px rgba(245, 158, 11, 0.1)';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = 'none';
                    });
                });
            }
        });
    </script>
@endsection
