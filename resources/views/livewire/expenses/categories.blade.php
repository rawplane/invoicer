<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Kategori Pengeluaran') }}
            </h2>
            <a href="{{ route('expenses.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700" wire:navigate>
                ← Kembali ke Daftar Pengeluaran
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session()->has('message'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Form Tambah Kategori -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Tambah Kategori Baru</h3>
                <form wire:submit="save" class="flex gap-4">
                    <div class="flex-1">
                        <x-text-input wire:model="name" placeholder="Nama Kategori (misal: Operasional, Gaji, Marketing)" class="w-full" />
                        <x-input-error class="mt-1" :messages="$errors->get('name')" />
                    </div>
                    <x-primary-button>Simpan Kategori</x-primary-button>
                </form>
            </div>

            <!-- Tabel Daftar Kategori -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Daftar Kategori Tersedia</h3>
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Jumlah Pengeluaran</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    @if ($editingId === $category->id)
                                        <form wire:submit.prevent="updateCategory" class="flex gap-2">
                                            <x-text-input wire:model="editingName" class="py-1 text-sm" />
                                            <x-primary-button class="py-1 text-xs">Simpan</x-primary-button>
                                            <button type="button" wire:click="$set('editingId', null)" class="text-xs text-slate-500">Batal</button>
                                        </form>
                                    @else
                                        {{ $category->name }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $category->expenses_count }} transaksi
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button wire:click="editCategory({{ $category->id }})" class="text-emerald-600 hover:text-emerald-700">Edit</button>
                                    <button wire:click="deleteCategory({{ $category->id }})" wire:confirm="Yakin ingin menghapus kategori ini?" class="text-rose-600 hover:text-rose-900">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-slate-500">Belum ada kategori pengeluaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
