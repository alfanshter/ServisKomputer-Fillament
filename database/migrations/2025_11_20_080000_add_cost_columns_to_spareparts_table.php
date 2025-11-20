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
        Schema::table('spareparts', function (Blueprint $table) {
            // Cek kolom sebelum menambahkan untuk menghindari error
            if (!Schema::hasColumn('spareparts', 'cost_price')) {
                $table->decimal('cost_price', 12, 2)->nullable()->after('price')->comment('Harga modal terakhir');
            }

            if (!Schema::hasColumn('spareparts', 'average_cost')) {
                $table->decimal('average_cost', 12, 2)->nullable()->after('cost_price')->comment('Harga modal rata-rata');
            }

            if (!Schema::hasColumn('spareparts', 'margin_percent')) {
                $table->decimal('margin_percent', 5, 2)->nullable()->after('average_cost')->comment('Margin keuntungan (%)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            if (Schema::hasColumn('spareparts', 'cost_price')) {
                $table->dropColumn('cost_price');
            }

            if (Schema::hasColumn('spareparts', 'average_cost')) {
                $table->dropColumn('average_cost');
            }

            if (Schema::hasColumn('spareparts', 'margin_percent')) {
                $table->dropColumn('margin_percent');
            }
        });
    }
};
