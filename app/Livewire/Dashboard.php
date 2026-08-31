<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        // 1. Total pemasukan bulan ini (invoice bertipe 'paid' yang rilis bulan ini)
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $monthlyIncome = $user->invoices()
            ->where('status', 'paid')
            ->whereBetween('issue_date', [$startOfMonth, $endOfMonth])
            ->sum('total');

        // 2. Total pengeluaran bulan ini
        $monthlyExpense = $user->expenses()
            ->whereBetween('entry_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // 3. Invoice belum dibayar (sent & overdue)
        $unpaidInvoicesSum = $user->invoices()
            ->whereIn('status', ['sent', 'overdue'])
            ->sum('total');

        $unpaidInvoicesCount = $user->invoices()
            ->whereIn('status', ['sent', 'overdue'])
            ->count();

        // 4. Data Grafik 6 Bulan Terakhir — digabung dalam 2 query grouped
        //    (sebelumnya: 12 query terpisah dalam loop)
        $chartData = $this->getChartData($user);

        // Recent Invoices
        $recentInvoices = $user->invoices()
            ->with('client')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => $monthlyExpense,
            'unpaidInvoicesSum' => $unpaidInvoicesSum,
            'unpaidInvoicesCount' => $unpaidInvoicesCount,
            'chartMonths' => $chartData['months'],
            'incomeData' => $chartData['income'],
            'expenseData' => $chartData['expense'],
            'recentInvoices' => $recentInvoices,
        ])->layout('layouts.app');
    }

    /**
     * Ambil data grafik 6 bulan terakhir dalam 2 query grouped (bukan 12).
     *
     * @return array{months: array<int, string>, income: array<int, float>, expense: array<int, float>}
     */
    private function getChartData($user): array
    {
        $months = [];
        $incomeByMonth = [];
        $expenseByMonth = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $months[$key] = $month->translatedFormat('M Y');
        }

        $keys = array_keys($months);

        // Query grouped untuk income (invoice paid per bulan)
        $incomeRows = $user->invoices()
            ->where('status', 'paid')
            ->whereBetween('issue_date', [now()->subMonths(5)->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->select(
                DB::raw("strftime('%Y-%m', issue_date) as month_key"),
                DB::raw('SUM(total) as total')
            )
            ->groupBy(DB::raw("strftime('%Y-%m', issue_date)"))
            ->pluck('total', 'month_key');

        // Query grouped untuk expense per bulan
        $expenseRows = $user->expenses()
            ->whereBetween('entry_date', [now()->subMonths(5)->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->select(
                DB::raw("strftime('%Y-%m', entry_date) as month_key"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy(DB::raw("strftime('%Y-%m', entry_date)"))
            ->pluck('total', 'month_key');

        $incomeData = [];
        $expenseData = [];

        foreach ($keys as $key) {
            $incomeData[] = (float) ($incomeRows[$key] ?? 0);
            $expenseData[] = (float) ($expenseRows[$key] ?? 0);
        }

        return [
            'months' => array_values($months),
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
}
