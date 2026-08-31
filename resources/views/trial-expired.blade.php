<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Invoicer') }} — Masa Trial Berakhir</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 p-8 text-center">
            <div class="mx-auto w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mb-5 ring-1 ring-amber-100">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div class="flex items-center justify-center gap-2 mb-4">
                <x-application-logo class="block h-8 w-8" />
                <span class="text-lg font-bold text-slate-900">Invoicer</span>
            </div>

            <h1 class="text-xl font-bold text-slate-900">Masa Trial Berakhir</h1>

            <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                Masa uji coba gratis Anda telah berakhir.
                Silakan berlangganan untuk melanjutkan mengelola invoice &amp; pengeluaran bisnis Anda.
            </p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <a href="{{ route('profile') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                    Berlangganan
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-200 transition-colors">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
