<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class SelectUserModal extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedUserId = null;
    public $showModal = false;

    #[On('open-select-user-modal')]
    public function openModal()
    {
        $this->showModal = true;
    }

    public function selectUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->dispatch('user-selected', userId: $userId, userName: $user->name);
        $this->showModal = false;
        $this->reset('search');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('search');
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.select-user-modal', [
            'users' => $users,
        ]);
    }
}
