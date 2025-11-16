<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'quantity',
        'min_stock',
        'price',
        'location',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_stock' => 'integer',
        'price' => 'decimal:2',
    ];

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock;
    }

    public function pesanans()
    {
        return $this->belongsToMany(Pesanan::class, 'pesanan_sparepart')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }
}
