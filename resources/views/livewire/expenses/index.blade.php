<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Daftar Pengeluaran (Expenses)') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('expenses.categories') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700" wire:navigate>
                    Kelola Kategori
                </a>
                <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700" wire:navigate>
                    + Catat Pengeluaran
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Filter Section -->
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="search" :value="__('Cari Pengeluaran')" />
                        <x-text-input wire:model.live.debounce.300ms="search" placeholder="Judul / Catatan..." class="w-full mt-1" />
                    </div>

                    <div>
                        <x-input-label for="categoryFilter" :value="__('Kategori')" />
                        <select wire:model.live="categoryFilter" class="w-full mt-1 border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option value="">-- Semua Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="startDate" :value="__('Dari Tanggal')" />
                        <x-text-input wire:model.live="startDate" type="date" class="w-full mt-1" />
                    </div>

                    <div>
                        <x-input-label for="endDate" :value="__('Sampai Tanggal')" />
                        <x-text-input wire:model.live="endDate" type="date" class="w-full mt-1" />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Judul Pengeluaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jumlah (Rp)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Bukti / Nota</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($expenses as $expense)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $expense->entry_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">{{ $expense->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                        <span class="bg-slate-100 text-slate-800 text-xs px-2 py-1 rounded-full font-semibold">
                                            {{ $expense->category ? $expense->category->name : 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-rose-600">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                        @if ($expense->receipt_path)
                                            <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" class="text-xs text-emerald-600 underline">Lihat Nota</a>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('expenses.edit', $expense->id) }}" class="text-emerald-600 hover:text-emerald-700" wire:navigate>Edit</a>
                                        <button wire:click="deleteExpense({{ $expense->id }})" wire:confirm="Apakah Anda yakin ingin menghapus pengeluaran ini?" class="text-rose-600 hover:text-rose-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-500">Belum ada transaksi pengeluaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
