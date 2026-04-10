# Aplikasi Patungan - Dokumentasi Teknis

## 📋 Deskripsi Sistem

Aplikasi Patungan adalah sistem manajemen biaya bersama yang dibangun menggunakan **CodeIgniter 3** dengan arsitektur **Model-View-Controller (MVC)** yang ketat. Sistem ini menyimpan semua data pengguna dalam format **JSON** (satu file per user) tanpa menggunakan database relasional, dan menggunakan **session** sebagai mekanisme pengelolaan autentikasi pengguna.

## 🏗️ Struktur Proyek

```
Patungan/
├── application/
│   ├── config/
│   │   ├── autoload.php          (Konfigurasi autoload library & helper)
│   │   ├── config.php            (Konfigurasi umum aplikasi)
│   │   ├── routes.php            (Konfigurasi routing URL)
│   │   └── ...
│   ├── controllers/
│   │   ├── Auth.php              (Controller autentikasi)
│   │   ├── Dashboard.php         (Controller dashboard)
│   │   └── Patungan_ctrl.php     (Controller CRUD patungan)
│   ├── models/
│   │   ├── UserData.php          (Model manajemen user & file JSON)
│   │   └── Patungan.php          (Model logika bisnis patungan)
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── header.php        (Header & styling CSS umum)
│   │   │   └── footer.php        (Footer halaman)
│   │   ├── auth/
│   │   │   ├── login.php         (Halaman login)
│   │   │   └── register.php      (Halaman register)
│   │   └── dashboard/
│   │       ├── index.php         (Dashboard utama)
│   │       ├── create_patungan.php (Form membuat patungan)
│   │       ├── edit_patungan.php (Form edit patungan)
│   │       └── detail_patungan.php (Detail & status pembayaran)
│   ├── data/                     (Folder penyimpanan file JSON user)
│   │   ├── users_index.json      (Index semua user)
│   │   ├── user_1.json           (Data user ID 1)
│   │   ├── user_2.json           (Data user ID 2)
│   │   └── ...
│   └── ...
├── system/                       (Core framework CodeIgniter)
├── index.php                     (Entry point aplikasi)
├── composer.json
└── vendor/                       (Dependencies Composer)
```

## 🔐 Alur Autentikasi

### Register (Pendaftaran User)
1. User mengakses halaman `/auth/register`
2. User memasukkan username dan password
3. Sistem memvalidasi input:
   - Username tidak boleh kosong
   - Password minimal 6 karakter
   - Password harus cocok dengan konfirmasi password
4. Sistem mengecek apakah username sudah terdaftar melalui `UserData::getUserByUsername()`
5. Jika belum terdaftar:
   - Generate `user_id` unik (auto-increment)
   - Hash password menggunakan `password_hash()` dengan algoritma BCRYPT
   - Buat file `user_{user_id}.json` dengan struktur:
     ```json
     {
       "user_id": 1,
       "username": "hildan",
       "password": "$2y$10$...",
       "patungan": []
     }
     ```
   - Update index `users_index.json`
   - User langsung masuk session dan redirect ke dashboard

### Login (Masuk Sistem)
1. User mengakses halaman `/auth/login`
2. User memasukkan username dan password
3. Sistem memindai `users_index.json` untuk menemukan user
4. Membaca file `user_{user_id}.json` lengkap
5. Memverifikasi password menggunakan `password_verify()`
6. Jika valid:
   - Set session:
     - `session['user_id']` = ID pengguna
     - `session['username']` = username
   - Redirect ke `/dashboard`
7. Jika tidak valid, tampilkan pesan error

### Logout (Keluar)
1. User klik tombol "Logout"
2. Sistem menghapus session dengan `session->sess_destroy()`
3. Redirect ke halaman login

## 💰 Alur Manajemen Patungan

### Dashboard (Tampilan Daftar Patungan)
1. User login dan masuk ke `/dashboard`
2. Controller cek session terlebih dahulu
3. Sistem membaca file `user_{user_id}.json` milik user yang login
4. Tampilkan semua patungan dalam array `patungan[]`
5. Untuk setiap patungan, tampilkan:
   - Nama grup
   - Total biaya
   - Biaya per orang
   - Jumlah anggota
   - Tanggal dibuat
   - Tombol: Lihat, Edit, Hapus

### Membuat Patungan Baru
1. User klik "+ Buat Patungan" dari dashboard
2. Masuk form di `/patungan_ctrl/create` dengan field:
   - Nama Grup (text)
   - Total Biaya (number)
   - Daftar Anggota (textarea - satu nama per baris)
