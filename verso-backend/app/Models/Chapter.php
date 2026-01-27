<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $primaryKey = 'chapter_id';
    protected $fillable = ['book_id', 'title', 'content', 'order_index'];
}