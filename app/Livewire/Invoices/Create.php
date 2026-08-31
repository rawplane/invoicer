<?php

namespace App\Livewire\Invoices;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public ?int $client_id = null;

    public string $invoice_number = '';

    public string $issue_date = '';

    public string $due_date = '';

    public string $status = 'draft';

    public float $tax = 0;

    public float $discount = 0;

    public float $subtotal = 0;

    public float $total = 0;

    public string $notes = '';

    public array $items = [];

    public function mount(): void
    {
        $this->issue_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(14)->format('Y-m-d');
        // Nomor invoice dibuat saat save() untuk hindari race condition
        $this->invoice_number = $this->previewInvoiceNumber();

        $this->items = [
            [
                'description' => '',
                'quantity' => 1,
                'unit_price' => 0,
                'amount' => 0,
            ],
        ];

        $this->calculateTotals();
    }

    /**
     * Preview nomor invoice berdasarkan data saat ini (belum dipersist).
     * Nomor final di-generate ulang dalam transaksi saat save() untuk mencegah race condition.
     */
    public function previewInvoiceNumber(): string
    {
        return $this->generateInvoiceNumber();
    }

    public function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";

        $latestInvoice = Auth::user()->invoices()
            ->where('invoice_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if (! $latestInvoice) {
            return "{$prefix}0001";
        }

        $lastNumber = (int) substr($latestInvoice->invoice_number, -4);
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$nextNumber}";
    }

    public function addItem(): void
    {
        $this->items[] = [
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'amount' => 0,
        ];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->calculateTotals();
        }
    }

    public function updatedItems(): void
    {
        $this->calculateTotals();
    }

    public function updatedTax(): void
    {
        $this->calculateTotals();
    }

    public function updatedDiscount(): void
    {
        $this->calculateTotals();
    }

    public function calculateTotals(): void
    {
        $this->subtotal = 0;

        foreach ($this->items as $key => $item) {
            $qty = (float) ($item['quantity'] ?? 0);
            $price = (float) ($item['unit_price'] ?? 0);
            $amount = $qty * $price;

            $this->items[$key]['amount'] = $amount;
            $this->subtotal += $amount;
        }

        $taxVal = (float) $this->tax;
        $discountVal = (float) $this->discount;

        $this->total = max(0, $this->subtotal + $taxVal - $discountVal);
    }

    public function save(): void
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'tax' => 'numeric|min:0',
            'discount' => 'numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'client_id.required' => 'Silakan pilih pelanggan.',
            'items.*.description.required' => 'Deskripsi item wajib diisi.',
            'items.*.quantity.min' => 'Jumlah item harus lebih dari 0.',
        ]);

        // Verifikasi client milik user yang sedang login
        $client = Auth::user()->clients()->findOrFail($this->client_id);

        $maxAttempts = 5;
        $created = false;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                DB::transaction(function () use ($client, &$created) {
                    // Lock invoice terakhir milik user untuk mencegah race condition
                    $year = date('Y');
                    $prefix = "INV-{$year}-";

                    $latestInvoice = Auth::user()->invoices()
                        ->where('invoice_number', 'like', "{$prefix}%")
                        ->orderBy('id', 'desc')
                        ->lockForUpdate()
                        ->first();

                    $nextNumber = $latestInvoice
                        ? (int) substr($latestInvoice->invoice_number, -4) + 1
                        : 1;

                    $invoiceNumber = $prefix.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                    $invoice = Auth::user()->invoices()->create([
                        'client_id' => $client->id,
                        'invoice_number' => $invoiceNumber,
                        'issue_date' => $this->issue_date,
                        'due_date' => $this->due_date,
                        'status' => $this->status,
                        'subtotal' => $this->subtotal,
                        'tax' => $this->tax,
                        'discount' => $this->discount,
                        'total' => $this->total,
                        'notes' => $this->notes,
                    ]);

                    foreach ($this->items as $item) {
                        $invoice->items()->create([
                            'description' => $item['description'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'amount' => (float) $item['quantity'] * (float) $item['unit_price'],
                        ]);
                    }
                });

                $created = true;
                break;
            } catch (\Illuminate\Database\QueryException $e) {
                // Tangkap unique constraint violation (SQLSTATE 23000) lalu retry
                if ($e->getCode() !== '23000' && ! str_contains($e->getMessage(), '23000')) {
                    throw $e;
                }
            }
        }

        if (! $created) {
            $this->addError('invoice_number', 'Gagal membuat invoice setelah beberapa percobaan. Coba lagi.');
            return;
        }

        session()->flash('message', 'Invoice berhasil dibuat.');

        $this->redirect(route('invoices.index'), navigate: true);
    }

    public function render()
    {
        $clients = Auth::user()->clients()->orderBy('name')->get();

        return view('livewire.invoices.create', [
            'clients' => $clients,
        ])->layout('layouts.app');
    }
}
