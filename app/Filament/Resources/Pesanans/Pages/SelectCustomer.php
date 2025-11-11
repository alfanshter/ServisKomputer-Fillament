<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class SelectCustomer extends Page
{
    use WithPagination;

    protected static string $resource = PesananResource::class;

    protected static ?string $title = 'Pilih Pelanggan Lama';

    #[Url]
    public string $searchQuery = '';

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function getCustomers()
    {
        return User::where('role', 'user')
            ->where(function ($query) {
                if ($this->searchQuery) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('phone', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
                }
            })
            ->orderBy('name')
            ->paginate(10);
    }

    public function selectCustomer($customerId)
    {
        return redirect()->route('filament.admin.resources.pesanans.create', ['user_id' => $customerId]);
    }
}
