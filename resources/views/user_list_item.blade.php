{{-- resources/views/partials/user_list_item.blade.php --}}
{{-- 
    Variabel yang diharapkan:
    $userItem (array) - Data pengguna dalam daftar
    $loggedInUser (array|null) - Data pengguna yang sedang login
    $isOwnProfileContext (bool) - Apakah ini halaman profil milik pengguna yang login
--}}
@if($userItem && isset($userItem['email']))
<div class="user-list-item p-3 sm:p-4 rounded-lg flex items-center justify-between shadow">
    <div class="flex items-center">
        <a href="{{ route('viewUser', ['email' => $userItem['email']]) }}">
            <img class="w-10 h-10 sm:w-12 sm:h-12 rounded-full mr-3 sm:mr-4 object-cover"
                 src="{{ $userItem['image'] ? asset('storage/' . $userItem['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($userItem['username'] ?? 'U') . '&background=random&color=fff&size=64' }}"
                 alt="{{ $userItem['username'] ?? 'User' }}'s avatar">
        </a>
        <div>
            <a href="{{ route('viewUser', ['email' => $userItem['email']]) }}" class="font-semibold text-[var(--text-primary-dark)] hover:underline text-sm sm:text-base">
                {{ $userItem['username'] ?? 'Unknown User' }}
            </a>
            <p class="text-xs sm:text-sm text-[var(--text-muted-dark)]">{{ $userItem['email'] }}</p>
            @if (!$isOwnProfileContext && $loggedInUser && $userItem['email'] === $loggedInUser['email'])
                <span class="text-xs text-blue-500 dark:text-blue-400">(This is you)</span>
            @endif
        </div>
    </div>

    <div class="ml-auto">
        @if ($loggedInUser && isset($userItem['follow_status']))
            @if ($userItem['follow_status'] === 'is_self')
                {{-- Tidak ada tombol untuk diri sendiri di daftar, kecuali jika ini profil orang lain dan "This is you" sudah ditampilkan --}}
                @if($isOwnProfileContext)
                     {{-- <a href="{{ route('profile.edit') }}" class="follow-btn btn-unfollow text-xs">Edit Profile</a> --}}
                @endif
            @else
                <button class="follow-btn action-follow-list text-xs sm:text-sm {{ 
                        $userItem['follow_status'] === 'following' ? 'btn-unfollow' : 
                        ($userItem['follow_status'] === 'follows_you' ? 'btn-follow-back' : 'btn-follow') 
                    }}" data-user-email="{{ $userItem['email'] }}">
                    @if ($userItem['follow_status'] === 'following')
                        Unfollow
                        @if ($userItem['is_mutual'])
                            <span class="status-badge mutual-badge">Mutual</span>
                        @endif
                    @elseif ($userItem['follow_status'] === 'follows_you')
                        Follow Back
                    @else
                        Follow
                    @endif
                </button>
            @endif
        @elseif (!$loggedInUser && $userItem['email'] !== ($profileUser['email'] ?? '')) {{-- Tombol follow jika tidak login & bukan profil utama--}}
            <a href="{{ route('login') }}" class="follow-btn btn-follow text-xs sm:text-sm">Follow</a>
        @endif
    </div>
</div>
@else
 @endif