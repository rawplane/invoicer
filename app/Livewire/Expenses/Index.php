<?php

namespace App\Livewire\Expenses;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $categoryFilter = '';

    public ?string $startDate = '';

    public ?string $endDate = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function deleteExpense(int $id): void
    {
        $expense = Auth::user()->expenses()->findOrFail($id);

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        session()->flash('message', 'Pengeluaran berhasil dihapus.');
    }

    public function render()
    {
        $expenses = Auth::user()->expenses()
            ->with('category')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('notes', 'like', '%'.$this->search.'%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('expense_category_id', $this->categoryFilter);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('entry_date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('entry_date', '<=', $this->endDate);
            })
            ->latest('entry_date')
            ->paginate(10);

        $categories = Auth::user()->expenseCategories()->get();

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
