<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'post_likes';

    // The attributes that are mass assignable
    protected $fillable = ['user_id', 'post_id'];

    // The timestamps are automatically handled by Eloquent
    public $timestamps = true;

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with Post
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
