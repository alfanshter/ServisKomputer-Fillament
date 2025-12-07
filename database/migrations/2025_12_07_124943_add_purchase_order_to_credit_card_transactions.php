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
        Schema::table('credit_card_transactions', function (Blueprint $table) {
            $table->foreignId('sparepart_purchase_order_id')
                ->nullable()
                ->after('sparepart_purchase_id')
                ->constrained('sparepart_purchase_orders')
                ->onDelete('set null')
                ->comment('Link ke Purchase Order jika transaksi dari PO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_card_transactions', function (Blueprint $table) {
            $table->dropForeign(['sparepart_purchase_order_id']);
            $table->dropColumn('sparepart_purchase_order_id');
        });
    }
};
