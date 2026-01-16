<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Annotation extends Model
{
    protected $primaryKey = 'annotation_id';
    protected $fillable = ['user_id', 'book_id', 'chapter_id', 'highlighted_text', 'note', 'color'];
    
    // Optional: Load user info if needed for group sharing
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}