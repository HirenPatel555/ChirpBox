<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle the like status for the given chirp.
     */
    public function toggle(Chirp $chirp)
    {
        $user = auth()->user();

        // Check if user already liked the chirp
        $like = $chirp->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $chirp->likes()->create([
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        $likesCount = $chirp->likes()->count();
        event(new \App\Events\ChirpLiked($chirp->id, $likesCount));

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $likesCount,
            ]);
        }

        return back();
    }
}
