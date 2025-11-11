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
            // Credit Score System (300-850 scale - US standard)
            $table->integer('credit_score')->default(500)->after('kyc_verified_at');
            $table->enum('credit_tier', ['poor', 'fair', 'good', 'very_good', 'excellent'])
                ->default('fair')->after('credit_score');
            $table->timestamp('credit_score_updated_at')->nullable()->after('credit_tier');
            
            // Risk flags (admin only visibility)
            $table->boolean('is_blacklisted')->default(false)->after('credit_score_updated_at');
            $table->text('blacklist_reason')->nullable()->after('is_blacklisted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'credit_score',
                'credit_tier',
                'credit_score_updated_at',
                'is_blacklisted',
                'blacklist_reason',
            ]);
        });
    }
};
