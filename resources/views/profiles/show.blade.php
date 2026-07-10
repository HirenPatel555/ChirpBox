<x-layout>
    <x-slot:title>
        {{ $user->name }}'s Profile
    </x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Profile Header Card -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- Avatar -->
                    <div class="avatar">
                        <div class="size-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s avatar" />
                        </div>
                    </div>

                    <!-- User Info Details -->
                    <div class="flex-1 text-center sm:text-left space-y-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h2 class="card-title text-2xl font-bold text-base-content">{{ $user->name }}</h2>
                                <p class="text-sm text-base-content/60">{{ $user->email }}</p>
                            </div>
                            
                            <!-- Action button (Follow/Unfollow or Edit Profile) -->
                            <div>
                                @auth
                                    @if (auth()->id() === $user->id)
                                        <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-sm">
                                            Edit Profile
                                        </a>
                                    @else
                                        <form method="POST" action="/users/{{ $user->id }}/follow" class="inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ auth()->user()->isFollowing($user) ? 'btn-error btn-outline' : 'btn-primary' }}">
                                                {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="flex flex-wrap gap-4 text-sm justify-center sm:justify-start pt-2 border-t border-base-200">
                            <div>
                                <span class="font-semibold text-base-content">{{ $chirpsCount }}</span>
                                <span class="text-base-content/60">Chirps</span>
                            </div>
                            <div>
                                <span class="font-semibold text-base-content">{{ $user->followers()->count() }}</span>
                                <span class="text-base-content/60">Followers</span>
                            </div>
                            <div>
                                <span class="font-semibold text-base-content">{{ $user->following()->count() }}</span>
                                <span class="text-base-content/60">Following</span>
                            </div>
                            <div>
                                <span class="text-base-content/60">Joined</span>
                                <span class="font-semibold text-base-content">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User's Chirps Feed Section -->
        <div class="space-y-4">
            <h3 class="text-xl font-bold px-1 text-base-content">{{ $user->name }}'s Chirps</h3>
            
            @forelse ($chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <div class="hero py-12 bg-base-100 shadow rounded-box">
                    <div class="hero-content text-center">
                        <div class="opacity-60">
                            <p class="text-base-content">No chirps posted by this user yet.</p>
                        </div>
                    </div>
                </div>
            @endforelse

            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $chirps->links() }}
            </div>
        </div>
    </div>
</x-layout>
