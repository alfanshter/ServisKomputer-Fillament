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
        // Update existing data first
        DB::table('sparepart_purchase_orders')
            ->whereIn('payment_method', ['visa', 'mastercard', 'paylater'])
            ->update(['payment_method' => 'credit_card']);

        DB::table('sparepart_purchase_orders')
            ->where('payment_method', 'cash')
            ->orWhereNull('payment_method')
            ->update(['payment_method' => 'cash']);

        Schema::table('sparepart_purchase_orders', function (Blueprint $table) {
            // Drop old column
            $table->dropColumn('payment_method');
        });

        Schema::table('sparepart_purchase_orders', function (Blueprint $table) {
            // Add new enum column
            $table->enum('payment_method', ['cash', 'transfer', 'credit_card'])
                ->default('cash')
                ->after('supplier_contact')
                ->comment('Metode pembayaran: cash, transfer, atau credit_card');

            // Add foreign key to credit_cards
            $table->foreignId('credit_card_id')
                ->nullable()
                ->after('payment_method')
                ->constrained('credit_cards')
                ->onDelete('set null')
                ->comment('ID kartu kredit jika payment_method = credit_card');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sparepart_purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['credit_card_id']);
            $table->dropColumn(['payment_method', 'credit_card_id']);
        });

        Schema::table('sparepart_purchase_orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('supplier_contact');
        });
    }
};
