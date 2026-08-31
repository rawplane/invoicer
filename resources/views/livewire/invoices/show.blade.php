<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Invoice: ') . $invoice->invoice_number }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                    📄 Cetak / Download PDF
                </a>
                <a href="{{ route('invoices.edit', $invoice->id) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700" wire:navigate>
                    Edit Invoice
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <!-- Header Perusahaan & Invoice -->
                <div class="flex justify-between items-start border-b border-slate-200 pb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ auth()->user()->business_name }}</h1>
                        <p class="text-sm text-slate-500 whitespace-pre-line mt-1">{{ auth()->user()->address ?? '-' }}</p>
                        <p class="text-sm text-slate-500 mt-1">Telp: {{ auth()->user()->phone ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-3xl font-extrabold text-emerald-600">INVOICE</h2>
                        <p class="text-sm font-semibold text-slate-700 mt-1">{{ $invoice->invoice_number }}</p>
                        <span class="inline-block mt-2 px-3 py-1 text-xs font-bold rounded-full uppercase
                            @if($invoice->status === 'paid') bg-emerald-100 text-emerald-800
                            @elseif($invoice->status === 'sent') bg-blue-100 text-blue-800
                            @elseif($invoice->status === 'overdue') bg-rose-100 text-rose-800
                            @else bg-amber-100 text-amber-800 @endif">
                            {{ $invoice->status }}
                        </span>
                    </div>
                </div>

                <!-- Info Pelanggan & Tanggal -->
                <div class="grid grid-cols-2 gap-6 my-6 text-sm">
                    <div>
                        <h3 class="font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Ditagihkan Kepada:</h3>
                        <p class="font-bold text-slate-900 text-base">{{ $invoice->client->name }}</p>
                        @if($invoice->client->company_name)
                            <p class="text-slate-600">{{ $invoice->client->company_name }}</p>
                        @endif
                        <p class="text-slate-500 mt-1">{{ $invoice->client->email ?? '' }}</p>
                        <p class="text-slate-500">{{ $invoice->client->phone ?? '' }}</p>
                        <p class="text-slate-500 whitespace-pre-line">{{ $invoice->client->address ?? '' }}</p>
                    </div>

                    <div class="text-right space-y-1">
                        <p><span class="font-semibold text-slate-600">Tanggal Invoice:</span> {{ $invoice->issue_date->format('d F Y') }}</p>
                        <p><span class="font-semibold text-slate-600">Jatuh Tempo:</span> {{ $invoice->due_date->format('d F Y') }}</p>
                    </div>
                </div>

                <!-- Tabel Line Items -->
                <div class="mt-8 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Deskripsi</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Harga Satuan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td class="px-4 py-4 text-sm text-slate-900 font-medium">{{ $item->description }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-600 text-center">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-600 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-900 font-semibold text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Calculation -->
                <div class="flex justify-end mt-6">
                    <div class="w-full md:w-1/2 space-y-2 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($invoice->tax > 0)
                            <div class="flex justify-between text-slate-600">
                                <span>Pajak</span>
                                <span>Rp {{ number_format($invoice->tax, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        @if($invoice->discount > 0)
                            <div class="flex justify-between text-slate-600">
                                <span>Diskon</span>
                                <span>- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-slate-300">
                            <span>Total Pembayaran</span>
                            <span class="text-emerald-600">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                @if($invoice->notes)
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Catatan:</h4>
                        <p class="text-sm text-slate-600 whitespace-pre-line">{{ $invoice->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
