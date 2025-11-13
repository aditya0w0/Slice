<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Profile fields
            $table->string('profile_photo')->nullable()->after('email');
            $table->boolean('two_factor_enabled')->default(false)->after('password');

            // Notification preferences
            $table->boolean('notify_order_updates')->default(true)->after('two_factor_enabled');
            $table->boolean('notify_promotions')->default(false)->after('notify_order_updates');
            $table->boolean('notify_reminders')->default(true)->after('notify_promotions');
            $table->boolean('notify_newsletter')->default(false)->after('notify_reminders');
            $table->boolean('notify_security_alerts')->default(true)->after('notify_newsletter');
            $table->enum('notification_frequency', ['realtime', 'daily', 'weekly'])->default('realtime')->after('notify_security_alerts');

            // Privacy settings
            $table->boolean('profile_visibility')->default(false)->after('notification_frequency');
            $table->boolean('activity_tracking')->default(true)->after('profile_visibility');
            $table->boolean('personalized_ads')->default(false)->after('activity_tracking');
            $table->boolean('location_services')->default(true)->after('personalized_ads');

            // Subscription
            $table->enum('subscription_plan', ['basic', 'plus', 'premium'])->default('basic')->after('location_services');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'two_factor_enabled',
                'notify_order_updates',
                'notify_promotions',
                'notify_reminders',
                'notify_newsletter',
                'notify_security_alerts',
                'notification_frequency',
                'profile_visibility',
                'activity_tracking',
                'personalized_ads',
                'location_services',
                'subscription_plan',
                'subscription_expires_at',
            ]);
        });
    }
};
