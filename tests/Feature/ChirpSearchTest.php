<?php

use App\Models\Chirp;
use App\Models\User;

test('search returns chirps matching message content', function () {
    $author = User::factory()->create();

    $matchingChirp = Chirp::create([
        'user_id' => $author->id,
        'message' => 'Searching for a needle in a haystack.',
    ]);

    $otherChirp = Chirp::create([
        'user_id' => $author->id,
        'message' => 'Just some other unrelated text.',
    ]);

    $response = $this->get('/search?q=needle');

    $response->assertStatus(200);
    $response->assertSee('Searching for a needle in a haystack.');
    $response->assertDontSee('Just some other unrelated text.');
});

test('search returns chirps matching author name', function () {
    $john = User::factory()->create(['name' => 'John Doe']);
    $jane = User::factory()->create(['name' => 'Jane Smith']);

    $johnChirp = Chirp::create([
        'user_id' => $john->id,
        'message' => 'Message from John.',
    ]);

    $janeChirp = Chirp::create([
        'user_id' => $jane->id,
        'message' => 'Message from Jane.',
    ]);

    $response = $this->get('/search?q=John');

    $response->assertStatus(200);
    $response->assertSee('Message from John.');
    $response->assertDontSee('Message from Jane.');
});

test('empty search query shows empty state ready message', function () {
    $response = $this->get('/search');

    $response->assertStatus(200);
    $response->assertSee('Ready to Search');
    $response->assertSee('Type keywords in the search bar above');
});

test('guest users can perform a search successfully', function () {
    $response = $this->get('/search?q=test');
    $response->assertStatus(200);
});
