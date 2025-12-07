<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SparepartPurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'sparepart_id',
        'sparepart_name',
        'sku',
        'description',
        'quantity',
        'cost_price',
        'margin_persen',
        'total_cost',
        'supplier',
        'supplier_contact',
        'payment_method',
        'credit_card_id',
        'order_date',
        'estimated_arrival',
        'received_date',
        'status',
        'notes',
        'is_new_sparepart',
    ];

    protected $casts = [
        'order_date' => 'date',
        'estimated_arrival' => 'date',
        'received_date' => 'date',
        'cost_price' => 'decimal:2',
        'margin_persen' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'is_new_sparepart' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->po_number)) {
                $model->po_number = self::generatePONumber();
            }
        });
    }

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
        return $this->hasOne(CreditCardTransaction::class, 'sparepart_purchase_order_id');
    }

    /**
     * Generate nomor PO otomatis: PO-2025-001
     */
    public static function generatePONumber(): string
    {
        $year = date('Y');
        $prefix = "PO-{$year}-";

        // Cari nomor terakhir untuk tahun ini
        $lastPO = self::where('po_number', 'LIKE', "{$prefix}%")
            ->orderBy('po_number', 'desc')
            ->first();

        if ($lastPO) {
            // Ambil nomor urut dari PO terakhir
            $lastNumber = (int) substr($lastPO->po_number, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Terima barang dari PO
     * - Buat/update sparepart
     * - Tambah stok
     * - Buat record di sparepart_purchases
     * - Update average_cost (rata-rata harga modal)
     * - Catat transaksi pengeluaran
     * - Update status PO
     */
    public function receiveGoods(): void
    {
        DB::transaction(function () {
            // 1. Cari atau buat sparepart
            if ($this->is_new_sparepart || !$this->sparepart_id) {
                // Cek apakah SKU sudah ada di database
                $existingSparepart = null;
                if (!empty($this->sku)) {
                    $existingSparepart = Sparepart::where('sku', $this->sku)->first();
                }

                if ($existingSparepart) {
                    // SKU sudah ada, gunakan sparepart yang sudah ada
                    $sparepart = $existingSparepart;

                    // Update PO dengan sparepart_id yang sudah ada
                    $this->sparepart_id = $sparepart->id;
                    $this->is_new_sparepart = false; // Set ke false karena ternyata sudah ada
                } else {
                    // Buat sparepart baru
                    $sparepart = Sparepart::create([
                        'name' => $this->sparepart_name,
                        'sku' => $this->sku,
                        'description' => $this->description,
                        'quantity' => 0, // Akan ditambahkan di bawah
                        'min_stock' => 5, // default
                        'cost_price' => $this->cost_price,
                        'margin_percent' => $this->margin_persen,
                        'average_cost' => $this->cost_price,
                        'price' => $this->cost_price + ($this->cost_price * $this->margin_persen / 100),
                    ]);

                    // Update PO dengan sparepart_id baru
                    $this->sparepart_id = $sparepart->id;
                }
            }

            // Sekarang pasti sudah ada sparepart_id, ambil sparepart-nya
            if (!isset($sparepart)) {
                // Update sparepart yang sudah ada
                $sparepart = $this->sparepart;
            }

            // Simpan quantity lama untuk kalkulasi rata-rata
            $oldQuantity = $sparepart->quantity;
            $oldAverageCost = $sparepart->average_cost ?? $sparepart->cost_price ?? 0;

            // Update quantity (tambah stok)
            $sparepart->quantity += $this->quantity;

            // Hitung average_cost dengan weighted average
            // Formula: ((qty_lama * harga_lama) + (qty_baru * harga_baru)) / (qty_lama + qty_baru)
            if ($oldQuantity > 0) {
                $totalOldCost = $oldQuantity * $oldAverageCost;
                $totalNewCost = $this->quantity * $this->cost_price;
                $sparepart->average_cost = ($totalOldCost + $totalNewCost) / ($oldQuantity + $this->quantity);
            } else {
                // Jika stok sebelumnya 0, langsung pakai harga baru
                $sparepart->average_cost = $this->cost_price;
            }

            // Update cost_price terakhir
            $sparepart->cost_price = $this->cost_price;

            // Update margin_percent jika ada
            if ($this->margin_persen > 0) {
                $sparepart->margin_percent = $this->margin_persen;
            }

            // Update harga jual berdasarkan margin
            $sparepart->price = $sparepart->average_cost + ($sparepart->average_cost * $sparepart->margin_percent / 100);

            $sparepart->save();

            // 2. Buat record di sparepart_purchases untuk history
            SparepartPurchase::create([
                'sparepart_id' => $sparepart->id,
                'quantity' => $this->quantity,
                'cost_price' => $this->cost_price,
                'total_cost' => $this->total_cost,
                'purchase_date' => $this->received_date ?? now(),
                'supplier' => $this->supplier,
                'notes' => "From PO: {$this->po_number}",
                'margin_persen' => $this->margin_persen,
                'harga_jual' => $this->cost_price + ($this->cost_price * $this->margin_persen / 100),
                'payment_method' => $this->payment_method ?? 'cash',
                'credit_card_id' => $this->credit_card_id,
            ]);

            // 3. Catat transaksi pengeluaran untuk pembelian sparepart
            // HANYA jika bukan pembayaran kartu kredit (untuk CC, pencatatan transaksi dilakukan saat bayar tagihan)
            if ($this->payment_method !== 'credit_card') {
                Transaction::create([
                    'tanggal' => $this->received_date ?? now(),
                    'tipe' => 'pengeluaran',
                    'kategori' => 'pengeluaran sparepart',
                    'nominal' => $this->total_cost,
                    'deskripsi' => "Pembelian Sparepart: {$this->sparepart_name} ({$this->quantity} unit) - PO: {$this->po_number}",
                    'metode_pembayaran' => $this->getTransactionPaymentMethod(),
                    'referensi' => $this->po_number,
                ]);
            }

            // 4. Update status PO
            $this->status = 'received';
            if (!$this->received_date) {
                $this->received_date = now();
            }
            $this->save();
        });
    }

    /**
     * Cek apakah PO bisa diterima
     */
    public function canReceive(): bool
    {
        return in_array($this->status, ['pending', 'shipped']);
    }

    /**
     * Status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'rekomendasi' => 'gray',
            'pending' => 'warning',
            'shipped' => 'info',
            'received' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'rekomendasi' => 'Rekomendasi',
            'pending' => 'Pending',
            'shipped' => 'Dikirim',
            'received' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    /**
     * Get payment method for Transaction table
     * Map new enum values to transaction record format
     */
    protected function getTransactionPaymentMethod(): string
    {
        if (!$this->payment_method) {
            return 'BCA'; // default fallback
        }

        return match($this->payment_method) {
            'cash' => 'cash',
            'transfer' => 'BCA', // default to BCA for transfer
            'credit_card' => $this->creditCard ? trim($this->creditCard->card_name) : 'Kartu Kredit',
            default => $this->payment_method,
        };
    }
}

