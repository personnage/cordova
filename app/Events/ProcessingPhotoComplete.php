<?php

namespace App\Events;

use App\Models\Photo;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProcessingPhotoComplete implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var Photo
     */
    public $photo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Photo $photo)
    {
        $this->photo = $photo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('processing-photo-channel.'.$this->photo->owner->id);
    }
}
