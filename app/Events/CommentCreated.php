<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Carbon\Carbon;

class CommentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        return new Channel('comments');
    }
    public function broadcastWith()
    {
        $createdAt = Carbon::parse($this->comment->created_at)->timezone('Europe/Moscow')->toDateTimeString();
        return [
            'id' => $this->comment->id,
            'user_name' => $this->comment->user_name,
            'email' => $this->comment->email,
            'home_page' => $this->comment->home_page,
            'text' => $this->comment->text,
            'parent_id' => $this->comment->parent_id,
            'created_at' => $createdAt,
            'replies' => [] // Добавляем пустой массив replies
        ];
    }
}

