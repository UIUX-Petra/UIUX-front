<style>
    .navbar-container {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--border-color);
    }

    .sidebar {
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border-right: 1px solid var(--border-color);
    }

    /* Logo styling */
    .logo-text span {
        transition: color 0.3s ease;
    }

    .logo-text:hover span {
        letter-spacing: 0.02rem;
    }

    /* Navigation items */
    .nav-link {
        position: relative;
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        margin-bottom: 0.25rem;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 0;
        background-color: var(--accent-tertiary);
        opacity: 0.1;
        transition: width 0.3s ease;
    }

    .nav-link:hover::before {
        width: 100%;
    }

    .nav-link i {
        transition: transform 0.3s ease;
    }

    .nav-link:hover i {
        transform: translateX(3px);
    }

    .active-nav {
        background-color: var(--border-color);
        background-opacity: 0.1;
        color: var(--text-primary) !important;
        font-weight: 500;
    }

    .active-nav i {
        color: var(--accent-tertiary);
    }

    /* Dropdown styling */
    .user-dropdown {
        transform-origin: top right;
        transition: transform 0.2s ease, opacity 0.2s ease;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--border-color);
    }

    .user-dropdown.hidden {
        transform: scale(0.95);
        opacity: 0;
        pointer-events: none;
    }

    .user-dropdown.visible {
        transform: scale(1);
        opacity: 1;
    }

    .user-menu-item {
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .user-menu-item::before {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 1px;
        width: 0;
        background-color: var(--accent-tertiary);
        transition: width 0.3s ease;
    }

    .user-menu-item:hover::before {
        width: 100%;
    }

    /* Search bar styling */
    .search-container {
        position: relative;
        transition: all 0.3s ease;
    }

    .search-input {
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        background-color: var(--bg-card);
    }

    .search-input:focus {
        box-shadow: 0 0 0 2px var(--accent-tertiary);
    }

    .search-input.active {
        box-shadow: 0 0 0 2px var(--accent-tertiary);
    }

    .search-button {
        transition: all 0.3s ease;
    }

    /* Search dropdown styling */
    .search-dropdown {
        transition: all 0.3s ease;
        transform-origin: top;
        max-height: 0;
        overflow: hidden;
    }

    .search-dropdown.active {
        max-height: 320px;
        overflow-y: auto;
    }

    /* History item with delete button */
    .history-item {
        position: relative;
        group: hover;
    }

    .history-item:hover .delete-btn {
        opacity: 1;
        transform: scale(1);
    }

    .delete-btn {
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.2s ease;
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%) scale(0.8);
        z-index: 10;
    }

    .delete-btn:hover {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    /* Mobile search container styling */
    .search-container2 {
        position: relative;
    }

    .search-input2 {
        border: 1px solid var(--border-color);
        background-color: var(--bg-card);
        transition: all 0.3s ease;
    }

    .search-input2:focus {
        box-shadow: 0 0 0 2px var(--accent-tertiary);
    }

    /* Mobile menu styling */
    .mobile-menu {
        transition: transform 0.3s ease, opacity 0.3s ease;
        transform-origin: top;
        border-top: 1px solid var(--border-color);
    }

    .mobile-menu.hidden {
        transform: translateY(-10px);
        opacity: 0;
        pointer-events: none;
    }

    .mobile-menu.visible {
        transform: translateY(0);
        opacity: 1;
    }

    /* Avatar styling */
    .avatar-container {
        position: relative;
        transition: all 0.2s ease;
    }

    .avatar-container:hover {
        transform: scale(1.05);
    }

    .avatar {
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .avatar:hover {
        border-color: var(--accent-tertiary);
    }

    /* Ask question button styling */
    .ask-question-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        background: linear-gradient(135deg, #38A3A5, #80ED99);
    }

    .ask-question-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }

    /* Hamburger menu animation */
    .hamburger {
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .hamburger:hover {
        transform: scale(1.1);
    }

    .hamburger-line {
        transition: all 0.3s ease;
        transform-origin: center;
    }

    .hamburger.active .hamburger-line:nth-child(1) {
        transform: translateY(6px) rotate(45deg);
    }

    .hamburger.active .hamburger-line:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active .hamburger-line:nth-child(3) {
        transform: translateY(-6px) rotate(-45deg);
    }

    /* Section divisions */
    .nav-section {
        position: relative;
    }

    .nav-section::after {
        content: '';
        position: absolute;
        left: 1rem;
        right: 1rem;
        height: 1px;
        background-color: var(--border-color);
        opacity: 0.5;
    }

    /* Theme toggle button */
    .theme-toggle {
        position: relative;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background-color: var(--bg-card);
        overflow: hidden;
    }

    .theme-toggle:hover {
        transform: rotate(15deg);
    }

    .theme-toggle i {
        transition: all 0.3s ease;
    }

    /* Badge indicators */
    .nav-badge {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background-color: var(--accent-tertiary);
        color: white;
        border-radius: 10px;
        font-size: 0.7rem;
        padding: 0.1rem 0.4rem;
        opacity: 0.9;
        transition: all 0.3s ease;
    }

    .nav-link:hover .nav-badge {
        transform: translateY(-50%) scale(1.1);
    }

    #searchHistoryDropdownContainer {
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }

    #searchHistoryDropdownContainer.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .history-delete-btn {
        opacity: 1;
        transition: all 0.2s ease;
        color: var(--text-muted);
        padding: 4px;
        border-radius: 4px;
        margin-left: auto;
        transform: scale(1.5);
    }

    .history-delete-btn:hover {
        background-color: var(--bg-tertiary);
        color: #ef4444;
        transform: scale(1.7);
    }

    .suggestedItems:hover .history-delete-btn {
        opacity: 1;
    }

    .dropdown-focused {
        background-color: var(--bg-tertiary) !important;
        border-left-color: var(--accent-primary) !important;
    }

    /* Ensure mobile history container is initially hidden */
    #searchHistoryDropdownContainer2 {
        display: none;
    }

    #searchHistoryDropdownContainer2.active {
        display: block;
    }

    /* Smooth transitions for dropdown focus states */
    .suggestedItems {
        transition: all 0.2s ease;
    }

    .suggestedItems:focus-within,
    .suggestedItems.dropdown-focused {
        outline: none;
        box-shadow: 0 0 0 2px var(--accent-primary);
    }

    /* Keyboard focus styling */
    .keyboard-focused {
        background-color: var(--bg-tertiary) !important;
        border-left-color: var(--accent-primary) !important;
        outline: 2px solid var(--accent-primary);
        outline-offset: -2px;
    }

    /* Smooth transitions untuk dropdown */
    #searchHistoryDropdownContainer,
    #searchHistoryDropdownContainer2,
    #searchResultsDropdownContainer,
    #searchResultsDropdownContainer2 {
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    #searchHistoryDropdownContainer.active,
    #searchHistoryDropdownContainer2.active {
        opacity: 1;
        transform: translateY(0);
    }

    #searchHistoryDropdownContainer:not(.active),
    #searchHistoryDropdownContainer2:not(.active) {
        opacity: 0;
        transform: translateY(-10px);
        pointer-events: none;
    }

    /* Focus states untuk better accessibility */
    .suggestedItems:focus {
        outline: 2px solid var(--accent-primary);
        outline-offset: -2px;
    }

    /* Hover states yang lebih smooth */
    .suggestedItems:hover,
    .suggestedItems:focus {
        background-color: var(--bg-tertiary);
        border-left-color: var(--accent-primary);
        transition: all 0.2s ease;
    }


    .contributions-section label {
        font-weight: bold;
        display: block;
    }

    .contributions-section select {
        border-radius: 4px;
        border: 1px solid var(--border-color);
        width: 100%;
    }

    #selected-contribution-content {
        margin-top: 15px;
        padding: 10px;
        background-color: #f9f9f9;
        border: 1px solid #eee;
        border-radius: 4px;
        min-height: 50px;
    }
