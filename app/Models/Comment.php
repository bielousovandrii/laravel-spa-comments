<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'email',
        'home_page',
        'text',
        'parent_id',
        'photo',
        'user_id'
    ];

    /**
     * Get the replies for the comment.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Get the parent comment.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::saved(function ($comment) {
//            $client = ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST')])->build();
//
//            $params = [
//                'index' => 'comments',
//                'id' => $comment->id,
//                'body' => $comment->toArray(),
//            ];
//
//            $client->index($params);
//        });
//
//        static::deleted(function ($comment) {
//            $client = ClientBuilder::create()->setHosts([env('ELASTICSEARCH_HOST')])->build();
//
//            $params = [
//                'index' => 'comments',
//                'id' => $comment->id,
//            ];
//
//            $client->delete($params);
//        });
//    }
}
