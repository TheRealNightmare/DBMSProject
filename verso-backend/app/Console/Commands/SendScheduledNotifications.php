<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send {--type=all : Type of notification to send (all, read, explore, connect)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notifications to all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        
        $notificationTypes = [
            'read' => [
                'type' => 'read_reminder',
                'title' => '📚 Time to Read!',
                'message' => 'Don\'t forget to continue your reading journey today. Your books are waiting!',
            ],
            'explore' => [
                'type' => 'explore_books',
                'title' => '🔍 Discover New Books',
                'message' => 'Explore our collection and find your next favorite book. New titles added!',
            ],
            'connect' => [
                'type' => 'connect_people',
                'title' => '👥 Connect with Readers',
                'message' => 'Join our community channels and connect with fellow book lovers!',
            ],
        ];

        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->error('No users found in the system.');
            return 1;
        }

        $this->info("Sending notifications to {$users->count()} users...");
        
        $sentCount = 0;
        $progressBar = $this->output->createProgressBar($users->count());
        
        foreach ($users as $user) {
            if ($type === 'all') {
                // Send a random notification type
                $notification = $notificationTypes[array_rand($notificationTypes)];
            } elseif (isset($notificationTypes[$type])) {
                // Send specific type
                $notification = $notificationTypes[$type];
            } else {
                $this->error("Invalid notification type: {$type}");
                return 1;
            }
            
            Notification::create([
                'user_id' => $user->user_id,
                'type' => $notification['type'],
                'title' => $notification['title'],
                'message' => $notification['message'],
            ]);
            
            $sentCount++;
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        $this->info("✓ Successfully sent {$sentCount} notifications!");
        
        return 0;
    }
}
