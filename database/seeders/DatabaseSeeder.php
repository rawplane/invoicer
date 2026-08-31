<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Pemilik UMKM',
            'email' => 'owner@umkm.id',
            'business_name' => 'Toko Berkah Utama',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 45, Jakarta',
            'subscription_plan' => 'free_trial',
            'trial_ends_at' => now()->addDays(14),
        ]);

        $categories = collect(['Operasional', 'Gaji & Bonus', 'Marketing', 'Peralatan', 'Utilitas & Listrik'])
            ->map(fn ($name) => ExpenseCategory::create([
                'user_id' => $user->id,
                'name' => $name,
            ]));

        $clients = Client::factory(5)->create(['user_id' => $user->id]);

        foreach ($clients as $index => $client) {
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'client_id' => $client->id,
                'invoice_number' => 'INV-'.date('Y').'-'.str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'issue_date' => now()->subDays(10)->toDateString(),
                'due_date' => now()->addDays(10)->toDateString(),
                'status' => $index % 2 === 0 ? 'paid' : 'sent',
                'subtotal' => 1500000.00,
                'tax' => 165000.00,
                'discount' => 50000.00,
                'total' => 1615000.00,
                'notes' => 'Pembayaran via transfer bank',
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => 'Jasa Pembuatan Software / Layanan',
                'quantity' => 1,
                'unit_price' => 1500000.00,
                'amount' => 1500000.00,
            ]);
        }

        foreach ($categories as $category) {
            Expense::create([
                'user_id' => $user->id,
                'expense_category_id' => $category->id,
                'title' => 'Biaya '.$category->name,
                'amount' => rand(100000, 750000),
                'entry_date' => now()->subDays(rand(1, 15))->toDateString(),
                'notes' => 'Catatan transaksi rutin',
            ]);
        }
    }
}
