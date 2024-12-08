<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'post_comments';

    // Mass assignable attributes
    protected $fillable = [
        'post_id',
        'user_id',
        'comment_text',
    ];

    /**
     * Relationship with the Post model
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relationship with the User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the PostCommentReply model
     */
    public function replies()
    {
        return $this->hasMany(PostCommentReply::class, 'comment_id');
    }
}