</style>

<!-- Navbar -->
<nav
    class="navbar-container fixed top-0 left-0 bg-[var(--bg-primary)] bg-opacity-95 w-full h-auto shadow-md text-white z-50">
    <div class="max-w-screen-xl relative flex flex-wrap items-center justify-between mx-auto p-3">
        <!-- Hamburger Button -->
        <div class="hamburger md:hidden flex flex-col justify-center space-y-1.5 p-1.5">
            <div class="hamburger-line w-6 h-0.5 bg-[var(--text-primary)] rounded-full"></div>
            <div class="hamburger-line w-6 h-0.5 bg-[var(--text-primary)] rounded-full"></div>
            <div class="hamburger-line w-6 h-0.5 bg-[var(--text-primary)] rounded-full"></div>
        </div>

        <!-- Logo Section with hover effect -->
        <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse xl:-m-6 group">
            <div class="relative overflow-hidden">
                <img id="theme-logo" src="{{ asset('assets/p2p logo - white.svg') }}" alt="Logo"
                    class="h-7 lg:h-9 w-auto theme-logo transition-all duration-300 group-hover:scale-90">
            </div>
            <div class="text-md cal-sans-regular logo-text">
                <span class="font-bold text-white transition-all">peer</span>
                <span class="font-bold text-[var(--accent-tertiary)] transition-all">- to -</span>
                <span class="font-bold text-white transition-all">peer</span>
            </div>
        </a>

        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-4 ml-auto">
            <!-- Search Input -->
            <div class="search-container flex items-center relative">
                <input autocomplete="off" id="searchInput" type="text" placeholder="Search anything..."
                    class="search-input placeholder-[var(--text-secondary)] xl:w-[56rem] w-[23rem] !bg-[var(--bg-tertiary)] px-4 py-2 rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--accent-tertiary)] text-sm">
                <button type="button" id="searchButton"
                    class="search-button text-[var(--text-dark)] bg-gradient-to-r from-[#38A3A5] to-[#80ED99] hover:bg-gradient-to-r hover:from-[#80ED99] hover:to-[#38A3A5]  px-4 py-2 -ml-10 rounded-r-lg focus:outline-none transition-all duration-300">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                <!-- History Dropdown Container -->
                <div id="searchHistoryDropdownContainer"
                    class="absolute top-full left-0 w-full bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-md mt-1 z-50 shadow-lg max-h-80 overflow-y-auto p-2">

                    <!-- History Header -->
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-[var(--border-color)]">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-[var(--text-muted)] text-xs"></i>
                            <h3 class="text-sm font-medium text-[var(--text-primary)]">Recent Searches</h3>
                        </div>
                        {{-- <span class="text-xs text-[var(--text-muted)] bg-[var(--bg-tertiary)] px-2 py-1 rounded-full">History</span> --}}
                    </div>

                    @foreach ($histories as $history => $historyItems)
                        <div class="mb-3">
                            <h4
                                class="text-xs uppercase text-[var(--text-muted)] px-2 py-1 font-semibold flex items-center gap-2">
                                @if ($history == 'user')
                                    <i class="fa-solid fa-user text-blue-400"></i>
                                @elseif($history == 'question')
                                    <i class="fa-solid fa-question-circle text-green-400"></i>
                                @else
                                    <i class="fa-solid fa-folder text-yellow-400"></i>
                                @endif
                                {{ strtoupper($history) }}S
                            </h4>
                            @foreach ($historyItems as $username => $historyItem)
                                <ul>
                                    <li>
                                        <div
                                            class="suggestedItems flex items-center px-3 py-2 hover:bg-[var(--bg-tertiary)] rounded-md text-[var(--text-primary)] text-sm transition-colors duration-200 border-l-2 border-transparent hover:border-[var(--accent-tertiary)]">
                                            <a data-id="{{ $history == 'user' ? $historyItem['email'] : $historyItem['id'] }}"
                                                data-type="{{ $history }}"
                                                href="{{ $history == 'user' ? '/viewUser/' . $historyItem['email'] : ($history == 'question' ? '/viewAnswers/' . $historyItem['id'] : 'aa') }}"
                                                class="flex items-center gap-2 flex-1">
                                                @if ($history == 'user')
                                                    <i class="fa-solid fa-user-circle text-blue-400"></i>
                                                @elseif($history == 'question')
                                                    <i class="fa-solid fa-comment-question text-green-400"></i>
                                                @endif
                                                <span>{{ $history == 'user' ? $username : ($history == 'question' ? $historyItem['title'] . ' (by ' . $username . ')' : 'aa') }}</span>
                                            </a>
                                            <button class="history-delete-btn"
                                                data-history="{{ $historyItem['historyId'] }}"
                                                data-id="{{ $history == 'user' ? $historyItem['email'] : $historyItem['id'] }}"
                                                data-type="{{ $history }}" title="Remove from history">
                                                <i class="fa-solid fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                    @endforeach

                    <!-- Clear History Option ??? -->
                    {{-- <div class="mt-3 pt-2 border-t border-[var(--border-color)]">
                        <button
                            class="text-xs text-[var(--text-muted)] hover:text-[var(--accent-primary)] flex items-center gap-1 px-2 py-1">
                            <i class="fa-solid fa-trash-can"></i>
                            Clear History
                        </button>
                    </div> --}}

                </div>


                <div id="searchResultsDropdownContainer"
                    class="absolute top-full left-0 w-full bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-md mt-1 z-50 hidden shadow-lg max-h-80 overflow-y-auto p-2">

                </div>
            </div>

            <!-- Theme Toggle with subtle animation -->
            <button id="theme-toggle" onclick="toggleTheme()" class="theme-toggle p-2 rounded-full"
                aria-label="Toggle theme">
                <i id="theme-toggle-icon" class="fa-solid fa-sun text-[var(--accent-tertiary)]"></i>
            </button>

            @if (!session()->has('email'))
                <!-- Sign Up Button -->
                <a href="{{ route('loginOrRegist') }}"
                    class="bg-[var(--accent-tertiary)] text-white font-medium rounded-lg lg:text-sm lg:px-4 lg:py-2 text-xs px-3 py-2 hover:shadow-lg transition-all duration-300 hover:bg-[var(--accent-primary)]">
                    Sign Up / Login
                </a>
            @else
                <!-- User Avatar with hover effects -->
                <div class="avatar-container relative">
                    <button type="button"
                        class="flex rounded-full bg-[var(--bg-card)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-tertiary)] focus:ring-offset-2 focus:ring-offset-[var(--bg-primary)]"
                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <img class="size-9 rounded-full avatar p-0.5 object-cover"
                            src="{{ $image ? asset('storage/' . $image) : 'https://ui-avatars.com/api/?name=' . urlencode($username ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                            alt="User avatar">
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="user-dropdown hidden absolute right-0 mt-2 w-60 bg-[var(--bg-secondary)] rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 py-1"
                        id="user-menu" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                        <div class="px-4 py-3 border-b border-[var(--border-color)]">
                            <span class="block text-sm text-[var(--text-primary)]">Hello,</span>
                            <span
                                class="block text-sm font-medium text-[var(--accent-tertiary)]">{{ $username ?? 'User' }}</span>
                        </div>
                        <div class="py-1" role="none">
                            <a href="{{ route('seeProfile') }}"
                                class="user-menu-item text-[var(--text-primary)] hover:text-[var(--accent-tertiary)] hover:bg-[var(--bg-card-hover)] block px-4 py-2 text-sm"
                                role="menuitem">
                                <i class="fa-solid fa-user mr-2"></i> Profile
                            </a>
                            <a href="{{ route('editProfile') }}"
                                class="user-menu-item text-[var(--text-primary)] hover:text-[var(--accent-tertiary)] hover:bg-[var(--bg-card-hover)] block px-4 py-2 text-sm"
                                role="menuitem">
                                <i class="fa-solid fa-gear mr-2"></i> Settings
                            </a>

                             @if(session()->has('email'))
                                @php
                                    $currentUser = app('App\Http\Controllers\UserController')->getUserByEmail(session('email'));
                                @endphp
                                <div class="border-t border-[var(--border-color)] my-1"></div>
                                <a href="{{ route('user.questions.list', ['id' => $currentUser['id'] ?? 0]) }}"
                                    class="user-menu-item text-[var(--text-primary)] hover:text-[var(--accent-tertiary)] hover:bg-[var(--bg-card-hover)] block px-4 py-2 text-sm"
                                    role="menuitem">
                                    <i class="fa-solid fa-circle-question mr-2"></i> My Questions
                                </a>
                                <a href="{{ route('user.answers.index', ['userId' => $currentUser['id'] ?? 0]) }}"
                                    class="user-menu-item text-[var(--text-primary)] hover:text-[var(--accent-tertiary)] hover:bg-[var(--bg-card-hover)] block px-4 py-2 text-sm"
                                    role="menuitem">
                                    <i class="fa-solid fa-comments mr-2"></i> My Answers
                                </a>
                                <div class="border-t border-[var(--border-color)] my-1"></div>
                            @endif
                            <a href="{{ route('logout') }}"
                                class="user-menu-item text-[var(--text-primary)] hover:text-red-400 hover:bg-[var(--bg-card-hover)] block px-4 py-2 text-sm"
                                role="menuitem">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i> Sign out
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Mobile User Navigation -->
        @if (!session()->has('email'))
            <!-- Mobile Sign Up Button -->
            <a href="{{ route('loginOrRegist') }}"
                class="md:hidden text-white bg-[var(--accent-tertiary)] hover:bg-[var(--accent-primary)] font-medium rounded-lg text-xs px-3 py-1.5 transition-all duration-300">
                Sign Up
            </a>
        @else
            <!-- Mobile User Menu -->
            <div class="md:hidden relative">
                <button type="button" class="relative flex rounded-full bg-[var(--bg-card)] text-sm focus:outline-none"
                    id="mobile-user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="sr-only">Open user menu</span>
                    <img class="size-8 rounded-full p-0.5 border border-[var(--accent-tertiary)]"
                        src="{{ $image ? asset('storage/' . $image) : 'https://ui-avatars.com/api/?name=' . urlencode($username ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                        alt="User avatar">
                </button>

                <!-- Mobile Dropdown Menu handled by JS -->
            </div>
        @endif
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="mobile-menu hidden flex-col gap-1 p-4 md:hidden bg-[var(--bg-secondary)] text-[var(--text-primary)] w-full border-t border-[var(--border-color)]">

        <div class="px-2 pb-3 search-container2">
            <input id="searchInput2" type="text" placeholder="Search..." autocomplete="off"
                class="search-input2 w-full px-4 py-2 rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--accent-tertiary)] text-sm">

            <div id="searchHistoryDropdownContainer2"
                class="absolute top-full left-0 w-full bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-md mt-1 z-50 shadow-lg max-h-80 overflow-y-auto p-2">

                <!-- History Header -->
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-[var(--border-color)]">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-[var(--text-muted)] text-xs"></i>
                        <h3 class="text-sm font-medium text-[var(--text-primary)]">Recent Searches</h3>
                    </div>
                    {{-- <span class="text-xs text-[var(--text-muted)] bg-[var(--bg-tertiary)] px-2 py-1 rounded-full">History</span> --}}
                </div>

                @foreach ($histories as $history => $historyItems)
                    <div class="mb-3">
                        <h4
                            class="text-xs uppercase text-[var(--text-muted)] px-2 py-1 font-semibold flex items-center gap-2">
                            @if ($history == 'user')
                                <i class="fa-solid fa-user text-blue-400"></i>
                            @elseif($history == 'question')
                                <i class="fa-solid fa-question-circle text-green-400"></i>
                            @else
                                <i class="fa-solid fa-folder text-yellow-400"></i>
                            @endif
                            {{ strtoupper($history) }}S
                        </h4>
                        @foreach ($historyItems as $username => $historyItem)
                            <ul>
                                <li>
                                    <div
                                        class="suggestedItems flex items-center px-3 py-2 hover:bg-[var(--bg-tertiary)] rounded-md text-[var(--text-primary)] text-sm transition-colors duration-200 border-l-2 border-transparent hover:border-[var(--accent-tertiary)]">
                                        <a data-id="{{ $history == 'user' ? $historyItem['email'] : $historyItem['id'] }}"
                                            data-type="{{ $history }}"
                                            href="{{ $history == 'user' ? '/viewUser/' . $historyItem['email'] : ($history == 'question' ? '/viewAnswers/' . $historyItem['id'] : 'aa') }}"
                                            class="flex items-center gap-2 flex-1">
                                            @if ($history == 'user')
                                                <i class="fa-solid fa-user-circle text-blue-400"></i>
                                            @elseif($history == 'question')
                                                <i class="fa-solid fa-comment-question text-green-400"></i>
                                            @endif
                                            <span>{{ $history == 'user' ? $username : ($history == 'question' ? $historyItem['title'] . ' (by ' . $username . ')' : 'aa') }}</span>
                                        </a>
                                        <button class="history-delete-btn"
                                            data-history="{{ $historyItem['historyId'] }}"
                                            data-id="{{ $history == 'user' ? $historyItem['email'] : $historyItem['id'] }}"
                                            data-type="{{ $history }}" title="Remove from history">
                                            <i class="fa-solid fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                @endforeach

                <!-- Clear History Option ??? -->
                {{-- <div class="mt-3 pt-2 border-t border-[var(--border-color)]">
                    <button
                        class="text-xs text-[var(--text-muted)] hover:text-[var(--accent-primary)] flex items-center gap-1 px-2 py-1">
                        <i class="fa-solid fa-trash-can"></i>
                        Clear History
                    </button>
                </div> --}}

            </div>

            <!-- Mobile Search Results Container -->
            <div id="searchResultsDropdownContainer2"
                class="w-full bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-md mt-1 z-50 hidden shadow-lg max-h-80 overflow-y-auto p-2">
            </div>
        </div>
        <div class="contributions-section mb-1">
            <select id="my-contributions-dropdown-mobile" name="contributions"
                class="w-full text-sm font-medium text-[var(--text-primary)] bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-md focus:outline-none focus:ring-1 focus:ring-[var(--accent-tertiary)] appearance-none py-2.5 px-3">
                <option value="" class="text-[var(--text-muted)]">My Activities</option>
                <option value="questions">Questions</option>
                <option value="answers">Answers</option>
                <option value="saves_general">Saves</option>
            </select>
        </div>
        <a href="{{ route('askPage') }}"
            class="nav-link flex items-center px-3 py-2 rounded-md bg-gradient-to-r from-[#38A3A5] to-[#80ED99] {{ request()->routeIs('askPage') ? 'active-nav' : '' }}">
            <i class="fa-solid fa-question-circle mr-3"></i> Ask a Question
        </a>
        <a href="{{ route('home') }}"
            class="nav-link flex items-center px-3 py-2 rounded-md {{ request()->routeIs('home') ? 'active-nav' : '' }}">
            <i class="fa-solid fa-house mr-3"></i> Home
        </a>
        <a href="{{ route('popular') }}"
            class="nav-link flex items-center px-3 py-2 rounded-md {{ request()->routeIs('popular') ? 'active-nav' : '' }}">
            <i class="fa-solid fa-fire mr-3"></i> Popular
            <span class="nav-badge">Hot</span>
        </a>
        <a href="{{ route('viewAllTags') }}"
            class="nav-link flex items-center px-3 py-2 rounded-md {{ request()->routeIs('viewAllTags') ? 'active-nav' : '' }}">
            <i class="fa-solid fa-tags mr-3"></i> Subjects
        </a>
        <a href="{{ route('viewAllUsers') }}"
            class="nav-link flex items-center px-3 py-2 rounded-md {{ request()->routeIs('viewAllUsers') ? 'active-nav' : '' }}">
            <i class="fa-solid fa-users mr-3"></i> Informates
        </a>

        <a href="{{ route('user.leaderboard') }}"
            class="nav-link flex items-center px-3 py-2 rounded-md {{ request()->routeIs('user.leaderboard') ? 'active-nav' : '' }}">
            <i class="fa-solid fa-trophy mr-3"></i> Leaderboard
        </a>

        <div class="border-t border-[var(--border-color)] my-2"></div>

        <div class="flex items-center justify-between p-2">
            <button id="mobile-theme-toggle" onclick="toggleTheme()" class="theme-toggle p-2"
                aria-label="Toggle theme">
                <i id="mobile-theme-toggle-icon" class="fa-solid fa-sun"></i>
            </button>

            @if (session()->has('email'))
                <div class="flex space-x-3">
                    <a href="{{ route('seeProfile') }}"
                        class="text-[var(--text-primary)] hover:text-[var(--accent-tertiary)]">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <a href="{{ route('editProfile') }}"
                        class="text-[var(--text-primary)] hover:text-[var(--accent-tertiary)]">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                    <a href="{{ route('logout') }}" class="text-[var(--text-primary)] hover:text-red-400">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div
    class="hidden md:flex flex-col lg:w-[20rem] w-64 h-screen lg:pl-28 pl-6 pr-2 py-6 shadow-md fixed top-0 left-0 z-[10] bg-[var(--bg-primary)] bg-opacity-95 sidebar">
    <!-- Sidebar content -->
    <div class="flex flex-col h-full mt-16">
        <!-- Main Navigation Section -->
        <div class="mb-8 nav-section pb-6">
            <h3 class="text-[var(--text-muted)] text-xs uppercase tracking-wider ml-3 mb-3">Main Navigation</h3>
            <nav class="flex flex-col space-y-1">
                <nav class="flex flex-col space-y-1">
                    <div class="contributions-section mb-1">
                        <select id="my-contributions-dropdown-desktop" name="contributions"
                            class="w-full text-sm font-medium text-[var(--text-primary)] bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-md focus:outline-none focus:ring-1 focus:ring-[var(--accent-tertiary)] appearance-none py-2.5 px-3">
                            <option value="" class="text-[var(--text-muted)]">My Contributions</option>
                            <option value="questions">Questions</option>
                            <option value="answers">Answers</option>
                            <option value="saves_general">Saves</option>
                        </select>
                    </div>
                    <a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active-nav' : '' }} text-[var(--text-primary)] py-2.5 text-sm pl-3 rounded-md flex items-center font-medium">
                        <i class="fa-solid fa-house mr-3 w-5 text-center"></i> Home
                    </a>
                    <a href="{{ route('popular') }}"
                        class="nav-link {{ request()->routeIs('popular') ? 'active-nav' : '' }} text-[var(--text-primary)] py-2.5 text-sm pl-3 rounded-md flex items-center font-medium">
                        <i class="fa-solid fa-fire mr-3 w-5 text-center"></i> Popular
                        <span class="nav-badge">Hot</span>
                    </a>
                    <a href="{{ route('viewAllTags') }}"
                        class="nav-link {{ request()->routeIs('viewAllTags') ? 'active-nav' : '' }} text-[var(--text-primary)] py-2.5 text-sm pl-3 rounded-md flex items-center font-medium">
                        <i class="fa-solid fa-tags mr-3 w-5 text-center"></i> Subjects
                    </a>
                    {{-- <a href="{{ route('savedQuestions') }}"
                    class="nav-link {{ request()->routeIs('savedQuestions') ? 'active-nav' : '' }} text-[var(--text-primary)] py-2.5 text-sm pl-3 rounded-md flex items-center font-medium">
                    <i class="fa-solid fa-bookmark mr-3 w-5 text-center"></i> Saves
                </a> --}}
                </nav>
        </div>

        <!-- Community Section -->
        <div class="mb-8 nav-section pb-6">
            <h3 class="text-[var(--text-muted)] text-xs uppercase tracking-wider ml-3 mb-3">Community</h3>
            <nav class="flex flex-col space-y-1">
                <a href="{{ route('viewAllUsers') }}"
                    class="nav-link {{ request()->routeIs('viewAllUsers') ? 'active-nav' : '' }} text-[var(--text-primary)] py-2.5 text-sm pl-3 rounded-md flex items-center font-medium">
                    <i class="fa-solid fa-users mr-3 w-5 text-center"></i> Informates
                </a>
                <a href="{{ route('user.leaderboard') }}"
                    class="nav-link {{ request()->routeIs('user.leaderboard') ? 'active-nav' : '' }} text-[var(--text-primary)] py-2.5 text-sm pl-3 rounded-md flex items-center font-medium">
                    <i class="fa-solid fa-trophy mr-3 w-5 text-center"></i> Leaderboard
                </a>
            </nav>
        </div>

        <div class="mb-8 nav-section pb-6">
            <h3 class="text-[var(--text-muted)] text-xs uppercase tracking-wider ml-3 mb-3">Actions</h3>
            <a href="{{ route('askPage') }}"
                class="nav-link flex items-center px-3 py-2 rounded-md bg-gradient-to-r from-[#38A3A5] to-[#80ED99] {{ request()->routeIs('askPage') ? 'active-nav' : '' }}">
                <i class="fa-solid fa-plus mr-3"></i> Ask a Question
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializePageFunctions();
        const contributionsDropdown = document.getElementById('my-contributions-dropdown');
        const userId = {!! isset($id) && $id ? json_encode($id) : "'null'" !!};

        function getContributionTypeFromUrl() {
            const path = window.location.pathname;
            if (userId && userId !== 'null') {
                const cleanUserId = userId.replace(/"/g, '');
                if (path.includes(`/user/${cleanUserId}/questions`)) return 'questions';
                if (path.includes(`/user/${cleanUserId}/answers`)) return 'answers';
            }
            const savedQuestionsPath = "{{ route('savedQuestions') }}";
            if (path.includes(savedQuestionsPath) && savedQuestionsPath !== "{{ url('/') }}")
            return 'saves_general';
            return '';
        }

        function setupContributionsDropdown(dropdownElement) {
            if (!dropdownElement) {
                // console.log('Dropdown element not found:', dropdownElement); // Untuk debugging jika salah satu tidak ada
                return;
            }

            const currentType = getContributionTypeFromUrl();
            if (currentType) {
                const optionExists = dropdownElement.querySelector(`option[value="${currentType}"]`);
                if (optionExists) {
                    dropdownElement.value = currentType;
                } else {
                    dropdownElement.value = "";
                }
            } else {
                dropdownElement.value = "";
            }

            dropdownElement.addEventListener('change', function() {
                const selectedValue = this.value;
                let targetUrl = null;
                const cleanUserId = userId ? userId.replace(/"/g, '') : null;

                if (selectedValue === "questions") {
                    if (cleanUserId && cleanUserId !== 'null') {
                        targetUrl = `/user/${cleanUserId}/questions`;
                    } else {
                        console.error("User ID tidak tersedia untuk 'My Questions'.");
                    }
                } else if (selectedValue === "answers") {
                    if (cleanUserId && cleanUserId !== 'null') {
                        targetUrl = `/user/${cleanUserId}/answers`;
                    } else {
                        console.error("User ID tidak tersedia untuk 'My Answers'.");
                    }
                } else if (selectedValue === "saves_general") {
                    targetUrl = "{{ route('savedQuestions') }}";
                }

                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            });
            // console.log('Setup complete for:', dropdownElement.id); // Debugging
        }

        // Inisialisasi untuk kedua dropdown
        const desktopDropdown = document.getElementById('my-contributions-dropdown-desktop');
        const mobileDropdown = document.getElementById('my-contributions-dropdown-mobile');

        setupContributionsDropdown(desktopDropdown);
        setupContributionsDropdown(mobileDropdown);

        const hamburger = document.querySelector('.hamburger');
        const mobileMenu = document.getElementById('mobile-menu');

        if (hamburger && mobileMenu) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('visible');
            });
        }

        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        if (userMenuButton && userMenu) {
            let menuTimeout;

            userMenuButton.addEventListener('mouseenter', () => {
                clearTimeout(menuTimeout);
                userMenu.classList.remove('hidden');
                userMenu.classList.add('visible');
            });

            userMenuButton.addEventListener('mouseleave', () => {
                menuTimeout = setTimeout(() => {
                    if (!userMenu.matches(':hover')) {
                        userMenu.classList.remove('visible');
                        userMenu.classList.add('hidden');
                    }
                }, 200);
            });

            userMenu.addEventListener('mouseenter', () => {
                clearTimeout(menuTimeout);
            });

            userMenu.addEventListener('mouseleave', () => {
                userMenu.classList.remove('visible');
                userMenu.classList.add('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.remove('visible');
                    userMenu.classList.add('hidden');
                }
            });
        }

        const mobileUserMenuButton = document.getElementById('mobile-user-menu-button');
        if (mobileUserMenuButton && mobileMenu && hamburger) {
            mobileUserMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('visible');
                hamburger.classList.toggle('active');
            });
        }

        // Navigation links animation
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            const icon = link.querySelector('i');
            if (icon) {
                link.addEventListener('mouseenter', () => {
                    icon.style.transform = 'translateX(3px)';
                });

                link.addEventListener('mouseleave', () => {
                    icon.style.transform = 'translateX(0)';
                });
            }
        });


        class SearchComponent {
            constructor(config) {
                this.inputId = config.inputId;
                this.containerId = config.containerId;
                this.resultsId = config.resultsId;
                this.historyId = config.historyId;
                this.buttonId = config.buttonId;
                this.trackHistory = config.trackHistory || false;
                this.isHistoryVisible = false;

                this.input = document.getElementById(this.inputId);
                this.container = this.input ? this.input.closest(`.${this.containerId}`) : null;
                this.resultsContainer = document.getElementById(this.resultsId);
                this.historyContainer = document.getElementById(this.historyId);

                this.removeBtn = this.historyContainer.closest('.history-delete-btn');
                this.button = document.getElementById(this.buttonId);

                this.debounceTimer = null;
                this.API_BASE_URL = "{{ env('API_URL', 'http://default-api-url.com/api') }}";

                this.init();
            }

            init() {
                if (!this.input || !this.container || !this.resultsContainer) {
                    console.error(`Search component initialization failed for ${this.inputId}`);
                    return;
                }

                this.setupEventListeners();
                this.makeAccessible();
            }

            setupEventListeners() {
                // Focus event - toggle history visibility
                this.input.addEventListener('focus', () => {
                    if (this.input.value.trim() === '' && this.historyContainer) {
                        this.showHistory();
                    }
                });

                // Input event - handle search
                this.input.addEventListener('input', (e) => {
                    const searchTerm = e.target.value.trim();
                    clearTimeout(this.debounceTimer);

                    if (searchTerm.length === 0) {
                        if (this.historyContainer) {
                            this.showHistory();
                        } else {
                            this.hideResults();
                        }
                        return;
                    }

                    // Hide history and show search results
                    if (this.historyContainer) {
                        this.hideHistory();
                    }

                    this.debounceTimer = setTimeout(() => {
                        this.fetchSearchSuggestions(searchTerm).then(apiResults => {
                            this.displaySuggestions(apiResults);
                        });
                    }, 300);
                });

                // Enter key - perform full search
                this.input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.performFullSearch(this.input.value.trim());
                        this.hideAllContainers();
                    }
                });

                // Search button click
                if (this.button) {
                    this.button.addEventListener('click', () => {
                        this.performFullSearch(this.input.value.trim());
                        this.hideAllContainers();
                    });
                }

                // Click outside to hide
                document.addEventListener('click', (event) => {
                    // Cek apakah click terjadi di luar semua komponen search
                    const isOutsideContainer = !this.container.contains(event.target);
                    const isOutsideResults = !this.resultsContainer || !this.resultsContainer
                        .contains(event.target);
                    const isOutsideHistory = !this.historyContainer || !this.historyContainer
                        .contains(event.target);

                    if (isOutsideContainer && isOutsideResults && isOutsideHistory) {
                        this.hideAllContainers();
                    }
                });

                this.handleKeyboardNavigation();
            }
            showHistory() {
                if (this.historyContainer) {
                    this.historyContainer.classList.add('active');
                    this.isHistoryVisible = true;
                    if (this.resultsContainer) {
                        this.resultsContainer.classList.add('hidden');
                    }

                    // Pastikan history container bisa menerima focus untuk keyboard navigation
                    if (!this.historyContainer.hasAttribute('tabindex')) {
                        this.historyContainer.setAttribute('tabindex', '-1');
                    }
                }
            }
            hideHistory() {
                if (this.historyContainer) {
                    this.historyContainer.classList.remove('active');
                    this.isHistoryVisible = false;
                }
            }
            makeAccessible() {
                if (this.input) {
                    this.input.setAttribute('aria-expanded', 'false');
                    this.input.setAttribute('aria-haspopup', 'listbox');
                    this.input.setAttribute('role', 'combobox');
                }

                if (this.resultsContainer) {
                    this.resultsContainer.setAttribute('role', 'listbox');
                    this.resultsContainer.setAttribute('aria-label', 'Search suggestions');
                }

                if (this.historyContainer) {
                    this.historyContainer.setAttribute('role', 'listbox');
                    this.historyContainer.setAttribute('aria-label', 'Search history');
                }
            }
            handleArrowKeys() {
                this.input.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                        e.preventDefault();

                        const activeContainer = this.isHistoryVisible ? this.historyContainer : this
                            .resultsContainer;
                        if (!activeContainer || activeContainer.classList.contains('hidden'))
                            return;

                        const items = activeContainer.querySelectorAll('.suggestedItems');
                        if (items.length === 0) return;

                        const currentFocus = activeContainer.querySelector(
                                '.suggestedItems:focus') ||
                            activeContainer.querySelector('.suggestedItems.keyboard-focused');

                        let nextIndex = 0;

                        if (currentFocus) {
                            const currentIndex = Array.from(items).indexOf(currentFocus);
                            if (e.key === 'ArrowDown') {
                                nextIndex = (currentIndex + 1) % items.length;
                            } else {
                                nextIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                            }

                            // Remove previous focus styling
                            currentFocus.classList.remove('keyboard-focused');
                        }

                        // Add focus styling to next item
                        items[nextIndex].classList.add('keyboard-focused');
                        items[nextIndex].focus();
                    }

                    if (e.key === 'Enter') {
                        const focusedItem = (this.isHistoryVisible ? this.historyContainer : this
                                .resultsContainer)
                            ?.querySelector(
                                '.suggestedItems:focus, .suggestedItems.keyboard-focused');

                        if (focusedItem) {
                            e.preventDefault();
                            focusedItem.click();
                        }
                    }
                });
            }
            handleKeyboardNavigation() {
                // Track focus state untuk container
                let containerHasFocus = false;

                // Monitor semua elemen dalam container untuk focus/blur
                const focusableElements = this.container.querySelectorAll('input, button, a, [tabindex]');

                focusableElements.forEach(element => {
                    element.addEventListener('focus', () => {
                        containerHasFocus = true;
                    });

                    // element.addEventListener('blur', (e) => {
                    //     setTimeout(() => {
                    //         const activeElement = document.activeElement;
                    //         const isStillInContainer = this.container.contains(
                    //             activeElement);

                    //         if (!isStillInContainer) {
                    //             containerHasFocus = false;
                    //             this.hideAllContainers();
                    //         }
                    //     }, 100);
                    // });
                });

                // Handle keyboard events
                document.addEventListener('keydown', (e) => {
                    // ESC key untuk menutup dropdown
                    if (e.key === 'Escape') {
                        this.hideAllContainers();
                        this.input.blur();
                    }

                    // Tab key navigation
                    if (e.key === 'Tab') {
                        setTimeout(() => {
                            const activeElement = document.activeElement;
                            const isInContainer = this.container.contains(activeElement) ||
                                (this.resultsContainer && this.resultsContainer.contains(
                                    activeElement)) ||
                                (this.historyContainer && this.historyContainer.contains(
                                    activeElement));

                            if (!isInContainer) {
                                this.hideAllContainers();
                            }
                        }, 10);
                    }
                });
            }

            toggleContainers(showHistory = true) {
                if (!this.historyContainer || !this.resultsContainer) return;

                if (showHistory) {
                    this.showHistory();
                } else {
                    this.hideHistory();
                    this.resultsContainer.classList.remove('hidden');
                }
            }

            hideResults() {
                if (this.resultsContainer) {
                    this.resultsContainer.innerHTML = '';
                    this.resultsContainer.classList.add('hidden');
                }
            }

            hideAllContainers() {
                this.hideHistory();
                if (this.resultsContainer) {
                    this.resultsContainer.classList.add('hidden');
                }
                // Reset input focus state jika diperlukan
                this.isHistoryVisible = false;
            }

            async fetchSearchSuggestions(query) {
                if (query.length < 1) {
                    this.hideResults();
                    return null;
                }

                try {
                    if (this.resultsContainer) {
                        this.resultsContainer.innerHTML = `
                        <div class="flex items-center gap-2 p-3 text-sm text-[var(--text-muted)]">
                            <i class="fa-solid fa-spinner fa-spin text-[var(--accent-primary)]"></i>
                            <span>Searching for "${query}"...</span>
                        </div>
                    `;
                        this.resultsContainer.classList.remove('hidden');
                    }

                    const response = await fetch(
                        `${this.API_BASE_URL}/search?q=${encodeURIComponent(query)}&context=all&limit=5`
                    );

                    if (!response.ok) {
                        console.error('API search error:', response.status, await response.text());
                        if (this.resultsContainer) {
                            this.resultsContainer.innerHTML = `
                            <div class="flex items-center gap-2 p-3 text-sm text-red-500">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                                <span>Error fetching results.</span>
                            </div>
                        `;
                        }
                        return null;
                    }

                    const result = await response.json();
                    return (result.success && result.data) ? result.data : null;
                } catch (error) {
                    console.error('Failed to fetch search suggestions:', error);
                    if (this.resultsContainer) {
                        this.resultsContainer.innerHTML = `
                        <div class="flex items-center gap-2 p-3 text-sm text-red-500">
                            <i class="fa-solid fa-wifi-slash"></i>
                            <span>Search request failed.</span>
                        </div>
                    `;
                    }
                    return null;
                }
            }

            displaySuggestions(categorizedResults) {
                if (!this.resultsContainer) return;

                // Clear and add header for search results
                this.resultsContainer.innerHTML = `
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-[var(--border-color)]">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-[var(--accent-primary)] text-xs"></i>
                        <h3 class="text-sm font-medium text-[var(--text-primary)]">Search Results</h3>
                    </div>
                </div>
            `;

                if (!categorizedResults || Object.keys(categorizedResults).length === 0) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'flex flex-col items-center gap-2 p-6 text-center';
                    noResultsDiv.innerHTML = `
                    <i class="fa-solid fa-search text-3xl text-[var(--text-muted)] opacity-50"></i>
                    <p class="text-sm text-[var(--text-muted)]">No matches found.</p>
                    <p class="text-xs text-[var(--text-muted)]">Try different keywords or check spelling</p>
                `;
                    this.resultsContainer.appendChild(noResultsDiv);
                    return;
                }

                let hasContent = false;
                const categoryOrder = ['questions', 'subjects', 'users'];
                const categoryIcons = {
                    'questions': 'fa-comment-question',
                    'subjects': 'fa-folder',
                    'users': 'fa-user'
                };
                const categoryColors = {
                    'questions': 'text-green-400',
                    'subjects': 'text-yellow-400',
                    'users': 'text-blue-400'
                };

                categoryOrder.forEach(categoryKey => {
                    if (categorizedResults[categoryKey] && categorizedResults[categoryKey].length >
                        0) {
                        hasContent = true;
                        const items = categorizedResults[categoryKey];

                        const sectionDiv = document.createElement('div');
                        sectionDiv.className = 'mb-3';

                        const title = document.createElement('h4');
                        title.className =
                            'text-xs uppercase text-[var(--text-muted)] px-2 py-1 font-semibold flex items-center gap-2';
                        title.innerHTML = `
                        <i class="fa-solid ${categoryIcons[categoryKey]} ${categoryColors[categoryKey]}"></i>
                        ${categoryKey.charAt(0).toUpperCase() + categoryKey.slice(1)}
                    `;
                        sectionDiv.appendChild(title);

                        const ul = document.createElement('ul');
                        items.forEach(item => {
                            const li = document.createElement('li');
                            let displayText = this.getDisplayText(item);

                            li.innerHTML = `
                            <a data-id="${item.id}" 
                               data-type="${item.type}" 
                               href="${item.url}" 
                               class="suggestedItems block px-3 py-2 hover:bg-[var(--bg-tertiary)] rounded-md text-[var(--text-primary)] text-sm transition-colors duration-200 border-l-2 border-transparent hover:border-[var(--accent-primary)]">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid ${categoryIcons[categoryKey]} ${categoryColors[categoryKey]}"></i>
                                    <span>${displayText}</span>
                                </div>
                            </a>
                        `;
                            ul.appendChild(li);
                        });

                        sectionDiv.appendChild(ul);
                        this.resultsContainer.appendChild(sectionDiv);
                    }
                });

                // Add click handlers for suggestions
                this.addSuggestionClickHandlers();

                if (!hasContent) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'flex flex-col items-center gap-2 p-6 text-center';
                    noResultsDiv.innerHTML = `
                    <i class="fa-solid fa-search text-3xl text-[var(--text-muted)] opacity-50"></i>
                    <p class="text-sm text-[var(--text-muted)]">No matches found.</p>
                `;
                    this.resultsContainer.appendChild(noResultsDiv);
                }
            }

            getDisplayText(item) {
                let displayText = '';
                if (item.type === 'question') {
                    displayText = item.title;
                    if (item.author_username) displayText += ` (by ${item.author_username})`;
                } else if (item.type === 'subject') {
                    displayText = item.name;
                } else if (item.type === 'user') {
                    displayText = item.username;
                    if (item.name && item.name !== item.username) displayText += ` (${item.name})`;
                } else {
                    displayText = item.title || item.name || item.username || 'Unknown item';
                }
                return displayText;
            }

            addSuggestionClickHandlers() {
                // Only add handlers for items in this specific container
                const suggestions = this.resultsContainer.querySelectorAll('.suggestedItems');

                suggestions.forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const href = link.href;
                        const searchedId = link.dataset.id;
                        const searchedType = link.dataset.type;

                        // Track history if enabled (desktop version)
                        if (this.trackHistory) {
                            fetch(`{{ route('nembakHistory', ['searchedId' => 'aaa']) }}`
                                .replace('aaa', searchedId), {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    },
                                    body: JSON.stringify({
                                        type: searchedType,
                                    })
                                });
                        }

                        setTimeout(() => {
                            window.location.href = href;
                        }, 100);
                    });
                });
            }

            performFullSearch(query) {
                if (query) {
                    window.location.href = `/search-results?q=${encodeURIComponent(query)}`;
                }
            }
        }

        // Initialize desktop search component
        const desktopSearch = new SearchComponent({
            inputId: 'searchInput',
            containerId: 'search-container',
            resultsId: 'searchResultsDropdownContainer',
            historyId: 'searchHistoryDropdownContainer',
            buttonId: 'searchButton',
            trackHistory: true // Desktop version tracks history
        });

        // Initialize mobile search component
        const mobileSearch = new SearchComponent({
            inputId: 'searchInput2',
            containerId: 'search-container2',
            resultsId: 'searchResultsDropdownContainer2',
            historyId: 'searchHistoryDropdownContainer2',
            buttonId: 'searchButton2',
            trackHistory: true
        });

        // Add existing history click handlers for desktop
        const existingHistoryItems = document.querySelectorAll(
            '#searchHistoryDropdownContainer .suggestedItems');
        existingHistoryItems.forEach(links => {
            let link = links.querySelector('a');
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const href = link.href;
                const searchedId = link.dataset.id;
                const searchedType = link.dataset.type;

                // fetch(`{{ route('nembakHistory', ['searchedId' => 'aaa']) }}`.replace('aaa',
                //     searchedId), {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         "X-CSRF-TOKEN": "{{ csrf_token() }}",
                //     },
                //     body: JSON.stringify({
                //         type: searchedType,
                //     })
                // });

                // TAMBAHKAN ROUTE UNTUK UPDATE UPDATED_AT HISTORY ID, atau mau tetep nambah history ya meskipun content e sama ???
                setTimeout(() => {
                    window.location.href = href;
                }, 100);
            });
        });


        let delBtns = document.querySelectorAll('.history-delete-btn');

        delBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const historyId = btn.dataset.history;

                // Elemen UL utama
                const ulH = btn.parentElement.parentElement.parentElement;
                ulH.classList.add('hidden');

                const ulParentContainer = ulH.parentElement;
                const ulHasSibling = Array.from(ulParentContainer.children)
                    .filter(child => !child.classList.contains(
                        'hidden'))
                    .length > 1;

                if (!ulHasSibling) {
                    ulParentContainer.classList.add('hidden');
                }

                // Temukan semua tombol dengan data-history yang sama (perangkat lain)
                const otherDeviceBtns = document.querySelectorAll(
                    `.history-delete-btn[data-history="${historyId}"]`);

                otherDeviceBtns.forEach(otherBtn => {
                    if (otherBtn !== btn) {
                        const otherUl = otherBtn.parentElement.parentElement
                            .parentElement;
                        otherUl.classList.add('hidden');

                        const otherUlParent = otherUl.parentElement;
                        const otherHasSibling = Array.from(otherUlParent.children)
                            .filter(child => !child.classList.contains(
                                'hidden'))
                            .length > 1;

                        if (!otherHasSibling) {
                            otherUlParent.classList.add('hidden');
                        }
                    }
                });

                // Kirim permintaan hapus ke server
                const formDel = new FormData();
                formDel.append('id', historyId);

                fetch(`{{ route('deleteHistory') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: formDel,
                    })
                    .then(response => response.json())
                    .then(res => {
                        Swal.close();

                        if (res.success) {
                            ulH.remove();

                            if (!ulHasSibling) {
                                ulParentContainer.remove();
                            }

                            otherDeviceBtns.forEach(otherBtn => {
                                if (otherBtn !== btn) {
                                    const otherUl = otherBtn.parentElement
                                        .parentElement.parentElement;
                                    const otherParent = otherUl.parentElement;

                                    const otherHasSibling = otherParent.children
                                        .length > 1;

                                    if (!otherHasSibling) {
                                        otherParent.classList.add('hidden');
                                    }
                                }
                            });
                        } else {
                            ulH.classList.remove('hidden');
                            ulParentContainer.classList.remove('hidden');

                            Toastify({
                                text: `Failed to remove this Search History.`,
                                duration: 7000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#e74c3c"
                                }
                            }).showToast();
                        }
                    })
                    .catch(() => {
                        ulH.classList.remove('hidden');
                        ulParentContainer.classList.remove('hidden');

                        Toastify({
                            text: `Network error while deleting history.`,
                            duration: 7000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#e67e22"
                            }
                        }).showToast();
                    });
            });
        });
        const themeObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    updateThemeIcons();
                    updateIconColors();
                    // updateSavedIcons();
                }
            });
        });
        themeObserver.observe(document.documentElement, {
            attributes: true
        });

        function initializePageFunctions() {
            updateThemeIcons();
            updateIconColors();
            // lazyLoadImages();
            // initSmoothScroll();
            // initSaveButtons();
            // updateSavedIcons();
        }

        function updateThemeIcons() {
            const isLightMode = document.documentElement.classList.contains('light-mode');
            const themeToggleIcon = document.getElementById('theme-toggle-icon');
            const mobileThemeToggleIcon = document.getElementById('mobile-theme-toggle-icon');
            const themeLogoToggle = document.getElementById('theme-logo');

            if (themeToggleIcon) themeToggleIcon.className = isLightMode ? 'fa-solid fa-moon' :
                'fa-solid fa-sun';
            if (mobileThemeToggleIcon) mobileThemeToggleIcon.className = isLightMode ? 'fa-solid fa-moon' :
                'fa-solid fa-sun';
            if (themeLogoToggle) themeLogoToggle.src = isLightMode ? "{{ asset('assets/p2p logo.svg') }}" :
                "{{ asset('assets/p2p logo - white.svg') }}";
        }

        const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
        if (mobileThemeToggle && typeof toggleTheme === 'function') {
            mobileThemeToggle.addEventListener('click', toggleTheme);
        }

        function updateIconColors() {
            const statsItems = document.querySelectorAll('.stats-item');
            const isLightMode = document.documentElement.classList.contains('light-mode');
            if (statsItems) {
                statsItems.forEach((item, index) => {
                    const icon = item.querySelector('i');
                    if (!icon) return;
                    if (index % 3 === 0) {
                        icon.style.color = isLightMode ? '#10b981' : '#23BF7F';
                    } else if (index % 3 === 1) {
                        icon.style.color = isLightMode ? '#f59e0b' : '#ffd249';
                    } else {
                        icon.style.color = isLightMode ? '#3b82f6' : '#909ed5';
                    }
                });
            }
        }

    });
</script>
