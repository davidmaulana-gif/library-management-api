# 📚 Library Management API

Backend REST API untuk sistem perpustakaan yang dibangun menggunakan **Laravel 12** dan **PostgreSQL**.

## 📖 Tentang Project

Project ini merupakan Backend REST API yang menyediakan fitur untuk mengelola data perpustakaan. API ini menggunakan Laravel Sanctum sebagai sistem autentikasi dan menerapkan Role-Based Access Control (Admin & User).

## ✨ Fitur

- 🔐 Login & Authentication menggunakan Laravel Sanctum
- 👤 Manajemen User
- 📄 Manajemen Data Diri
- 🏫 Manajemen Kelas
- 🎓 Manajemen Jurusan
- 📚 Manajemen Buku
- 🗂️ Manajemen Kategori Buku
- 📥 Manajemen Peminjaman Buku
- 📤 Manajemen Pengembalian Buku
- 🛡️ Middleware Role (Admin & User)
- ✅ Request Validation
- 🗑️ Soft Delete
- 🔍 Pencarian Data
- 📡 RESTful API

---

## 🛠️ Teknologi yang Digunakan

- PHP
- Laravel 12
- PostgreSQL
- Laravel Sanctum
- Postman
- Git
- GitHub

---

## 📂 Struktur Database

Project ini menggunakan beberapa tabel utama:

- Users
- Data Diris
- Perans
- Kelas
- Jurusans
- Kategoris
- Bukus
- Pinjams
- Kembalis

---

## 🚀 Instalasi

Clone repository

```bash
git clone https://github.com/davidmaulana-gif/library-management-api.git
```

Masuk ke folder project

```bash
cd library-management-api
```

Install dependency

```bash
composer install
```

Salin file environment

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Atur koneksi database PostgreSQL pada file `.env`.

Jalankan migration

```bash
php artisan migrate
```

Jalankan seeder

```bash
php artisan db:seed
```

Menjalankan server

```bash
php artisan serve
```

---

## 🔐 Authentication

API menggunakan Laravel Sanctum.

Login terlebih dahulu untuk mendapatkan Bearer Token.

Masukkan token pada Header:

```
Authorization: Bearer {token}
```

---

## 🧪 API Testing

Seluruh endpoint diuji menggunakan **Postman**.

---

## 👨‍💻 Developer

**Muhammad David Maulana**

Backend Developer (Laravel)
