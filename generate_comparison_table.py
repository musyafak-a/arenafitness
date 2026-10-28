import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls
import os
import shutil

cmp_data = [
    (
        "Purnawan &\nSancoko\n(2025)",
        "Design and Implementation of FitSphere as an Android Application for Gym Membership Management at Chain Gym (J-INTECH: Journal of Information and Technology, Vol. 13 No. 2, Hal. 282–291)",
        "Masalah:\nPengelolaan data anggota dan pendaftaran kelas di Chain Gym masih manual dan terpisah, menimbulkan inefisiensi administrasi, keterbatasan pelacakan riwayat latihan, dan kesulitan member mengelola jadwal.\n\nSolusi:\nMembangun aplikasi Android FitSphere berbasis Kotlin dan BaaS Firebase untuk pendaftaran member, langganan paket, jadwal kelas, konsultasi, dan kalkulator BMI.",
        "Menjadi acuan utama dalam perancangan aplikasi mobile keanggotaan gym, pemodelan use case interaksi member di smartphone, serta perancangan fitur kalkulator kebugaran (BMI/IMT) dan riwayat aktivitas latihan (workout history).",
        "FitSphere hanya berbasis Android dengan backend BaaS Firebase tanpa sistem kasir terpadu (POS), tidak memiliki integrasi payment gateway resmi ber-webhook otomatis (hanya pencatatan internal), serta tidak memiliki presensi QR Code scanner nir-kartu di resepsionis.\n\nPlatform WARGYM memadukan Web Kasir & Admin (Laravel 12) dengan Mobile Member Portal, mengintegrasikan Payment Gateway Midtrans (QRIS/VA) dengan webhook idempoten, presensi digital QR Code dinamis & Self-Service Check-in, Smart Stacking Logic perpanjangan masa aktif kalender, WhatsApp Gateway Reminder otomatis H-7, serta Modul Asisten Latihan Mandiri (panduan postur gerakan per otot target, live workout logger set/kg/reps, dan rest timer otomatis)."
    ),
    (
        "Sururi, Thoib,\nNugraha, Bayu,\n& Shah (2025)",
        "Perancangan Aplikasi Membership Gym Berbasis Web untuk Optimalisasi Layanan Pelanggan (JUKTISI: Jurnal Komputer, Teknik dan Sistem Informasi, Vol. 4 No. 2, Hal. 1121–1132)",
        "Masalah:\nPencatatan keanggotaan manual yang rawan kekeliruan data, lambatnya antrean pendaftaran/perpanjangan di meja kasir, dan keterbatasan penyampaian informasi jadwal serta promosi kepada member.\n\nSolusi:\nMerancang aplikasi membership gym berbasis web dengan pendekatan User-Centered Design (UCD) untuk memfasilitasi pendaftaran daring, pembayaran, dan informasi promosi real-time.",
        "Menjadi acuan dalam perancangan antarmuka portal layanan mandiri (self-service) bagi member gym, tata letak informasi jadwal latihan dan promosi yang ergonomis, serta konsep optimalisasi retensi pelanggan gym berbasis digital.",
        "Penelitian Sururi dkk. berfokus pada tahap perancangan prototipe web konseptual tanpa implementasi gateway pembayaran real-time berlisensi, tidak memiliki kartu presensi digital QR Code untuk verifikasi kedatangan di meja resepsionis, serta belum mencakup modul pendamping latihan mandiri.\n\nPlatform WARGYM menghadirkan sistem operasional penuh: POS Kasir Multi-Item (langganan member, tiket harian daily pass, dan suplemen ber-SKU), otomatisasi checkout Midtrans (verifikasi QRIS/VA instan), presensi QR scanner cepat (<2 detik), notifikasi WhatsApp reminder, serta Modul Workout Tracker mandiri (pencatat beban progressive overload)."
    ),
    (
        "Sari &\nAlfarisi\n(2024)",
        "Perancangan Sistem Aplikasi Pendataan Membership Gym Menggunakan Metode Unified Software Development Process (USDP) Berbasis Web (Hello World: Jurnal Ilmu Komputer, Vol. 3 No. 1, Hal. 37–48)",
        "Masalah:\nPencatatan data keanggotaan dan pembayaran di One-R Gym masih menggunakan buku kertas fisik konvensional yang rentan rusak, sobek, hilang, dan lambat dalam pelacakan status aktif member.\n\nSolusi:\nMembangun aplikasi web pendataan keanggotaan menggunakan metode USDP berbasis PHP dan MySQL untuk mengkomputerisasi registrasi, paket kebugaran, dan transaksi kasir.",
        "Menjadi acuan dalam pemodelan data relasional keanggotaan gym menggunakan tahapan USDP (Inception, Elaboration, Construction, Transition), perancangan relasi tabel members, packages, dan payments, serta struktur hak akses admin.",
        "Penelitian Sari & Alfarisi hanya mencakup pendataan administratif dasar (CRUD) dengan pencatatan pembayaran manual di kasir tanpa fasilitas pembayaran online, tidak memiliki mekanisme penambahan masa aktif bertumpuk (smart stacking), tidak memiliki presensi digital QR Code, dan tidak memiliki modul pendampingan latihan.\n\nPlatform WARGYM menyempurnakan kelemahan tersebut dengan arsitektur modern Laravel 12 & Mobile, Smart Stacking Logic akumulatif masa aktif (tidak memotong hari aktif member), Payment Gateway Midtrans otomatis, Kartu Presensi QR Code nir-kartu dengan foto profil verifikasi visual, WhatsApp Gateway pengingat jatuh tempo H-7, serta Modul Asisten Latihan Mandiri terintegrasi."
    )
]

