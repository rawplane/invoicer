<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Expense $expense;

    public string $title = '';

    public ?int $expense_category_id = null;

    public float $amount = 0;

    public string $entry_date = '';

    public $receipt;

    public ?string $existingReceipt = null;

    public string $notes = '';

    public function mount(int $id): void
    {
        $this->expense = Auth::user()->expenses()->findOrFail($id);

        $this->title = $this->expense->title;
        $this->expense_category_id = $this->expense->expense_category_id;
        $this->amount = (float) $this->expense->amount;
        $this->entry_date = $this->expense->entry_date->format('Y-m-d');
        $this->existingReceipt = $this->expense->receipt_path;
        $this->notes = $this->expense->notes ?? '';
    }

    public function update(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'expense_category_id' => [
                'nullable',
                'integer',
                function (string $attribute, ?int $value, \Closure $fail) {
                    if ($value === null) {
                        return;
                    }

                    $exists = ExpenseCategory::where('id', $value)
                        ->where('user_id', Auth::id())
                        ->exists();

                    if (! $exists) {
                        $fail('Kategori pengeluaran yang dipilih tidak valid.');
                    }
                },
            ],
            'amount' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
            'receipt' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        $receiptPath = $this->existingReceipt;
        $newReceiptUploaded = false;

        if ($this->receipt) {
            if ($this->existingReceipt) {
                Storage::disk('public')->delete($this->existingReceipt);
            }
            $receiptPath = $this->receipt->store('receipts', 'public');
            $newReceiptUploaded = true;
        }

        try {
            $this->expense->update([
                'title' => $this->title,
                'expense_category_id' => $this->expense_category_id,
                'amount' => $this->amount,
                'entry_date' => $this->entry_date,
                'receipt_path' => $receiptPath,
                'notes' => $this->notes,
            ]);
        } catch (\Throwable $e) {
            // Hapus file receipt baru bila update DB gagal
            if ($newReceiptUploaded && $receiptPath) {
                Storage::disk('public')->delete($receiptPath);
            }
            throw $e;
        }

        session()->flash('message', 'Pengeluaran berhasil diperbarui.');

        $this->redirect(route('expenses.index'), navigate: true);
    }

    public function render()
    {
        $categories = Auth::user()->expenseCategories()->orderBy('name')->get();

        return view('livewire.expenses.edit', [
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
