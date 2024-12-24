<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $file;   

    public function __construct($message, $file = null)
    {
        $this->message = $message;
        $this->file = $file; 
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('xabar'),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message, 
            'file' => $this->file, 
            'time' => now()->toDateTimeString(),
        ];
    }
}

