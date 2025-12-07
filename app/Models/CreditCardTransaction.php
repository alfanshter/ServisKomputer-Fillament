<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreditCardTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'credit_card_id',
        'sparepart_purchase_id',
        'sparepart_purchase_order_id',
        'transaction_date',
        'description',
        'amount',
        'status',
        'billing_date',
        'due_date',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'billing_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function sparepartPurchase(): BelongsTo
    {
        return $this->belongsTo(SparepartPurchase::class);
    }

    public function sparepartPurchaseOrder(): BelongsTo
    {
        return $this->belongsTo(SparepartPurchaseOrder::class);
    }

    // Helper Methods
    /**
     * Bayar tagihan kartu kredit
     * Method ini akan:
     * 1. Update status jadi 'paid' dengan paid_date
     * 2. Create Transaction record untuk pencatatan kas keluar
     */
    public function payBill(?string $paidDate = null, ?string $paymentMethod = 'transfer'): void
    {
        $paidDate = $paidDate ?? Carbon::now()->toDateString();

        DB::transaction(function () use ($paidDate, $paymentMethod) {
            // 1. Update status CC transaction
            $this->update([
                'status' => 'paid',
                'paid_date' => $paidDate,
            ]);

            // 2. Catat transaksi pengeluaran kas untuk pembayaran kartu kredit
            Transaction::create([
                'tanggal' => $paidDate,
                'tipe' => 'pengeluaran',
                'kategori' => 'pembayaran kartu kredit',
                'nominal' => $this->amount,
                'deskripsi' => "Pembayaran Tagihan Kartu Kredit: {$this->creditCard->card_name} - {$this->description}",
                'metode_pembayaran' => $paymentMethod,
                'referensi' => "CC-TRX-{$this->id}",
            ]);
        });
    }

    public function markAsPaid(?string $paidDate = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => $paidDate ?? Carbon::now(),
        ]);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending'
            && $this->due_date
            && Carbon::now()->isAfter($this->due_date);
    }

    public function getDaysOverdueAttribute(): ?int
    {
        if (!$this->isOverdue()) {
            return null;
        }

        return Carbon::now()->diffInDays($this->due_date);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now());
    }

    public function scopeDueThisMonth($query)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return $query->where('status', 'pending')
            ->whereBetween('due_date', [$startOfMonth, $endOfMonth]);
    }

    // Boot method to auto-update status
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($transaction) {
            if ($transaction->isOverdue() && $transaction->status === 'pending') {
                $transaction->status = 'overdue';
            }
        });
    }
}

