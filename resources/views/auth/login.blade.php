<x-layout>
    <x-slot:title>
        Sign In
    </x-slot:title>

    <div class="flex items-center justify-center min-h-[calc(100vh-12rem)] px-4 py-8">
        <!-- bgCard centered panel -->
        <div class="w-full max-w-md bg-bgCard rounded-2xl border border-borderSubtle shadow-xl p-8 space-y-6">
            <!-- Logo above form -->
            <div class="text-center">
                <a href="/" class="font-display font-bold text-3xl bg-clip-text text-transparent gradient-primary tracking-tight">
                    ChirpBox
                </a>
                <h2 class="text-xs text-textMuted mt-1.5 font-body">Welcome back! Please sign in.</h2>
            </div>

            <form method="POST" action="/login" class="space-y-4">
                @csrf

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
                        autofocus
                    />
                    @error('email')
                        <span class="text-xs text-accentCoral font-body font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-control w-full space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold text-textMuted font-body">Password</label>
                    </div>
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

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="label cursor-pointer justify-start gap-2 p-0">
                        <input type="checkbox" name="remember" class="checkbox checkbox-sm rounded border-borderSubtle bg-bgBase text-accentViolet focus:ring-accentViolet focus:ring-1">
                        <span class="text-xs text-textMuted font-body select-none">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn border-0 text-white rounded-full font-body font-semibold w-full shadow-md gradient-primary hover:scale-[1.02] active:scale-[0.98] transition-transform duration-200 mt-6">
                    Sign In
                </button>
            </form>

            <div class="divider before:bg-borderSubtle/50 after:bg-borderSubtle/50 text-textMuted/40 text-xs">OR</div>

            <!-- Switch Link -->
            <div class="text-center text-sm font-body">
                <span class="text-textMuted">Don't have an account?</span>
                <a href="/register" class="text-accentViolet hover:underline font-semibold ml-1">Register</a>
            </div>
        </div>
    </div>
</x-layout>