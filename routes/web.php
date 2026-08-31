<?php

use App\Http\Controllers\InvoicePdfController;
use App\Livewire\Clients;
use App\Livewire\Dashboard;
use App\Livewire\Expenses;
use App\Livewire\Invoices;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified', 'trial'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Clients
    Route::get('/clients', Clients\Index::class)->name('clients.index');
    Route::get('/clients/create', Clients\Create::class)->name('clients.create');
    Route::get('/clients/{id}/edit', Clients\Edit::class)->name('clients.edit');

    // Invoices
    Route::get('/invoices', Invoices\Index::class)->name('invoices.index');
    Route::get('/invoices/create', Invoices\Create::class)->name('invoices.create');
    Route::get('/invoices/{id}', Invoices\Show::class)->name('invoices.show');
    Route::get('/invoices/{id}/edit', Invoices\Edit::class)->name('invoices.edit');
    Route::get('/invoices/{id}/pdf', [InvoicePdfController::class, 'stream'])->name('invoices.pdf');

    // Expenses
    Route::get('/expenses', Expenses\Index::class)->name('expenses.index');
    Route::get('/expenses/create', Expenses\Create::class)->name('expenses.create');
    Route::get('/expenses/categories', Expenses\Categories::class)->name('expenses.categories');
    Route::get('/expenses/{id}/edit', Expenses\Edit::class)->name('expenses.edit');
});

// Halaman peringatan trial berakhir (auth-only, tanpa middleware trial)
Route::middleware('auth')->get('/trial-expired', function () {
    return view('trial-expired');
})->name('trial.expired');

require __DIR__.'/auth.php';
