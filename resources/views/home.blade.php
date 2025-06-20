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
            border-color: var(--accent-tertiary);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.08);
            transform: translateY(-2px);
        }

        .main-content {
            background-color: var(--bg-secondary);
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

        .ask-question-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .ask-question-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-5px);
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
            outline: 2px solid var(--accent-tertiary);
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
            border-color: var(--accent-tertiary);
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

        .tabs-container {
            border-bottom: 1px solid var(--border-color);
        }

        .tab-item {
            padding: 0.5rem 0.25rem;
            margin-bottom: -1px;
            border-bottom: 2px solid transparent;
            color: var(--text-secondary);
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .tab-item:not(.active):hover {
            color: var(--text-primary);
            border-bottom-color: var(--border-color);
        }

        .tab-item.active {
            color: var(--text-primary);
            font-weight: 600;
            border-bottom-color: var(--accent-tertiary);
        }

        .tag-filter-button {
            background-color: var(--bg-card);
            color: var(--text-secondary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 10px 15px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }

        .tag-filter-button:hover,
        .tag-filter-button.active {
            border-color: var(--accent-tertiary);
            color: var(--text-primary);
        }

        .tag-filter-button .chevron-icon {
            transition: transform 0.3s ease;
        }

        .tag-filter-button.active .chevron-icon {
            transform: rotate(180deg);
        }

        #tag-filter-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            /* Position below the button with a small gap */
            right: 0;
            width: 280px;
            z-index: 40;
            /* Make sure it's above other content but below the main navbar */
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        #tag-filter-dropdown.open {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Search bar inside the dropdown */
        .tag-search-input-wrapper {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }

        .tag-search-input {
            width: 100%;
            padding: 0.5rem 0.75rem 0.5rem 2.25rem;
            /* Space for the icon */
            border-radius: 6px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .tag-search-input:focus {
            outline: none;
            border-color: var(--accent-tertiary);
            box-shadow: 0 0 0 2px rgba(56, 163, 165, 0.2);
        }

        /* List items in the dropdown */
        .tag-link-item {
            display: block;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            border-left: 2px solid transparent;
        }

        .tag-link-item:hover {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            border-left-color: var(--accent-primary);
        }

        .tag-link-item.active {
            background-color: var(--bg-accent-subtle);
            color: var(--accent-tertiary);
            font-weight: 600;
            border-left-color: var(--accent-tertiary);
        }

        .skeleton {
            background-color: var(--bg-card-hover);
        }

        .skeleton .bg-gray-300 {
            background-color: var(--bg-secondary);
            animation: shimmer 2s infinite linear;
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

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

    <!-- Header Section -->
    {{-- <div
        class=" bg-transparent rounded-lg p-6 px-8 max-w-5xl mt-6 mb-6 flex justify-start items-start space-x-5 popular-container backdrop-blur-sm relative overflow-hidden">
        <!-- Decorative fire elements -->
        <div
            class="absolute -right-20 -bottom-28 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(245,158,11,0.15)] to-[rgba(250,204,21,0.15)] blur-2xl">
        </div>
        <div
            class="absolute -left-10 -top-10 w-32 h-32 rounded-full bg-gradient-to-tl from-[rgba(245,158,11,0.1)] to-[rgba(250,204,21,0.1)] blur-xl">
        </div>

        <div class="text-4xl relative p-3 rounded-full bg-[rgba(245,158,11,0.15)] z-10">
            <i class="fa-solid fa-fire text-[var(--accent-tertiary)]"></i>
        </div>

        <div class="flex flex-col z-10">
            <h1 class="cal-sans-regular popular-title lg:text-3xl text-2xl mb-2 font-bold">
                Popular Questions
            </h1>
            <p class="text-[var(--text-secondary)] text-lg pl-0.5 font-regular max-w-xl">
                Hottest discussions voted by the community. These questions have received the most engagement.
            </p>
        </div>
    </div> --}}

    <div
        class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.2)] to-[rgba(128,237,153,0.2)] blur-2xl">
    </div>
    <div
        class="bg-transparent rounded-lg p-6 px-8 max-w-5xl justify-start items-start my-6 flex flex-col md:flex-row md:items-center md:space-x-6 backdrop-blur-sm relative overflow-hidden">
        <div class="flex flex-col pl-3 z-10">
            @if (session()->has('email'))
                <div class="mb-4">
                    <div class="cal-sans-regular text-xl lg:text-2xl text-[var(--text-primary)] mb-1 tracking-wide">
                        Welcome,
                    </div>
                    <h1
                        class="cal-sans-regular text-4xl lg:text-6xl bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] py-0.5 bg-clip-text text-transparent leading-tight">
                        {{ $username }}!
                    </h1>
                </div>
                <p class=" text-lg pl-0.5 leading-relaxed max-w-xl">
                    <span class="font-semibold text-[#3cac9d]">Ask questions</span>.
                    <span class="font-semibold text-[#57CC99]">share answers</span>,
                    <span class="font-semibold text-[#6bce82]">learn together</span>,
                    <span class="text-[var(--text-muted)]"> with fellow </span>
                    <span class="font-bold !text-[var(text-secondary)]">Petranesian Informates</span>!
                </p>
            @endif
        </div>
    </div>

    <div class="w-full justify-start items-start px-8 mt-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            {{-- Search Bar --}}
            {{-- <div class="question-search-bar w-full md:w-auto md:flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input id="questionSearchInput" type="text" placeholder="Search questions by title..."
                    value="{{ $initialSearchTerm ?? '' }}">
            </div> --}}

            {{-- Sort & Tag Filters --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-4 w-full">
                <div class="tabs-container flex items-center gap-x-6 text-sm">
                    <a href="#" data-sortby="latest"
                        class="tab-item {{ ($initialSortBy ?? 'latest') == 'latest' ? 'active' : '' }}">
                        <i class="fa-solid fa-bolt mr-1.5"></i> Most Recent
                    </a>
                    <a href="#" data-sortby="views"
                        class="tab-item {{ ($initialSortBy ?? '') == 'views' ? 'active' : '' }}">
                        <i class="fa-solid fa-eye mr-1.5"></i> Most Views
                    </a>
                    <a href="#" data-sortby="votes"
                        class="tab-item {{ ($initialSortBy ?? '') == 'votes' ? 'active' : '' }}">
                        <i class="fa-solid fa-thumbs-up mr-1.5"></i> Most Votes
                    </a>
                </div>

                <div class="relative md:ml-auto" id="tag-filter-container">

                    <button type="button" id="tag-filter-button" class="tag-filter-button w-full md:w-auto">
                        <i class="fa-solid fa-tag text-xs"></i>
                        <span>Subject: <span id="current-tag-name"
                                class="font-semibold">{{ $initialFilterTag ?: 'All' }}</span></span>
                        <i class="fa-solid fa-chevron-down chevron-icon text-xs ml-auto"></i>
                    </button>

                    <div id="tag-filter-dropdown">
                        <div class="tag-search-input-wrapper">
                            <i
                                class="fa-solid fa-magnifying-glass text-sm text-gray-500 absolute left-6 top-1/2 -translate-y-1/2 transform"></i>
                            <input type="text" id="tag-search-input" placeholder="Search subjects..."
                                class="tag-search-input">
                        </div>

                        <ul class="max-h-60 overflow-y-auto p-1" id="tag-list">
                            <li>
                                <a href="#" class="tag-link-item {{ !$initialFilterTag ? 'active' : '' }}"
                                    data-tag-name="">All Subjects</a>
                            </li>

                            @if (isset($tags) && count($tags) > 0)
                                @foreach ($tags as $tag)
                                    <li>
                                        <a href="#"
                                            class="tag-link-item {{ ($initialFilterTag ?? '') == $tag['name'] ? 'active' : '' }}"
                                            data-tag-name="{{ $tag['name'] }}">
                                            {{ $tag['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl justify-start items-start px-8">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-3/4 bg-transparent rounded-lg" id="questions-list-ajax-container">
                @include('partials.questions_list_content', [
                    'questions' => $questions,
                    'currentFilterTag' => $initialFilterTag,
                    'currentSearchTerm' => $initialSearchTerm,
                ])
                <div class="pagination-container mt-8">
                    {{ $questions->appends(request()->query())->links() }}
                </div>
            </div>

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

                            <div class="w-full mt-1 pt-5 border-t border-[var(--border-color)]">
                                <a href="{{ route('askPage') }}"
                                    class="w-full ask-question-btn bg-gradient-to-r from-[#38A3A5] to-[#80ED99] text-black font-medium py-2.5 text-md px-4 rounded-lg flex items-center justify-center hover:shadow-lg hover:from-[#80ED99] hover:to-[#38A3A5] transform hover:scale-105 transition-all duration-200">
                                    <i class="fa-solid fa-plus mr-2"></i> Ask a Question
                                </a>
                            </div>

                            {{-- <!-- Popular topics -->
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
                            </div> --}}
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
        // --- Functions from home.blade.php to be added ---
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

                    extraTags.forEach(tag => {
                        tag.classList.toggle('hidden', !isCurrentlyHidden);
                    });

                    if (isCurrentlyHidden) {
                        this.textContent = 'show less';
                    } else {
                        this.textContent = this.dataset.initialText;
                    }
                });
                button.dataset.toggleInitialized = 'true';
            });
        }

        // --- End of functions from home.blade.php ---


        document.addEventListener('DOMContentLoaded', function() {
            // Initial calls for elements present on page load
            initSaveButtons(); // Existing
            updateSavedIcons(); // Existing
            updateIconColors(); // Existing
            initClickableQuestionCards(); // New
            initTagToggles(); // New

            if (typeof Trie === 'undefined') {
                console.error(
                    'FATAL ERROR: Trie class is not defined. Make sure utils.trie.blade.php is included correctly and defines the Trie class globally.'
                );
                const questionsListOutputContainer = document.getElementById(
                    'questionsListOutput'); // Assuming this is a typo and meant questionsListContainer
                const mainQuestionContainer = document.getElementById('questions-list-ajax-container');
                if (mainQuestionContainer) {
                    mainQuestionContainer.innerHTML =
                        '<p style="color:red; text-align:center; padding:20px;">Search functionality is currently unavailable due to a configuration error. Please contact support.</p>';
                }
                return;
            }

            // updateIconColors(); // Already called above

            if (typeof window.pageThemeObserver === 'undefined') {
                window.pageThemeObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') {
                            updateIconColors();
                            updateSavedIcons
                                (); // Good to update saved icon colors on theme change too
                        }
                    });
                });
                window.pageThemeObserver.observe(document.documentElement, {
                    attributes: true
                });
            }


            const questionsListContainer = document.getElementById('questions-list-ajax-container');
            const paginationLinksContainer = questionsListContainer.querySelector('.pagination-container');
            const searchInput = document.getElementById('questionSearchInput');
            // const tagFilterSelect = document.getElementById('filter_tag_select');
            const sortByButtons = document.querySelectorAll('.tab-item[data-sortby]');

            const tagFilterContainer = document.getElementById('tag-filter-container');
            const tagFilterButton = document.getElementById('tag-filter-button');
            const tagFilterDropdown = document.getElementById('tag-filter-dropdown');
            const tagSearchInput = document.getElementById('tag-search-input');
            const tagList = document.getElementById('tag-list');
            const currentTagNameSpan = document.getElementById('current-tag-name');

            let currentPage = {{ $initialPage ?? 1 }};
            let currentSortBy = '{{ $initialSortBy ?? 'latest' }}';
            let currentFilterTag = '{{ $initialFilterTag ?? '' }}';
            let currentSearchTerm = '{{ $initialSearchTerm ?? '' }}';

            const ajaxUrl = '{{ route('home') }}';

            // event listener buat subject drop down + search
            if (tagFilterContainer) {
                tagFilterButton.addEventListener('click', (event) => {
                    event
                .stopPropagation(); // Prevent the 'document' click listener from firing immediately
                    const isOpen = tagFilterDropdown.classList.toggle('open');
                    tagFilterButton.classList.toggle('active', isOpen);
                    if (isOpen) {
                        tagSearchInput.focus();
                    }
                });

                tagSearchInput.addEventListener('input', () => {
                    const searchTerm = tagSearchInput.value.toLowerCase();
                    tagList.querySelectorAll('li').forEach(li => {
                        const tagName = li.textContent.toLowerCase();
                        li.style.display = tagName.includes(searchTerm) ? 'block' : 'none';
                    });
                });

                // handle select subject
                tagList.addEventListener('click', (event) => {
                    const targetLink = event.target.closest('.tag-link-item');
                    if (targetLink) {
                        event.preventDefault();
                        const selectedTag = targetLink.dataset.tagName;

                        if (currentFilterTag !== selectedTag) {
                            currentFilterTag = selectedTag;
                            fetchQuestions(1); // Fetch new questions for page 1 with the new tag

                            // Update the button text and active state in the list
                            currentTagNameSpan.textContent = selectedTag || 'All';
                            tagList.querySelectorAll('.tag-link-item').forEach(link => link.classList
                                .remove('active'));
                            targetLink.classList.add('active');
                        }

                        tagFilterDropdown.classList.remove('open');
                        tagFilterButton.classList.remove('active');
                    }
                });
            }

            document.addEventListener('click', (event) => {
                if (tagFilterContainer && !tagFilterContainer.contains(event.target)) {
                    tagFilterDropdown.classList.remove('open');
                    tagFilterButton.classList.remove('active');
                }
            });

            function showLoadingSkeleton() {
                // ... (your existing skeleton logic - no change needed here)
                if (!questionsListContainer) return;
                const listContentArea = questionsListContainer.querySelector(
                    '#questionsListOutput'); // Assuming content goes here
                if (listContentArea) { // Clear only specific content area if it exists
                    listContentArea.innerHTML = ''; // Clear previous questions
                } else { // Fallback: clear most of container except pagination
                    while (questionsListContainer.firstChild && questionsListContainer.firstChild !==
                        paginationLinksContainer) {
                        questionsListContainer.removeChild(questionsListContainer.firstChild);
                    }
                }
                let skeletonHTML = '';
                const skeletonCount = 3;
                for (let i = 0; i < skeletonCount; i++) {
                    skeletonHTML += `
                    <div class="question-card popular-question-card rounded-lg mb-4 p-5 flex skeleton">
                        <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)]">
                            <div class="w-6 h-4 rounded bg-gray-300 animate-pulse"></div> <div class="w-6 h-4 rounded bg-gray-300 animate-pulse"></div> <div class="w-6 h-4 rounded bg-gray-300 animate-pulse"></div>
                        </div>
                        <div class="flex-1 p-0 mr-4 z-10">
                            <div class="h-5 rounded w-3/4 mb-3 bg-gray-300 animate-pulse"></div> <div class="h-3 rounded w-full mb-2 bg-gray-300 animate-pulse"></div>
                            <div class="h-3 rounded w-5/6 mb-4 bg-gray-300 animate-pulse"></div>
                            <div class="flex flex-wrap gap-2 items-center"> <div class="h-4 w-16 rounded bg-gray-300 animate-pulse"></div> <div class="h-4 w-20 rounded bg-gray-300 animate-pulse"></div> </div>
                        </div>
                    </div>`;
                }
                // Insert skeleton before pagination or at the start of where content should be
                const targetInsertLocation = listContentArea || questionsListContainer;
                const insertBeforeElement = listContentArea ? null :
                    paginationLinksContainer; // If listContentArea, append. Else, insert before pagination.

                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = skeletonHTML;
                Array.from(tempDiv.children).forEach(skelNode => {
                    if (insertBeforeElement) {
                        targetInsertLocation.insertBefore(skelNode, insertBeforeElement);
                    } else {
                        targetInsertLocation.appendChild(skelNode);
                    }
                });
                if (paginationLinksContainer) paginationLinksContainer.innerHTML = '';
            }

            async function fetchQuestions(page = 1, updateUrlHistory = true) {
                showLoadingSkeleton();
                const params = new URLSearchParams({
                    page,
                    sort_by: currentSortBy
                });
                if (currentFilterTag) params.append('filter_tag', currentFilterTag);
                if (currentSearchTerm) params.append('search_term', currentSearchTerm);

                const displayParams = new URLSearchParams(params.toString());
                if (parseInt(page) === 1 && displayParams.has('page')) {
                    displayParams.delete('page');
                }

                const requestUrl = `${ajaxUrl}?${params.toString()}`;
                const historyUrl =
                    `${window.location.pathname}${displayParams.toString() ? '?' + displayParams.toString() : ''}`;

                try {
                    const response = await fetch(requestUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    const data = await response.json();

                    // Determine where to inject the new HTML content
                    const listContentArea = questionsListContainer.querySelector(
                        '#questionsListOutput'); // Ideal target
                    const targetContainer = listContentArea || questionsListContainer; // Fallback
                    const insertBeforeNode = listContentArea ? null :
                        paginationLinksContainer; // If using listContentArea, append to it. Otherwise, insert before pagination in main container.

                    // Clear previous content before injecting new HTML
                    if (listContentArea) {
                        listContentArea.innerHTML = data.html; // Replace content of specific area
                    } else {
                        // Clear old question cards if not using a dedicated output div
                        while (targetContainer.firstChild && targetContainer.firstChild !==
                            paginationLinksContainer) {
                            targetContainer.removeChild(targetContainer.firstChild);
                        }
                        const tempContentDiv = document.createElement('div');
                        tempContentDiv.innerHTML = data.html;
                        Array.from(tempContentDiv.children).forEach(contentNode => {
                            if (insertBeforeNode) {
                                targetContainer.insertBefore(contentNode, insertBeforeNode);
                            } else {
                                targetContainer.appendChild(contentNode);
                            }
                        });
                    }


                    if (paginationLinksContainer) {
                        paginationLinksContainer.innerHTML = data.pagination_html;
                        initializePaginationLinks();
                    }
                    currentPage = data.current_page || page;
                    if (updateUrlHistory) {
                        window.history.pushState({
                            page: currentPage,
                            sortBy: currentSortBy,
                            filterTag: currentFilterTag,
                            searchTerm: currentSearchTerm
                        }, '', historyUrl);
                    }

                    // Re-initialize interactive elements for the new content
                    initSaveButtons();
                    updateSavedIcons();
                    updateIconColors();
                    initClickableQuestionCards(); // Apply to new cards
                    initTagToggles(); // Apply to new tags

                } catch (error) {
                    console.error('Error fetching questions:', error);
                    const errorTarget = questionsListContainer.querySelector('#questionsListOutput') ||
                        questionsListContainer;
                    while (errorTarget.firstChild && errorTarget.firstChild !== paginationLinksContainer) {
                        errorTarget.removeChild(errorTarget.firstChild);
                    }
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'popular-question-card rounded-lg p-8 text-center text-red-500';
                    errorDiv.innerHTML = '<p>Sorry, something went wrong. Please try refreshing the page.</p>';

                    const insertBeforeErrorNode = questionsListContainer.querySelector('#questionsListOutput') ?
                        null : paginationLinksContainer;
                    if (insertBeforeErrorNode) {
                        errorTarget.insertBefore(errorDiv, insertBeforeErrorNode);
                    } else {
                        errorTarget.appendChild(errorDiv);
                    }

                    if (paginationLinksContainer) paginationLinksContainer.innerHTML = '';
                }
            }

            function initializePaginationLinks() {
                // ... (your existing pagination logic - no change needed here)
                if (!paginationLinksContainer) return;
                paginationLinksContainer.querySelectorAll('a[href]').forEach(link => {
                    if (link.getAttribute('aria-current') === 'page' || link.closest(
                            'span[aria-disabled="true"]')) return;
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        if (page) fetchQuestions(parseInt(page));
                    });
                });
            }
            initializePaginationLinks(); // Initial call for any pre-rendered pagination

            sortByButtons.forEach(button => {
                // ... (your existing sort button logic - no change needed here)
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const newSortBy = this.dataset.sortby;
                    if (newSortBy && newSortBy !== currentSortBy) {
                        currentSortBy = newSortBy;
                        currentPage = 1;
                        fetchQuestions(currentPage);
                        sortByButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });

            if (tagFilterSelect) {
                // ... (your existing tag filter logic - no change needed here)
                tagFilterSelect.addEventListener('change', function() {
                    currentFilterTag = this.value;
                    currentPage = 1;
                    fetchQuestions(currentPage);
                });
            }

            let searchDebounceTimeout;
            if (searchInput) {
                // ... (your existing search input logic - no change needed here)
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchDebounceTimeout);
                    searchDebounceTimeout = setTimeout(() => {
                        currentSearchTerm = this.value.trim();
                        currentPage = 1;
                        fetchQuestions(currentPage);
                    }, 500);
                });
            }

            window.addEventListener('popstate', function(event) {
                // ... (your existing popstate logic - no change needed here)
                const state = event.state || {};
                const paramsFromUrl = new URLSearchParams(window.location.search);

                currentPage = state.page || parseInt(paramsFromUrl.get('page')) || 1;
                currentSortBy = state.sortBy || paramsFromUrl.get('sort_by') || 'latest';
                currentFilterTag = state.filterTag || paramsFromUrl.get('filter_tag') || '';
                currentSearchTerm = state.searchTerm || paramsFromUrl.get('search_term') || '';

                if (searchInput) searchInput.value = currentSearchTerm;
                if (tagFilterSelect) tagFilterSelect.value = currentFilterTag;
                sortByButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.sortby ===
                    currentSortBy));
                fetchQuestions(currentPage, false); // `false` because URL is already updated by browser
            });

            questionsListContainer.addEventListener('click', function(event) {
                // ... (your existing filter clear logic - no change needed here)
                if (event.target.matches('a.filter-clear-link')) {
                    event.preventDefault();
                    currentFilterTag = '';
                    currentSearchTerm = '';
                    currentPage = 1;
                    // currentSortBy = 'latest'; 
                    if (searchInput) searchInput.value = '';
                    if (tagFilterSelect) tagFilterSelect.value = '';
                    sortByButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.sortby ===
                        currentSortBy));
                    fetchQuestions(currentPage);
                }
            });

            // Removed showLoadingState and removeLoadingState functions as showLoadingSkeleton is used.
            // updateIconColors(); // Already called at the start of DOMContentLoaded

            // const themeObserver = new MutationObserver ... // This is defined earlier
            // themeObserver.observe(document.documentElement, { attributes: true }); // Also defined earlier

            const communityCards = document.querySelectorAll('.grid > div');
            if (communityCards) {
                // ... (your existing community card hover logic - no change needed here)
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
        }); // End of DOMContentLoaded

        // Globally defined functions (save/unsave/updateIcons)
        // initSaveButtons is now called within DOMContentLoaded and after AJAX
        function initSaveButtons() {
            const saveButtons = document.querySelectorAll('.save-question-btn');
            saveButtons.forEach(button => {
                // Check if already processed by looking for a custom attribute
                if (button.dataset.saveBtnInitialized === 'true') return;

                const newButton = button.cloneNode(true);
                newButton.removeAttribute('onclick');
                button.parentNode.replaceChild(newButton, button);

                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Crucial to prevent card click

                    const icon = this.querySelector('i');
                    if (icon && icon.classList.contains('fa-solid') && icon.classList.contains(
                            'fa-bookmark')) {
                        unsaveQuestion(this);
                    } else {
                        saveQuestion(this);
                    }
                });
                newButton.dataset.saveBtnInitialized = 'true'; // Mark as initialized
            });
        }

        function updateIconColors() { // This function is defined in your original script
            const statsItems = document.querySelectorAll('.stats-item');
            const isLightMode = document.documentElement.classList.contains('light-mode');
            if (statsItems) {
                statsItems.forEach((item, index) => {
                    const icon = item.querySelector('i');
                    if (!icon) return;
                    if (index % 3 === 0) icon.style.color = isLightMode ? '#10b981' : '#23BF7F';
                    else if (index % 3 === 1) icon.style.color = isLightMode ? '#f59e0b' : '#ffd249';
                    else icon.style.color = isLightMode ? '#4DB2BF' : '#3DAAA3';
                });
            }
        }


        function updateSavedIcons() {
            // ... (your existing updateSavedIcons logic - no change needed here)
            const savedIcons = document.querySelectorAll('.save-question-btn i.fa-solid.fa-bookmark');
            const isLightMode = document.documentElement.classList.contains('light-mode');
            savedIcons.forEach(icon => {
                icon.style.color =
                    'var(--accent-secondary)'; // Simplified as it seems to be the same for both modes
            });
        }

        function unsaveQuestion(btn) {
            // ... (your existing unsaveQuestion logic - no change needed here)
            const id = btn.getAttribute('data-question-id');
            let formData = new FormData();
            formData.append("question_id", id);

            let loadingToast = Toastify({
                text: "Unsaving...",
                duration: -1,
                /*...*/
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
                        /*...*/
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    btn.innerHTML =
                        `<i class="fa-regular fa-bookmark text-[var(--text-muted)] hover:text-[var(--accent-secondary)]"></i>`;
                    btn.setAttribute("title", "Save Question");
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
                /* Error Toast */
            });
        }

        function saveQuestion(btn) {
            // ... (your existing saveQuestion logic - no change needed here)
            const id = btn.getAttribute('data-question-id');
            let formData = new FormData();
            formData.append("question_id", id);

            let loadingToast = Toastify({
                text: "Saving...",
                duration: -1,
                /*...*/
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
                        /*...*/
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    btn.innerHTML = `<i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>`;
                    btn.setAttribute("title", "Unsave Question");
                    updateSavedIcons(); // Call to ensure new saved icon gets correct styling
                    btn.classList.add('saved-animation');
                    setTimeout(() => btn.classList.remove('saved-animation'), 300);
                } else {
                    Toastify({
                        text: res.message || "Failed to save.",
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
                /* Error Toast */
            });
        }
    </script>
@endsection
