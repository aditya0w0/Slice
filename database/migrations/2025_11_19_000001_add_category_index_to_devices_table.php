<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            // Add index on category for faster filtering when category query param is present
            if (!Schema::hasColumn('devices', 'category')) {
                return;
            }

            $table->index('category', 'devices_category_idx');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'category')) {
                $table->dropIndex('devices_category_idx');
            }
        });
    }
};
