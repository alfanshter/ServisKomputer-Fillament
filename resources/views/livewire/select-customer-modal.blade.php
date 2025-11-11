<div class="space-y-4">
    <!-- Button untuk buka modal -->
    <button 
        type="button"
        wire:click="openModal"
        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition"
    >
        📋 Pilih dari Daftar Pelanggan
    </button>

    <!-- Modal Backdrop -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeModal">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 max-h-screen overflow-y-auto" @click.stop>
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold">Pilih Pelanggan Lama</h2>
                <button 
                    type="button"
                    wire:click="closeModal"
                    class="text-white hover:text-gray-200 text-2xl"
                >
                    ✕
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Search Input -->
                <div class="mb-4">
                    <input 
                        type="text" 
                        wire:model.live="searchQuery"
                        placeholder="🔍 Cari nama, nomor HP, atau email..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <!-- Customer List -->
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse($customers as $customer)
                        <button 
                            type="button"
                            wire:click="selectCustomer({{ $customer->id }})"
                            class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-400 transition duration-150"
                        >
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">{{ $customer->name }}</div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        📱 {{ $customer->phone ?? 'Tidak ada' }} | 📧 {{ $customer->email ?? 'Tidak ada' }}
                                    </div>
                                    @if($customer->address)
                                    <div class="text-xs text-gray-500 mt-1">📍 {{ Str::limit($customer->address, 50) }}</div>
                                    @endif
                                </div>
                                <div class="ml-2 text-blue-600 text-xl">→</div>
                            </div>
                        </button>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <p class="text-lg">😕 Tidak ada pelanggan yang ditemukan</p>
                            <p class="text-sm mt-2">Coba cari dengan nama, nomor HP, atau email lain</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($customers->count() > 0)
                <div class="mt-4 pt-4 border-t">
                    {{ $customers->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
