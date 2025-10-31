<?php

namespace App\Filament\Resources\Spareparts\Pages;

use App\Filament\Resources\Spareparts\SparepartResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateSparepart extends CreateRecord
{
    protected static string $resource = SparepartResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // ambil inisial nama barang
        $slug = strtoupper(Str::slug($data['name'], '-'));

        // ambil angka urut dari database
        $lastId = \App\Models\Sparepart::max('id') + 1;

        // buat SKU otomatis
        $data['sku'] = 'SP-' . substr($slug, 0, 6) . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);

        return $data;
    }
}
