<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Pesanan yang menggunakan jasa ini
     */
    public function pesanans(): BelongsToMany
    {
        return $this->belongsToMany(Pesanan::class, 'pesanan_service')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }
}
