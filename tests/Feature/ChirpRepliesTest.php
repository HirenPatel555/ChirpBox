<?php

use App\Models\Chirp;
use App\Models\User;

test('authenticated user can create a reply linked to a parent chirp', function () {
    $user = User::factory()->create();
    $parentChirp = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'message' => 'This is a parent chirp!',
    ]);

    $response = $this->actingAs($user)->post('/chirps', [
        'message' => 'This is a reply to the parent chirp.',
        'parent_id' => $parentChirp->id,
    ]);

    $response->assertRedirect(route('chirps.show', $parentChirp->id));

    $this->assertDatabaseHas('chirps', [
        'message' => 'This is a reply to the parent chirp.',
        'parent_id' => $parentChirp->id,
        'user_id' => $user->id,
    ]);
});

test('feed page only displays top level chirps and not replies', function () {
    $parentChirp = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'message' => 'Parent chirp visible on feed',
    ]);

    $replyChirp = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'parent_id' => $parentChirp->id,
        'message' => 'Reply chirp hidden on feed',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Parent chirp visible on feed');
    $response->assertDontSee('Reply chirp hidden on feed');
});

test('thread detail page displays parent chirp and all its replies', function () {
    $parentChirp = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'message' => 'Main parent chirp',
    ]);

    $reply1 = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'parent_id' => $parentChirp->id,
        'message' => 'First reply content',
    ]);

    $reply2 = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'parent_id' => $parentChirp->id,
        'message' => 'Second reply content',
    ]);

    $response = $this->get(route('chirps.show', $parentChirp->id));

    $response->assertStatus(200);
    $response->assertSee('Main parent chirp');
    $response->assertSee('First reply content');
    $response->assertSee('Second reply content');
});

test('reply validation fails with non-existent parent_id', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/chirps', [
        'message' => 'Invalid reply attempt',
        'parent_id' => 99999, // Non-existent ID
    ]);

    $response->assertSessionHasErrors(['parent_id']);
});
