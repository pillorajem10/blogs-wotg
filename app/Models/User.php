<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_fname',
        'user_lname',
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
        'approval_token',  // Add approval_token here
    ];

    protected $hidden = [
        'password',
    ];

    public function seekers()
    {
        return $this->hasMany(Seeker::class, 'seeker_missionary', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'comment_userid');
    }
}
