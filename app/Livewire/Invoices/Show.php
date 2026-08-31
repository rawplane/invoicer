<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Invoice $invoice;

    public function mount(int $id): void
    {
        $this->invoice = Auth::user()->invoices()
            ->with(['client', 'items'])
            ->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.invoices.show')->layout('layouts.app');
    }
}
