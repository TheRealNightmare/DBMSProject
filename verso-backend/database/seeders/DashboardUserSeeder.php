<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Use updateOrCreate to prevent unique constraint errors
        $user = User::updateOrCreate(
            ['email' => 'reader@example.com'], // Search by email
            [
                'username' => 'DemoReader',
                'password' => Hash::make('password'),
                'bio' => 'Avid reader and tech enthusiast.',
            ]
        );

        // 2. Clear existing data for a clean seed
        // This prevents stacking duplicate shelves/sessions on every run
        DB::table('shelves')->where('user_id', $user->user_id)->delete();

        // 3. Create the "History" shelf
        $shelfId = DB::table('shelves')->insertGetId([
            'user_id' => $user->user_id,
            'name' => 'History', // Column name is 'name' in your migration
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $books = Book::limit(5)->get();

        foreach ($books as $book) {
            $itemId = DB::table('shelf_items')->insertGetId([
                'shelf_id' => $shelfId,
                'book_id' => $book->book_id,
                'status' => 'Read',
                'added_at' => now(),
            ]);

            // 4. Generate dynamic monthly data for the HoursChart
            for ($i = 0; $i < 5; $i++) {
                $monthDate = Carbon::now()->subMonths($i);
                $startTime = $monthDate->copy()->startOfMonth()->addDays(rand(1, 25));
                
                DB::table('reading_sessions')->insert([
                    'item_id' => $itemId,
                    'start_time' => $startTime,
                    'end_time' => $startTime->copy()->addHours(rand(2, 10)),
                    'pages_read_count' => rand(20, 50),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}