<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparepartPurchase extends Model
{
    protected $fillable = [
        'sparepart_id',
        'quantity',
        'cost_price',
        'total_cost',
        'purchase_date',
        'supplier',
        'notes',
        'margin_persen',
        'harga_jual',
        'payment_method',
        'credit_card_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'cost_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'margin_persen' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    public function creditCard()
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function creditCardTransaction()
    {
        return $this->hasOne(CreditCardTransaction::class);
    }
}
