import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls
import os
import shutil

# Load journal data
journals_data = [
    {
        "identitas": (
            "FitSphere as an Android Application for Gym Membership Management at Chain Gym\n\n"
            "Penulis: Aria Anggit Purnawan, Sulistyo Dwi Sancoko\n"
            "Publikasi: J-INTECH (Journal of Information and Technology), Vol. 13, No. 2, Tahun 2025, Hal. 282–291\n"
            "ISSN: E-ISSN: 2580-720X, P-ISSN: 2303-1425 (Terakreditasi SINTA 4)\n"
            "Penerbit/Afiliasi: Universitas Teknologi Yogyakarta"
        ),
        "tujuan": (
            "Mengatasi permasalahan pengelolaan data dan layanan membership pada Chain Gym (Klaten) yang masih "
            "dikelola secara manual dan terpisah, yang mengakibatkan inefisiensi pada proses pendaftaran kelas kebugaran, "
            "pelacakan aktivitas latihan (fitness tracking), serta keterlambatan pengelolaan arsip data anggota. "
            "Penelitian ini bertujuan membangun aplikasi mobile Android bernama 'FitSphere' untuk mendigitalkan dan "
            "mengintegrasikan manajemen keanggotaan gym secara fleksibel, mandiri, dan efisien."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Rapid Application Development (RAD) yang terdiri dari 4 tahapan berulang: "
            "Requirements Planning (analisis kebutuhan), User Design (pemodelan sistem berorientasi objek menggunakan UML: "
            "Use Case Diagram, Activity Diagram, Sequence Diagram, dan Class Diagram), Construction (pengembangan aplikasi), "
            "dan Cutover (pengujian dan implementasi sistem).\n"
            "2. Pengumpulan Data: Observasi langsung alur operasional Chain Gym Klaten, wawancara mendalam dengan pemilik/staf gym, "
            "dan studi literatur ilmiah.\n"
            "3. Teknologi & Arsitektur: Bahasa pemrograman Kotlin, IDE Android Studio, dan Backend-as-a-Service (BaaS) Firebase "
            "(Firebase Authentication, Cloud Firestore, Realtime Database).\n"
            "4. Metode Pengujian: Black-Box Testing untuk memverifikasi fungsionalitas seluruh fitur aplikasi."
        ),
        "hasil": (
            "1. Berhasil membangun aplikasi Android FitSphere dengan fitur utama: registrasi & login member, warta berita gym (gym news), "
            "pembelian paket membership, konsultasi pelatih kebugaran, pencatatan riwayat latihan (workout history), pendaftaran kelas gym, "
            "serta kalkulator indeks massa tubuh (BMI Calculator).\n"
            "2. Pengujian fungsional dengan metode Black-Box Testing memperoleh tingkat keberhasilan 100% (semua skenario pengujian sukses).\n"
            "3. Sistem berhasil meningkatkan efisiensi administrasi manajemen member bagi pengelola gym dan memberikan kemudahan bagi member "
            "untuk mengelola langganan dan kelas tanpa harus datang secara fisik ke meja resepsionis."
        )
    },
    {
        "identitas": (
            "Model Aplikasi Sistem Pelayanan Pada Gym\n\n"
            "Penulis: Eka Chandra Kirana, Siti Abidah, Ripaidi\n"
            "Publikasi: Jutisi: Jurnal Ilmiah Teknik Informatika dan Sistem Informasi, Vol. 10, No. 3, Desember 2021, Hal. 883–894\n"
            "ISSN: e-ISSN: 2685-0893, p-ISSN: 2089-3787\n"
            "Penerbit/Afiliasi: STMIK Banjarbaru"
        ),
        "tujuan": (
            "Menjawab kendala pelayanan kebugaran konvensional di mana pencatatan data member, penentuan paket langganan, "
            "jadwal fitness, dan penetapan pelatih (trainer) masih menggunakan buku tulis fisik, sehingga pencarian data lambat, "
            "arsip rawan tercecer, dan pembuatan laporan keuangan bulanan memerlukan rekapitulasi hitung ulang yang rawan salah. "
            "Tujuan penelitian ini adalah membangun model aplikasi berbasis web yang mampu mengotomatisasi seluruh alur pelayanan gym."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Model Prototyping yang melibatkan partisipasi aktif calon pengguna sistem dalam "
            "setiap siklus evaluasi antarmuka dan kebutuhan fungsional.\n"
            "2. Pengumpulan Data: Observasi langsung tata kelola operasional gym, wawancara terstruktur dengan pemilik dan instruktur, "
            "serta studi kepustakaan.\n"
            "3. Desain & Pemodelan: Menggunakan standar UML (Unified Modeling Language) meliputi Use Case Diagram, Activity Diagram, "
            "dan Class Diagram.\n"
            "4. Teknologi & Arsitektur: Dibangun berbasis Web menggunakan bahasa pemrograman PHP, server web Apache, dan basis data MySQL.\n"
            "5. Metode Pengujian: Menggunakan pengujian ganda yaitu White-Box Testing (analisis logika basis path) dan Black-Box Testing (validasi form dan antarmuka)."
        ),
        "hasil": (
            "1. Menghasilkan aplikasi web sistem pelayanan gym yang mengintegrasikan pencatatan data keanggotaan, penentuan paket membership, "
            "jadwal latihan kebugaran, alokasi instruktur/pelatih senam/fitness, transaksi pembayaran, dan pembuatan laporan pendapatan bulanan.\n"
            "2. Pengujian White-Box dan Black-Box membuktikan bahwa seluruh alur kode logis dan form fungsionalitas berjalan secara valid tanpa kesalahan logika.\n"
            "3. Aplikasi mampu mempercepat pencarian arsip anggota, mengeliminasi kesalahan hitung pada pelaporan bulanan, serta mempermudah member memantau jadwal secara online."
        )
    },
    {
        "identitas": (
            "Pengembangan Aplikasi Workout Berbasis Android dengan Fitur Personalisasi Program Latihan dan Monitoring Kesehatan Menggunakan Metode High Intensity Interval Training (HIIT)\n\n"
            "Penulis: Ilham Balit Luxfanza, Niki Ratama\n"
            "Publikasi: JORAPI: Journal of Research and Publication Innovation, Vol. 3, No. 1, Januari 2025, Hal. 2515–2523\n"
            "ISSN: ISSN: 2985-4768\n"
            "Penerbit/Afiliasi: Universitas Pamulang"
        ),
        "tujuan": (
            "Membantu member di Arum Gym yang sering mengalami kebingungan dan hambatan dalam memilih menu gerakan latihan yang tepat "
            "saat berlatih mandiri tanpa pendampingan personal trainer. Penelitian ini bertujuan mengembangkan aplikasi workout berbasis Android "
            "yang menyediakan fitur personalisasi program latihan dan monitoring kesehatan menggunakan metode High Intensity Interval Training (HIIT) "
            "guna memandu latihan secara benar, teratur, dan terukur."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Multimedia Development Life Cycle (MDLC) dengan 6 fase: Concept (perumusan konsep & tujuan), "
            "Design (perancangan storyboard dan alur UI), Material Collecting (pengumpulan aset ikon, gambar postur, dan instruksi), "
            "Assembly (pengkodean modul aplikasi), Testing (pengujian fungsional), dan Distribution (penyebaran aplikasi kepada pengguna).\n"
            "2. Pengumpulan Data: Wawancara langsung dengan pemilik Arum Gym, observasi lapangan terhadap kendala member pemula, dan studi pustaka kebugaran.\n"
            "3. Teknologi & Arsitektur: Dikembangkan menggunakan IDE Android Studio, bahasa Java/Kotlin, pemrosesan multimedia, dan SQLite.\n"
            "4. Metode Pengujian: Black-Box Testing untuk fungsionalitas dan kuesioner User Acceptance Testing (UAT) untuk mengukur tingkat kepuasan pengguna."
        ),
        "hasil": (
            "1. Terwujudnya aplikasi Android workout dengan modul personalisasi program latihan, panduan instruksi visual gerakan tubuh, "
            "timer interval latihan dan istirahat otomatis berbasis metode HIIT, serta monitoring kesehatan berupa kalkulasi estimasi kalori dan denyut nadi.\n"
            "2. Hasil pengujian UAT menunjukkan bahwa mayoritas pengguna memberikan respon sangat positif dengan rata-rata persentase kepuasan mencapai 91%.\n"
            "3. Aplikasi terbukti efektif membantu member Arum Gym mengatasi kendala saat latihan mandiri, meminimalisir risiko cedera otot akibat salah postur, "
            "serta meningkatkan motivasi berolahraga secara konsisten."
        )
    },
    {
        "identitas": (
            "Pengembangan Sistem Informasi Pengelolaan Membership Gym King Fitness Berbasis Web Menggunakan Metode USDP\n\n"
            "Penulis: Hanna Paulina, Dea Zahari, Anita Meliyanti, Kusuma Hati\n"
            "Publikasi: Jurnal Rekayasa Sistem Informasi dan Teknologi (JRSIT), Vol. 3, No. 3, Februari 2026, Hal. 481–492\n"
            "ISSN: e-ISSN: 3025-888X\n"
            "Penerbit/Afiliasi: STMIK Antar Bangsa"
        ),
        "tujuan": (
            "Menyelesaikan permasalahan operasional pada King Fitness yang masih mengandalkan pencatatan membership manual dengan buku kertas, "
            "yang sering menimbulkan keterlambatan administrasi, kesalahan pencatatan transaksi, hilangnya data fisik, dan kesulitan dalam pelacakan masa aktif member. "
            "Tujuan penelitian ini adalah membangun sistem informasi pengelolaan membership berbasis web untuk meningkatkan efisiensi administrasi, akurasi data, "
            "dan kecepatan pelayanan anggota."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Unified Software Development Process (USDP) yang mencakup empat tahap iteratif berorientasi objek: "
            "Inception (identifikasi kebutuhan & lingkup projek), Elaboration (analisis arsitektur sistem & desain use case), "
            "Construction (implementasi kode dan fitur aplikasi), dan Transition (pengujian, migrasi, dan implementasi pengguna).\n"
            "2. Pemodelan Sistem: Unified Modeling Language (UML) meliputi Use Case Diagram, Activity Diagram, dan Class Diagram.\n"
            "3. Pengumpulan Data: Observasi alur kerja kasir King Fitness, wawancara mendalam dengan pemilik/staf, dan pengumpulan berkas pendaftaran.\n"
            "4. Teknologi & Arsitektur: Framework PHP Laravel, basis data relasional MySQL, HTML5, CSS3, dan Bootstrap.\n"
            "5. Metode Pengujian: Black-Box Testing pada seluruh fitur antarmuka dan modul fungsional."
        ),
        "hasil": (
            "1. Berhasil mengembangkan sistem informasi web membership King Fitness dengan fitur: pendaftaran member daring, pemilihan paket membership, "
            "verifikasi pembayaran, pencetakan kartu digital ber-Barcode/QR Code keanggotaan, serta pencatatan absensi presensi harian member.\n"
            "2. Pengujian Black-Box menunjukkan bahwa seluruh fitur sistem berjalan 100% valid sesuai spesifikasi fungsionalitas yang ditetapkan.\n"
            "3. Sistem terbukti menurunkan tingkat kesalahan input data, mempercepat alur administrasi kasir, mempermudah pelacakan masa kedaluwarsa keanggotaan, "
            "serta menyajikan data laporan yang akurat bagi manajemen King Fitness."
        )
    },
    {
        "identitas": (
            "Perancangan Aplikasi Workout Tanpa Alat Bantu\n\n"
            "Penulis: Kristina, Kartono, Harimukti Wicaksana\n"
            "Publikasi: Jurnal InTekSis, Vol. 10, No. 2, Tahun 2023, Hal. 12–21\n"
            "Penerbit/Afiliasi: Universitas Widya Dharma Pontianak"
        ),
        "tujuan": (
            "Membantu masyarakat dan pemula yang ingin berolahraga tetapi memiliki keterbatasan akses terhadap peralatan gym, keterbatasan biaya sewa instruktur, "
            "dan minimnya pengetahuan tentang teknik gerakan olahraga yang benar sehingga rentan mengalami cedera otot. "
            "Penelitian ini bertujuan merancang aplikasi mobile Android yang menyediakan panduan latihan workout mandiri tanpa alat bantu disertai kalkulasi gizi dan kalori harian."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Model Waterfall (Analisis, Desain, Pengkodean, dan Pengujian Sistem).\n"
            "2. Metode Penelitian: Metode deskriptif dengan teknik pengumpulan data studi pustaka literatur ilmu kesehatan, kebugaran jasmani, dan teknologi mobile.\n"
            "3. Pemodelan Sistem: Perancangan alur kerja sistem, diagram alir data (Flowchart), dan antarmuka mobile.\n"
            "4. Teknologi & Arsitektur: Dikembangkan menggunakan IDE Android Studio, bahasa Java, XML Layout, dan database lokal SQLite.\n"
            "5. Metode Pengujian: Pengujian fungsionalitas sistem pada perangkat smartphone Android untuk memastikan video tutorial dan rumus berjalan akurat."
        ),
        "hasil": (
            "1. Menghasilkan aplikasi Android workout tanpa alat dengan fitur utama: video tutorial gerakan olahraga tubuh (push-up, squat, plank, dll.), "
            "kalkulator Basal Metabolic Rate (BMR), kalkulator Body Mass Index (BMI), estimasi kebutuhan kalori harian, estimasi kebutuhan protein harian, "
            "kalender riwayat latihan, dan grafik pemantauan penurunan berat badan.\n"
            "2. Aplikasi terbukti dapat memandu pemula melakukan workout secara tepat dan aman tanpa perlu peralatan gym yang mahal.\n"
            "3. Memudahkan pengguna mengatur target gizi kalori/protein dan mengevaluasi konsistensi latihan fisik secara mandiri kapan saja dan di mana saja."
        )
    },
    {
        "identitas": (
            "Perancangan Sistem Informasi Membership Fitness pada FR Fitness Cileungsi Berbasis Java NetBeans\n\n"
            "Penulis: Aprijal Muhaemin\n"
            "Publikasi: JRAMI: Jurnal Riset dan Aplikasi Mahasiswa Informatika, Vol. 04, No. 01, Tahun 2023, Hal. 32–39\n"
            "ISSN: e-ISSN: 2715-8756\n"
            "Penerbit/Afiliasi: Universitas Indraprasta PGRI (UNINDRA)"
        ),
        "tujuan": (
            "Membangun sistem informasi keanggotaan fitness pada FR Fitness Cileungsi yang lebih aman, terkomputerisasi, dan efisien "
            "untuk menggantikan sistem pembukuan manual di buku besar yang rawan tercecer dan menimbulkan selisih kas. "
            "Penelitian ini bertujuan mempermudah admin dan member dalam memproses pendaftaran baru, perpanjangan keanggotaan, penjualan produk suplemen, "
            "serta pembuatan laporan transaksi keuangan secara cepat dan akurat."
        ),
        "metode": (
            "1. Metode Penelitian: Metode penelitian deskriptif untuk menganalisis kelemahan sistem berjalan dan merumuskan sistem baru.\n"
            "2. Pengumpulan Data: Studi pustaka, wawancara langsung dengan pemilik FR Fitness, dan observasi operasional kasir.\n"
            "3. Pemodelan Sistem: Desain aliran data dengan Flowchart dan relasi entitas basis data (ERD).\n"
            "4. Teknologi & Arsitektur: Bahasa pemrograman Java dengan lingkungan IDE NetBeans, Graphical User Interface (Java Swing), driver JDBC, dan basis data MySQL.\n"
            "5. Metode Pengujian: Pengujian validasi masukan form dan pengujian keluaran cetak laporan (iReport)."
        ),
        "hasil": (
            "1. Menghasilkan aplikasi desktop sistem informasi membership FR Fitness Cileungsi yang memuat modul login staf, pendaftaran member baru, "
            "transaksi perpanjangan keanggotaan, kasir penjualan produk suplemen/minuman, serta cetak bukti transaksi dan laporan pendapatan harian/bulanan.\n"
            "2. Sistem terkomputerisasi ini mempermudah admin mengolah data secara akurat dan meminimalisir kesalahan input data manual.\n"
            "3. Menghemat waktu pemrosesan layanan di kasir, mempercepat pencarian arsip riwayat member, dan meningkatkan efisiensi pelaporan laba gym bagi pemilik."
        )
    },
    {
        "identitas": (
            "Perancangan Sistem Informasi Pelayanan Membership Gym Berbasis Web pada AB8 Gym Bakung Jaya Kota Jambi\n\n"
            "Penulis: Puja Budesri, Mutamassikin\n"
            "Publikasi: Jurnal Studi Multidisipliner, Vol. 10, No. 4, April 2026, Hal. 297–314\n"
            "ISSN: e-ISSN: 2118-7453\n"
            "Penerbit/Afiliasi: UIN Sulthan Thaha Saifuddin Jambi"
        ),
        "tujuan": (
            "Menjawab persoalan administrasi manual pada AB8 Gym Bakung Jaya Kota Jambi yang memicu risiko kesalahan data, antrean layanan pendaftaran yang lambat, "
            "kesulitan pengawasan masa aktif keanggotaan, serta pencatatan absensi yang tidak terdokumentasi rapi. "
            "Penelitian ini bertujuan merancang dan mengimplementasikan sistem informasi pelayanan membership gym berbasis web yang dapat diakses online 24/7 "
            "serta dilengkapi notifikasi email otomatis untuk mengoptimalkan layanan pelanggan."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Metode Prototyping yang terdiri dari tahap pengumpulan kebutuhan pengguna, perancangan cepat (quick design), "
            "pembuatan prototipe sistem, evaluasi prototipe bersama pengguna, dan perbaikan berulang hingga sistem siap dioperasikan.\n"
            "2. Pemodelan Sistem: Menggunakan Unified Modeling Language (UML) mencakup Use Case Diagram, Activity Diagram, dan Class Diagram.\n"
            "3. Pengumpulan Data: Observasi operasional, wawancara dengan pengelola gym, dan studi pustaka.\n"
            "4. Teknologi & Arsitektur: Framework PHP Laravel, basis data MySQL, HTML5, CSS3, JavaScript, dan web hosting online.\n"
            "5. Metode Pengujian: Black-Box Testing untuk fungsi teknis dan User Acceptance Testing (UAT) kepada 10 responden pengguna."
        ),
        "hasil": (
            "1. Berhasil membangun dan mempublikasikan (hosting online) sistem informasi membership AB8 Gym dengan fitur: registrasi member online, "
            "verifikasi pembayaran, pengelolaan jadwal latihan, pencatatan absensi kehadiran digital, laporan finansial, dan notifikasi email pengingat kedaluwarsa masa aktif.\n"
            "2. Pengujian Black-Box menunjukkan seluruh fungsi berjalan baik, dan pengujian UAT memperoleh nilai rata-rata kelayakan 82% (kategori 'Sangat Layak').\n"
            "3. Sistem berhasil mempermudah akses pelayanan bagi admin dan member tanpa batasan waktu dan tempat serta meningkatkan kepatuhan perpanjangan paket anggota."
        )
    },
    {
        "identitas": (
            "Perancangan Aplikasi Membership Gym Berbasis Web untuk Optimalisasi Layanan Pelanggan\n\n"
            "Penulis: Nafis Sururi, Imam Thoib, Danang Satya Nugraha, Fendy Bayu F, M. Sabil Shah P\n"
            "Publikasi: JUKTISI: Jurnal Komputer, Teknik dan Sistem Informasi, Vol. 4, No. 2, September 2025, Hal. 1121–1132\n"
            "ISSN: p-ISSN: 2962-3022, e-ISSN: 2963-7104, DOI: 10.62712/juktisi.v4i2.605\n"
            "Penerbit/Afiliasi: Institut Teknologi Mojosari, Nganjuk"
        ),
        "tujuan": (
            "Mengatasi masalah pengelolaan membership gym akibat lonjakan gaya hidup sehat yang tidak diimbangi sistem modern, di mana pencatatan manual "
            "menyebabkan keterlambatan registrasi, rentan salah hitung, dan minimnya saluran penyampaian informasi jadwal atau promosi yang menurunkan loyalitas pelanggan. "
            "Penelitian ini bertujuan merancang aplikasi membership gym berbasis web yang berfokus pada optimalisasi layanan pelanggan (Customer Relationship Optimization)."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Pendekatan User-Centered Design (UCD) dan Prototyping yang mengutamakan kebutuhan serta kenyamanan antarmuka pengguna.\n"
            "2. Pengumpulan Data: Observasi alur pelayanan gym, wawancara semi-terstruktur dengan staf kasir dan member aktif, serta studi literatur manajemen layanan.\n"
            "3. Pemodelan Sistem: Desain antarmuka responsif (wireframing & prototyping), perancangan proses bisnis, dan skema database relasional.\n"
            "4. Teknologi & Arsitektur: Dikembangkan berbasis web menggunakan PHP/Laravel, CSS Framework (Bootstrap/Tailwind), JavaScript, dan database MySQL.\n"
            "5. Metode Pengujian: Evaluasi prototipe antarmuka dan pengujian fungsionalitas fitur layanan."
        ),
        "hasil": (
            "1. Menghasilkan rancangan aplikasi membership gym berbasis web yang dilengkapi fitur: pendaftaran dan perpanjangan keanggotaan online mandiri, "
            "pengelolaan database profil anggota, modul integrasi pembayaran, penyajian jadwal latihan instruktur, dan pengumuman promosi real-time.\n"
            "2. Sistem terbukti mampu memangkas waktu proses administrasi keanggotaan dan mempercepat akses informasi bagi member tanpa harus hadir fisik di kasir.\n"
            "3. Penerapan sistem memberikan kemudahan layanan yang berdampak langsung pada peningkatan mutu pelayanan, kepuasan, dan loyalitas member gym."
        )
    },
    {
        "identitas": (
            "Perancangan Sistem Aplikasi Pendataan Membership Gym Menggunakan Metode Unified Software Development Process (USDP) Berbasis Web\n\n"
            "Penulis: Indah Purnama Sari, Faisal Alfarisi\n"
            "Publikasi: Hello World: Jurnal Ilmu Komputer, Vol. 3, No. 1, April 2024, Hal. 37–48\n"
            "ISSN: e-ISSN: 2829-8616, DOI: 10.56211/helloworld.v3i1.523\n"
            "Penerbit/Afiliasi: Universitas Muhammadiyah Sumatera Utara (UMSU), Medan"
        ),
        "tujuan": (
            "Mengatasi kelemahan pada One-R Gym (Deli Serdang) yang memiliki banyak anggota tetapi pencatatan data keanggotaan dan pembayaran paketnya masih "
            "menggunakan catatan tertulis kertas konvensional yang rentan rusak, tercecer, dan hilang. Penelitian ini bertujuan membangun sistem aplikasi pendataan "
            "membership terkomputerisasi berbasis web yang aman, terstruktur, dan dapat dimanfaatkan sebagai sarana promosi bisnis gym kepada masyarakat luas."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Unified Software Development Process (USDP) yang berbasis komponen dan berorientasi objek dengan 4 tahap utama: "
            "Inception (perencanaan awal), Elaboration (analisis spesifikasi dan desain arsitektur), Construction (pembuatan kode program), dan Transition (pengujian dan instalasi).\n"
            "2. Pemodelan Sistem: Unified Modeling Language (UML) meliputi Use Case Diagram, Activity Diagram, dan Class Diagram.\n"
            "3. Pengumpulan Data: Observasi langsung di One-R Gym, wawancara dengan pengelola, serta studi kepustakaan sistem informasi.\n"
            "4. Teknologi & Arsitektur: Bahasa pemrograman PHP, database MySQL (MySQLi), dan framework Bootstrap untuk tampilan web yang responsif.\n"
            "5. Metode Pengujian: Pengujian verifikasi fungsionalitas sistem pada modul input data dan cetak laporan."
        ),
        "hasil": (
            "1. Menghasilkan aplikasi web pendataan membership One-R Gym yang mencakup fungsi: manajemen data member, pengaturan paket keanggotaan, "
            "pencatatan transaksi pembayaran, pencetakan bukti bayar (kwitansi), pembuatan laporan data anggota terpusat, dan halaman promosi gym untuk publik.\n"
            "2. Penggunaan metode USDP membuat struktur perangkat lunak lebih terorganisasi, modular, dan mudah dikembangkan lebih lanjut di masa mendatang.\n"
            "3. Mengeliminasi risiko kehilangan data akibat kerusakan arsip fisik dan mempermudah pengelola dalam mengontrol data anggota secara akurat."
        )
    },
    {
        "identitas": (
            "Web-Based Gym Membership System with Semi-Automated QRIS Verification Developed Through Agile-Scrum\n\n"
            "Penulis: Ragel Trisudarmo, Anisa Ramdhani, Dila Mardiyanti, Nabila Anggi Permatasari\n"
            "Publikasi: Nusantara Journal of Artificial Intelligence and Information Systems (NUAI), Vol. 2, No. 1, June 2026, pp. 17–26\n"
            "ISSN: ISSN: 3090-8302, DOI: 10.47776/nuai.v1i2.1904\n"
            "Penerbit/Afiliasi: Universitas Kuningan, Indonesia"
        ),
        "tujuan": (
            "Menjawab persoalan pengelolaan administrasi membership manual di TGW Gym yang menimbulkan tingginya ketidakakuratan pencatatan, "
            "proses verifikasi pembayaran nontunai yang sangat memakan waktu, dan keterlambatan pembaruan status masa aktif anggota. "
            "Tujuan penelitian ini adalah membangun dan mengevaluasi sistem keanggotaan gym berbasis web dengan fitur verifikasi pembayaran QRIS "
            "semi-otomatis menggunakan kerangka kerja Agile-Scrum untuk meningkatkan efisiensi dan akurasi operasional."
        ),
        "metode": (
            "1. Metode Pengembangan Sistem: Agile Development Model dengan kerangka kerja Scrum melalui 5 kali siklus Sprint (dua mingguan): "
            "Sprint 1 (manajemen akun & autentikasi), Sprint 2 (pendaftaran member & katalog paket), Sprint 3 (unggah bukti bayar QRIS & verifikasi semi-otomatis admin), "
            "Sprint 4 (logika perpanjangan masa aktif / expiry-aware renewal), dan Sprint 5 (rekapitulasi laporan transaksi & perbaikan antarmuka).\n"
            "2. Pengumpulan Data: Observasi terstruktur sebelum dan sesudah implementasi (pre and post-implementation observation) selama 15 hari, "
            "wawancara dengan staf dan member, serta studi dokumen transaksi.\n"
            "3. Teknologi & Arsitektur: Framework PHP Laravel 11, basis data MySQL 8, Tailwind CSS, dan modul pemindaian QRIS dinamis.\n"
            "4. Metode Pengujian: Black-Box Testing menyeluruh mencakup 8 skenario pengujian fungsional pada halaman pemilihan paket, pembayaran, dan perpanjangan."
        ),
        "hasil": (
            "1. Berhasil membangun sistem web keanggotaan TGW Gym dengan kemampuan registrasi mandiri, pemilihan paket fleksibel, "
            "pengunggahan bukti transfer QRIS dengan verifikasi cepat di panel admin, serta kalkulasi perpanjangan masa aktif kalender bertumpuk secara akurat.\n"
            "2. Pengujian Black-Box pada 8 skenario pengujian fungsional dinyatakan 100% lulus (semua skenario berjalan sesuai spesifikasi kebutuhan).\n"
            "3. Hasil observasi membuktikan penurunan drastis waktu verifikasi pembayaran dari hitungan jam/hari menjadi hitungan menit, "
            "mengeliminasi kesalahan pencatatan transaksi kasir, serta memastikan keaktifan hak latihan member terupdate secara tepat waktu."
        )
    }
]

