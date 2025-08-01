# 🏷️ Absensi Polda Sulut

Website Kepolisian Daerah (POLDA), Sulawesi Utara. Dirancang khusus untuk sistem informasi karyawan dan absensi.

## ✨ Fitur

-   🧑‍💼 Multi-role Login (Admin, Pengguna)
-   👤 Manajemen Pengguna (CRUD User)
-   🧑🏻‍💼 Manajemen Pegawai (CRUD Employee)
-   🤷🏻‍♂️ Manajemen Absensi (CRUD Attendance)
-   📜 Cek Riwayat Absensi
-   📊 Dashboard Admin dan Statistik

## ⚙️ Teknologi

-   Laravel 12
-   PHP 8.3
-   Tailwind CSS
-   Alpine.js
-   MySQL
-   Bootstrap Icon

## 🛠️ Instalasi & Setup

1. Clone repository:

    ```bash
    git clone https://github.com/theowongkar/absensi-polda-sulut.git
    cd absensi-polda-sulut
    ```

2. Install dependency:

    ```bash
    composer install
    npm install && npm run build
    ```

3. Salin file `.env`:

    ```bash
    cp .env.example .env
    ```

4. Atur konfigurasi `.env` (database, mail, dsb)

5. Generate key dan migrasi database:

    ```bash
    php artisan key:generate
    php artisan storage:link
    php artisan migrate:fresh --seed
    ```

6. Jalankan server lokal:

    ```bash
    php artisan serve
    ```

7. Buka browser dan akses http://127.0.0.1:8000

## 👥 Role & Akses

| Role     | Akses                                |
| -------- | ------------------------------------ |
| Admin    | CRUD data user, employee, attendance |
| Pengguna | CRU data user, attendance            |

## 📎 Catatan Tambahan

-   Pastikan folder `storage` dan `bootstrap/cache` writable.
-   Dapat dikembangkan lebih lanjut untuk integrasi API unit tracking (GPS, IoT, dsb)
