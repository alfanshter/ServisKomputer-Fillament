<x-filament::page>
    <div class="grid grid-cols-3 gap-4">
        <x-filament::stats.card
            heading="Total Pengguna"
            value="{{ \App\Models\User::count() }}"
        />
        <x-filament::stats.card
            heading="Total Pesanan"
            value="{{ \App\Models\Order::count() }}"
        />
        <x-filament::stats.card
            heading="Total Pendapatan"
            value="Rp {{ number_format(\App\Models\Order::sum('total')) }}"
        />
    </div>
</x-filament::page>
