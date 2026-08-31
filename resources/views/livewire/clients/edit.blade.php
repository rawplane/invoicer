<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form wire:submit="update" class="space-y-6">
                    <div>
                        <x-input-label for="name" :value="__('Nama Pelanggan / Kontak Utama *')" />
                        <x-text-input wire:model="name" id="name" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="company_name" :value="__('Nama Perusahaan / Bisnis')" />
                        <x-text-input wire:model="company_name" id="company_name" type="text" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Nomor Telepon / WhatsApp')" />
                        <x-text-input wire:model="phone" id="phone" type="text" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Alamat Lengkap')" />
                        <textarea wire:model="address" id="address" class="mt-1 block w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" rows="3"></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('clients.index') }}" class="text-slate-600 hover:text-slate-900" wire:navigate>Batal</a>
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
