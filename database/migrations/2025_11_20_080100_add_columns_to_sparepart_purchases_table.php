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
        Schema::table('sparepart_purchases', function (Blueprint $table) {
            // Tambah kolom yang kurang
            if (!Schema::hasColumn('sparepart_purchases', 'sparepart_id')) {
                $table->foreignId('sparepart_id')->after('id')->constrained('spareparts')->cascadeOnDelete();
            }

            if (!Schema::hasColumn('sparepart_purchases', 'quantity')) {
                $table->integer('quantity')->default(0);
            }

            if (!Schema::hasColumn('sparepart_purchases', 'cost_price')) {
                $table->decimal('cost_price', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('sparepart_purchases', 'total_cost')) {
                $table->decimal('total_cost', 12, 2)->default(0);
            }

            if (!Schema::hasColumn('sparepart_purchases', 'purchase_date')) {
                $table->date('purchase_date')->nullable();
            }

            if (!Schema::hasColumn('sparepart_purchases', 'supplier')) {
                $table->string('supplier')->nullable();
            }

            if (!Schema::hasColumn('sparepart_purchases', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('sparepart_purchases', 'margin_persen')) {
                $table->decimal('margin_persen', 5, 2)->default(0);
            }

            if (!Schema::hasColumn('sparepart_purchases', 'harga_jual')) {
                $table->decimal('harga_jual', 12, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sparepart_purchases', function (Blueprint $table) {
            $columns = [
                'sparepart_id', 'quantity', 'cost_price', 'total_cost',
                'purchase_date', 'supplier', 'notes', 'margin_persen', 'harga_jual'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('sparepart_purchases', $column)) {
                    if ($column === 'sparepart_id') {
                        $table->dropForeign(['sparepart_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
