@props(['chirp'])

<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex space-x-3">
            @if ($chirp->user)
                <a href="{{ route('profiles.show', $chirp->user) }}" class="avatar hover:opacity-85 transition-opacity">
                    <div class="size-10 rounded-full">
                        <img src="{{ $chirp->user->avatar_url }}"
                            alt="{{ $chirp->user->name }}'s avatar" class="rounded-full" />
                    </div>
                </a>
            @else
                <div class="avatar placeholder">
                    <div class="size-10 rounded-full">
                        <img src="https://avatars.laravel.cloud/f61123d5-0b27-434c-a4ae-c653c7fc9ed6?vibe=stealth"
                            alt="Anonymous User" class="rounded-full" />
                    </div>
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <div class="flex justify-between w-full">
                    <div class="flex items-center gap-1">
                        @if ($chirp->user)
                            <a href="{{ route('profiles.show', $chirp->user) }}" class="text-sm font-semibold link link-hover text-base-content">
                                {{ $chirp->user->name }}
                            </a>
                        @else
                            <span class="text-sm font-semibold text-base-content">Anonymous</span>
                        @endif

                        @auth
                            @if ($chirp->user && auth()->id() !== $chirp->user_id)
                                <span class="text-base-content/60">·</span>
                                <form method="POST" action="/users/{{ $chirp->user->id }}/follow" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold link link-hover {{ auth()->user()->isFollowing($chirp->user) ? 'text-error hover:text-error-hover' : 'text-primary hover:text-primary-hover' }}">
                                        {{ auth()->user()->isFollowing($chirp->user) ? 'Unfollow' : 'Follow' }}
                                    </button>
                                </form>
                            @endif
                        @endauth

                        <span class="text-base-content/60">·</span>
                        <span class="text-sm text-base-content/60">{{ $chirp->created_at->diffForHumans() }}</span>
                        @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
                            <span class="text-base-content/60">·</span>
                            <span class="text-sm text-base-content/60 italic">edited</span>
                        @endif
                    </div>

                    @if (auth()->check() && auth()->id() === $chirp->user_id)
                        <div class="flex gap-1">
                            <a href="/chirps/{{ $chirp->id }}/edit" class="btn btn-ghost btn-xs">
                                Edit
                            </a>
                            <form method="POST" action="/chirps/{{ $chirp->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this chirp?')"
                                    class="btn btn-ghost btn-xs text-error">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <p class="mt-1">{{ $chirp->message }}</p>
                <div class="mt-3 flex items-center space-x-6">
                    @include('chirps.like', ['chirp' => $chirp])

                    @if (!$chirp->parent_id)
                        <a href="/chirps/{{ $chirp->id }}" class="btn btn-ghost btn-xs text-base-content/70 hover:text-primary flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 fill-none text-current" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379L12 21l3.12-3.134c1.152-.086 2.294-.213 3.423-.379 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                            <span class="text-xs font-medium">Replies ({{ $chirp->replies()->count() }})</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>