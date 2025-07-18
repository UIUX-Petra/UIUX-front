{{-- resources/views/users/connections.blade.php --}}
@extends('layout') {{-- Sesuaikan dengan nama layout utama Anda --}}

@section('title', $title ?? 'User Connections')

@section('style')
    <style>
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
        }

        /* Decorative background elements */
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Ccircle cx='10' cy='10' r='1'/%3E%3C/g%3E%3C/svg%3E");
            background-size: 20px 20px;
        }

        .light-mode .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23000000' fill-opacity='0.03' fill-rule='evenodd'%3E%3Ccircle cx='10' cy='10' r='1'/%3E%3C/g%3E%3C/svg%3E");
        }

        /* Floating decorative elements */
        .floating-decoration {
            position: fixed;
            pointer-events: none;
            z-index: -1;
        }

        .decoration-1 {
            top: 10%;
            right: 10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(56, 163, 165, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .decoration-2 {
            bottom: 20%;
            left: 5%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(128, 237, 153, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Header Profile Card - Updated to match homepage style */
        .profile-header-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .profile-header-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(56, 163, 165, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Enhanced welcome header similar to homepage */
        .welcome-header {
            background: transparent;
            border-radius: 16px;
            padding: 2rem;
            margin: 1.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(56, 163, 165, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .titleGradient {
            background: linear-gradient(90deg, #38A3A5, #57CC99, #80ED99);
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

        .profile-avatar {
            position: relative;
            z-index: 10;
        }

        .profile-avatar::after {
            content: '';
            position: absolute;
            inset: -4px;
            background: linear-gradient(45deg, #38A3A5, #80ED99, #38A3A5);
            border-radius: 50%;
            z-index: -1;
            animation: avatarGlow 3s ease-in-out infinite alternate;
        }

        @keyframes avatarGlow {
            0% {
                opacity: 0.5;
            }
            100% {
                opacity: 0.8;
            }
        }

        /* Tab System - Updated to match homepage tabs */
        .tab-navigation {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: visible;
        }

        .tab-navigation::before {
            display: none;
        }

        .tab-button {
            position: relative;
            padding: 0.75rem 1rem;
            margin-bottom: -1px;
            cursor: pointer;
            border-radius: 0;
            border-bottom: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            color: var(--text-secondary);
            background: transparent;
            border-top: none;
            border-left: none;
            border-right: none;
            z-index: 10;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-button:not(.active):hover {
            color: var(--text-primary);
            border-bottom-color: var(--border-color);
            transform: translateY(-1px);
        }

        .tab-button.active {
            color: var(--text-primary);
            font-weight: 600;
            border-bottom-color: #38A3A5;
            background: transparent;
            box-shadow: none;
            transform: none;
        }

        .tab-button .ml-2 {
            background: rgba(56, 163, 165, 0.1);
            color: #38A3A5;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .tab-button.active .ml-2 {
            background: rgba(56, 163, 165, 0.2);
            color: #38A3A5;
        }

        .tab-content {
            display: none !important;
        }

        .tab-content.active {
            display: block !important;
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* User List Items - Enhanced cards */
        .connections-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .user-list-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .user-list-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--bg-pattern);
            opacity: 0.3;
            pointer-events: none;
        }

        .user-list-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(56, 163, 165, 0.15);
            border-color: rgba(56, 163, 165, 0.3);
            background: var(--bg-card-hover);
        }

        /* Follow buttons - Enhanced styling */
        .follow-btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .follow-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .follow-btn:hover::before {
            transform: translateX(100%);
        }

        .btn-follow {
            background: linear-gradient(135deg, #38A3A5, #80ED99);
            color: #000;
            font-weight: 700;
        }

        .btn-follow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(56, 163, 165, 0.4);
            background: linear-gradient(135deg, #80ED99, #38A3A5);
        }

        .btn-unfollow {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .btn-unfollow:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);
        }

        .btn-follow-back {
            background: linear-gradient(135deg, #57CC99, #80ED99);
            color: #000;
            font-weight: 600;
        }

        .btn-follow-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(87, 204, 153, 0.4);
        }

        .status-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            margin-left: 0.5rem;
            font-weight: 600;
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        /* Empty States - Enhanced */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .empty-state::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(128, 237, 153, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            opacity: 0.6;
            background: rgba(56, 163, 165, 0.1);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Section Headers - Enhanced */
        .section-header {
            position: relative;
            padding-left: 1rem;
            margin-bottom: 2rem;
        }

        .section-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: linear-gradient(to bottom, #38A3A5, #80ED99);
            border-radius: 2px;
        }

        /* Stats Display - Enhanced */
        .connection-stats {
            display: flex;
            gap: 1.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: var(--bg-secondary);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            min-width: 120px;
        }

        .stat-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent, rgba(56, 163, 165, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(56, 163, 165, 0.2);
            border-color: var(--accent-primary);
            background: var(--bg-card-hover);
        }

        .stat-item:hover::before {
            transform: translateX(100%);
        }

        .stat-item:active {
            transform: translateY(0px) scale(0.98);
        }

        .stat-icon {
            color: var(--accent-primary);
            transition: all 0.3s ease;
        }

        .stat-item:hover .stat-icon {
            color: #38A3A5;
            transform: scale(1.1);
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .connections-grid {
                grid-template-columns: 1fr;
            }

            .profile-header-card {
                padding: 1.5rem;
            }

            .welcome-header {
                padding: 1.5rem;
            }

            .tab-navigation {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 0;
            }

            .tab-button {
                white-space: nowrap;
                flex-shrink: 0;
            }

            .connection-stats {
                flex-wrap: wrap;
                gap: 0.75rem;
                justify-content: center;
            }

            .stat-item {
                min-width: auto;
                flex: 1;
                justify-content: center;
            }
        }

        /* Loading States */
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

        /* Additional enhancements */
        .user-avatar-small {
            border: 2px solid rgba(56, 163, 165, 0.2);
            transition: all 0.3s ease;
        }

        .user-list-item:hover .user-avatar-small {
            border-color: rgba(56, 163, 165, 0.5);
            transform: scale(1.05);
        }

        .user-info h3 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .user-info p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Enhanced profile header for own profile */
        .profile-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        @media (max-width: 640px) {
            .profile-actions {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $breadcrumbs = [
            ['url' => route('viewUser', ['email' => $profileUser['email']]), 'label' => $profileUser['username'] ?? 'User Profile', 'icon' => 'fas fa-user'],
            ['url' => null, 'label' => 'Connections', 'icon' => 'fas fa-users', 'current' => true]
        ];
    @endphp
    @include('partials.nav', ['loggedInUser' => $loggedInUser])

    <!-- Floating decorative elements -->
    <div class="floating-decoration decoration-1"></div>
    <div class="floating-decoration decoration-2"></div>
    
    <div class="container items-start justify-start px-4 sm:px-6 lg:px-8 py-8 max-w-5xl">
        <div class="mb-6 px-2">
            <nav class="flex items-center gap-2 text-sm">
                @foreach($breadcrumbs as $index => $breadcrumb)
                    @if(!$loop->first)
                        <span class="text-[var(--text-muted)] text-xs">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                    @if(isset($breadcrumb['current']) && $breadcrumb['current'])
                        <span class="text-[var(--accent-primary)] font-semibold flex items-center">
                            <i class="{{ $breadcrumb['icon'] }} mr-1.5 text-xs"></i>
                            {{ $breadcrumb['label'] }}
                        </span>
                    @else
                        <a href="{{ $breadcrumb['url'] }}"
                        class="text-[var(--text-secondary)] no-underline transition-colors duration-200 ease-in-out hover:text-[var(--text-primary)] flex items-center">
                            <i class="{{ $breadcrumb['icon'] }} mr-1.5 text-xs"></i>
                            {{ $breadcrumb['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>

        {{-- Header Profil Pengguna --}}
        <div class="profile-header-card rounded-2xl p-6 mb-8 shadow-lg">
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6">
                <div class="profile-avatar flex-shrink-0">
                    <img class="w-28 h-28 lg:w-32 lg:h-32 rounded-full object-cover border-4 border-white/20"
                        src="{{ $profileUser['image'] ? asset('storage/' . $profileUser['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($profileUser['username'] ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                        alt="{{ $profileUser['username'] ?? 'User' }}'s avatar">
                </div>

                <div class="flex-1 text-center lg:text-left relative z-10">
                     <h1 class="cal-sans-regular text-4xl lg:text-5xl titleGradient leading-tight py-1">
                        {{ $profileUser['username'] ?? 'User Profile' }}
                    </h1>
                    <p class="text-[var(--text-secondary)] text-lg mb-2">{{ $profileUser['email'] ?? '' }}</p>

                    @if ($profileUser['biodata'])
                        <p class="text-[var(--text-muted)] mb-4 max-w-2xl">{{ $profileUser['biodata'] }}</p>
                    @endif

                    {{-- Connection Stats --}}
                    {{-- <div class="connection-stats justify-center lg:justify-start">
                        <div class="stat-item stat-clickable" data-stat-target="followers" title="View Followers">
                            <i class="fas fa-users stat-icon"></i>
                            <span class="font-semibold">{{ $followersList->count() }}</span>
                            <span class="text-sm text-[var(--text-secondary)]">Followers</span>
                        </div>
                        <div class="stat-item stat-clickable" data-stat-target="following" title="View Following">
                            <i class="fas fa-user-friends stat-icon"></i>
                            <span class="font-semibold">{{ $followingList->count() }}</span>
                            <span class="text-sm text-[var(--text-secondary)]">Following</span>
                        </div>
                    </div> --}}

                    @if ($loggedInUser && !$isOwnProfile)
                        @php
                            $relation = $profileUser['current_user_relation'] ?? [
                                'follow_status' => 'not_following',
                                'is_mutual' => false,
                            ];
                        @endphp
                        <div class="profile-actions">
                            <button
                                class="follow-btn action-follow-profile {{ $relation['follow_status'] === 'following'
                                    ? 'btn-unfollow'
                                    : ($relation['follow_status'] === 'follows_you'
                                        ? 'btn-follow-back'
                                        : 'btn-follow') }}"
                                data-user-email="{{ $profileUser['email'] }}">
                                @if ($relation['follow_status'] === 'following')
                                    <i class="fas fa-user-check"></i>Following
                                    @if ($relation['is_mutual'])
                                        <span class="status-badge">Mutual</span>
                                    @endif
                                @elseif ($relation['follow_status'] === 'follows_you')
                                    <i class="fas fa-user-plus"></i>Follow Back
                                @else
                                    <i class="fas fa-user-plus"></i>Follow
                                @endif
                            </button>
                        </div>
                    @elseif (!$loggedInUser && !$isOwnProfile)
                        <div class="profile-actions">
                            <a href="{{ route('loginOrRegist') }}" class="follow-btn btn-follow">
                                <i class="fas fa-user-plus"></i>Follow
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Navigasi Tab --}}
        <div class="tab-navigation flex gap-6 mb-8">
            <button class="tab-button {{ $activeTab === 'followers' ? 'active' : '' }}" data-tab-target="#followersContent"
                data-tab-name="followers">
                <i class="fas fa-users"></i>
                Followers
                <span class="ml-2">{{ $followersList->count() }}</span>
            </button>
            <button class="tab-button {{ $activeTab === 'following' ? 'active' : '' }}" data-tab-target="#followingContent"
                data-tab-name="following">
                <i class="fas fa-user-friends"></i>
                Following
                <span class="ml-2">{{ $followingList->count() }}</span>
            </button>
        </div>

        {{-- Konten Tab --}}
        <div>
            {{-- Konten Tab Followers --}}
            <div id="followersContent" class="tab-content {{ $activeTab === 'followers' ? 'active' : '' }}">
                <div class="section-header">
                    <h2 class="text-2xl font-bold text-[var(--text-primary)]">
                        {{ $profileUser['username'] ?? 'This user' }}'s Followers
                    </h2>
                    <p class="text-[var(--text-secondary)] mt-2">People who follow
                        {{ $profileUser['username'] ?? 'this user' }}</p>
                </div>

                @if ($followersList->isNotEmpty())
                    <div class="connections-grid">
                        @foreach ($followersList as $item)
                            @include('user_list_item', [
                                'userItem' => $item,
                                'loggedInUser' => $loggedInUser,
                                'isOwnProfileContext' => $isOwnProfile,
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[var(--text-primary)] mb-3">No Followers Yet</h3>
                        <p class="text-[var(--text-muted)] max-w-md mx-auto">
                            {{ $profileUser['username'] ?? 'This user' }} doesn't have any followers yet.
                            Be the first to follow and connect!
                        </p>
                    </div>
                @endif
            </div>

            {{-- Konten Tab Following --}}
            <div id="followingContent" class="tab-content {{ $activeTab === 'following' ? 'active' : '' }}">
                <div class="section-header">
                    <h2 class="text-2xl font-bold text-[var(--text-primary)]">
                        {{ $profileUser['username'] ?? 'This user' }} is Following
                    </h2>
                    <p class="text-[var(--text-secondary)] mt-2">People that {{ $profileUser['username'] ?? 'this user' }}
                        follows</p>
                </div>

                @if ($followingList->isNotEmpty())
                    <div class="connections-grid">
                        @foreach ($followingList as $item)
                            @include('user_list_item', [
                                'userItem' => $item,
                                'loggedInUser' => $loggedInUser,
                                'isOwnProfileContext' => $isOwnProfile,
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[var(--text-primary)] mb-3">Not Following Anyone</h3>
                        <p class="text-[var(--text-muted)] max-w-md mx-auto">
                            {{ $profileUser['username'] ?? 'This user' }} isn't following anyone yet.
                            Discover and connect with other users to build your network!
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            // const statItems = document.querySelectorAll('.stat-clickable');

            function updateURLAndActivateTab(tabName) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('type', tabName);
                history.pushState({
                    type: tabName
                }, "", currentUrl.pathname + currentUrl.search + '#' + tabName);

                tabs.forEach(t => {
                    t.classList.remove('active');
                    if (t.getAttribute('data-tab-name') === tabName) {
                        t.classList.add('active');
                    }
                });

                tabContents.forEach(content => {
                    content.classList.remove('active');
                    if (content.id === tabName + 'Content') {
                        content.classList.add('active');
                    }
                });
            }

            // Handle tab button clicks
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabName = tab.getAttribute('data-tab-name');
                    if (tabName) {
                        updateURLAndActivateTab(tabName);
                    }
                });
            });

            // Handle stat item clicks - NEW FUNCTIONALITY
            // statItems.forEach(statItem => {
            //     statItem.addEventListener('click', () => {
            //         const targetTab = statItem.getAttribute('data-stat-target');
            //         if (targetTab) {
            //             updateURLAndActivateTab(targetTab);

            //             // Smooth scroll to tab navigation
            //             const tabNavigation = document.querySelector('.tab-navigation');
            //             if (tabNavigation) {
            //                 tabNavigation.scrollIntoView({ 
            //                     behavior: 'smooth', 
            //                     block: 'start' 
            //                 });
            //             }
            //         }
            //     });
            // });

            // Initialize tab based on URL
            const currentUrlParams = new URLSearchParams(window.location.search);
            const typeParam = currentUrlParams.get('type');
            const hash = window.location.hash.substring(1);
            let initialTab = 'followers';

            if (typeParam && (typeParam === 'followers' || typeParam === 'following')) {
                initialTab = typeParam;
            } else if (hash && (hash === 'followers' || hash === 'following')) {
                initialTab = hash;
            }

            const initialTabElement = document.querySelector(`.tab-button[data-tab-name="${initialTab}"]`);
            if (initialTabElement) {
                updateURLAndActivateTab(initialTab);
            } else {
                const firstAvailableTab = document.querySelector('.tab-button');
                if (firstAvailableTab) {
                    updateURLAndActivateTab(firstAvailableTab.getAttribute('data-tab-name'));
                }
            }

            // Handle browser back/forward navigation
            window.addEventListener('popstate', function(event) {
                const stateType = event.state ? event.state.type : null;
                const currentHash = window.location.hash.substring(1);
                let tabToActivate = 'followers';

                if (stateType && (stateType === 'followers' || stateType === 'following')) {
                    tabToActivate = stateType;
                } else if (currentHash && (currentHash === 'followers' || currentHash === 'following')) {
                    tabToActivate = currentHash;
                }

                const targetTabElement = document.querySelector(
                    `.tab-button[data-tab-name="${tabToActivate}"]`);
                if (targetTabElement) {
                    updateURLAndActivateTab(tabToActivate);
                } else {
                    const firstAvailableTab = document.querySelector('.tab-button');
                    if (firstAvailableTab) {
                        updateURLAndActivateTab(firstAvailableTab.getAttribute('data-tab-name'));
                    }
                }
            });

            // Follow/Unfollow functionality
            const handleFollowAction = async (targetUserEmail, actionButton) => {
                const originalButtonHTML = actionButton.innerHTML;
                actionButton.disabled = true;
                actionButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';

                try {
                    const response = await fetch("{{ route('nembakFollow') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            email: targetUserEmail
                        })
                    });

                    const data = await response.json();

                    console.log(data);

                    if (data.success && data.data) {
                        const newStatus = data.data.userRelation == 1 || data.data.userRelation == 3 ?
                            'following' : (data.data.userRelation == 2 ? 'follows_you' : 'follow');

                        const isMutual = data.data.userRelation == 3 ? true : false;

                        let newButtonText = '<i class="fas fa-user-plus mr-2"></i>Follow';
                        const baseClasses = 'follow-btn';
                        const actionClass = actionButton.classList.contains('action-follow-profile') ?
                            'action-follow-profile' : (actionButton.classList.contains(
                                'action-follow-list') ? 'action-follow-list' : '');
                        let statusClass = 'btn-follow';

                        const emailUrl = window.location.pathname.split('/')[
                            2]; // atau pakai data.data.targetEmail
                        const dataUserEmail = actionButton.getAttribute('data-user-email'); //target email
                        const loggedInemail = @json($loggedInUser['email'] ?? '');
                        const loggedInUsername = @json($loggedInUser['username'] ?? '');

                        let connectionsGrid = document.querySelector('.connections-grid');
                        const emptyState = document.querySelector('.empty-state');
                        const userListItem = document.querySelector('.user-list-item');

                        if (loggedInemail === emailUrl) { //own Profile ubah following saja
                            let followingsCountOnNavTab = document.querySelectorAll(
                                '.tab-navigation .tab-button')[1].querySelector('span');
                            let followingsCountOnStat = document.querySelectorAll(
                                '.connection-stats .stat-item')[1].querySelector('span');

                            followingsCountOnNavTab.textContent = data.data.myFollow;
                            followingsCountOnStat.textContent = data.data.myFollow;

                            let followingList = document.querySelector('#followingContent');
                            let followingConnections = followingList.querySelector('.connections-grid');
                            let followingEmpty = followingList.querySelector('.empty-state');

                            if (newStatus === 'follow' || newStatus === 'follows_you') {
                                newButtonText = 'Follow';
                                statusClass = 'text-xs sm:text-sm btn-follow';
                            } else if (newStatus === 'unfollow') {
                                newButtonText = 'Unfollow';
                                statusClass = 'text-xs sm:text-sm btn-unfollow';
                            } else if (newStatus === 'followBack') {
                                newButtonText = 'Follow Back';
                                statusClass = 'text-xs sm:text-sm btn-follow-back';
                            }

                            // Handle adding user to following list when following someone new
                            if (newStatus === 'following') {
                                // Create connections grid if it doesn't exist (from empty state)
                                if (followingEmpty && !followingConnections) {
                                    followingEmpty.remove();

                                    followContainer = document.createElement('div');
                                    followContainer.className = 'connections-grid';

                                    followingList.appendChild(followContainer);
                                    followingConnections = followContainer;
                                }

                                // Add user to following list if connections grid exists and user not already there
                                if (followingConnections) {
                                    const existingUser = Array.from(followingConnections.querySelectorAll(
                                            '.user-list-item'))
                                        .find(item => {
                                            const userEmailElement = item.querySelector('p');
                                            return userEmailElement && userEmailElement.textContent
                                                .toLowerCase().includes(targetUserEmail.toLowerCase());
                                        });

                                    if (!existingUser) {
                                        const followingHTML = `
<div class="user-list-item p-3 sm:p-4 rounded-lg flex items-center justify-between shadow">
    <div class="flex items-center">
        <a href="http://localhost:8000/viewUser/${data.data.targetEmail}">
            <img class="w-10 h-10 sm:w-12 sm:h-12 rounded-full mr-3 sm:mr-4 object-cover" src="https://ui-avatars.com/api/?name=${data.data.targetUsername}&amp;background=random&amp;color=fff&amp;size=64" alt="${data.data.targetUsername}'s avatar">
        </a>
        <div>
            <a href="http://localhost:8000/viewUser/${data.data.targetEmail}" class="font-semibold text-[var(--text-primary-dark)] hover:underline text-sm sm:text-base">
                ${data.data.targetUsername}
            </a>
            <p class="text-xs sm:text-sm text-[var(--text-muted-dark)]">${data.data.targetEmail}</p>
        </div>
    </div>

    <div class="ml-auto">
        <button class="follow-btn action-follow-list" data-user-email="${data.data.targetEmail}">
            Unfollow
        </button>
    </div>
</div>
                `;
                                        followingConnections.insertAdjacentHTML('beforeend', followingHTML);
                                        const newButton = followingConnections.querySelector(
                                            `.follow-btn[data-user-email="${data.data.targetEmail}"]`);
                                        if (newButton) {
                                            newButton.addEventListener('click', function() {
                                                handleFollowAction(this.getAttribute(
                                                    'data-user-email'), this);
                                            });
                                        }
                                    }
                                }
                            }
                        }

                        if (dataUserEmail != emailUrl) {
                            newButtonText = 'Follow';
                            statusClass = 'text-xs sm:text-sm btn-follow';
                        } else { // khusus user yang dilihat di url (user yang dilihat detailnya)
                            let followersCountOnNavTab = document.querySelector(
                                '.tab-navigation .tab-button span');
                            let followersCountOnStat = document.querySelector(
                                '.connection-stats .stat-item span');

                            followersCountOnNavTab.textContent = data.data.countFollowers;
                            followersCountOnStat.textContent = data.data.countFollowers;

                            if (newStatus === 'following') {
                                if (!connectionsGrid && emptyState) {
                                    emptyState.remove();

                                    const gridContainer = document.createElement('div');
                                    gridContainer.className = 'connections-grid';

                                    const parentContainer = emptyState.parentNode || document.querySelector(
                                        '.tab-content');
                                    if (parentContainer) {
                                        parentContainer.appendChild(gridContainer);
                                    }

                                    connectionsGrid = gridContainer;
                                }
                            } else if (newStatus === 'follow' || newStatus === 'follows_you') {
                                const remainingItems = document.querySelectorAll('.user-list-item');

                                if (remainingItems.length <= 1 && connectionsGrid) {
                                    connectionsGrid.remove();

                                    const emptyStateHTML = `
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-[var(--text-primary)] mb-3">No Followers Yet</h3>
                                <p class="text-[var(--text-muted)] max-w-md mx-auto">
                                    This user doesn't have any followers yet.
                                    Be the first to follow and connect!
                                </p>
                            </div>
                        `;

                                    const parentContainer = connectionsGrid.parentNode || document
                                        .querySelector('.tab-content');
                                    if (parentContainer) {
                                        parentContainer.insertAdjacentHTML('beforeend', emptyStateHTML);
                                    }
                                }
                            }
                        }

                        if (newStatus === 'following') { // aku follow dia
                            newButtonText = '<i class="fas fa-user-check mr-2"></i>Following';
                            statusClass = 'btn-unfollow';

                            if (dataUserEmail != emailUrl) {
                                newButtonText = 'Unfollow';
                                statusClass = 'text-xs sm:text-sm btn-unfollow';
                            } else {

                                if (connectionsGrid) {
                                    const userHTML = `
                            <div class="user-list-item p-3 sm:p-4 rounded-lg flex items-center justify-between shadow"> 
                                <div class="flex items-center">
                                    <a href="http://localhost:8000/viewUser/${loggedInemail}">
                                        <img class="w-10 h-10 sm:w-12 sm:h-12 rounded-full mr-3 sm:mr-4 object-cover" src="https://ui-avatars.com/api/?name=${encodeURIComponent(loggedInUsername)}&background=random&color=fff&size=64" alt="${loggedInUsername}'s avatar">
                                    </a>
                                    <div>
                                        <a href="http://localhost:8000/viewUser/${loggedInemail}" class="font-semibold text-[var(--text-primary-dark)] hover:underline text-sm sm:text-base">
                                            ${loggedInUsername}
                                        </a>
                                        <p class="text-xs sm:text-sm text-[var(--text-muted-dark)]">${loggedInemail}</p>
                                        <span class="text-xs text-blue-500 dark:text-blue-400">(This is you)</span>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                </div>
                            </div>
                        `;
                                    connectionsGrid.insertAdjacentHTML('beforeend', userHTML);
                                }
                            }

                            if (isMutual) {
                                newButtonText += ' <span class="status-badge">Mutual</span>';
                            }

                        } else if (newStatus === 'follows_you') { //dia follow aku, but aku tidak
                            newButtonText = 'Follow Back';
                            statusClass = 'btn-follow-back text-xs sm:text-sm';

                            if (dataUserEmail == emailUrl) {
                                newButtonText = '<i class="fas fa-user-plus mr-2"></i>Follow Back';
                                statusClass = 'btn-follow-back';
                                const matchText = loggedInemail;
                                const target = Array.from(document.querySelectorAll('.user-list-item'))
                                    .find(p => p.textContent.toLowerCase().includes(matchText
                                        .toLowerCase()));

                                if (target) {
                                    target.remove();
                                }
                            }

                        } else if (newStatus === 'follow') { //dia tidak follow aku, dan aku tidak
                            newButtonText = 'Follow';
                            statusClass = 'btn-follow text-xs sm:text-sm';

                            if (dataUserEmail == emailUrl) {
                                newButtonText = '<i class="fas fa-user-plus mr-2"></i>Follow';
                                statusClass = 'btn-follow';
                                const matchText = loggedInemail;
                                const target = Array.from(document.querySelectorAll('.user-list-item'))
                                    .find(p => p.textContent.toLowerCase().includes(matchText
                                        .toLowerCase()));

                                if (target) {
                                    target.remove();
                                }
                            }
                        }

                        document.querySelectorAll(`.follow-btn[data-user-email="${targetUserEmail}"]`)
                            .forEach(btn => {
                                btn.innerHTML = newButtonText;
                                btn.className = `${baseClasses} ${actionClass} ${statusClass}`.replace(
                                    /\s+/g, ' ').trim();
                            });

                    } else {
                        Toastify({
                            text: data.message || 'An error occurred.',
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#e74c3c"
                            }
                        }).showToast();
                        actionButton.innerHTML = originalButtonHTML;
                    }
                } catch (error) {
                    Toastify({
                        text: 'A network error occurred. Please try again.',
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                    console.error('Follow action error:', error);
                    actionButton.innerHTML = originalButtonHTML;
                } finally {
                    actionButton.disabled = false;
                }
            };

            document.querySelectorAll('.action-follow-profile, .action-follow-list').forEach(button => {
                button.addEventListener('click', function() {
                    const userEmail = button.getAttribute('data-user-email');
                    if (userEmail && button.classList.contains('follow-btn')) {
                        handleFollowAction(userEmail, button);
                    }
                });
            });
        });
    </script>
@endsection