3. Controller validasi:
   - Nama grup tidak boleh kosong
   - Daftar anggota minimal 1, tidak boleh kosong
   - Total biaya harus > 0
4. Sistem proses:
   - Hitung jumlah anggota
   - Hitung biaya per orang: `total_biaya / jumlah_anggota`
   - Bulatkan biaya per orang hingga 2 desimal
   - Generate ID patungan (auto-increment dalam file user)
   - Buat anggota dengan status awal "belum bayar"
   - Struktur data:
     ```json
     {
       "id": 1,
       "nama_grup": "Makan Bersama",
       "total_biaya": 225000,
       "biaya_per_orang": 45000,
       "anggota": [
         {"nama": "Hildan", "status": "belum bayar"},
         {"nama": "Aji", "status": "belum bayar"},
         ...
       ],
       "created_at": "2026-04-10 14:30:00",
       "updated_at": "2026-04-10 14:30:00"
     }
     ```
5. Tambahkan patungan ke array `patungan[]` dalam file user
6. Simpan dengan atomic write
7. Redirect ke dashboard

### Melihat Detail Patungan
1. User klik "Lihat" pada salah satu patungan
2. Masuk halaman `/patungan_ctrl/detail/{id}`
3. Tampilkan detail lengkap:
   - Nama grup dan informasi biaya
   - Tabel anggota dengan nama, status pembayaran, dan tombol aksi
4. User dapat:
   - Tandai anggota sudah bayar / belum bayar
   - Edit patungan
   - Hapus patungan

### Update Status Pembayaran Anggota
1. Di halaman detail patungan, user klik tombol "Tandai Sudah Bayar" atau "Reset ke Belum Bayar"
2. Form submit ke `/patungan_ctrl/updateStatus` dengan data:
   - `patungan_id`
   - `anggota_index`
   - `status` (baru)
3. Controller:
   - Cek session
   - Baca file `user_{user_id}.json`
   - Cari patungan berdasarkan ID
   - Update status anggota pada index yang dipilih
   - Simpan dengan atomic write
   - Return response (JSON jika AJAX, atau redirect jika form biasa)

### Edit Patungan
1. User klik "Edit" pada patungan
2. Masuk form di `/patungan_ctrl/edit/{id}`
3. Form pre-filled dengan data patungan sebelumnya
4. User ubah nama grup, total biaya, atau anggota
5. Submit form
6. Controller validasi (sama seperti create)
7. Update struktur anggota (reset status ke "belum bayar")
8. Simpan dengan atomic write
9. Redirect ke dashboard

### Hapus Patungan
1. User klik "Hapus" pada patungan
2. Pop-up konfirmasi muncul
3. Jika confirm:
   - Controller panggil `Patungan::delete()`
   - Baca file `user_{user_id}.json`
   - Cari dan hapus patungan berdasarkan ID
   - Re-index array `patungan[]`
   - Simpan dengan atomic write
   - Redirect ke dashboard

## 🔒 Keamanan & Teknik Penyimpanan

### Password Hashing
- Menggunakan `password_hash()` dengan algoritma BCRYPT
- Cost factor default = 10
- Format: `$2y$10$...`

### Session Management
- Session disimpan di folder `system/session/ci_session/`
- Session ID di-set di cookie dengan nama `ci_session`
- Setiap akses halaman wajib check session terlebih dahulu melalui `checkAuth()`

### File Locking & Atomic Write
- Semua operasi write ke file JSON dilakukan dengan teknik atomic write:
  1. Write ke file sementara: `{filepath}.tmp`
  2. Rename file sementara ke file asli (operasi atomic)
  3. Jika gagal, hapus file sementara
- Tujuan: mencegah race condition dan kerusakan data

### Data Isolation
- Data setiap user terisolasi dalam file JSON terpisah
- User hanya bisa akses data miliknya sendiri melalui pengecekan session
- User ID dari session dijadikan basis untuk membaca file JSON yang benar

## 📝 Model & Controller Details

### UserData Model
**Methods:**
- `register($username, $password)` - Register user baru
- `login($username, $password)` - Validasi login
- `getUserByUsername($username)` - Cari user berdasarkan username
- `getUserData($user_id)` - Baca file user_{user_id}.json
- `saveUserData($user_id, $data)` - Simpan perubahan ke file user

