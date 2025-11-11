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

   <!-- Kartu pelanggan terpilih -->
    <template x-if="selectedUserId">
        <x-filament::section
            collapsible="false"
            class="!p-4 border border-gray-200 rounded-xl bg-gray-50 dark:bg-gray-900/50"
        >
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <x-filament::icon
                        icon="heroicon-o-user-circle"
                        class="w-10 h-10 text-primary-500"
                    />
                    <div>
                        <p class="text-xs font-medium text-primary-600 uppercase">Pelanggan Terpilih</p>
                        <p x-text="selectedUserName" class="text-base font-semibold text-gray-900 dark:text-gray-100"></p>
                    </div>
                </div>

                <x-filament::icon-button
                    icon="heroicon-o-x-mark"
                    color="danger"
                    size="sm"
                    label="Hapus Pilihan"
                    @click="clearSelection"
                />
            </div>
        </x-filament::section>
    </template>

    <!-- Tombol buka modal -->
    <div>
        <x-filament::button
            type="button"
            color="primary"
            size="lg"
            icon="heroicon-o-user-plus"
            class="w-full justify-center"
            @click="$dispatch('open-select-user-modal')"
        >
            <span x-show="!selectedUserId">Pilih Pelanggan Lama</span>
            <span x-show="selectedUserId">Ganti Pelanggan</span>
        </x-filament::button>
    </div>

    <!-- Livewire component untuk modal -->
    @livewire('select-user-modal')
</div>
