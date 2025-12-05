<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update ENUM untuk kolom status
        DB::statement("ALTER TABLE `sparepart_purchase_orders`
            MODIFY COLUMN `status`
            ENUM('rekomendasi', 'pending', 'shipped', 'received', 'cancelled')
            NOT NULL DEFAULT 'rekomendasi'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM lama
        DB::statement("ALTER TABLE `sparepart_purchase_orders`
            MODIFY COLUMN `status`
            ENUM('pending', 'shipped', 'received', 'cancelled')
            NOT NULL DEFAULT 'pending'");
    }
};
