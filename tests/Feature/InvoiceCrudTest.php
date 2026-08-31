<?php

use App\Models\Client;
use App\Models\ExpenseCategory;
use App\Models\Invoice;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->client = Client::factory()->create(['user_id' => $this->user->id]);
    $this->actingAs($this->user);
});

test('can create invoice with dynamic items', function () {
    Livewire::test('invoices.create')
        ->set('client_id', $this->client->id)
        ->set('issue_date', now()->format('Y-m-d'))
        ->set('due_date', now()->addDays(14)->format('Y-m-d'))
        ->set('status', 'draft')
        ->set('tax', 50000)
        ->set('discount', 10000)
        ->set('items', [
            ['description' => 'Jasa Web', 'quantity' => 2, 'unit_price' => 500000, 'amount' => 1000000],
            ['description' => 'Maintenance', 'quantity' => 1, 'unit_price' => 200000, 'amount' => 200000],
        ])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('invoices.index'));

    $invoice = Invoice::latest('id')->first();

    expect($invoice->client_id)->toBe($this->client->id);
    expect($invoice->status)->toBe('draft');
    // subtotal/tax/discount/total cast ke decimal:2 (string), convert ke float untuk compare
    expect((float) $invoice->subtotal)->toBe(1200000.0);
    expect((float) $invoice->tax)->toBe(50000.0);
    expect((float) $invoice->discount)->toBe(10000.0);
    expect((float) $invoice->total)->toBe(1240000.0);
    expect($invoice->invoice_number)->toStartWith('INV-' . date('Y') . '-');
    expect($invoice->items)->toHaveCount(2);
});

test('invoice number is unique and auto-incremented per user', function () {
    $existing = Invoice::factory()->create([
        'user_id' => $this->user->id,
        'client_id' => $this->client->id,
        'invoice_number' => 'INV-' . date('Y') . '-0001',
    ]);

    $existingCount = Invoice::where('user_id', $this->user->id)->count();
    expect($existingCount)->toBe(1, "Pre-condition: should have 1 invoice, got {$existingCount}");

    Livewire::test('invoices.create')
        ->set('client_id', $this->client->id)
        ->set('issue_date', now()->format('Y-m-d'))
        ->set('due_date', now()->addDays(14)->format('Y-m-d'))
        ->set('status', 'draft')
        ->set('items', [
            ['description' => 'Test', 'quantity' => 1, 'unit_price' => 100, 'amount' => 100],
        ])
        ->call('save')
        ->assertHasNoErrors();

    $newInvoice = Invoice::orderBy('id', 'desc')->first();
    expect($newInvoice->invoice_number)->toBe('INV-' . date('Y') . '-0002');
    expect($existing->fresh()->invoice_number)->toBe('INV-' . date('Y') . '-0001');
});

test('invoice validation requires client and at least one item', function () {
    Livewire::test('invoices.create')
        ->set('client_id', null)
        ->set('items', [])
        ->call('save')
        ->assertHasErrors(['client_id', 'items']);
});

test('updateStatus only accepts valid status values', function () {
    $invoice = Invoice::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'draft',
    ]);

    Livewire::test('invoices.index')
        ->call('updateStatus', $invoice->id, 'invalid-status')
        ->assertDispatched('status-update-failed');

    // Pastikan status tidak berubah
    expect($invoice->fresh()->status)->toBe('draft');
});

test('updateStatus updates with valid status', function () {
    $invoice = Invoice::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'draft',
    ]);

    Livewire::test('invoices.index')
        ->call('updateStatus', $invoice->id, 'sent')
        ->assertHasNoErrors();

    expect($invoice->fresh()->status)->toBe('sent');
});

test('can delete own invoice', function () {
    $invoice = Invoice::factory()->create(['user_id' => $this->user->id]);

    Livewire::test('invoices.index')
        ->call('deleteInvoice', $invoice->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
});

test('cannot delete invoice owned by other user', function () {
    $otherUser = User::factory()->create();
    $otherClient = Client::factory()->create(['user_id' => $otherUser->id]);
    $otherInvoice = Invoice::factory()->create([
        'user_id' => $otherUser->id,
        'client_id' => $otherClient->id,
    ]);

    // findOrFail lewat relasi user akan throw ModelNotFoundException (404)
    try {
        Livewire::test('invoices.index')
            ->call('deleteInvoice', $otherInvoice->id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // OK — expected
    }

    $this->assertDatabaseHas('invoices', ['id' => $otherInvoice->id]);
});

test('can view invoice show page', function () {
    $invoice = Invoice::factory()->create([
        'user_id' => $this->user->id,
        'client_id' => $this->client->id,
    ]);

    Livewire::test('invoices.show', ['id' => $invoice->id])
        ->assertOk();
});
