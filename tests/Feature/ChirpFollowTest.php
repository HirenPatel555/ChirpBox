<?php

use App\Models\Chirp;
use App\Models\User;

test('authenticated user can follow another user', function () {
    $follower = User::factory()->create();
    $followed = User::factory()->create();

    $response = $this->actingAs($follower)->post("/users/{$followed->id}/follow");

    $response->assertRedirect();
    $this->assertTrue($follower->isFollowing($followed));
    $this->assertDatabaseHas('followers', [
        'follower_id' => $follower->id,
        'followed_id' => $followed->id,
    ]);
});

test('authenticated user can unfollow another user', function () {
    $follower = User::factory()->create();
    $followed = User::factory()->create();

    $follower->following()->attach($followed->id);
    $this->assertTrue($follower->isFollowing($followed));

    $response = $this->actingAs($follower)->post("/users/{$followed->id}/follow");

    $response->assertRedirect();
    $this->assertFalse($follower->isFollowing($followed));
    $this->assertDatabaseMissing('followers', [
        'follower_id' => $follower->id,
        'followed_id' => $followed->id,
    ]);
});

test('user cannot follow themselves', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post("/users/{$user->id}/follow");

    $response->assertRedirect();
    $this->assertFalse($user->isFollowing($user));
    $this->assertDatabaseMissing('followers', [
        'follower_id' => $user->id,
        'followed_id' => $user->id,
    ]);
});

test('following feed only shows chirps from followed users', function () {
    $user = User::factory()->create();
    $followedUser = User::factory()->create();
    $unfollowedUser = User::factory()->create();

    $user->following()->attach($followedUser->id);

    $followedChirp = Chirp::create([
        'user_id' => $followedUser->id,
        'message' => 'Chirp from followed user',
    ]);

    $unfollowedChirp = Chirp::create([
        'user_id' => $unfollowedUser->id,
        'message' => 'Chirp from unfollowed user',
    ]);

    $response = $this->actingAs($user)->get('/?feed=following');

    $response->assertStatus(200);
    $response->assertSee('Chirp from followed user');
    $response->assertDontSee('Chirp from unfollowed user');
});

test('guest is redirected to login when trying to follow a user', function () {
    $followed = User::factory()->create();

    $response = $this->post("/users/{$followed->id}/follow");

    $response->assertRedirect('/login');
});
