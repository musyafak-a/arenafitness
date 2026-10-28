# DOKUMEN PROPOSAL SIDANG KELAYAKAN JUDUL TUGAS AKHIR
**Program Studi:** D-III Manajemen Informatika (PSDKU)  
**Objek / Mitra Penelitian:** WARGYM (sebelumnya Arena Fitness)  
**Topik Bidang:** Sistem Informasi Manajemen Multiplatform & Rekayasa Perangkat Lunak Terapan

---

## 1. JUDUL DAN DESKRIPSI UTAMA

### A. Judul Tugas Akhir
> **"Rancang Bangun Platform Layanan Gym Terintegrasi (Web & Mobile) dengan Modul Asisten Latihan Mandiri (Studi Kasus: WARGYM)"**

### B. Deskripsi Modul Khusus (Core Innovation)
> **Modul Asisten Latihan Mandiri (*Self-Workout Tracker*)** adalah fitur pendamping interaktif yang dirancang untuk mengoptimalkan sesi latihan member secara mandiri di gym. Bertindak layaknya *personal trainer virtual* dan *buku catatan digital*, sistem ini menyediakan panduan visual gerakan yang komprehensif, serta memungkinkan pencatatan beban dan repetisi guna mendukung peningkatan kekuatan terukur (*progressive overload*).

---

## 2. LATAR BELAKANG PEMBUATAN SISTEM

1. **Sulitnya Menemukan Pusat Informasi Operasional Gym (Informasi Tertimbun Konten Promosi Media Sosial):**  
   Saat ini, media sosial (seperti Instagram dan TikTok) milik **WARGYM** lebih difungsikan sebagai media promosi visual, dokumentasi kegiatan, dan tayangan suasana/kondisi tempat gym. Akibatnya, member maupun calon member kesulitan menemukan ruang pusat informasi resmi yang terstruktur. Informasi penting—seperti rincian paket membership resmi, perubahan jam operasional, ketersediaan produk/suplemen, tata tertib gym, hingga status masa aktif keanggotaan—sering kali tertimbun postingan promosi. Hal ini memaksa member untuk selalu bertanya berulang kali melalui pesan langsung (*DM/chat*) ke admin atau datang langsung ke resepsionis.

2. **Sistem Presensi Manual dengan Kartu Fisik yang Rentan Hilang, Rusak, dan Tertinggal:**  
   Proses check-in di WARGYM masih mengandalkan kartu keanggotaan (*member card*) fisik. Kartu fisik memiliki kelemahan mendasar: mudah hilang, rusak, atau tertinggal di rumah saat member hendak berolahraga. Ketika kartu tertinggal, kasir harus mencari data member secara manual di catatan register, yang memicu antrean panjang di meja resepsionis pada jam sibuk (*peak hours*). Selain itu, sistem fisik ini rawan disalahgunakan (dipinjamkan ke pihak lain) serta membebani operasional dengan biaya cetak kartu berulang.

3. **Ketiadaan *Tool* Mandiri bagi Pemula untuk Mempelajari Form Gerakan Sesuai Otot Target dalam 1 Aplikasi yang Efisien:**  
   Member pemula sering mengalami kebingungan (*gym anxiety*) mengenai teknik gerakan yang benar (*proper exercise form*) dan pemilihan variasi latihan sesuai target otot yang ingin mereka bangun (*muscle hypertrophy*). Di sisi lain, menyewa jasa *Personal Trainer* (PT) berbayar membutuhkan biaya tambahan yang tidak dapat dijangkau oleh semua kalangan member. Tanpa adanya alat pendamping, member terpaksa berpindah-pindah media (mencari video tutorial di YouTube/media sosial, lalu mencatat beban di notes ponsel biasa) yang sangat tidak praktis, terfragmentasi, dan tidak efisien. Pemula membutuhkan sebuah *tool* interaktif dalam genggaman yang berfungsi sebagai panduan visual sekaligus buku catatan beban (*progressive overload*).

