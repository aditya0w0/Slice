<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use PDO;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('devices')) {
            // guard against duplicate index creation in a DB-agnostic way
            if (! $this->indexExists('devices', 'devices_family_index')) {
                Schema::table('devices', function (Blueprint $table) {
                    $table->index('family');
                });
            }
        }

        if (Schema::hasTable('cart_items')) {
            if (! $this->indexExists('cart_items', 'cart_user_variant_idx')) {
                Schema::table('cart_items', function (Blueprint $table) {
                    $table->index(['user_id','variant_slug','capacity','months'], 'cart_user_variant_idx');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('devices')) {
            if ($this->indexExists('devices', 'devices_family_index')) {
                Schema::table('devices', function (Blueprint $table) {
                    $table->dropIndex(['family']);
                });
            }
        }

        if (Schema::hasTable('cart_items')) {
            if ($this->indexExists('cart_items', 'cart_user_variant_idx')) {
                Schema::table('cart_items', function (Blueprint $table) {
                    $table->dropIndex('cart_user_variant_idx');
                });
            }
        }
    }

    /**
     * Check whether an index exists on a table in a DB-agnostic way.
     */
    protected function indexExists(string $table, string $indexName): bool
    {
        try {
            $driver = DB::getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        } catch (\Exception $e) {
            return false;
        }

        if ($driver === 'mysql') {
            $res = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return !empty($res);
        }

        if ($driver === 'sqlite') {
            $res = DB::select("PRAGMA index_list('{$table}')");
            foreach ($res as $row) {
                // result is typically an object with a 'name' property
                if (isset($row->name) && $row->name === $indexName) {
                    return true;
                }
                if (is_array($row) && isset($row['name']) && $row['name'] === $indexName) {
                    return true;
                }
            }
            return false;
        }

        // Fallback: try Postgres style lookup
        try {
            $res = DB::select("SELECT indexname FROM pg_indexes WHERE tablename = ? AND indexname = ?", [$table, $indexName]);
            return !empty($res);
        } catch (\Exception $e) {
            // As a last resort, return false to allow migrations to create the index
            return false;
        }
    }
};
