<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    
    protected $primaryKey = 'user_id'; // Important: match DB
    
    protected $fillable = [
        'username',
        'email',
        'password',
        'bio',
        'profile_image',
        'zip_code',
    ];

    protected $hidden = ['password'];

    // Relationships
    // public function shelves() {
    //     return $this->hasMany(Shelf::class, 'user_id', 'user_id');
    // }
}
