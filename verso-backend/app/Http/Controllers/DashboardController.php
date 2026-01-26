<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(Request $request) {
        $user = $request->user();

        // 1. Basic Stats (Already implemented)
        $completed = DB::table('shelf_items')
            ->join('shelves', 'shelves.shelf_id', '=', 'shelf_items.shelf_id')
            ->where('shelves.user_id', $user->user_id)
            ->where('status', 'Read')
            ->count();

        $totalMinutes = DB::table('reading_sessions')
            ->join('shelf_items', 'shelf_items.item_id', '=', 'reading_sessions.item_id')
            ->join('shelves', 'shelves.shelf_id', '=', 'shelf_items.shelf_id')
            ->where('shelves.user_id', $user->user_id)
            ->sum(DB::raw('TIMESTAMPDIFF(MINUTE, start_time, end_time)'));

        // 2. [NEW] Monthly Hours Spent (for HoursChart)
        $monthlyHours = DB::table('reading_sessions')
            ->join('shelf_items', 'shelf_items.item_id', '=', 'reading_sessions.item_id')
            ->join('shelves', 'shelves.shelf_id', '=', 'shelf_items.shelf_id')
            ->where('shelves.user_id', $user->user_id)
            ->select(
                DB::raw("DATE_FORMAT(start_time, '%b') as month"),
                DB::raw("ROUND(SUM(TIMESTAMPDIFF(MINUTE, start_time, end_time)) / 60, 1) as hours")
            )
            ->groupBy('month')
            ->orderBy('start_time')
            ->get();

        // 3. [NEW] Leaderboard Data
        $leaderboard = DB::table('users')
            ->select('username as name', 'profile_image as avatar')
            // Placeholder logic for points and courses; replace with actual logic if available
            ->addSelect(DB::raw('FLOOR(RAND() * 100) as course')) 
            ->addSelect(DB::raw('FLOOR(RAND() * 300) as hour'))
            ->addSelect(DB::raw('FLOOR(RAND() * 15000) as point'))
            ->orderBy('point', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'total_books' => 5000,
            'completed' => $completed,
            'quiz_score' => 50,
            'hours_spent' => round($totalMinutes / 60, 1),
            'monthly_stats' => $monthlyHours, // New data for chart
            'leaderboard' => $leaderboard    // New data for table
        ]);
    }
}