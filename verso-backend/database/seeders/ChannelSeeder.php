<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultChannels = [
            [
                'name' => 'General',
                'description' => 'General discussion about books and reading',
                'room_code' => 'GENERAL-CHAT',
                'is_default' => true,
                'created_by' => null,
            ],
            [
                'name' => 'Book Recommendations',
                'description' => 'Share and discover new book recommendations',
                'room_code' => 'BOOK-RECS',
                'is_default' => true,
                'created_by' => null,
            ],
            [
                'name' => 'Reading Club',
                'description' => 'Discuss books you are currently reading',
                'room_code' => 'READING-CLUB',
                'is_default' => true,
                'created_by' => null,
            ],
        ];

        foreach ($defaultChannels as $channel) {
            Group::create($channel);
        }

        $this->command->info('Default channels created successfully!');
    }
}
