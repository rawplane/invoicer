Kamu adalah senior Laravel engineer dengan 10+ tahun pengalaman membangun aplikasi SaaS multi-tenant. Saya ingin kamu membantu saya membangun project "Invoice & Expense Tracker" untuk UMKM (usaha kecil menengah) di Indonesia.

## KONTEKS PROJECT
- Tujuan: SaaS sederhana untuk UMKM mencatat invoice (tagihan ke pelanggan) dan expense (pengeluaran bisnis)
- Target user: pemilik usaha kecil, non-teknis, butuh UI yang simple dan cepat dipakai
- Model: multi-tenant single-user-per-business (setiap akun user = satu bisnis, TIDAK ada tim/multi-user per bisnis di versi awal)
- Tahap: MVP untuk validasi pasar, jadi prioritaskan kesederhanaan dan kecepatan development di atas fitur canggih

## TECH STACK WAJIB
- Laravel 13 (struktur baru tanpa Kernel.php)
- PHP 8.3+
- Livewire 3 + Alpine.js untuk interaktivitas (JANGAN pakai Inertia/React/Vue kecuali saya minta)
- Tailwind CSS untuk styling
- SQLite sebagai database default
- Laravel Breeze untuk starter auth (bukan Jetstream)
- Pest PHP untuk testing
- barryvdh/laravel-dompdf untuk export PDF

## FITUR MVP (WAJIB ADA)
1. Auth: register (dengan field business_name), login, logout, forgot password
2. CRUD Client/Customer (nama, email, telepon, alamat, nama perusahaan)
3. CRUD Invoice dengan:
   - Multiple line items (dynamic, bisa tambah/hapus baris)
   - Auto-generate nomor invoice format INV-YYYY-0001 (increment per user per tahun)
   - Status: draft, sent, paid, overdue, cancelled
   - Auto kalkulasi subtotal, pajak, diskon, total
   - Export ke PDF
4. CRUD Expense dengan kategori dan upload bukti/nota
5. Dashboard: total pemasukan bulan ini, total pengeluaran bulan ini, invoice belum dibayar, grafik sederhana

## ATURAN KEAMANAN KRITIS
- Ini adalah aplikasi multi-tenant. SETIAP query ke tabel clients, invoices, invoice_items, expenses, expense_categories, payments WAJIB difilter berdasarkan user_id yang sedang login.
- Gunakan Eloquent Global Scope atau Policy untuk mencegah satu user mengakses data user lain.
- Setiap kali kamu membuat Controller/Livewire component yang mengambil data berdasarkan ID dari request (misal route model binding), WAJIB verifikasi bahwa record tersebut milik user yang sedang login sebelum ditampilkan/diedit/dihapus.
- Simpan semua nilai uang sebagai decimal(15,2), JANGAN PERNAH pakai float/double.
- Validasi semua input, terutama di form invoice items (quantity, unit_price harus numeric dan >= 0).

## CARA KERJA YANG SAYA MAU
Kerjakan bertahap, JANGAN generate semua sekaligus. Ikuti urutan ini:

FASE 1: Setup project awal
- Buat struktur project Laravel + install semua package di atas
- Setup .env.example dengan konfigurasi yang diperlukan
- Jelaskan command yang perlu saya jalankan manual (composer install, npm install, dll)

FASE 2: Database
- Buat semua migration untuk tabel: users (tambah kolom business_name, business_logo, phone, address, subscription_plan, trial_ends_at), clients, invoices, invoice_items, expenses, expense_categories, payments
- Buat semua Eloquent Model dengan relasi yang benar, $fillable, dan casts yang sesuai (terutama untuk kolom decimal dan date)
- Buat Model Factory untuk setiap model (untuk keperluan testing dan seeding)
- Tunjukkan skema lengkap sebelum lanjut ke fase berikutnya

FASE 3: Autentikasi
- Setup/customize Laravel Breeze dengan Livewire
- Tambah field business_name ke form register
- Buat trial period logic (14 hari default) di kolom trial_ends_at saat register

FASE 4: Modul Client
- Livewire component untuk list (dengan search + pagination), create, edit, delete client
- Gunakan Livewire form validation

FASE 5: Modul Invoice (paling kompleks, kerjakan hati-hati)
- Livewire component untuk create/edit invoice dengan dynamic line items (tambah/hapus baris item secara real-time)
- Auto-kalkulasi subtotal, tax, discount, total secara reaktif saat user mengetik
- Logic generate invoice_number otomatis
- Fitur ubah status invoice
- Export PDF menggunakan dompdf dengan template yang rapi

FASE 6: Modul Expense
- CRUD expense dengan kategori
- Upload file bukti/nota (simpan ke storage/app/public)
- Filter by kategori dan rentang tanggal

FASE 7: Dashboard
- Widget ringkasan (total income, total expense, unpaid invoices)
- Grafik sederhana pakai Chart.js (income vs expense per bulan, 6 bulan terakhir)

FASE 8: Testing
- Buat Pest test untuk memverifikasi isolasi data antar tenant (user A tidak bisa akses data user B) — ini WAJIB dan prioritas tertinggi
- Test kalkulasi invoice benar
- Test invoice number tidak duplikat

Untuk SETIAP fase:
- Tunjukkan semua kode lengkap (jangan dipotong dengan "... rest of code")
- Jelaskan keputusan desain penting yang kamu ambil
- Setelah selesai satu fase, tanya saya apakah mau lanjut ke fase berikutnya atau ada yang mau direvisi dulu

Mulai dari FASE 1 sekarang.
