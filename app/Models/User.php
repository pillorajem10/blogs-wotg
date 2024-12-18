<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_fname',
        'user_lname',
        'user_nickname',
        'user_role',
        'user_gender',
        'email',
        'password',
        'user_mobile_number',
        'user_church_name',
        'user_birthday',
        'user_country',
        'user_city',
        'user_dgroup_leader',
        'user_ministry',
        'user_already_a_dgroup_leader',
        'user_already_a_dgroup_member',
        'approval_token',
        'user_profile_picture',
        'user_meeting_day',       
        'user_meeting_time',      
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relationship with Seekers
     */
    public function seekers()
    {
        return $this->hasMany(Seeker::class, 'seeker_missionary', 'id');
    }

    /**
     * Relationship with Comments
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'user_id');
    }

    /**
     * Relationship with Replies
     */
    public function replies()
    {
        return $this->hasMany(PostCommentReply::class, 'user_id');
    }

    /**
     * Function to check if a user liked a specific post
     */
    public function hasLiked($postId)
    {
        return PostLike::where('user_id', $this->id)
                        ->where('post_id', $postId)
                        ->exists();
    }
}
