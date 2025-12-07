<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use App\Models\PesananOrderPhoto;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

      // 🔹 Ini dijalankan sebelum Filament create ke database
    protected function mutateFormDataBeforeCreate(array $data): array
    {
          // Jika customer baru → buat dulu user-nya
          if (($data['customer_type'] ?? null) === 'new') {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
                'role' => 'customer', // ✅ Standarisasi role sebagai 'customer'
                'password' => Hash::make(Str::random(16)), // ✅ Password random yang aman
            ]);

            $data['user_id'] = $user->id;
        }

        return $data;
    }

    protected function afterCreate(): void
    {


        // ✅ 2. Simpan foto sebelum servis
        $beforePhotos = $this->data['before_photos'] ?? [];
        foreach ($beforePhotos as $path) {
            PesananOrderPhoto::create([
                'pesanan_id' => $this->record->id,
                'type' => 'before',
                'path' => $path,
            ]);
        }

        // ✅ 3. Simpan foto sesudah servis
        $afterPhotos = $this->data['after_photos'] ?? [];
        foreach ($afterPhotos as $path) {
            PesananOrderPhoto::create([
                'pesanan_id' => $this->record->id,
                'type' => 'after',
                'path' => $path,
            ]);
        }
    }
}
