<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model {
    protected $primaryKey = 'message_id';
    public $timestamps = false; // Disable standard timestamps since migration uses 'sent_at'
    
    protected $fillable = ['group_id', 'sender_id', 'message_body', 'is_blurred', 'sent_at'];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_blurred' => 'boolean',
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
    
    public function group() {
        return $this->belongsTo(Group::class, 'group_id', 'group_id');
    }
}