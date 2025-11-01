<div class="space-y-4 text-center">
    <p class="text-gray-300">Pilih tindakan untuk servis ini:</p>
    <div class="flex justify-center gap-4 mt-4">
        <x-filament::button
            color="danger"
            wire:click="$dispatch('close-modal', { id: 'next_status' }); $wire.call('updateStatus', '{{ $record->id }}', 'batal')">
            Batalkan Servis
        </x-filament::button>

        <x-filament::button
            color="success"
            wire:click="$dispatch('close-modal', { id: 'next_status' }); $wire.call('updateStatus', '{{ $record->id }}', 'dalam proses')">
            Lanjut Proses
        </x-filament::button>
    </div>
</div>
