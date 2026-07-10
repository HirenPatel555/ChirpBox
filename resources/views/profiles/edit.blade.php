<x-layout>
    <x-slot:title>
        Edit Profile
    </x-slot:title>

    <div class="max-w-md mx-auto space-y-6">
        <div>
            <a href="{{ route('profiles.show', $user) }}" class="btn btn-ghost btn-sm gap-2">
                ← Back to Profile
            </a>
        </div>

        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title text-xl font-bold mb-4 text-base-content">Edit Profile Avatar</h2>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Current Avatar Preview -->
                    <div class="flex flex-col items-center gap-4">
                        <span class="text-sm font-medium text-base-content/70">Current Avatar</span>
                        <div class="avatar">
                            <div class="size-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s current avatar" />
                            </div>
                        </div>
                    </div>

                    <!-- File input -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold text-base-content">Choose new avatar image</span>
                        </label>
                        <input 
                            type="file" 
                            name="avatar" 
                            class="file-input file-input-bordered w-full @error('avatar') file-input-error @enderror" 
                            accept="image/*"
                            required
                        />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Maximum size 2MB (jpeg, png, jpg, gif, webp)</span>
                        </label>
                        @error('avatar')
                            <div class="label">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="card-actions justify-end">
                        <button type="submit" class="btn btn-primary w-full sm:w-auto">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
