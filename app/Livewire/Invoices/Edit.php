<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public Invoice $invoice;

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

    public function mount(int $id): void
    {
        $this->invoice = Auth::user()->invoices()->with('items')->findOrFail($id);

        $this->client_id = $this->invoice->client_id;
        $this->invoice_number = $this->invoice->invoice_number;
        $this->issue_date = $this->invoice->issue_date->format('Y-m-d');
        $this->due_date = $this->invoice->due_date->format('Y-m-d');
        $this->status = $this->invoice->status;
        $this->tax = (float) $this->invoice->tax;
        $this->discount = (float) $this->invoice->discount;
        $this->notes = $this->invoice->notes ?? '';

        $this->items = $this->invoice->items->map(fn ($item) => [
            'id' => $item->id,
            'description' => $item->description,
            'quantity' => (float) $item->quantity,
            'unit_price' => (float) $item->unit_price,
            'amount' => (float) $item->amount,
        ])->toArray();

        $this->calculateTotals();
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

    public function update(): void
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
        ]);

        $client = Auth::user()->clients()->findOrFail($this->client_id);

        DB::transaction(function () use ($client) {
            $this->invoice->update([
                'client_id' => $client->id,
                'invoice_number' => $this->invoice_number,
                'issue_date' => $this->issue_date,
                'due_date' => $this->due_date,
                'status' => $this->status,
                'subtotal' => $this->subtotal,
                'tax' => $this->tax,
                'discount' => $this->discount,
                'total' => $this->total,
                'notes' => $this->notes,
            ]);

            $this->invoice->items()->delete();

            foreach ($this->items as $item) {
                $this->invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => (float) $item['quantity'] * (float) $item['unit_price'],
                ]);
            }
        });

        session()->flash('message', 'Invoice berhasil diperbarui.');

        $this->redirect(route('invoices.index'), navigate: true);
    }

    public function render()
    {
        $clients = Auth::user()->clients()->orderBy('name')->get();

        return view('livewire.invoices.edit', [
            'clients' => $clients,
        ])->layout('layouts.app');
    }
}
