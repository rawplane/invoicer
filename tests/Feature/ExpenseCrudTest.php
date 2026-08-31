<?php

use App\Models\ExpenseCategory;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('can create expense with own category', function () {
    $category = ExpenseCategory::factory()->create(['user_id' => $this->user->id]);

    Livewire::test('expenses.create')
        ->set('title', 'Beli ATK')
        ->set('expense_category_id', $category->id)
        ->set('amount', 150000)
        ->set('entry_date', now()->format('Y-m-d'))
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('expenses.index'));

    $this->assertDatabaseHas('expenses', [
        'title' => 'Beli ATK',
        'amount' => 150000,
        'expense_category_id' => $category->id,
        'user_id' => $this->user->id,
    ]);
});

test('cannot create expense with other users category', function () {
    $otherUser = User::factory()->create();
    $otherCategory = ExpenseCategory::factory()->create(['user_id' => $otherUser->id]);

    Livewire::test('expenses.create')
        ->set('title', 'Test')
        ->set('expense_category_id', $otherCategory->id)
        ->set('amount', 50000)
        ->set('entry_date', now()->format('Y-m-d'))
        ->call('save')
        ->assertHasErrors(['expense_category_id']);
});

test('expense validation requires positive amount', function () {
    Livewire::test('expenses.create')
        ->set('title', 'Test')
        ->set('amount', 0)
        ->call('save')
        ->assertHasErrors(['amount']);
});

test('can create expense without category', function () {
    Livewire::test('expenses.create')
        ->set('title', 'Biaya Tak Terduga')
        ->set('expense_category_id', null)
        ->set('amount', 75000)
        ->set('entry_date', now()->format('Y-m-d'))
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('expenses', [
        'title' => 'Biaya Tak Terduga',
        'amount' => 75000,
        'expense_category_id' => null,
        'user_id' => $this->user->id,
    ]);
});

test('can delete own expense', function () {
    $category = ExpenseCategory::factory()->create(['user_id' => $this->user->id]);
    $expense = \App\Models\Expense::factory()->create([
        'user_id' => $this->user->id,
        'expense_category_id' => $category->id,
    ]);

    Livewire::test('expenses.index')
        ->call('deleteExpense', $expense->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
});
