<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Skipping notification seeding.');
            return;
        }

        $notifications = [
            [
                'title' => 'Welcome to Slice!',
                'message' => 'Thank you for joining Slice. Start exploring our device rental options and enjoy seamless technology access.',
                'type' => 'success',
                'icon' => 'check-circle',
                'action_url' => '/devices',
                'is_read' => false,
            ],
            [
                'title' => 'Complete Your Profile',
                'message' => 'To unlock all features, please complete your profile and verify your identity through our KYC process.',
                'type' => 'info',
                'icon' => 'info',
                'action_url' => '/settings',
                'is_read' => false,
            ],
            [
                'title' => 'New Device Available',
                'message' => 'Check out our latest MacBook Pro M3 - now available for rent at competitive rates.',
                'type' => 'info',
                'icon' => 'info',
                'action_url' => '/devices/macbook-pro',
                'is_read' => true,
            ],
            [
                'title' => 'Security Update',
                'message' => 'We\'ve enhanced our security measures to better protect your data. Your account is now even more secure.',
                'type' => 'warning',
                'icon' => 'alert-triangle',
                'action_url' => null,
                'is_read' => true,
            ],
            [
                'title' => 'Order Confirmed',
                'message' => 'Your device rental order has been confirmed. You will receive updates on delivery status.',
                'type' => 'success',
                'icon' => 'check-circle',
                'action_url' => '/orders',
                'is_read' => false,
            ],
            [
                'title' => 'Payment Reminder',
                'message' => 'Your payment for the current rental period is due in 3 days. Please ensure timely payment to avoid service interruption.',
                'type' => 'warning',
                'icon' => 'alert-triangle',
                'action_url' => '/balance',
                'is_read' => false,
            ],
            [
                'title' => 'Device Maintenance',
                'message' => 'Scheduled maintenance will be performed on your rented device. It will be unavailable for 2 hours.',
                'type' => 'info',
                'icon' => 'info',
                'action_url' => null,
                'is_read' => true,
            ],
            [
                'title' => 'Referral Program',
                'message' => 'Earn rewards by referring friends! Get 10% off your next rental for each successful referral.',
                'type' => 'success',
                'icon' => 'check-circle',
                'action_url' => '/referrals',
                'is_read' => false,
            ],
        ];

        // Create notifications for each user
        foreach ($users as $user) {
            // Create 2-5 random notifications per user
            $numNotifications = rand(2, 5);
            $selectedNotifications = collect($notifications)->random($numNotifications);

            foreach ($selectedNotifications as $notificationData) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $notificationData['type'],
                    'title' => $notificationData['title'],
                    'message' => $notificationData['message'],
                    'icon' => $notificationData['icon'],
                    'action_url' => $notificationData['action_url'],
                    'is_read' => $notificationData['is_read'],
                    'created_at' => now()->subDays(rand(0, 30)), // Random date within last 30 days
                ]);
            }
        }

        // Create some admin-sent notifications
        $adminNotifications = [
            [
                'title' => 'System Maintenance Notice',
                'message' => 'We will be performing system maintenance on Sunday from 2-4 AM UTC. Services may be temporarily unavailable.',
                'type' => 'warning',
                'icon' => 'alert-triangle',
            ],
            [
                'title' => 'New Feature: Device Tracking',
                'message' => 'We\'ve added real-time device tracking! You can now see the location and status of your rented devices.',
                'type' => 'success',
                'icon' => 'check-circle',
            ],
            [
                'title' => 'Holiday Schedule Update',
                'message' => 'Due to the upcoming holidays, our customer support hours will be adjusted. Please check our support page for details.',
                'type' => 'info',
                'icon' => 'info',
            ],
        ];

        // Send admin notifications to all users
        foreach ($users as $user) {
            foreach ($adminNotifications as $adminNotif) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $adminNotif['type'],
                    'title' => $adminNotif['title'],
                    'message' => $adminNotif['message'],
                    'icon' => $adminNotif['icon'],
                    'action_url' => null,
                    'is_read' => rand(0, 1), // Random read status
                    'created_at' => now()->subDays(rand(1, 14)), // Within last 2 weeks
                ]);
            }
        }

        $this->command->info('Notification seeding completed successfully!');
        $this->command->info('Created notifications for ' . $users->count() . ' users.');
    }
}
