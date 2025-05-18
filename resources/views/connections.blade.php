{{-- resources/views/users/connections.blade.php --}}
@extends('layout') {{-- Sesuaikan dengan nama layout utama Anda --}}

@section('title', $title ?? 'User Connections')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Variabel warna (sesuaikan dengan tema Anda dari layout.blade.php jika ada) */
        :root {
            --text-primary-light: #212529;
            /* Contoh untuk light mode */
            --text-secondary-light: #6C757D;
            --text-muted-light: #ADB5BD;
            --bg-main-light: #F8F9FA;
            --bg-card-light: #FFFFFF;
            --bg-card-hover-light: #F1F3F5;
            --bg-hover-light: #E9ECEF;
            --border-color-light: #DEE2E6;
            --accent-primary-light: #007BFF;
            --accent-primary-dark-light: #0056b3;
            --bg-button-secondary-light: #6C757D;
            --text-button-secondary-light: #FFFFFF;
            --border-button-secondary-light: #6C757D;
            --bg-button-secondary-hover-light: #5A6268;

            --text-primary-dark: #E0E0E0;
            --text-secondary-dark: #B0B0B0;
            --text-muted-dark: #808080;
            --bg-main-dark: #1A1A1A;
            --bg-card-dark: #2C2C2C;
            --bg-card-hover-dark: #383838;
            --bg-hover-dark: #333333;
            --border-color-dark: #444;
            --accent-primary-dark-theme: #7E57C2;
            /* Ungu */
            --accent-primary-dark-dark-theme: #673AB7;
            --bg-button-secondary-dark: #4A4A4A;
            --text-button-secondary-dark: #E0E0E0;
            --border-button-secondary-dark: #606060;
            --bg-button-secondary-hover-dark: #555555;
        }

        /* Default ke Dark Theme, atau atur berdasarkan class di <html> */
        body {
            background-color: var(--bg-main-dark);
            color: var(--text-primary-dark);
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            font-weight: 600;
            color: var(--text-secondary-dark);
        }

        .tab-button.active {
            border-bottom-color: var(--accent-primary-dark-theme);
            color: var(--text-primary-dark);
        }

        .tab-button:hover {
            background-color: var(--bg-hover-dark);
        }

        .tab-content {
            display: none !important;
        }

        .tab-content.active {
            display: block !important;
        }

        .user-list-item {
            background-color: var(--bg-card-dark);
            border: 1px solid var(--border-color-dark);
            transition: box-shadow 0.2s, background-color 0.2s;
        }

        .user-list-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background-color: var(--bg-card-hover-dark);
        }

        .follow-btn {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s, border-color 0.2s;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-follow {
            background-color: var(--accent-primary-dark-theme);
            color: white;
        }

        .btn-follow:hover {
            background-color: var(--accent-primary-dark-dark-theme);
        }

        .btn-unfollow {
            background-color: var(--bg-button-secondary-dark);
            color: var(--text-button-secondary-dark);
            border-color: var(--border-button-secondary-dark);
        }

        .btn-unfollow:hover {
            background-color: var(--bg-button-secondary-hover-dark);
        }

        .btn-follow-back {
            /* Bisa sama dengan btn-follow atau style sendiri */
            background-color: var(--accent-secondary, #28a745);
            /* Contoh warna hijau */
            color: white;
        }

        .btn-follow-back:hover {
            background-color: var(--accent-secondary-dark, #1e7e34);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            margin-left: 0.5rem;
            font-weight: normal;
        }

        .mutual-badge {
            background-color: #28a74520;
            /* Hijau muda transparan */
            color: #1a642d;
            /* Hijau tua */
            border: 1px solid #28a74590;
        }

        /* Light mode specific styles */
        html.light-mode body {
            background-color: var(--bg-main-light);
            color: var(--text-primary-light);
        }

        html.light-mode .tab-button {
            color: var(--text-secondary-light);
        }

        html.light-mode .tab-button.active {
            border-bottom-color: var(--accent-primary-light);
            color: var(--text-primary-light);
        }

        html.light-mode .tab-button:hover {
            background-color: var(--bg-hover-light);
        }

        html.light-mode .user-list-item {
            background-color: var(--bg-card-light);
            border: 1px solid var(--border-color-light);
        }

        html.light-mode .user-list-item:hover {
            background-color: var(--bg-card-hover-light);
        }

        html.light-mode .btn-follow {
            background-color: var(--accent-primary-light);
        }

        html.light-mode .btn-follow:hover {
            background-color: var(--accent-primary-dark-light);
        }

        html.light-mode .btn-unfollow {
            background-color: var(--bg-button-secondary-light);
            color: var(--text-button-secondary-light);
            border-color: var(--border-button-secondary-light);
        }

        html.light-mode .btn-unfollow:hover {
            background-color: var(--bg-button-secondary-hover-light);
        }

        html.light-mode .header-card {
            /* Tambahkan class ini di div header profil */
            background-color: var(--bg-card-light) !important;
            /* Override inline style jika ada */
        }

        html.light-mode .header-card h1,
        html.light-mode .header-card p {
            color: var(--text-primary-light) !important;
        }

        html.light-mode .header-card p.text-\[var\(--text-secondary\)\] {
            color: var(--text-secondary-light) !important;
        }

        html.light-mode .header-card p.text-\[var\(--text-muted\)\] {
            color: var(--text-muted-light) !important;
        }
    </style>
@endsection

@section('content')
    {{-- Asumsi Anda punya partials nav yang menerima $loggedInUser --}}
    @include('partials.nav', ['loggedInUser' => $loggedInUser])
    {{-- @include('utils.background') --}}

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header Profil Pengguna --}}
        <div class="header-card flex flex-col sm:flex-row items-center mb-6 p-4 rounded-lg shadow-md"
            style="background-color: var(--bg-card-dark);">
            <img class="w-20 h-20 sm:w-24 sm:h-24 rounded-full mr-0 sm:mr-6 mb-4 sm:mb-0 border-2 border-[var(--accent-primary-dark-theme)] object-cover"
                src="{{ $profileUser['image'] ? asset('storage/' . $profileUser['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($profileUser['username'] ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                alt="{{ $profileUser['username'] ?? 'User' }}'s avatar">
            <div class="text-center sm:text-left">
                <h1 class="text-2xl sm:text-3xl font-bold text-[var(--text-primary-dark)]">
                    {{ $profileUser['username'] ?? 'User Profile' }}</h1>
                <p class="text-[var(--text-secondary-dark)]">{{ $profileUser['email'] ?? '' }}</p>
                @if ($profileUser['biodata'])
                    <p class="text-sm text-[var(--text-muted-dark)] mt-1">{{ $profileUser['biodata'] }}</p>
                @endif

                {{-- Tombol Follow/Unfollow untuk profil utama yang sedang dilihat --}}
                @if ($loggedInUser && !$isOwnProfile)
                    @php
                        // $profileUser sudah memiliki 'current_user_relation' dari controller
                        $relation = $profileUser['current_user_relation'] ?? [
                            'follow_status' => 'not_following',
                            'is_mutual' => false,
                        ];
                    @endphp
                    <div class="mt-3">
                        <button
                            class="follow-btn action-follow-profile {{ $relation['follow_status'] === 'following'
                                ? 'btn-unfollow'
                                : ($relation['follow_status'] === 'follows_you'
                                    ? 'btn-follow-back'
                                    : 'btn-follow') }}"
                            data-user-email="{{ $profileUser['email'] }}">
                            @if ($relation['follow_status'] === 'following')
                                Unfollow
                                @if ($relation['is_mutual'])
                                    <span class="status-badge mutual-badge">Mutual</span>
                                @endif
                            @elseif ($relation['follow_status'] === 'follows_you')
                                Follow Back
                            @else
                                Follow
                            @endif
                        </button>
                    </div>
                @elseif (!$loggedInUser && !$isOwnProfile)
                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="follow-btn btn-follow">Follow</a>
                    </div>
                @elseif($isOwnProfile)
                    <div class="mt-3">
                        {{-- <a href="{{ route('profile.edit') }}" class="follow-btn btn-unfollow">Edit Profile</a> --}}
                    </div>
                @endif
            </div>
        </div>

        {{-- Navigasi Tab --}}
        <div class="mb-6 border-b border-[var(--border-color-dark)]">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button class="tab-button {{ $activeTab === 'followers' ? 'active' : '' }}"
                    data-tab-target="#followersContent" data-tab-name="followers">
                    Followers ({{ $followersList->count() }})
                </button>
                <button class="tab-button {{ $activeTab === 'following' ? 'active' : '' }}"
                    data-tab-target="#followingContent" data-tab-name="following">
                    Following ({{ $followingList->count() }})
                </button>
            </nav>
        </div>

        {{-- Konten Tab --}}
        <div>
            {{-- Konten Tab Followers --}}
            <div id="followersContent" class="tab-content {{ $activeTab === 'followers' ? 'active' : '' }}">
                @if ($followersList->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($followersList as $item)
                            @include('user_list_item', [
                                'userItem' => $item,
                                'loggedInUser' => $loggedInUser,
                                'isOwnProfileContext' => $isOwnProfile,
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <i class="fas fa-user-friends text-4xl text-[var(--text-muted-dark)] mb-3"></i>
                        <p class="text-[var(--text-muted-dark)]">{{ $profileUser['username'] ?? 'This user' }} doesn't have
                            any followers yet.</p>
                    </div>
                @endif
            </div>

            {{-- Konten Tab Following --}}
            <div id="followingContent" class="tab-content {{ $activeTab === 'following' ? 'active' : '' }}">
                @if ($followingList->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($followingList as $item)
                            @include('user_list_item', [
                                'userItem' => $item,
                                'loggedInUser' => $loggedInUser,
                                'isOwnProfileContext' => $isOwnProfile,
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <i class="fas fa-users text-4xl text-[var(--text-muted-dark)] mb-3"></i>
                        <p class="text-[var(--text-muted-dark)]">{{ $profileUser['username'] ?? 'This user' }} isn't
                            following anyone yet.</p>
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
            const profileUserEmail = "{{ $profileUser['email'] }}"; // Digunakan untuk URL

            function updateURLAndActivateTab(tabName) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('type', tabName);
                // Update URL tanpa reload, dan juga ubah hash untuk user experience
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

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabName = tab.getAttribute('data-tab-name');
                    updateURLAndActivateTab(tabName);
                });
            });

            // Inisialisasi tab berdasarkan URL (baik query param 'type' atau hash)
            const currentUrlParams = new URLSearchParams(window.location.search);
            const typeParam = currentUrlParams.get('type');
            const hash = window.location.hash.substring(1); // Hapus '#'
            let initialTab = 'followers';

            if (typeParam && (typeParam === 'followers' || typeParam === 'following')) {
                initialTab = typeParam;
            } else if (hash && (hash === 'followers' || hash === 'following')) {
                initialTab = hash;
            }

            // Set tab aktif awal dan pastikan URL konsisten
            updateURLAndActivateTab(initialTab);


            window.addEventListener('popstate', function(event) {
                // Handle navigasi back/forward browser
                const stateType = event.state ? event.state.type : null;
                const currentHash = window.location.hash.substring(1);
                let tabToActivate = 'followers';

                if (stateType && (stateType === 'followers' || stateType === 'following')) {
                    tabToActivate = stateType;
                } else if (currentHash && (currentHash === 'followers' || currentHash === 'following')) {
                    tabToActivate = currentHash;
                }
                updateURLAndActivateTab(tabToActivate);
            });


            const handleFollowAction = async (targetUserEmail, actionButton) => {
                const originalButtonHTML = actionButton.innerHTML;
                actionButton.disabled = true;
                actionButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                try {
                    const response = await fetch("{{ route('user.toggleFollow') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: targetUserEmail
                        })
                    });

                    const data = await response.json();

                    if (data.ok && data.data) {
                        const newStatus = data.data.status;
                        const isMutual = data.data.is_mutual;
                        let newButtonText = 'Follow';
                        let newButtonClasses = 'follow-btn btn-follow'; // Default classes

                        if (newStatus === 'following') {
                            newButtonText = 'Unfollow';
                            newButtonClasses = 'follow-btn btn-unfollow';
                            if (isMutual) {
                                newButtonText += ' <span class="status-badge mutual-badge">Mutual</span>';
                            }
                        } else if (newStatus === 'follows_you') {
                            newButtonText = 'Follow Back';
                            newButtonClasses = 'follow-btn btn-follow-back';
                        }
                        // Else: defaultnya 'Follow' dan 'btn-follow'

                        actionButton.innerHTML = newButtonText;
                        // Hanya update kelas utama, biarkan action-follow-list/profile tetap
                        actionButton.className = actionButton.className.replace(
                                /btn-(follow|unfollow|follow-back)/g, '').trim() + ' ' + newButtonClasses
                            .split(' ')[1];


                        // Jika tombol ada di header profile utama, update juga teks dan kelasnya
                        if (actionButton.classList.contains('action-follow-profile')) {
                            // Tidak perlu reload, UI sudah diupdate
                        } else {
                            // Untuk tombol di daftar, update juga tombol di header jika targetnya sama
                            const headerButton = document.querySelector(
                                `.action-follow-profile[data-user-email="${targetUserEmail}"]`);
                            if (headerButton) {
                                headerButton.innerHTML = newButtonText;
                                headerButton.className = headerButton.className.replace(
                                        /btn-(follow|unfollow|follow-back)/g, '').trim() + ' ' +
                                    newButtonClasses.split(' ')[1];
                            }
                        }
                        // Pertimbangkan untuk reload atau update jumlah followers/following jika diperlukan
                        // Untuk kesederhanaan, kita tidak reload di sini, tapi idealnya API bisa return count baru
                        // atau kita bisa buat AJAX call lain untuk update count.
                        // Contoh:
                        // updateFollowerCountOnPage(data.newFollowerCount);

                    } else {
                        Swal.fire('Error', data.message || 'An error occurred.', 'error');
                        actionButton.innerHTML = originalButtonHTML;
                    }
                } catch (error) {
                    console.error('Follow action error:', error);
                    Swal.fire('Error', 'A network error occurred. Please try again.', 'error');
                    actionButton.innerHTML = originalButtonHTML;
                } finally {
                    actionButton.disabled = false;
                }
            };

            document.querySelectorAll('.action-follow-profile, .action-follow-list').forEach(button => {
                button.addEventListener('click', function() {
                    const userEmail = this.getAttribute('data-user-email');
                    if (userEmail) {
                        handleFollowAction(userEmail, this);
                    }
                });
            });
            // Theme switcher logic (jika Anda memilikinya di layout utama)
            const themeToggle = document.getElementById('theme-toggle'); // Asumsi ID tombol Anda
            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    document.documentElement.classList.toggle('light-mode');
                    // Simpan preferensi tema jika perlu
                });
            }
            // Inisialisasi tema saat load
            if (localStorage.getItem('theme') === 'light' || (!('theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: light)').matches)) {
                // document.documentElement.classList.add('light-mode'); // Hapus ini jika tema diatur di <html> oleh script lain
            } else {
                // document.documentElement.classList.remove('light-mode'); // Hapus ini jika tema diatur di <html>
            }
        });
    </script>
@endsection