4. **Kebutuhan Solusi *All-in-One Platform* Terintegrasi:**  
   Berdasarkan ketiga permasalahan di atas, diperlukan sebuah **Platform Layanan Gym Terintegrasi (Web & Mobile)** sebagai solusi *All-in-One*:
   * **Bagi Member (Mobile App):** Cukup menggunakan **1 aplikasi saja** untuk mengakses pusat informasi resmi gym, menampilkan kartu presensi digital QR yang aman di ponsel, melakukan perpanjangan paket instan, sekaligus memanfaatkan **Modul Asisten Latihan Mandiri** (panduan visual form gerakan + pencatat beban/repetisi) saat berlatih di gym.
   * **Bagi Pengelola & Kasir WARGYM (Web Dashboard):** Menyediakan sistem operasional terpadu untuk validasi presensi instan, kasir POS (suplemen & tiket tamu harian), otomasi perpanjangan bertumpuk (*stacking logic*), serta pelaporan keuangan *real-time*.

---

## 3. IDENTIFIKASI PERMASALAHAN & PERMINTAAN KLIEN (WARGYM)

Secara spesifik, permasalahan operasional dan permintaan mitra WARGYM dirumuskan sebagai berikut:

1. **Permasalahan Utama (Pain Points):**
   * **Pusat Informasi Tersebar:** Media sosial tidak efektif sebagai pusat rujukan informasi operasional dan status keanggotaan.
   * **Inefisiensi Presensi:** Kartu fisik mudah hilang/tertinggal dan memperlambat verifikasi masuk member di resepsionis.
   * **Ketiadaan Pendamping Latihan Pemula:** Member pemula tidak memiliki sarana mandiri yang efisien dalam 1 aplikasi untuk belajar form gerakan dan memantau peningkatan beban latihan (*progressive overload*).
   * **Pencatatan Keuangan & Kasir Manual:** Pembayaran dan pencatatan suplemen/tiket tamu masih terpisah, serta verifikasi bukti transfer manual rawan bukti palsu.

2. **Permintaan Solusi dari Pihak WARGYM:**
   * **Aplikasi Mobile Member All-in-One:** Satu aplikasi serbaguna bagi member untuk info gym, kartu digital QR, pembayaran online (Midtrans), dan asisten latihan mandiri.
   * **Sistem Web Kasir & Manajemen Terpadu:** Kasir cepat untuk POS produk, validasi presensi QR instan, dan pencatatan kas otomatis.
   * **Sistem Perpanjangan Adil (*Smart Stacking*):** Perpanjangan paket tidak memotong sisa hari aktif member yang masih berjalan.
   * **Automated Notification:** Pengingat otomatis kedaluwarsa H-7 via WhatsApp Gateway agar member tidak terlambat memperpanjang paket.

---

## 4. SPESIFIKASI MODUL ASISTEN LATIHAN MANDIRI (*CORE FEATURE*)

Modul ini menjadi **nilai kebaruan (*novelty*)** dan pembeda utama tugas akhir ini:

### Rincian Fungsionalitas Modul Asisten Latihan Mandiri:
1. **Katalog Master Gerakan (*Exercise Library*):**  
   Menyediakan database gerakan fitness terstandarisasi yang dikelompokkan berdasarkan kelompok otot (*Chest, Back, Legs, Shoulders, Arms, Core*), dilengkapi visual instruksi dan otot primer/sekunder yang dilatih.
2. **Penyusun Program Latihan (*Custom Routines & Templates*):**  
   Member dapat membuat template jadwal latihan sendiri (misal: *Push Day, Pull Day, Leg Day*) atau menggunakan rekomendasi program latihan dari gym.
3. **Pencatat Latihan Langsung (*Live Workout Logger*):**  
   Saat berolahraga, member menginput beban (kg) dan repetisi (*reps*) per set. Sistem menyimpan catatan beban terakhir (*previous weight record*) sebagai acuan target sesi hari ini.
