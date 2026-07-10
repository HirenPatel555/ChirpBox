<?php

use App\Events\TestBroadcastEvent;
use Illuminate\Support\Facades\Event;

test('test broadcast event can be dispatched', function () {
    Event::fake();

    event(new TestBroadcastEvent('Hello Reverb'));

    Event::assertDispatched(TestBroadcastEvent::class, function ($event) {
        return $event->message === 'Hello Reverb';
    });
});

test('reverb configuration is loaded', function () {
    $config = config('broadcasting.connections.reverb');
    
    $this->assertNotNull($config);
    $this->assertEquals('reverb', $config['driver']);
});
