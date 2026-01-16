<?php
namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller {
    public function index() {
        // IMPORTANT: Removed the where('start_time', '>', now()) check
        return Event::orderBy('start_time', 'asc')->get();
    }
}