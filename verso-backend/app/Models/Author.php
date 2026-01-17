<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $primaryKey = 'author_id';
    protected $fillable = ['name', 'bio', 'image'];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_author', 'author_id', 'book_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'author_follows', 'author_id', 'user_id');
    }
}