<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public bool $sidebarOpen = false;

    public function toggleSidebar(): void
    {
        $this->sidebarOpen = ! $this->sidebarOpen;
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ sidebarOpen: @entangle('sidebarOpen') }" @toggle-sidebar.window="sidebarOpen = ! sidebarOpen" class="contents">
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 ease-in-out transform lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        <!-- Logo Section -->
        <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-700/50">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5">
                <x-application-logo class="block h-9 w-9" />
                <span class="text-lg font-bold text-white tracking-tight">Invoicer</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('dashboard') }}" wire:navigate
                @class([
                    'group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                    'bg-emerald-500/10 text-emerald-400' => request()->routeIs('dashboard'),
                    'text-slate-400 hover:bg-slate-800 hover:text-slate-200' => !request()->routeIs('dashboard'),
                ])>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z M4 10h16 M10 4v4 M14 4v4 M10 14h4 M10 18h4" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('clients.index') }}" wire:navigate
                @class([
                    'group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                    'bg-emerald-500/10 text-emerald-400' => request()->routeIs('clients.*'),
                    'text-slate-400 hover:bg-slate-800 hover:text-slate-200' => !request()->routeIs('clients.*'),
                ])>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-6 0 4 4 0 006 0zm6 0a4 4 0 10-6 0" />
                </svg>
                Pelanggan
            </a>

            <a href="{{ route('invoices.index') }}" wire:navigate
                @class([
                    'group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                    'bg-emerald-500/10 text-emerald-400' => request()->routeIs('invoices.*'),
                    'text-slate-400 hover:bg-slate-800 hover:text-slate-200' => !request()->routeIs('invoices.*'),
                ])>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 5h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z M8 3v18 M16 3v18" />
                </svg>
                Invoice
            </a>

            <a href="{{ route('expenses.index') }}" wire:navigate
                @class([
                    'group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                    'bg-emerald-500/10 text-emerald-400' => request()->routeIs('expenses.*') && !request()->routeIs('expenses.categories'),
                    'text-slate-400 hover:bg-slate-800 hover:text-slate-200' => !request()->routeIs('expenses.*') || request()->routeIs('expenses.categories'),
                ])>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Pengeluaran
            </a>

            <a href="{{ route('expenses.categories') }}" wire:navigate
                @class([
                    'group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                    'bg-emerald-500/10 text-emerald-400' => request()->routeIs('expenses.categories'),
                    'text-slate-400 hover:bg-slate-800 hover:text-slate-200' => !request()->routeIs('expenses.categories'),
                ])>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                Kategori
            </a>
        </nav>

        <!-- User & Logout Section -->
        <div class="border-t border-slate-700/50 p-3 space-y-2">
            <a href="{{ route('profile') }}" wire:navigate
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors text-slate-400 hover:bg-slate-800 hover:text-slate-200">
                <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-slate-200 truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</div>
                </div>
            </a>

            <button wire:click="logout" type="button" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-rose-500/10 hover:text-rose-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </div>
    </aside>

    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden">
    </div>
</div>
