<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public Client $client;

    public string $name = '';

    public string $company_name = '';

    public string $email = '';

    public string $phone = '';

    public string $address = '';

    protected array $rules = [
        'name' => 'required|string|max:255',
        'company_name' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'address' => 'nullable|string|max:1000',
    ];

    public function mount(int $id): void
    {
        $this->client = Auth::user()->clients()->findOrFail($id);

        $this->name = $this->client->name;
        $this->company_name = $this->client->company_name ?? '';
        $this->email = $this->client->email ?? '';
        $this->phone = $this->client->phone ?? '';
        $this->address = $this->client->address ?? '';
    }

    public function update(): void
    {
        $validated = $this->validate();

        $this->client->update($validated);

        session()->flash('message', 'Data pelanggan berhasil diperbarui.');

        $this->redirect(route('clients.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.clients.edit')->layout('layouts.app');
    }
}
