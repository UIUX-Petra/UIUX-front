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
            background-image: linear-gradient(to right, transparent 0%, var(--bg-card) 50%, transparent 100%);
            background-size: 1000px 100%;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .menu-item:hover {
            background-color: var(--bg-card-hover);
            color: var(--text-primary);
        }

        .menu-item i {
            width: 1rem;
            text-align: center;
        }

        .question-card.menu-is-open {
            z-index: 30;
        }

        /* Dropdown Menu Styling */
        #global-question-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 1rem;

            opacity: 0;
            transition: opacity 200ms ease-out;
            pointer-events: none;
        }

        #global-question-menu.open {
            opacity: 1;
            pointer-events: auto;
        }

        .menu-content-wrapper {
            width: 100%;
            max-width: 320px;
            /* Lebar maksimal di mobile */
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            overflow: hidden;

            transform: scale(0.95);
            transition: transform 200ms ease-out;
        }

        #global-question-menu.open .menu-content-wrapper {
            transform: scale(1);
        }

        @media (min-width: 1000px) {

            #global-question-menu {
                position: absolute;
                width: auto;
                height: auto;
                background-color: transparent;
                padding: 0;
                display: block;
                align-items: unset;
                justify-content: unset;
            }

            .menu-content-wrapper {
                width: 280px;
                max-width: none;
                transform-origin: top left;
            }

            #global-question-menu.open .menu-content-wrapper {
                transform: scale(1);
            }
        }

        .menu-panel:not(.hidden) {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .menu-panel-header {
            display: grid;
            grid-template-columns: 40px 1fr 40px;
            align-items: center;
            padding: 0.5rem;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .menu-back-button {
            grid-column: 1;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .menu-back-button:hover {
            background-color: var(--bg-card-hover);
        }

        .menu-panel-title {
            grid-column: 2;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-panel-content {
            padding: 0;
            margin: 0;
            list-style: none;
            overflow-y: auto;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: var(--text-secondary);
            transition: background-color 0.2s;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .menu-item:hover {
            background-color: var(--bg-card-hover);
            color: var(--text-primary);
        }

        .menu-item i {
            width: 1rem;
            text-align: center;
            color: var(--text-muted);
        }

        .report-notes-panel-body {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
        }

        .report-notes-panel-body .instruction-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.4;
            text-align: left;
            margin-bottom: 0.25rem;
        }

        .report-notes-input {
            width: 100%;
            min-height: 90px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
            color: var(--text-primary);
            resize: vertical;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .report-notes-input:focus {
            outline: none;
            border-color: var(--accent-tertiary);
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.15);
        }

        .report-submit-button {
            background-color: var(--accent-primary);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.2s, transform 0.2s;
        }

        .report-submit-button:hover {
            background-color: var(--accent-primary-hover);
            transform: translateY(-1px);
        }
    </style>
    @include('partials.nav')

    <div id="global-question-menu" class="hidden">
        {{-- Panel 1: Panel Utama (Save, Report) --}}
        <div class="menu-content-wrapper">
            <div class="menu-panel menu-panel-main">
                <ul class="menu-panel-content">
                    <li>
                        <a class="menu-item" data-action="save">

                            <i class="fa-regular fa-bookmark"></i>
                            <span>Save</span>
                        </a>
                    </li>
                    <li>
                        <a class="menu-item" data-action="show-report-panel">
                            <i class="fa-regular fa-flag"></i>
                            <span>Report</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Panel 2: Alasan laporan --}}
            <div class="menu-panel menu-panel-report hidden">
                <div class="menu-panel-header">
                    <button type="button" data-action="back-to-main" class="menu-back-button">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <h4 class="menu-panel-title">Report Content</h4>
                </div>
                <ul class="menu-panel-content report-reason-list">

                </ul>
            </div>

            <div class="menu-panel menu-panel-notes hidden">
                <div class="menu-panel-header">
                    <button type="button" data-action="back-to-report-panel" class="menu-back-button">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <h4 class="menu-panel-title">Lainnya</h4>
                </div>
                <div class="report-notes-panel-body">
                    <p class="instruction-text">Jelaskan secara singkat mengapa konten ini tidak pantas.</p>
                    <textarea class="report-notes-input" placeholder="Tulis alasan Anda di sini..." maxlength="255"></textarea>
                    <button type="button" data-action="submit-report-with-notes" class="report-submit-button">Kirim
                        Laporan</button>
                </div>
            </div>
        </div>
    </div>
    @if (session()->has('Error'))
        <script>
            Toastify({
                text: "{{ session('Error') }}" || "An unexpected error occurred from the server.",
                duration: 3000,
                style: {
                    background: "#e74c3c"
                }
            }).showToast();
        </script>
    @endif
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('script')
    @include('utils.trie')
    <script id="report-reasons-data" type="application/json">{!! json_encode($reportReasons ?? []) !!}</script>

    <script>
        (function() {
            "use strict";

            const config = {
                ajaxUrl: '{{ route('home') }}',
                saveUrl: '{{ route('saveQuestion') }}',
                unsaveUrl: '{{ route('unsaveQuestion') }}',
                reportUrl: '{{ route('submitReport') }}',
                csrfToken: "{{ csrf_token() }}",
                reportReasons: JSON.parse(document.getElementById('report-reasons-data').textContent || '[]')
            };

            let state = {
                currentPage: {{ $initialPage ?? 1 }},
                sortBy: '{{ $initialSortBy ?? 'latest' }}',
                filterTag: '{{ $initialFilterTag ?? '' }}',
                searchTerm: '{{ $initialSearchTerm ?? '' }}',
            };

            const dom = {
                questionsListContainer: null,
                sortByButtons: null,
                tagFilter: {
                    container: null,
                    button: null,
                    dropdown: null,
                    searchInput: null,
                    list: null,
                    currentNameSpan: null
                }
            };

            async function fetchQuestions(page = 1, updateUrlHistory = true) {
                showLoadingSkeleton();

                const params = new URLSearchParams({
                    page,
                    sort_by: state.sortBy
                });
                if (state.filterTag) params.append('filter_tag', state.filterTag);
                if (state.searchTerm) params.append('search_term', state.searchTerm);

                try {
                    const response = await fetch(`${config.ajaxUrl}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                    const data = await response.json();

                    dom.questionsListContainer.innerHTML = data.html;
                    const paginationContainer = document.createElement('div');
                    paginationContainer.className = 'pagination-container mt-8';
                    paginationContainer.innerHTML = data.pagination_html;
                    dom.questionsListContainer.appendChild(paginationContainer);

                    state.currentPage = data.current_page || page;

                    if (updateUrlHistory) {
                        const displayParams = new URLSearchParams(params.toString());
                        if (parseInt(page) === 1) displayParams.delete('page');
                        const historyUrl =
                            `${window.location.pathname}${displayParams.toString() ? '?' + displayParams.toString() : ''}`;
                        window.history.pushState({
                            ...state,
                            page: state.currentPage
                        }, '', historyUrl);
                    }

                    initializeAllDynamicScripts();

                } catch (error) {
                    console.error('Error fetching questions:', error);
                    dom.questionsListContainer.innerHTML =
                        `<div class="popular-question-card rounded-lg p-8 text-center text-red-500"><p>Sorry, something went wrong. Please try refreshing the page.</p></div>`;
                }
            }

            function showLoadingSkeleton() {
                if (!dom.questionsListContainer) return;
                let skeletonHTML = '';
                for (let i = 0; i < 3; i++) {
                    skeletonHTML += `
                        <div class="question-card popular-question-card rounded-lg mb-4 p-5 flex skeleton">
                            <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)]"><div class="w-6 h-4 rounded bg-gray-300"></div><div class="w-6 h-4 rounded bg-gray-300"></div><div class="w-6 h-4 rounded bg-gray-300"></div></div>
                            <div class="flex-1 p-0 mr-4 z-10"><div class="h-5 rounded w-3/4 mb-3 bg-gray-300"></div><div class="h-3 rounded w-full mb-2 bg-gray-300"></div><div class="h-3 rounded w-5/6 mb-4 bg-gray-300"></div><div class="flex flex-wrap gap-2 items-center"><div class="h-4 w-16 rounded bg-gray-300"></div><div class="h-4 w-20 rounded bg-gray-300"></div></div></div>
                        </div>`;
                }
                dom.questionsListContainer.innerHTML = skeletonHTML;
            }

            async function handleApiCall(url, data, {
                successMsg,
                errorMsg,
                loadingMsg
            }, callbacks = {}) {
                const {
                    successCallback,
                    errorCallback,
                    finalCallback
                } = callbacks;
                const toastStyles = {
                    success: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                    },
                    failure: {
                        background: "#e74c3c"
                    }
                };

                let loadingToast;
                if (loadingMsg) {
                    loadingToast = Toastify({
                        text: loadingMsg,
                        duration: -1,
                        style: {
                            background: "#444"
                        }
                    });
                    loadingToast.showToast();
                }

                try {
                    const response = await fetch(url, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": config.csrfToken,
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify(data)
                    });
                    const result = await response.json();
                    if (response.ok && result.success) {
                        Toastify({
                            text: successMsg || result.message,
                            duration: 2000,
                            style: toastStyles.success
                        }).showToast();
                        if (successCallback) successCallback(result);
                    } else {
                        throw new Error(result.message || errorMsg);
                    }
                } catch (error) {
                    console.error(`API call to ${url} failed:`, error);
                    Toastify({
                        text: error.message,
                        duration: 3000,
                        style: toastStyles.failure
                    }).showToast();
                    if (errorCallback) errorCallback(error);
                } finally {
                    if (loadingToast) loadingToast.hideToast();
                    if (finalCallback) finalCallback();
                }
            }

            function saveQuestion(id, iconEl, textEl) {
                handleApiCall(config.saveUrl, {
                    question_id: id
                }, {
                    successMsg: "Question saved!",
                    errorMsg: "Failed to save question."
                }, {
                    successCallback: () => {
                        iconEl.classList.replace('fa-regular', 'fa-solid');
                        iconEl.style.color = 'var(--accent-secondary)';
                        textEl.textContent = 'Unsave';
                    }
                });
            }

            function unsaveQuestion(id, iconEl, textEl) {
                handleApiCall(config.unsaveUrl, {
                    question_id: id
                }, {
                    successMsg: "Question unsaved.",
                    errorMsg: "Failed to unsave question."
                }, {
                    successCallback: () => {
                        iconEl.classList.replace('fa-solid', 'fa-regular');
                        iconEl.style.color = '';
                        textEl.textContent = 'Save';
                    }
                });
            }

            function submitReport(reportableId, reportableType, reasonId) {
                handleApiCall(
                    config.reportUrl, {
                        reportable_id: reportableId,
                        reportable_type: reportableType,
                        report_reason_id: reasonId
                    }, {
                        loadingMsg: "Sending report...",
                        successMsg: "Report submitted. Thank you!",
                        errorMsg: "Failed to submit report."
                    }
                );
            }


            function initializeAllDynamicScripts() {
                initClickableQuestionCards();
                initTagToggles();
                initQuestionMenus();
                initializePaginationLinks();
                updateIconColors();
            }

            function initClickableQuestionCards() {
                dom.questionsListContainer.querySelectorAll('.question-card').forEach(card => {
                    if (card.dataset.clickableInit) return;
                    card.dataset.clickableInit = 'true';
                    card.addEventListener('click', function(e) {
                        if (e.target.closest(
                                '.question-menu-container, .question-tag-link, .more-tags-button'))
                            return;
                        if (this.dataset.url) window.location.href = this.dataset.url;
                    });
                });
            }

            function initTagToggles() {
                dom.questionsListContainer.querySelectorAll('.more-tags-button').forEach(button => {
                    if (button.dataset.toggleInit) return;
                    button.dataset.toggleInit = 'true';
                    button.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const questionId = this.dataset.questionId;
                        const extraTags = document.querySelectorAll(`.extra-tag-${questionId}`);
                        const isHidden = extraTags.length > 0 && extraTags[0].classList.contains(
                            'hidden');
                        extraTags.forEach(tag => tag.classList.toggle('hidden', !isHidden));
                        this.textContent = isHidden ? 'show less' : this.dataset.initialText;
                    });
                });
            }

            function initializePaginationLinks() {
                const paginationContainer = dom.questionsListContainer.querySelector('.pagination-container');
                if (!paginationContainer) return;
                paginationContainer.querySelectorAll('a[href]').forEach(link => {
                    if (link.getAttribute('aria-current') === 'page' || link.closest(
                            'span[aria-disabled="true"]')) return;
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = new URL(this.href).searchParams.get('page');
                        if (page) fetchQuestions(parseInt(page));
                    });
                });
            }
    
            function initQuestionMenus() {
                const globalMenu = document.getElementById('global-question-menu');
                if (!globalMenu) return;

                const mainPanel = globalMenu.querySelector('.menu-panel-main');
                const reportPanel = globalMenu.querySelector('.menu-panel-report');
                const notesPanel = globalMenu.querySelector('.menu-panel-notes');
                const notesTextarea = notesPanel.querySelector('.report-notes-input');
                const saveLink = mainPanel.querySelector('[data-action="save"]');
                const saveIcon = saveLink.querySelector('i');
                const saveText = saveLink.querySelector('span');

                let activeTrigger = null;

                const showPanel = (panelToShow) => {
                    [mainPanel, reportPanel, notesPanel].forEach(p => p.classList.add('hidden'));
                    panelToShow.classList.remove('hidden');
                };

                const closeMenu = () => {
                    globalMenu.classList.remove('open');
                    activeTrigger = null;
                };

                document.querySelectorAll('.question-menu-trigger').forEach(trigger => {
                    trigger.addEventListener('click', (e) => {
                        e.stopPropagation();

                        if (activeTrigger === trigger) {
                            closeMenu();
                            return;
                        }

                        activeTrigger = trigger;

                        const questionId = trigger.dataset.questionId;
                        const isSaved = trigger.dataset.isSaved === 'true';

                        globalMenu.dataset.questionId = questionId;

                        if (isSaved) {
                            saveIcon.className = 'fa-solid fa-bookmark text-[var(--accent-secondary)]';
                            saveText.textContent = 'Unsave';
                        } else {
                            saveIcon.className = 'fa-regular fa-bookmark';
                            saveText.textContent = 'Save';
                        }

                        if (window.innerWidth >= 1000) {
                            const questionCard = trigger.closest('.question-card');
                            const rect = questionCard.getBoundingClientRect();

                            globalMenu.style.top = `${window.scrollY + rect.top}px`;
                            globalMenu.style.left = `${rect.right + 10}px`;
                        } else {
                            globalMenu.style.top = '';
                            globalMenu.style.left = '';
                        }

                        globalMenu.classList.add('open');
                        showPanel(mainPanel);
                    });
                });

                globalMenu.addEventListener('click', e => {
                    const target = e.target.closest('[data-action]');
                    if (target) {
                        e.preventDefault();
                        e.stopPropagation();

                        const action = target.dataset.action;
                        const questionId = globalMenu.dataset.questionId;

                        switch (action) {
                            case 'save':
                                if (saveIcon.classList.contains('fa-solid')) {
                                    unsaveQuestion(questionId, saveIcon, saveText);
                                } else {
                                    saveQuestion(questionId, saveIcon, saveText);
                                }
                                break;
                            case 'show-report-panel':
                                const reasonList = reportPanel.querySelector('.report-reason-list');
                                reasonList.innerHTML = '';
                                config.reportReasons.forEach(reason => {
                                    const isOtherReason = reason.title.toLowerCase().includes(
                                    'lainnya');
                                    const itemAction = isOtherReason ? 'show-notes-panel' :
                                        'submit-report';
                                    reasonList.insertAdjacentHTML('beforeend',
                                        `<li><a href="#" class="menu-item" data-action="${itemAction}" data-reason-id="${reason.id}">${reason.title}</a></li>`
                                        );
                                });
                                showPanel(reportPanel);
                                break;
                            case 'show-notes-panel':
                                notesPanel.dataset.reasonId = target.dataset.reasonId;
                                showPanel(notesPanel);
                                notesTextarea.focus();
                                break;
                            case 'back-to-main':
                                showPanel(mainPanel);
                                break;
                            case 'back-to-report-panel':
                                showPanel(reportPanel);
                                break;
                            case 'submit-report':
                                submitReport(questionId, 'question', target.dataset.reasonId);
                                closeMenu();
                                break;
                            case 'submit-report-with-notes':
                                const reasonIdForNotes = notesPanel.dataset.reasonId;
                                const additionalNotes = notesTextarea.value.trim();
                                if (!additionalNotes) {
                                    Toastify({
                                        text: "Please provide a reason.",
                                        duration: 3000,
                                        style: {
                                            background: "#e74c3c"
                                        }
                                    }).showToast();
                                    return;
                                }
                                submitReport(questionId, 'question', reasonIdForNotes, additionalNotes);
                                closeMenu();
                                notesTextarea.value = '';
                                break;
                        }
                    } else if (e.target === globalMenu) {
                        closeMenu();
                    }
                });

                document.addEventListener('click', (e) => {
                    if (window.innerWidth >= 1000 && !globalMenu.contains(e.target) && !e.target.closest(
                            '.question-menu-trigger')) {
                        closeMenu();
                    }
                });
            }

            function updateIconColors() {
                const isLightMode = document.documentElement.classList.contains('light-mode');
                document.querySelectorAll('.stats-item').forEach((item, index) => {
                    const icon = item.querySelector('i');
                    if (!icon) return;
                    if (index % 3 === 0) icon.style.color = isLightMode ? '#10b981' : '#23BF7F';
                    else if (index % 3 === 1) icon.style.color = isLightMode ? '#f59e0b' : '#ffd249';
                    else icon.style.color = isLightMode ? '#4DB2BF' : '#3DAAA3';
                });
            }


            function bindGlobalEventListeners() {
                dom.sortByButtons.forEach(button => {
                    button.addEventListener('click', e => {
                        e.preventDefault();
                        const newSortBy = e.currentTarget.dataset.sortby;
                        if (newSortBy !== state.sortBy) {
                            state.sortBy = newSortBy;
                            dom.sortByButtons.forEach(btn => btn.classList.remove('active'));
                            e.currentTarget.classList.add('active');
                            fetchQuestions(1);
                        }
                    });
                });

                if (dom.tagFilter.container) {
                    dom.tagFilter.button.addEventListener('click', e => {
                        e.stopPropagation();
                        const isOpen = dom.tagFilter.dropdown.classList.toggle('open');
                        dom.tagFilter.dropdown.classList.toggle('hidden', !isOpen);
                        dom.tagFilter.button.classList.toggle('active', isOpen);
                        if (isOpen) dom.tagFilter.searchInput.focus();
                    });

                    dom.tagFilter.searchInput.addEventListener('input', e => {
                        const searchTerm = e.target.value.toLowerCase();
                        dom.tagFilter.list.querySelectorAll('li').forEach(li => {
                            li.style.display = li.textContent.toLowerCase().includes(searchTerm) ? '' :
                                'none';
                        });
                    });

                    dom.tagFilter.list.addEventListener('click', e => {
                        const targetLink = e.target.closest('.tag-link-item');
                        if (!targetLink) return;
                        e.preventDefault();
                        const selectedTag = targetLink.dataset.tagName;
                        if (state.filterTag !== selectedTag) {
                            state.filterTag = selectedTag;
                            dom.tagFilter.currentNameSpan.textContent = selectedTag || 'All';
                            dom.tagFilter.list.querySelector('.active')?.classList.remove('active');
                            targetLink.classList.add('active');
                            fetchQuestions(1);
                        }
                        dom.tagFilter.dropdown.classList.remove('open');
                        dom.tagFilter.dropdown.classList.add('hidden');
                        dom.tagFilter.button.classList.remove('active');
                    });
                }

                document.addEventListener('click', e => {
                    if (!e.target.closest('.question-menu-container')) {
                        document.querySelectorAll('.question-menu-dropdown.open').forEach(d => {
                            d.classList.remove('open');
                            d.classList.add('hidden');
                        });
                    }
                    if (dom.tagFilter.container && !dom.tagFilter.container.contains(e.target)) {
                        dom.tagFilter.dropdown.classList.remove('open');
                        dom.tagFilter.dropdown.classList.add('hidden');
                        dom.tagFilter.button.classList.remove('active');
                    }
                });

                window.addEventListener('popstate', e => {
                    const popState = e.state || {};
                    const params = new URLSearchParams(window.location.search);
                    state.sortBy = popState.sortBy || params.get('sort_by') || 'latest';
                    state.filterTag = popState.filterTag || params.get('filter_tag') || '';

                    dom.sortByButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.sortby === state
                        .sortBy));
                    if (dom.tagFilter.container) {
                        dom.tagFilter.currentNameSpan.textContent = state.filterTag || 'All';
                        dom.tagFilter.list.querySelector('.active')?.classList.remove('active');
                        dom.tagFilter.list.querySelector(`[data-tag-name="${state.filterTag}"]`)?.classList.add(
                            'active');
                    }

                    fetchQuestions(popState.page || params.get('page') || 1, false);
                });

                new MutationObserver(updateIconColors).observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }

            
            function init() {
                dom.questionsListContainer = document.getElementById('questions-list-ajax-container');
                dom.sortByButtons = document.querySelectorAll('.tab-item[data-sortby]');
                dom.tagFilter.container = document.getElementById('tag-filter-container');
                if (dom.tagFilter.container) {
                    dom.tagFilter.button = document.getElementById('tag-filter-button');
                    dom.tagFilter.dropdown = document.getElementById('tag-filter-dropdown');
                    dom.tagFilter.searchInput = document.getElementById('tag-search-input');
                    dom.tagFilter.list = document.getElementById('tag-list');
                    dom.tagFilter.currentNameSpan = document.getElementById('current-tag-name');
                }

                if (!dom.questionsListContainer) {
                    console.error("Critical error: Questions container not found.");
                    return;
                }

                bindGlobalEventListeners();
                initializeAllDynamicScripts();
            }

            document.addEventListener('DOMContentLoaded', init);

        })();
    </script>
@endsection
