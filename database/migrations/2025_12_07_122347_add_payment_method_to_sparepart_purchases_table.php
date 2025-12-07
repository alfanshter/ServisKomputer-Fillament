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
        Schema::table('sparepart_purchases', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'transfer', 'credit_card'])->default('cash')->after('total_cost');
            $table->foreignId('credit_card_id')->nullable()->after('payment_method')->constrained('credit_cards')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sparepart_purchases', function (Blueprint $table) {
            $table->dropForeign(['credit_card_id']);
            $table->dropColumn(['payment_method', 'credit_card_id']);
        });
    }
};
