# HP Business

Sistem manajemen bisnis HP second berbasis PHP Native dan MySQL.

## Struktur proyek

- `app/config` — konfigurasi koneksi database.
- `app/controllers` — menerima request dan menyiapkan data untuk halaman.
- `app/models` — query serta logika pengambilan data MySQL.
- `app/views` — template tampilan dashboard dan layout umum.
- `app/helpers` — fungsi kecil yang digunakan bersama, seperti format Rupiah dan escape HTML.
- `database/schema.sql` — struktur serta data awal database.
- `public` — folder yang diakses browser; berisi entry point, CSS, JavaScript, aset gambar, dan unggahan unit.
- `routes/web.php` — daftar URL aplikasi dan controller yang menanganinya.
- `storage/logs` — tempat pencatatan error atau aktivitas aplikasi pada tahap berikutnya.

## Menjalankan aplikasi

1. Nyalakan Apache dan MySQL di XAMPP.
2. Import `database/schema.sql` lewat phpMyAdmin jika database belum ada.
3. Buka `http://localhost/hp-business/public/`.

Dashboard mengambil metrik secara langsung dari tabel `customers`, `product_units`, `purchases`, `sales`, dan `sale_items`.

## Fitur yang sudah berjalan

- Login satu pengguna dengan email dan password (tanpa pembatasan role di aplikasi).
- CRUD tambah data produk, supplier, dan pelanggan.
- Pembelian HP: otomatis membuat unit ber-IMEI, pembelian, detail pembelian, dan pergerakan stok masuk dalam satu transaksi database.
- Penjualan HP: hanya dapat memilih unit tersedia, otomatis menghitung profit, mengubah status unit menjadi `sold`, dan mencatat stok keluar.
- Inventory dengan pencarian IMEI/model serta laporan penjualan dasar.

### Akun pengembangan

Gunakan email `admin@hp-business.local` dan kata sandi `admin123` untuk masuk. Ganti kata sandi ini sebelum aplikasi dipakai dengan data bisnis nyata.

Google Sign-In memerlukan Client ID OAuth, Client Secret, dan redirect URI dari Google Cloud; fitur itu belum diaktifkan agar aplikasi lokal tetap dapat langsung dipakai tanpa layanan pihak ketiga.
