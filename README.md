# Nanolink API - API URL Shortener 

API URL Shortener siap-produksi (*production-ready*) yang dibangun menggunakan Laravel. Project ini mendemonstrasikan pemahaman arsitektur *backend* tingkat lanjut, berfokus pada ketersediaan (*high availability*), kecepatan, dan pemrosesan asinkron (*asynchronous processing*).

Tidak seperti aplikasi CRUD pada umumnya, API ini menangani *redirect* URL hanya dalam hitungan milidetik berkat implementasi **Redis Caching**, dan memindahkan beban penulisan *database* yang berat (seperti pelacakan analitik) ke **Background Jobs**. Hasilnya? *User* tidak merasakan *loading* sama sekali!

## Fitur Utama

* **Tanpa Login tapi Aman:** Publik bisa menggunakan API ini tanpa perlu autentikasi, namun dilindungi ketat oleh *Rate Limiting* (Throttle) untuk mencegah serangan *spam* atau *bot*.
* **Redirect Secepat Kilat:** Disokong penuh oleh Redis Cache. Sistem akan melakukan *bypass* (melewati) *database* MySQL sepenuhnya saat proses *redirect* pengguna ke *link* tujuan.
* **Analitik Asynchronous:** Pencatatan riwayat klik (IP Address, User Agent, dan Waktu) diproses menggunakan Laravel Queues di latar belakang (*background job*).
* **Statistik Canggih:** Endpoint analitik dibangun menggunakan *query* Eloquent yang sangat dioptimasi (*Eager Loading* dengan pembatasan/ *constraints* khusus).

## 🛠️ Tech Stack

* **Framework:** Laravel 13 (PHP 8.3+)
* **Database:** MySQL
* **Cache & Queue Engine:** Redis
* **Environment:** Laravel Sail (Docker)


## Cara Menjalankan Project (Lokal)

**1. Clone Repository:**
```bash
git clone https://github.com/kkhff/Nanolink-API.git
cd LaporHub-API
```

**2. Setup Environment**
```bash
cp .env.example .env
```

**3. Install Dependencies:** Jika kamu memiliki PHP dan Composer lokal:
```bash
composer install
```
Jika kamu **hanya ingin menggunakan** Docker (Tanpa install PHP di lokal):
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

**4. Jalankan Docker Sail**
```bash
./vendor/bin/sail up -d
```

**5. Generate Key & Migrate**
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

**6. Langkah Krusial:** Nyalakan `queue work` agar server bisa mengeksekusi tugas pencatatan analitik di latar belakang!  
```bash
./vendor/bin/sail artisan queue:work
```

