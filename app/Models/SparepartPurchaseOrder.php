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
     * - Catat transaksi pengeluaran
     * - Update status PO
     */
    public function receiveGoods(): void
    {
        DB::transaction(function () {
            // 1. Cari atau buat sparepart
            if ($this->is_new_sparepart || !$this->sparepart_id) {
                // Buat sparepart baru
                $sparepart = Sparepart::create([
                    'name' => $this->sparepart_name,
                    'sku' => $this->sku,
                    'description' => $this->description,
                    'quantity' => $this->quantity,
                    'min_stock' => 5, // default
                    'cost_price' => $this->cost_price,
                    'margin_percent' => $this->margin_persen,
                    'average_cost' => $this->cost_price,
                    'price' => $this->cost_price + ($this->cost_price * $this->margin_persen / 100),
                ]);

                // Update PO dengan sparepart_id baru
                $this->sparepart_id = $sparepart->id;
            } else {
                // Update sparepart yang sudah ada
                $sparepart = $this->sparepart;
                $sparepart->quantity += $this->quantity;
                $sparepart->save();
            }

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
            ]);

            // 3. Update average cost dan selling price
            $sparepart->updatePricing();

            // 4. Catat transaksi pengeluaran untuk pembelian sparepart
            Transaction::create([
                'tanggal' => $this->received_date ?? now(),
                'tipe' => 'pengeluaran',
                'kategori' => 'pengeluaran sparepart',
                'nominal' => $this->total_cost,
                'deskripsi' => "Pembelian Sparepart: {$this->sparepart_name} ({$this->quantity} unit) - PO: {$this->po_number}",
                'metode_pembayaran' => $this->payment_method ?? 'BCA',
                'referensi' => $this->po_number,
            ]);

            // 5. Update status PO
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
            'pending' => 'Menunggu',
            'shipped' => 'Dikirim',
            'received' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
}
