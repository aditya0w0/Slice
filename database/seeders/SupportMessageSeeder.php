<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Notification;

class SupportMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $notifications = Notification::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Skipping support message seeding.');
            return;
        }

        // Sample conversations
        $conversations = [
            [
                ['sender' => 'user', 'message' => 'Hi, I received a notification about my order but I\'m not sure what it means.'],
                ['sender' => 'admin', 'message' => 'Hello! I\'d be happy to help clarify that notification for you. Could you tell me which specific notification you\'re referring to?'],
                ['sender' => 'user', 'message' => 'It was about order confirmation. The message said my order was confirmed but I haven\'t received any details.'],
                ['sender' => 'admin', 'message' => 'I see. Let me check your order status. Your order has been confirmed and is being processed. You should receive a shipping notification within 24 hours.'],
            ],
            [
                ['sender' => 'user', 'message' => 'I\'m having trouble with the device I rented. It\'s not connecting properly.'],
                ['sender' => 'admin', 'message' => 'I\'m sorry to hear you\'re having connectivity issues. Let me help troubleshoot this. What device are you using and what error messages are you seeing?'],
                ['sender' => 'user', 'message' => 'It\'s a MacBook Pro. It keeps saying "Unable to connect to WiFi" even though the network is working.'],
                ['sender' => 'admin', 'message' => 'That sounds like a common WiFi issue. Try forgetting the network and reconnecting. If that doesn\'t work, we can arrange a replacement device.'],
            ],
            [
                ['sender' => 'user', 'message' => 'How do I return the device when I\'m done renting?'],
                ['sender' => 'admin', 'message' => 'Great question! You can return the device through our app or website. Just go to your rentals section and select "Return Device". We\'ll provide prepaid shipping labels.'],
            ],
        ];

        foreach ($users as $user) {
            // Create 1-2 conversations per user
            $numConversations = rand(1, 2);

            for ($i = 0; $i < $numConversations; $i++) {
                $conversation = $conversations[array_rand($conversations)];
                $notificationId = $notifications->random()?->id;

                foreach ($conversation as $index => $msg) {
                    SupportMessage::create([
                        'user_id' => $user->id,
                        'notification_id' => $notificationId,
                        'sender_type' => $msg['sender'],
                        'message' => $msg['message'],
                        'is_read' => $msg['sender'] === 'user' ? true : rand(0, 1), // Users have read their own messages
                        'created_at' => now()->subHours(rand(1, 168)), // Random time within last week
                    ]);
                }
            }
        }

        $this->command->info('Support message seeding completed successfully!');
        $this->command->info('Created support conversations for ' . $users->count() . ' users.');
    }
}
