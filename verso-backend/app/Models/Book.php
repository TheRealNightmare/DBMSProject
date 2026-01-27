<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $primaryKey = 'book_id';
    
    protected $fillable = ['title', 'description', 'cover_image', 'category', 'rating_avg','content_path','publication_date'];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author', 'book_id', 'author_id');
    }

    /**
     * Get the shelf items associated with the book.
     */
    public function shelfItems()
    {
        return $this->hasMany(ShelfItem::class, 'book_id');
    }
}