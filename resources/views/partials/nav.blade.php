<style>
    .ham {
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        transition: transform 400ms;
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .hamRotate.active {
        transform: rotate(45deg);
    }

    .hamRotate180.active {
        transform: rotate(180deg);
    }

    .line {
        fill: none;
        transition: stroke-dasharray 400ms, stroke-dashoffset 400ms;
        stroke: #000;
        stroke-width: 5.5;
        stroke-linecap: round;
    }

    .ham4 .top {
        stroke-dasharray: 40 121;
    }

    .ham4 .bottom {
        stroke-dasharray: 40 121;
    }

    .ham4.active .top {
        stroke-dashoffset: -68px;
    }

    .ham4.active .bottom {
        stroke-dashoffset: -68px;
    }
    .active-nav {
        background-color: #12192c;
        font-weight: 500;
    }
    
    .active-ask {
        transform: scale(1.05);
        font-weight: 600;
    }

</style>
<nav class="fixed top-0 left-0 bg-[var(--bg-primary)] border-gray-200 w-full h-auto shadow-md text-white z-50">
    <div class="max-w-screen-xl relative flex flex-wrap items-center justify-between mx-auto p-4">
        <!-- Hamburger Button -->
        <svg id="hamburger-svg" class="ham hamRotate ham4 md:hidden" viewBox="0 0 100 100" width="50">
            <path class="line top"
                d="m 70,33 h -40 c 0,0 -8.5,-0.149796 -8.5,8.5 0,8.649796 8.5,8.5 8.5,8.5 h 20 v -20" />
            <path class="line middle" d="m 70,50 h -40" />
            <path class="line bottom"
                d="m 30,67 h 40 c 0,0 8.5,0.149796 8.5,-8.5 0,-8.649796 -8.5,-8.5 -8.5,-8.5 h -20 v 20" />
        </svg>

        <!-- Logo Section -->
        <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse xl:-m-6">
            <img src="{{ asset('assets/p2p logo - white.svg') }}" alt="Logo" class="h-6 lg:h-8 w-auto theme-logo">
            <div class="text-mg cal-sans-regular">
                <span class="font-bold text-white">peer</span>
                <span class="font-bold text-[var(--accent-tertiary)]">- to -</span>
                <span class="font-bold text-white">peer</span>
            </div>
        </a>

        
        <div class="hidden md:flex items-center gap-4 ml-auto">
            <!-- Search Input -->
            <div class="flex items-center">
                <input type="text" placeholder="Search..."
                    class="w-80 px-4 py-2 rounded-s-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-[#485d93] text-sm">
                <button type="submit"
                    class="text-white bg-[var(--bg-tertiary)] hover:bg-[#5a68a2] px-3 py-2 rounded-e-lg focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <button id="theme-toggle" onclick="toggleTheme()" class="theme-toggle bottom-0" aria-label="Toggle theme">
                <i id="theme-toggle-icon" class="fa-solid fa-sun"></i>
            </button>

            @if (!session()->has('email'))
                <!-- Sign Up Button -->
                <a href="{{ route('loginOrRegist') }}"
                    class="text-white hover:text-[#485d93] hover:bg-[#a8bcf3] bg-[#7494ec] font-medium rounded-lg lg:text-sm lg:px-4 lg:py-2 text-xs px-2 py-2">
                    Regist / Login
                </a>
            @else

            <!-- User Avatar -->
            <div class="relative">
                <button type="button"
                    class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="sr-only">Open user menu</span>
                    <img class="size-8 rounded-full"
                        src="{{ $image ? asset('storage/' . $image) : 'https://via.placeholder.com/150' }}"
                        alt="User avatar">
                </button>

                <!-- Dropdown Menu -->
                <div class="hidden absolute right-0 mt-2 w-48 bg-[var(--bg-secondary)] rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                    id="user-menu" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                    <div class="py-1" role="none">
                        <a href="{{ route('seeProfile') }}" class="text-white hover:text-[#ffe98f] hover:bg-[var(--bg-card-hover)] block px-4 py-2 text-sm"
                            role="menuitem">Profile</a>
                        <a href="{{ route('editProfile') }}" class="text-white hover:text-[#ffe98f] hover:bg-[var(--bg-card-hover)] block px-4 py-2 text-sm"
                            role="menuitem">Settings</a>
                        <a href="{{ route('logout') }}" class="text-white hover:text-[#ffe98f] hover:bg-[var(--bg-card-hover)] block px-4 py-2 text-sm"
                            role="menuitem">Sign out</a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if (!session()->has('email'))
            <!-- Sign Up Button -->
            <a href="{{ route('loginOrRegist') }}"
                class="text-white hover:text-[#485d93] hover:bg-[#a8bcf3] bg-[#7494ec] font-medium rounded-lg lg:text-sm lg:px-4 lg:py-2 text-xs px-2 py-2">
                Regist / Login
            </a>
        @else

            <!-- User Action -->
            <div class="md:hidden flex ml-3">
                <div>
                    <button type="button"
                        class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <img class="size-8 rounded-full" src="{{ $image ? asset('storage/' . $image) : 'https://via.placeholder.com/150' }}" alt="User avatar">
                    </button>
                </div>

                <!-- Dropdown Menu -->
                <div class="hidden absolute right-0 mt-2 w-48 bg-[var(--bg-secondary)] rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                    id="user-menu" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                    <div class="py-1" role="none">
                        <!-- Ganti route -->
                        <a href="{{route('seeProfile')}}" class="text-white hover:text-[#ffe98f] block px-4 py-2 text-sm" role="menuitem">Profile</a>
                        <a href="{{route('editProfile')}}" class="text-white hover:text-[#ffe98f] block px-4 py-2 text-sm" role="menuitem">Settings</a>
                        <a href="{{route('logout')}}" class="text-white hover:text-[#ffe98f] block px-4 py-2 text-sm" role="menuitem">Sign out</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Mobile Menu -->
<div id="mobile-menu" class="hidden flex-col gap-4 p-4 md:hidden bg-[#1c2246] text-white w-full">
    <a href="{{ route('home') }}" class="hover:text-[#ffe98f]">
        <i class="fa-solid fa-house mr-2"></i> Home
    </a>
    <a href="{{ route('popular') }}" class="hover:text-[#ffe98f]">
        <i class="fa-solid fa-fire mr-2"></i> Popular
    </a>
    <a href="{{ route('viewAllTags') }}" class="hover:text-[#ffe98f]">
        <i class="fa-solid fa-tags mr-2"></i> Tags
    </a>
    <a href="{{ route('viewAllUsers') }}" class="hover:text-[#ffe98f]">
        <i class="fa-solid fa-users mr-2"></i> Informates
    </a>
    <a href="{{ route('askPage') }}" class="hover:text-[#ffe98f]">
        <i class="fa-solid fa-question-circle mr-2"></i> Ask a Question
    </a>
    <a href="{{ route('user.leaderboard') }}" class="hover:text-[#ffe98f]">
        <i class="fa-solid fa-trophy mr-2"></i> Leaderboard
    </a>

    @if (!session()->has('email'))
        <a href="{{ route('loginOrRegist') }}" class="hover:text-[#ffe98f]">
            <i class="fa-solid fa-sign-in mr-2"></i> Regist / Login
        </a>
    @else
        <a href="{{ route('seeProfile') }}" class="hover:text-[#ffe98f]">
            <i class="fa-solid fa-user mr-2"></i> Profile
        </a>
        <a href="{{ route('editProfile') }}" class="hover:text-[#ffe98f]">
            <i class="fa-solid fa-cog mr-2"></i> Settings
        </a>
        <a href="{{ route('logout') }}" class="hover:text-[#ffe98f]">
            <i class="fa-solid fa-sign-out mr-2"></i> Sign Out
        </a>
    @endif
</div>
</nav>


    <!-- Navbar Links -->
    <div class="hidden md:flex flex-col lg:w-[20rem] w-64 h-screen lg:pl-28 pl-6 pr-2 py-6 shadow-md fixed top-[4.5rem] left-0 z-[10] bg-[var(--bg-primary)]" id="sidebar">
        <!-- Ask a Question - Highlighted Button -->
        <nav class="flex flex-col space-y-0">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active-nav' : '' }} text-white hover:text-[#ffe98f] py-2 text-sm pl-3 transition-all duration-200">
                <i class="fa-solid fa-house mr-3"></i> Home
            </a>
            <a href="{{ route('popular') }}" class="nav-link {{ request()->routeIs('popular') ? 'active-nav' : '' }} text-white hover:text-[#ffe98f] py-2 text-sm pl-3 transition-all duration-200">
                <i class="fa-solid fa-fire mr-3"></i> Popular
            </a>
            <a href="{{ route('viewAllTags') }}" class="nav-link {{ request()->routeIs('viewAllTags') ? 'active-nav' : '' }} text-white hover:text-[#ffe98f] py-2 text-sm pl-3 transition-all duration-200">
                <i class="fa-solid fa-tags mr-3"></i> Tags
            </a>
        </nav>
        <div class="mt-10">
            {{-- <span class="text-[var(--text-muted)]">SOCIAL</span>
            <hr class="h-px pl-8 mx-1 my-2 bg-[var(--text-muted)]"> --}}
            <nav class="flex flex-col space-y-0">
                <a href="{{ route('viewAllUsers') }}" class="nav-link {{ request()->routeIs('viewAllUsers') ? 'active-nav' : '' }} text-white hover:text-[#ffe98f] py-2 text-sm pl-3 transition-all duration-200">
                    <i class="fa-solid fa-users mr-3"></i> Informates
                </a>
                <a href="{{ route('user.leaderboard') }}" class="nav-link {{ request()->routeIs('user.leaderboard') ? 'active-nav' : '' }} mt-2 text-white hover:text-[#ffe98f] py-2 text-sm pl-3 transition-all duration-200">
                    <i class="fa-solid fa-trophy mr-3"></i> Leaderboard
                </a>
            </nav>
        </div>
    </div>
</nav>

<script>
    const hamburgerSvg = document.getElementById('hamburger-svg');
    const mobileMenu = document.getElementById('mobile-menu');

    // Toggle menu and icon animation
    hamburgerSvg.addEventListener('click', () => {
        hamburgerSvg.classList.toggle('active');
        mobileMenu.classList.toggle('hidden');
        mobileMenu.classList.toggle('flex');
    });

    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');

    userMenuButton.addEventListener('mouseenter', () => {
        userMenu.classList.remove('hidden');
    });
    userMenu.addEventListener('mouseleave', () => {
        userMenu.classList.add('hidden');
    });

</script>


{{-- Dark mode --}}

<style>
    /* Active state for nav items */
    .active-nav {
        background-color: var(--border-color);
        font-weight: 500;
    }
    
    /* Light/dark mode adaptive styling for nav elements */
    .nav-link {
        color: var(--text-primary);
    }
    
    .nav-link:hover {
        color: var(--accent-primary);
        background-color: var(--border-color);
    }
    
    /* Ask button styling with theme variables */
    .ask-question-btn {
        background: var(--button-primary);
        color: var(--button-text);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .ask-question-btn:hover {
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
    }
    
    .active-ask {
        box-shadow: 0 0 15px var(--accent-tertiary);
        transform: scale(1.05);
        font-weight: 600;
    }
    
    /* Sidebar background adaptable to theme */
    #sidebar > div {
        background-color: var(--bg-primary);
        border-right: 1px solid var(--border-color);
    }
    
    /* Logo color adaptation for light mode */
    .theme-text {
        color: var(--text-primary);
    }
    
    /* Change logo for light mode */
    .light-mode .theme-logo[src*="white.svg"] {
        content: url("{{ asset('assets/p2p logo.svg') }}");
    }
</style>

<!-- Small script to handle logo switching -->
<script>
    // Observe theme changes to swap the logo
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                const isLightMode = document.documentElement.classList.contains('light-mode');
                const logoImg = document.querySelector('.theme-logo');
                
                if (logoImg) {
                    if (isLightMode && logoImg.src.includes('white.svg')) {
                        logoImg.src = "{{ asset('assets/p2p logo.svg') }}";
                    } else if (!isLightMode && !logoImg.src.includes('white.svg')) {
                        logoImg.src = "{{ asset('assets/p2p logo - white.svg') }}";
                    }
                }
            }
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        observer.observe(document.documentElement, { attributes: true });
    });
</script>