<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Daftar Pelanggan (Clients)') }}
            </h2>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                + Tambah Pelanggan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4 flex justify-between items-center">
                    <div class="w-1/3">
                        <x-text-input wire:model.live.debounce.300ms="search" placeholder="Cari nama, perusahaan, email..." class="w-full" />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Perusahaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($clients as $client)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">{{ $client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $client->company_name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $client->email ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $client->phone ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('clients.edit', $client->id) }}" class="text-emerald-600 hover:text-emerald-700" wire:navigate>Edit</a>
                                        <button wire:click="deleteClient({{ $client->id }})" wire:confirm="Apakah Anda yakin ingin menghapus pelanggan ini?" class="text-rose-600 hover:text-rose-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-slate-500">Belum ada data pelanggan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
