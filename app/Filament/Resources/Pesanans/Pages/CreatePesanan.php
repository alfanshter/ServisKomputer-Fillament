<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use App\Models\PesananOrderPhoto;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Get;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    public ?string $selectedCustomerId = null;

    public function mount(): void
    {
        parent::mount();
        
        // Cek apakah ada parameter user_id dari selection
        $userId = request()->query('user_id');
        $skipSelection = request()->query('skip_selection');
        
        $fillData = [];
        
        if ($userId) {
            // Jika user dipilih, set ke existing dan isi user_id
            $fillData['customer_type'] = 'existing';
            $fillData['user_id'] = $userId;
        } elseif ($skipSelection) {
            // Jika skip selection, default ke pelanggan baru
            $fillData['customer_type'] = 'new';
        } else {
            // Default ke pelanggan lama
            $fillData['customer_type'] = 'existing';
        }
        
        $this->form->fill($fillData);
    }

    public function getHeading(): string
    {
        return 'Tambah Pesanan Baru';
    }

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
                'role' => 'user',
                'password' => Hash::make($data['email']), // default password
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
