<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookComment extends Model
{
    protected $primaryKey = 'comment_id';
    protected $fillable = ['book_id', 'user_id', 'comment'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
