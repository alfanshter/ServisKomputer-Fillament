<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'item_type',
        'item_name',
        'item_description',
        'quantity',
        'price',
        'subtotal',
        'source',
        'source_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi ke Pesanan
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Scope untuk filter berdasarkan tipe item
     */
    public function scopeServices($query)
    {
        return $query->where('item_type', 'service');
    }

    public function scopeSpareparts($query)
    {
        return $query->where('item_type', 'sparepart');
    }

    /**
     * Accessor untuk format harga
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
