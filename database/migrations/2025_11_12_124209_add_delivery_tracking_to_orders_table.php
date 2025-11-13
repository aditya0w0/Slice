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
        Schema::table('orders', function (Blueprint $table) {
            // Delivery tracking fields
            $table->string('delivery_status')->default('pending')->after('status');
            // Status options: pending, processing, packed, shipped, out_for_delivery, delivered

            $table->timestamp('processing_at')->nullable()->after('delivery_status');
            $table->timestamp('packed_at')->nullable()->after('processing_at');
            $table->timestamp('shipped_at')->nullable()->after('packed_at');
            $table->timestamp('out_for_delivery_at')->nullable()->after('shipped_at');
            $table->timestamp('delivered_at')->nullable()->after('out_for_delivery_at');

            $table->timestamp('estimated_delivery_date')->nullable()->after('delivered_at');
            $table->text('delivery_notes')->nullable()->after('estimated_delivery_date');
            $table->string('tracking_number')->nullable()->after('delivery_notes');
            $table->string('courier_name')->nullable()->after('tracking_number');
            $table->string('courier_phone')->nullable()->after('courier_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_status',
                'processing_at',
                'packed_at',
                'shipped_at',
                'out_for_delivery_at',
                'delivered_at',
                'estimated_delivery_date',
                'delivery_notes',
                'tracking_number',
                'courier_name',
                'courier_phone',
            ]);
        });
    }
};
