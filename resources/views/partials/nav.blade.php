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

    .search-button {
        transition: all 0.3s ease;
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
            <div class="search-container flex items-center">
                <input type="text" placeholder="Search topics, questions, users..."
                    class="search-input placeholder-[var(--text-secondary)] xl:w-[56rem] w-[23rem] !bg-[var(--bg-tertiary)] px-4 py-2 rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--accent-tertiary)] text-sm">
                <button type="submit"
                    class="search-button text-[var(--text-dark)] bg-[var(--accent-tertiary)] hover:bg-[var(--accent-primary)] px-4 py-2 -ml-10 rounded-r-lg focus:outline-none transition-all duration-300">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <!-- Theme Toggle with subtle animation -->
            <button id="theme-toggle" onclick="toggleTheme()" class="theme-toggle p-2 rounded-full"
                aria-label="Toggle theme">
                <i id="theme-toggle-icon" class="fa-solid fa-sun"></i>
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
                        src="{{ $image ? asset('storage/' . $image) : 'https://via.placeholder.com/150' }}"
                        alt="User avatar">
                </button>

                <!-- Mobile Dropdown Menu handled by JS -->
            </div>
        @endif
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="mobile-menu hidden flex-col gap-1 p-4 md:hidden bg-[var(--bg-secondary)] text-[var(--text-primary)] w-full border-t border-[var(--border-color)]">
        <div class="px-2 pb-3">
            <input type="text" placeholder="Search..."
                class="search-input w-full px-4 py-2 rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--accent-tertiary)] text-sm">
        </div>

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
            <i class="fa-solid fa-tags mr-3"></i> Tags
        </a>
        <a href="{{ route('viewAllUsers') }}"
            class="nav-link flex items-center px-3 py-2 rounded-md {{ request()->routeIs('viewAllUsers') ? 'active-nav' : '' }}">
            <i class="fa-solid fa-users mr-3"></i> Informates
        </a>
        <a href="{{ route('askPage') }}"
            class="nav-link flex items-center px-3 py-2 rounded-md {{ request()->routeIs('askPage') ? 'active-nav' : '' }}">
            <i class="fa-solid fa-question-circle mr-3"></i> Ask a Question
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
                <a href="{{ route('askPage') }}"
                    class="nav-link flex items-center px-3 py-2 rounded-md {{ request()->routeIs('askPage') ? 'active-nav' : '' }}">
                    <i class="fa-solid fa-question-circle mr-3"></i> Ask a Question
                </a>
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
                <a href="{{ route('savedQuestions') }}"
                    class="nav-link {{ request()->routeIs('savedQuestions') ? 'active-nav' : '' }} text-[var(--text-primary)] py-2.5 text-sm pl-3 rounded-md flex items-center font-medium">
                    <i class="fa-solid fa-bookmark mr-3 w-5 text-center"></i> Saves
                </a>
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
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.querySelector('.hamburger');
        const mobileMenu = document.getElementById('mobile-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('visible');
        });

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
        if (mobileUserMenuButton) {
            mobileUserMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('visible');
                hamburger.classList.toggle('active');
            });
        }

        const themeObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    updateThemeIcons();
                }
            });
        });

        themeObserver.observe(document.documentElement, {
            attributes: true
        });

        if (mobileThemeToggle) {
            mobileThemeToggle.addEventListener('click', function() {
                if (typeof toggleTheme === 'function') {
                    toggleTheme();
                }
            });
        }

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
    });
</script>
