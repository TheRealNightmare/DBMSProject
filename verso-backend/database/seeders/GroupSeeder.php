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
                'description' => 'General discussion for everyone'
            ]);
            
            Group::create([
                'name' => 'Book Club', 
                'description' => 'Discussing our monthly reads'
            ]);
            
            Group::create([
                'name' => 'Recommendations', 
                'description' => 'Share your favorite books'
            ]);
        }
    }
}