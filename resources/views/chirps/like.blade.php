<div class="flex items-center space-x-1" id="like-container-{{ $chirp->id }}">
    @auth
        <button 
            type="button"
            onclick="toggleLike({{ $chirp->id }})"
            class="btn btn-link btn-xs no-underline text-textMuted hover:text-accentCoral flex items-center gap-1.5 focus:outline-none p-0"
            id="like-button-{{ $chirp->id }}"
        >
            <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="size-4 transition-all duration-300 {{ $chirp->isLikedBy(auth()->user()) ? 'fill-accentCoral text-accentCoral' : 'fill-none text-current' }}" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
                stroke-width="2"
                id="like-icon-{{ $chirp->id }}"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
            <span id="like-count-{{ $chirp->id }}" class="text-xs font-semibold">{{ $chirp->likesCount() }}</span>
        </button>
    @else
        <a 
            href="/login"
            class="btn btn-link btn-xs no-underline text-textMuted hover:text-accentCoral flex items-center gap-1.5 p-0"
        >
            <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="size-4 fill-none text-current" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
                stroke-width="2"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
            <span class="text-xs font-semibold">{{ $chirp->likesCount() }}</span>
        </a>
    @endauth
</div>
