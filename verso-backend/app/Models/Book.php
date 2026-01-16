<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $primaryKey = 'book_id';
    protected $fillable = ['title', 'description', 'author_name', 'cover_image', 'category', 'rating_avg'];
}