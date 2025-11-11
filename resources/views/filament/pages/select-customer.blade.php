<x-filament::page>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pilih Pelanggan Lama</h1>
            <p class="text-gray-600 mt-2">Cari dan pilih pelanggan dari daftar yang sudah ada</p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto mb-8">
            <div class="relative">
                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text"
                    wire:model.live="searchQuery"
                    placeholder="Cari nama, nomor HP, atau email..."
                    class="w-full pl-10 pr-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                />
            </div>
        </div>

        <!-- Customer List -->
        <div class="max-w-4xl mx-auto">
            @if($this->getCustomers()->count() > 0)
                <div class="grid gap-3">
                    @foreach($this->getCustomers() as $customer)
                        <button 
                            type="button"
                            wire:click="selectCustomer({{ $customer->id }})"
                            class="text-left p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition duration-150 cursor-pointer"
                        >
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $customer->name }}</h3>
                                    <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-600">
                                        @if($customer->phone)
                                        <span class="flex items-center gap-1">
                                            <span>📱</span> {{ $customer->phone }}
                                        </span>
                                        @endif
                                        @if($customer->email)
                                        <span class="flex items-center gap-1">
                                            <span>📧</span> {{ $customer->email }}
                                        </span>
                                        @endif
                                    </div>
                                    @if($customer->address)
                                    <p class="text-sm text-gray-500 mt-2 line-clamp-1">📍 {{ $customer->address }}</p>
                                    @endif
                                </div>
                                <div class="text-2xl text-blue-600 flex-shrink-0">→</div>
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $this->getCustomers()->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                    </svg>
                    <p class="text-2xl text-gray-600 mt-4">Tidak ada pelanggan yang ditemukan</p>
                    @if($searchQuery)
                        <p class="text-gray-500 mt-2">Coba cari dengan kata kunci yang berbeda</p>
                    @else
                        <p class="text-gray-500 mt-2">Belum ada pelanggan dalam sistem</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center gap-3 mt-8 pt-6 border-t">
            <a 
                href="{{ route('filament.admin.resources.pesanans.index') }}" 
                class="inline-flex items-center justify-center px-6 py-2 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
            >
                ← Kembali
            </a>
            <a 
                href="{{ route('filament.admin.resources.pesanans.create', ['skip_selection' => true]) }}" 
                class="inline-flex items-center justify-center px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition"
            >
                ➕ Pelanggan Baru
            </a>
        </div>
    </div>
</x-filament::page>
