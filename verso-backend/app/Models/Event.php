<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    protected $primaryKey = 'event_id';
    protected $fillable = [
        'title', 
        'category', // Added
        'host_name', 
        'description', 
        'start_time', 
        'end_time'
    ];
}