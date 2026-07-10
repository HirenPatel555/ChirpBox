<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChirpRequest;
use App\Http\Requests\UpdateChirpRequest;
use App\Models\Chirp;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChirpController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $feed = $request->query('feed', 'all');
        $query = Chirp::with('user')->whereNull('parent_id');

        if ($feed === 'following' && auth()->check()) {
            $followingIds = auth()->user()->following()->pluck('followed_id');
            $query->whereIn('user_id', $followingIds);
        }

        $chirps = $query->latest()->get();
        return view('home', [
            'chirps' => $chirps,
            'currentFeed' => $feed,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChirpRequest $request)
    {
        $chirp = auth()->user()->chirps()->create($request->validated());

        event(new \App\Events\ChirpCreated($chirp));

        if ($chirp->parent_id) {
            return redirect()->route('chirps.show', $chirp->parent_id)->with('success', 'Your reply has been posted!');
        }

        return redirect('/')->with('success', 'Your chirp has been posted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        return view('chirps.show', compact('chirp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChirpRequest $request, Chirp $chirp)
    {
        $chirp->update($request->validated());

        return redirect('/')->with('success', 'Your chirp has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();
        return redirect('/')->with('success', 'Your chirp has been deleted!');
    }
}
