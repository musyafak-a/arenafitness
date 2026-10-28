import os
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn
from pptx import Presentation
from pptx.util import Inches as PptInches, Pt as PptPt
from pptx.dml.color import RGBColor as PptRGBColor

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    tcPr.append(parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>'))

def set_cell_margins(cell, top=120, bottom=120, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def set_table_borders(table, color="D3D3D3"):
    tblPr = table._tbl.tblPr
    borders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        f'  <w:top w:val="single" w:sz="4" w:space="0" w:color="{color}"/>'
        f'  <w:left w:val="none"/>'
        f'  <w:bottom w:val="single" w:sz="6" w:space="0" w:color="{color}"/>'
        f'  <w:right w:val="none"/>'
        f'  <w:insideH w:val="single" w:sz="4" w:space="0" w:color="{color}"/>'
        f'  <w:insideV w:val="none"/>'
        f'</w:tblBorders>'
    )
    tblPr.append(borders)

def build_docx(filename="DOKUMEN_KELAYAKAN_JUDUL_WARGYM.docx"):
    doc = Document()
    
    # Page setup (A4, standard academic margins: Left 4cm, Top 3cm, Right 3cm, Bottom 3cm)
    for section in doc.sections:
        section.page_width = Inches(8.27)
        section.page_height = Inches(11.69)
        section.top_margin = Inches(1.18)      # 3.0 cm
        section.bottom_margin = Inches(1.18)   # 3.0 cm
        section.left_margin = Inches(1.57)     # 4.0 cm
        section.right_margin = Inches(1.18)    # 3.0 cm

    # Base styling
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Times New Roman'
    normal_style.font.size = Pt(12)
    normal_style.font.color.rgb = RGBColor(30, 30, 30)

    # ------------------ COVER PAGE ------------------
    p_inst = doc.add_paragraph()
    p_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_inst1 = p_inst.add_run("PROPOSAL UJIAN KELAYAKAN JUDUL TUGAS AKHIR\n")
    r_inst1.bold = True
    r_inst1.font.size = Pt(14)
    r_inst1.font.color.rgb = RGBColor(15, 23, 42)
    
    r_inst2 = p_inst.add_run("PROGRAM STUDI D-III MANAJEMEN INFORMATIKA (PSDKU)\n\n\n")
    r_inst2.bold = True
    r_inst2.font.size = Pt(13)

    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_title_tag = p_title.add_run("USULAN JUDUL TUGAS AKHIR:\n")
    r_title_tag.font.size = Pt(11)
    r_title_tag.font.color.rgb = RGBColor(100, 116, 139)
    
    r_title = p_title.add_run("RANCANG BANGUN PLATFORM LAYANAN GYM TERINTEGRASI\n(WEB & MOBILE) DENGAN MODUL ASISTEN LATIHAN MANDIRI\n(STUDI KASUS: WARGYM)\n\n\n\n")
    r_title.bold = True
    r_title.font.size = Pt(14)
    r_title.font.color.rgb = RGBColor(15, 23, 42)

    p_bio = doc.add_paragraph()
    p_bio.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_bio1 = p_bio.add_run("Diajukan Sebagai Salah Satu Syarat Kelayakan Judul Tugas Akhir\nDiploma III (D-III) Manajemen Informatika\n\n\n")
    r_bio1.font.size = Pt(11)
    r_bio1.italic = True
    
    r_author = p_bio.add_run("Disusun Oleh:\n")
    r_author.bold = True
    r_author.font.size = Pt(12)
    p_bio.add_run("[NAMA MAHASISWA]\nNIM: [NIM MAHASISWA]\n\n\n")

    p_foot = doc.add_paragraph()
    p_foot.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_foot = p_foot.add_run("PROGRAM STUDI DIPLOMA III MANAJEMEN INFORMATIKA\nDEPARTEMEN ILMU KOMPUTER DAN MANAJEMEN INFORMATIKA\nTAHUN AKADEMIK 2026/2027")
    r_foot.bold = True
    r_foot.font.size = Pt(12)

    doc.add_page_break()

    # Helper functions
    def add_sec_heading(title):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(16)
        p.paragraph_format.space_after = Pt(6)
        p.paragraph_format.keep_with_next = True
        r = p.add_run(title)
        r.bold = True
        r.font.size = Pt(13)
        r.font.color.rgb = RGBColor(15, 23, 42)
        return p

    def add_sub_heading(title):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(10)
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.keep_with_next = True
        r = p.add_run(title)
        r.bold = True
        r.font.size = Pt(12)
        r.font.color.rgb = RGBColor(30, 41, 59)
        return p

    def add_p(text, bold_prefix="", italic=False):
        p = doc.add_paragraph()
        p.paragraph_format.space_after = Pt(6)
        p.paragraph_format.line_spacing = 1.15
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        if bold_prefix:
            r_b = p.add_run(bold_prefix)
            r_b.bold = True
        r = p.add_run(text)
        r.italic = italic
        return p

    def add_bullet(text, bold_prefix=""):
        p = doc.add_paragraph(style='List Bullet')
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.line_spacing = 1.15
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        if bold_prefix:
            r_b = p.add_run(bold_prefix)
            r_b.bold = True
        p.add_run(text)
        return p

    def format_table_headers_and_cells(tbl, col_widths, headers, rows):
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        set_table_borders(tbl)
        hdr_row = tbl.rows[0]
        for idx, text in enumerate(headers):
            cell = hdr_row.cells[idx]
            cell.width = col_widths[idx]
            set_cell_background(cell, "1E293B")
            set_cell_margins(cell, top=140, bottom=140, left=150, right=150)
            p = cell.paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            r = p.add_run(text)
            r.bold = True
            r.font.size = Pt(10)
            r.font.color.rgb = RGBColor(255, 255, 255)
            
        for r_idx, r_data in enumerate(rows):
            row = tbl.rows[r_idx + 1]
            bg_color = "F8FAFC" if r_idx % 2 == 1 else "FFFFFF"
            for c_idx, val in enumerate(r_data):
                cell = row.cells[c_idx]
                cell.width = col_widths[c_idx]
                set_cell_background(cell, bg_color)
                set_cell_margins(cell, top=100, bottom=100, left=120, right=120)
                p = cell.paragraphs[0]
                p.paragraph_format.line_spacing = 1.1
                r = p.add_run(str(val))
                r.font.size = Pt(10)
                if c_idx == 0:
                    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
                    r.bold = True
                else:
                    p.alignment = WD_ALIGN_PARAGRAPH.LEFT

    # ------------------ SECTION 1 ------------------
    add_sec_heading("1. JUDUL DAN DESKRIPSI UTAMA TUGAS AKHIR")
    add_sub_heading("1.1 Judul Tugas Akhir")
    add_p("Rancang Bangun Platform Layanan Gym Terintegrasi (Web & Mobile) dengan Modul Asisten Latihan Mandiri (Studi Kasus: WARGYM)", bold_prefix="Judul yang Diusulkan: ", italic=True)
    
    add_sub_heading("1.2 Deskripsi Modul Khusus (Core Innovation)")
    add_p(
        "Modul Asisten Latihan Mandiri (Self-Workout Tracker) adalah fitur pendamping interaktif yang dirancang "
        "untuk mengoptimalkan sesi latihan member secara mandiri di gym. Bertindak layaknya personal trainer virtual "
        "dan buku catatan digital, sistem ini menyediakan panduan visual gerakan yang komprehensif, serta memungkinkan "
        "pencatatan beban dan repetisi guna mendukung peningkatan kekuatan terukur (progressive overload)."
    )

    # ------------------ SECTION 2 ------------------
    add_sec_heading("2. LATAR BELAKANG PEMBUATAN SISTEM")
    add_p(
        "Keberadaan fasilitas kebugaran fisik (gym) seperti WARGYM memiliki peran penting dalam memfasilitasi gaya hidup "
        "sehat masyarakat. Namun dalam praktiknya, terdapat kendala operasional mendasar dan friksi pengalaman pengguna "
        "yang dihadapi oleh pihak manajemen maupun member. Berdasarkan observasi di WARGYM, terdapat 3 (tiga) permasalahan "
        "utama yang melatarbelakangi perancangan sistem ini:"
    )

    add_bullet(
        " Media sosial (seperti Instagram dan TikTok) milik WARGYM selama ini lebih difungsikan sebagai etalase promosi visual, "
        "dokumentasi kegiatan harian, dan tayangan suasana tempat gym. Akibatnya, member maupun calon member sangat kesulitan "
        "menemukan informasi resmi yang terstruktur. Informasi krusial—seperti rincian paket membership resmi, jam buka/tutup "
        "operasional libur nasional, ketersediaan suplemen, tata tertib gym, hingga status masa aktif keanggotaan—sering kali "
        "tertimbun oleh postingan promosi. Hal ini memaksa member untuk selalu mengirim pesan manual (DM/chat) ke staf atau datang "
        "langsung ke resepsionis hanya untuk memastikan informasi dasar.",
        bold_prefix="1. Sulitnya Menemukan Pusat Informasi Operasional Gym:"
    )

    add_bullet(
        " Proses presensi masuk (check-in) di WARGYM saat ini masih mengandalkan kartu keanggotaan fisik konvensional. "
        "Kartu fisik memiliki kelemahan mendasar: mudah rusak, mudah hilang, atau sering tertinggal di rumah saat member hendak berlatih. "
        "Ketika kartu tertinggal, staf kasir harus mencari nama dan data keanggotaan secara manual di buku/file register, "
        "yang memicu antrean panjang di meja resepsionis pada jam sibuk (peak hours sore/malam). Selain itu, sistem kartu fisik "
        "rentan disalahgunakan (dipinjamkan kepada orang lain) dan membebani gym dengan biaya cetak kartu berulang.",
        bold_prefix="2. Sistem Presensi Manual Berbasis Kartu Fisik yang Rentan Hilang & Tertinggal:"
    )

    add_bullet(
        " Member pemula (beginner gym-goers) sering mengalami kebingungan dan kecemasan (gym anxiety) mengenai bentuk postur gerakan "
        "yang benar (proper exercise form) dan pemilihan variasi alat sesuai kelompok otot yang ingin dibangun (muscle hypertrophy target). "
        "Di sisi lain, menyewa jasa Personal Trainer (PT) profesional membutuhkan biaya tambahan yang tidak terjangkau bagi sebagian besar member. "
        "Tanpa alat pendamping, member terpaksa berpindah-pindah media (mencari video tutorial di YouTube, lalu mencatat beban di notes ponsel) "
        "yang sangat tidak praktis, terfragmentasi, dan tidak efisien untuk melacak peningkatan beban bertahap (progressive overload).",
        bold_prefix="3. Ketiadaan Tool Mandiri bagi Pemula untuk Mempelajari Form Gerakan dalam 1 Aplikasi:"
    )

    add_sub_heading("Visi Solusi: Platform Layanan Gym All-in-One")
    add_p(
        "Berdasarkan analisis akar masalah tersebut, solusi yang paling tepat dan efisien adalah membangun Platform Layanan Gym Terintegrasi "
        "(Web & Mobile) sebagai satu ekosistem All-in-One. Bagi member, cukup menggunakan 1 aplikasi mobile di ponsel pintar untuk mengakses "
        "pusat informasi resmi gym, menampilkan kartu presensi digital QR nir-kartu, memperpanjang paket membership secara instan, serta "
        "membuka modul asisten latihan mandiri saat berolahraga. Bagi pengelola dan kasir WARGYM, sistem menyediakan portal web terpadu "
        "untuk validasi presensi instan, kasir POS suplemen & tiket harian, otomatisasi masa aktif bertumpuk, dan rekap keuangan real-time."
    )

    # ------------------ SECTION 3 ------------------
    add_sec_heading("3. IDENTIFIKASI PERMASALAHAN DAN PERMINTAAN KLIEN (WARGYM)")
    add_p(
        "Berdasarkan wawancara dengan pengelola WARGYM, kebutuhan sistem dirinci menjadi dua sudut pandang:"
    )
    add_sub_heading("3.1 Kebutuhan Pengelola / Kasir WARGYM")
    add_bullet("Kasir dapat memproses pembayaran membership, tiket tamu harian (daily pass), dan penjualan produk suplemen dalam satu layar cepat dan langsung mencetak struk.", bold_prefix="• Point of Sale (POS) Terpadu: ")
    add_bullet("Validasi kehadiran member cepat melalui pemindaian QR Code di resepsionis tanpa membuka berkas fisik.", bold_prefix="• Presensi Digital Cepat: ")
    add_bullet("Aturan perpanjangan paket yang adil, di mana perpanjangan sebelum expired otomatis disambung dari tanggal kedaluwarsa terakhir (tidak memotong hari aktif member).", bold_prefix="• Smart Stacking Logic: ")
    add_bullet("Pembayaran online via QRIS, GoPay, dan Transfer Virtual Account yang terverifikasi otomatis via webhook Midtrans tanpa kirim bukti transfer WhatsApp.", bold_prefix="• Pembayaran Cashless Otomatis: ")
    add_bullet("Peringatan otomatis H-7 sebelum masa aktif keanggotaan berakhir via WhatsApp Gateway guna mencegah penurunan retensi member.", bold_prefix="• Notifikasi Pengingat Masa Aktif: ")
    add_bullet("Rekap kas harian dan ekspor laporan laba-rugi/omzet berkala (PDF/Excel) untuk pemilik gym.", bold_prefix="• Laporan Finansial Real-Time: ")

    add_sub_heading("3.2 Kebutuhan Member WARGYM")
    add_bullet("Panduan visual postur gerakan fitness per kelompok otot target dan petunjuk penggunaan alat gym yang aman.", bold_prefix="• Virtual Personal Trainer: ")
    add_bullet("Pencatatan langsung set, beban angkatan (kg), repetisi, dan timer istirahat otomatis antar-set latihan.", bold_prefix="• Digital Workout Logger: ")
    add_bullet("Akses kartu QR presensi dan status sisa hari aktif keanggotaan langsung dari aplikasi ponsel.", bold_prefix="• Kartu Member Digital Nir-Kartu: ")
    add_bullet("Rujukan tunggal untuk melihat jadwal operasional, aturan gym, daftar harga paket, dan pengumuman resmi.", bold_prefix="• Pusat Informasi Resmi: ")

    # ------------------ SECTION 4 ------------------
    add_sec_heading("4. SPESIFIKASI MODUL ASISTEN LATIHAN MANDIRI (CORE INNOVATION)")
    add_p(
        "Modul Asisten Latihan Mandiri dirancang dengan 5 (lima) pilar fungsional utama:"
    )
    add_bullet("Menyediakan basis data gerakan kebugaran terstandarisasi yang dikelompokkan berdasarkan otot target (Dada, Punggung, Kaki, Bahu, Lengan, Core), dilengkapi animasi/gambar visual instruksi bentuk gerakan dan panduan keselamatan alat.", bold_prefix="1. Katalog Master Gerakan (Exercise Library): ")
    add_bullet("Member dapat merancang program latihan mingguan (misal: Push Day, Pull Day, Leg Day) atau memilih program template latihan yang direkomendasikan oleh gym.", bold_prefix="2. Penyusun Program Latihan (Custom Routines): ")
    add_bullet("Antarmuka pencatatan langsung saat berolahraga di gym. Member menginput beban (kg) dan repetisi per set, di mana sistem secara otomatis menampilkan beban latihan sebelumnya sebagai target acuan hari ini.", bold_prefix="3. Pencatat Latihan Langsung (Live Workout Logger): ")
    add_bullet("Penghitung waktu istirahat otomatis yang langsung berjalan saat member menyelesaikan sebuah set latihan (pilihan 60, 90, atau 120 detik) dengan pemberitahuan getar/audio.", bold_prefix="4. Timer Istirahat Otomatis (Auto Rest Timer): ")
    add_bullet("Sistem menghitung total volume angkatan (Total Volume Load = Set × Repetisi × Beban kg) dan menyajikan grafik konsistensi latihan untuk mendukung prinsip progressive overload.", bold_prefix="5. Analisis Peningkatan Kekuatan (Progressive Overload Tracker): ")

    # ------------------ SECTION 5 ------------------
    add_sec_heading("5. SPESIFIKASI TEKNOLOGI (TECH STACK)")
    t_stack = doc.add_table(rows=8, cols=3)
    stack_headers = ["Komponen Sistem", "Pilihan Teknologi", "Peran & Alasan Penggunaan"]
    stack_widths = [Inches(1.5), Inches(1.8), Inches(3.2)]
    stack_rows = [
        ["Backend & API Core", "PHP 8.2 & Laravel 12", "Framework backend teruji dengan arsitektur MVC, Eloquent ORM, middleware otorisasi, dan task scheduler pengingat otomatis."],
        ["Autentikasi API", "Laravel Sanctum", "Autentikasi berbasis Bearer Token yang aman, ringan, dan standar industri untuk melayani aplikasi mobile Flutter."],
        ["Frontend Web Staf", "Blade, Vite 7, Tailwind CSS v4", "Antarmuka kasir dan admin bertema Industrial Dark Brutalist yang responsif, cepat, dan berkontras tinggi."],
        ["Aplikasi Mobile", "Flutter 3.x (Dart 3.x)", "Pengembangan multiplatform (Android & iOS) dengan performa grafis tinggi (60-120 fps) untuk modul workout yang interaktif."],
        ["Basis Data Relasional", "MySQL / MariaDB", "Skema database ternormalisasi (3NF) dengan foreign key constraints ketat guna mencegah inkonsistensi data."],
        ["Payment Gateway", "Midtrans Snap API & Webhook", "Pemrosesan pembayaran digital resmi (QRIS, E-Wallet, VA Bank) dengan verifikasi otomatis secara real-time."],
        ["WhatsApp Gateway", "Evolution API / Gateway", "Pengiriman otomatis pengingat kedaluwarsa H-7 dan bukti transaksi digital langsung ke nomor WhatsApp member."]
    ]
    format_table_headers_and_cells(t_stack, stack_widths, stack_headers, stack_rows)

    # ------------------ SECTION 6 ------------------
    add_sec_heading("6. ANALISIS KEBUTUHAN SISTEM")
    add_sub_heading("6.1 Kebutuhan Fungsional (Functional Requirements)")
    t_kf = doc.add_table(rows=16, cols=3)
    kf_headers = ["Kode", "Modul Sistem", "Deskripsi Kebutuhan Fungsional"]
    kf_widths = [Inches(0.8), Inches(1.8), Inches(3.9)]
    kf_rows = [
        ["KF-01", "Autentikasi Multi-Role", "Sistem menyediakan autentikasi peran terisolasi: Master Admin (Owner), Admin Gym, Kasir, dan Member."],
        ["KF-02", "Pusat Informasi Resmi", "Sistem menyajikan katalog paket membership, regulasi, fasilitas, dan pengumuman resmi WARGYM."],
        ["KF-03", "Presensi Digital QR", "Member dapat menampilkan kode QR digital di mobile untuk dipindai saat tiba di gym secara nir-kartu."],
        ["KF-04", "Validasi Presensi Staf", "Kasir/Admin dapat memvalidasi presensi presensi dan status keaktifan paket member secara instan."],
        ["KF-05", "Point of Sale (POS) Kasir", "Kasir dapat melayani transaksi paket member, daily pass tamu, penjualan suplemen, dan cetak struk nota."],
        ["KF-06", "Smart Stacking Logic", "Sistem menghitung masa aktif perpanjangan secara akumulatif kalender jika diperpanjang sebelum masa berlaku habis."],
        ["KF-07", "Payment Gateway Midtrans", "Member dapat membayar perpanjangan paket via QRIS/VA dan sistem mengupdate status via webhook otomatis."],
        ["KF-08", "Katalog Panduan Gerakan", "Member dapat mengakses visualisasi bentuk gerakan fitness dan keterangan otot target per kategori tubuh."],
        ["KF-09", "Penyusun Routine Latihan", "Member dapat membuat jadwal dan template program latihan mandiri (custom routine)."],
        ["KF-10", "Live Workout Logger", "Member dapat mencatat set, beban angkatan (kg), repetisi, dan mengaktifkan rest timer otomatis antar-set."],
        ["KF-11", "Analisis Volume Latihan", "Sistem merekam total beban angkatan kumulatif dan menampilkan grafik progres latihan member."],
        ["KF-12", "WhatsApp Reminder Engine", "Sistem mengirimkan notifikasi pengingat otomatis saat masa aktif member tersisa 7 hari, 3 hari, dan hari-H."],
        ["KF-13", "Biaya Operasional", "Admin dapat menginput pos-pos pengeluaran operasional gym untuk pembukuan laba-rugi."],
        ["KF-14", "Laporan & Rekapitulasi", "Sistem menyajikan rekap kas kasir harian, laporan laba-rugi, dan ekspor laporan ke Excel dan PDF."],
        ["KF-15", "Manajemen Akun Staf", "Master Admin memiliki hak eksklusif mengelola akun login kasir dan staf gym."]
    ]
    format_table_headers_and_cells(t_kf, kf_widths, kf_headers, kf_rows)

    add_sub_heading("6.2 Kebutuhan Non-Fungsional (Non-Functional Requirements)")
    add_bullet("Password dienkripsi menggunakan hashing Bcrypt. Komunikasi API mobile diamankan dengan Laravel Sanctum Bearer Token. Transaksi Midtrans divalidasi dengan signature key kriptografis.", bold_prefix="1. Keamanan (Security): ")
    add_bullet("Waktu respon pemindaian QR presensi dan pembuatan invoice kasir di bawah 2 detik. Antarmuka aplikasi mobile berjalan mulus pada 60 fps.", bold_prefix="2. Performa (Performance): ")
    add_bullet("Seluruh transaksi keuangan dan penambahan masa aktif dijalankan dalam mekanisme ACID Database Transactions untuk menjamin keutuhan data.", bold_prefix="3. Keandalan (Reliability): ")
    add_bullet("Antarmuka kasir dirancang dengan gaya Industrial Brutalist kontras tinggi untuk kenyamanan mata staf kasir, serta antarmuka mobile ramah jempol (thumb-friendly).", bold_prefix="4. Kemudahan Penggunaan (Usability): ")

    # ------------------ SECTION 7 ------------------
    add_sec_heading("7. ALUR SISTEM DAN WORKFLOW UTAMA")
    add_sub_heading("7.1 Alur Presensi QR Code Mandiri")
    add_p(
        "1. Member membuka aplikasi mobile WARGYM dan menampilkan kartu digital berisikan QR Code presensi.\n"
        "2. Member mengarahkan QR Code ke perangkat pemindai (scanner/tablet) di meja resepsionis.\n"
        "3. Sistem web backend memverifikasi checkin_code dan memeriksa kolom expires_at member di database MySQL.\n"
        "4. Jika masa aktif masih berlaku, sistem mencatat riwayat presensi ke tabel 'checkins' dan menampilkan status BERHASIL beserta sisa hari aktif.\n"
        "5. Jika masa aktif telah habis, sistem menolak presensi dan mengarahkan member untuk melakukan perpanjangan paket."
    )

    add_sub_heading("7.2 Alur Perpanjangan Online & Smart Stacking Logic")
    add_p(
        "1. Member memilih paket membership di aplikasi mobile dan menekan tombol checkout.\n"
        "2. Server Laravel meminta Snap Token ke server Midtrans Payment Gateway.\n"
        "3. Member menyelesaikan pembayaran via QRIS atau Virtual Account pada antarmuka Snap.\n"
        "4. Midtrans mengirimkan notifikasi HTTP POST (Webhook) ke server Laravel.\n"
        "5. Server memvalidasi signature key Midtrans dan menjalankan aturan STACKING:\n"
        "   - Apabila member masih aktif: Tanggal Kedaluwarsa Baru = Tanggal Kedaluwarsa Lama + Durasi Paket.\n"
        "   - Apabila member sudah kedaluwarsa: Tanggal Kedaluwarsa Baru = Tanggal Hari Ini + Durasi Paket.\n"
        "6. Sistem mengirimkan pesan konfirmasi sukses dan invoice digital ke WhatsApp member secara otomatis."
    )

    add_sub_heading("7.3 Alur Sesi Modul Asisten Latihan Mandiri")
    add_p(
        "1. Member membuka tab Workout di aplikasi mobile dan memilih routine atau latihan bebas.\n"
        "2. Member memilih gerakan target otot dan dapat membuka panduan visual form gerakan bila diperlukan.\n"
        "3. Saat melakukan latihan, member mencatat berat beban (kg) dan repetisi yang diselesaikan pada set berjalan.\n"
        "4. Menekan tombol centang set langsung mengaktifkan timer istirahat otomatis (misal 60 detik).\n"
        "5. Sesi diakhiri dengan kalkulasi total beban angkatan (volume load) yang tersimpan ke rekam jejak progres latihan member."
    )

    # ------------------ SECTION 8 ------------------
    add_sec_heading("8. ROLE PENGGUNA DAN MATRIKS HAK AKSES")
    t_roles = doc.add_table(rows=11, cols=5)
    r_headers = ["Fitur / Modul Sistem", "Master Admin", "Admin Gym", "Kasir", "Member Mobile"]
    r_widths = [Inches(2.5), Inches(1.0), Inches(1.0), Inches(1.0), Inches(1.0)]
    r_rows = [
        ["Manajemen Akun Staf", "Penuh", "Tidak", "Tidak", "Tidak"],
        ["Laporan Keuangan & Omzet", "Penuh", "Tidak", "Tidak", "Tidak"],
        ["Ekspor Data (Excel/PDF)", "Penuh", "Penuh", "Tidak", "Tidak"],
        ["Master Paket & Katalog Produk", "Penuh", "Penuh", "Lihat", "Tidak"],
        ["Pencatatan Biaya Operasional", "Penuh", "Penuh", "Tidak", "Tidak"],
        ["Kirim Pengumuman & WhatsApp", "Penuh", "Penuh", "Tidak", "Tidak"],
        ["Transaksi Kasir POS", "Penuh", "Penuh", "Penuh", "Tidak"],
        ["Validasi Presensi QR", "Penuh", "Penuh", "Penuh", "Tidak"],
        ["Kartu Presensi QR Digital", "Tidak", "Tidak", "Tidak", "Penuh"],
        ["Checkout Midtrans & Workout", "Tidak", "Tidak", "Tidak", "Penuh"]
    ]
    format_table_headers_and_cells(t_roles, r_widths, r_headers, r_rows)

    # ------------------ SECTION 9 ------------------
    add_sec_heading("9. LAPORAN PROGRES PENGEMBANGAN SISTEM SAAT INI")
    add_p(
        "Pengembangan sistem berbasis basis kode proyek saat ini telah mencapai tingkat kesiapan sekitar 75% secara keseluruhan "
        "dengan rincian pencapaian sebagai berikut:"
    )
    t_prog = doc.add_table(rows=10, cols=3)
    p_headers = ["Komponen Sistem", "Status Progres", "Keterangan Implementasi Riil"]
    p_widths = [Inches(2.0), Inches(1.3), Inches(3.2)]
    p_rows = [
        ["Database Normalisasi (3NF)", "100% (Selesai)", "Seluruh tabel (members, plans, subscriptions, transactions, checkins, products, categories, expenses) ternormalisasi bersih dengan FK."],
        ["Web Kasir (Point of Sale)", "100% (Selesai)", "Transaksi membership, tiket tamu harian, penjualan suplemen, kalkulasi kembalian, dan cetak struk kasir selesai."],
        ["Presensi QR Check-in", "100% (Selesai)", "Validasi presensi scanner dan pengecekan status kedaluwarsa berfungsi penuh."],
        ["Midtrans Snap & Webhook", "95% (Selesai)", "Controller checkout dan webhook payment verification sudah terpasang di Laravel backend."],
        ["Logika Stacking Masa Aktif", "100% (Selesai)", "Algoritma penambahan durasi kalender bertumpuk telah terintegrasi di modul pembayaran."],
        ["WhatsApp Gateway Reminder", "90% (Selesai)", "Modul notifikasi otomatis siap terhubung ke engine Evolution API untuk reminder H-7."],
        ["Laporan Finansial & Ekspor", "100% (Selesai)", "Dashboard rekap kasir harian dan ekspor laporan berkala ke Excel/PDF telah aktif."],
        ["Aplikasi Mobile (Flutter)", "45% (Dalam Pengerjaan)", "Arsitektur client, auth Sanctum, dan navigasi tab bawah sudah siap."],
        ["Modul Asisten Latihan Mandiri", "50% (Dalam Pengerjaan)", "Katalog gerakan otot dan form input logger (set, kg, reps, rest timer) sedang tahap penyelesaian di sisi mobile."]
    ]
    format_table_headers_and_cells(t_prog, p_widths, p_headers, p_rows)

    # ------------------ SECTION 10 ------------------
    add_sec_heading("10. KEUNGGULAN SISTEM DAN MATRIKS KOMPARASI KOMPETITOR")
    add_p(
        "Untuk membuktikan orisinalitas dan keunggulan sistem dibandingkan solusi yang ada di pasar kebugaran, "
        "berikut adalah tabel matriks komparasi objektif:"
    )
    t_comp = doc.add_table(rows=7, cols=5)
    c_headers = ["Fitur Kebutuhan", "Medsos Gym", "Software Komersial", "Tracker Global", "Platform WARGYM"]
    c_widths = [Inches(1.8), Inches(1.1), Inches(1.2), Inches(1.1), Inches(1.3)]
    c_rows = [
        ["Pusat Info Terstruktur", "Tertimbun postingan", "Terbatas", "Tidak ada", "Pusat Info Resmi"],
        ["Presensi Digital", "Kartu fisik", "Alat mahal", "Tidak bisa", "QR Ponsel Nir-Kartu"],
        ["Perpanjangan Membership", "Chat admin transfer", "Langganan Kartu Kredit", "Tidak ada", "Midtrans + Stacking"],
        ["Panduan Gerakan Visual", "Cari YouTube sendiri", "Tidak ada", "Ada", "Terintegrasi di 1 App"],
        ["Pencatat Beban & Timer", "Buku manual / Notes", "Tidak ada", "Ada, terisolasi", "Modul Asisten Mandiri"],
        ["Biaya bagi Gym Lokal", "Gratis tapi pasif", "Jutaan/bulan (USD)", "Freemium mandiri", "Mandiri Milik Sendiri"]
    ]
    format_table_headers_and_cells(t_comp, c_widths, c_headers, c_rows)

    # ------------------ SECTION 11 ------------------
    add_sec_heading("11. STRATEGI PERTAHANAN JUDUL DI HADAPAN DOSEN PENGUJI")
    add_sub_heading("11.1 Argumen Inti (Jawaban 'Elevator Pitch')")
    add_p(
        "\"Betul Bapak/Ibu Dosen Penguji, di pasar memang sudah ada aplikasi gym. Namun saat ini industri terbelah menjadi dua: "
        "aplikasi kasir gym komersial yang mahal dan tidak peduli dengan latihan member, serta aplikasi tracker latihan global "
        "yang terisolasi dari tempat gym. Member akhirnya mengalami App Fatigue karena harus memakai 4-5 media berbeda. "
        "Keunggulan utama sistem WARGYM adalah menyatukan seluruh siklus hidup member ke dalam Platform All-in-One: dari pusat informasi resmi, "
        "presensi QR nir-kartu, transaksi instan, hingga asisten latihan mandiri dalam satu aplikasi saja.\"",
        bold_prefix="Pernyataan Pembuka: ", italic=True
    )

    add_sub_heading("11.2 Empat Pilar Pertahanan Akademik")
    add_bullet("Member tidak perlu lagi membuka Instagram untuk jadwal, membawa kartu plastik untuk check-in, membuka YouTube untuk panduan form, dan membuka Notes untuk catat repetisi. Cukup 1 aplikasi WARGYM untuk semua kebutuhan gym.", bold_prefix="1. Mengatasi App Fatigue: ")
    add_bullet("Pada aplikasi mandiri seperti Hevy, gym tempat member berlatih tidak mendapatkan nilai tambah bisnis. Di sistem WARGYM, fitur latihan menjadi instrumen retensi agar member betah dan terus memperpanjang membership.", bold_prefix="2. Menopang Retensi Bisnis Mitra: ")
    add_bullet("Sistem ini memiliki Smart Stacking Logic (durasi baru disambung dari tanggal akhir paket lama), integrasi QRIS/VA Midtrans, dan notifikasi WhatsApp lokal.", bold_prefix="3. Custom Business Logic Khas Gym Lokal: ")
    add_bullet("Menghindarkan mitra WARGYM dari biaya sewa software luar negeri yang mengenakan tarif bulanan puluhan juta rupiah dalam mata uang asing.", bold_prefix="4. Efisiensi Biaya Operasional (Cost-Effective): ")

    # ------------------ SECTION 12 ------------------
    add_sec_heading("12. MATERI SLIDE PRESENTASI UJIAN KELAYAKAN D-III MI PSDKU")
    add_p("Berikut adalah butir-butir teks yang telah dipetakan secara presisi ke dalam 5 slide template resmi PPT:")
    
    add_sub_heading("Slide 1 — JUDUL & PENULIS")
    add_bullet("Rancang Bangun Platform Layanan Gym Terintegrasi (Web & Mobile) dengan Modul Asisten Latihan Mandiri (Studi Kasus: WARGYM)", bold_prefix="Judul: ")
    add_bullet("[Nama Mahasiswa] / [NIM]", bold_prefix="Penulis: ")
    add_bullet("Diploma III Manajemen Informatika PSDKU", bold_prefix="Program Studi: ")

    add_sub_heading("Slide 2 — PERMASALAHAN (Poin-Poin Penting)")
    add_bullet("Media sosial lebih difokuskan untuk ruang promosi dan kegiatan tempat, sehingga info tarif resmi, jam operasional, dan status keanggotaan tertimbun postingan.", bold_prefix="1. Sulitnya mencari pusat informasi resmi gym: ")
    add_bullet("Kartu membership fisik mudah hilang, rusak, atau tertinggal, yang memicu antrean verifikasi manual di meja kasir pada jam sibuk.", bold_prefix="2. Presensi manual berbasis kartu fisik: ")
    add_bullet("Pemula kesulitan mempelajari postur gerakan sesuai otot target dan tidak memiliki media praktis untuk mencatat beban bertahap (progressive overload) tanpa sewa personal trainer mahal.", bold_prefix="3. Ketiadaan tool mandiri bagi pemula dalam 1 aplikasi: ")

    add_sub_heading("Slide 3 — IDE PENYELESAIAN MASALAH (Peta Konsep All-in-One)")
    add_bullet("Rujukan terstruktur untuk paket membership, jadwal buka, dan regulasi gym.", bold_prefix="• Pusat Informasi Resmi: ")
    add_bullet("Check-in mandiri nir-kartu langsung dari layar ponsel pintar.", bold_prefix="• Presensi Digital QR Code: ")
    add_bullet("Panduan visual gerakan per otot target, live workout logger (set, kg, reps), dan rest timer otomatis dalam 1 genggaman.", bold_prefix="• Modul Asisten Latihan Mandiri: ")
    add_bullet("POS suplemen & daily pass, checkout Midtrans otomatis, dan logika perpanjangan bertumpuk (stacking).", bold_prefix="• Web Kasir & Manajemen: ")

    add_sub_heading("Slide 4 — ANALISIS KEBUTUHAN FUNGSIONAL")
    add_bullet("Akses info resmi, kartu QR presensi, checkout Midtrans online, katalog gerakan visual, live workout logger, rest timer otomatis, grafik riwayat latihan.", bold_prefix="• Modul Member (Mobile): ")
    add_bullet("Autentikasi multi-role, kasir POS suplemen, scanner presensi QR, manajemen paket membership, laporan rekap kasir & ekspor data Excel/PDF.", bold_prefix="• Modul Kasir & Admin (Web): ")

    add_sub_heading("Slide 5 — ANALISIS KEBUTUHAN NON-FUNGSIONAL")
    add_bullet("Hashing Bcrypt, Bearer Token Laravel Sanctum, verifikasi kriptografis signature Midtrans.", bold_prefix="• Keamanan: ")
    add_bullet("Waktu respon presensi QR dan invoice kasir < 2 detik, tampilan mobile responsif 60 fps.", bold_prefix="• Performa: ")
    add_bullet("Integritas data pembayaran dan penambahan masa aktif dengan ACID Database Transactions.", bold_prefix="• Keandalan: ")
    add_bullet("Antarmuka kontras tinggi (Industrial Brutalist) untuk kasir dan navigasi mobile intuitif satu tangan.", bold_prefix="• Usabilitas: ")

    doc.save(filename)
    print(f"[SUCCESS] Word document saved to: {filename}")

def build_pptx(template_name="2023.TEMPLATE UJIAN KELAYAKAN D-III MI PSDKU 2023 (1).pptx", output_name="PRESENTASI_KELAYAKAN_JUDUL_WARGYM.pptx"):
    prs = Presentation(template_name)
    
    # Slide 1: JUDUL & PENULIS
    s1 = prs.slides[0]
    for shape in s1.shapes:
        if shape.has_text_frame:
            tf = shape.text_frame
            txt = shape.text.strip()
            if "JUDUL" in txt:
                tf.text = "RANCANG BANGUN PLATFORM LAYANAN GYM TERINTEGRASI\n(WEB & MOBILE) DENGAN MODUL ASISTEN LATIHAN MANDIRI\n(STUDI KASUS: WARGYM)"
                for p in tf.paragraphs:
                    p.font.size = PptPt(22)
                    p.font.bold = True
            elif "PENULIS" in txt:
                tf.text = "Disusun Oleh:\n[NAMA MAHASISWA] - NIM: [NIM]\n\nProgram Studi D-III Manajemen Informatika (PSDKU)\nDepartemen Ilmu Komputer dan Manajemen Informatika\n2026"
                for p in tf.paragraphs:
                    p.font.size = PptPt(14)

    # Slide 2: PERMASALAHAN
    s2 = prs.slides[1]
    for shape in s2.shapes:
        if shape.has_text_frame:
            txt = shape.text.strip()
            if "Buat dalam bentuk poin" in txt or "poin-poin" in txt:
                tf = shape.text_frame
                tf.text = (
                    "1. Sulitnya Mencari Pusat Informasi Operasional Gym\n"
                    "   • Medsos (IG/TikTok) lebih ke ruang promosi dan suasana tempat.\n"
                    "   • Info tarif resmi, jadwal buka, dan status membership tertimbun postingan.\n\n"
                    "2. Presensi Manual Berbasis Kartu Fisik\n"
                    "   • Kartu fisik mudah hilang, rusak, atau tertinggal di rumah.\n"
                    "   • Verifikasi manual menimbulkan antrean panjang di kasir jam sibuk.\n\n"
                    "3. Ketiadaan Tool Mandiri bagi Pemula dalam 1 Aplikasi\n"
                    "   • Pemula bingung bentuk gerakan (form) sesuai otot target tanpa sewa PT.\n"
                    "   • Tidak ada alat praktis mencatat beban bertahap (progressive overload).\n"
                    "   • Terpaksa berpindah-pindah media (YouTube, Notes, WA) secara tidak efisien."
                )
                for p in tf.paragraphs:
                    p.font.size = PptPt(13)

    # Slide 3: IDE PENYELESAIAN MASALAH
    s3 = prs.slides[2]
    for shape in s3.shapes:
        if shape.has_text_frame:
            txt = shape.text.strip()
            if "peta konsep" in txt.lower():
                tf = shape.text_frame
                tf.text = (
                    "SOLUSI PLATFORM ALL-IN-ONE (WEB & MOBILE):\n\n"
                    "1. Pusat Informasi Resmi Terpusat:\n"
                    "   • Rujukan pasti tarif paket, jadwal operasional, dan pengumuman resmi.\n\n"
                    "2. Presensi Digital QR Code Nir-Kartu:\n"
                    "   • Check-in mandiri langsung via ponsel tanpa takut kartu tertinggal.\n\n"
                    "3. Modul Asisten Latihan Mandiri (Core Innovation):\n"
                    "   • Panduan visual form gerakan sesuai kelompok otot target.\n"
                    "   • Live workout logger: catat set, beban (kg), repetisi, & rest timer otomatis.\n\n"
                    "4. Web Manajemen & Kasir Terpadu:\n"
                    "   • POS kasir, pembayaran online Midtrans, & Smart Stacking Logic."
                )
                for p in tf.paragraphs:
                    p.font.size = PptPt(13)

    # Slide 4: KEBUTUHAN FUNGSIONAL
    s4 = prs.slides[3]
    for shape in s4.shapes:
        if shape.has_text_frame:
            txt = shape.text.strip()
            if "kebutuhan fungsional" in txt.lower():
                tf = shape.text_frame
                tf.text = (
                    "A. Modul Aplikasi Mobile (Member):\n"
                    "   • Akses Pusat Informasi Resmi WARGYM (Paket, Jadwal, Fasilitas)\n"
                    "   • Kartu Presensi Digital QR Code (Check-in mandiri nir-kartu)\n"
                    "   • Checkout Perpanjangan Online via Midtrans (QRIS/VA) & Stacking Logic\n"
                    "   • Katalog Master Gerakan Visual & Target Otot\n"
                    "   • Live Workout Logger (Set, Beban kg, Repetisi, Auto Rest Timer)\n"
                    "   • Analisis Volume Latihan & Rekam Jejak Progressive Overload\n\n"
                    "B. Modul Web Operasional (Kasir & Admin):\n"
                    "   • Autentikasi Multi-Role (Master Admin, Admin, Kasir)\n"
                    "   • Kasir POS (Suplemen, Minuman, Tiket Tamu Daily Pass, Cetak Struk)\n"
                    "   • Scanner & Verifikasi Presensi QR Member Real-time\n"
                    "   • Laporan Keuangan Harian Kasir & Ekspor Laba-Rugi (Excel/PDF)"
                )
                for p in tf.paragraphs:
                    p.font.size = PptPt(12)

    # Slide 5: KEBUTUHAN NON-FUNGSIONAL
    s5 = prs.slides[4]
    for shape in s5.shapes:
        if shape.has_text_frame:
            txt = shape.text.strip()
            if "kebutuhan nonfungsional" in txt.lower():
                tf = shape.text_frame
                tf.text = (
                    "1. Keamanan (Security):\n"
                    "   • Enkripsi kata sandi menggunakan hashing Bcrypt / Argon2id.\n"
                    "   • Autentikasi API mobile diamankan dengan Laravel Sanctum Bearer Token.\n"
                    "   • Verifikasi kriptografis signature key pada webhook Midtrans.\n\n"
                    "2. Performa (Performance):\n"
                    "   • Waktu respon scanner presensi dan invoice kasir < 2 detik.\n"
                    "   • Antarmuka aplikasi mobile Flutter berjalan mulus pada 60 fps.\n\n"
                    "3. Keandalan (Reliability):\n"
                    "   • Mutasi transaksi dan masa aktif menggunakan ACID Database Transactions.\n\n"
                    "4. Kemudahan Penggunaan (Usability):\n"
                    "   • Web kasir Industrial Brutalist Dark kontras tinggi ramah staf kasir.\n"
                    "   • Navigasi mobile responsif ramah satu tangan (thumb-friendly)."
                )
                for p in tf.paragraphs:
                    p.font.size = PptPt(12)

    prs.save(output_name)
    print(f"[SUCCESS] PowerPoint presentation saved to: {output_name}")

if __name__ == "__main__":
    build_docx("DOKUMEN_KELAYAKAN_JUDUL_WARGYM.docx")
    build_pptx("2023.TEMPLATE UJIAN KELAYAKAN D-III MI PSDKU 2023 (1).pptx", "PRESENTASI_KELAYAKAN_JUDUL_WARGYM.pptx")
