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
            $table->string('legal_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('zip_code')->nullable()->after('state');
            $table->string('country')->nullable()->default('US')->after('zip_code');
            $table->date('date_of_birth')->nullable()->after('country');
            $table->string('id_number')->nullable()->after('date_of_birth'); // National ID / SSN
            $table->boolean('kyc_verified')->default(false)->after('is_admin');
            $table->timestamp('kyc_verified_at')->nullable()->after('kyc_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'legal_name', 'phone', 'address', 'city', 'state',
                'zip_code', 'country', 'date_of_birth', 'id_number',
                'kyc_verified', 'kyc_verified_at'
            ]);
        });
    }
};
