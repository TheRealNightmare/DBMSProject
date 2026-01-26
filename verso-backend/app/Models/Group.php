<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {
    protected $primaryKey = 'group_id';
    protected $fillable = ['name', 'description', 'room_code', 'is_default', 'created_by'];

    public function messages() {
        return $this->hasMany(GroupMessage::class, 'group_id', 'group_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}