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

        // Refresh untuk ambil data sparepart terbaru
        $record->refresh();

        // 🔥 HITUNG ULANG TOTAL COST setelah edit
        $serviceCost = $record->service_cost ?? 0;
        $sparepartCost = $record->spareparts->sum('pivot.subtotal') ?? 0;
        $totalCost = $serviceCost + $sparepartCost;

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
