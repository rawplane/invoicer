<?php

namespace App\Livewire\Clients;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
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

    public function save(): void
    {
        $validated = $this->validate();

        Auth::user()->clients()->create($validated);

        session()->flash('message', 'Pelanggan berhasil ditambahkan.');

        $this->redirect(route('clients.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.clients.create')->layout('layouts.app');
    }
}
