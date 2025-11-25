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
            // Add kyc_status column after email_verified_at
            $table->string('kyc_status')->default('unverified')->after('email_verified_at');
        });

        // Migrate existing KYC data: Set kyc_status based on approved KYC submissions
        DB::statement("
            UPDATE users u
            INNER JOIN user_kycs k ON k.user_id = u.id
            SET u.kyc_status = CASE 
                WHEN k.status = 'approved' THEN 'verified'
                WHEN k.status = 'rejected' THEN 'unverified'
                ELSE 'pending'
            END
            WHERE k.id IN (
                SELECT MAX(id) 
                FROM user_kycs 
                GROUP BY user_id
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kyc_status');
        });
    }
};
