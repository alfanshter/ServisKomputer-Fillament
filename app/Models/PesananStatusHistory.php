<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Helper untuk mendapatkan label status yang readable
    public function getOldStatusLabelAttribute()
    {
        return $this->getStatusLabel($this->old_status);
    }

    public function getNewStatusLabelAttribute()
    {
        return $this->getStatusLabel($this->new_status);
    }

    private function getStatusLabel($status)
    {
        return match ($status) {
            'belum mulai' => 'Belum Mulai',
            'analisa' => 'Analisa',
            'selesai_analisa' => 'Selesai Analisa',
            'konfirmasi' => 'Konfirmasi',
            'dalam proses' => 'Dalam Proses',
            'menunggu sparepart' => 'Menunggu Sparepart',
            'on hold' => 'On Hold',
            'revisi' => 'Revisi',
            'selesai' => 'Selesai',
            'dibayar' => 'Dibayar',
            'batal' => 'Batal',
            default => ucwords(str_replace('_', ' ', $status ?? '')),
        };
    }
}
