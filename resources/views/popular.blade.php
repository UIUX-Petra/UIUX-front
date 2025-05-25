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
            border-radius: 10px;
            /* Sesuai referensi */
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            padding: 0.60rem 1rem;
            /* Disesuaikan agar tinggi mirip tombol */
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .question-search-bar:focus-within {
            border-color: #f59e0b;
            /* Tema popular */
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.15);
            /* Tema popular */
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
        .filter-button {
            /* Untuk <a> tag (Sort by) */
            background-color: var(--bg-card);
            color: var(--text-muted);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 10px 20px;
            /* Sesuai referensi tabs */
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .filter-button:hover {
            border-color: #f59e0b;
            color: #f59e0b;
        }

        .filter-button.active {
            background-color: #f59e0b;
            /* Warna tema popular, bukan var(--accent-tertiary) dari referensi */
            color: var(--text-dark, #1a202c);
            font-weight: 600;
            /* Sesuai referensi tabs */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Sesuai referensi tabs */
            border-color: #f59e0b;
        }

        .tag-filter-select {
            /* Untuk <select> element */
            background-color: var(--bg-card);
            color: #f59e0b;
            border: 1px solid #f59e0b;;
            border-radius: 8px;
            padding: 10px 16px;
            /* Mirip tombol, tinggi sama, padding horizontal sedikit beda utk arrow */
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            /* Samakan dengan search input jika perlu */
            line-height: 1.5;
            /* Sesuaikan dengan padding vertikal */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25em 1.25em;
            padding-right: 2.5rem;
            min-width: 150px;
            /* Lebar minimal agar "All Tags" terbaca */
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }

        /* .tag-filter-select:hover {
            border-color: #f59e0b;
            color: #f59e0b;
        } */

        .tag-filter-select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.15);
            outline: none;
            color: #f59e0b;
        }

        .skeleton {
            /* Warna dasar skeleton, sesuaikan dengan tema Anda */
            background-color: var(--bg-card-hover);
            /* Contoh, bisa juga var(--border-color) atau warna abu-abu netral */
        }

        .skeleton .bg-gray-300 {
            /* Ganti bg-gray-300 dengan class yang Anda gunakan untuk elemen shimmer */
            background-color: var(--bg-secondary);
            /* Warna elemen shimmer, lebih terang/gelap dari dasar */
            animation: shimmer 2s infinite linear;
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            /* Sesuaikan ukuran ini agar lebih besar dari lebar elemen */
            100% {
                background-position: 1000px 0;
            }
        }

        /* Tambahan untuk elemen skeleton agar memiliki background-size yang besar untuk efek shimmer */
        .skeleton .bg-gray-300,
        .skeleton .h-5,
        .skeleton .h-3,
        .skeleton .h-4,
        .skeleton .w-6 {
            /* Target elemen yang akan shimmer */
            background-image: linear-gradient(to right, transparent 0%, var(--bg-card) 50%, transparent 100%);
            background-size: 1000px 100%;
            /* Lebar besar untuk shimmer */
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
            {{-- Search Bar --}}
            <div class="question-search-bar w-full md:w-auto md:flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input id="questionSearchInput" type="text" placeholder="Search questions by title..."
                       value="{{ $initialSearchTerm ?? '' }}">
            </div>

            {{-- Sort & Tag Filters --}}
            <div class="flex flex-wrap items-center gap-x-3 gap-y-3">
                <a href="#" data-sortby="latest" class="filter-button {{ ($initialSortBy ?? 'latest') == 'latest' ? 'active' : '' }}">
                    <i class="fa-solid fa-bolt"></i> New Questions
                </a>
                <a href="#" data-sortby="views" class="filter-button {{ ($initialSortBy ?? '') == 'views' ? 'active' : '' }}">
                    <i class="fa-solid fa-eye"></i> Views
                </a>
                <a href="#" data-sortby="votes" class="filter-button {{ ($initialSortBy ?? '') == 'votes' ? 'active' : '' }}">
                    <i class="fa-solid fa-thumbs-up"></i> Votes
                </a>

                <select id="filter_tag_select" name="filter_tag" class="tag-filter-select">
                    <option value="">All Tags</option>
                    @if(isset($tags) && count($tags) > 0)
                        @foreach ($tags as $tag)
                            <option value="{{ $tag['name'] }}" {{ ($initialFilterTag ?? '') == $tag['name'] ? 'selected' : '' }}>
                                {{ $tag['name'] }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>No tags available</option>
                    @endif
                </select>
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
                    <img src="{{ $image ? asset('storage/' . $image) : 'https://ui-avatars.com/api/?name=' . urlencode($username ?? 'User') . '&background=7E57C2&color=fff&size=128' }}" class="w-8 h-8 rounded-full mr-2">
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
            <div class="w-full md:w-3/4 bg-transparent rounded-lg" id="questions-list-ajax-container">
                @include('partials.questions_list_content', [
                    'questions' => $questions,
                    'currentFilterTag' => $initialFilterTag,
                    'currentSearchTerm' => $initialSearchTerm
                ])
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
    @include('utils.trie') 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Trie === 'undefined') {
                console.error(
                    'FATAL ERROR: Trie class is not defined. Make sure utils.trie.blade.php is included correctly and defines the Trie class globally.'
                    );
                const questionsListOutputContainer = document.getElementById('questionsListOutput');
                if (questionsListOutputContainer) {
                    questionsListOutputContainer.innerHTML =
                        '<p style="color:red; text-align:center; padding:20px;">Search functionality is currently unavailable due to a configuration error. Please contact support.</p>';
                }
                return;
            }

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
                window.pageThemeObserver.observe(document.documentElement, {
                    attributes: true
                });
            }


           const questionsListContainer = document.getElementById('questions-list-ajax-container');
        const paginationLinksContainer = questionsListContainer.querySelector('.pagination-container');
        const searchInput = document.getElementById('questionSearchInput');
        const tagFilterSelect = document.getElementById('filter_tag_select');
        const sortByButtons = document.querySelectorAll('.filter-button[data-sortby]');

        let currentPage = {{ $initialPage ?? 1 }};
        let currentSortBy = '{{ $initialSortBy ?? 'latest' }}';
        let currentFilterTag = '{{ $initialFilterTag ?? '' }}';
        let currentSearchTerm = '{{ $initialSearchTerm ?? '' }}';

        const ajaxUrl = '{{ route('popular') }}';

        function showLoadingSkeleton() {
            if (!questionsListContainer) return;
            while(questionsListContainer.firstChild && questionsListContainer.firstChild !== paginationLinksContainer) {
                questionsListContainer.removeChild(questionsListContainer.firstChild);
            }
            let skeletonHTML = '';
            const skeletonCount = 3;
            for (let i = 0; i < skeletonCount; i++) {
                skeletonHTML += `
                    <div class="question-card popular-question-card rounded-lg mb-4 p-5 flex skeleton">
                        <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)]">
                            <div class="w-6 h-4 rounded bg-gray-300"></div> <div class="w-6 h-4 rounded bg-gray-300"></div> <div class="w-6 h-4 rounded bg-gray-300"></div>
                        </div>
                        <div class="flex-1 p-0 mr-4 z-10">
                            <div class="h-5 rounded w-3/4 mb-3 bg-gray-300"></div> <div class="h-3 rounded w-full mb-2 bg-gray-300"></div>
                            <div class="h-3 rounded w-5/6 mb-4 bg-gray-300"></div>
                            <div class="flex flex-wrap gap-2 items-center"> <div class="h-4 w-16 rounded bg-gray-300"></div> <div class="h-4 w-20 rounded bg-gray-300"></div> </div>
                        </div>
                    </div>`;
            }
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = skeletonHTML;
            Array.from(tempDiv.children).forEach(skelNode => {
                 questionsListContainer.insertBefore(skelNode, paginationLinksContainer);
            });
            if (paginationLinksContainer) paginationLinksContainer.innerHTML = '';
        }

        async function fetchQuestions(page = 1, updateUrlHistory = true) {
            showLoadingSkeleton();
            const params = new URLSearchParams({ page, sort_by: currentSortBy });
            if (currentFilterTag) params.append('filter_tag', currentFilterTag);
            if (currentSearchTerm) params.append('search_term', currentSearchTerm);

            const displayParams = new URLSearchParams(params.toString()); 
            if (parseInt(page) === 1 && displayParams.has('page')) {
                 displayParams.delete('page'); // Hapus page=1 dari URL display
            }

            const requestUrl = `${ajaxUrl}?${params.toString()}`;
            const historyUrl = `${window.location.pathname}${displayParams.toString() ? '?' + displayParams.toString() : ''}`;

            try {
                const response = await fetch(requestUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                const data = await response.json();

                while(questionsListContainer.firstChild && questionsListContainer.firstChild !== paginationLinksContainer) {
                    questionsListContainer.removeChild(questionsListContainer.firstChild);
                }
                const tempContentDiv = document.createElement('div');
                tempContentDiv.innerHTML = data.html;
                Array.from(tempContentDiv.children).forEach(contentNode => {
                    questionsListContainer.insertBefore(contentNode, paginationLinksContainer);
                });

                if (paginationLinksContainer) {
                    paginationLinksContainer.innerHTML = data.pagination_html;
                    initializePaginationLinks();
                }
                currentPage = data.current_page || page;
                if (updateUrlHistory) {
                    window.history.pushState({ page: currentPage, sortBy: currentSortBy, filterTag: currentFilterTag, searchTerm: currentSearchTerm }, '', historyUrl);
                }
                updateIconColors();
            } catch (error) {
                console.error('Error fetching questions:', error);
                while(questionsListContainer.firstChild && questionsListContainer.firstChild !== paginationLinksContainer) {
                    questionsListContainer.removeChild(questionsListContainer.firstChild);
                }
                const errorDiv = document.createElement('div');
                errorDiv.className = 'popular-question-card rounded-lg p-8 text-center text-red-500';
                errorDiv.innerHTML = '<p>Sorry, something went wrong. Please try refreshing the page.</p>';
                questionsListContainer.insertBefore(errorDiv, paginationLinksContainer);
                if (paginationLinksContainer) paginationLinksContainer.innerHTML = '';
            }
        }

        function initializePaginationLinks() {
            if (!paginationLinksContainer) return;
            paginationLinksContainer.querySelectorAll('a[href]').forEach(link => {
                if (link.getAttribute('aria-current') === 'page' || link.closest('span[aria-disabled="true"]')) return;
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page');
                    if (page) fetchQuestions(parseInt(page));
                });
            });
        }
        initializePaginationLinks();

        sortByButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const newSortBy = this.dataset.sortby;
                if (newSortBy && newSortBy !== currentSortBy) {
                    currentSortBy = newSortBy; currentPage = 1; fetchQuestions(currentPage);
                    sortByButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        if (tagFilterSelect) {
            tagFilterSelect.addEventListener('change', function() {
                currentFilterTag = this.value; currentPage = 1; fetchQuestions(currentPage);
            });
        }

        let searchDebounceTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchDebounceTimeout);
                searchDebounceTimeout = setTimeout(() => {
                    currentSearchTerm = this.value.trim(); currentPage = 1; fetchQuestions(currentPage);
                }, 500);
            });
        }

        window.addEventListener('popstate', function(event) {
            const state = event.state || {};
            const paramsFromUrl = new URLSearchParams(window.location.search);

            currentPage = state.page || parseInt(paramsFromUrl.get('page')) || 1;
            currentSortBy = state.sortBy || paramsFromUrl.get('sort_by') || 'latest';
            currentFilterTag = state.filterTag || paramsFromUrl.get('filter_tag') || '';
            currentSearchTerm = state.searchTerm || paramsFromUrl.get('search_term') || '';

            if (searchInput) searchInput.value = currentSearchTerm;
            if (tagFilterSelect) tagFilterSelect.value = currentFilterTag;
            sortByButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.sortby === currentSortBy));
            fetchQuestions(currentPage, false);
        });

        // Event listener untuk clear filter link di partial
        questionsListContainer.addEventListener('click', function(event) {
            if (event.target.matches('a.filter-clear-link')) {
                event.preventDefault();
                currentFilterTag = '';
                currentSearchTerm = '';
                currentPage = 1; 
                // currentSortBy = 'latest'; 
                if (searchInput) searchInput.value = '';
                if (tagFilterSelect) tagFilterSelect.value = '';
                sortByButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.sortby === currentSortBy));
                fetchQuestions(currentPage);
            }
        });

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
