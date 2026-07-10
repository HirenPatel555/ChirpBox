<x-layout>
    <x-slot:title>
        {{ $user->name }}'s Profile
    </x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Profile Header Card Panel -->
        <div class="bg-bgCard rounded-2xl border border-borderSubtle shadow-xl overflow-hidden">
            <!-- Gradient primary banner strip -->
            <div class="h-32 w-full gradient-primary"></div>

            <div class="px-6 pb-6 relative">
                <!-- Avatar overlapping the banner -->
                <div class="absolute -top-16 left-6">
                    <div class="relative flex items-center justify-center p-[3px] rounded-full gradient-primary">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s avatar" class="size-28 rounded-full border-4 border-bgCard object-cover" />
                    </div>
                </div>

                <!-- Spacer to make room for absolute avatar -->
                <div class="h-16"></div>

                <!-- Header Info details -->
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mt-4">
                    <div class="space-y-1">
                        <h2 class="font-display font-bold text-2xl text-textPrimary leading-none">
                            {{ $user->name }}
                        </h2>
                        <div class="font-mono text-textMuted text-xs flex items-center gap-1.5 pt-0.5">
                            <span>@{{ Str::lower(str_replace(' ', '', $user->name)) }}</span>
                            <span>·</span>
                            <span class="text-textMuted/70">{{ $user->email }}</span>
                        </div>
                    </div>

                    <!-- Action Button (Follow/Unfollow or Edit Profile) -->
                    <div>
                        @auth
                            @if (auth()->id() === $user->id)
                                <a href="{{ route('profile.edit') }}" class="btn btn-sm rounded-full border border-borderSubtle hover:bg-bgCardHover text-textPrimary font-body text-xs font-semibold px-5">
                                    Edit Profile
                                </a>
                            @else
                                <form method="POST" action="/users/{{ $user->id }}/follow" class="inline">
                                    @csrf
                                    @if (auth()->user()->isFollowing($user))
                                        <button type="submit" class="btn btn-sm rounded-full border border-accentCoral bg-transparent text-accentCoral hover:bg-accentCoral/10 font-body text-xs font-semibold px-6 transition-colors">
                                            Unfollow
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-sm rounded-full border-0 gradient-primary text-white font-body text-xs font-semibold px-6 shadow-md hover:scale-[1.03] active:scale-[0.98] transition-all">
                                            Follow
                                        </button>
                                    @endif
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Stats and Join Date -->
                <div class="flex flex-wrap items-center gap-6 text-sm font-body pt-5 mt-5 border-t border-borderSubtle/50 text-textMuted">
                    <div>
                        <span class="font-semibold text-textPrimary">{{ $chirpsCount }}</span>
                        <span>Chirps</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-semibold text-textPrimary">{{ $user->followers()->count() }}</span>
                        <span>Followers</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-semibold text-textPrimary">{{ $user->following()->count() }}</span>
                        <span>Following</span>
                    </div>
                    <div class="flex items-center gap-1.5 ml-auto sm:ml-0 font-mono text-xs text-textMuted/80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Joined {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- User's Chirps Feed Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-display font-bold px-1 text-textPrimary">{{ $user->name }}'s Chirps</h3>
            
            @forelse ($chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <div class="bg-bgCard rounded-2xl border border-borderSubtle shadow-md p-12 text-center text-textMuted">
                    <p class="font-body text-sm">No chirps posted by this user yet.</p>
                </div>
            @endforelse

            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $chirps->links() }}
            </div>
        </div>
    </div>
</x-layout>
