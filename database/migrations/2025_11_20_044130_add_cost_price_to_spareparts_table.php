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
            $table->decimal('cost_price', 12, 2)->nullable()->after('price')->comment('Harga modal terakhir');
            $table->decimal('average_cost', 12, 2)->nullable()->after('cost_price')->comment('Harga modal rata-rata');
            $table->decimal('margin_percent', 5, 2)->nullable()->after('average_cost')->comment('Margin keuntungan (%)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'average_cost', 'margin_percent']);
        });
    }
};