### Patungan Model
**Methods:**
- `create($user_id, $nama_grup, $total_biaya, $anggota_names)` - Buat patungan baru
- `getAllByUser($user_id)` - Ambil semua patungan user
- `getById($user_id, $patungan_id)` - Ambil patungan spesifik
- `update($user_id, $patungan_id, $nama_grup, $total_biaya, $anggota_names)` - Update patungan
- `updateAnggotaStatus($user_id, $patungan_id, $anggota_index, $status)` - Ubah status pembayaran anggota
- `delete($user_id, $patungan_id)` - Hapus patungan

### Auth Controller
**Methods:**
- `login()` - GET: tampilkan form login | POST: proses login
- `register()` - GET: tampilkan form register | POST: proses register
- `logout()` - Hapus session dan redirect ke login

### Dashboard Controller
**Methods:**
- `index()` - Tampilkan dashboard dengan daftar semua patungan user

### Patungan_ctrl Controller
**Methods:**
- `create()` - GET: tampilkan form | POST: buat patungan baru
- `edit($patungan_id)` - GET: tampilkan form | POST: update patungan
- `detail($patungan_id)` - Tampilkan detail patungan dan status pembayaran
- `updateStatus()` - Update status pembayaran anggota
- `delete($patungan_id)` - Hapus patungan

## 🚀 Cara Menjalankan

### 1. Setup Awal
```bash
cd c:\Users\j\Downloads\Patungan
php composer install  # atau gunakan composer dari Laragon
```

### 2. Jalankan dengan Laragon
```bash
# Buka Laragon
# Klik "Start All"
# Akses aplikasi di: http://patungan.test atau http://localhost/Patungan
```

### 3. Akses Pertama
- Aplikasi akan redirect ke halaman login `/auth/login`
- Jika belum punya akun, klik "Daftar di sini" untuk register
- Isi username dan password, klik "Daftar"
- Sistem akan otomatis login dan redirect ke dashboard

## 📊 Struktur Data JSON

### users_index.json
```json
[
  {
    "user_id": 1,
    "username": "hildan",
    "created_at": "2026-04-10 10:00:00"
  },
  {
    "user_id": 2,
    "username": "aji",
    "created_at": "2026-04-10 11:30:00"
  }
]
```

### user_1.json
```json
{
  "user_id": 1,
  "username": "hildan",
  "password": "$2y$10$...",
  "patungan": [
    {
      "id": 1,
      "nama_grup": "Lunch Bersama",
      "total_biaya": 300000,
      "biaya_per_orang": 75000,
      "anggota": [
        {"nama": "Hildan", "status": "sudah bayar"},
        {"nama": "Aji", "status": "belum bayar"},
        {"nama": "Budi", "status": "sudah bayar"},
        {"nama": "Citra", "status": "belum bayar"}
      ],
      "created_at": "2026-04-10 12:00:00",
      "updated_at": "2026-04-10 14:30:00"
    }
  ]
}
```

## ✨ Fitur Utama

✅ Registrasi & Login dengan password hashing
✅ Dashboard menampilkan semua patungan user
✅ Buat patungan baru dengan perhitungan otomatis biaya per orang
✅ Edit patungan (ubah nama, biaya, anggota)
✅ Hapus patungan
✅ Lihat detail patungan dengan daftar anggota
✅ Tandai/update status pembayaran anggota
✅ Data terisolasi per user
✅ Session management untuk autentikasi
✅ Atomic write untuk mencegah kerusakan data
✅ Responsive CSS styling
✅ Session check di setiap controller method

## 🛠️ Teknologi

- **Framework:** CodeIgniter 3
- **Database:** JSON Files (tanpa database relasional)
- **Autentikasi:** Session + Password Hashing
- **Bahasa:** PHP 7.4+
- **Frontend:** HTML5 + CSS3
- **Server:** Apache (via Laragon)

## 📝 Catatan Pengembang

1. Semua file user disimpan di `application/data/` dengan format `user_{user_id}.json`
2. Index semua user disimpan di `application/data/users_index.json`
3. Session check menggunakan kondisi: `if (!$this->session->userdata('user_id')) redirect('auth/login')`
4. Atomic write dilakukan dengan menulis ke file `.tmp` lalu rename
5. Response error/success ditampilkan via flash message atau alert di view
6. Semua input sudah di-sanitasi dengan `htmlspecialchars()` untuk mencegah XSS

---

**Dibuat:** 10 April 2026
**Versi:** 1.0
**Status:** Development Ready
