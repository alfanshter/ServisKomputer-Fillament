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
        // Update enum status untuk menambahkan 'siap_diambil'
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM(
            'belum mulai',
            'analisa',
            'selesai_analisa',
            'konfirmasi',
            'dalam proses',
            'menunggu sparepart',
            'on hold',
            'revisi',
            'selesai',
            'siap_diambil',
            'dibayar',
            'batal'
        ) NOT NULL DEFAULT 'belum mulai'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum lama (tanpa 'siap_diambil')
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM(
            'belum mulai',
            'analisa',
            'selesai_analisa',
            'konfirmasi',
            'dalam proses',
            'menunggu sparepart',
            'on hold',
            'revisi',
            'selesai',
            'dibayar',
            'batal'
        ) NOT NULL DEFAULT 'belum mulai'");
    }
};
