<div>
    <div class="space-y-4">
        @if($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-300">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-white">Pilih Pelanggan Lama</h2>
                                <p class="text-blue-100 text-sm mt-1">Cari dan pilih pelanggan dari daftar yang tersedia</p>
                            </div>
                            <button
                                wire:click="closeModal"
                                class="p-2 hover:bg-white/20 text-white rounded-lg transition"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6">
                        <!-- Search Input -->
                        <div class="mb-6">
                            <div class="relative">
                                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input
                                    wire:model.live="search"
                                    type="text"
                                    placeholder="Cari nama, email, atau no. HP..."
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                                >
                            </div>
                        </div>

                        <!-- Users List -->
                        <div class="space-y-2 max-h-96 overflow-y-auto custom-scrollbar">
                            @forelse($users as $user)
                                <button
                                    wire:click="selectUser({{ $user->id }})"
                                    type="button"
                                    class="w-full text-left p-4 border-2 border-gray-100 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition group"
                                >
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3 flex-1">
                                            <!-- Avatar -->
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <!-- User Info -->
                                            <div>
                                                <p class="font-bold text-gray-900 group-hover:text-blue-700 transition">{{ $user->name }}</p>
                                                <div class="flex gap-2 mt-1">
                                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                    @if($user->phone)
                                                        <span class="text-gray-300">•</span>
                                                        <p class="text-xs text-gray-500">{{ $user->phone }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </button>
                            @empty
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h2v-2a11 11 0 00-20 0v2h2v-2z"></path>
                                    </svg>
                                    <p class="font-medium text-gray-600">Tidak ada pelanggan yang ditemukan</p>
                                    <p class="text-sm mt-1">Coba cari dengan nama, email, atau nomor HP lain</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex justify-center">
                                    {{ $users->links(data: ['scrollTo' => false]) }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 bg-gray-50">
                        <button
                            wire:click="closeModal"
                            type="button"
                            class="px-6 py-2 text-gray-700 font-medium border-2 border-gray-300 rounded-lg hover:bg-gray-100 transition"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>    @script
    <script>
        Livewire.on('user-selected', function(data) {
            // Dispatch event ke Filament form
            const event = new CustomEvent('user-selected', {
                detail: {
                    userId: data.userId,
                    userName: data.userName
                }
            });
            window.dispatchEvent(event);
        });
    </script>
    @endscript

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @keyframes fadeInZoom {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-in {
            animation: fadeInZoom 0.3s ease-out;
        }
    </style>
</div>
