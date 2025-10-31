<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'tipe',
        'kategori',
        'deskripsi',
        'nominal',
        'metode_pembayaran',
        'referensi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    /**
     * 🔗 Jika nanti transaksi terhubung dengan pesanan
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'referensi', 'id');
    }

    /**
     * 🧮 Accessor untuk format nominal (contoh: Rp120.000)
     */
    public function getFormattedNominalAttribute(): string
    {
        return 'Rp' . number_format($this->nominal, 0, ',', '.');
    }

    /**
     * 🧭 Scope untuk filter tipe transaksi
     */
    public function scopePemasukan($query)
    {
        return $query->where('tipe', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('tipe', 'pengeluaran');
    }
}
