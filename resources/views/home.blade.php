<x-layout>
    <x-slot:title>
        Home Feed
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Latest Chirps</h1>

        <!-- Feed Filter -->
        @auth
            <div class="flex space-x-2 mt-6 border-b border-base-300 pb-2">
                <a href="/?feed=all" class="btn btn-sm {{ $currentFeed === 'all' ? 'btn-primary' : 'btn-ghost' }}">
                    All Chirps
                </a>
                <a href="/?feed=following" class="btn btn-sm {{ $currentFeed === 'following' ? 'btn-primary' : 'btn-ghost' }}">
                    Following Only
                </a>
            </div>
        @endauth

        <!-- Compose Box Form Card -->
        @auth
            <div class="bg-bgCard rounded-2xl border border-borderSubtle shadow-lg p-5 mt-8">
                <form method="POST" action="/chirps">
                    @csrf
                    <div class="flex gap-4">
                        <!-- User's avatar on left -->
                        <div class="hidden sm:block">
                            <div class="relative flex items-center justify-center p-[2px] rounded-full gradient-primary">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}'s avatar" class="size-10 rounded-full border border-bgBase object-cover" />
                            </div>
                        </div>

                        <!-- Textarea and controls on right -->
                        <div class="flex-1 space-y-3">
                            <div class="form-control w-full">
                                <textarea 
                                    name="message" 
                                    placeholder="What's on your mind?"
                                    class="w-full bg-transparent border-0 focus:ring-0 text-textPrimary text-base placeholder-textMuted/50 resize-none min-h-[85px] focus:outline-none font-body"
                                    maxlength="255"
                                    required
                                    oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'; document.getElementById('char-counter').innerText = this.value.length + '/255'"
                                >{{ old('message') }}</textarea>
                            </div>

                            @error('message')
                                <div class="text-xs text-accentCoral font-body font-semibold">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="flex items-center justify-between border-t border-borderSubtle/50 pt-3">
                                <!-- Character counter -->
                                <span id="char-counter" class="font-mono text-textMuted text-xs">
                                    {{ old('message') ? strlen(old('message')) : 0 }}/255
                                </span>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-sm border-0 text-white rounded-full font-body font-semibold px-6 shadow-md gradient-primary hover:scale-[1.03] active:scale-[0.98] transition-transform duration-200">
                                    Chirp
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-bgCard rounded-2xl border border-borderSubtle shadow-lg p-6 mt-8 text-center space-y-4">
                <h3 class="font-display font-bold text-xl text-textPrimary">Join the conversation in ChirpBox</h3>
                <p class="font-body text-sm text-textMuted max-w-sm mx-auto">Sign in or register an account now to start sharing your thoughts with the community!</p>
                <div class="flex items-center justify-center gap-3">
                    <a href="/login" class="btn btn-sm btn-ghost text-textPrimary font-body">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-sm font-semibold font-body gradient-primary text-white border-0 px-6 rounded-full shadow-md hover:scale-[1.03] transition-transform duration-200">Sign Up</a>
                </div>
            </div>
        @endauth

        <!-- Feed -->
        <div class="space-y-4 mt-8" id="chirps-feed">
            @forelse ($chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <div class="hero py-12">
                    <div class="hero-content text-center">
                        <div>
                            <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <p class="mt-4 text-base-content/60">No chirps yet. Be the first to chirp!</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>