<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Invoicer — Kelola Invoice & Pengeluaran UMKM Anda dengan mudah, rapi, dan profesional.">

    <title>{{ config('app.name', 'Invoicer') }} — Invoice & Pengeluaran UMKM</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-slate-800">

    <!-- Navbar -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2.5">
                    <x-application-logo class="block h-9 w-9" />
                    <span class="text-xl font-bold text-slate-900 tracking-tight">Invoicer</span>
                </a>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#fitur" class="text-sm font-medium text-slate-600 hover:text-slate-900">Fitur</a>
                    <a href="#keuntungan" class="text-sm font-medium text-slate-600 hover:text-slate-900">Keuntungan</a>
                    <a href="#harga" class="text-sm font-medium text-slate-600 hover:text-slate-900">Harga</a>
                </nav>

                <!-- CTA Buttons -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium text-slate-600 hover:text-slate-900">Masuk</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                            Mulai Gratis
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-emerald-50/30">
        <div class="absolute inset-0 bg-grid-slate-100 bg-[size:40px_40px] opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Text -->
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Free Trial 14 Hari — Tanpa Kartu Kredit
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight tracking-tight">
                        Kelola Invoice &<br>
                        <span class="text-emerald-600">Pengeluaran UMKM</span><br>
                        Lebih Rapi & Profesional
                    </h1>
                    <p class="mt-6 text-lg text-slate-600 leading-relaxed max-w-lg">
                        Buat dan kirim invoice profesional dalam hitungan detik, lacak pengeluaran operasional, dan pantau kesehatan finansial bisnis Anda — semua dalam satu dashboard.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-md">
                                Mulai Free Trial Sekarang
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                                Sudah Punya Akun? Masuk
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Right: Dashboard Mockup -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400/20 to-teal-400/20 rounded-3xl blur-3xl"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl ring-1 ring-slate-200 overflow-hidden">
                        <!-- Mock Header -->
                        <div class="flex items-center gap-1.5 px-4 py-3 bg-slate-50 border-b border-slate-100">
                            <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        </div>
                        <!-- Mock Content -->
                        <div class="p-6">
                            <div class="grid grid-cols-3 gap-3 mb-6">
                                <div class="p-3 rounded-lg bg-emerald-50 ring-1 ring-emerald-100">
                                    <div class="text-xs text-emerald-600 font-medium">Pemasukan</div>
                                    <div class="text-sm font-bold text-slate-900 mt-1">Rp 4.2M</div>
                                </div>
                                <div class="p-3 rounded-lg bg-rose-50 ring-1 ring-rose-100">
                                    <div class="text-xs text-rose-600 font-medium">Pengeluaran</div>
                                    <div class="text-sm font-bold text-slate-900 mt-1">Rp 1.8M</div>
                                </div>
                                <div class="p-3 rounded-lg bg-amber-50 ring-1 ring-amber-100">
                                    <div class="text-xs text-amber-600 font-medium">Outstanding</div>
                                    <div class="text-sm font-bold text-slate-900 mt-1">Rp 2.1M</div>
                                </div>
                            </div>
                            <!-- Mock Chart -->
                            <div class="flex items-end gap-2 h-24">
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full bg-emerald-200 rounded-t" style="height: 40%"></div>
                                    <div class="w-full bg-rose-200 rounded-b" style="height: 20%"></div>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full bg-emerald-200 rounded-t" style="height: 60%"></div>
                                    <div class="w-full bg-rose-200 rounded-b" style="height: 30%"></div>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full bg-emerald-300 rounded-t" style="height: 75%"></div>
                                    <div class="w-full bg-rose-300 rounded-b" style="height: 25%"></div>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full bg-emerald-400 rounded-t" style="height: 90%"></div>
                                    <div class="w-full bg-rose-400 rounded-b" style="height: 35%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-slate-900">Semua yang UMKM Anda Butuhkan</h2>
                <p class="mt-4 text-slate-600">Dari buat invoice hingga lacak pengeluaran, semua dalam satu tempat.</p>
            </div>

            <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="p-6 rounded-2xl bg-slate-50 ring-1 ring-slate-100 hover:shadow-lg hover:ring-emerald-200 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 5h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" /></svg>
                    </div>
                    <h3 class="font-semibold text-slate-900">Invoice Profesional</h3>
                    <p class="mt-2 text-sm text-slate-600">Buat invoice dengan nomor otomatis, dynamic items, dan export PDF instan.</p>
                </div>

                <!-- Card 2 -->
                <div class="p-6 rounded-2xl bg-slate-50 ring-1 ring-slate-100 hover:shadow-lg hover:ring-emerald-200 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <h3 class="font-semibold text-slate-900">Tracking Pengeluaran</h3>
                    <p class="mt-2 text-sm text-slate-600">Catat pengeluaran operasional dengan kategori dinamis dan upload bukti nota.</p>
                </div>

                <!-- Card 3 -->
                <div class="p-6 rounded-2xl bg-slate-50 ring-1 ring-slate-100 hover:shadow-lg hover:ring-emerald-200 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                    </div>
                    <h3 class="font-semibold text-slate-900">Dashboard Keuangan</h3>
                    <p class="mt-2 text-sm text-slate-600">Grafik visualisasi pemasukan vs pengeluaran 6 bulan terakhir & ringkasan finansial.</p>
                </div>

                <!-- Card 4 -->
                <div class="p-6 rounded-2xl bg-slate-50 ring-1 ring-slate-100 hover:shadow-lg hover:ring-emerald-200 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.948 11.948 0 0014 9.5 11.948 11.948 0 002.617 6.495 4.5 4.5 0 000 11.5V17a2 2 0 002 2h16a2 2 0 002-2v-5.5a4.5 4.5 0 00-2.382-3.984z" /></svg>
                    </div>
                    <h3 class="font-semibold text-slate-900">Data Terisolasi</h3>
                    <p class="mt-2 text-sm text-slate-600">Setiap akun bisnis terpisah aman. Data Anda hanya milik Anda, tidak dibagi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing / CTA Section -->
    <section id="harga" class="py-20 bg-slate-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 mb-6">
                💡 Mulai Gratis Hari Ini
            </div>
            <h2 class="text-3xl font-bold text-slate-900">Gratis Selama 14 Hari</h2>
            <p class="mt-4 text-slate-600 text-lg">Tidak perlu kartu kredit. Batalkan kapan saja. Semua fitur tersedia.</p>

            <div class="mt-8 inline-block">
                <div class="p-8 rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 text-left">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-slate-900">Rp 0</span>
                        <span class="text-slate-500">/ 14 hari</span>
                    </div>
                    <ul class="mt-6 space-y-3 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Invoice & Dynamic Items tak terbatas</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Export PDF Profesional</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Tracking Pengeluaran & Kategori</li>
                        <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Dashboard & Grafik Keuangan</li>
                    </ul>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" wire:navigate class="mt-6 w-full inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                            Daftar Sekarang
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <x-application-logo class="block h-8 w-8" />
                    <span class="text-lg font-bold text-white">Invoicer</span>
                </div>
                <p class="text-sm">&copy; {{ date('Y') }} Invoicer. Dibuat untuk UMKM Indonesia.</p>
            </div>
        </div>
    </footer>

</body>
</html>
