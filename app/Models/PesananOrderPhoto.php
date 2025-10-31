<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananOrderPhoto extends Model
{

    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'type',    // 'before' atau 'after'
        'path',
    ];


    public function pesananOrder()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
