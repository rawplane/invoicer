<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Invoice: ') . $invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form wire:submit="update" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="client_id" :value="__('Pilih Pelanggan *')" />
                            <select wire:model="client_id" id="client_id" class="mt-1 block w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} {{ $client->company_name ? "({$client->company_name})" : '' }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                        </div>

                        <div>
                            <x-input-label for="invoice_number" :value="__('Nomor Invoice *')" />
                            <x-text-input wire:model="invoice_number" id="invoice_number" type="text" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('invoice_number')" />
                        </div>

                        <div>
                            <x-input-label for="issue_date" :value="__('Tanggal Invoice *')" />
                            <x-text-input wire:model="issue_date" id="issue_date" type="date" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('issue_date')" />
                        </div>

                        <div>
                            <x-input-label for="due_date" :value="__('Tanggal Jatuh Tempo *')" />
                            <x-text-input wire:model="due_date" id="due_date" type="date" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select wire:model="status" id="status" class="mt-1 block w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="draft">Draft</option>
                                <option value="sent">Terkirim (Sent)</option>
                                <option value="paid">Lunas (Paid)</option>
                                <option value="overdue">Jatuh Tempo (Overdue)</option>
                                <option value="cancelled">Dibatalkan (Cancelled)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                    </div>

                    <!-- Line Items -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-slate-900 mb-4">Detail Baris Item (Line Items)</h3>
                        
                        <div class="space-y-4">
                            @foreach ($items as $index => $item)
                                <div class="flex flex-col md:flex-row items-center gap-4 bg-slate-50 p-4 rounded-md border border-slate-200" key="item-{{ $index }}">
                                    <div class="w-full md:w-5/12">
                                        <x-input-label :value="__('Deskripsi / Produk / Jasa *')" />
                                        <x-text-input wire:model.live.debounce.300ms="items.{{ $index }}.description" type="text" class="mt-1 block w-full" placeholder="Nama item/layanan" />
                                        <x-input-error class="mt-1" :messages="$errors->get('items.'.$index.'.description')" />
                                    </div>

                                    <div class="w-full md:w-2/12">
                                        <x-input-label :value="__('Jumlah (Qty)')" />
                                        <x-text-input wire:model.live.debounce.300ms="items.{{ $index }}.quantity" type="number" step="0.01" class="mt-1 block w-full" />
                                        <x-input-error class="mt-1" :messages="$errors->get('items.'.$index.'.quantity')" />
                                    </div>

                                    <div class="w-full md:w-3/12">
                                        <x-input-label :value="__('Harga Satuan (Rp)')" />
                                        <x-text-input wire:model.live.debounce.300ms="items.{{ $index }}.unit_price" type="number" step="0.01" class="mt-1 block w-full" />
                                        <x-input-error class="mt-1" :messages="$errors->get('items.'.$index.'.unit_price')" />
                                    </div>

                                    <div class="w-full md:w-2/12">
                                        <x-input-label :value="__('Jumlah (Rp)')" />
                                        <div class="mt-3 font-semibold text-slate-700">
                                            Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        @if (count($items) > 1)
                                            <button type="button" wire:click="removeItem({{ $index }})" class="text-rose-600 hover:text-rose-800 font-bold p-2">
                                                ✕
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" wire:click="addItem" class="mt-4 inline-flex items-center px-3 py-2 bg-slate-200 border border-transparent rounded-md text-sm font-semibold text-slate-700 hover:bg-slate-300">
                            + Tambah Baris Item
                        </button>
                    </div>

                    <!-- Summary & Calculation -->
                    <div class="flex justify-end mt-6">
                        <div class="w-full md:w-1/2 space-y-3 bg-slate-50 p-4 rounded-md border border-slate-200">
                            <div class="flex justify-between text-sm font-medium text-slate-600">
                                <span>Subtotal:</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between items-center text-sm font-medium text-slate-600">
                                <div>
                                    <span>Pajak (Rp):</span>
                                    <p class="text-xs text-slate-400 font-normal">Nominal rupiah, bukan persen</p>
                                </div>
                                <x-text-input wire:model.live.debounce.300ms="tax" type="number" step="0.01" min="0" class="w-1/2 text-right py-1" />
                            </div>

                            <div class="flex justify-between items-center text-sm font-medium text-slate-600">
                                <div>
                                    <span>Diskon (Rp):</span>
                                    <p class="text-xs text-slate-400 font-normal">Nominal rupiah, bukan persen</p>
                                </div>
                                <x-text-input wire:model.live.debounce.300ms="discount" type="number" step="0.01" min="0" class="w-1/2 text-right py-1" />
                            </div>

                            <div class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-slate-300">
                                <span>Total Pembayaran:</span>
                                <span class="text-emerald-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Catatan / Syarat & Ketentuan')" />
                        <textarea wire:model="notes" id="notes" class="mt-1 block w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" rows="3"></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('invoices.index') }}" class="text-slate-600 hover:text-slate-900" wire:navigate>Batal</a>
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
