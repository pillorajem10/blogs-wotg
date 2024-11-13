<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'comments';  // This is optional as Laravel will assume it to be the plural form of the model name

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'comment_blogid', 
        'comment_userid', 
        'comment_body',
    ];

    // Define the primary key (default is 'id', but explicitly specifying here)
    protected $primaryKey = 'id';

    // Laravel expects auto-incrementing IDs, but if your primary key is not an auto-incrementing integer, you can disable it like this:
    // public $incrementing = false; // Uncomment this if you have a non-incrementing primary key

    public $timestamps = true; // This is true by default (uses created_at and updated_at)

    // Relationship with User (a comment belongs to a user)
    public function user()
    {
        return $this->belongsTo(User::class, 'comment_userid');  // 'comment_userid' is the foreign key
    }

    // Relationship with Blog (a comment belongs to a blog)
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'comment_blogid');  // 'comment_blogid' is the foreign key
    }
}
