<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Toggle the follow state for a user.
     */
    public function toggle(User $user)
    {
        $currentUser = auth()->user();

        // Prevent self-following
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if ($currentUser->isFollowing($user)) {
            $currentUser->following()->detach($user->id);
            $message = "You have unfollowed {$user->name}.";
        } else {
            $currentUser->following()->attach($user->id);
            $message = "You are now following {$user->name}.";
        }

        return back()->with('success', $message);
    }
}