4. **Timer Istirahat Otomatis (*Auto Rest Timer*):**  
   Setelah mencentang set selesai, timer istirahat (misal 60, 90, atau 120 detik) langsung berjalan dengan indikator audio/getar saat waktu jeda berakhir.
5. **Analisis Riwayat & Peningkatan Kekuatan (*Strength Analytics*):**  
   Sistem menghitung total beban angkatan (*Total Volume Load = set × reps × kg*) dan mencatat rekor angkatan tertinggi (*Personal Record / PR*).

---

## 5. TECH STACK (SPESIFIKASI TEKNOLOGI)

| Lapisan / Komponen | Pilihan Teknologi | Peran & Alasan Penggunaan |
|---|---|---|
| **Backend & REST API** | **Laravel 12 (PHP 8.2+)** | Framework backend teruji dengan arsitektur MVC, Eloquent ORM, middleware otorisasi, dan scheduler task pengingat otomatis. |
| **Autentikasi API Mobile** | **Laravel Sanctum** | Autentikasi berbasis *Bearer Token* yang ringan, aman, dan efisien untuk komunikasi antara aplikasi mobile dan server. |
| **Frontend Web (Kasir/Admin)** | **Blade Engine, Vite 7, Tailwind CSS v4** | Antarmuka kasir dan administrasi web yang responsif, cepat dimuat, dengan tema *Dark Industrial Brutalist* kontras tinggi. |
| **Aplikasi Mobile (Member)** | **Flutter 3.x (Dart 3.x)** | Pengembangan aplikasi mobile multiplatform (Android & iOS) dengan performa grafis tinggi (60-120 fps) untuk antarmuka interaktif modul workout. |
| **Database** | **MySQL / MariaDB** | Database relasional ternormalisasi (3NF) dengan integritas *Foreign Key* dan *indexing* optimal. |
| **Payment Gateway** | **Midtrans Snap API & Webhook** | Pembayaran digital resmi (QRIS, GoPay, VA Bank) dengan konfirmasi status transaksi otomatis secara *real-time*. |
| **Notification Gateway** | **Evolution API / WhatsApp Gateway** | Pengiriman otomatis pengingat kedaluwarsa H-7 dan invoice digital ke WhatsApp member. |
| **Penyimpanan Lokal Mobile** | **Flutter Secure Storage / Hive** | Menyimpan sesi token login dan caching sementara data gerakan latihan. |

---

## 6. ANALISIS KEBUTUHAN SISTEM

### A. Kebutuhan Fungsional (*Functional Requirements*)

#### Modul Web Operasional (Kasir & Admin):
* **KF-01 (Autentikasi Multi-Role):** Sistem menyediakan login berbasis peran: Master Admin (Owner), Admin Gym, dan Kasir.
* **KF-02 (Manajemen Member & Kartu QR):** Admin/Kasir dapat mendaftarkan member baru, mengedit data, dan men-generate QR Code presensi unik.
* **KF-03 (Manajemen Paket Membership):** Admin dapat membuat dan mengubah paket keanggotaan (durasi bulan, harga paket, deskripsi).
* **KF-04 (Point of Sale Kasir):** Kasir dapat melayani transaksi paket member, daily pass, penjualan suplemen/minuman, serta mencetak struk.
* **KF-05 (Presensi Staf/Admin):** Kasir/Admin dapat memvalidasi presensi tamu dan member serta melihat status keaktifan paket secara instan.
* **KF-06 (Manajemen Pengeluaran):** Admin dapat menginput biaya operasional gym (*operational expenses*) untuk pembukuan laba-rugi.
* **KF-07 (Laporan & Rekapitulasi):** Sistem menyajikan rekap kasir harian, laporan pendapatan bulanan, dan ekspor data ke format Excel dan PDF.
* **KF-08 (Broadcast & Pengumuman):** Admin dapat mempublikasikan pengumuman gym dan mengirim pesan broadcast WhatsApp.

