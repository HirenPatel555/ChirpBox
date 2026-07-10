<?php

use App\Models\Chirp;
use App\Models\User;

test('owner can edit their chirp', function () {
    $owner = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'This is a chirp message from the owner.',
    ]);

    $response = $this->actingAs($owner)->get("/chirps/{$chirp->id}/edit");
    $response->assertStatus(200);
});

test('owner can update their chirp', function () {
    $owner = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'Original message',
    ]);

    $response = $this->actingAs($owner)->put("/chirps/{$chirp->id}", [
        'message' => 'Updated message by owner',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('chirps', [
        'id' => $chirp->id,
        'message' => 'Updated message by owner',
    ]);
});

test('owner can delete their chirp', function () {
    $owner = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'Chirp to be deleted',
    ]);

    $response = $this->actingAs($owner)->delete("/chirps/{$chirp->id}");

    $response->assertRedirect('/');
    $this->assertDatabaseMissing('chirps', [
        'id' => $chirp->id,
    ]);
});

test('other logged in user cannot edit a chirp', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'Owner chirp',
    ]);

    $response = $this->actingAs($otherUser)->get("/chirps/{$chirp->id}/edit");
    $response->assertStatus(403);
});

test('other logged in user cannot update a chirp', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'Original message',
    ]);

    $response = $this->actingAs($otherUser)->put("/chirps/{$chirp->id}", [
        'message' => 'Malicious update',
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseHas('chirps', [
        'id' => $chirp->id,
        'message' => 'Original message',
    ]);
});

test('other logged in user cannot delete a chirp', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'Owner chirp',
    ]);

    $response = $this->actingAs($otherUser)->delete("/chirps/{$chirp->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('chirps', [
        'id' => $chirp->id,
    ]);
});

test('guest is redirected to login when attempting to edit a chirp', function () {
    $owner = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'Owner chirp',
    ]);

    $response = $this->get("/chirps/{$chirp->id}/edit");
    $response->assertRedirect('/login');
});

test('guest is redirected to login when attempting to update a chirp', function () {
    $owner = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'Owner chirp',
    ]);

    $response = $this->put("/chirps/{$chirp->id}", [
        'message' => 'Attempted guest update',
    ]);

    $response->assertRedirect('/login');
});

test('guest is redirected to login when attempting to delete a chirp', function () {
    $owner = User::factory()->create();
    $chirp = $owner->chirps()->create([
        'message' => 'Owner chirp',
    ]);

    $response = $this->delete("/chirps/{$chirp->id}");

    $response->assertRedirect('/login');
});
