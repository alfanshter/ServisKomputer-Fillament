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
        Schema::create('credit_card_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_card_id')->constrained('credit_cards')->onDelete('cascade');
            $table->foreignId('sparepart_purchase_id')->nullable()->constrained('sparepart_purchases')->onDelete('set null');
            $table->date('transaction_date');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->date('billing_date')->nullable(); // Tanggal masuk tagihan
            $table->date('due_date')->nullable(); // Tanggal jatuh tempo
            $table->date('paid_date')->nullable(); // Tanggal bayar
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_card_transactions');
    }
};
