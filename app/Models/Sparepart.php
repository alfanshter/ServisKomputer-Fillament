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
        'cost_price',
        'margin_percent',
        'average_cost',
        'location',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_stock' => 'integer',
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'margin_percent' => 'decimal:2',
        'average_cost' => 'decimal:2',
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

    public function purchases()
    {
        return $this->hasMany(SparepartPurchase::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(SparepartPurchaseOrder::class);
    }

    /**
     * Hitung rata-rata harga modal dari semua pembelian
     */
    public function calculateAverageCost()
    {
        $purchases = $this->purchases;

        if ($purchases->count() === 0) {
            return $this->cost_price ?? 0;
        }

        $totalCost = $purchases->sum('total_cost');
        $totalQuantity = $purchases->sum('quantity');

        return $totalQuantity > 0 ? round($totalCost / $totalQuantity, 2) : 0;
    }

    /**
     * Hitung harga jual berdasarkan average_cost dan margin_percent
     */
    public function calculateSellingPrice()
    {
        $cost = $this->average_cost ?? $this->cost_price ?? 0;
        $margin = $this->margin_percent ?? 0;

        return round($cost + ($cost * $margin / 100), 2);
    }

    /**
     * Update average cost dan selling price berdasarkan riwayat pembelian
     */
    public function updatePricing()
    {
        $this->average_cost = $this->calculateAverageCost();
        $this->price = $this->calculateSellingPrice();
        $this->save();
    }
}
