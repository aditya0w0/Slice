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
        // Generate referral codes for existing users who don't have one
        DB::table('users')->whereNull('referral_code')->get()->each(function ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'referral_code' => 'SLICE' . strtoupper(substr(md5($user->id), 0, 6))
                ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this
    }
};
