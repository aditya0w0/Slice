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
        Schema::create('balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']); // credit = top up, debit = spending
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->string('payment_method')->nullable(); // credit_card, qris, bank_transfer
            $table->string('bank')->nullable(); // For bank transfer: bca, mandiri, bni, bri
            $table->string('virtual_account')->nullable(); // Dynamic VA number
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_transactions');
    }
};
