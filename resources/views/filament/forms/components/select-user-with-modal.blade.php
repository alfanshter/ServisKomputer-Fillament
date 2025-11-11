@php
    $id = $getId();
    $statePath = $getStatePath();
@endphp

<div x-data="{
    selectedUserName: '',
    selectedUserId: null,
    init() {
        window.addEventListener('user-selected', (e) => {
            this.selectedUserName = e.detail.userName;
            this.selectedUserId = e.detail.userId;
            @this.set('{{ $statePath }}', e.detail.userId);
        });
    }
}" class="space-y-3">
    <!-- Label -->
    <label class="block text-sm font-semibold text-gray-800">
        {{ $getLabel() }}
        @if($isRequired())
            <span class="text-red-500 ml-1">*</span>
        @endif
    </label>

    <!-- Selected User Display Card -->
    <div x-show="selectedUserId" class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 flex-1">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">✓ Pelanggan Terpilih</p>
                    <p x-text="selectedUserName" class="text-lg font-bold text-blue-900 truncate"></p>
                </div>
            </div>
            <button
                type="button"
                @click="selectedUserName = ''; selectedUserId = null; @this.set('{{ $statePath }}', null)"
                class="ml-2 p-2 hover:bg-red-100 text-red-500 rounded-lg transition hover:scale-110"
                title="Hapus pilihan"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Button buka modal -->
    <button
        type="button"
        @click="$dispatch('open-select-user-modal')"
        class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
        </svg>
        <span x-show="!selectedUserId">Pilih Pelanggan Lama</span>
        <span x-show="selectedUserId">Ganti Pelanggan</span>
    </button>

    <!-- Livewire component untuk modal -->
    @livewire('select-user-modal')
</div>
