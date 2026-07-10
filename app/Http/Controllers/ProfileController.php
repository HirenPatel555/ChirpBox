<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the user's public profile page.
     */
    public function show(User $user)
    {
        $chirps = $user->chirps()
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        $chirpsCount = $user->chirps()->count();

        return view('profiles.show', [
            'user' => $user,
            'chirps' => $chirps,
            'chirpsCount' => $chirpsCount,
        ]);
    }

    /**
     * Show the edit profile page.
     */
    public function edit()
    {
        return view('profiles.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the user's profile/avatar.
     */
    public function update(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,webp'],
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar in 'public/avatars' directory
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
        }

        return redirect()->route('profiles.show', $user)->with('success', 'Profile avatar updated successfully!');
    }
}
