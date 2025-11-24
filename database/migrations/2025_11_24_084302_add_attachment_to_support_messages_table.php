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
        Schema::table('support_messages', function (Blueprint $table) {
            $table->string('attachment_url')->nullable();
            $table->string('attachment_type')->nullable(); // image, document, video
            $table->string('attachment_name')->nullable();
            $table->integer('attachment_size')->nullable(); // in bytes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropColumn(['attachment_url', 'attachment_type', 'attachment_name', 'attachment_size']);
        });
    }
};
