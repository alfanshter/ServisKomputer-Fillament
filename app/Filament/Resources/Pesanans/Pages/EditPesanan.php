<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPesanan extends EditRecord
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Ini dipanggil saat form edit dibuka, untuk mengisi ulang data ke field.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        // Ambil foto before dari relasi
        $data['before_photos'] = $record->photos()
            ->where('type', 'before')
            ->pluck('path')
            ->toArray();

        // Ambil foto after dari relasi
        $data['after_photos'] = $record->photos()
            ->where('type', 'after')
            ->pluck('path')
            ->toArray();

        // 🔥 Load sparepart data untuk edit
        if ($record->spareparts && $record->spareparts->count() > 0) {
            $data['spareparts_edit'] = $record->spareparts->map(function ($sparepart) {
                return [
                    'sparepart_id' => $sparepart->id,
                    'quantity' => $sparepart->pivot->quantity,
                    'price' => $sparepart->pivot->price,
                    'subtotal' => $sparepart->pivot->subtotal,
                ];
            })->toArray();
        }

        // 🔥 Load services data untuk edit
        if ($record->services && $record->services->count() > 0) {
            $data['services_edit'] = $record->services->map(function ($service) {
                return [
                    'service_id' => $service->id,
                    'quantity' => $service->pivot->quantity,
                    'price' => $service->pivot->price,
                    'subtotal' => $service->pivot->subtotal,
                ];
            })->toArray();
        }

        return $data;
    }

    /**
     * Ini dipanggil sebelum data disimpan ke database.
     * Kita gunakan untuk handle data services_edit dan spareparts_edit
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Hapus field virtual yang tidak ada di tabel pesanans
        unset($data['services_edit']);
        unset($data['spareparts_edit']);

        return $data;
    }

    /**
     * Ini untuk menyimpan ulang foto ke tabel relasi
     */
    protected function afterSave(): void
    {
        $record = $this->record;

        // Hapus foto lama
        $record->photos()->where('type', 'before')->delete();
        $record->photos()->where('type', 'after')->delete();

        // Simpan foto baru
        if (!empty($this->data['before_photos'])) {
            foreach ($this->data['before_photos'] as $path) {
                $record->photos()->create([
                    'type' => 'before',
                    'path' => $path,
                ]);
            }
        }

        if (!empty($this->data['after_photos'])) {
            foreach ($this->data['after_photos'] as $path) {
                $record->photos()->create([
                    'type' => 'after',
                    'path' => $path,
                ]);
            }
        }

        if (!empty($this->data['progress_photos'])) {
            foreach ($this->data['progress_photos'] as $path) {
                $record->photos()->create([
                    'type' => 'progress',
                    'path' => $path,
                ]);
            }
        }

        // 🔥 HANDLE SPAREPART CHANGES
        // Ambil data sparepart lama sebelum update
        $oldSpareparts = $record->spareparts()->get()->keyBy('id');

        // Proses sparepart baru dari form
        if (!empty($this->data['spareparts_edit'])) {
            $newSparepartIds = [];

            foreach ($this->data['spareparts_edit'] as $sparepartData) {
                $sparepartId = $sparepartData['sparepart_id'];
                $newQuantity = $sparepartData['quantity'];
                $newPrice = $sparepartData['price'];
                $newSubtotal = $sparepartData['subtotal'] ?? ($newQuantity * $newPrice);

                $newSparepartIds[] = $sparepartId;

                // Cek apakah sparepart sudah ada sebelumnya
                if ($oldSpareparts->has($sparepartId)) {
                    $oldQuantity = $oldSpareparts[$sparepartId]->pivot->quantity;

                    // Jika quantity berubah, update stok
                    if ($oldQuantity != $newQuantity) {
                        $diff = $newQuantity - $oldQuantity;
                        $sparepart = \App\Models\Sparepart::find($sparepartId);

                        if ($sparepart) {
                            // Kurangi stok jika quantity bertambah, tambah stok jika berkurang
                            $sparepart->decrement('quantity', $diff);
                        }
                    }

                    // Update pivot data
                    $record->spareparts()->updateExistingPivot($sparepartId, [
                        'quantity' => $newQuantity,
                        'price' => $newPrice,
                        'subtotal' => $newSubtotal,
                    ]);
                } else {
                    // Sparepart baru ditambahkan
                    $sparepart = \App\Models\Sparepart::find($sparepartId);

                    if ($sparepart) {
                        // Attach sparepart baru dan kurangi stok
                        $record->spareparts()->attach($sparepartId, [
                            'quantity' => $newQuantity,
                            'price' => $newPrice,
                            'subtotal' => $newSubtotal,
                        ]);

                        $sparepart->decrement('quantity', $newQuantity);
                    }
                }
            }

            // Hapus sparepart yang dihapus dari form dan kembalikan stoknya
            foreach ($oldSpareparts as $oldSparepart) {
                if (!in_array($oldSparepart->id, $newSparepartIds)) {
                    // Kembalikan stok
                    $oldSparepart->increment('quantity', $oldSparepart->pivot->quantity);

                    // Detach dari pesanan
                    $record->spareparts()->detach($oldSparepart->id);
                }
            }
        } else {
            // Jika semua sparepart dihapus, kembalikan semua stok
            foreach ($oldSpareparts as $oldSparepart) {
                $oldSparepart->increment('quantity', $oldSparepart->pivot->quantity);
            }

            $record->spareparts()->detach();
        }

        // 🔥 HANDLE SERVICE CHANGES
        // Ambil data service lama sebelum update
        $oldServices = $record->services()->get()->keyBy('id');

        // Proses service baru dari form
        if (!empty($this->data['services_edit'])) {
            $newServiceIds = [];

            foreach ($this->data['services_edit'] as $serviceData) {
                $serviceId = $serviceData['service_id'];
                $newQuantity = $serviceData['quantity'];
                $newPrice = $serviceData['price'];
                $newSubtotal = $serviceData['subtotal'] ?? ($newQuantity * $newPrice);

                $newServiceIds[] = $serviceId;

                // Cek apakah service sudah ada sebelumnya
                if ($oldServices->has($serviceId)) {
                    // Update pivot data
                    $record->services()->updateExistingPivot($serviceId, [
                        'quantity' => $newQuantity,
                        'price' => $newPrice,
                        'subtotal' => $newSubtotal,
                    ]);
                } else {
                    // Service baru ditambahkan
                    $record->services()->attach($serviceId, [
                        'quantity' => $newQuantity,
                        'price' => $newPrice,
                        'subtotal' => $newSubtotal,
                    ]);
                }
            }

            // Hapus service yang dihapus dari form
            foreach ($oldServices as $oldService) {
                if (!in_array($oldService->id, $newServiceIds)) {
                    // Detach dari pesanan
                    $record->services()->detach($oldService->id);
                }
            }
        } else {
            // Jika semua service dihapus
            $record->services()->detach();
        }

        // Refresh untuk ambil data sparepart dan services terbaru
        $record->refresh();

        // Load ulang relasi services dan spareparts dengan data terbaru dari database
        $record->load(['services', 'spareparts']);

        // 🔥 UPDATE INVOICE ITEMS (snapshot) agar invoice selalu menampilkan data terbaru
        // Hapus invoice items lama
        $record->invoiceItems()->delete();

        // Simpan ulang jasa ke invoice items
        foreach ($record->services as $service) {
            \App\Models\PesananInvoiceItem::create([
                'pesanan_id' => $record->id,
                'item_type' => 'service',
                'item_name' => $service->name,
                'item_description' => $service->description,
                'quantity' => $service->pivot->quantity,
                'price' => $service->pivot->price,
                'subtotal' => $service->pivot->subtotal,
            ]);
        }

        // Simpan ulang sparepart ke invoice items
        foreach ($record->spareparts as $sparepart) {
            \App\Models\PesananInvoiceItem::create([
                'pesanan_id' => $record->id,
                'item_type' => 'sparepart',
                'item_name' => $sparepart->name,
                'item_description' => $sparepart->description,
                'quantity' => $sparepart->pivot->quantity,
                'price' => $sparepart->pivot->price,
                'subtotal' => $sparepart->pivot->subtotal,
            ]);
        }

        // Load ulang untuk perhitungan
        $record->load(['services', 'spareparts']);

        // 🔥 HITUNG ULANG TOTAL COST setelah edit (dengan diskon)
        $serviceCost = $record->services->sum('pivot.subtotal') ?? 0; // Dari master jasa, bukan service_cost lagi
        $sparepartCost = $record->spareparts->sum('pivot.subtotal') ?? 0;
        $discount = $record->discount ?? 0;
        $subtotal = $serviceCost + $sparepartCost;
        $totalCost = $subtotal - $discount;

        // Update total_cost
        $record->update(['total_cost' => $totalCost]);

        // 🔥 Refresh record supaya data terbaru langsung muncul
        $record->refresh();
    }

    /**
     * Redirect setelah save agar data langsung terupdate
     */
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data pesanan berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        // Redirect ke halaman edit yang sama agar data fresh
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
