<?php

namespace App\Livewire\Invoices;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updateStatus(int $id, string $newStatus): void
    {
        $validStatuses = ['draft', 'sent', 'paid', 'overdue', 'cancelled'];

        if (! in_array($newStatus, $validStatuses, true)) {
            $this->dispatch('status-update-failed', message: 'Status tidak valid.');
            return;
        }

        $invoice = Auth::user()->invoices()->findOrFail($id);
        $invoice->update(['status' => $newStatus]);

        session()->flash('message', 'Status invoice berhasil diperbarui.');
    }

    public function deleteInvoice(int $id): void
    {
        $invoice = Auth::user()->invoices()->findOrFail($id);
        $invoice->delete();

        session()->flash('message', 'Invoice berhasil dihapus.');
    }

    public function render()
    {
        $invoices = Auth::user()->invoices()
            ->with('client')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%'.$this->search.'%')
                        ->orWhereHas('client', function ($cq) {
                            $cq->where('name', 'like', '%'.$this->search.'%')
                                ->orWhere('company_name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.invoices.index', [
            'invoices' => $invoices,
        ])->layout('layouts.app');
    }
}
