<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {
    protected $primaryKey = 'group_id';
    protected $fillable = ['name', 'description'];
}