<?php

namespace App\Livewire\Clients;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteClient(int $id): void
    {
        $client = Auth::user()->clients()->findOrFail($id);
        $client->delete();

        session()->flash('message', 'Pelanggan berhasil dihapus.');
    }

    public function render()
    {
        $clients = Auth::user()->clients()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('company_name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.clients.index', [
            'clients' => $clients,
        ])->layout('layouts.app');
    }
}
