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
        // Untuk MySQL, kita perlu ALTER TABLE untuk update ENUM
        DB::statement("ALTER TABLE transactions MODIFY COLUMN metode_pembayaran VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM lama
        DB::statement("ALTER TABLE transactions MODIFY COLUMN metode_pembayaran ENUM('cash','paylater','visa','mastercard','tokped visa','gopay later','seabank','BCA','Mandiri') NOT NULL");
    }
};
