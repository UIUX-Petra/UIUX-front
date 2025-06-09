{{-- resources/views/partials/user_card.blade.php --}}
<div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-xl p-4 flex items-center justify-between transition-all duration-300 relative overflow-hidden shadow-[0_2px_8px_rgba(0,0,0,0.04)] min-h-[80px] no-underline cursor-pointer hover:transform hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(0,0,0,0.12)]  hover:no-underline group">
    
    {{-- Hover gradient top border --}}
    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#38A3A5] to-[#80ED99] opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
    
    <div class="flex items-center gap-4 flex-1">
        {{-- Avatar with floating effect --}}
        <div class="relative flex-shrink-0">
            <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['username'] ?? 'User') . '&background=38A3A5&color=fff&size=80' }}"
                 alt="Profile Picture" class="w-12 h-12 rounded-full object-cover border-2 border-[var(--bg-secondary)] transition-transform duration-300 relative z-[2] group-hover:scale-105">
            
            {{-- Avatar ring --}}
            <div class="absolute -top-0.5 -left-0.5 -right-0.5 -bottom-0.5 rounded-full bg-gradient-to-br from-[#38A3A5] to-[#80ED99] opacity-0 transition-opacity duration-300 z-[1] group-hover:opacity-100"></div>
        </div>

        {{-- User Info --}}
        <div class="flex flex-col gap-1 flex-1 min-w-0">
            <h3 class="font-semibold text-lg text-[var(--text-primary)] m-0 whitespace-nowrap overflow-hidden text-ellipsis transition-colors duration-200 group-hover:text-[var(--text-highlight)]">{{ $user['username'] }}</h3>
            
            {{-- Main Stat --}}
            <div class="flex items-center gap-2 text-sm text-[var(--text-secondary)]">
                @if($type === 'reputation')
                    <i class="fa-solid fa-star text-sm text-[var(--accent-tertiary)]"></i>
                    <span class="font-semibold text-[var(--text-primary)]">{{ number_format($user['reputation'] ?? 0) }}</span>
                    <span class="text-[var(--text-secondary)] text-xs">reputation</span>
                @elseif($type === 'newest')
                    <i class="fa-solid fa-calendar-alt text-sm text-[var(--accent-primary)]"></i>
                    <span class="text-[var(--text-secondary)] text-xs">joined {{ \Carbon\Carbon::parse($user['created_at'])->diffForHumans() }}</span>
                @elseif($type === 'voter')
                    <i class="fa-solid fa-thumbs-up text-sm text-[var(--accent-secondary)]"></i>
                    <span class="font-semibold text-[var(--text-primary)]">{{ number_format($user['vote_count'] ?? 0) }}</span>
                    <span class="text-[var(--text-secondary)] text-xs">votes given</span>
                @endif
            </div>
        </div>
    </div>

    {{-- View Profile Arrow --}}
    <a href="{{ route('viewUser', ['email' => $user['email']]) }}" class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-r from-[#38A3A5] to-[#80ED99] flex items-center justify-center text-white transition-all duration-300 opacity-80 group-hover:opacity-100 group-hover:scale-110 group-hover:shadow-[0_4px_12px_rgba(56,163,165,0.3)]">
        <i class="fa-solid fa-arrow-right text-sm transition-transform duration-200 group-hover:translate-x-0.5"></i>
    </a>
</div>