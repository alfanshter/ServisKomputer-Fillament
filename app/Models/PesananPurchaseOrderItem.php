<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananPurchaseOrderItem extends Model
{
    protected $fillable = [
        'pesanan_id',
        'purchase_order_id',
        'sparepart_id',
        'quantity',
        'status',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(SparepartPurchaseOrder::class, 'purchase_order_id');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
