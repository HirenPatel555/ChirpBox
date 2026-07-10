<x-layout>
    <x-slot:title>
        Register
    </x-slot:title>

    <div class="flex items-center justify-center min-h-[calc(100vh-12rem)] px-4 py-8">
        <!-- bgCard centered panel -->
        <div class="w-full max-w-md bg-bgCard rounded-2xl border border-borderSubtle shadow-xl p-8 space-y-6">
            <!-- Logo above form -->
            <div class="text-center">
                <a href="/" class="font-display font-bold text-3xl bg-clip-text text-transparent gradient-primary tracking-tight">
                    ChirpBox
                </a>
                <h2 class="text-xs text-textMuted mt-1.5 font-body">Create a new account to join us!</h2>
            </div>

            <form method="POST" action="/register" class="space-y-4">
                @csrf

                <!-- Name -->
                <div class="form-control w-full space-y-1.5">
                    <label class="text-xs font-semibold text-textMuted font-body">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="John Doe"
                        value="{{ old('name') }}"
                        class="input w-full bg-bgBase border border-borderSubtle focus:border-accentViolet focus:ring-1 focus:ring-accentViolet rounded-xl text-textPrimary text-sm font-body px-4 py-2.5 @error('name') border-accentCoral @enderror" 
                        required 
                        autofocus
                    />
                    @error('name')
                        <span class="text-xs text-accentCoral font-body font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-control w-full space-y-1.5">
                    <label class="text-xs font-semibold text-textMuted font-body">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        class="input w-full bg-bgBase border border-borderSubtle focus:border-accentViolet focus:ring-1 focus:ring-accentViolet rounded-xl text-textPrimary text-sm font-body px-4 py-2.5 @error('email') border-accentCoral @enderror" 
                        required 
                    />
                    @error('email')
                        <span class="text-xs text-accentCoral font-body font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-control w-full space-y-1.5">
                    <label class="text-xs font-semibold text-textMuted font-body">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="••••••••"
                        class="input w-full bg-bgBase border border-borderSubtle focus:border-accentViolet focus:ring-1 focus:ring-accentViolet rounded-xl text-textPrimary text-sm font-body px-4 py-2.5 @error('password') border-accentCoral @enderror" 
                        required 
                    />
                    @error('password')
                        <span class="text-xs text-accentCoral font-body font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-control w-full space-y-1.5">
                    <label class="text-xs font-semibold text-textMuted font-body">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        placeholder="••••••••"
                        class="input w-full bg-bgBase border border-borderSubtle focus:border-accentViolet focus:ring-1 focus:ring-accentViolet rounded-xl text-textPrimary text-sm font-body px-4 py-2.5" 
                        required 
                    />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn border-0 text-white rounded-full font-body font-semibold w-full shadow-md gradient-primary hover:scale-[1.02] active:scale-[0.98] transition-transform duration-200 mt-6">
                    Create Account
                </button>
            </form>

            <div class="divider before:bg-borderSubtle/50 after:bg-borderSubtle/50 text-textMuted/40 text-xs">OR</div>

            <!-- Switch Link -->
            <div class="text-center text-sm font-body">
                <span class="text-textMuted">Already have an account?</span>
                <a href="/login" class="text-accentViolet hover:underline font-semibold ml-1">Sign In</a>
            </div>
        </div>
    </div>
</x-layout>