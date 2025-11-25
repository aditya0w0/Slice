<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;

class TestRandomNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:random-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create random notifications for all users for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->error("No users found in the database!");
            return 1;
        }

        $types = ['info', 'warning', 'success', 'error'];
        $titles = [
            'Welcome to Slice!',
            'Your order has been updated',
            'Payment received',
            'Account verification required',
            'New message from support',
            'Device added successfully',
            'Subscription renewed',
            'Security alert',
        ];
        $messages = [
            'Thank you for joining our platform. Explore all the features available to you.',
            'Your recent order status has changed. Please check your order details.',
            'We have received your payment. Your transaction was successful.',
            'Please verify your account to access all features.',
            'A support team member has sent you a message. Check your inbox.',
            'A new device has been added to your account.',
            'Your subscription has been automatically renewed.',
            'We detected unusual activity on your account. Please review your security settings.',
        ];
        $icons = ['fas fa-info', 'fas fa-exclamation-triangle', 'fas fa-check-circle', 'fas fa-times-circle', 'fas fa-bell'];

        $createdCount = 0;
        foreach ($users as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $types[array_rand($types)],
                'title' => $titles[array_rand($titles)],
                'message' => $messages[array_rand($messages)],
                'icon' => $icons[array_rand($icons)],
                'action_url' => null, // or some random URL
                'is_read' => false,
            ]);
            $createdCount++;
        }

        $this->info("✓ Created {$createdCount} random notifications for all users!");
        return 0;
    }
}
