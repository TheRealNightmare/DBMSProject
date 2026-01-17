<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    protected $table = 'shelves';
    protected $primaryKey = 'shelf_id';

    protected $fillable = [
        'user_id',
        'name',
        'privacy_level', // 'Public', 'Private', 'Friends'
        'is_system_default', // true for automatically created shelves like 'Reading List'
    ];

    /**
     * Get the user who owns this shelf.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the items (books) on this shelf.
     */
    public function items()
    {
        return $this->hasMany(ShelfItem::class, 'shelf_id');
    }
}