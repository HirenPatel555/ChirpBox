<?php

namespace App\Events;

use App\Models\Chirp;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChirpCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Chirp $chirp) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chirps'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->chirp->id,
            'message' => $this->chirp->message,
            'created_at' => $this->chirp->created_at->diffForHumans(),
            'parent_id' => $this->chirp->parent_id,
            'user' => [
                'id' => $this->chirp->user?->id,
                'name' => $this->chirp->user?->name ?? 'Anonymous',
                'avatar_url' => $this->chirp->user?->avatar_url,
            ],
        ];
    }
}