#### Modul Aplikasi Mobile (Member):
* **KF-09 (Login & Profil Member):** Member dapat login via mobile, melihat sisa masa aktif, dan mengelola profil akun.
* **KF-10 (Kartu Presensi Digital QR):** Member dapat menampilkan QR Code presensi berlayar penuh untuk dipindai saat tiba di gym.
* **KF-11 (Perpanjangan Paket Online - Midtrans):** Member dapat memilih paket dan membayar via Midtrans Snap (QRIS/VA) dengan logika masa aktif bertumpuk (*stacking*).
* **KF-12 (Katalog Gerakan & Panduan Visual):** Member dapat menelusuri database gerakan fitness beserta visual instruksi dan kelompok otot target.
* **KF-13 (Pencatat Sesi Latihan - Workout Logger):** Member dapat mencatat set, beban angkatan (kg), repetisi, dan mengaktifkan rest timer otomatis.
* **KF-14 (Riwayat & Statistik Latihan):** Member dapat meninjau riwayat latihan masa lalu dan grafik total volume latihan yang telah dicapai.
* **KF-15 (Notifikasi Otomatis WhatsApp):** Member menerima notifikasi WhatsApp saat masa aktif tersisa 7 hari, 3 hari, dan pada hari kedaluwarsa.

---

### B. Kebutuhan Non-Fungsional (*Non-Functional Requirements*)

1. **Keamanan (*Security*):**
   * Password dienkripsi dengan standar hash industri (Bcrypt/Argon2id).
   * Komunikasi data mobile dilindungi oleh token otorisasi Laravel Sanctum.
   * Validasi transaksi Midtrans diverifikasi secara kriptografis menggunakan *Server Key Signature*.
2. **Performa (*Performance*):**
   * Waktu respon pembuatan invoice kasir dan verifikasi presensi QR tidak melebihi 2 detik.
   * Tampilan antarmuka pencatatan set latihan di mobile berjalan mulus tanpa lag (60 fps).
3. **Integritas Data (*Reliability*):**
   * Setiap transaksi pembayaran dan penambahan masa aktif dijalankan dalam mekanisme *Database Transactions* (ACID) untuk mencegah korupsi data.
4. **Kemudahan Penggunaan (*Usability*):**
   * Antarmuka kasir dirancang dengan pola visual *high contrast* untuk mempercepat input staf.
   * Antarmuka pencatatan latihan di aplikasi mobile dioptimalkan untuk pengoperasian satu tangan (*thumb-friendly interaction*).

---

## 7. ROLE PENGGUNA & MATRIKS HAK AKSES

| Fitur / Modul Sistem | Master Admin (Owner) | Admin Gym | Kasir (Cashier) | Member (Mobile) |
|---|:---:|:---:|:---:|:---:|
| **Manajemen Akun Staf** | ✅ Penuh | ❌ | ❌ | ❌ |
| **Laporan Eksekutif Laba-Rugi & Omzet** | ✅ Penuh | ❌ | ❌ | ❌ |
| **Ekspor Laporan (Excel/PDF)** | ✅ | ✅ | ❌ | ❌ |
| **Kelola Master Paket & Katalog Produk** | ✅ | ✅ | ❌ | ❌ |
| **Pencatatan Biaya Operasional (*Expense*)** | ✅ | ✅ | ❌ | ❌ |
| **Kirim Broadcast Pengumuman & WhatsApp** | ✅ | ✅ | ❌ | ❌ |
| **Transaksi Kasir (POS, Suplemen, Daily Pass)** | ✅ | ✅ | ✅ Penuh | ❌ |
| **Validasi Presensi Check-in QR** | ✅ | ✅ | ✅ Penuh | ❌ |
| **Tampil Kartu Presensi Digital QR** | ❌ | ❌ | ❌ | ✅ Penuh |
| **Perpanjangan Paket Online (Midtrans)** | ❌ | ❌ | ❌ | ✅ Penuh |
| **Modul Asisten Latihan Mandiri (Workout Logger)**| ❌ | ❌ | ❌ | ✅ Penuh |
| **Akses Katalog Panduan Gerakan & Otot Target** | ❌ | ❌ | ❌ | ✅ Penuh |

