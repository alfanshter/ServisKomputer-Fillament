<?php

namespace App\Livewire\Filament\Resources\Pesanans;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SelectCustomerModal extends Component
{
    use WithPagination;

    public $searchQuery = '';
    public $showModal = false;
    public $selectedCustomerId = null;

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $customer = User::find($customerId);
        $this->dispatch('customerSelected', customerId: $customerId, customerName: $customer->name);
        $this->closeModal();
    }

    public function getCustomers()
    {
        return User::where('role', 'user')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('phone', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
            })
            ->orderBy('name')
            ->paginate(8);
    }

    public function render()
    {
        return view('livewire.filament.resources.pesanans.select-customer-modal', [
            'customers' => $this->getCustomers(),
        ]);
    }
}
