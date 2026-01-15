<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(Request $request) {
        $user = $request->user();

        // Count books in 'History' shelf (assuming History means read)
        $completed = DB::table('shelf_items')
            ->join('shelves', 'shelves.shelf_id', '=', 'shelf_items.shelf_id')
            ->where('shelves.user_id', $user->user_id)
            ->where('status', 'Read')
            ->count();

        // Calculate hours from reading sessions
        $totalMinutes = DB::table('reading_sessions')
            ->join('shelf_items', 'shelf_items.item_id', '=', 'reading_sessions.item_id')
            ->join('shelves', 'shelves.shelf_id', '=', 'shelf_items.shelf_id')
            ->where('shelves.user_id', $user->user_id)
            ->sum(DB::raw('TIMESTAMPDIFF(MINUTE, start_time, end_time)'));

        return response()->json([
            'total_books' => 5000, // Static or count all DB books
            'completed' => $completed,
            'quiz_score' => 50, // Mock, needs Quiz table
            'hours_spent' => round($totalMinutes / 60, 1)
        ]);
    }
}
