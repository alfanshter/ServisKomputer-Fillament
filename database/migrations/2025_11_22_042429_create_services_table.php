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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama jasa: "Ganti Keyboard", "Install OS", dll
            $table->string('category')->nullable(); // Kategori: "Hardware", "Software", dll
            $table->text('description')->nullable(); // Deskripsi detail
            $table->decimal('price', 15, 2)->default(0); // Harga standar
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif
            $table->timestamps();
        });

        // Pivot table untuk pesanan - jasa (many to many)
        Schema::create('pesanan_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->integer('quantity')->default(1); // Jumlah jasa (jika perlu)
            $table->decimal('price', 15, 2); // Harga saat digunakan (bisa beda dari master)
            $table->decimal('subtotal', 15, 2); // qty * price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_service');
        Schema::dropIfExists('services');
    }
};
