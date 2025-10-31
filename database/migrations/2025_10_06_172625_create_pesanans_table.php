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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();

            // Relasi ke users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('device_type', 50);
            $table->text('damage_description');
            $table->text('solution')->nullable();
            $table->text('analisa')->nullable();
            $table->enum('priority', ['normal', 'urgent'])->default('normal');
            $table->enum('status', ['belum mulai','analisa', 'selesai_analisa','konfirmasi', 'dalam proses', 'menunggu sparepart', 'selesai', 'dibayar','batal', 'revisi','on hold'])->default('belum mulai');

            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();

            $table->decimal('service_cost', 12, 2)->nullable();
            $table->decimal('capital_cost', 12, 2)->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
