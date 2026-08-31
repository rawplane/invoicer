<?php

namespace App\Livewire\Expenses;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $title = '';

    public ?int $expense_category_id = null;

    public float $amount = 0;

    public string $entry_date = '';

    public $receipt;

    public string $notes = '';

    public function mount(): void
    {
        $this->entry_date = now()->format('Y-m-d');
    }

    public function save(): void
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
            'receipt' => 'nullable|image|max:2048', // Maksimal 2MB
            'notes' => 'nullable|string',
        ]);

        $receiptPath = null;
        if ($this->receipt) {
            $receiptPath = $this->receipt->store('receipts', 'public');
        }

        try {
            Auth::user()->expenses()->create([
                'title' => $this->title,
                'expense_category_id' => $this->expense_category_id,
                'amount' => $this->amount,
                'entry_date' => $this->entry_date,
                'receipt_path' => $receiptPath,
                'notes' => $this->notes,
            ]);
        } catch (\Throwable $e) {
            // Hapus file receipt orphan bila penyimpanan DB gagal
            if ($receiptPath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($receiptPath);
            }
            throw $e;
        }

        session()->flash('message', 'Pengeluaran berhasil dicatat.');

        $this->redirect(route('expenses.index'), navigate: true);
    }

    public function render()
    {
        $categories = Auth::user()->expenseCategories()->orderBy('name')->get();

        return view('livewire.expenses.create', [
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
