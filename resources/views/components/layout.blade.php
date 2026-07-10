<!DOCTYPE html>
<html lang="en" data-theme="lofi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - ChirpBox' : 'ChirpBox'}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.userId = {{ auth()->id() ?? 'null' }};
    </script>
</head>

<body class="min-h-screen flex flex-col bg-bgBase text-textPrimary font-body">
    <!-- Slim Top Navigation Bar -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-bgBase/80 border-b border-borderSubtle">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Left Side: Logo & Links -->
                <div class="flex items-center gap-6">
                    <!-- Logo -->
                    <a href="/" class="font-display font-bold text-2xl bg-clip-text text-transparent gradient-primary tracking-tight">
                        ChirpBox
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex items-center gap-4 text-sm font-medium text-textMuted">
                        <a href="/" class="hover:text-textPrimary transition-colors {{ request()->is('/') ? 'text-textPrimary font-semibold' : '' }}">
                            Feed
                        </a>
                        <a href="{{ route('search') }}" class="hover:text-textPrimary transition-colors {{ request()->is('search') ? 'text-textPrimary font-semibold' : '' }}">
                            Search
                        </a>
                    </div>
                </div>

                <!-- Right Side: Search, Presence & Auth -->
                <div class="flex items-center gap-4">
                    <!-- Desktop Search Input inside navbar -->
                    <div class="hidden sm:block">
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <input 
                                type="text" 
                                name="q" 
                                placeholder="Search..." 
                                value="{{ request('q') }}"
                                class="bg-bgCard border border-borderSubtle text-textPrimary text-xs rounded-full py-1.5 pl-4 pr-10 w-48 focus:outline-none focus:border-accentViolet transition-colors"
                            />
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-textMuted hover:text-textPrimary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Presence Indicator -->
                    <div id="presence-indicator" class="hidden items-center gap-1.5 text-xs bg-emerald-500/10 text-emerald-400 px-2.5 py-1 rounded-full border border-emerald-500/20">
                        <span class="relative flex size-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full size-2 bg-emerald-500"></span>
                        </span>
                        <span id="presence-count" class="font-semibold">0</span> <span>online</span>
                    </div>

                    @auth
                        <!-- User Avatar & Dropdown -->
                        <div class="dropdown dropdown-end">
                            <button tabindex="0" class="btn btn-ghost btn-circle avatar hover:bg-transparent focus:bg-transparent">
                                <div class="relative flex items-center justify-center p-[2px] rounded-full gradient-primary">
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}'s avatar" class="size-8 rounded-full border border-bgBase object-cover" />
                                </div>
                            </button>
                            <ul tabindex="0" class="dropdown-content menu p-2 shadow-xl bg-bgCard border border-borderSubtle rounded-box w-52 mt-3 z-[1] text-textPrimary">
                                <div class="px-4 py-2 border-b border-borderSubtle mb-1">
                                    <p class="text-xs text-textMuted">Signed in as</p>
                                    <p class="text-sm font-semibold truncate text-textPrimary">{{ auth()->user()->name }}</p>
                                </div>
                                <li>
                                    <a href="{{ route('profiles.show', auth()->user()) }}" class="hover:bg-bgCardHover text-sm">
                                        My Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="hover:bg-bgCardHover text-sm">
                                        Edit Settings
                                    </a>
                                </li>
                                <div class="divider my-1 border-borderSubtle"></div>
                                <li>
                                    <form method="POST" action="/logout" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full text-left text-accentCoral hover:bg-bgCardHover text-sm">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="hidden md:flex items-center gap-3">
                            <a href="/login" class="text-sm font-semibold text-textMuted hover:text-textPrimary transition-colors">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-sm text-xs font-semibold gradient-primary text-white border-0 shadow-md hover:opacity-90">
                                Sign Up
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Hamburger Menu Button -->
                    <div class="dropdown dropdown-end md:hidden">
                        <button tabindex="0" class="btn btn-ghost btn-circle text-textMuted hover:text-textPrimary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>
                        <ul tabindex="0" class="dropdown-content menu p-2 shadow-xl bg-bgCard border border-borderSubtle rounded-box w-52 mt-3 z-[1] text-textPrimary">
                            <li>
                                <a href="/" class="hover:bg-bgCardHover text-sm">
                                    Feed
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('search') }}" class="hover:bg-bgCardHover text-sm">
                                    Search
                                </a>
                            </li>
                            @guest
                                <div class="divider my-1 border-borderSubtle"></div>
                                <li>
                                    <a href="/login" class="hover:bg-bgCardHover text-sm">
                                        Sign In
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('register') }}" class="hover:bg-bgCardHover text-sm">
                                        Sign Up
                                    </a>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success Toast -->
    @if (session('success'))
        <div class="toast toast-top toast-center">
            <div class="alert alert-success animate-fade-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <main class="flex-1 container mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    <footer class="footer footer-center p-5 bg-bgCard text-textMuted text-xs border-t border-borderSubtle">
        <div>
            <p>© 2025 ChirpBox - Built with Laravel and ❤️</p>
        </div>
    </footer>
</body>

</html>