def style_cell(cell, bg_color=None, border_color="000000", border_sz="4", pad_top=100, pad_bottom=100, pad_left=120, pad_right=120):
    tcPr = cell._tc.get_or_add_tcPr()
    if bg_color:
        shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{bg_color}"/>')
        tcPr.append(shd)
    tcMar = parse_xml(f'''<w:tcMar {nsdecls("w")}>
        <w:top w:w="{pad_top}" w:type="dxa"/>
        <w:bottom w:w="{pad_bottom}" w:type="dxa"/>
        <w:left w:w="{pad_left}" w:type="dxa"/>
        <w:right w:w="{pad_right}" w:type="dxa"/>
    </w:tcMar>''')
    tcPr.append(tcMar)

def set_table_borders(table, color="000000", sz="4"):
    tblPr = table._tbl.tblPr
    borders = parse_xml(f'''<w:tblBorders {nsdecls("w")}>
        <w:top w:val="single" w:sz="{sz}" w:space="0" w:color="{color}"/>
        <w:bottom w:val="single" w:sz="{sz}" w:space="0" w:color="{color}"/>
        <w:insideH w:val="single" w:sz="{sz}" w:space="0" w:color="{color}"/>
        <w:insideV w:val="single" w:sz="{sz}" w:space="0" w:color="{color}"/>
        <w:left w:val="single" w:sz="{sz}" w:space="0" w:color="{color}"/>
        <w:right w:val="single" w:sz="{sz}" w:space="0" w:color="{color}"/>
    </w:tblBorders>''')
    tblPr.append(borders)

