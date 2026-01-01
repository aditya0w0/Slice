<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // Using chunking to handle large datasets memory-efficiently
        DB::table('users')->orderBy('id')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $latestKyc = DB::table('user_kycs')
                    ->where('user_id', $user->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($latestKyc) {
                    $status = match ($latestKyc->status) {
                        'approved' => 'verified',
                        'rejected' => 'unverified',
                        default => 'pending',
                    };

                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['kyc_status' => $status]);
                }
            }
        });
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
