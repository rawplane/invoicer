<?php

namespace App\Livewire\Expenses;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Categories extends Component
{
    public string $name = '';

    public ?int $editingId = null;

    public string $editingName = '';

    protected array $rules = [
        'name' => 'required|string|max:255',
    ];

    public function save(): void
    {
        $this->validate();

        Auth::user()->expenseCategories()->create([
            'name' => $this->name,
        ]);

        $this->name = '';
        session()->flash('message', 'Kategori pengeluaran berhasil dibuat.');
    }

    public function editCategory(int $id): void
    {
        $category = Auth::user()->expenseCategories()->findOrFail($id);
        $this->editingId = $category->id;
        $this->editingName = $category->name;
    }

    public function updateCategory(): void
    {
        $this->validate([
            'editingName' => 'required|string|max:255',
        ]);

        $category = Auth::user()->expenseCategories()->findOrFail($this->editingId);
        $category->update(['name' => $this->editingName]);

        $this->editingId = null;
        $this->editingName = '';
        session()->flash('message', 'Kategori berhasil diperbarui.');
    }

    public function deleteCategory(int $id): void
    {
        $category = Auth::user()->expenseCategories()->findOrFail($id);
        $category->delete();

        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    public function render()
    {
        $categories = Auth::user()->expenseCategories()
            ->withCount('expenses')
            ->latest()
            ->get();

        return view('livewire.expenses.categories', [
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
