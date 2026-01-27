<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    
    protected $primaryKey = 'user_id'; 
    
    protected $fillable = [
        'username', 'email', 'password', 'bio', 'profile_image', 'zip_code',
    ];

    protected $hidden = ['password'];

    public function followers() {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function following() {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    // New: User follows Authors
    public function followingAuthors() {
        return $this->belongsToMany(Author::class, 'author_follows', 'user_id', 'author_id');
    }

    public function shelves()
    {
        return $this->hasMany(Shelf::class, 'user_id');
    }
}