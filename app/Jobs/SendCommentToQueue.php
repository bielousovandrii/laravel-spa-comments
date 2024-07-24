<?php
namespace App\Jobs;


use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
class SendCommentToQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public $comment;

public function __construct(Comment $comment)
{
$this->comment = $comment;
}

public function handle()
{
// Логика для отправки комментария в RabbitMQ
// Например, используя библиотеку PhpAmqpLib
$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
config('queue.connections.rabbitmq.hosts')[0]['host'],
config('queue.connections.rabbitmq.hosts')[0]['port'],
config('queue.connections.rabbitmq.hosts')[0]['user'],
config('queue.connections.rabbitmq.hosts')[0]['password']
);

$channel = $connection->channel();
$channel->queue_declare('comments', false, true, false, false);

$msg = new \PhpAmqpLib\Message\AMQPMessage(json_encode($this->comment->toArray()), [
'delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT,
]);

$channel->basic_publish($msg, '', 'comments');

$channel->close();
$connection->close();
}
}
