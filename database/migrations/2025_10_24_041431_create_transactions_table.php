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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->enum('kategori', [
                'pemasukan',
                'pengeluaran sparepart',
                'pengeluaran operasional',
                'marketing',
                'sodaqoh',
                'alat bahan',
                'gaji karyawan',
                'pengeluaran wajib',
            ]);
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 12, 2);
            $table->enum('metode_pembayaran', [
                'cash',
                'paylater',
                'visa',
                'mastercard',
                'tokped visa',
                'gopay later',
                'seabank',
                'BCA',
                'Mandiri',
            ]);
            $table->string('referensi')->nullable(); // misal no invoice atau ID pesanan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
