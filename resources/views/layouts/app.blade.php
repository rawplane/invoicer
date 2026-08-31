<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Invoicer — Kelola Invoice & Pengeluaran UMKM Anda dengan mudah.">

        <title>{{ config('app.name', 'Invoicer') }}@yield('title_suffix')</title>

        <!-- Fonts: Inter for modern SaaS feel -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        <div class="min-h-screen flex">
            <!-- Sidebar Navigation -->
            <livewire:layout.navigation />

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
                <!-- Top Bar -->
                <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-sm border-b border-slate-200">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        <!-- Mobile menu button + Logo -->
                        <div class="flex items-center gap-3 lg:hidden">
                            <button @click="$dispatch('toggle-sidebar')" type="button" class="p-2 rounded-md text-slate-500 hover:bg-slate-100 hover:text-slate-600 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div class="flex items-center gap-2">
                                <x-application-logo class="block h-8 w-8" />
                                <span class="text-base font-bold text-slate-900 tracking-tight">Invoicer</span>
                            </div>
                        </div>

                        <!-- Page Title (optional header slot) -->
                        <div class="flex-1 lg:flex hidden">
                            @if (isset($header))
                                {{ $header }}
                            @endif
                        </div>

                        <!-- Right Side: Business badge & user menu -->
                        <div class="flex items-center gap-3">
                            @if(auth()->user())
                                <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Trial Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    @if (isset($header))
                        <div class="lg:hidden bg-white border-b border-slate-200 px-4 py-4 sm:px-6">
                            {{ $header }}
                        </div>
                    @endif
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
