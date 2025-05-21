{{-- resources/views/users/connections.blade.php --}}
@extends('layout') {{-- Sesuaikan dengan nama layout utama Anda --}}

@section('title', $title ?? 'User Connections')

@section('style')
    <style>
        body {
            background-color: var(--bg-main-dark, #1A1A1A);
            color: var(--text-primary-dark, #E0E0E0);
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            font-weight: 600;
            color: var(--text-secondary-dark, #B0B0B0);
        }

        .tab-button.active {
            border-bottom-color: var(--accent-primary-dark-theme, #7E57C2);
            color: var(--text-primary-dark, #E0E0E0);
        }

        .tab-button:hover {
            background-color: var(--bg-hover-dark, #333333);
        }

        .tab-content {
            display: none !important;
            /* Menyembunyikan konten tab yang tidak aktif */
        }

        .tab-content.active {
            display: block !important;
            /* Menampilkan konten tab yang aktif */
        }

        .user-list-item {
            background-color: var(--bg-card-dark, #2C2C2C);
            border: 1px solid var(--border-color-dark, #444);
            transition: box-shadow 0.2s, background-color 0.2s;
        }

        .user-list-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background-color: var(--bg-card-hover-dark, #383838);
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
            background-color: var(--accent-primary-dark-theme, #7E57C2);
            color: white;
        }

        .btn-follow:hover {
            background-color: var(--accent-primary-dark-dark-theme, #673AB7);
        }

        .btn-unfollow {
            background-color: var(--bg-button-secondary-dark, #4A4A4A);
            color: var(--text-button-secondary-dark, #E0E0E0);
            border-color: var(--border-button-secondary-dark, #606060);
        }

        .btn-unfollow:hover {
            background-color: var(--bg-button-secondary-hover-dark, #555555);
        }

        .btn-follow-back {
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
            color: #1a642d;
            border: 1px solid #28a74590;
        }

       
    </style>
@endsection

@section('content')
    @include('partials.nav', ['loggedInUser' => $loggedInUser])

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header Profil Pengguna --}}
        <div class="header-card flex flex-col sm:flex-row items-center mb-6 p-4 rounded-lg shadow-md"
            style="background-color: var(--bg-card-dark, #2C2C2C);">
            <img class="w-20 h-20 sm:w-24 sm:h-24 rounded-full mr-0 sm:mr-6 mb-4 sm:mb-0 border-2 border-[var(--accent-primary-dark-theme, #7E57C2)] object-cover"
                src="{{ $profileUser['image'] ? asset('storage/' . $profileUser['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($profileUser['username'] ?? 'User') . '&background=7E57C2&color=fff&size=128' }}"
                alt="{{ $profileUser['username'] ?? 'User' }}'s avatar">
            <div class="text-center sm:text-left">
                <h1 class="text-2xl sm:text-3xl font-bold text-[var(--text-primary-dark, #E0E0E0)]">
                    {{ $profileUser['username'] ?? 'User Profile' }}</h1>
                <p class="text-[var(--text-secondary-dark, #B0B0B0)]">{{ $profileUser['email'] ?? '' }}</p>
                @if ($profileUser['biodata'])
                    <p class="text-sm text-[var(--text-muted-dark, #808080)] mt-1">{{ $profileUser['biodata'] }}</p>
                @endif

                @if ($loggedInUser && !$isOwnProfile)
                    @php
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
                        <a href="{{ route('loginOrRegist') }}" class="follow-btn btn-follow">Follow</a>
                    </div>
                @elseif($isOwnProfile)
                    <div class="mt-3">
                        {{-- <a href="{{ route('profile.edit') }}" class="follow-btn btn-unfollow">Edit Profile</a> --}}
                    </div>
                @endif
            </div>
        </div>

        {{-- Navigasi Tab --}}
        <div class="mb-6 border-b border-[var(--border-color-dark, #444)]">
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
            {{-- ID "followersContent" unik dan digunakan oleh JavaScript --}}
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
                        <i class="fas fa-user-friends text-4xl text-[var(--text-muted-dark, #808080)] mb-3"></i>
                        <p class="text-[var(--text-muted-dark, #808080)]">{{ $profileUser['username'] ?? 'This user' }}
                            doesn't have
                            any followers yet.</p>
                    </div>
                @endif
            </div>

            {{-- Konten Tab Following --}}
            {{-- ID "followingContent" unik dan digunakan oleh JavaScript --}}
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
                        <i class="fas fa-users text-4xl text-[var(--text-muted-dark, #808080)] mb-3"></i>
                        <p class="text-[var(--text-muted-dark, #808080)]">{{ $profileUser['username'] ?? 'This user' }}
                            isn't
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
            // const profileUserEmail = "{{ $profileUser['email'] }}"; // Tidak digunakan langsung di fungsi tab

            function updateURLAndActivateTab(tabName) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('type', tabName);
                // Update URL dan tambahkan hash untuk pengalaman pengguna yang lebih baik
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
                    content.classList.remove('active'); // Hapus kelas active dari semua konten tab
                    if (content.id === tabName + 'Content') {
                        content.classList.add('active'); // Tambahkan kelas active pada konten yang sesuai
                    }
                });
            }


            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabName = tab.getAttribute('data-tab-name');
                    if (tabName) { // Pastikan tabName ada
                        updateURLAndActivateTab(tabName);
                    }
                });
            });

            // Inisialisasi tab berdasarkan URL (query param 'type' atau hash)
            const currentUrlParams = new URLSearchParams(window.location.search);
            const typeParam = currentUrlParams.get('type');
            const hash = window.location.hash.substring(1); // Hapus '#'
            let initialTab = 'followers'; // Default tab

            if (typeParam && (typeParam === 'followers' || typeParam === 'following')) {
                initialTab = typeParam;
            } else if (hash && (hash === 'followers' || hash === 'following')) {
                initialTab = hash;
            }

            // Pastikan tab awal valid sebelum mengaktifkannya
            const initialTabElement = document.querySelector(`.tab-button[data-tab-name="${initialTab}"]`);
            if (initialTabElement) {
                updateURLAndActivateTab(initialTab);
            } else {
                // Jika tab awal tidak valid (misalnya, URL yang salah), aktifkan tab pertama yang tersedia
                const firstAvailableTab = document.querySelector('.tab-button');
                if (firstAvailableTab) {
                    updateURLAndActivateTab(firstAvailableTab.getAttribute('data-tab-name'));
                }
            }

            // Handle navigasi back/forward browser
            window.addEventListener('popstate', function(event) {
                const stateType = event.state ? event.state.type : null;
                const currentHash = window.location.hash.substring(1);
                let tabToActivate = 'followers'; // Default

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
                        // Ambil kelas dasar dan kelas spesifik tombol (action-follow-list/profile)
                        const baseClasses = 'follow-btn';
                        const actionClass = actionButton.classList.contains('action-follow-profile') ?
                            'action-follow-profile' : (actionButton.classList.contains(
                                'action-follow-list') ? 'action-follow-list' : '');
                        let statusClass = 'btn-follow';


                        if (newStatus === 'following') {
                            newButtonText = 'Unfollow';
                            statusClass = 'btn-unfollow';
                            if (isMutual) {
                                newButtonText += ' <span class="status-badge mutual-badge">Mutual</span>';
                            }
                        } else if (newStatus === 'follows_you') {
                            newButtonText = 'Follow Back';
                            statusClass = 'btn-follow-back';
                        }

                        // Update semua tombol yang relevan di halaman
                        document.querySelectorAll(`.follow-btn[data-user-email="${targetUserEmail}"]`)
                            .forEach(btn => {
                                btn.innerHTML = newButtonText;
                                // Hapus kelas status lama dan tambahkan yang baru, sambil mempertahankan kelas aksi
                                btn.className = `${baseClasses} ${actionClass} ${statusClass}`.replace(
                                    /\s+/g, ' ').trim();
                            });

                    } else {
                        Swal.fire('Error', data.message || 'An error occurred.', 'error');
                        actionButton.innerHTML = originalButtonHTML; // Kembalikan jika error
                    }
                } catch (error) {
                    console.error('Follow action error:', error);
                    Swal.fire('Error', 'A network error occurred. Please try again.', 'error');
                    actionButton.innerHTML = originalButtonHTML; // Kembalikan jika error
                } finally {
                    actionButton.disabled = false;
                }
            };

            document.querySelectorAll('.action-follow-profile, .action-follow-list').forEach(button => {
                button.addEventListener('click', function() {
                    const userEmail = this.getAttribute('data-user-email');
                    if (userEmail && this.classList.contains(
                            'follow-btn')) { // Pastikan itu tombol follow
                        handleFollowAction(userEmail, this);
                    }
                });
            });

            // Logika Theme Switcher (sesuaikan dengan implementasi Anda)
            // const themeToggle = document.getElementById('theme-toggle');
            // if (themeToggle) {
            //     themeToggle.addEventListener('click', () => {
            //         const isLightMode = document.documentElement.classList.toggle('light-mode');
            //         localStorage.setItem('theme', isLightMode ? 'light' : 'dark');
            //         // Jika Anda memiliki variabel CSS yang perlu diupdate secara dinamis:
            //         // updateThemeVariables(isLightMode); 
            //     });
            // }

            // // Inisialisasi tema saat halaman dimuat
            // function applyInitialTheme() {
            //     const savedTheme = localStorage.getItem('theme');
            //     const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
            //     let currentThemeIsLight = false;

            //     if (savedTheme === 'light') {
            //         currentThemeIsLight = true;
            //     } else if (savedTheme === 'dark') {
            //         currentThemeIsLight = false;
            //     } else if (prefersLight) {
            //         currentThemeIsLight = true;
            //     }

            //     if (currentThemeIsLight) {
            //         if(!document.documentElement.classList.contains('light-mode')) {
            //            // document.documentElement.classList.add('light-mode');
            //         }
            //     } else {
            //         if(document.documentElement.classList.contains('light-mode')) {
            //            // document.documentElement.classList.remove('light-mode');
            //         }
            //     }
            //     // Jika tema diatur di tag <html> oleh script lain (dari layout utama),
            //     // baris di atas untuk add/remove 'light-mode' mungkin tidak diperlukan.
            // }
            // applyInitialTheme();
        });
    </script>
@endsection
