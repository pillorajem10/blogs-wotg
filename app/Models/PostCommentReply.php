<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCommentReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'reply_text',
    ];

    /**
     * A reply belongs to a parent comment.
     */
    public function comment()
    {
        return $this->belongsTo(PostComment::class, 'comment_id');
    }

    /**
     * A reply belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
