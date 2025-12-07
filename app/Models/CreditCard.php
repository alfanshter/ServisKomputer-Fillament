<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class CreditCard extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'card_name',
        'bank_name',
        'card_type',
        'card_number_last4',
        'credit_limit',
        'statement_day',  // Tanggal cetak tagihan (e.g., 26)
        'due_day',        // Tanggal jatuh tempo (e.g., 11)
        'notes',
        'is_active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'statement_day' => 'integer',
        'due_day' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function transactions(): HasMany
    {
        return $this->hasMany(CreditCardTransaction::class);
    }

    public function sparepartPurchases(): HasMany
    {
        return $this->hasMany(SparepartPurchase::class);
    }

    // Helper Methods
    /**
     * Calculate due date for a transaction
     *
     * Logika:
     * - Statement Day: 26 (tagihan dicetak tgl 26)
     * - Due Day: 11 (jatuh tempo tgl 11)
     *
     * Contoh 1: Transaksi 10 Des
     * - Statement bulan ini: 26 Des (10 < 26)
     * - Due date: 11 Jan (bulan setelah statement)
     *
     * Contoh 2: Transaksi 27 Des
     * - Statement bulan depan: 26 Jan (27 > 26)
     * - Due date: 11 Feb (bulan setelah statement)
     */
    public function calculateDueDate(Carbon $transactionDate): Carbon
    {
        $currentMonth = $transactionDate->month;
        $currentYear = $transactionDate->year;

        // Tentukan statement date bulan ini
        $statementThisMonth = Carbon::create(
            $currentYear,
            $currentMonth,
            $this->statement_day
        );

        // Jika transaksi terjadi sebelum statement date bulan ini,
        // maka tagihan cetak bulan ini, jatuh tempo bulan depan
        if ($transactionDate->lessThan($statementThisMonth)) {
            // Statement bulan ini
            $statementDate = $statementThisMonth;
        } else {
            // Statement bulan depan (transaksi setelah statement)
            $statementDate = $statementThisMonth->copy()->addMonth();
        }

        // Due date = bulan setelah statement date, tanggal sesuai due_day
        $dueMonth = $statementDate->copy()->addMonth();
        $dueDate = Carbon::create($dueMonth->year, $dueMonth->month, $this->due_day);

        return $dueDate;
    }

    /**
     * Get billing info for display
     */
    public function getBillingInfoAttribute(): string
    {
        return "Cetak tagihan tgl {$this->statement_day}, Jatuh tempo tgl {$this->due_day}";
    }

    public function getNextBillingDateAttribute(): Carbon
    {
        $today = Carbon::now();
        $statementDate = Carbon::create($today->year, $today->month, $this->statement_day);

        if ($statementDate->isPast()) {
            $statementDate->addMonth();
        }

        return $statementDate;
    }

    public function getNextDueDateAttribute(): Carbon
    {
        $today = Carbon::now();
        $dueDate = Carbon::create($today->year, $today->month, $this->due_day);

        if ($dueDate->isPast()) {
            $dueDate->addMonth();
        }

        return $dueDate;
    }

    public function getTotalOutstandingAttribute(): float
    {
        return $this->transactions()
            ->where('status', 'pending')
            ->sum('amount');
    }

    public function getAvailableCreditAttribute(): float
    {
        return $this->credit_limit - $this->total_outstanding;
    }

    public function getDaysUntilDueAttribute(): int
    {
        return Carbon::now()->diffInDays($this->next_due_date, false);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcomingDue($query, $days = 7)
    {
        $today = Carbon::now();
        $futureDate = $today->copy()->addDays($days);

        return $query->active()
            ->whereHas('transactions', function($q) {
                $q->where('status', 'pending');
            });
    }
}

