<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model {
    protected $primaryKey = 'message_id';
    protected $fillable = ['group_id', 'sender_id', 'message_body', 'sent_at'];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
}