---

## 8. LAPORAN PROGRES IMPLEMENTASI SISTEM SAAT INI

Berdasarkan basis kode sistem yang sedang dibangun pada proyek (`arenafitness` yang bertransformasi ke **WARGYM**):

```
Status Kesiapan Sistem Keseluruhan: [████████░░] 75%
```

| Komponen / Modul | Status Progres | Keterangan Implementasi Riil |
|---|:---:|---|
| **Database & Normalisasi (3NF)** | **100% (Selesai)** | Seluruh tabel (*members, membership_plans, subscriptions, transactions, checkins, products, categories, expenses*) sudah ternormalisasi bersih dan memiliki FK constraint. |
| **Web Kasir (Point of Sale)** | **100% (Selesai)** | Transaksi membership, tiket tamu harian, penjualan suplemen, penghitungan kembalian, dan cetak struk kasir telah berfungsi penuh. |
| **Presensi & Check-in QR** | **100% (Selesai)** | Validasi presensi mandiri dan presensi kasir dengan pengecekan status kedaluwarsa sudah aktif. |
| **Integrasi Midtrans Snap & Webhook** | **95% (Selesai)** | Controller pembayaran dan penanganan webhook sudah diimplementasikan di backend Laravel. |
| **Aturan Stacking Masa Aktif** | **100% (Selesai)** | Logika penambahan masa aktif bertumpuk kalender penuh sudah diimplementasikan di modul transaksi. |
| **WhatsApp Gateway Reminder** | **90% (Selesai)** | Siap dengan driver Evolution API untuk broadcast dan notifikasi otomatis H-7. |
| **Laporan Keuangan & Ekspor** | **100% (Selesai)** | Rekap kasir harian dan ekspor laporan periodik sudah berfungsi di dashboard admin. |
| **Aplikasi Mobile (Flutter)** | **45% (Dalam Pengerjaan)** | Struktur arsitektur mobile, autentikasi Sanctum, dan navigasi bottom bar sudah dirancang; tahap integrasi modul asisten latihan (*workout tracker*). |
| **Modul Asisten Latihan Mandiri** | **50% (Dalam Pengerjaan)** | Desain skema data master gerakan, routine, dan form input logger (set, beban kg, reps, rest timer) sedang tahap penyelesaian di sisi mobile. |

---

## 9. KEUNGGULAN SISTEM (*VALUE PROPOSITION*)

1. **Solusi Lengkap dalam 1 Aplikasi (*All-in-One Member Experience*):**  
   Member tidak perlu lagi membuka banyak aplikasi berbeda. Cukup 1 aplikasi mobile untuk melihat pusat informasi resmi gym, melakukan presensi digital via QR, perpanjangan paket instan, hingga memandu latihan mandiri.
2. **Pusat Informasi Terstruktur (*Single Information Hub*):**  
   Menyediakan rujukan informasi resmi yang rapi dan mudah diakses (daftar paket, jam buka, info alat, status masa aktif), membebaskan member dari ketergantungan bertanya manual di media sosial yang tertimbun konten promosi.
3. **Presensi Digital Nir-Kartu (*Cardless QR Check-in*):**  
   Menghilangkan ketergantungan pada kartu keanggotaan fisik yang mudah hilang atau tertinggal. Presensi menjadi lebih cepat, aman, dan tanpa antrean di resepsionis.
4. **Personal Trainer Virtual & Buku Catatan Digital Mandiri:**  
   Member pemula mendapatkan panduan visual gerakan fitness yang tepat sesuai target otot yang ingin dibangun, dilengkapi pencatat beban (*kg*) dan repetisi untuk menjamin terjadinya *progressive overload* tanpa biaya tambahan.
5. **Otomatisasi Operasional & Pembayaran:**  
   Didukung kasir POS terpadu, integrasi pembayaran digital Midtrans tanpa bukti transfer palsu, dan aturan perpanjangan adil (*smart stacking logic*).
