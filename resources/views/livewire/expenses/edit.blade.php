<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Pengeluaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form wire:submit="update" class="space-y-6">
                    <div>
                        <x-input-label for="title" :value="__('Judul / Deskripsi Pengeluaran *')" />
                        <x-text-input wire:model="title" id="title" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="expense_category_id" :value="__('Kategori Pengeluaran')" />
                            <select wire:model="expense_category_id" id="expense_category_id" class="mt-1 block w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="">-- Tanpa Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('expense_category_id')" />
                        </div>

                        <div>
                            <x-input-label for="amount" :value="__('Jumlah (Rp) *')" />
                            <x-text-input wire:model="amount" id="amount" type="number" step="0.01" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="entry_date" :value="__('Tanggal Pengeluaran *')" />
                        <x-text-input wire:model="entry_date" id="entry_date" type="date" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('entry_date')" />
                    </div>

                    <div>
                        <x-input-label for="receipt" :value="__('Upload Bukti / Nota Baru (Opsional)')" />
                        @if ($existingReceipt)
                            <div class="mb-2 text-xs text-slate-600">
                                Nota saat ini: <a href="{{ asset('storage/' . $existingReceipt) }}" target="_blank" class="text-emerald-600 underline">Lihat file</a>
                            </div>
                        @endif
                        <input type="file" wire:model="receipt" id="receipt" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                        <x-input-error class="mt-2" :messages="$errors->get('receipt')" />
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Catatan Tambahan')" />
                        <textarea wire:model="notes" id="notes" class="mt-1 block w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" rows="3"></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('expenses.index') }}" class="text-slate-600 hover:text-slate-900" wire:navigate>Batal</a>
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
