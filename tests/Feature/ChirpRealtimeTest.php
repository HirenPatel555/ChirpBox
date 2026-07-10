<?php

use App\Events\ChirpCreated;
use App\Events\ChirpLiked;
use App\Models\Chirp;
use App\Models\User;
use Illuminate\Support\Facades\Event;

test('creating a chirp dispatches ChirpCreated event', function () {
    Event::fake([ChirpCreated::class]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/chirps', [
        'message' => 'This is a live broadcast test.',
    ]);

    $response->assertRedirect('/');

    Event::assertDispatched(ChirpCreated::class, function ($event) use ($user) {
        return $event->chirp->message === 'This is a live broadcast test.'
            && $event->chirp->user_id === $user->id;
    });
});

test('toggling like dispatches ChirpLiked event', function () {
    Event::fake([ChirpLiked::class]);

    $user = User::factory()->create();
    $chirp = Chirp::create([
        'user_id' => $user->id,
        'message' => 'Hello test chirp',
    ]);

    $response = $this->actingAs($user)->post("/chirps/{$chirp->id}/like");

    Event::assertDispatched(ChirpLiked::class, function ($event) use ($chirp) {
        return $event->chirpId === $chirp->id 
            && $event->likesCount === 1;
    });
});