def build_literature_review_docx(output_path):
    doc = docx.Document()
    
    # Page setup (A4 Landscape for wide table view or Portrait with tight margins)
    # Let's inspect: a 4-column literature review table is best in Portrait or Landscape?
    # In academic reviews, Portrait with 2cm margins gives ample space!
    for section in doc.sections:
        section.top_margin = Inches(0.8)
        section.bottom_margin = Inches(0.8)
        section.left_margin = Inches(0.8)
        section.right_margin = Inches(0.8)
        section.page_width = Inches(8.27)
        section.page_height = Inches(11.69)

    # Header Paragraphs
    def add_p(text="", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=0, space_after=2, bold=False, italic=False, size_pt=11):
        p = doc.add_paragraph()
        p.alignment = align
        p.paragraph_format.space_before = Pt(space_before)
        p.paragraph_format.space_after = Pt(space_after)
        p.paragraph_format.line_spacing = 1.15
        if text:
            run = p.add_run(text)
            run.font.name = "Times New Roman"
            run.font.size = Pt(size_pt)
            run.bold = bold
            run.italic = italic
            run.font.color.rgb = RGBColor(0,0,0)
        return p

    # Student metadata
    p_nama = add_p(space_after=2)
    r1 = p_nama.add_run("NAMA\t: ")
    r1.font.name = "Times New Roman"; r1.font.size = Pt(11); r1.bold = True
    r2 = p_nama.add_run("Mohammad Yusril Musyafak")
    r2.font.name = "Times New Roman"; r2.font.size = Pt(11)

    p_absen = add_p(space_after=2)
    r1 = p_absen.add_run("Absen\t: ")
    r1.font.name = "Times New Roman"; r1.font.size = Pt(11); r1.bold = True
    r2 = p_absen.add_run("17")
    r2.font.name = "Times New Roman"; r2.font.size = Pt(11)

    p_kelas = add_p(space_after=8)
    r1 = p_kelas.add_run("Kelas\t: ")
    r1.font.name = "Times New Roman"; r1.font.size = Pt(11); r1.bold = True
    r2 = p_kelas.add_run("3F")
    r2.font.name = "Times New Roman"; r2.font.size = Pt(11)

    # Title
    p_title1 = add_p("LITERATURE REVIEW", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=3, bold=True, size_pt=12)
    p_title2 = add_p("(Rancang Bangun Platform Layanan Gym Terintegrasi (Web & Mobile) dengan Modul asisten Latihan Mandiri studi kasus wargym)", 
                     align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=12, bold=True, size_pt=11)

    # Create Table: 1 Header + 10 Rows
    table = doc.add_table(rows=1, cols=4)
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(table)

    headers = ["Identitas Artikel", "Tujuan Penelitian", "Metode dan Rencana Penelitian", "Hasil Penelitian"]
    hdr_cells = table.rows[0].cells
    for i, h in enumerate(headers):
        hdr_cells[i].paragraphs[0].text = h
        run = hdr_cells[i].paragraphs[0].runs[0]
        run.font.name = "Times New Roman"
        run.font.size = Pt(10)
        run.bold = True
        hdr_cells[i].paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        hdr_cells[i].paragraphs[0].paragraph_format.space_before = Pt(4)
        hdr_cells[i].paragraphs[0].paragraph_format.space_after = Pt(4)
        style_cell(hdr_cells[i], bg_color="E2E8F0")

    # Add 10 Journal Rows
    for idx, j_data in enumerate(journals_data):
        row = table.add_row()
        cells = row.cells
        
        # Col 0: Identitas Artikel
        p0 = cells[0].paragraphs[0]
        p0.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p0.paragraph_format.space_before = Pt(2)
        p0.paragraph_format.space_after = Pt(2)
        p0.paragraph_format.line_spacing = 1.15
        run0 = p0.add_run(j_data["identitas"])
        run0.font.name = "Times New Roman"
        run0.font.size = Pt(9)

        # Col 1: Tujuan Penelitian
        p1 = cells[1].paragraphs[0]
        p1.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p1.paragraph_format.space_before = Pt(2)
        p1.paragraph_format.space_after = Pt(2)
        p1.paragraph_format.line_spacing = 1.15
        run1 = p1.add_run(j_data["tujuan"])
        run1.font.name = "Times New Roman"
        run1.font.size = Pt(9)

        # Col 2: Metode dan Rencana Penelitian
        p2 = cells[2].paragraphs[0]
        p2.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p2.paragraph_format.space_before = Pt(2)
        p2.paragraph_format.space_after = Pt(2)
        p2.paragraph_format.line_spacing = 1.15
        run2 = p2.add_run(j_data["metode"])
        run2.font.name = "Times New Roman"
        run2.font.size = Pt(9)

        # Col 3: Hasil Penelitian
        p3 = cells[3].paragraphs[0]
        p3.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p3.paragraph_format.space_before = Pt(2)
        p3.paragraph_format.space_after = Pt(2)
        p3.paragraph_format.line_spacing = 1.15
        run3 = p3.add_run(j_data["hasil"])
        run3.font.name = "Times New Roman"
        run3.font.size = Pt(9)

        bg = "FFFFFF" if idx % 2 == 0 else "F8FAFC"
        for c in cells:
            style_cell(c, bg_color=bg)

    # Column widths
    col_widths = [Inches(1.6), Inches(1.6), Inches(1.7), Inches(1.7)]
    for row in table.rows:
        for i, w in enumerate(col_widths):
            row.cells[i].width = w

    doc.save(output_path)
    print(f"Saved: {output_path}")

if __name__ == "__main__":
    out1 = r"C:\laragon\www\arenafitness\jurnal refrensi\literaturereview_Mohammad Yusril Musyafak.docx"
    out2 = r"C:\Users\LENOVO\Downloads\jurnal refrensi\literaturereview_Mohammad Yusril Musyafak.docx"
    build_literature_review_docx(out1)
    shutil.copy2(out1, out2)
    print("Copied to Downloads folder as well!")
