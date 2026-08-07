# Penjelasan Module - Fleet Management System

Dokumen ini menjelaskan struktur, alur bisnis, route, controller, validasi, service, model, tabel database, relasi, dan response dari setiap module yang ada di dalam sistem manajemen armada (*Fleet Management System*).

---

## 1. Module: Autentikasi (Authentication)

*   **Tujuan Module:** 
    Mengelola akses masuk (*login*) dan keluar (*logout*) pengguna ke dalam sistem berdasarkan kredensial yang valid (email/username dan password) serta membatasi hak akses halaman berdasarkan peran (*role*) user.
*   **Alur Bisnis:** 
    1. Pengguna mengakses halaman login.
    2. Pengguna memasukkan email atau username beserta password.
    3. Sistem memverifikasi kredensial tersebut. Jika benar, sesi (*session*) dibuat dan pengguna diarahkan ke Dashboard. Jika salah, kembali ke halaman login dengan pesan kesalahan.
    4. Pengguna dapat melakukan logout untuk mengakhiri sesi.
*   **Route yang Digunakan:**
    *   `GET /login` (Name: `login`) – Menampilkan form login (hanya untuk *guest*).
    *   `POST /login` – Memproses autentikasi login (hanya untuk *guest*).
    *   `POST /logout` (Name: `logout`) – Mengakhiri sesi pengguna (harus sudah *login*).
