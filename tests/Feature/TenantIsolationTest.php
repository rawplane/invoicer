<?php

use App\Models\Client;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user A cannot view or edit user B client data', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $clientB = Client::factory()->create(['user_id' => $userB->id]);

    $this->actingAs($userA)
        ->get(route('clients.edit', $clientB->id))
        ->assertStatus(404);
});

test('user A cannot view or edit user B invoice data', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $invoiceB = Invoice::factory()->create(['user_id' => $userB->id]);

    $this->actingAs($userA)
        ->get(route('invoices.show', $invoiceB->id))
        ->assertStatus(404);

    $this->actingAs($userA)
        ->get(route('invoices.edit', $invoiceB->id))
        ->assertStatus(404);

    $this->actingAs($userA)
        ->get(route('invoices.pdf', $invoiceB->id))
        ->assertStatus(404);
});

test('user A cannot view or edit user B expense data', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $expenseB = Expense::factory()->create(['user_id' => $userB->id]);

    $this->actingAs($userA)
        ->get(route('expenses.edit', $expenseB->id))
        ->assertStatus(404);
});

test('invoice numbers are unique per user per year', function () {
    $user = User::factory()->create();

    $invoice1 = Invoice::factory()->create([
        'user_id' => $user->id,
        'invoice_number' => 'INV-2026-0001',
    ]);

    expect($invoice1->invoice_number)->toBe('INV-2026-0001');
});
