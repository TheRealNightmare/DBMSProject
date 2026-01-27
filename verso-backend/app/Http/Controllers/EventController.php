<?php
namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller {
    public function index() {
        // IMPORTANT: Removed the where('start_time', '>', now()) check
        return Event::orderBy('start_time', 'asc')->get();
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'host_name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
        ]);

        $event = Event::create($validated);
        return response()->json($event, 201);
    }
}