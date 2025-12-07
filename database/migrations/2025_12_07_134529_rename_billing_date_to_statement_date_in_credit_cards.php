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
        Schema::table('credit_cards', function (Blueprint $table) {
            // Rename billing_day to statement_day (lebih jelas untuk tanggal cetak tagihan)
            $table->renameColumn('billing_day', 'statement_day');
            $table->renameColumn('due_day', 'due_day'); // Keep the same, sudah jelas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_cards', function (Blueprint $table) {
            // Revert back
            $table->renameColumn('statement_day', 'billing_day');
        });
    }
};
