### 📖Sistem Reservasi (Simple Booking System)
<br>
Aplikasi web sistem reservasi meja restoran yang fungsional dan modern, dibangun menggunakan PHP Native, MySQL, dan JavaScript. Aplikasi ini dirancang dengan antarmuka dark mode yang elegan dan pengalaman pengguna yang interaktif, termasuk pemilihan tanggal melalui kalender.
<br>
Sistem ini memiliki dua hak akses utama: Customer yang dapat membuat dan melihat reservasi mereka, dan Admin yang dapat mengelola semua reservasi yang masuk.

### ✨Fitur Utama
#### Untuk Pelanggan (Customer)
Registrasi & Login: Sistem autentikasi pengguna yang aman dengan password hashing.
<br>
Dashboard Pribadi: Halaman utama setelah login untuk navigasi.
<br>
Booking via Kalender: Antarmuka kalender interaktif untuk memilih tanggal reservasi.
<br>
Pencegahan Double Booking: Sistem secara otomatis menolak reservasi jika slot waktu yang dipilih sudah penuh.
<br>
Riwayat Reservasi: Pelanggan dapat melihat riwayat dan status semua reservasi yang pernah dibuat (Pending, Confirmed, Canceled).
<br>
### Untuk Administrator (Admin)
Panel Admin Terproteksi: Halaman khusus yang hanya bisa diakses oleh pengguna dengan peran admin.
<br>
Manajemen Reservasi Total: Menampilkan semua reservasi dari semua pelanggan dalam satu tabel.
<br>
Ubah Status Reservasi: Admin dapat menyetujui (Confirm) atau menolak (Cancel) reservasi yang masih Pending.
<br>
### Fitur Teknis
UI Dark Mode Elegan: Desain antarmuka modern yang cocok untuk tema restoran.
<br>
Pesan Flash: Notifikasi berbasis session untuk memberikan feedback (sukses atau error) kepada pengguna tanpa menggunakan parameter URL.
<br>
Kode Terstruktur: Pemisahan antara logika PHP dan tampilan HTML untuk kemudahan pengelolaan.
<br>
Keamanan: Menggunakan Prepared Statements di semua query SQL untuk mencegah SQL Injection.
<br>
### 🛠️ Teknologi yang Digunakan
Frontend: HTML, CSS, JavaScript (Vanilla JS)
<br>
Backend: PHP (Native/Prosedural)
<br>
Database: MySQL
<br>
Font: Poppins dari Google Fonts
