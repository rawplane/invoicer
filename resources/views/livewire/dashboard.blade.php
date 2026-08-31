<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Dashboard Ringkasan UMKM: {{ auth()->user()->business_name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Widget Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Income -->
                <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-slate-200 border-l-4 border-emerald-500">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Pemasukan Bulan Ini</div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg>
                        </div>
                    </div>
                    <div class="mt-3 text-2xl font-bold text-slate-900">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
                    <div class="mt-1 text-xs text-slate-400">Total invoice bertanda paid bulan ini</div>
                </div>

                <!-- Expense -->
                <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-slate-200 border-l-4 border-rose-500">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Pengeluaran Bulan Ini</div>
                        <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
                        </div>
                    </div>
                    <div class="mt-3 text-2xl font-bold text-slate-900">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</div>
                    <div class="mt-1 text-xs text-slate-400">Total operasional & pengeluaran usaha</div>
                </div>

                <!-- Unpaid Invoices -->
                <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-slate-200 border-l-4 border-amber-500">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Belum Dibayar (Pending)</div>
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                    <div class="mt-3 text-2xl font-bold text-slate-900">Rp {{ number_format($unpaidInvoicesSum, 0, ',', '.') }}</div>
                    <div class="mt-1 text-xs text-amber-600 font-semibold">{{ $unpaidInvoicesCount }} Invoice aktif (sent / overdue)</div>
                </div>
            </div>

            <!-- Chart.js Section -->
            <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Grafik Pemasukan vs Pengeluaran (6 Bulan Terakhir)</h3>
                <div class="relative h-72">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>

            <!-- Recent Invoices Table -->
            <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-slate-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-slate-900">Invoice Terbaru</h3>
                    <a href="{{ route('invoices.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium" wire:navigate>Lihat Semua →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No. Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($recentInvoices as $inv)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-emerald-600">
                                        <a href="{{ route('invoices.show', $inv->id) }}" wire:navigate>{{ $inv->invoice_number }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $inv->client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                            @if($inv->status === 'paid') bg-emerald-100 text-emerald-800
                                            @elseif($inv->status === 'sent') bg-blue-100 text-blue-800
                                            @elseif($inv->status === 'overdue') bg-rose-100 text-rose-800
                                            @elseif($inv->status === 'cancelled') bg-slate-100 text-slate-600
                                            @else bg-amber-100 text-amber-800 @endif">
                                            {{ $inv->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 0a9 9 0 1118 0 9 9 0 01-18 0z" /></svg>
                                            <span>Belum ada invoice. <a href="{{ route('invoices.create') }}" wire:navigate class="text-emerald-600 hover:underline font-medium">Buat invoice pertama Anda →</a></span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Chart.js via Vite (bukan CDN) -->
    @vite('resources/js/dashboard.js')
    <script>
        document.addEventListener('livewire:navigated', () => {
            initChart();
        });

        document.addEventListener('DOMContentLoaded', () => {
            initChart();
        });

        function initChart() {
            const ctx = document.getElementById('financeChart');
            if (!ctx) return;

            if (window.myFinanceChart) {
                window.myFinanceChart.destroy();
            }

            window.myFinanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartMonths),
                    datasets: [
                        {
                            label: 'Pemasukan (Rp)',
                            data: @json($incomeData),
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pengeluaran (Rp)',
                            data: @json($expenseData),
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</div>
