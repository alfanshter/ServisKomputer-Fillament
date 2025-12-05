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
        Schema::create('pesanan_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->string('item_type'); // 'service' atau 'sparepart'
            $table->string('item_name'); // Nama jasa/sparepart (snapshot)
            $table->string('item_description')->nullable(); // Deskripsi tambahan
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2); // Harga satuan (snapshot)
            $table->decimal('subtotal', 12, 2); // Quantity × Price
            $table->string('source')->nullable(); // 'stock', 'po', 'manual' untuk tracking
            $table->foreignId('source_id')->nullable(); // ID dari sparepart/service (untuk referensi saja, tidak strict foreign key)
            $table->timestamps();

            // Index untuk performa
            $table->index('pesanan_id');
            $table->index('item_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_invoice_items');
    }
};
