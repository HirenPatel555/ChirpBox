<?php

use App\Models\Chirp;
use App\Models\User;
use App\Models\Like;
use Illuminate\Database\UniqueConstraintViolationException;

test('authenticated user can like a chirp', function () {
    $user = User::factory()->create();
    $chirp = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'message' => 'Liking this chirp!',
    ]);

    $response = $this->actingAs($user)->postJson("/chirps/{$chirp->id}/like");

    $response->assertStatus(200);
    $response->assertJson([
        'liked' => true,
        'likes_count' => 1,
    ]);

    $this->assertDatabaseHas('likes', [
        'user_id' => $user->id,
        'chirp_id' => $chirp->id,
    ]);
});

test('authenticated user can unlike a chirp', function () {
    $user = User::factory()->create();
    $chirp = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'message' => 'Liking this chirp!',
    ]);

    // Create initial like
    $chirp->likes()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->postJson("/chirps/{$chirp->id}/like");

    $response->assertStatus(200);
    $response->assertJson([
        'liked' => false,
        'likes_count' => 0,
    ]);

    $this->assertDatabaseMissing('likes', [
        'user_id' => $user->id,
        'chirp_id' => $chirp->id,
    ]);
});

test('duplicate likes cannot be created', function () {
    $user = User::factory()->create();
    $chirp = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'message' => 'Double liking this chirp!',
    ]);

    // First like
    $chirp->likes()->create([
        'user_id' => $user->id,
    ]);

    // Expect UniqueConstraintViolationException if directly inserting duplicate
    $this->expectException(UniqueConstraintViolationException::class);
    
    $chirp->likes()->create([
        'user_id' => $user->id,
    ]);
});

test('guest is redirected to login when attempting to like a chirp', function () {
    $chirp = Chirp::create([
        'user_id' => User::factory()->create()->id,
        'message' => 'Liking this chirp!',
    ]);

    $response = $this->post("/chirps/{$chirp->id}/like");

    $response->assertRedirect('/login');
});