*   **Controller yang Menangani:** 
    [AuthController](file:///c:/xampppp/htdocs/belajar-laravel/app/Http/Controllers/AuthController.php)
*   **Request Validation:**
    *   `login` (Wajib, String): Dapat berupa alamat email atau username.
    *   `password` (Wajib, String).
*   **Service yang Dipanggil:** 
    Menggunakan Laravel Autentikasi bawaan (`Illuminate\Support\Facades\Auth`).
*   **Repository atau Model yang Digunakan:** 
    [User](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/User.php)
*   **Tabel Database yang Diakses:** 
    `users`
*   **Relasi antar Tabel:** 
    Tidak ada relasi khusus yang diakses pada saat proses autentikasi (kecuali relasi bawaan user).
*   **Response yang Dikembalikan:**
    *   `RedirectResponse` ke halaman dashboard jika berhasil.
    *   `RedirectResponse` kembali ke halaman sebelumnya (`back()`) dengan menyertakan error validation jika gagal.
    *   `RedirectResponse` ke halaman login setelah logout.

---

## 2. Module: Dashboard

*   **Tujuan Module:** 
    Menyajikan ringkasan data operasional armada, statistik visual (grafik pengeluaran, status kendaraan), kalender jatuh tempo KIR & servis, serta memfasilitasi menu aksi cepat yang disesuaikan berdasarkan peran (*role*) user yang masuk (Admin, Teknisi, Driver/User, Pimpinan).
*   **Alur Bisnis:**
    1. Pengguna yang sudah masuk diarahkan ke halaman dashboard.
    2. Sistem mendeteksi *role* pengguna dan memproses data yang relevan:
        *   **Admin / Superadmin / Pimpinan:** Menampilkan daftar pengeluaran yang memerlukan persetujuan (*approval*), keluhan baru, bagan pengeluaran terboros, tren pengeluaran 6 bulan terakhir, dan status KIR.
        *   **Teknisi:** Menampilkan jumlah checklist hari ini dan keluhan yang perlu segera ditangani.
        *   **User/Driver:** Menampilkan daftar kendaraan yang dikemudikan, status kendaraan siap pakai, dan daftar keluhan yang telah dikirim olehnya.
*   **Route yang Digunakan:** 
    `GET /dashboard` (Name: `dashboard`)
*   **Controller yang Menangani:** 
    [DashboardController](file:///c:/xampppp/htdocs/belajar-laravel/app/Http/Controllers/DashboardController.php)
*   **Request Validation:** 
    Tidak ada input langsung (hanya menampilkan data berdasarkan sesi login user).
*   **Service yang Dipanggil:** 
    Query builder dan Eloquent query langsung di controller.
*   **Repository atau Model yang Digunakan:** 
    [Vehicle](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Vehicle.php), [Complaint](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Complaint.php), [DailyChecklist](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/DailyChecklist.php), [Expense](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Expense.php)
*   **Tabel Database yang Diakses:** 
    `vehicles`, `complaints`, `daily_checklists`, `expenses`
*   **Relasi antar Tabel:**
    *   `Vehicle` hasMany `Expense`
    *   `Vehicle` hasMany `DailyChecklist`
    *   `Complaint` belongsTo `Vehicle` & `User`
*   **Response yang Dikembalikan:** 
    `View` (`dashboard.blade.php`) beserta array data statistika operasional.

---

## 3. Module: Data Master Kendaraan (Vehicles)

*   **Tujuan Module:** 
    Mengelola informasi data master seluruh kendaraan operasional, seperti pelat nomor, tipe, status operasional, estimasi jadwal servis berikutnya, dan jatuh tempo KIR.
*   **Alur Bisnis:**
    1. **Admin/Superadmin** dapat menambahkan, memperbarui, dan menghapus data kendaraan beserta foto kendaraannya.
    2. **Teknisi & Admin** dapat memperbarui status operasional kendaraan secara instan (*Siap Pakai, Sedang Diservis, Selesai*).
    3. Semua pengguna yang terautentikasi dapat melihat daftar kendaraan dan detail riwayat checklist serta pengeluarannya.
*   **Route yang Digunakan:**
    *   `GET /vehicles` (Name: `vehicles.index`) – Melihat daftar kendaraan.
    *   `GET /vehicles/{vehicle}` (Name: `vehicles.show`) – Melihat detail & riwayat kendaraan.
    *   `GET /vehicles-create` (Name: `vehicles.create`) – Form tambah kendaraan (Admin/Superadmin).
    *   `POST /vehicles` (Name: `vehicles.store`) – Menyimpan data kendaraan baru (Admin/Superadmin).
    *   `GET /vehicles/{vehicle}/edit` (Name: `vehicles.edit`) – Form edit kendaraan (Admin/Superadmin).
    *   `PUT /vehicles/{vehicle}` (Name: `vehicles.update`) – Menyimpan pembaruan kendaraan (Admin/Superadmin).
    *   `DELETE /vehicles/{vehicle}` (Name: `vehicles.destroy`) – Menghapus kendaraan (Admin/Superadmin).
    *   `PUT /vehicles/{vehicle}/status` (Name: `vehicles.updateStatus`) – Memperbarui status kendaraan (Admin/Teknisi).
    *   `GET /vehicles/{vehicle}/read-notification` (Name: `vehicles.readNotification`) – Menandai notifikasi servis kendaraan telah dibaca.
*   **Controller yang Menangani:** 
    [VehicleController](file:///c:/xampppp/htdocs/belajar-laravel/app/Http/Controllers/VehicleController.php)
*   **Request Validation:**
    *   `jenis_kendaraan`, `merek`, `tipe` (Wajib, String).
    *   `tahun` (Wajib, Integer, rentang 1900 - tahun saat ini + 1).
    *   `plat_nomor` (Wajib, String, Unik di tabel `vehicles`).
    *   `odometer_awal` (Wajib, Integer, minimal 0).
    *   `foto` (Opsional, Gambar dengan format jpeg/png/jpg/gif, maks 2MB).
    *   `status` (Wajib saat update, pilihan: *Siap Pakai, Sedang Diservis, Selesai*).
*   **Service yang Dipanggil:** 
    `Storage::disk('public')` untuk penyimpanan dan penghapusan berkas foto kendaraan.
*   **Repository atau Model yang Digunakan:** 
    [Vehicle](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Vehicle.php)
*   **Tabel Database yang Diakses:** 
    `vehicles`
*   **Relasi antar Tabel:**
    *   `Vehicle` hasMany `DailyChecklist` (Relasi untuk mengambil riwayat inspeksi harian).
    *   `Vehicle` hasMany `Expense` (Relasi untuk menghitung riwayat pengeluaran dan penentuan servis terakhir).
*   **Response yang Dikembalikan:**
    *   `View` (`vehicles.index`, `vehicles.show`, `vehicles.create`, `vehicles.edit`).
    *   `RedirectResponse` kembali ke halaman indeks kendaraan dengan membawa pesan sukses.

---

## 4. Module: Pemeriksaan Harian (Daily Checklist)

*   **Tujuan Module:** 
    Mendokumentasikan inspeksi kondisi fisik dan fungsional kendaraan secara rutin (seperti oli mesin, air radiator, lampu, rem, ban, dll.) sebelum/sesudah digunakan.
*   **Alur Bisnis:**
    1. **Teknisi / Admin** melakukan pemeriksaan fisik kendaraan.
    2. Pengguna mengisi form checklist harian dengan menentukan status masing-masing komponen (`OK` atau `Not OK`) serta mencatat odometer terkini.
    3. Setelah data disimpan, odometer pada data master kendaraan otomatis diperbarui sesuai angka odometer terbaru yang dimasukkan.
*   **Route yang Digunakan:**
    *   `GET /checklist` (Name: `checklist.index`) – Melihat daftar checklist harian yang telah diisi.
    *   `GET /checklist-create` (Name: `checklist.create`) – Menampilkan formulir input checklist baru (Admin/Teknisi).
    *   `POST /checklist` (Name: `checklist.store`) – Menyimpan data checklist baru (Admin/Teknisi).
    *   `GET /checklist/{checklist}` (Name: `checklist.show`) – Melihat detail hasil inspeksi checklist.
    *   `DELETE /checklist/{checklist}` (Name: `checklist.destroy`) – Menghapus data checklist (Admin/Teknisi).
*   **Controller yang Menangani:** 
    [DailyChecklistController](file:///c:/xampppp/htdocs/belajar-laravel/app/Http/Controllers/DailyChecklistController.php)
*   **Request Validation:**
    *   `vehicle_id` (Wajib, Valid di tabel `vehicles`).
    *   `tanggal` (Wajib, format Tanggal).
    *   `nama_teknisi` (Wajib, String).
    *   `odometer` (Opsional, Integer, minimal 0).
    *   `oli_mesin`, `air_radiator`, `minyak_rem`, `ban_rem`, `lampu_klakson`, `kebersihan` (Wajib, Pilihan: `OK` atau `Not OK`).
    *   `catatan_tambahan` (Opsional, String).
*   **Service yang Dipanggil:** 
    Query langsung melalui Eloquent.
*   **Repository atau Model yang Digunakan:** 
    [DailyChecklist](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/DailyChecklist.php), [Vehicle](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Vehicle.php)
*   **Tabel Database yang Diakses:** 
    `daily_checklists`, `vehicles` (untuk sinkronisasi odometer).
*   **Relasi antar Tabel:**
    *   `DailyChecklist` belongsTo `Vehicle` (Setiap baris checklist merujuk ke satu unit kendaraan).
*   **Response yang Dikembalikan:**
    *   `View` (`checklist.index`, `checklist.create`, `checklist.show`).
    *   `RedirectResponse` ke index checklist disertai pesan sukses (`with('success', ...)`).

---

## 5. Module: Rekap Biaya & Pengeluaran (Expenses)

*   **Tujuan Module:** 
    Mencatat, melacak, dan memproses persetujuan seluruh pengeluaran keuangan operasional kendaraan (BBM, tol, parkir, pajak, perbaikan bengkel, dll.).
*   **Alur Bisnis:**
    1. **Teknisi atau Admin** mencatatkan pengeluaran baru (misal: nota bengkel, struk BBM).
    2. Sistem menetapkan status approval default yaitu `Menunggu Persetujuan`.
    3. **Admin / Pimpinan** meninjau data pengeluaran tersebut di halaman dashboard atau indeks biaya, kemudian menyetujui (`Disetujui`) atau menolak (`Ditolak`) pengeluaran tersebut.
*   **Route yang Digunakan:**
    *   `GET /expenses` (Name: `expenses.index`) – Melihat daftar biaya, filter pencarian, dan rekap bulanan.
    *   `GET /expenses-create` (Name: `expenses.create`) – Form pencatatan biaya baru (Admin/Teknisi).
    *   `POST /expenses` (Name: `expenses.store`) – Menyimpan biaya baru (Admin/Teknisi).
    *   `PUT /expenses/{expense}/approve` (Name: `expenses.approve`) – Menyetujui atau menolak biaya (Admin/Pimpinan).
    *   `DELETE /expenses/{expense}` (Name: `expenses.destroy`) – Menghapus data biaya (Admin/Teknisi).
*   **Controller yang Menangani:** 
    [ExpenseController](file:///c:/xampppp/htdocs/belajar-laravel/app/Http/Controllers/ExpenseController.php)
*   **Request Validation:**
    *   `vehicle_id` (Wajib, Valid di tabel `vehicles`).
    *   `tanggal` (Wajib, format Tanggal).
    *   `jenis_pengeluaran` (Wajib, pilihan: `BBM, Tol, Bengkel, Parkir, Pajak, Lainnya`).
    *   `jumlah_biaya` (Wajib, Angka desimal/numeric, minimal 0).
    *   `status_approval` (Hanya untuk rute approve, pilihan: `Disetujui, Ditolak`).
*   **Service yang Dipanggil:** 
    Eloquent query builder untuk filter pencarian berdasarkan pelat nomor kendaraan, jenis, dan bulan.
*   **Repository atau Model yang Digunakan:** 
    [Expense](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Expense.php), [Vehicle](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Vehicle.php)
*   **Tabel Database yang Diakses:** 
    `expenses`, `vehicles`
*   **Relasi antar Tabel:**
    *   `Expense` belongsTo `Vehicle` (Biaya dikaitkan ke unit kendaraan spesifik).
*   **Response yang Dikembalikan:**
    *   `View` (`expenses.index`, `expenses.create`, `expenses.edit`).
    *   `RedirectResponse` kembali ke index biaya disertai status keberhasilan pesan.

---

## 6. Module: Keluhan Kendaraan (Complaints)

*   **Tujuan Module:** 
    Memfasilitasi driver/user untuk melaporkan kendala fisik atau mesin pada kendaraan yang sedang dikendarai, serta memantau proses tindak lanjut perbaikan oleh teknisi.
*   **Alur Bisnis:**
    1. **Driver (User)** membuat laporan keluhan baru dengan melampirkan keterangan detail, tanggal, serta bukti visual (foto/video kerusakan). Status awal otomatis diset menjadi `Baru`.
    2. **Teknisi** melihat adanya keluhan baru dan mengubah statusnya menjadi `Diproses`. Status kendaraan yang dilaporkan otomatis berubah menjadi `Sedang Diservis`.
    3. Setelah selesai diperbaiki, Teknisi mengubah status keluhan menjadi `Selesai`, menginput persentase progress (`100%`), mengisi catatan penyelesaian, serta menuliskan estimasi biaya perbaikan.
    4. Sistem otomatis membuat rekap pengeluaran baru berjenis `Bengkel` di tabel `expenses` apabila terdapat biaya perbaikan yang diinput, dan mengubah status kendaraan kembali ke `Siap Pakai`.
*   **Route yang Digunakan:**
    *   `GET /complaints` (Name: `complaints.index`) – Melihat daftar keluhan (Driver hanya melihat keluhan milik dirinya sendiri, Admin/Teknisi melihat semua).
    *   `GET /complaints-create` (Name: `complaints.create`) – Membuka halaman pelaporan keluhan.
    *   `POST /complaints` (Name: `complaints.store`) – Menyimpan keluhan baru beserta file media.
    *   `PUT /complaints/{complaint}/status` (Name: `complaints.updateStatus`) – Memperbarui status keluhan, progress, dan biaya perbaikan (Admin/Teknisi).
*   **Controller yang Menangani:** 
    [ComplaintController](file:///c:/xampppp/htdocs/belajar-laravel/app/Http/Controllers/ComplaintController.php)
*   **Request Validation:**
    *   `vehicle_id` (Wajib, Valid di tabel `vehicles`).
    *   `tanggal` (Wajib, format Tanggal).
    *   `keluhan` (Wajib, String max 1000 karakter).
    *   `foto_kerusakan` (Opsional, Gambar maks 10MB).
    *   `video_kerusakan` (Opsional, Video format mp4/mov/avi/webm maks 50MB).
    *   `status` (Saat update status: pilihan `Baru, Diproses, Selesai`).
*   **Service yang Dipanggil:** 
    Fungsi upload file lokal (`$file->move(...)`) untuk menyimpan berkas kerusakan ke folder `public/uploads/complaints`.
*   **Repository atau Model yang Digunakan:** 
    [Complaint](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Complaint.php), [Expense](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Expense.php), [Vehicle](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/Vehicle.php)
*   **Tabel Database yang Diakses:** 
    `complaints`, `expenses` (opsional jika ada biaya), `vehicles` (untuk mengubah status kendaraan).
*   **Relasi antar Tabel:**
    *   `Complaint` belongsTo `Vehicle`
    *   `Complaint` belongsTo `User` (Sebagai pelapor keluhan)
*   **Response yang Dikembalikan:**
    *   `View` (`complaints.index`, `complaints.create`).
    *   `RedirectResponse` ke indeks keluhan dengan notifikasi sukses.

---

## 7. Module: Manajemen Pengguna & Profil (User Management & Profile)

*   **Tujuan Module:** 
    Mengelola akun pengguna sistem armada (Admin, Teknisi, Driver, Pimpinan) serta mengizinkan pengguna aktif memperbarui informasi profil mandiri (nama, password, avatar).
*   **Alur Bisnis:**
    1. **Admin / Superadmin** dapat melihat daftar pengguna sistem, menambah akun baru, mengubah data akun (*role*, username, email), atau menghapus akun (tidak bisa menghapus akun sendiri).
    2. Semua pengguna yang sudah login dapat mengakses menu edit profil untuk memperbarui nama, email, password, dan foto avatar profil mereka sendiri.
*   **Route yang Digunakan:**
    *   `GET /users` (Name: `users.index`) – Daftar pengguna (Admin/Superadmin).
    *   `GET /users-create` (Name: `users.create`) – Form tambah user baru (Admin/Superadmin).
    *   `POST /users` (Name: `users.store`) – Menyimpan user baru (Admin/Superadmin).
    *   `GET /users/{user}/edit` (Name: `users.edit`) – Form edit user (Admin/Superadmin).
    *   `PUT /users/{user}` (Name: `users.update`) – Menyimpan pembaruan user (Admin/Superadmin).
    *   `DELETE /users/{user}` (Name: `users.destroy`) – Menghapus user (Admin/Superadmin).
    *   `POST /profile/update` (Name: `profile.update`) – Memperbarui profil & avatar mandiri bagi user yang sedang login.
*   **Controller yang Menangani:** 
    [UserController](file:///c:/xampppp/htdocs/belajar-laravel/app/Http/Controllers/UserController.php)
*   **Request Validation:**
    *   `name`, `username`, `email` (Wajib, unik pada tabel `users` kecuali untuk ID saat ini).
    *   `password` (Wajib di store, opsional di update, minimal 6 karakter, didukung *confirmed* pada profil).
    *   `role` (Wajib, pilihan: `superadmin, admin, teknisi, user, pimpinan`).
    *   `avatar` (Opsional, file gambar maks 2MB).
*   **Service yang Dipanggil:** 
    *   `Hash::make(...)` untuk enkripsi password baru.
    *   Fungsi upload file lokal untuk memindahkan berkas avatar ke folder `public/uploads/avatars`.
*   **Repository atau Model yang Digunakan:** 
    [User](file:///c:/xampppp/htdocs/belajar-laravel/app/Models/User.php)
*   **Tabel Database yang Diakses:** 
    `users`
*   **Relasi antar Tabel:**
    *   `User` hasMany `Complaint` (Sejarah keluhan yang pernah dilaporkan oleh user tersebut).
*   **Response yang Dikembalikan:**
    *   `View` (`users.index`, `users.create`, `users.edit`).
    *   `RedirectResponse` ke index user atau halaman sebelumnya (`back()`) disertai status sukses.
