<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRating extends Model
{
    protected $primaryKey = 'rating_id';
    protected $fillable = ['book_id', 'user_id', 'rating'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
