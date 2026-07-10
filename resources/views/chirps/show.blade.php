<x-layout>
    <x-slot:title>
        Chirp Thread
    </x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Back Link -->
        <div>
            <a href="/" class="btn btn-ghost btn-sm gap-2">
                ← Back to Feed
            </a>
        </div>

        <!-- Parent Chirp -->
        <div class="card bg-base-100 shadow border-l-4 border-primary">
            <div class="card-body">
                <div class="flex space-x-3">
                    @if ($chirp->user)
                        <div class="avatar">
                            <div class="size-10 rounded-full">
                                <img src="https://avatars.laravel.cloud/{{ urlencode($chirp->user->email) }}"
                                    alt="{{ $chirp->user->name }}'s avatar" class="rounded-full" />
                            </div>
                        </div>
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
                                <span class="text-sm font-semibold">{{ $chirp->user ? $chirp->user->name : 'Anonymous' }}</span>
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
                        <p class="mt-1 text-lg font-medium text-base-content">{{ $chirp->message }}</p>
                        
                        <div class="mt-4 flex items-center space-x-4 border-t border-base-200 pt-3">
                            @include('chirps.like', ['chirp' => $chirp])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reply Form (Only if authenticated) -->
        @auth
            <div class="card bg-base-100 shadow">
                <div class="card-body py-4">
                    <form method="POST" action="/chirps">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $chirp->id }}">
                        
                        <div class="form-control w-full">
                            <textarea name="message" placeholder="Post your reply..."
                                class="textarea textarea-bordered w-full resize-none @error('message') textarea-error @enderror" rows="2" maxlength="255"
                                required>{{ old('message') }}</textarea>
                        </div>

                        @error('message')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span> 
                            </div>
                        @enderror

                        <div class="mt-2 flex items-center justify-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert bg-base-100 shadow text-sm">
                <span>Please <a href="/login" class="link link-primary font-semibold">sign in</a> to reply to this thread.</span>
            </div>
        @endauth

        <!-- Replies Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold px-1">Replies ({{ $chirp->replies()->count() }})</h3>
            
            @forelse ($chirp->replies()->with(['user', 'likes'])->latest()->get() as $reply)
                <x-chirp :chirp="$reply" />
            @empty
                <div class="text-center py-8 bg-base-100 rounded-box shadow opacity-60 text-sm">
                    No replies yet. Be the first to reply!
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
