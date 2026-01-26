<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        // Check if groups exist to avoid duplicates
        if (Group::count() === 0) {
            Group::create([
                'name' => 'General', 
                'description' => 'General discussion for everyone',
                'room_code' => 'GENERAL-CHAT',
                'is_default' => true,
            ]);
            
            Group::create([
                'name' => 'Book Club', 
                'description' => 'Discussing our monthly reads',
                'room_code' => 'BOOK-CLUB',
                'is_default' => true,
            ]);
            
            Group::create([
                'name' => 'Recommendations', 
                'description' => 'Share your favorite books',
                'room_code' => 'BOOK-RECS',
                'is_default' => true,
            ]);
        }
    }
}