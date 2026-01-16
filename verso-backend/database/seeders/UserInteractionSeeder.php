<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserInteractionSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------
        // 1. Create Test User (or Update if exists)
        // ---------------------------------------------------------
        $email = 'test@example.com';
        
        $userId = DB::table('users')->where('email', $email)->value('user_id');

        if (!$userId) {
            $userId = DB::table('users')->insertGetId([
                'username' => 'testuser',
                'email' => $email,
                'password' => Hash::make('12345678'), // Fixed Password
                'bio' => 'I love reading sci-fi and history.',
                'profile_image' => 'https://i.pravatar.cc/150?u=testuser',
                'zip_code' => '12345',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("User created: {$email} (id: $userId)");
        } else {
            $this->command->info("User already exists: {$email} (id: $userId)");
        }

        // ---------------------------------------------------------
        // 2. Create "Main Shelf" for this user
        // ---------------------------------------------------------
        // Check if shelf exists to avoid duplicates
        $shelfId = DB::table('shelves')->where('user_id', $userId)->value('shelf_id');

        if (!$shelfId) {
            $shelfId = DB::table('shelves')->insertGetId([
                'user_id' => $userId,
                'name' => 'My Library',
                'privacy_level' => 'Public',
                'is_system_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---------------------------------------------------------
        // 3. Clear old items for this shelf to ensure clean slate
        // ---------------------------------------------------------
        // Be careful: this deletes previous test data for this specific shelf
        DB::table('shelf_items')->where('shelf_id', $shelfId)->delete();


        // ---------------------------------------------------------
        // 4. Seed Books & Reading Sessions
        // ---------------------------------------------------------
        $books = DB::table('books')->inRandomOrder()->limit(10)->get();

        if ($books->isEmpty()) {
            $this->command->error("No books found! Run 'php artisan db:seed --class=BookSeeder' first.");
            return;
        }

        foreach ($books as $index => $book) {
            // Determine status
            $status = ($index < 5) ? 'Read' : 'Reading';

            // Insert Shelf Item
            $itemId = DB::table('shelf_items')->insertGetId([
                'shelf_id' => $shelfId,
                'book_id' => $book->book_id,
                'status' => $status,
                'added_at' => now()->subDays(rand(5, 30)),
            ]);

            // Create HEAVY reading sessions to ensure data shows up
            if ($status === 'Read' || $status === 'Reading') {
                $sessionsCount = rand(5, 10); 
                
                for ($i = 0; $i < $sessionsCount; $i++) {
                    // Random start time in last 30 days
                    $start = Carbon::now()->subDays(rand(0, 30))->subHours(rand(1, 10));
                    
                    // DURATION: Between 60 and 180 minutes (1 to 3 hours per session)
                    $minutes = rand(60, 180); 
                    $end = (clone $start)->addMinutes($minutes);

                    DB::table('reading_sessions')->insert([
                        'item_id' => $itemId,
                        'start_time' => $start,
                        'end_time' => $end, // <--- CRITICAL: Must not be null
                        'pages_read_count' => rand(20, 50),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        $this->command->info("Seeded reading sessions for User ID: $userId");
    }
}