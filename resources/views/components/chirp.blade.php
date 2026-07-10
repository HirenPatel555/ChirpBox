@props(['chirp'])

<div class="bg-bgCard rounded-2xl border border-borderSubtle p-5 hover:bg-bgCardHover transition-all duration-300 space-y-3" id="chirp-card-{{ $chirp->id }}">
    <div class="flex items-start justify-between gap-4">
        <!-- User Info Details -->
        <div class="flex items-center gap-3">
            @if ($chirp->user)
                <a href="{{ route('profiles.show', $chirp->user) }}" class="avatar hover:opacity-90 transition-opacity shrink-0">
                    <img src="{{ $chirp->user->avatar_url }}" alt="{{ $chirp->user->name }}'s avatar" class="size-8 rounded-full border border-borderSubtle/50 object-cover" />
                </a>
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                    <a href="{{ route('profiles.show', $chirp->user) }}" class="font-body font-semibold text-textPrimary hover:underline text-sm leading-none">
                        {{ $chirp->user->name }}
                    </a>
                    <span class="font-mono text-textMuted text-xs">
                        @{{ Str::lower(str_replace(' ', '', $chirp->user->name)) }}
                    </span>
                </div>
            @else
                <div class="relative flex items-center justify-center p-[2px] rounded-full bg-borderSubtle">
                    <img src="https://avatars.laravel.cloud/f61123d5-0b27-434c-a4ae-c653c7fc9ed6?vibe=stealth" alt="Anonymous User" class="size-8 rounded-full border border-bgBase object-cover" />
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                    <span class="font-body font-semibold text-textPrimary text-sm leading-none">Anonymous</span>
                    <span class="font-mono text-textMuted text-xs">@anonymous</span>
                </div>
            @endif

            <!-- Follow indicator -->
            @auth
                @if ($chirp->user && auth()->id() !== $chirp->user_id)
                    <span class="text-textMuted font-mono text-xs hidden sm:inline">·</span>
                    <form method="POST" action="/users/{{ $chirp->user->id }}/follow" class="inline">
                        @csrf
                        <button type="submit" class="font-body font-semibold text-xs transition-colors {{ auth()->user()->isFollowing($chirp->user) ? 'text-accentCoral hover:text-opacity-80' : 'text-accentViolet hover:text-opacity-80' }}">
                            {{ auth()->user()->isFollowing($chirp->user) ? 'Unfollow' : 'Follow' }}
                        </button>
                    </form>
                @endif
            @endauth

            <span class="text-textMuted font-mono text-xs">·</span>
            <span class="font-mono text-textMuted text-xs">{{ $chirp->created_at->diffForHumans() }}</span>

            @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
                <span class="text-textMuted font-mono text-xs">·</span>
                <span class="font-mono text-textMuted text-xs italic">edited</span>
            @endif
        </div>

        <!-- Edit/Delete Dropdown Context Menu -->
        @if (auth()->check() && auth()->id() === $chirp->user_id)
            <div class="dropdown dropdown-end">
                <button tabindex="0" class="btn btn-ghost btn-circle btn-xs text-textMuted hover:text-textPrimary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
                <ul tabindex="0" class="dropdown-content menu p-1 shadow-md bg-bgCard border border-borderSubtle rounded-box w-28 text-textPrimary z-[1]">
                    <li>
                        <a href="/chirps/{{ $chirp->id }}/edit" class="font-body text-xs hover:bg-bgCardHover">
                            Edit
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="/chirps/{{ $chirp->id }}" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this chirp?')" class="w-full text-left font-body text-xs text-accentCoral hover:bg-bgCardHover">
                                Delete
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>

    <!-- Chirp Content Message -->
    <p class="font-body text-textPrimary text-base leading-relaxed break-words whitespace-pre-wrap">{{ $chirp->message }}</p>

    <!-- Action Row Bottom -->
    <div class="flex items-center gap-6 pt-2 border-t border-borderSubtle/50 text-textMuted font-mono">
        @include('chirps.like', ['chirp' => $chirp])

        @if (!$chirp->parent_id)
            <a href="/chirps/{{ $chirp->id }}" class="hover:text-textPrimary transition-colors flex items-center gap-1.5 text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 fill-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379L12 21l3.12-3.134c1.152-.086 2.294-.213 3.423-.379 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
                <span>{{ $chirp->replies()->count() }}</span>
            </a>
        @endif
    </div>
</div>