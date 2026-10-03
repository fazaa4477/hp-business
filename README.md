# HP Business

Sistem manajemen bisnis HP second berbasis PHP Native dan MySQL. Semua data operasional dimasukkan melalui aplikasi; schema tidak berisi produk, pelanggan, atau transaksi contoh.

## Struktur proyek

- `app/config` — konfigurasi koneksi database.
- `app/controllers` — menerima request dan menyiapkan data untuk halaman.
- `app/models` — query serta logika pengambilan data MySQL.
- `app/views` — template tampilan dashboard dan layout umum.
- `app/helpers` — fungsi kecil yang digunakan bersama, seperti format Rupiah dan escape HTML.
- `database/schema.sql` — struktur database baru tanpa seed data.
- `database/migrations` — perubahan schema untuk instalasi database yang sudah berjalan.
- `public` — folder yang diakses browser; berisi entry point, CSS, JavaScript, aset gambar, dan unggahan unit.
- `routes/web.php` — daftar URL aplikasi dan controller yang menanganinya.
- `storage/logs` — tempat pencatatan error atau aktivitas aplikasi pada tahap berikutnya.

## Menjalankan aplikasi

1. Nyalakan Apache dan MySQL di XAMPP.
2. Untuk instalasi baru, import `database/schema.sql` lewat phpMyAdmin.
3. Untuk database lama, import `database/migrations/001_add_operations_finance.sql` satu kali.
4. Buka `http://localhost/hp-business/public/`.

Dashboard mengambil metrik secara langsung dari tabel `customers`, `product_units`, `purchases`, `sales`, dan `sale_items`.

## Fitur yang sudah berjalan

- Login dan autentikasi pengguna dengan enkripsi password (tanpa batasan role).
- CRUD Lengkap (Tambah, Lihat, Edit, Hapus) untuk Master Produk HP, Pelanggan, dan Supplier.
- CRUD Lengkap Pembelian HP (Kulak): Form pembelian otomatis membuat data unit ber-IMEI, detail pembelian, serta mutasi stok masuk dalam transaksi database atomik (ACID). Dilengkapi fitur edit dan pembatalan transaksi dengan proteksi jika unit sudah terjual.
- CRUD Lengkap Penjualan HP: Pilihan unit ready stock dengan kalkulasi otomatis profit/laba rugi, update status unit menjadi `sold`, mutasi stok keluar, edit transaksi penjualan, serta pembatalan yang mengembalikan stok unit menjadi `available`.
- CRUD Lengkap Servis Unit: Pencatatan biaya perbaikan unit yang otomatis menambahkan modal dasar unit HP dan tercatat pada buku kas operasional, dengan fitur edit selisih biaya serta hapus/rollback servis.
- Manajemen Kas (Cashflow), Inventory IMEI tracker, serta Laporan Penjualan komprehensif dengan ekspor Excel dan cetak PDF.

### Akun pertama

Saat database belum memiliki pengguna, halaman login berubah menjadi formulir pembuatan akun pemilik. Setelah akun dibuat, aplikasi hanya menerima login email dan kata sandi tersebut.

Google Sign-In memerlukan Client ID OAuth, Client Secret, dan redirect URI dari Google Cloud; fitur itu belum diaktifkan agar aplikasi lokal tetap dapat langsung dipakai tanpa layanan pihak ketiga.
