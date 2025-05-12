<style>
    .sidebar {
        background-color: var(--bg-primary);
        position: fixed; /* Fixed position for viewport relativity */
        top: 0;
        left: 0;
        height: 100vh; /* Full viewport height */
        /* Default Desktop Widths (adjust as needed) */
        width: 16rem; /* Matches md:w-[16rem] */
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: var(--accent-secondary) transparent;
        z-index: 1040; /* High z-index */
        /* Transitions */
        transition: width 0.3s ease, transform 0.3s ease, padding-top 0.3s ease;
    }

    @media (min-width: 1024px) { 
        .sidebar:not(.sidebar-collapsed):not(.active) {
            width: 18rem;
        }
    }

    .sidebar::-webkit-scrollbar { width: 5px; }
    .sidebar::-webkit-scrollbar-thumb { background-color: var(--accent-secondary); border-radius: 10px; }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.85rem 1rem;
        margin: 0.35rem 0;
        border-radius: 0.5rem;
        font-weight: 500;
        color: var(--text-primary);
        text-decoration: none; 
        transition: all 0.2s ease-out;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }
    .nav-link:hover {
        background-color: var(--bg-card-hover);
        transform: translateX(4px);
        color: var(--text-primary); 
    }
    .nav-link i {
        font-size: 1.1rem;
        min-width: 2rem;
        margin-right: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    .nav-text {
        transition: opacity 0.2s ease;
        opacity: 1;
        overflow: hidden;
    }

    .active-nav {
        background-color: var(--bg-card-hover);
        border-left: 4px solid var(--accent-tertiary);
        color: var(--accent-tertiary) !important; 
        font-weight: 600;
        padding-left: calc(1rem - 4px); 
    }
    .active-nav i {
         color: var(--accent-tertiary);
         transform: scale(1.1);
    }

    .section-divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0 0.75rem;
        padding: 0 1rem;
        transition: padding 0.3s ease;
    }
    .section-divider span {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        font-weight: 600;
        white-space: nowrap;
        transition: opacity 0.2s ease;
        opacity: 1;
    }
    .section-divider hr {
        flex-grow: 1;
        height: 1px;
        background-color: var(--border-color);
        border: none;
        margin-left: 0.75rem;
        opacity: 0.5;
        transition: opacity 0.2s ease, margin-left 0.3s ease;
    }
    .user-profile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
        transition: padding 0.3s ease, border-color 0.3s ease, height 0.3s ease, opacity 0.3s ease;
        overflow: hidden;
    }
    .user-profile-info-wrapper {
        display: flex;
        align-items: center;
        flex-grow: 1;
        min-width: 0;
        margin-right: 0.5rem;
        transition: opacity 0.3s ease;
        opacity: 1;
    }
    .user-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--accent-tertiary);
        flex-shrink: 0;
        transition: width 0.3s ease, height 0.3s ease, margin 0.3s ease, opacity 0.3s ease;
    }
    .user-info {
        margin-left: 0.75rem;
        overflow: hidden;
        white-space: nowrap;
        transition: opacity 0.2s ease 0.1s;
        opacity: 1;
    }
    .user-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-primary);
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .user-role {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .desktop-collapse-toggle {
        background-color: transparent;
        border: none;
        color: var(--text-muted);
        padding: 0.5rem;
        margin-left: auto;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex; 
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
    }
    .desktop-collapse-toggle:hover {
        background-color: var(--bg-card-hover);
        color: var(--accent-tertiary);
    }
    .desktop-collapse-toggle i {
        font-size: 0.9rem;
        transition: transform 0.3s ease;
    }
    @media (max-width: 767.98px) {
         .desktop-collapse-toggle { display: none !important; }
    }

    .sidebar-collapsed {
        width: 5rem !important; 
        padding-top: 0.75rem;
        transform: translateX(0) !important; 
    }
    .sidebar-collapsed .nav-text,
    .sidebar-collapsed .section-divider span,
    .sidebar-collapsed .user-info {
        opacity: 0;
        width: 0;
        pointer-events: none;
        transition: opacity 0.1s ease, width 0.3s ease;
    }
    .sidebar-collapsed .section-divider hr {
        opacity: 0;
        margin-left: 0;
        transition: opacity 0.1s ease, margin-left 0.3s ease;
    }
    .sidebar-collapsed .section-divider {
        padding: 0 0.5rem;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        justify-content: center;
    }
    .sidebar-collapsed .nav-link {
        justify-content: center;
        padding: 0.85rem;
    }
    .sidebar-collapsed .nav-link:hover {
        transform: none;
    }
    .sidebar-collapsed .nav-link i {
        margin-right: 0;
        font-size: 1.25rem;
    }
    .sidebar-collapsed .active-nav {
        border-left-width: 0;
        padding-left: 0.85rem; 
    }
    
    .sidebar-collapsed .user-profile {
        padding: 0.75rem 0.5rem;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        border-bottom: none;
        height: auto; 
        margin-bottom: 1.5rem;
        opacity: 1;
    }
    .sidebar-collapsed .user-profile-info-wrapper {
        opacity: 0;
        height: 0;
        overflow: hidden;
        margin-right: 0;
        flex-grow: 0;
    }
    .sidebar-collapsed .user-avatar {
        width: 2.25rem;
        height: 2.25rem;
        display: block;
        margin: 0 auto 0.5rem auto;
        opacity: 1;
    }
    .sidebar-collapsed .desktop-collapse-toggle {
        margin: 0;
        position: static;
    }
    .sidebar-collapsed .desktop-collapse-toggle i {
        transform: rotate(180deg);
    }

    .tooltip { position: relative; }
    .sidebar-collapsed .tooltip::before,
    .sidebar-collapsed .desktop-collapse-toggle::before {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.85rem;
        white-space: nowrap;
        z-index: 1050; 
        margin-left: 0.75rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease 0.1s; 
    }
    .sidebar-collapsed .tooltip:hover::before,
    .sidebar-collapsed .desktop-collapse-toggle:hover::before {
        opacity: 1;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            width: 16rem; 
            transform: translateX(-100%);
            padding-top: 0;
        }
        .sidebar.active {
            transform: translateX(0);
            width: 16rem !important;
        }
        .sidebar-collapsed { width: 16rem !important; }
        .sidebar-collapsed .user-profile,
        .sidebar-collapsed .nav-link,
        .sidebar-collapsed .section-divider { }
    }

    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1030; 
        transition: opacity 0.3s ease;
        pointer-events: none;
        opacity: 0;
    }
    .sidebar-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .sidebar-toggle {
        position: fixed;
        bottom: 1.25rem;
        right: 1.25rem;
        z-index: 1045; 
        display: flex;
        align-items: center;
        justify-content: center;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background-color: var(--accent-tertiary);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .sidebar-toggle:hover {
        transform: scale(1.1);
    }
    @media (min-width: 768px) {
        .sidebar-toggle {
            display: none;
        }
    }

    body.overflow-hidden {
        overflow: hidden;
    }
</style>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<aside class="sidebar" id="sidebar">

   
    @if (session()->has('email'))
    <div class="user-profile">
        <div class="user-profile-info-wrapper">
             <img class="user-avatar" src="{{ $image ? asset('storage/' . $image) : 'https://via.placeholder.com/150/' . substr(str_replace(' ','',strtolower($name ?? 'U')), 0, 6) . '/ffffff?text=' . strtoupper(substr($name ?? 'U', 0, 1)) }}" alt="User avatar">
            <div class="user-info">
                <div class="user-name">{{ $name ?? 'User Name' }}</div>
                <div class="user-role">{{ $role ?? 'Member' }}</div>
            </div>
        </div>
        <button class="desktop-collapse-toggle" id="desktop-collapse-toggle" aria-label="Toggle Sidebar" data-tooltip="Collapse Sidebar">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
    </div>
    @endif

    <div class="px-3">
        <nav class="flex flex-col">
            <a href="{{ route('home') }}" class="nav-link tooltip {{ request()->routeIs('home') ? 'active-nav' : '' }}" data-tooltip="Home">
                <i class="fa-solid fa-house"></i><span class="nav-text">Home</span>
            </a>
            <a href="{{ route('popular') }}" class="nav-link tooltip {{ request()->routeIs('popular') ? 'active-nav' : '' }}" data-tooltip="Popular">
                <i class="fa-solid fa-fire"></i><span class="nav-text">Popular</span>
            </a>
            <a href="{{ route('viewAllTags') }}" class="nav-link tooltip {{ request()->routeIs('viewAllTags') ? 'active-nav' : '' }}" data-tooltip="Tags">
                <i class="fa-solid fa-tags"></i><span class="nav-text">Tags</span>
            </a>
        </nav>

        <div class="section-divider"><span>Community</span><hr></div>
        <nav class="flex flex-col">
            <a href="{{ route('viewAllUsers') }}" class="nav-link tooltip {{ request()->routeIs('viewAllUsers') ? 'active-nav' : '' }}" data-tooltip="Informates">
                <i class="fa-solid fa-users"></i><span class="nav-text">Informates</span>
            </a>
            <a href="{{ route('user.leaderboard') }}" class="nav-link tooltip {{ request()->routeIs('user.leaderboard') ? 'active-nav' : '' }}" data-tooltip="Leaderboard">
                <i class="fa-solid fa-trophy"></i><span class="nav-text">Leaderboard</span>
            </a>
        </nav>

        @if (session()->has('email'))
        <div class="section-divider"><span>Account</span><hr></div>
        <nav class="flex flex-col">
            <a href="{{ route('seeProfile') }}" class="nav-link tooltip {{ request()->routeIs('seeProfile') ? 'active-nav' : '' }}" data-tooltip="Profile">
                <i class="fa-solid fa-user"></i><span class="nav-text">Profile</span>
            </a>
            <a href="{{ route('editProfile') }}" class="nav-link tooltip {{ request()->routeIs('editProfile') ? 'active-nav' : '' }}" data-tooltip="Settings">
                <i class="fa-solid fa-gear"></i><span class="nav-text">Settings</span>
            </a>
            <a href="{{ route('logout') }}" class="nav-link tooltip" data-tooltip="Sign Out">
                <i class="fa-solid fa-right-from-bracket"></i><span class="nav-text">Sign Out</span>
            </a>
        </nav>
        @endif
    </div>
</aside>

<button class="sidebar-toggle" id="mobile-sidebar-toggle" aria-label="Open Menu">
    <i class="fa-solid fa-bars" id="mobile-toggle-icon"></i>
</button>

<script>
    (function() {
        const sidebar = document.getElementById('sidebar');
        const desktopCollapseToggle = document.getElementById('desktop-collapse-toggle');
        const mobileToggle = document.getElementById('mobile-sidebar-toggle');
        const mobileToggleIcon = document.getElementById('mobile-toggle-icon');
        const overlay = document.getElementById('sidebar-overlay');

        if (!sidebar || !mobileToggle || !mobileToggleIcon || !overlay) {
            console.error("Sidebar elements not found!");
            return;
        }

        const isMobile = () => window.innerWidth < 768; 

        const applyDesktopState = () => {
            if (!isMobile() && desktopCollapseToggle) { 
        
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('overflow-hidden');
                if (mobileToggleIcon) { 
                    mobileToggleIcon.classList.remove('fa-xmark');
                    mobileToggleIcon.classList.add('fa-bars');
                    mobileToggle.setAttribute('aria-label', 'Open Menu');
                }

                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState === 'true') {
                    sidebar.classList.add('sidebar-collapsed');
                    desktopCollapseToggle.setAttribute('data-tooltip', 'Expand Sidebar');
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    desktopCollapseToggle.setAttribute('data-tooltip', 'Collapse Sidebar');
                }
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                if (!sidebar.classList.contains('active')) {
                    overlay.classList.remove('active');
                    document.body.classList.remove('overflow-hidden');
                     if (mobileToggleIcon) {
                         mobileToggleIcon.classList.remove('fa-xmark');
                         mobileToggleIcon.classList.add('fa-bars');
                         mobileToggle.setAttribute('aria-label', 'Open Menu');
                     }
                }
            }
        };

        const closeMobileMenu = () => {
             sidebar.classList.remove('active');
             overlay.classList.remove('active');
             document.body.classList.remove('overflow-hidden');
             mobileToggleIcon.classList.remove('fa-xmark');
             mobileToggleIcon.classList.add('fa-bars');
             mobileToggle.setAttribute('aria-label', 'Open Menu');
         }

        const openMobileMenu = () => {
             sidebar.classList.add('active');
             overlay.classList.add('active');
             document.body.classList.add('overflow-hidden');
             sidebar.classList.remove('sidebar-collapsed'); 
             mobileToggleIcon.classList.remove('fa-bars');
             mobileToggleIcon.classList.add('fa-xmark');
             mobileToggle.setAttribute('aria-label', 'Close Menu');
        }

        applyDesktopState();


        if (desktopCollapseToggle) {
            desktopCollapseToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                desktopCollapseToggle.setAttribute('data-tooltip', isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar');
            });
        }

        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation(); 
            if (sidebar.classList.contains('active')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });

        overlay.addEventListener('click', function() {
            closeMobileMenu();
        });

        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                 applyDesktopState();

                 if (isMobile()) {
                     if (sidebar) {
                        sidebar.classList.remove('sidebar-collapsed'); 
                     }
                 } else {
                    closeMobileMenu();
                 }
            }, 150); 
        });

    })(); 
</script>