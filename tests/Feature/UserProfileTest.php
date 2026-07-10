<?php

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('public profile page displays user details and their chirps', function () {
    $user = User::factory()->create([
        'name' => 'Profile User',
        'created_at' => now()->subMonths(3), // Joined 3 months ago
    ]);

    $chirp = Chirp::create([
        'user_id' => $user->id,
        'message' => 'Chirp written by profile user',
    ]);

    $response = $this->get(route('profiles.show', $user));

    $response->assertStatus(200);
    $response->assertSee('Profile User');
    $response->assertSee($user->created_at->format('M Y')); // Join date
    $response->assertSee('1'); // Chirp count
    $response->assertSee('Chirp written by profile user');
});

test('authenticated user can view profile edit form and upload a valid avatar', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.edit'));
    $response->assertStatus(200);

    $avatar = UploadedFile::fake()->create('my_avatar.png', 100, 'image/png');

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'avatar' => $avatar,
    ]);

    $response->assertRedirect(route('profiles.show', $user));

    $user->refresh();
    $this->assertNotNull($user->avatar);
    
    // Assert file was stored in public storage disk
    Storage::disk('public')->assertExists($user->avatar);
});

test('uploading a non-image file results in validation errors', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $document = UploadedFile::fake()->create('document.pdf', 500); // 500kb PDF

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'avatar' => $document,
    ]);

    $response->assertSessionHasErrors(['avatar']);
    
    $user->refresh();
    $this->assertNull($user->avatar);
});

test('guest is redirected to login when trying to access profile edit and update routes', function () {
    $response = $this->get(route('profile.edit'));
    $response->assertRedirect('/login');

    $response = $this->post(route('profile.update'), [
        'avatar' => UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg'),
    ]);
    $response->assertRedirect('/login');
});
