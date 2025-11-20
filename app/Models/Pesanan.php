<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'device_type',
        'damage_description',
        'solution',
        'analisa', // ✅ tambahkan ini
        'priority',
        'status',
        'start_date',
        'kelengkapan',
        'end_date',
        'service_cost',
        'discount',
        'total_cost',
        'capital_cost',
        'notes',
    ];


    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(PesananOrderPhoto::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(PesananStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'pesanan_sparepart')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }

}
