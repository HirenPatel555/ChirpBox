<x-layout>
    <x-slot:title>
        Search Results
    </x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Search Header Info -->
        <div>
            @if ($query)
                <h1 class="text-3xl font-bold text-base-content">Search Results</h1>
                <p class="text-sm text-base-content/60 mt-1">Showing matches for <span class="font-semibold text-primary">"{{ $query }}"</span></p>
            @else
                <h1 class="text-3xl font-bold text-base-content">Search</h1>
                <p class="text-sm text-base-content/60 mt-1">Enter a keyword to search chirps and authors.</p>
            @endif
        </div>

        <!-- Dedicated Search form (mobile-friendly or fallback) -->
        <div class="card bg-base-100 shadow p-4 sm:hidden">
            <form action="{{ route('search') }}" method="GET" class="flex gap-2">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Search chirps and users..." 
                    value="{{ $query }}"
                    class="input input-bordered w-full"
                />
                <button type="submit" class="btn btn-primary">
                    Search
                </button>
            </form>
        </div>

        <!-- Search Results List -->
        <div class="space-y-4">
            @if ($query)
                @if ($chirps && $chirps->count() > 0)
                    <div class="px-1 text-sm font-medium text-base-content/70 mb-2">
                        Found {{ $chirps->total() }} matching {{ Str::plural('chirp', $chirps->total()) }}
                    </div>

                    @foreach ($chirps as $chirp)
                        <x-chirp :chirp="$chirp" />
                    @endforeach

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $chirps->links() }}
                    </div>
                @else
                    <div class="hero py-16 bg-base-100 shadow rounded-box">
                        <div class="hero-content text-center">
                            <div class="max-w-md">
                                <svg class="mx-auto h-12 w-12 opacity-30 text-base-content" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-bold text-base-content">No results found</h3>
                                <p class="mt-2 text-sm text-base-content/60">We couldn't find any chirps or users matching "{{ $query }}". Try checking spelling or using broader keywords.</p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="hero py-16 bg-base-100 shadow rounded-box">
                    <div class="hero-content text-center">
                        <div class="max-w-md">
                            <svg class="mx-auto h-12 w-12 opacity-30 text-base-content" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-bold text-base-content">Ready to Search</h3>
                            <p class="mt-2 text-sm text-base-content/60">Type keywords in the search bar above to look up posts and users instantly.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>
