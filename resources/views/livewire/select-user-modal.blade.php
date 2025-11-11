<div>
    <div class="space-y-4">
        @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4">
            <div class="w-full max-w-2xl bg-white rounded-xl shadow-2xl border border-gray-100 animate-in fade-in duration-300">

                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-blue-500 px-5 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-white">Pilih Pelanggan Lama</h2>
                        <p class="text-indigo-100 text-xs mt-0.5">Cari dan pilih pelanggan yang sudah terdaftar</p>
                    </div>
                    <button
                        wire:click="closeModal"
                        class="p-1.5 rounded-md hover:bg-white/20 text-white transition">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5">
                    <!-- Search Input (Filament component with Livewire binding) -->
                    <div class="flex items-center justify-between mb-4 gap-3">
                        <div class="flex-1">
                            <x-filament::input.wrapper inline-prefix :prefix-icon="\Filament\Support\Icons\Heroicon::MagnifyingGlass">
                                <x-filament::input
                                    type="search"
                                    placeholder="Cari pelanggan..."
                                    wire:model.live="search"
                                    class="w-full" />
                            </x-filament::input.wrapper>
                        </div>

                        <span class="text-xs text-gray-600 whitespace-nowrap">
                            <strong>{{ $users->total() }}</strong> pelanggan
                        </span>
                    </div>

                    <!-- Users Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto custom-scrollbar">
                        @forelse($users as $user)
                        <button
                            wire:click="selectUser({{ $user->id }})"
                            type="button"
                            class="w-full text-left p-3.5 border border-gray-100 rounded-lg hover:border-indigo-300 hover:shadow-sm transition bg-white flex items-start gap-3">
                            <!-- Avatar -->
                            <div class="shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-medium text-sm"
                                    style="background: linear-gradient(135deg, #6366f1, #06b6d4);">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="truncate">
                                        <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-600 transition truncate">
                                            {{ $user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5 truncate">
                                            @if($user->phone){{ $user->phone }}@endif
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-[10px] text-gray-400">Member sejak</p>
                                        <p class="text-xs text-gray-600">{{ optional($user->created_at)->format('Y') ?? '-' }}</p>
                                    </div>
                                </div>
                                @if($user->address ?? false)
                                <p class="text-xs text-gray-400 mt-1.5 truncate">{{ $user->address }}</p>
                                @endif
                            </div>
                        </button>
                        @empty
                        <div class="col-span-1 md:col-span-2 text-center py-10 text-gray-500">
                            <x-heroicon-o-user-group class="w-10 h-10 mx-auto mb-3 text-gray-400" />
                            <p class="font-medium text-gray-600">Tidak ada pelanggan ditemukan</p>
                            <p class="text-xs mt-0.5 text-gray-400">Coba nama, email, atau nomor HP lain</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                    <div class="mt-5 pt-3 border-t border-gray-100">
                        <div class="flex justify-center">
                            {{ $users->links(data: ['scrollTo' => false]) }}
                        </div>
                    </div>
                    @endif
                </div>


                <!-- Footer -->
                <div class="flex justify-end gap-2 border-t border-gray-100 px-5 py-3 bg-gray-50">
                    <x-filament::button
                        type="button"
                        color="gray"
                        size="sm"
                        icon="heroicon-o-x-mark"
                        wire:click="closeModal"
                        class="justify-center">
                        Tutup
                    </x-filament::button>
                </div>

            </div>
        </div>
        @endif
    </div>

    @script
    <script>
        Livewire.on('user-selected', function(data) {
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
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .animate-in {
            animation: fadeInZoom 0.25s ease-out;
        }

        @keyframes fadeInZoom {
            from {
                opacity: 0;
                transform: scale(0.96);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</div>
