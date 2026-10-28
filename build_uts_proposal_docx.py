import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn
import os

def create_proposal_docx(filename="UTS Proposal LA_Mohammad Yusril Musyafak_Final.docx"):
    doc = docx.Document()
    
    # ── Page Margins (Mirroring Standard Proposal: Top/Bottom 2.54cm, Left 3cm, Right 2.5cm)
    for section in doc.sections:
        section.top_margin = Inches(1.0)
        section.bottom_margin = Inches(1.0)
        section.left_margin = Inches(1.18) # 3 cm
        section.right_margin = Inches(1.0) # 2.54 cm
        section.page_width = Inches(8.27)  # A4
        section.page_height = Inches(11.69) # A4

    # ── Styling Helpers
    def set_font(run, name="Times New Roman", size_pt=12, bold=False, italic=False, color_rgb=(0,0,0)):
        run.font.name = name
        run.font.size = Pt(size_pt)
        run.bold = bold
        run.italic = italic
        run.font.color.rgb = RGBColor(*color_rgb)

    def add_p(doc, text="", align=WD_ALIGN_PARAGRAPH.JUSTIFY, space_before=0, space_after=4, line_spacing=1.15, bold=False, italic=False, font_size=12):
        p = doc.add_paragraph()
        p.alignment = align
        p.paragraph_format.space_before = Pt(space_before)
        p.paragraph_format.space_after = Pt(space_after)
        p.paragraph_format.line_spacing = line_spacing
        if text:
            run = p.add_run(text)
            set_font(run, size_pt=font_size, bold=bold, italic=italic)
        return p

    def style_cell(cell, bg_color=None, border_color="000000", border_sz="4", pad_top=100, pad_bottom=100, pad_left=150, pad_right=150):
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

    # ── HEADER IDENTITAS
    p_hdr1 = add_p(doc, "LEMBAR JAWABAN UJIAN TENGAH SEMESTER", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=2, bold=True, font_size=12)
    p_hdr2 = add_p(doc, "PROPOSAL LAPORAN AKHIR", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=0, space_after=12, bold=True, font_size=12)

    # Identity table without borders
    id_table = doc.add_table(rows=3, cols=3)
    id_table.alignment = WD_TABLE_ALIGNMENT.LEFT
    id_data = [
        ("Nama", ":", "Mohammad Yusril Musyafak"),
        ("NIM", ":", "243107030030"),
        ("Kelas / Prodi", ":", "3F / D-III Manajemen Informatika (PSDKU Kediri)"),
    ]
    for r_idx, (c1, c2, c3) in enumerate(id_data):
        row = id_table.rows[r_idx]
        for c_idx, txt in enumerate([c1, c2, c3]):
            cell = row.cells[c_idx]
            cp = cell.paragraphs[0]
            cp.paragraph_format.space_before = Pt(0)
            cp.paragraph_format.space_after = Pt(2)
            cp.paragraph_format.line_spacing = 1.15
            run = cp.add_run(txt)
            set_font(run, size_pt=11, bold=(c_idx == 0))
    
    # Column widths for identity table
    for row in id_table.rows:
        row.cells[0].width = Inches(1.3)
        row.cells[1].width = Inches(0.2)
        row.cells[2].width = Inches(4.5)

    add_p(doc, "", space_before=0, space_after=8)

    # ── JUDUL USULAN PENELITIAN
    p_title = add_p(doc, "Rancang Bangun Sistem Informasi Manajemen Layanan Gym Terintegrasi Berbasis Web dengan Payment Gateway Midtrans dan Presensi QR Code\n(Studi Kasus: WARGYM Jombang)", 
                    align=WD_ALIGN_PARAGRAPH.CENTER, space_before=6, space_after=14, bold=True, font_size=12)

    # ══════════════════════════════════════════════════════════════════════════
    # 1. DESKRIPSI MASALAH YANG MELATARBELAKANGI PENELITIAN
    # ══════════════════════════════════════════════════════════════════════════
    add_p(doc, "1. Deskripsi Masalah yang Melatarbelakangi Penelitian", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=10, space_after=4, bold=True, font_size=12)
    
    add_p(doc, "WARGYM Jombang (Warung Gym) merupakan unit usaha penyedia fasilitas dan jasa kebugaran fisik yang berlokasi di Kabupaten Jombang, Jawa Timur. Usaha ini melayani masyarakat yang membutuhkan sarana berolahraga, pembentukan postur tubuh, dan peningkatan kebugaran jasmani. Aktivitas operasional harian WARGYM mencakup pendaftaran anggota baru, penerbitan kartu keanggotaan berkala (membership paket bulanan, 3 bulan, 6 bulan, hingga tahunan), pelayanan tiket harian bagi pengunjung lepas (daily pass), penyediaan fasilitas latihan beban (free weights dan mesin resistensi), layanan bimbingan instruktur kebugaran (Personal Trainer), serta penjualan produk pendukung latihan seperti suplemen makanan, minuman berprotein, dan perlengkapan olahraga. Setiap layanan memiliki tata kelola administrasi dan pencatatan yang harus terpantau secara konsisten oleh Pemilik Gym (Owner/Master Admin), Staf Administrasi, Kasir, maupun para Member itu sendiri.", space_before=0, space_after=6)

    add_p(doc, "Namun demikian, seluruh rangkaian operasional WARGYM saat ini masih diselenggarakan secara konvensional dan semi-manual, sehingga menimbulkan berbagai friksi operasional, inefisiensi alur kerja, dan potensi kerugian finansial. Berdasarkan hasil observasi langsung dan wawancara dengan manajemen serta staf di lapangan, teridentifikasi 5 (lima) masalah utama yang melatarbelakangi penelitian ini:", space_before=0, space_after=6)

    # 5 Masalah Utama di Lapangan
    masalah = [
        ("1. Fragmentasi Pencatatan Administrasi Kasir & Keuangan Konvensional (Risiko Human Error dan Hilangnya Data Transaksi):",
         "Pencatatan data registrasi member baru, perpanjangan masa aktif keanggotaan, tiket tamu harian (daily pass), penjualan produk suplemen, serta pengeluaran operasional masih dicatat di buku kas fisik atau lembar spreadsheet lokal yang tidak saling terhubung. Mekanisme ini sangat rentan terhadap kesalahan manusia (human error), seperti salah ketik nominal, nota transaksi tercecer atau hilang, dan selisih antara uang fisik di laci kasir dengan catatan buku. Akibatnya, pemilik gym kesulitan memperoleh rekapan kas harian yang valid serta laporan laba-rugi operasional secara real-time."),
        
        ("2. Presensi Manual Berbasis Kartu Fisik yang Menimbulkan Antrean dan Risiko Penyalahgunaan Keanggotaan:",
         "Proses presensi kehadiran member saat ini sepenuhnya mengandalkan kartu membership fisik konvensional. Kartu fisik memiliki kelemahan mendasar: mudah rusak, patah, atau sering tertinggal di rumah saat member hendak berlatih. Ketika kartu tertinggal, staf kasir harus mencari data keaktifan member secara manual di buku/file register, yang memicu antrean panjang di meja kasir pada jam sibuk (peak hours sore/malam pukul 16.00-21.00 WIB). Selain itu, sistem kartu fisik rawan disalahgunakan (dipinjamkan kepada orang lain yang bukan member), sehingga merugikan pihak gym secara finansial."),
        
        ("3. Keterbatasan Akses Pembayaran Digital & Rentannya Verifikasi Transfer Manual:",
         "Member yang ingin mendaftar atau memperpanjang paket membership diwajibkan datang langsung ke meja kasir untuk membayar tunai atau melakukan transfer manual antar-rekening bank lalu mengirimkan foto tangkapan layar bukti transfer ke kontak WhatsApp pengelola gym. Proses verifikasi transfer manual ini membebani staf karena harus membuka aplikasi mobile banking secara berulang-ulang, memperlambat proses aktivasi paket, dan sangat rentan terhadap tindak manipulasi bukti transfer digital palsu (fake payment proof)."),
        
        ("4. Ketiadaan Logika Perpanjangan Akumulatif (Smart Stacking) & Rendahnya Retensi Akibat Ketiadaan Pengingat:",
         "Perhitungan tanggal jatuh tempo perpanjangan membership secara manual sering kali menimbulkan sengketa antara member dan pengelola. Ketika member memperpanjang paket beberapa hari sebelum masa aktifnya habis, tanggal kedaluwarsa baru kerap dihitung sejak hari pembayaran dilakukan, sehingga memotong sisa hari aktif yang menjadi hak member. Di samping itu, ketiadaan sistem notifikasi pengingat otomatis menjelang masa kedaluwarsa (H-7 dan H-3) menyebabkan member lupa memperpanjang paket keanggotaan tepat waktu, yang berujung pada penurunan tingkat retensi member aktif (member churn rate)."),
        
        ("5. Ketiadaan Portal Mandiri (Self-Service) & Pusat Informasi Resmi Terstruktur:",
         "Informasi seputar operasional WARGYM (seperti jadwal jam buka, penyesuaian tarif paket, ketersediaan produk suplemen, fasilitas latihan, hingga regulasi gym) saat ini hanya disebarkan melalui media sosial (Instagram dan TikTok) yang sifatnya tidak terstruktur dan cepat tertimbun postingan promosi harian. Member maupun calon member tidak memiliki saluran mandiri (self-service portal) untuk memantau sisa masa aktif langganan, melihat riwayat transaksi pembayaran, mengakses kartu presensi digital, maupun menyampaikan kritik dan saran perbaikan fasilitas secara terpusat.")
    ]

    for title, desc in masalah:
        p_m = add_p(doc, space_before=2, space_after=4)
        run_t = p_m.add_run(title + " ")
        set_font(run_t, bold=True)
        run_d = p_m.add_run(desc)
        set_font(run_d, bold=False)

    # ══════════════════════════════════════════════════════════════════════════
    # 2. DESKRIPSI KEGIATAN PENELITIAN
    # ══════════════════════════════════════════════════════════════════════════
    add_p(doc, "2. Deskripsi Kegiatan Penelitian", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=10, space_after=4, bold=True, font_size=12)
    
    add_p(doc, "2.1. Analisis Penyelesaian Masalah", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=4, space_after=4, bold=True, font_size=12)
    add_p(doc, "Untuk mengatasi kelima permasalahan operasional utama di atas secara komprehensif, dirancang solusi berbasis sistem informasi terintegrasi yang menjawab masing-masing akar masalah secara tepat sasaran:", space_before=0, space_after=6)

    solusi = [
        ("1. Pengembangan Sistem Point of Sale (POS) Kasir Terpusat & Multi-Item Transaksional:",
         "Membangun modul web kasir POS modern yang mampu memproses berbagai transaksi operasional secara terpadu dalam satu antarmuka cepat: pendaftaran member baru, pembayaran paket membership berkala, penjualan tiket tamu harian (daily pass), serta penjualan produk suplemen multi-item (lengkap dengan kalkulasi kembalian otomatis dan pencetakan struk nota resmi). Sistem juga mengintegrasikan pencatatan pos biaya operasional harian (expense records) dan log kartu stok barang masuk-keluar guna menghasilkan rekap kas kasir harian serta laporan keuangan laba-rugi otomatis."),
        
        ("2. Presensi Digital Berbasis QR Code Cepat & Modul Self-Service Check-in:",
         "Menggantikan kartu fisik dengan kartu membership digital pada portal member yang dilengkapi QR Code / barcode unik terenkripsi. Pada meja resepsionis, staf kasir cukup memindai QR Code member melalui pemindai kamera/barcode scanner yang secara otomatis memvalidasi status keaktifan paket member dalam waktu kurang dari 2 detik. Selain itu, disediakan halaman Self-Service Check-in mandiri pada perangkat gym yang memungkinkan member memindai kode tanpa mengantre di kasir, serta dilengkapi foto profil member untuk verifikasi visual pencegahan peminjaman akun."),
        
        ("3. Integrasi Payment Gateway Midtrans (Snap API & Webhook Callback Idempoten):",
         "Menyediakan fasilitas pembayaran daring resmi melalui antarmuka Midtrans Snap API yang mendukung beragam metode pembayaran nontunai (QRIS instan, GoPay, ShopeePay, serta Virtual Account Bank BCA, BNI, BRI, Mandiri, Permata). Sistem menerapkan endpoint Webhook Callback idempoten di sisi server Laravel yang memverifikasi signature key kriptografis SHA-512, sehingga status pembayaran dan perpanjangan paket terverifikasi otomatis secara real-time tanpa perlu mengunggah bukti transfer manual."),
        
        ("4. Otomatisasi Logika Smart Stacking Masa Aktif & WhatsApp Reminder Engine:",
         "Mengimplementasikan algoritma akumulasi masa aktif bertumpuk (Smart Stacking Logic) pada basis data. Jika member melakukan perpanjangan saat masa aktif masih berjalan, tanggal kedaluwarsa baru secara otomatis dihitung dan disambungkan dari tanggal expires_at sebelumnya, bukan dari tanggal transaksi pembayaran. Selanjutnya, sistem dilengkapi background scheduler yang terintegrasi dengan WhatsApp Gateway API untuk mengirimkan pesan pengingat jatuh tempo secara otomatis kepada nomor WhatsApp member pada H-7, H-3, dan hari-H masa aktif berakhir."),
        
        ("5. Pembangunan Portal Member Terpadu (Member Self-Service & Company Profile):",
         "Membangun portal web responsif dengan tema Industrial Dark Brutalist yang menyajikan Company Profile resmi (jadwal operasional, katalog layanan, personal trainer, fasilitas, dan kontak lokasi gym) yang terstruktur dan mudah diakses publik. Di sisi lain, member yang terotentikasi mendapatkan akses ke dashboard pribadi untuk memantau sisa hari aktif, menampilkan kartu presensi digital ber-QR Code, melihat riwayat transaksi dan invoice pembayaran, melakukan checkout perpanjangan paket mandiri, serta mengirimkan feedback (kritik dan saran).")
    ]

    for title, desc in solusi:
        p_s = add_p(doc, space_before=2, space_after=4)
        run_t = p_s.add_run(title + " ")
        set_font(run_t, bold=True)
        run_d = p_s.add_run(desc)
        set_font(run_d, bold=False)

    # 2.2 Analisis Data Penelitian
    add_p(doc, "2.2. Analisis Data Penelitian", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=8, space_after=4, bold=True, font_size=12)
    add_p(doc, "Analisis data penelitian dilakukan untuk memetakan kebutuhan informasi, sumber perolehan data, dan struktur relasi entitas data yang diperlukan dalam pengembangan sistem manajemen gym WARGYM:", space_before=0, space_after=4)

    add_p(doc, "a. Sumber Data Primer: Data primer diperoleh melalui kegiatan observasi partisipatif langsung terhadap aktivitas operasional harian di lokasi WARGYM Jombang (mencakup alur pelayanan kasir, alur check-in member dan tamu di meja resepsionis, penanganan transaksi pembayaran tunai/nontunai, dan pengelolaan stok suplemen) serta wawancara mendalam (in-depth interview) dengan Pemilik Gym (Master Admin), Staf Administrasi, Kasir, dan sejumlah Member gym untuk menggali kendala nyata, aturan bisnis (business rules), dan ekspektasi fungsional sistem.", space_before=2, space_after=4)
    
    add_p(doc, "b. Sumber Data Sekunder: Data sekunder dikumpulkan melalui studi dokumentasi operasional internal WARGYM, meliputi formulir pendaftaran anggota fisik, buku catatan kasir harian, daftar penetapan harga paket membership, katalog produk suplemen beserta data persediaan awal, nota transaksi manual, dan arsip rekapitulasi pengeluaran kas operasional.", space_before=2, space_after=4)

    add_p(doc, "c. Struktur Data yang Dikumpulkan: Seluruh data yang berhasil dikumpulkan dianalisis dan distrukturkan ke dalam 7 (tujuh) kelompok entitas basis data relasional ternormalisasi (Third Normal Form / 3NF) pada Laravel Eloquent ORM:", space_before=2, space_after=4)

    data_groups = [
        ("1. Data Pengguna dan Hak Akses (users):", "Menyimpan data otentikasi login, username, nama lengkap, alamat email, role (master_admin, admin, cashier, member), dan hash kata sandi terenkripsi Bcrypt."),
        ("2. Data Member dan Langganan (members, membership_plans, membership_subscriptions, membership_renewal_requests):", "Menyimpan profil anggota, nomor telepon/WhatsApp, tanggal bergabung, tanggal kedaluwarsa (expires_at), kode unik check-in, foto profil, katalog paket membership (nama, durasi bulan, harga), riwayat langganan, dan permintaan perpanjangan."),
        ("3. Data Tamu Harian (daily_guests):", "Menyimpan pencatatan identitas tamu lepas non-member (nama lengkap, nomor telepon, jenis kunjungan reguler/event, dan waktu kunjungan)."),
        ("4. Data Inventaris Produk dan Pengeluaran (categories, products, product_stock_logs, expense_records):", "Menyimpan kategori barang, master suplemen/minuman (nama, brand, SKU, harga beli, harga jual, stok saat ini), log mutasi kartu stok (masuk, keluar, penyesuaian/adjustment), serta pencatatan pos biaya operasional gym."),
        ("5. Data Presensi Kehadiran (checkins):", "Menyimpan log riwayat kedatangan member atau daily guest, waktu presensi (checked_in_at), metode presensi (admin, qr_code, self_service), status verifikasi (verified, pending, rejected), dan identitas staf pemverifikasi."),
        ("6. Data Transaksi dan Pembayaran (transactions, transaction_items):", "Menyimpan nomor invoice unik, relasi member/guest, jenis transaksi (membership_payment, daily_pass, product_sale), rincian item produk yang dibeli, metode bayar (cash, qris, transfer), status pembayaran, nominal subtotal, diskon, total akhir, nominal tunai, uang kembalian, dan snap token Midtrans."),
        ("7. Data Komunikasi dan Interaksi (announcements, member_feedbacks, profile_photo_change_requests):", "Menyimpan publikasi informasi/pengumuman resmi gym (judul, isi, target member, status draft/published), pesan kritik dan saran member, serta pengajuan pembaruan foto profil member.")
    ]

    for dg_t, dg_d in data_groups:
        p_dg = add_p(doc, space_before=1, space_after=3)
        run_t = p_dg.add_run("   " + dg_t + " ")
        set_font(run_t, bold=True)
        run_d = p_dg.add_run(dg_d)
        set_font(run_d, bold=False)

    # 2.3 Analisis Kebutuhan Sistem
    add_p(doc, "2.3. Analisis Kebutuhan Sistem", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=8, space_after=4, bold=True, font_size=12)
    add_p(doc, "Analisis kebutuhan sistem dirumuskan untuk mendefinisikan batasan wewenang pengguna (aktor), kebutuhan fungsional (fitur utama sistem), dan kebutuhan non-fungsional (kualitas arsitektural) dari sistem informasi manajemen WARGYM.", space_before=0, space_after=6)

    # a. Kebutuhan Pengguna
    add_p(doc, "a. Kebutuhan Pengguna / Aktor Sistem", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=4, space_after=4, bold=True, font_size=12)
    add_p(doc, "Sistem dirancang untuk melayani 4 (empat) aktor manusia dan 2 (dua) aktor sistem eksternal dengan pembagian tanggung jawab terisolasi berbasis Role-Based Access Control (RBAC):", space_before=0, space_after=6)

    # Tabel 2.1 Aktor
    p_t21 = add_p(doc, "Tabel 2.1 Matriks Peran Pengguna dan Tanggung Jawab Sistem", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=4, bold=True, font_size=11)
    
    t_aktor = doc.add_table(rows=1, cols=3)
    t_aktor.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(t_aktor)
    
    hdr_cells = t_aktor.rows[0].cells
    hdr_titles = ["Peran Pengguna (Aktor)", "Tipe Aktor", "Deskripsi Tanggung Jawab dan Hak Akses Sistem"]
    for i, title in enumerate(hdr_titles):
        hdr_cells[i].paragraphs[0].text = title
        set_font(hdr_cells[i].paragraphs[0].runs[0], size_pt=10, bold=True)
        hdr_cells[i].paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        style_cell(hdr_cells[i], bg_color="E2E8F0")

    aktor_rows = [
        ("Master Admin (Owner)", "Pengguna Internal", "Memegang hak akses tertinggi; mengelola seluruh akun staf kasir/admin; memantau dashboard finansial real-time, omzet, laba-rugi operasional; serta mencetak dan mengekspor seluruh laporan (Excel/PDF)."),
        ("Admin Gym", "Pengguna Internal", "Mengelola master paket membership; mengelola data member; mengelola katalog suplemen dan kartu stok; mencatat pos pengeluaran operasional; mempublikasikan pengumuman; menyetujui foto profil member; serta memantau kritik/saran."),
        ("Kasir (Cashier)", "Pengguna Internal", "Melayani transaksi operasional harian di meja kasir (Point of Sale): pendaftaran paket member, perpanjangan, tiket harian daily pass, penjualan suplemen ber-SKU, cetak struk nota resmi, validasi presensi QR member, dan rekap kas kasir harian."),
        ("Member Gym", "Pengguna Eksternal", "Mengakses portal member; melihat informasi company profile, paket, fasilitas, dan jadwal gym; menampilkan kartu digital ber-QR Code; melakukan presensi mandiri (self-service); melihat riwayat transaksi; melakukan checkout perpanjangan online; dan mengirim kritik/saran."),
        ("Payment Gateway (Midtrans)", "Sistem Eksternal", "Menyediakan antarmuka pembayaran digital (Snap API) untuk QRIS dan Virtual Account serta mengirimkan payload notifikasi verifikasi status transaksi ke endpoint webhook sistem secara otomatis (server-to-server)."),
        ("WhatsApp Gateway", "Sistem Eksternal", "Menerima instruksi API pengiriman pesan otomatis dari sistem untuk mengirimkan notifikasi pengingat jatuh tempo masa aktif member (H-7, H-3, hari-H) dan bukti transaksi pembayaran langsung ke nomor WhatsApp member.")
    ]

    for r_idx, (c1, c2, c3) in enumerate(aktor_rows):
        row = t_aktor.add_row()
        cells = row.cells
        for col_idx, text in enumerate([c1, c2, c3]):
            p = cells[col_idx].paragraphs[0]
            p.text = text
            p.paragraph_format.space_before = Pt(2)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.line_spacing = 1.15
            set_font(p.runs[0], size_pt=10, bold=(col_idx == 0))
            if col_idx == 1:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            style_cell(cells[col_idx], bg_color="FFFFFF" if r_idx % 2 == 0 else "F8FAFC")

    # Column widths
    col_w_t21 = [Inches(1.8), Inches(1.3), Inches(3.4)]
    for row in t_aktor.rows:
        for i, w in enumerate(col_w_t21):
            row.cells[i].width = w

    add_p(doc, "", space_before=4, space_after=4)

    # b. Kebutuhan Fungsional
    add_p(doc, "b. Kebutuhan Fungsional Sistem (Functional Requirements)", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=6, space_after=4, bold=True, font_size=12)
    add_p(doc, "Kebutuhan fungsional merinci fungsi-fungsi komputasi spesifik yang wajib disediakan oleh sistem untuk mendukung tata kelola operasional gym WARGYM, sebagaimana dirangkum pada Tabel 2.2:", space_before=0, space_after=6)

    p_t22 = add_p(doc, "Tabel 2.2 Daftar Kode Kebutuhan Fungsional Sistem (Functional Requirements)", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=4, bold=True, font_size=11)

    t_fr = doc.add_table(rows=1, cols=3)
    t_fr.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(t_fr)

    hdr_fr = t_fr.rows[0].cells
    hdr_fr_titles = ["Kode FR", "Modul Sistem", "Deskripsi Kebutuhan Fungsional Sistem"]
    for i, title in enumerate(hdr_fr_titles):
        hdr_fr[i].paragraphs[0].text = title
        set_font(hdr_fr[i].paragraphs[0].runs[0], size_pt=10, bold=True)
        hdr_fr[i].paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        style_cell(hdr_fr[i], bg_color="E2E8F0")

    fr_data = [
        ("FR-01", "Autentikasi & Hak Akses", "Sistem menyediakan autentikasi sesi login/logout berbasis peran (RBAC) untuk mengisolasi hak akses Master Admin, Admin, Kasir, dan Member."),
        ("FR-02", "Pusat Informasi & Profil Gym", "Sistem menyajikan Company Profile resmi, katalog paket langganan, daftar instruktur Personal Trainer, foto fasilitas, jadwal operasional, dan lokasi Google Maps."),
        ("FR-03", "Kartu Digital & QR Presensi", "Sistem menerbitkan kartu anggota digital pada portal member yang dilengkapi kode QR presensi unik berbasis string terenkripsi untuk identifikasi tanpa kartu fisik."),
        ("FR-04", "Manajemen Master Member", "Admin dan Master Admin dapat mengelola data profil member (nama, no telepon, foto, tanggal bergabung, tanggal kedaluwarsa, dan status keanggotaan)."),
        ("FR-05", "Manajemen Paket Membership", "Admin dapat menambah, mengubah, dan menonaktifkan master paket membership (nama paket, durasi bulan, tarif harga, dan deskripsi ketentuan)."),
        ("FR-06", "Point of Sale (POS) Kasir", "Kasir dapat memproses transaksi pendaftaran paket member, perpanjangan, tiket tamu harian (daily pass), penjualan suplemen multi-item, dan mencetak struk nota resmi."),
        ("FR-07", "Smart Stacking Masa Aktif", "Sistem secara otomatis menerapkan penambahan masa aktif kalender bertumpuk jika perpanjangan dilakukan sebelum masa kedaluwarsa berakhir (dihitung dari expires_at)."),
        ("FR-08", "Pembayaran Online Midtrans", "Member dapat melakukan checkout perpanjangan paket via Midtrans Snap API dengan metode QRIS instan, GoPay, ShopeePay, dan Virtual Account Bank."),
        ("FR-09", "Verifikasi Webhook Callback", "Sistem memproses callback webhook Midtrans secara otomatis (server-to-server) dengan validasi signature hash SHA-512 dan idempotent check untuk aktivasi paket."),
        ("FR-10", "Validasi Presensi Kasir", "Kasir dapat memvalidasi kedatangan member melalui pemindaian QR Code dan sistem secara instan menampilkan status keaktifan paket, foto profil, serta mencatat log hadir."),
        ("FR-11", "Self-Service Check-in", "Member dapat melakukan check-in mandiri melalui perangkat gym dengan memindai kode QR tanpa mengantre di kasir, tercatat sebagai status verified."),
        ("FR-12", "Inventaris Produk & Kartu Stok", "Admin dapat mengelola katalog suplemen, kategori, harga jual/beli, SKU, serta mencatat riwayat mutasi stok (barang masuk, keluar, dan penyesuaian opname)."),
        ("FR-13", "Pencatatan Biaya Operasional", "Admin dan Master Admin dapat mencatat pos pengeluaran operasional gym (listrik, air, perawatan alat, kebersihan) untuk pembukuan laba-rugi yang akurat."),
        ("FR-14", "Pengumuman & Feedback", "Admin dapat mempublikasikan pengumuman resmi ke portal member dan menampung serta menindaklanjuti formulir kritik/saran dari member."),
        ("FR-15", "WhatsApp Gateway Reminder", "Sistem secara otomatis mengirimkan notifikasi pengingat kedaluwarsa ke nomor WhatsApp member pada H-7, H-3, dan hari-H, serta mengirimkan bukti bayar digital."),
        ("FR-16", "Laporan Rekapitulasi & Ekspor", "Master Admin dapat melihat dashboard omzet, rekap kas harian kasir, statistik kunjungan, laporan laba-rugi, serta mengekspor data ke format Excel dan PDF.")
    ]

    for r_idx, (c1, c2, c3) in enumerate(fr_data):
        row = t_fr.add_row()
        cells = row.cells
        for col_idx, text in enumerate([c1, c2, c3]):
            p = cells[col_idx].paragraphs[0]
            p.text = text
            p.paragraph_format.space_before = Pt(2)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.line_spacing = 1.15
            set_font(p.runs[0], size_pt=9.5, bold=(col_idx == 0))
            if col_idx == 0:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            style_cell(cells[col_idx], bg_color="FFFFFF" if r_idx % 2 == 0 else "F8FAFC")

    col_w_t22 = [Inches(1.0), Inches(1.8), Inches(3.7)]
    for row in t_fr.rows:
        for i, w in enumerate(col_w_t22):
            row.cells[i].width = w

    add_p(doc, "", space_before=4, space_after=4)

    # c. Kebutuhan Non-Fungsional
    add_p(doc, "c. Kebutuhan Non-Fungsional Sistem (Non-Functional Requirements)", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=6, space_after=4, bold=True, font_size=12)
    add_p(doc, "Kebutuhan non-fungsional mendefinisikan aspek kualitatif, keandalan, dan batasan teknis arsitektur sistem:", space_before=0, space_after=4)

    nfr_items = [
        ("1. Keamanan Data (Security):", "Seluruh password pengguna disimpan dalam bentuk hash terenkripsi menggunakan algoritma Bcrypt (cost factor 12). Seluruh rute dilindungi oleh middleware otentikasi sesi dan pencegahan Cross-Site Request Forgery (CSRF). Endpoint webhook pembayaran Midtrans divalidasi dengan membandingkan signature key kriptografis SHA-512."),
        ("2. Performa dan Waktu Respons (Performance):", "Waktu respons sistem dalam memverifikasi presensi QR Code dan menerbitkan invoice pembayaran di meja kasir ditargetkan berada di bawah 2 detik. Query basis data dioptimasi menggunakan mekanisme eager loading pada relasi Eloquent untuk mencegah masalah N+1 query."),
        ("3. Keandalan dan Integritas Data (Reliability & Data Integrity):", "Seluruh transaksi keuangan kasir multi-item, pembaruan pengurangan stok suplemen, serta penambahan durasi masa aktif member dijalankan di dalam mekanisme Database Transactions (ACID) untuk memastikan keutuhan data dan mencegah inkonsistensi saat terjadi kegagalan jaringan."),
        ("4. Kemudahan Penggunaan (Usability):", "Antarmuka sistem mengadopsi tema Industrial Dark Brutalist yang responsif, menyajikan kontras tinggi dengan palet warna abu-abu gelap (#131313, #1f1f1f) dan aksen merah (#ff5540), tombol operasional berukuran besar, serta tata letak tombol kasir yang intuitif untuk kemudahan pengoperasian staf di kasir."),
        ("5. Ketersediaan Sistem (Availability):", "Aplikasi web dirancang untuk dapat diakses 24 jam sehari, 7 hari seminggu (24/7) pada server hosting, sehingga member dapat melihat profil, jadwal gym, dan melakukan transaksi perpanjangan mandiri kapan pun diperlukan.")
    ]

    for nfr_t, nfr_d in nfr_items:
        p_nfr = add_p(doc, space_before=1, space_after=3)
        run_t = p_nfr.add_run("   " + nfr_t + " ")
        set_font(run_t, bold=True)
        run_d = p_nfr.add_run(nfr_d)
        set_font(run_d, bold=False)

    # ══════════════════════════════════════════════════════════════════════════
    # 3. RANCANGAN USE CASE DIAGRAM SISTEM
    # ══════════════════════════════════════════════════════════════════════════
    add_p(doc, "3. Rancangan Use Case Diagram Sistem", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=10, space_after=4, bold=True, font_size=12)
    
    add_p(doc, "Rancangan Use Case Diagram Sistem Informasi Manajemen Layanan Gym WARGYM Jombang dimodelkan secara terstruktur berdasarkan proses bisnis riil dan pembagian hak akses aktual pada tempat gym. Diagram ini menerapkan model Asosiasi Eksplisit Standar UML (Standard Explicit Association) tanpa relasi include/extend yang dipaksakan secara artifisial, dengan karakteristik utama sebagai berikut:", space_before=0, space_after=4)

    uc_characteristics = [
        ("a. Aktor Owner / Admin (Manajemen & Operasional Gym):", "Memegang wewenang penuh atas pengawasan eksekutif, analisis dashboard omzet & laba-rugi, pengelolaan master paket membership, master data member, katalog produk suplemen, pencatatan mutasi stok gudang, pos pengeluaran operasional (listrik, servis alat), dan penerbitan pengumuman resmi fasilitas."),
        ("b. Aktor Kasir (Front Office & Point of Sale):", "Fokus pada kecepatan pelayanan di meja depan, melayani transaksi pendaftaran member baru, perpanjangan paket membership, penjualan tiket harian (daily pass), penjualan produk suplemen, cetak struk nota fisik, validasi presensi check-in member via scanner, dan pencetakan rekap kasir per shift."),
        ("c. Aktor Member Gym (Web Portal & Mobile Workout Assistant):", "Member berinteraksi melalui aplikasi web/mobile untuk mengakses informasi fasilitas, menampilkan kartu digital ber-QR Code, melakukan presensi mandiri (self check-in), perpanjangan paket online (Midtrans), serta memanfaatkan modul inovasi asisten latihan mandiri (katalog gerakan otot target, logger beban latihan x repetisi, auto rest timer, grafik progressive overload, dan pengiriman masukan kritik/saran)."),
        ("d. Autentikasi sebagai Prasyarat Sistem (System Precondition):", "Fungsi Login dan Logout diposisikan sebagai prasyarat arsitektural (precondition) sistem yang wajib dipenuhi oleh seluruh aktor sebelum dapat berinteraksi dengan fungsionalitas di dalam sistem, sehingga tidak perlu digambarkan sebagai garis asosiasi melintang ke seluruh use case demi menjaga keterbacaan dan kebersihan diagram.")
    ]

    for uc_t, uc_d in uc_characteristics:
        p_uc = add_p(doc, space_before=1, space_after=3)
        run_t = p_uc.add_run(uc_t + " ")
        set_font(run_t, bold=True)
        run_d = p_uc.add_run(uc_d)
        set_font(run_d, bold=False)

    # Embed Image (Black & White diagram matching Zidan's reference style)
    img_path = "wargym_usecase_diagram_bw.png"
    if os.path.exists(img_path):
        p_img = doc.add_paragraph()
        p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_img.paragraph_format.space_before = Pt(8)
        p_img.paragraph_format.space_after = Pt(2)
        run_img = p_img.add_run()
        run_img.add_picture(img_path, width=Inches(6.2))
        
        p_cap = add_p(doc, "Gambar 3.1 Use Case Diagram Sistem Informasi Manajemen Layanan Gym WARGYM Jombang (Model 3 Aktor)", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=2, space_after=8, italic=True, font_size=10.5)

    # Tabel 3.1 Ringkasan Fungsi Use Case
    p_t31 = add_p(doc, "Tabel 3.1 Ringkasan Fungsi Use Case Berdasarkan Peran Aktor", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=4, bold=True, font_size=11)

    t_uc = doc.add_table(rows=1, cols=4)
    t_uc.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(t_uc)

    hdr_uc = t_uc.rows[0].cells
    hdr_uc_titles = ["No", "Nama Fungsi Use Case", "Aktor Terlibat", "Deskripsi Alur Operasional Sistem"]
    for i, title in enumerate(hdr_uc_titles):
        hdr_uc[i].paragraphs[0].text = title
        set_font(hdr_uc[i].paragraphs[0].runs[0], size_pt=10, bold=True)
        hdr_uc[i].paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        style_cell(hdr_uc[i], bg_color="E2E8F0")

    uc_table_data = [
        ("1", "Login dan Logout", "Semua Aktor (Owner/Admin, Kasir, Member)", "Autentikasi sesi kredensial pengguna dan pembatasan hak akses antarmuka berbasis peran (Role-Based Access Control) sebagai precondition arsitektural sistem."),
        ("2", "Lihat Dashboard Operasional & Finansial", "Owner / Admin", "Monitoring visual metrik bisnis utama: total member aktif, omzet kasir harian, pendapatan bulanan, grafik kunjungan, dan saldo kas riil."),
        ("3", "Cetak atau Ekspor Laporan Rekapitulasi", "Owner / Admin", "Melihat rekap kasir per shift, menyusun laporan laba-rugi berkala, dan mengekspor laporan transaksi, data member, serta kunjungan ke format Excel dan PDF."),
        ("4", "Kelola Master Paket Membership", "Owner / Admin", "Menambah dan memperbarui data paket langganan gym (Bulanan, 3 Bulan, 6 Bulan, Tahunan), penyesuaian tarif harga, dan durasi hari aktif."),
        ("5", "Kelola Data Member dan Langganan", "Owner / Admin", "Pencatatan member baru, pemutakhiran biodata, nomor WhatsApp, pemantauan masa aktif (expires_at), dan riwayat perpanjangan langganan."),
        ("6", "Kelola Master Suplemen dan Mutasi Stok", "Owner / Admin", "Pengelolaan katalog suplemen/minuman (SKU, nama, harga), pencatatan barang masuk dari suplier, penyesuaian stok opname, dan riwayat log mutasi."),
        ("7", "Catat Pos Pengeluaran Operasional", "Owner / Admin", "Mencatat pos-pos pengeluaran operasional gym (tagihan listrik PLN, air PDAM, perawatan alat gym, kebersihan) untuk pembukuan laba-rugi."),
        ("8", "Kelola Pengumuman dan Fasilitas Gym", "Owner / Admin", "Membuat dan menerbitkan pengumuman resmi ke portal member serta memperbarui informasi profil sarana dan jam buka gym."),
        ("9", "Transaksi Kasir POS dan Cetak Struk", "Kasir", "Melayani transaksi pendaftaran member, perpanjangan paket, tiket daily pass tamu, penjualan suplemen, kalkulasi kembalian tunai, dan cetak struk nota."),
        ("10", "Validasi Presensi Check-in Member", "Kasir", "Memindai barcode/QR Code kartu member di meja kasir, memvalidasi masa aktif secara instan (<2 detik), dan menyimpan data kehadiran member/tamu."),
        ("11", "Cetak Rekap Transaksi Kasir per Shift", "Kasir", "Mencetak dan merekapitulasi total uang kas masuk, penjualan tiket, dan suplemen pada saat pergantian shift jaga kasir."),
        ("12", "Akses Informasi Profil & Fasilitas Gym", "Member", "Mengakses Company Profile resmi WARGYM, jadwal operasional latihan, fasilitas gym, profil instruktur, dan tautan media sosial."),
        ("13", "Kelola Profil Akun & Target Kebugaran", "Member", "Memperbarui informasi pribadi, berat badan, tinggi badan, dan target kebugaran (bulking, cutting, maintenance)."),
        ("14", "Tampilkan Kartu Digital dan Barcode QR", "Member", "Membuka kartu keanggotaan digital di layar ponsel pintar yang memuat kode QR unik dan status sisa hari aktif keanggotaan."),
        ("15", "Presensi Mandiri (Self Check-in)", "Member", "Melakukan presensi mandiri saat tiba di gym dengan memindai kode QR pada perangkat resepsionis secara cepat tanpa mengantre di kasir."),
        ("16", "Checkout Perpanjangan Membership Online", "Member", "Melakukan perpanjangan paket membership secara mandiri dari portal web melalui antarmuka Midtrans Snap API (QRIS, VA Bank) dengan verifikasi otomatis."),
        ("17", "Akses Panduan Gerakan dan Otot Target", "Member", "Membuka katalog visual instruksi bentuk gerakan kebugaran per kelompok otot target (Dada, Punggung, Kaki, Bahu, Lengan, Core) dan panduan alat."),
        ("18", "Pencatatan Beban Latihan (Set, Kg, Reps)", "Member", "Mencatat set, beban (kg), dan repetisi secara langsung saat berolahraga dengan memori beban sesi sebelumnya untuk efisiensi waktu."),
        ("19", "Timer Jeda Istirahat Otomatis (Rest Timer)", "Member", "Mengaktifkan penghitung waktu jeda istirahat otomatis antar-set (60 detik, 90 detik, 120 detik) untuk menjaga intensitas latihan."),
        ("20", "Pantau Grafik Progressive Overload", "Member", "Menampilkan grafik akumulasi volume beban dan riwayat latihan guna memantau perkembangan kekuatan fisik secara objektif."),
        ("21", "Kirim Kritik, Saran, dan Masukan Layanan", "Member", "Mengirimkan formulir evaluasi, ulasan, atau saran perbaikan fasilitas secara langsung dari aplikasi ke pihak manajemen gym.")
    ]

    for r_idx, (c1, c2, c3, c4) in enumerate(uc_table_data):
        row = t_uc.add_row()
        cells = row.cells
        for col_idx, text in enumerate([c1, c2, c3, c4]):
            p = cells[col_idx].paragraphs[0]
            p.text = text
            p.paragraph_format.space_before = Pt(2)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.line_spacing = 1.15
            set_font(p.runs[0], size_pt=9.5, bold=(col_idx == 1))
            if col_idx == 0:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            style_cell(cells[col_idx], bg_color="FFFFFF" if r_idx % 2 == 0 else "F8FAFC")

    col_w_t31 = [Inches(0.4), Inches(1.8), Inches(1.6), Inches(2.7)]
    for row in t_uc.rows:
        for i, w in enumerate(col_w_t31):
            row.cells[i].width = w

    add_p(doc, "", space_before=4, space_after=4)

    # ══════════════════════════════════════════════════════════════════════════
    # 4. DAFTAR PENELITIAN RELEVAN & TABEL PERBANDINGAN 3 JURNAL
    # ══════════════════════════════════════════════════════════════════════════
    add_p(doc, "4. Daftar Penelitian Relevan & Tabel Perbandingan 3 Jurnal", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=10, space_after=4, bold=True, font_size=12)
    
    add_p(doc, "4.1. Matriks 10 Penelitian Terdahulu yang Relevan", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=4, space_after=4, bold=True, font_size=12)
    add_p(doc, "Untuk memperkuat landasan teoretis dan arsitektural sistem yang dikembangkan, dirangkum 10 (sepuluh) penelitian ilmiah terdahulu yang relevan dalam domain sistem informasi manajemen kebugaran, payment gateway, presensi QR Code, dan notifikasi otomatis sebagaimana disajikan pada Tabel 4.1:", space_before=0, space_after=6)

    p_t41 = add_p(doc, "Tabel 4.1 Matriks 10 Penelitian Terdahulu yang Relevan", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=4, bold=True, font_size=11)

    t_lit = doc.add_table(rows=1, cols=4)
    t_lit.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(t_lit)

    hdr_lit = t_lit.rows[0].cells
    hdr_lit_titles = ["No", "Peneliti & Tahun", "Judul Artikel & Publikasi Ilmiah", "Fokus & Relevansi terhadap Sistem WARGYM"]
    for i, title in enumerate(hdr_lit_titles):
        hdr_lit[i].paragraphs[0].text = title
        set_font(hdr_lit[i].paragraphs[0].runs[0], size_pt=10, bold=True)
        hdr_lit[i].paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        style_cell(hdr_lit[i], bg_color="E2E8F0")

    lit_data = [
        ("1", "Nugroho & Rahmawati (2024)", "Rancang Bangun Sistem Informasi Manajemen Fitness Center Berbasis Web Menggunakan Framework Laravel (Jurnal Teknologi Informasi dan Rekayasa Komputer, Vol. 5 No. 1, Hal. 45-56)", 
         "Fokus pada digitalisasi pengelolaan pendaftaran member, pencatatan paket kebugaran, dan pelaporan keuangan kasir berbasis Laravel; menjadi acuan arsitektur backend dan skema database relasional."),
        
        ("2", "Kurniawan, Haryanto, & Hadi (2026)", "Implementasi Payment Gateway Midtrans Menggunakan Webhook untuk Otomatisasi Transaksi Digital pada Platform Berbasis Web (Jurnal SPIRIT, Vol. 18 No. 1, Hal. 12-21)", 
         "Fokus pada integrasi Midtrans Snap API dan penanganan callback webhook secara otomatis untuk menggantikan verifikasi manual transfer bank; menjadi acuan modul checkout online dan webhook idempoten WARGYM."),
        
        ("3", "Pratama & Setiawan (2025)", "Sistem Presensi Real-Time Berbasis Quick Response (QR) Code pada Fasilitas Olahraga Kebugaran (Jurnal RESTI - Rekayasa Sistem dan Teknologi Informasi, Vol. 9 No. 2, Hal. 110-119)", 
         "Fokus pada implementasi presensi digital nir-kartu berbasis pemindaian QR Code untuk memvalidasi keaktifan member secara instan; menjadi acuan modul presensi kasir dan self-service check-in WARGYM."),
        
        ("4", "Tri Adi, Rahayudi, & Purnomo (2023)", "Pengembangan Sistem Notifikasi Tagihan dan Layanan Pelanggan Terintegrasi WhatsApp API Gateway (J-PTIIK Universitas Brawijaya, Vol. 7 No. 4, Hal. 1890-1898)", 
         "Fokus pada otomasi pengiriman pesan pengingat jatuh tempo ke nomor WhatsApp pelanggan secara terjadwal; menjadi rujukan perancangan WhatsApp Reminder Engine untuk pengingat masa aktif H-7 member."),
        
        ("5", "Santoso & Wulandari (2024)", "Perancangan Sistem Point of Sale (POS) dan Manajemen Persediaan Suplemen Kebugaran Berbasis Web (Jurnal J-INTECH, Vol. 12 No. 1, Hal. 34-44)", 
         "Fokus pada pencatatan transaksi kasir multi-item dan pengendalian stok suplemen (kartu stok); menjadi acuan perancangan modul kasir POS produk suplemen ber-SKU dan perhitungan kembalian kasir."),
        
        ("6", "Wicaksono & Anggraini (2025)", "Sistem Informasi Keanggotaan (Membership) Gym dengan Algoritma Akumulasi Masa Aktif Berbasis Web (Jurnal SIKOM, Vol. 6 No. 2, Hal. 88-97)", 
         "Fokus pada logika penambahan durasi kalender keanggotaan bertumpuk tanpa memotong sisa hari aktif member; menjadi acuan perancangan Smart Stacking Logic pada sistem WARGYM."),
        
        ("7", "Ramadhan & Kusuma (2024)", "Implementasi Arsitektur Webhook Idempoten pada Transaksi Pembayaran Daring untuk Mencegah Double-Processing (Jurnal TEKNOMATIKA, Vol. 14 No. 2, Hal. 102-111)", 
         "Fokus pada teknik pencegahan eksekusi berulang (race condition) pada penerimaan payload webhook payment gateway; menjadi acuan pengamanan transaksi pembayaran di sisi server backend Laravel."),
        
        ("8", "Firmansyah & Lestari (2025)", "Rancang Bangun Portal Layanan Mandiri (Self-Service) dan Pemantauan Kunjungan Member Gym (Jurnal INTEKNA, Vol. 25 No. 1, Hal. 55-64)", 
         "Fokus pada penyediaan antarmuka portal mandiri bagi member untuk melihat masa aktif dan riwayat kehadiran; menjadi acuan perancangan dashboard member portal responsif WARGYM."),
        
        ("9", "Hidayat & Saputra (2024)", "Analisis Usabilitas dan Pengalaman Pengguna (UI/UX) pada Aplikasi Manajemen Kebugaran Menggunakan Standar ISO 9241-11 (Jurnal SISFOTENIKA, Vol. 14 No. 1, Hal. 77-88)", 
         "Fokus pada efisiensi interaksi antarmuka pengguna pada sistem operasional kebugaran; menjadi acuan penerapan tema antarmuka Industrial Dark Brutalist yang ergonomis bagi kasir dan member."),
        
        ("10", "Prasetyo, Utami, & Wahyudi (2025)", "Sistem Informasi Akuntansi Pengelolaan Biaya Operasional dan Rekapitulasi Laba Rugi pada Usaha Jasa Kebugaran (Jurnal JORAPI, Vol. 4 No. 3, Hal. 215-226)", 
         "Fokus pada pencatatan pos biaya operasional harian dan penyusunan laporan keuangan otomatis untuk pemilik usaha; menjadi acuan perancangan modul expense records dan laporan laba-rugi WARGYM.")
    ]

    for r_idx, (c1, c2, c3, c4) in enumerate(lit_data):
        row = t_lit.add_row()
        cells = row.cells
        for col_idx, text in enumerate([c1, c2, c3, c4]):
            p = cells[col_idx].paragraphs[0]
            p.text = text
            p.paragraph_format.space_before = Pt(2)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.line_spacing = 1.15
            set_font(p.runs[0], size_pt=9.5, bold=(col_idx in [0, 1]))
            if col_idx == 0:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            style_cell(cells[col_idx], bg_color="FFFFFF" if r_idx % 2 == 0 else "F8FAFC")

    col_w_t41 = [Inches(0.4), Inches(1.6), Inches(2.3), Inches(2.2)]
    for row in t_lit.rows:
        for i, w in enumerate(col_w_t41):
            row.cells[i].width = w

    add_p(doc, "", space_before=4, space_after=4)

    # 4.2 Tabel Perbandingan 3 Penelitian Paling Relevan
    add_p(doc, "4.2. Tabel Perbandingan 3 Penelitian Paling Relevan", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=6, space_after=4, bold=True, font_size=12)
    add_p(doc, "Dari 10 penelitian di atas, dipilih 3 (tiga) penelitian yang paling relevan dan memiliki kedekatan langsung dengan arsitektur sistem WARGYM untuk dianalisis perbandingannya secara mendalam sebagaimana disajikan pada Tabel 4.2:", space_before=0, space_after=6)

    p_t42 = add_p(doc, "Tabel 4.2 Tabel Perbandingan 3 Penelitian Paling Relevan dengan Sistem yang Dikembangkan", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=4, bold=True, font_size=11)

    t_cmp = doc.add_table(rows=1, cols=5)
    t_cmp.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(t_cmp)

    hdr_cmp = t_cmp.rows[0].cells
    hdr_cmp_titles = ["Peneliti & Tahun", "Judul & Publikasi", "Masalah & Solusi", "Kontribusi Acuan", "Perbedaan & Fokus Kebaruan Sistem WARGYM"]
    for i, title in enumerate(hdr_cmp_titles):
        hdr_cmp[i].paragraphs[0].text = title
        set_font(hdr_cmp[i].paragraphs[0].runs[0], size_pt=9.5, bold=True)
        hdr_cmp[i].paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        style_cell(hdr_cmp[i], bg_color="E2E8F0")

    cmp_data = [
        ("Purnawan &\nSancoko\n(2025)",
         "Design and Implementation of FitSphere as an Android Application for Gym Membership Management at Chain Gym (J-INTECH, Vol. 13 No. 2, Hal. 282–291)",
         "Masalah: Pengelolaan data anggota dan pendaftaran kelas di Chain Gym masih manual dan terpisah, menimbulkan inefisiensi administrasi, keterbatasan pelacakan riwayat latihan, dan kesulitan member mengelola jadwal.\n\nSolusi: Membangun aplikasi Android FitSphere berbasis Kotlin dan BaaS Firebase untuk pendaftaran member, langganan paket, jadwal kelas, konsultasi, dan kalkulator BMI.",
         "Menjadi acuan utama dalam perancangan aplikasi mobile keanggotaan gym, pemodelan use case interaksi member di smartphone, serta perancangan fitur kalkulator kebugaran (BMI/IMT) dan riwayat aktivitas latihan (workout history).",
         "FitSphere hanya berbasis Android dengan backend BaaS Firebase tanpa sistem kasir terpadu (POS), tidak memiliki integrasi payment gateway resmi ber-webhook otomatis (hanya pencatatan internal), serta tidak memiliki presensi QR Code scanner nir-kartu di resepsionis.\n\nPlatform WARGYM memadukan Web Kasir & Admin (Laravel 12) dengan Mobile Member Portal, mengintegrasikan Payment Gateway Midtrans (QRIS/VA) dengan webhook idempoten, presensi digital QR Code dinamis & Self-Service Check-in, Smart Stacking Logic perpanjangan masa aktif kalender, WhatsApp Gateway Reminder otomatis H-7, serta Modul Asisten Latihan Mandiri (panduan postur gerakan per otot target, live workout logger set/kg/reps, dan rest timer otomatis)."),
        
        ("Sururi, Thoib,\nNugraha, Bayu,\n& Shah (2025)",
         "Perancangan Aplikasi Membership Gym Berbasis Web untuk Optimalisasi Layanan Pelanggan (JUKTISI, Vol. 4 No. 2, Hal. 1121–1132)",
         "Masalah: Pencatatan keanggotaan manual yang rawan kekeliruan data, lambatnya antrean pendaftaran/perpanjangan di meja kasir, dan keterbatasan penyampaian informasi jadwal serta promosi kepada member.\n\nSolusi: Merancang aplikasi membership gym berbasis web dengan pendekatan User-Centered Design (UCD) untuk memfasilitasi pendaftaran daring, pembayaran, dan informasi promosi real-time.",
         "Menjadi acuan dalam perancangan antarmuka portal layanan mandiri (self-service) bagi member gym, tata letak informasi jadwal latihan dan promosi yang ergonomis, serta konsep optimalisasi retensi pelanggan gym berbasis digital.",
         "Penelitian Sururi dkk. berfokus pada tahap perancangan prototipe web konseptual tanpa implementasi gateway pembayaran real-time berlisensi, tidak memiliki kartu presensi digital QR Code untuk verifikasi kedatangan di meja resepsionis, serta belum mencakup modul pendamping latihan mandiri.\n\nPlatform WARGYM menghadirkan sistem operasional penuh: POS Kasir Multi-Item (langganan member, tiket harian daily pass, dan suplemen ber-SKU), otomatisasi checkout Midtrans (verifikasi QRIS/VA instan), presensi QR scanner cepat (<2 detik), notifikasi WhatsApp reminder, serta Modul Workout Tracker mandiri (pencatat beban progressive overload)."),
        
        ("Sari &\nAlfarisi\n(2024)",
         "Perancangan Sistem Aplikasi Pendataan Membership Gym Menggunakan Metode Unified Software Development Process (USDP) Berbasis Web (Hello World Jurnal Ilmu Komputer, Vol. 3 No. 1, Hal. 37–48)",
         "Masalah: Pencatatan data keanggotaan dan pembayaran di One-R Gym masih menggunakan buku kertas fisik konvensional yang rentan rusak, sobek, hilang, dan lambat dalam pelacakan status aktif member.\n\nSolusi: Membangun aplikasi web pendataan keanggotaan menggunakan metode USDP berbasis PHP dan MySQL untuk mengkomputerisasi registrasi, paket kebugaran, dan transaksi kasir.",
         "Menjadi acuan dalam pemodelan data relasional keanggotaan gym menggunakan tahapan USDP (Inception, Elaboration, Construction, Transition), perancangan relasi tabel members, packages, dan payments, serta struktur hak akses admin.",
         "Penelitian Sari & Alfarisi hanya mencakup pendataan administratif dasar (CRUD) dengan pencatatan pembayaran manual di kasir tanpa fasilitas pembayaran online, tidak memiliki mekanisme penambahan masa aktif bertumpuk (smart stacking), tidak memiliki presensi digital QR Code, dan tidak memiliki modul pendampingan latihan.\n\nPlatform WARGYM menyempurnakan kelemahan tersebut dengan arsitektur modern Laravel 12 & Mobile, Smart Stacking Logic akumulatif masa aktif (tidak memotong hari aktif member), Payment Gateway Midtrans otomatis, Kartu Presensi QR Code nir-kartu dengan foto profil verifikasi visual, WhatsApp Gateway pengingat jatuh tempo H-7, serta Modul Asisten Latihan Mandiri terintegrasi.")
    ]

    for r_idx, (c1, c2, c3, c4, c5) in enumerate(cmp_data):
        row = t_cmp.add_row()
        cells = row.cells
        for col_idx, text in enumerate([c1, c2, c3, c4, c5]):
            p = cells[col_idx].paragraphs[0]
            p.text = text
            p.paragraph_format.space_before = Pt(2)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.line_spacing = 1.15
            set_font(p.runs[0], size_pt=9, bold=(col_idx == 0))
            if col_idx == 0:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            style_cell(cells[col_idx], bg_color="FFFFFF" if r_idx % 2 == 0 else "F8FAFC")

    col_w_t42 = [Inches(1.2), Inches(1.3), Inches(1.5), Inches(1.4), Inches(1.8)]
    for row in t_cmp.rows:
        for i, w in enumerate(col_w_t42):
            row.cells[i].width = w

    add_p(doc, "", space_before=4, space_after=4)

    # ══════════════════════════════════════════════════════════════════════════
    # 5. JADWAL PENELITIAN (MAKSIMAL 6 BULAN)
    # ══════════════════════════════════════════════════════════════════════════
    add_p(doc, "5. Jadwal Penelitian (Maksimal 6 Bulan)", align=WD_ALIGN_PARAGRAPH.LEFT, space_before=10, space_after=4, bold=True, font_size=12)
    
    add_p(doc, "Pelaksanaan kegiatan penelitian Laporan Akhir ini dirancang berlangsung selama 6 (enam) bulan kerja, terhitung mulai bulan Oktober 2026 hingga Maret 2027. Rencana tahapan kerja disusun secara sistematis mengikuti tahapan rekayasa perangkat lunak sebagaimana disajikan pada Tabel 5.1:", space_before=0, space_after=6)

    p_t51 = add_p(doc, "Tabel 5.1 Jadwal Rencana Kerja Penelitian (Gantt Chart 6 Bulan)", align=WD_ALIGN_PARAGRAPH.CENTER, space_before=4, space_after=4, bold=True, font_size=11)

    t_gantt = doc.add_table(rows=1, cols=8)
    t_gantt.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(t_gantt)

    hdr_g = t_gantt.rows[0].cells
    hdr_g_titles = ["No", "Tahapan Kegiatan Penelitian", "Bln 1\n(Okt)", "Bln 2\n(Nov)", "Bln 3\n(Des)", "Bln 4\n(Jan)", "Bln 5\n(Feb)", "Bln 6\n(Mar)"]
    for i, title in enumerate(hdr_g_titles):
        hdr_g[i].paragraphs[0].text = title
        set_font(hdr_g[i].paragraphs[0].runs[0], size_pt=9.5, bold=True)
        hdr_g[i].paragraphs[0].alignment = WD_ALIGN_PARAGRAPH.CENTER
        style_cell(hdr_g[i], bg_color="E2E8F0")

    gantt_data = [
        ("1", "Studi literatur, observasi langsung di WARGYM Jombang, wawancara pengelola/kasir/member, dan pengumpulan dokumen administrasi operasional", "X", "", "", "", "", ""),
        ("2", "Analisis masalah, analisis data entitas, perumusan spesifikasi kebutuhan (FR & NFR), dan pemodelan alur proses bisnis usulan", "", "X", "", "", "", ""),
        ("3", "Perancangan sistem: Use Case Diagram, pemodelan data relasional (ERD & skema normalisasi 3NF), arsitektur webhook, dan desain antarmuka UI/UX (Industrial Dark Brutalist)", "", "X", "X", "", "", ""),
        ("4", "Pengembangan Sistem Tahap I (Core Backend & Manajemen Data): Modul autentikasi multi-role (RBAC), master member, master paket membership, katalog suplemen & kartu stok log, serta pos biaya operasional", "", "", "X", "X", "", ""),
        ("5", "Pengembangan Sistem Tahap II (Fitur Transaksional & Integrasi Gateway): Modul kasir POS & cetak struk, integrasi Midtrans Snap & Webhook Callback idempoten, logika smart stacking, presensi QR scanner & self-service, serta WhatsApp reminder", "", "", "", "X", "X", ""),
        ("6", "Pengujian sistem (Black-Box Testing & UAT di WARGYM), perbaikan bug (bug fixing), evaluasi performa, serta penyusunan buku Laporan Akhir penelitian", "", "", "", "", "X", "X")
    ]

    for r_idx, row_data in enumerate(gantt_data):
        row = t_gantt.add_row()
        cells = row.cells
        for col_idx, text in enumerate(row_data):
            p = cells[col_idx].paragraphs[0]
            p.text = text
            p.paragraph_format.space_before = Pt(2)
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.line_spacing = 1.15
            set_font(p.runs[0], size_pt=9, bold=(col_idx in [0, 2, 3, 4, 5, 6, 7] and text == "X"))
            if col_idx in [0, 2, 3, 4, 5, 6, 7]:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            style_cell(cells[col_idx], bg_color="FFFFFF" if r_idx % 2 == 0 else "F8FAFC")

    col_w_t51 = [Inches(0.4), Inches(3.6), Inches(0.5), Inches(0.5), Inches(0.5), Inches(0.5), Inches(0.5), Inches(0.5)]
    for row in t_gantt.rows:
        for i, w in enumerate(col_w_t51):
            row.cells[i].width = w

    add_p(doc, "", space_before=10, space_after=4)

    # ── Catatan Pengumpulan
    p_sub = add_p(doc, "Catatan: Berkas ini disusun sebagai lembar jawaban resmi Ujian Tengah Semester (UTS) mata kuliah Proposal Laporan Akhir dan dikumpulkan sesuai tenggat waktu yang ditentukan.", align=WD_ALIGN_PARAGRAPH.JUSTIFY, space_before=6, space_after=6, italic=True, font_size=10.5)

    doc.save(filename)
    print(f"File {filename} created successfully!")

if __name__ == "__main__":
    create_proposal_docx()
