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
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_name'); // e.g., "BCA Visa Platinum"
            $table->string('bank_name'); // e.g., "BCA", "Mandiri"
            $table->enum('card_type', ['visa', 'mastercard', 'jcb', 'amex', 'other'])->default('visa');
            $table->string('card_number_last4'); // Last 4 digits only for security
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->integer('billing_day'); // Tanggal tagihan (1-31)
            $table->integer('due_day'); // Tanggal jatuh tempo (1-31)
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_cards');
    }
};
