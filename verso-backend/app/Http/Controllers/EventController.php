<?php
namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller {
    public function index() {
        return Event::where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->get();
    }
}