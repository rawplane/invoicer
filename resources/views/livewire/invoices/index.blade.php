<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Daftar Invoice') }}
            </h2>
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                + Buat Invoice Baru
            </a>
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
                <div class="mb-4 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="w-full sm:w-1/3">
                        <x-text-input wire:model.live.debounce.300ms="search" placeholder="Cari No. Invoice / Pelanggan..." class="w-full" />
                    </div>
                    <div class="w-full sm:w-1/4">
                        <select wire:model.live="statusFilter" class="w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="draft">Draft</option>
                            <option value="sent">Sent (Terkirim)</option>
                            <option value="paid">Paid (Lunas)</option>
                            <option value="overdue">Overdue (Jatuh Tempo)</option>
                            <option value="cancelled">Cancelled (Batal)</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No. Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-emerald-600">
                                        <a href="{{ route('invoices.show', $invoice->id) }}" wire:navigate>{{ $invoice->invoice_number }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-900 font-medium">
                                        {{ $invoice->client->name }}
                                        @if($invoice->client->company_name)
                                            <span class="text-xs text-slate-500 block">{{ $invoice->client->company_name }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $invoice->issue_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $invoice->due_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select wire:change="updateStatus({{ $invoice->id }}, $event.target.value)" class="text-xs rounded-full font-semibold px-2 py-1 border-0
                                            @if($invoice->status === 'paid') bg-emerald-100 text-emerald-800
                                            @elseif($invoice->status === 'sent') bg-blue-100 text-blue-800
                                            @elseif($invoice->status === 'overdue') bg-rose-100 text-rose-800
                                            @elseif($invoice->status === 'cancelled') bg-slate-100 text-slate-800
                                            @else bg-amber-100 text-amber-800 @endif">
                                            <option value="draft" @selected($invoice->status === 'draft')>Draft</option>
                                            <option value="sent" @selected($invoice->status === 'sent')>Sent</option>
                                            <option value="paid" @selected($invoice->status === 'paid')>Paid</option>
                                            <option value="overdue" @selected($invoice->status === 'overdue')>Overdue</option>
                                            <option value="cancelled" @selected($invoice->status === 'cancelled')>Cancelled</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-slate-600 hover:text-slate-900" wire:navigate>Lihat</a>
                                        <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank" class="text-emerald-600 hover:text-emerald-900">PDF</a>
                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-emerald-600 hover:text-emerald-700" wire:navigate>Edit</a>
                                        <button wire:click="deleteInvoice({{ $invoice->id }})" wire:confirm="Apakah Anda yakin ingin menghapus invoice ini?" class="text-rose-600 hover:text-rose-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-slate-500">Belum ada invoice.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