def style_cell(cell, bg_color=None, pad_top=100, pad_bottom=100, pad_left=120, pad_right=120):
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

def build_standalone_table():
    doc = docx.Document()
    
    # Page setup: Portrait with 1 inch margins
    for section in doc.sections:
        section.top_margin = Inches(1.0)
        section.bottom_margin = Inches(1.0)
        section.left_margin = Inches(1.0)
        section.right_margin = Inches(1.0)
        section.page_width = Inches(8.27)
        section.page_height = Inches(11.69)

    # Title paragraph matching image:
    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_title.paragraph_format.space_before = Pt(0)
    p_title.paragraph_format.space_after = Pt(8)
    run_t = p_title.add_run("4.2. Tabel Perbandingan 3 Penelitian Paling Relevan")
    run_t.font.name = "Times New Roman"
    run_t.font.size = Pt(12)
    run_t.bold = True

    # Create Table: 1 Header row + 3 Data rows
    table = doc.add_table(rows=1, cols=5)
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    set_table_borders(table)

    headers = [
        "Peneliti &\nTahun",
        "Judul &\nPublikasi",
        "Masalah &\nSolusi",
        "Kontribusi\nAcuan",
        "Perbedaan &\nFokus Kebaruan\nWARGYM"
    ]
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
        hdr_cells[i].paragraphs[0].paragraph_format.line_spacing = 1.15
        style_cell(hdr_cells[i], bg_color="E2E8F0")

    for r_idx, row_data in enumerate(cmp_data):
        row = table.add_row()
        cells = row.cells
        for col_idx, text in enumerate(row_data):
            p = cells[col_idx].paragraphs[0]
            p.text = text
            p.paragraph_format.space_before = Pt(3)
            p.paragraph_format.space_after = Pt(3)
            p.paragraph_format.line_spacing = 1.15
            run = p.runs[0]
            run.font.name = "Times New Roman"
            run.font.size = Pt(9.5)
            run.bold = (col_idx == 0)
            if col_idx == 0:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            style_cell(cells[col_idx], bg_color="FFFFFF" if r_idx % 2 == 0 else "F8FAFC")

    # Column widths (Total ~6.27 inches)
    col_widths = [Inches(1.1), Inches(1.3), Inches(1.4), Inches(1.2), Inches(1.5)]
    for row in table.rows:
        for i, w in enumerate(col_widths):
            row.cells[i].width = w

    out_docx = r"C:\laragon\www\arenafitness\Tabel_Perbandingan_3_Penelitian_Paling_Relevan_WARGYM.docx"
    doc.save(out_docx)
    print(f"Saved: {out_docx}")

    # Copy to Downloads
    out_dl = r"C:\Users\LENOVO\Downloads\jurnal refrensi\Tabel_Perbandingan_3_Penelitian_Paling_Relevan_WARGYM.docx"
    try:
        shutil.copy2(out_docx, out_dl)
        print(f"Copied to: {out_dl}")
    except Exception as e:
        print("Note on copying to downloads:", e)

if __name__ == "__main__":
    build_standalone_table()
