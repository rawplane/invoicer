# 🧾 Invoice & Expense Tracker (MSME SaaS)

A simple SaaS platform built with **Laravel 13** and **Livewire 3** designed for Micro, Small, and Medium Enterprises (MSMEs) to manage customer invoicing (Invoices) and business operational expenses (Expenses).

---

## 🚀 Tech Stack

* **Framework**: Laravel 13 (PHP 8.3+)
* **Frontend / Interactivity**: Livewire 3 + Alpine.js + Tailwind CSS
* **Starter Auth**: Laravel Breeze (Livewire)
* **Database**: SQLite (can be customized to MySQL/PostgreSQL)
* **PDF Generator**: `barryvdh/laravel-dompdf` (DomPDF)
* **Testing Framework**: Pest PHP

---

## ✨ Key Features (MVP)

1. **Multi-Tenant Data Isolation (Single-User Per Business)**
   * Each registered account manages its own business in isolation.
   * Access control & data queries are automatically isolated based on `user_id`.

2. **Authentication & Trial Period**
   * Registration includes **Business / Company Name**.
   * Automatically grants a **14-Day Free Trial** upon registration.

3. **Customer Management Module (Clients)**
   * Client CRUD (Name, Company, Email, Phone, Address).
   * Real-time search & pagination.

4. **Interactive Invoice Module & Dynamic Items**
   * Auto-generates unique invoice numbers formatted as `INV-YYYY-0001` per tenant per year.
   * Dynamic Line Items (add/remove item rows in real-time).
   * Automatic reactive calculations for subtotal, tax, discount, and total payment.
   * Invoice status management: `Draft`, `Sent`, `Paid`, `Overdue`, `Cancelled`.
   * Export & Download PDF Invoices with a professional layout.

5. **Expense Tracker Module**
   * Business Expense CRUD with dynamic categories.
   * Receipt/transaction proof upload & preview (stored in `storage`).
   * Filter transactions by category and date range.

6. **Summary Dashboard & Interactive Charts**
   * Financial Summary: Total Monthly Income, Total Monthly Expenses, and Unpaid/Pending Invoices.
   * **Chart.js** visual charts (Income vs Expenses for the last 6 months).
   * List of recent invoice transactions.

---

## 🛠️ Installation & Setup Guide

### **1. Clone Repository & Install Dependencies**
```bash
git clone <repository-url>
cd invoice-tracker

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### **2. Environment Setup (`.env`)**
Copy `.env.example` to `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Ensure SQLite database configuration in `.env` matches:
```env
DB_CONNECTION=sqlite
```

### **3. Database Migration & Storage Link**
Run database migrations, initial seeders, and storage symlink:
```bash
# Migration & Seeder (database.sqlite file will be created automatically if it doesn't exist)
php artisan migrate --seed

# Symlink public storage for receipt attachments
php artisan storage:link
```

### **4. Build Frontend Assets & Run Server**
```bash
# Build production assets
npm run build

# Run Laravel development server
php artisan serve
```

Access the application via browser at: **`http://127.0.0.1:8000`**

#### 🔑 Sample Account (Seeded Data):
* **Email**: `owner@umkm.id`
* **Password**: `password`

---

## 🧪 Testing

This project includes feature testing & multi-tenant data isolation security checks using **Pest PHP**.

Run test suite:
```bash
php artisan test
```

Or run specific tenant isolation tests:
```bash
php artisan test --filter=TenantIsolationTest
```

---

## 📄 License
This project is licensed under the [MIT License](LICENSE).

