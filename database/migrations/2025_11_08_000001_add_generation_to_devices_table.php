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
        if (! Schema::hasColumn('devices', 'generation')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->integer('generation')->nullable()->after('family')->index();
            });

            // NOTE: we do not attempt to backfill seed data here because seeding
            // happens after migrations during `migrate --seed` flows. The
            // DeviceSeeder will set `generation` for seeded rows so newly created
            // installations are populated correctly.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('devices', 'generation')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->dropIndex(['generation']);
                $table->dropColumn('generation');
            });
        }
    }
};
