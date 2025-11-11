<x-filament-panels::page>
    <div class="space-y-6">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pilih Pelanggan Lama</h1>
            <p class="text-gray-600 mt-2">Cari dan pilih pelanggan dari daftar yang sudah ada</p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto mb-8">
            <input 
                type="text"
                wire:model.live="searchQuery"
                placeholder="🔍 Cari nama, nomor HP, atau email..."
                class="w-full px-6 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
            />
        </div>

        <!-- Customer Grid -->
        <div class="max-w-4xl mx-auto">
            @if(count($customers) > 0)
                <div class="grid gap-4">
                    @foreach($customers as $customer)
                        <button 
                            type="button"
                            wire:click="selectCustomer({{ $customer->id }})"
                            class="text-left p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition duration-150"
                        >
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $customer->name }}</h3>
                                    <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-600">
                                        <span>📱 {{ $customer->phone ?? 'Tidak ada' }}</span>
                                        <span>📧 {{ $customer->email ?? 'Tidak ada' }}</span>
                                    </div>
                                    @if($customer->address)
                                    <p class="text-sm text-gray-500 mt-2">📍 {{ $customer->address }}</p>
                                    @endif
                                </div>
                                <div class="ml-4 text-2xl text-blue-600">→</div>
                            </div>
                        </button>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-2xl text-gray-500">😕 Tidak ada pelanggan yang ditemukan</p>
                    @if($searchQuery)
                        <p class="text-gray-600 mt-2">Coba cari dengan kata kunci yang berbeda</p>
                    @else
                        <p class="text-gray-600 mt-2">Belum ada pelanggan dalam sistem</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center gap-4 mt-8">
            <a href="{{ route('filament.admin.resources.pesanans.index') }}" class="inline-flex items-center px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition">
                ← Kembali
            </a>
            <a href="{{ route('filament.admin.resources.pesanans.create', ['skip_selection' => true]) }}" class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                ➕ Pelanggan Baru
            </a>
        </div>
    </div>
</x-filament-panels::page>
