<?php

namespace App\Events;

use App\Models\FileSubmission;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploaded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fileSubmission;

    public function __construct(FileSubmission $fileSubmission)
    {
        $this->fileSubmission = $fileSubmission->load('user');
    }

    public function broadcastOn()
    {
        return new Channel('school.' . $this->fileSubmission->school_id);
    }

    public function broadcastAs()
    {
        return 'FileUploaded';
    }
}
