# Sistem Dashboard Monitoring & Pelaporan Sekolah (Web Admin)

Aplikasi web berbasis **Laravel** yang berfungsi sebagai panel admin (Dashboard) untuk memantau laporan siswa dan merespons sinyal darurat (*Panic Button*).

Aplikasi ini dirancang sebagai **API Consumer (Client-Side Application)**, dimana data tidak disimpan di database lokal aplikasi ini, melainkan diambil secara *real-time* dari server API eksternal menggunakan AJAX/Fetch API.

## 🌟 Fitur Unggulan

### 1. 📊 Dashboard Statistik
Visualisasi data sekolah secara real-time untuk membantu pengambilan keputusan.
* **Statistik Angka:** Menampilkan total laporan, kasus diproses, selesai, dan ditolak.
* **Grafik Batang (Bar Chart):** Distribusi laporan berdasarkan Kelas.
* **Grafik Donat (Doughnut Chart):** Persentase kategori laporan (Bullying Fisik, Verbal, dll).
* *Powered by: Chart.js*

### 2. 🚨 Monitoring Panic Button (Sinyal Darurat)
Fitur prioritas tinggi untuk keselamatan siswa.
* Menampilkan daftar alert masuk dari aplikasi mobile siswa.
* **Peta Interaktif:** Tombol "Lihat Lokasi" menampilkan posisi GPS siswa (Latitude/Longitude) secara akurat.
* **Integrasi Google Maps:** Tautan langsung untuk navigasi ke lokasi siswa.
* *Powered by: Leaflet JS (OpenStreetMap) - Gratis tanpa API Key.*

### 3. 📩 Manajemen Laporan Masuk
Kotak masuk pengaduan siswa.
* **Smart Filtering:** Otomatis hanya menampilkan laporan yang **Aktif** (Status: Menunggu, Sedang Diproses, Pemanggilan Orang Tua).
* **Bukti Visual:** Menampilkan foto bukti kejadian yang diambil dari server API (`attachments`).
* **Detail Kronologi:** Melihat deskripsi lengkap kejadian, lokasi, dan pelapor.

### 4. 📝 Tindak Lanjut (Response Handling)
Modul bagi Guru BK untuk menangani kasus.
* Mengubah status laporan (misal: dari "Menunggu" ke "Sedang Diproses").
* Mengirim pesan balasan kepada siswa.
* Menambahkan catatan internal sekolah.
* Menggunakan method `PUT` ke API.

### 5. 📂 Riwayat & Arsip
Bank data untuk kasus yang telah selesai.
* Memisahkan laporan yang berstatus **Selesai** atau **Ditolak** dari daftar aktif.
* Berfungsi sebagai arsip digital sekolah.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

### Backend Framework
* **[Laravel 10.x](https://laravel.com/)**: Digunakan sebagai *Frontend Controller* untuk mengatur Routing, View (Blade), dan struktur aplikasi. Tidak menggunakan database MySQL lokal untuk data utama laporan.

### Frontend & UI
* **Blade Templates**: Templating engine Laravel.
* **Bootstrap**: Framework CSS untuk tampilan responsif (menggunakan template admin *Kapella*).
* **[jQuery](https://jquery.com/)**: Digunakan untuk memanipulasi DOM dan menangani *AJAX Request*.

### Integrasi API & Data
* **Fetch API / AJAX**: Menghubungkan dashboard dengan REST API Server (`api-hacktown.rusnandapurnama.com`).
* **Authentication**: Menggunakan Token Based Auth (`Bearer Token`) yang disimpan di `localStorage` browser.

### Libraries Pihak Ketiga
1.  **[Leaflet JS](https://leafletjs.com/)**: Menampilkan peta lokasi siswa (Panic Button) menggunakan OpenStreetMap (Solusi alternatif Google Maps API).
2.  **[Chart.js](https://www.chartjs.org/)**: Membuat visualisasi grafik yang interaktif di dashboard.
3.  **[SweetAlert2](https://sweetalert2.github.io/)**: Menampilkan notifikasi *popup* (Alert) yang modern dan interaktif.

---

## ⚙️ Persyaratan Sistem

* PHP >= 8.1
* Composer
* Koneksi Internet (Wajib, untuk mengambil data dari API & Peta)

---

## 🚀 Cara Instalasi

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/username-anda/nama-repo.git](https://github.com/username-anda/nama-repo.git)
    cd nama-repo
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**
    Salin file contoh konfigurasi:
    ```bash
    cp .env.example .env
    ```
    Generate Application Key:
    ```bash
    php artisan key:generate
    ```

4.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

5.  **Akses Aplikasi**
    Buka browser dan kunjungi `http://localhost:8000`.

---

## 📂 Struktur Endpoint API

Aplikasi ini bergantung pada endpoint eksternal berikut untuk operasionalnya:

| Method | Endpoint | Fungsi |
| :--- | :--- | :--- |
| `GET` | `/laporans` | Mengambil semua data laporan & riwayat. |
| `GET` | `/alert` | Mengambil data lokasi Panic Button. |
| `GET` | `/status` | Mengambil daftar referensi status laporan. |
| `PUT` | `/laporan` | Mengupdate status dan respons laporan. |
| `GET` | `/sekolah` | Mengambil profil sekolah yang sedang login. |
| `PUT` | `/sekolah` | Mengupdate data profil sekolah. |

---

## 🛡️ Catatan Keamanan & Akses

* **Token Management:** Sistem otomatis mengecek keberadaan `user_token` di local storage. Jika token tidak ada atau kadaluwarsa (401), pengguna akan diarahkan paksa ke halaman login.
* **School Filtering:** Data laporan difilter berdasarkan `sekolah_id` untuk memastikan admin hanya melihat data milik sekolahnya sendiri.

## 🤝 Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan fork repository ini dan buat Pull Request.

---

**Dibuat untuk keperluan Hackathon / Tugas Akhir.**
