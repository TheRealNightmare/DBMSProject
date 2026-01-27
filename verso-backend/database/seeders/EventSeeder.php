<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories matching the colors in EventCalendar.vue
        $categories = [
            'Know your Writer', 
            'Genre-mania', 
            'Movie screening', 
            'Play on.........'
        ];

        $hosts = ['Brian Cox', 'Jerome K. Jerome', 'Dianne Russell', 'Daniel Defoe', 'Jane Austen'];

        // 1. Create an event for TODAY (so you see it immediately)
        Event::create([
            'title' => 'Today\'s Special',
            'category' => 'Genre-mania',
            'host_name' => 'Admin Host',
            'description' => 'A special event happening right now!',
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHours(2),
        ]);

        // 2. Create events for the current month
        for ($i = 0; $i < 15; $i++) {
            // Random day in current month, random time
            $date = Carbon::now()->startOfMonth()->addDays(rand(0, 28))->setTime(rand(9, 20), 0);
            
            $category = $categories[array_rand($categories)];

            Event::create([
                'title' => $category . ' Session',
                'category' => $category,
                'host_name' => $hosts[array_rand($hosts)],
                'description' => 'Join us for an exciting session of literary exploration and fun.',
                'start_time' => $date,
                'end_time' => $date->copy()->addHours(2),
            ]);
        }
        
        // 3. Create a few future events (next month)
        for ($i = 0; $i < 5; $i++) {
            $date = Carbon::now()->addMonth()->startOfMonth()->addDays(rand(0, 15))->setTime(rand(10, 18), 0);
            
            Event::create([
                'title' => 'Future: ' . $categories[array_rand($categories)],
                'category' => $categories[array_rand($categories)],
                'host_name' => 'Future Host',
                'description' => 'Upcoming event next month.',
                'start_time' => $date,
                'end_time' => $date->copy()->addHours(3),
            ]);
        }
    }
}