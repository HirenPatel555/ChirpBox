<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search chirps by message or username.
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        $chirps = null;

        if ($query) {
            $chirps = Chirp::with(['user', 'likes', 'replies'])
                ->where(function ($q) use ($query) {
                    $q->where('message', 'like', '%' . $query . '%')
                      ->orWhereHas('user', function ($uq) use ($query) {
                          $uq->where('name', 'like', '%' . $query . '%');
                      });
                })
                ->latest()
                ->paginate(15)
                ->withQueryString();
        }

        return view('search.index', [
            'chirps' => $chirps,
            'query' => $query,
        ]);
    }
}
