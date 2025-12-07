<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify ENUM to add 'pembayaran kartu kredit' option
        DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `kategori` ENUM(
            'pemasukan',
            'pengeluaran sparepart',
            'pengeluaran operasional',
            'marketing',
            'sodaqoh',
            'alat bahan',
            'gaji karyawan',
            'pengeluaran wajib',
            'pembayaran kartu kredit'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM values
        DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `kategori` ENUM(
            'pemasukan',
            'pengeluaran sparepart',
            'pengeluaran operasional',
            'marketing',
            'sodaqoh',
            'alat bahan',
            'gaji karyawan',
            'pengeluaran wajib'
        ) NOT NULL");
    }
};
