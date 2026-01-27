<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShelfItem extends Model
{
    use HasFactory;

    protected $table = 'shelf_items';
    protected $primaryKey = 'item_id';
    public $timestamps = false; // We only have 'added_at', not updated_at

    protected $fillable = [
        'shelf_id',
        'book_id',
        'status', // 'Read', 'Reading', 'Want to Read'
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    /**
     * Get the shelf this item belongs to.
     */
    public function shelf()
    {
        return $this->belongsTo(Shelf::class, 'shelf_id');
    }

    /**
     * Get the book associated with this item.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}