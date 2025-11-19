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
        Schema::create('sparepart_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique()->comment('Nomor PO: PO-2025-001');
            $table->foreignId('sparepart_id')->nullable()->constrained()->nullOnDelete()->comment('Null jika sparepart baru');
            $table->string('sparepart_name')->comment('Nama sparepart');
            $table->string('sku')->nullable()->comment('SKU sparepart');
            $table->text('description')->nullable()->comment('Deskripsi sparepart');
            $table->integer('quantity')->comment('Jumlah yang dipesan');
            $table->decimal('cost_price', 12, 2)->comment('Harga modal per unit');
            $table->decimal('margin_persen', 5, 2)->default(0)->comment('Margin keuntungan %');
            $table->decimal('total_cost', 12, 2)->comment('Total biaya pembelian');
            $table->string('supplier')->nullable()->comment('Nama supplier');
            $table->string('supplier_contact')->nullable()->comment('Kontak supplier');
            $table->date('order_date')->comment('Tanggal order');
            $table->date('estimated_arrival')->nullable()->comment('Estimasi tanggal tiba');
            $table->date('received_date')->nullable()->comment('Tanggal barang diterima');
            $table->enum('status', ['pending', 'shipped', 'received', 'cancelled'])->default('pending');
            $table->text('notes')->nullable()->comment('Catatan pembelian');
            $table->boolean('is_new_sparepart')->default(false)->comment('True jika sparepart belum ada di inventory');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart_purchase_orders');
    }
};
