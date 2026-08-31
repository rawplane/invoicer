<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Invoicer') }} — Masuk</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        <div class="min-h-screen flex">
            <!-- Left Panel: Branding (hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-900 text-white p-12 flex-col justify-between relative overflow-hidden">
                <!-- Decorative blobs -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl"></div>

                <div class="relative">
                    <a href="/" class="flex items-center gap-2.5">
                        <x-application-logo class="block h-10 w-10" />
                        <span class="text-2xl font-bold tracking-tight">Invoicer</span>
                    </a>
                </div>

                <div class="relative">
                    <h2 class="text-3xl font-bold leading-tight">Kelola Keuangan<br>Bisnis UMKM Anda<br>dengan Mudah.</h2>
                    <p class="mt-4 text-slate-300 max-w-md leading-relaxed">
                        Buat invoice profesional, lacak pengeluaran, dan pantau kesehatan finansial bisnis Anda — semua dalam satu dashboard yang rapi dan modern.
                    </p>

                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Invoice PDF profesional dengan dynamic items
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Tracking pengeluaran & kategori operasional
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Dashboard grafik & data terisolasi aman
                        </div>
                    </div>
                </div>

                <div class="relative text-sm text-slate-400">
                    &copy; {{ date('Y') }} Invoicer. Dibuat untuk UMKM Indonesia.
                </div>
            </div>

            <!-- Right Panel: Form -->
            <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-8">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8">
                    <a href="/" class="flex items-center gap-2.5">
                        <x-application-logo class="block h-10 w-10" />
                        <span class="text-2xl font-bold text-slate-900 tracking-tight">Invoicer</span>
                    </a>
                </div>

                <!-- Form Content -->
                <div class="w-full max-w-sm mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
