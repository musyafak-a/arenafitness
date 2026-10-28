import os
import sys
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

def create_document():
    doc = Document()
    
    # Page Setup: A4, 2.5 cm margins
    for section in doc.sections:
        section.page_width = Inches(8.27)   # A4 width
        section.page_height = Inches(11.69) # A4 height
        section.top_margin = Inches(1.0)
        section.bottom_margin = Inches(1.0)
        section.left_margin = Inches(1.0)
        section.right_margin = Inches(1.0)
        
        # Header & Footer setup
        header = section.header
        hp = header.paragraphs[0]
        hp.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        hrun = hp.add_run("WARGYM MOJOSONGO — DOKUMENTASI SISTEM & SPESIFIKASI TEKNIS")
        hrun.font.name = "Segoe UI"
        hrun.font.size = Pt(8.5)
        hrun.font.color.rgb = RGBColor(148, 163, 184) # slate-400
        
        footer = section.footer
        fp = footer.paragraphs[0]
        fp.alignment = WD_ALIGN_PARAGRAPH.LEFT
        frun1 = fp.add_run("WARGYM Company Profile & Personal Training Catalogue  |  ")
        frun1.font.name = "Segoe UI"
        frun1.font.size = Pt(8.5)
        frun1.font.color.rgb = RGBColor(148, 163, 184)
        
        frun2 = fp.add_run("Dokumentasi Resmi 2026")
        frun2.font.name = "Segoe UI"
        frun2.font.size = Pt(8.5)
        frun2.font.color.rgb = RGBColor(148, 163, 184)

    # Styles setup
    style_normal = doc.styles['Normal']
    font_normal = style_normal.font
    font_normal.name = 'Segoe UI'
    font_normal.size = Pt(10.5)
    font_normal.color.rgb = RGBColor(30, 41, 59) # slate-800
    
    # Helper functions
    def set_cell_background(cell, hex_color):
        tcPr = cell._tc.get_or_add_tcPr()
        for child in list(tcPr):
            if child.tag.endswith('shd'):
                tcPr.remove(child)
        shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{hex_color}"/>')
        tcPr.append(shd)

    def set_cell_margins(cell, top=120, bottom=120, left=150, right=150):
        tcPr = cell._tc.get_or_add_tcPr()
        tcMar = parse_xml(f'<w:tcMar {nsdecls("w")}><w:top w:w="{top}" w:type="dxa"/><w:bottom w:w="{bottom}" w:type="dxa"/><w:left w:w="{left}" w:type="dxa"/><w:right w:w="{right}" w:type="dxa"/></w:tcMar>')
        tcPr.append(tcMar)

    def set_table_borders(table, color="E2E8F0", sz="4", val="single"):
        tblPr = table._tbl.tblPr
        borders = parse_xml(f'''
            <w:tblBorders {nsdecls("w")}>
                <w:top w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>
                <w:bottom w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>
                <w:left w:val="none"/>
                <w:right w:val="none"/>
                <w:insideH w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>
                <w:insideV w:val="none"/>
            </w:tblBorders>
        ''')
        tblPr.append(borders)

    def add_h1(text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(18)
        p.paragraph_format.space_after = Pt(6)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.bold = True
        run.font.name = "Segoe UI"
        run.font.size = Pt(15)
        run.font.color.rgb = RGBColor(225, 29, 72) # #E11D48
        return p

    def add_h2(text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(13)
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.bold = True
        run.font.name = "Segoe UI"
        run.font.size = Pt(12.5)
        run.font.color.rgb = RGBColor(15, 23, 42) # #0F172A
        return p

    def add_h3(text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(9)
        p.paragraph_format.space_after = Pt(2)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.bold = True
        run.font.name = "Segoe UI"
        run.font.size = Pt(11)
        run.font.color.rgb = RGBColor(51, 65, 85) # #334155
        return p

    def add_p(text, bold_prefix=None, space_after=5):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(space_after)
        p.paragraph_format.line_spacing = 1.15
        if bold_prefix:
            r_bold = p.add_run(bold_prefix)
            r_bold.bold = True
            r_bold.font.color.rgb = RGBColor(15, 23, 42)
        r = p.add_run(text)
        r.font.color.rgb = RGBColor(51, 65, 85)
        return p

    def add_callout(text, title="CATATAN PENTING"):
        tbl = doc.add_table(rows=1, cols=1)
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        tbl.autofit = False
        tbl.columns[0].width = Inches(6.27)
        cell = tbl.cell(0, 0)
        set_cell_background(cell, "FFF5F5") # red tint
        set_cell_margins(cell, top=140, bottom=140, left=200, right=180)
        tcPr = cell._tc.get_or_add_tcPr()
        borders = parse_xml(f'''
            <w:tcBorders {nsdecls("w")}>
                <w:left w:val="single" w:sz="24" w:space="0" w:color="E11D48"/>
                <w:top w:val="none"/>
                <w:right w:val="none"/>
                <w:bottom w:val="none"/>
            </w:tcBorders>
        ''')
        tcPr.append(borders)
        p = cell.paragraphs[0]
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        p.paragraph_format.line_spacing = 1.15
        r_title = p.add_run(f"📌 {title}: ")
        r_title.bold = True
        r_title.font.name = "Segoe UI"
        r_title.font.size = Pt(10)
        r_title.font.color.rgb = RGBColor(225, 29, 72)
        r_text = p.add_run(text)
        r_text.font.name = "Segoe UI"
        r_text.font.size = Pt(10)
        r_text.font.color.rgb = RGBColor(30, 41, 59)
        
        p_space = doc.add_paragraph()
        p_space.paragraph_format.space_before = Pt(0)
        p_space.paragraph_format.space_after = Pt(4)

    def add_code(code_text):
        tbl = doc.add_table(rows=1, cols=1)
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        tbl.autofit = False
        tbl.columns[0].width = Inches(6.27)
        cell = tbl.cell(0, 0)
        set_cell_background(cell, "F8FAFC") # light slate
        set_cell_margins(cell, top=120, bottom=120, left=160, right=160)
        tcPr = cell._tc.get_or_add_tcPr()
        borders = parse_xml(f'''
            <w:tcBorders {nsdecls("w")}>
                <w:left w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
                <w:top w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
                <w:right w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
                <w:bottom w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
            </w:tcBorders>
        ''')
        tcPr.append(borders)
        p = cell.paragraphs[0]
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        p.paragraph_format.line_spacing = 1.15
        r = p.add_run(code_text.strip())
        r.font.name = "Consolas"
        r.font.size = Pt(8.5)
        r.font.color.rgb = RGBColor(30, 41, 59)
        
        p_space = doc.add_paragraph()
        p_space.paragraph_format.space_before = Pt(0)
        p_space.paragraph_format.space_after = Pt(4)

    # ==========================================
    # COVER PAGE
    # ==========================================
    p_cov_space = doc.add_paragraph()
    p_cov_space.paragraph_format.space_before = Pt(40)
    
    # Badge
    p_badge = doc.add_paragraph()
    p_badge.paragraph_format.space_before = Pt(0)
    p_badge.paragraph_format.space_after = Pt(8)
    r_badge = p_badge.add_run("DOKUMENTASI SISTEM & SPESIFIKASI TEKNIS")
    r_badge.bold = True
    r_badge.font.name = "Segoe UI"
    r_badge.font.size = Pt(11)
    r_badge.font.color.rgb = RGBColor(225, 29, 72)
    
    # Main Title
    p_title = doc.add_paragraph()
    p_title.paragraph_format.space_before = Pt(0)
    p_title.paragraph_format.space_after = Pt(6)
    r_title = p_title.add_run("WARGYM MOJOSONGO")
    r_title.bold = True
    r_title.font.name = "Segoe UI"
    r_title.font.size = Pt(28)
    r_title.font.color.rgb = RGBColor(15, 23, 42)
    
    # Subtitle
    p_sub = doc.add_paragraph()
    p_sub.paragraph_format.space_before = Pt(0)
    p_sub.paragraph_format.space_after = Pt(24)
    r_sub = p_sub.add_run("Web Application Company Profile & Personal Training Catalogue")
    r_sub.font.name = "Segoe UI"
    r_sub.font.size = Pt(14)
    r_sub.font.color.rgb = RGBColor(100, 116, 139) # slate-500
    
    # Decorative rule line
    p_line = doc.add_paragraph()
    p_line.paragraph_format.space_after = Pt(36)
    r_line = p_line.add_run("―" * 45)
    r_line.font.color.rgb = RGBColor(225, 29, 72)
    
    # Meta Info Card Table
    meta_table = doc.add_table(rows=6, cols=2)
    meta_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    meta_table.autofit = False
    meta_table.columns[0].width = Inches(2.2)
    meta_table.columns[1].width = Inches(4.07)
    set_table_borders(meta_table, color="E2E8F0", sz="4")
    
    meta_data = [
        ("Nama Sistem", "WARGYM Mojosongo Web Platform"),
        ("Tipe Aplikasi", "Single Page Application (SPA)"),
        ("Framework & Tooling", "Vue.js 3.5+ (Composition API) & Vite 8"),
        ("Live Production URL", "https://wargym-profile.vercel.app"),
        ("Repositori Resmi", "https://github.com/gymwarung/arena-profile"),
        ("Pembaruan Terakhir", "Oktober 2026 (Commit 60d4520)")
    ]
    
    for i, (k, v) in enumerate(meta_data):
        row = meta_table.rows[i]
        c0, c1 = row.cells[0], row.cells[1]
        set_cell_background(c0, "F8FAFC" if i % 2 == 0 else "FFFFFF")
        set_cell_background(c1, "F8FAFC" if i % 2 == 0 else "FFFFFF")
        set_cell_margins(c0, top=100, bottom=100, left=140, right=140)
        set_cell_margins(c1, top=100, bottom=100, left=140, right=140)
        
        p0 = c0.paragraphs[0]
        p0.paragraph_format.space_before = Pt(0)
        p0.paragraph_format.space_after = Pt(0)
        r0 = p0.add_run(k)
        r0.bold = True
        r0.font.size = Pt(9.5)
        r0.font.color.rgb = RGBColor(71, 85, 105)
        
        p1 = c1.paragraphs[0]
        p1.paragraph_format.space_before = Pt(0)
        p1.paragraph_format.space_after = Pt(0)
        r1 = p1.add_run(v)
        r1.font.size = Pt(9.5)
        r1.font.color.rgb = RGBColor(15, 23, 42)
        if "http" in v:
            r1.font.color.rgb = RGBColor(225, 29, 72)
            r1.bold = True

    doc.add_page_break()

    # ==========================================
    # DAFTAR ISI
    # ==========================================
    add_h1("DAFTAR ISI")
    
    toc_items = [
        ("1. Latar Belakang Sistem", "3"),
        ("   1.1 Gambaran Umum Bisnis", "3"),
        ("   1.2 Permasalahan yang Dihadapi (Problem Statement)", "3"),
        ("   1.3 Solusi Sistem", "3"),
        ("2. Arsitektur & Tech Stack", "4"),
        ("   2.1 Arsitektur Client-Side SPA", "4"),
        ("   2.2 Rincian Teknologi (Code Stack)", "4"),
        ("3. Arsitektur Data & Struktur Entitas", "5"),
        ("   3.1 Model Entitas Personal Trainer (PersonalTrainer)", "5"),
        ("   3.2 Model Entitas Fasilitas (Facility)", "6"),
        ("   3.3 Model Entitas Informasi & Layanan (ServicePlan)", "6"),
        ("4. Diagram Use Case", "7"),
        ("   4.1 Definisi Aktor & Hak Akses", "7"),
        ("   4.2 Tabel Spesifikasi Use Case (UC-01 s/d UC-10)", "7"),
        ("   4.3 Diagram Visual Use Case", "8"),
        ("5. Entity Relationship Diagram (ERD)", "9"),
        ("   5.1 Hubungan Relasi Logis Antar Entitas", "9"),
        ("   5.2 Kamus Data & Atribut Entitas", "9"),
        ("6. Alur Kerja Sistem (System Workflow & Flowchart)", "11"),
        ("   6.1 Alur Navigasi & Eksplorasi Personal Trainer", "11"),
        ("   6.2 Alur Otomatisasi CI/CD Deployment Vercel", "12"),
        ("7. Riwayat Push GitHub & Log Perbaikan (Changelog)", "13"),
        ("   7.1 Tabel Catatan Historis 21 Commit Lengkap", "13"),
        ("8. Petunjuk Pemeliharaan & Deployment", "16"),
        ("   8.1 Menjalankan di Lingkungan Lokal", "16"),
        ("   8.2 Validasi Build Produksi", "16"),
        ("   8.3 Prosedur Rilis & Push ke GitHub", "16")
    ]
    
    toc_table = doc.add_table(rows=len(toc_items), cols=2)
    toc_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    toc_table.autofit = False
    toc_table.columns[0].width = Inches(5.5)
    toc_table.columns[1].width = Inches(0.77)
    
    for i, (item, page) in enumerate(toc_items):
        row = toc_table.rows[i]
        c0, c1 = row.cells[0], row.cells[1]
        set_cell_background(c0, "FFFFFF")
        set_cell_background(c1, "FFFFFF")
        set_cell_margins(c0, top=40, bottom=40, left=60, right=60)
        set_cell_margins(c1, top=40, bottom=40, left=60, right=60)
        
        p0 = c0.paragraphs[0]
        p0.paragraph_format.space_before = Pt(0)
        p0.paragraph_format.space_after = Pt(0)
        r0 = p0.add_run(item)
        r0.font.size = Pt(10)
        if not item.startswith("   "):
            r0.bold = True
            r0.font.color.rgb = RGBColor(15, 23, 42)
        else:
            r0.font.color.rgb = RGBColor(71, 85, 105)
            
        p1 = c1.paragraphs[0]
        p1.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p1.paragraph_format.space_before = Pt(0)
        p1.paragraph_format.space_after = Pt(0)
        r1 = p1.add_run(page)
        r1.font.size = Pt(10)
        r1.font.color.rgb = RGBColor(148, 163, 184)
        if not item.startswith("   "):
            r1.bold = True
            
    doc.add_page_break()

    # ==========================================
    # BAB 1: LATAR BELAKANG SISTEM
    # ==========================================
    add_h1("1. LATAR BELAKANG SISTEM")
    
    add_h2("1.1 Gambaran Umum Bisnis")
    add_p(
        "WARGYM (Warung Gym) adalah pusat kebugaran berbasis komunitas yang berlokasi di Jl. Mojosongo, Diwek, Balong Besuk, Kecamatan Diwek, Kabupaten Jombang, Jawa Timur. WARGYM didirikan dengan visi utama menghilangkan stigma bahwa gym adalah tempat yang mahal, kaku, eksklusif, dan mengintimidasi (intimidating) bagi orang awam."
    )
    add_p(
        "Mengadopsi filosofi kehangatan sebuah 'Warung', WARGYM dirancang tidak sekadar sebagai tempat mengangkat beban, melainkan sebagai wadah sosial: tempat datang untuk berlatih, bertemu orang, saling menyapa, menikmati istirahat santai, bertukar pengalaman, dan membangun kebiasaan hidup sehat (longevity & healthy lifestyle) secara berkelanjutan bersama-sama."
    )
    
    add_h2("1.2 Permasalahan yang Dihadapi (Problem Statement)")
    add_p("Sebelum sistem aplikasi web profile resmi ini dibangun, pengelola dan calon member menghadapi sejumlah hambatan utama:")
    
    add_p("Calon anggota seringkali kesulitan mendapatkan informasi akurat mengenai fasilitas alat yang tersedia, jam operasional terkini, serta tarif keanggotaan harian maupun bulanan karena informasi tersebar secara terpisah di pesan obrolan.", bold_prefix="1. Ketiadaan Media Informasi Terpusat: ")
    add_p("Banyak masyarakat (khususnya pemula dan wanita) berkeinginan memulai olahraga kebugaran dan angkat beban, namun merasa minder, ragu cara menggunakan alat secara benar, atau takut mengalami cedera fisik.", bold_prefix="2. Kebutuhan Bimbingan & Edukasi Pemula: ")
    add_p("Calon klien membutuhkan transparansi mengenai spesialisasi, latar belakang, dan metode pendekatan masing-masing pelatih (misalnya: program Weight Loss, Body Shaping, atau Kebugaran Usia Lanjut/Longevity) sebelum mengambil keputusan menyewa jasa pendampingan privat.", bold_prefix="3. Aksesibilitas Profil Personal Trainer (PT): ")

    add_h2("1.3 Solusi Sistem")
    add_p(
        "Sistem web ini dibangun sebagai aplikasi modern berbasis Single Page Application (SPA) yang berkecepatan tinggi, interaktif, responsif sempurna pada ponsel pintar maupun komputer, serta terhubung langsung dengan saluran komunikasi WhatsApp resmi WARGYM. Sistem ini berfungsi sebagai media representasi resmi (digital presence), etalase katalog fasilitas interaktif, dan media personal branding bagi para Personal Trainer resmi WARGYM."
    )
    
    add_callout(
        "Dengan integrasi klik WhatsApp langsung (wa.me click-to-chat) yang dipersonalisasi untuk masing-masing trainer, calon anggota dapat langsung berkonsultasi secara privat tanpa perantara yang rumit.",
        title="NILAI STRATEGIS SISTEM"
    )

    # ==========================================
    # BAB 2: ARSITEKTUR & TECH STACK
    # ==========================================
    add_h1("2. ARSITEKTUR & TECH STACK")
    
    add_h2("2.1 Arsitektur Client-Side SPA")
    add_p(
        "Aplikasi dirancang dengan pola arsitektur Client-Side Single Page Application (SPA). Berbeda dengan website tradisional multi-halaman yang melakukan refresh halaman penuh pada setiap perpindahan menu, arsitektur ini memuat seluruh bundel komponen di awal, kemudian melakukan rendering view secara instan di browser klien via Vue Router."
    )
    
    arch_ascii = """+-------------------------------------------------------------------------+
|                              USER CLIENT                                |
|             (Mobile Smartphone, Tablet, Laptop, Desktop Browser)        |
+-------------------------------------------------------------------------+
                                    |
                                    v  HTTPS Request (Global Edge CDN)
+-------------------------------------------------------------------------+
|                         VERCEL EDGE CLOUD HOSTING                       |
|           - Continuous Integration / Continuous Deployment (CI/CD)      |
|           - Rewrite Rule: /(.*) -> /index.html (SPA Fallback)           |
+-------------------------------------------------------------------------+
                                    |
                                    v  Asset Delivery (HTML, JS, CSS, Media)
+-------------------------------------------------------------------------+
|                          VUE 3 APPLICATION CORE                         |
|  +-------------------------------------------------------------------+  |
|  | Vue Router 4 (HTML5 History Mode, Dynamic Page Title, Nav Guard)  |  |
|  +-------------------------------------------------------------------+  |
|  | View Components:                                                  |  |
|  | * CompanyProfile.vue   * Fasilitas.vue        * Team.vue          |  |
|  | * PersonalTrainer.vue  * Informasi.vue        * Contact.vue       |  |
|  +-------------------------------------------------------------------+  |
|  | Presentation & Styling Layer:                                     |  |
|  | * Tailwind CSS (JIT Engine, Custom Dark Industrial Theme)         |  |
|  | * Vanilla CSS (Glassmorphism, Metal Grid Pattern, Backdrop Blur)  |  |
|  | * Google Fonts (Oswald, Hanken Grotesk, JetBrains Mono)           |  |
|  +-------------------------------------------------------------------+  |
|  | In-Memory Reactive Store (Trainers, Facilities, Services)         |  |
+-------------------------------------------------------------------------+
                                    |
                                    v  Dynamic Action Trigger
+-------------------------------------------------------------------------+
|                       INTEGRASI EKSTERNAL                               |
|  - WhatsApp Business Click-to-Chat Gateway (https://wa.me/...)          |
|  - Google Maps Location Embed (Jl. Mojosongo, Diwek, Jombang)           |
+-------------------------------------------------------------------------+"""
    add_code(arch_ascii)

    add_h2("2.2 Rincian Teknologi (Code Stack)")
    add_p("Berikut adalah tabel spesifikasi teknologi yang digunakan pada pengembangan sistem:")
    
    tech_table = doc.add_table(rows=8, cols=3)
    tech_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    tech_table.autofit = False
    tech_table.columns[0].width = Inches(1.8)
    tech_table.columns[1].width = Inches(1.8)
    tech_table.columns[2].width = Inches(2.67)
    set_table_borders(tech_table, color="E2E8F0", sz="4")
    
    tech_headers = ["Lapisan (Layer)", "Teknologi", "Fungsi & Justifikasi Teknis"]
    for j, h in enumerate(tech_headers):
        cell = tech_table.rows[0].cells[j]
        set_cell_background(cell, "1E293B")
        set_cell_margins(cell, top=120, bottom=120, left=120, right=120)
        p = cell.paragraphs[0]
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        r = p.add_run(h)
        r.bold = True
        r.font.size = Pt(9.5)
        r.font.color.rgb = RGBColor(255, 255, 255)
        
    tech_rows = [
        ("Frontend Framework", "Vue.js 3.5+", "Menggunakan Composition API (<script setup>) untuk reaktivitas state yang efisien, isolasi komponen, dan struktur kode yang modular."),
        ("Build Tool & Bundler", "Vite 8", "Menyediakan Hot Module Replacement (HMR) berkecepatan milidetik pada saat pengembangan dan optimasi bundel tree-shaking saat produksi."),
        ("Client Routing", "Vue Router 4", "Mengatur navigasi SPA tanpa reload halaman, manipulasi history browser, dan pengelolaan dynamic metadata title halaman."),
        ("Styling & Theme", "Tailwind CSS + Custom CSS", "Membangun antarmuka bertema gelap industrial (Dark Luxury Gym: #131313, brand red #ff5540, glassmorphism panel)."),
        ("Typography & Ikon", "Google Fonts & Icons", "Font Oswald (Headline tegas), Hanken Grotesk (keterbacaan body paragraf tinggi), JetBrains Mono (label angka), & Material Symbols."),
        ("Data Storage", "Client Reactive Store", "Data terstruktur disimpan in-memory pada objek JavaScript reaktif, memastikan waktu muat 0 milidetik tanpa overhead latency API eksternal."),
        ("Deployment & CI/CD", "Vercel Edge Platform", "Terintegrasi otomatis dengan Git webhook di branch main GitHub untuk continuous build and deployment otomatis.")
    ]
    
    for i, row in enumerate(tech_rows):
        tbl_row = tech_table.rows[i+1]
        bg = "F8FAFC" if i % 2 == 1 else "FFFFFF"
        for j, val in enumerate(row):
            c = tbl_row.cells[j]
            set_cell_background(c, bg)
            set_cell_margins(c, top=90, bottom=90, left=110, right=110)
            p = c.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r = p.add_run(val)
            r.font.size = Pt(9)
            if j == 0:
                r.bold = True
                r.font.color.rgb = RGBColor(15, 23, 42)
            elif j == 1:
                r.font.color.rgb = RGBColor(225, 29, 72)
                r.bold = True
            else:
                r.font.color.rgb = RGBColor(71, 85, 105)

    doc.add_page_break()

    # ==========================================
    # BAB 3: ARSITEKTUR DATA & STRUKTUR ENTITAS
    # ==========================================
    add_h1("3. ARSITEKTUR DATA & STRUKTUR ENTITAS")
    add_p(
        "Aplikasi menerapkan pendekatan Decoupled Data Modeling. Data master dipisahkan dari elemen visual sehingga memudahkan pemeliharaan berkala, penambahan pelatih baru, maupun pengkinian daftar alat fasilitas tanpa merusak layout antarmuka."
    )
    
    add_h2("3.1 Model Entitas Personal Trainer (PersonalTrainer)")
    add_p(
        "Model ini mengelola seluruh informasi pelatih, mencakup nama, kutipan filosofis, latar belakang perjalanan hidup (About Me), spesialisasi terperinci, hingga pesan prefill WhatsApp:"
    )
    
    trainer_schema = """interface PersonalTrainer {
  name: string;               // Nama panggilan (misal: "GHION", "FANNY", "IZAA")
  fullName: string;           // Nama lengkap / gelar profesional (misal: "Coach Fanny")
  profession: string;         // Jabatan profesional (misal: "Personal Trainer / Fitness Coach")
  role?: string;              // Peran spesifik (misal: "Women Personal Trainer")
  tagline?: string;           // Slogan singkat (misal: "Beginner Friendly Women Fitness")
  specialties: string[];      // Array tags keahlian untuk badge preview kartu depan
  previewDesc: string;        // Ringkasan preview 3 baris (line-clamp-3) pada kartu
  quote: string;              // Kutipan prinsip atau filosofi utama pelatih
  quoteSub?: string;          // Penjelasan pelengkap kutipan
  desc?: string;              // Narasi metode latihan pelatih
  aboutMe?: string[];         // Array paragraf narasi cerita otentik perjalanan pelatih
  detailedSpecialties?: {     // Daftar spesialisasi mendalam beserta ikon Material Symbols
    icon: string;
    title: string;
    desc: string;
  }[];
  approachPoints?: string[];  // Poin-poin metode pendekatan bimbingan (checklist)
  focusPoints?: string[];     // Fokus pendampingan untuk member pemula
  vision?: string;            // Visi dan komitmen kepelatihan
  mottos?: string[];          // Slogan singkat pendukung
  slogan: string;             // Slogan penutup modal
  waMessageText: string;      // Teks pesan default saat tombol WhatsApp diklik calon member
}"""
    add_code(trainer_schema)

    add_h2("3.2 Model Entitas Fasilitas (Facility)")
    add_p("Model ini mengelola inventaris peralatan dan sarana pendukung gym:")
    
    facility_schema = """interface Facility {
  id: string;                              // Identifikasi unik fasilitas
  name: string;                            // Nama alat / sarana (misal: "Smith Machine")
  category: 'cardio' | 'strength' | 'lainnya'; // Kategori untuk filter interaktif
  count: number;                           // Jumlah unit alat yang tersedia
  image: string;                           // Path aset gambar lokal (/images/...)
  description: string;                     // Deskripsi manfaat dan fungsi latihan
}"""
    add_code(facility_schema)

    add_h2("3.3 Model Entitas Informasi & Layanan (ServicePlan)")
    add_p("Model ini mengelola paket keanggotaan dan sesi latihan:")
    
    service_schema = """interface ServicePlan {
  id: string;                              // Identifikasi unik paket
  name: string;                            // Nama paket (misal: "Member Bulanan")
  category: 'membership' | 'daily' | 'pt'; // Jenis klasifikasi layanan
  price: number;                           // Nominal tarif dalam Rupiah
  period: string;                          // Periode durasi (misal: "per bulan", "per visit")
  benefits: string[];                      // Daftar fasilitas dan keuntungan yang didapat
}"""
    add_code(service_schema)

    doc.add_page_break()

    # ==========================================
    # BAB 4: DIAGRAM USE CASE
    # ==========================================
    add_h1("4. DIAGRAM USE CASE")
    
    add_h2("4.1 Definisi Aktor & Hak Akses")
    add_p("Sistem ini melibatkan 2 (dua) aktor utama dalam operasionalnya:")
    add_p("Masyarakat umum, pemula kebugaran, dan calon anggota gym yang mengakses website melalui browser seluler atau desktop untuk memperoleh informasi lengkap dan menghubungi pelatih.", bold_prefix="1. Pengunjung Web / Calon Member: ")
    add_p("Pelatih kebugaran resmi WARGYM dan tim pengelola yang menerima calon klien melalui WhatsApp untuk konsultasi program, jadwal sesi, dan evaluasi target.", bold_prefix="2. Personal Trainer / Pengelola: ")

    add_h2("4.2 Tabel Spesifikasi Use Case")
    
    uc_table = doc.add_table(rows=11, cols=4)
    uc_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    uc_table.autofit = False
    uc_table.columns[0].width = Inches(0.9)
    uc_table.columns[1].width = Inches(1.8)
    uc_table.columns[2].width = Inches(1.1)
    uc_table.columns[3].width = Inches(2.47)
    set_table_borders(uc_table, color="E2E8F0", sz="4")
    
    uc_headers = ["Kode UC", "Nama Use Case", "Aktor Utama", "Deskripsi Fungsionalitas"]
    for j, h in enumerate(uc_headers):
        cell = uc_table.rows[0].cells[j]
        set_cell_background(cell, "1E293B")
        set_cell_margins(cell, top=120, bottom=120, left=100, right=100)
        p = cell.paragraphs[0]
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        r = p.add_run(h)
        r.bold = True
        r.font.size = Pt(9.5)
        r.font.color.rgb = RGBColor(255, 255, 255)

    uc_data = [
        ("UC-01", "Melihat Beranda & Nilai Utama", "Pengunjung", "Melihat representasi visual gym, ringkasan keunggulan, dan slogan WARGYM."),
        ("UC-02", "Melihat Cerita & Filosofi", "Pengunjung", "Membaca latar belakang asal-usul nama WARGYM dan komitmen gym ramah kantong."),
        ("UC-03", "Katalog Fasilitas Lengkap", "Pengunjung", "Menjelajahi daftar inventaris alat kebugaran dan sarana gym dengan foto riil."),
        ("UC-04", "Filter Kategori Fasilitas", "Pengunjung", "Menyortir alat berdasarkan kategori Cardio, Strength Machine, atau Fasilitas Umum."),
        ("UC-05", "Melihat Daftar Personal Trainer", "Pengunjung", "Melihat 3 kartu pelatih resmi (Kak Ghion, Coach Fanny, Kak Izaa) dengan badge spesialisasi."),
        ("UC-06", "Buka Modal Detail Profil Trainer", "Pengunjung", "Mengklik kartu pelatih untuk membuka jendela modal interaktif dengan layar penuh."),
        ("UC-07", "Membaca Riwayat & Filosofi PT", "Pengunjung", "Membaca kisah inspiratif pelatih, rincian keahlian, checklist latihan, dan visi."),
        ("UC-08", "Melihat Jam Buka & Lokasi", "Pengunjung", "Mengetahui jam buka operasional gym, alamat lengkap, dan denah Google Maps."),
        ("UC-09", "Konsultasi / Booking via WhatsApp", "Pengunjung", "Mengklik tombol CTA konsultasi untuk membuka aplikasi WhatsApp dengan pesan terformat."),
        ("UC-10", "Menerima Chat Calon Klien", "Personal Trainer", "Menerima pesan otomatis dari calon member dan memberikan bimbingan konsultasi.")
    ]
    
    for i, row in enumerate(uc_data):
        tbl_row = uc_table.rows[i+1]
        bg = "F8FAFC" if i % 2 == 1 else "FFFFFF"
        for j, val in enumerate(row):
            c = tbl_row.cells[j]
            set_cell_background(c, bg)
            set_cell_margins(c, top=80, bottom=80, left=100, right=100)
            p = c.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r = p.add_run(val)
            r.font.size = Pt(8.5)
            if j == 0:
                r.bold = True
                r.font.color.rgb = RGBColor(225, 29, 72)
            elif j == 1:
                r.bold = True
                r.font.color.rgb = RGBColor(15, 23, 42)
            else:
                r.font.color.rgb = RGBColor(71, 85, 105)

    add_h2("4.3 Diagram Visual Use Case")
    add_p("Berikut adalah representasi visual diagram use case yang menggambarkan hubungan antara aktor dan kapabilitas sistem:")
    
    if os.path.exists("usecase.png"):
        p_img = doc.add_paragraph()
        p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_img.paragraph_format.space_before = Pt(8)
        p_img.paragraph_format.space_after = Pt(4)
        run_img = p_img.add_run()
        run_img.add_picture("usecase.png", width=Inches(6.0))
        
        p_cap = doc.add_paragraph()
        p_cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_cap.paragraph_format.space_after = Pt(12)
        r_cap = p_cap.add_run("Gambar 4.1: Diagram Use Case Sistem Web Profile WARGYM Mojosongo")
        r_cap.font.size = Pt(8.5)
        r_cap.font.italic = True
        r_cap.font.color.rgb = RGBColor(100, 116, 139)

    doc.add_page_break()

    # ==========================================
    # BAB 5: ENTITY RELATIONSHIP DIAGRAM (ERD)
    # ==========================================
    add_h1("5. ENTITY RELATIONSHIP DIAGRAM (ERD)")
    
    add_h2("5.1 Hubungan Relasi Logis Antar Entitas")
    add_p(
        "Meskipun data tersimpan secara in-memory reaktif di sisi client, arsitektur data internal mematuhi relasi konseptual basis data relasional. Relasi kunci yang terbentuk antara lain:"
    )
    add_p("Satu entitas pelatih dapat memiliki banyak tag keahlian ringkas untuk kartu depan.", bold_prefix="1. PERSONAL_TRAINER ke TRAINER_SPECIALTY (1:N): ")
    add_p("Satu pelatih menawarkan berbagai bidang spesialisasi mendalam beserta uraian bimbingan.", bold_prefix="2. PERSONAL_TRAINER ke DETAIL_SPECIALTY (1:N): ")
    add_p("Satu pelatih menerapkan beberapa metodologi pendekatan kepelatihan.", bold_prefix="3. PERSONAL_TRAINER ke APPROACH_POINT (1:N): ")
    add_p("Satu pelatih memiliki narasi riwayat perjalanan hidup yang disusun berurutan.", bold_prefix="4. PERSONAL_TRAINER ke TRAINER_STORY (1:N): ")
    add_p("Setiap kategori (Cardio, Strength, Sarana Umum) mengelompokkan berbagai alat fisik.", bold_prefix="5. FACILITY_CATEGORY ke FACILITY_ITEM (1:N): ")
    add_p("Profil gym menyediakan berbagai opsi tarif keanggotaan dan sesi latihan privat.", bold_prefix="6. WARGYM_PROFILE ke SERVICE_PRICING (1:N): ")

    add_h2("5.2 Kamus Data & Atribut Entitas")
    
    erd_table = doc.add_table(rows=11, cols=4)
    erd_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    erd_table.autofit = False
    erd_table.columns[0].width = Inches(1.8)
    erd_table.columns[1].width = Inches(1.2)
    erd_table.columns[2].width = Inches(1.0)
    erd_table.columns[3].width = Inches(2.27)
    set_table_borders(erd_table, color="E2E8F0", sz="4")
    
    erd_headers = ["Entitas Basis Data", "Kunci (Key)", "Relasi", "Deskripsi & Peran Entitas"]
    for j, h in enumerate(erd_headers):
        cell = erd_table.rows[0].cells[j]
        set_cell_background(cell, "1E293B")
        set_cell_margins(cell, top=120, bottom=120, left=100, right=100)
        p = cell.paragraphs[0]
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        r = p.add_run(h)
        r.bold = True
        r.font.size = Pt(9.5)
        r.font.color.rgb = RGBColor(255, 255, 255)

    erd_data = [
        ("PERSONAL_TRAINER", "trainer_id (PK)", "1 : N (Child)", "Master profil pelatih (nama, kutipan filosofis, kontak wa, slogan)."),
        ("TRAINER_SPECIALTY", "id (PK), trainer_id (FK)", "N : 1 (Parent)", "Daftar tag keahlian singkat yang muncul pada kartu preview depan."),
        ("DETAIL_SPECIALTY", "id (PK), trainer_id (FK)", "N : 1 (Parent)", "Spesialisasi mendalam dengan ikon Material Symbols dan penjelasan."),
        ("APPROACH_POINT", "id (PK), trainer_id (FK)", "N : 1 (Parent)", "Checklist prinsip latihan dan pendekatan bimbingan ramah pemula."),
        ("TRAINER_STORY", "id (PK), trainer_id (FK)", "N : 1 (Parent)", "Paragraf narasi latar belakang dan kisah inspiratif pelatih."),
        ("FACILITY_CATEGORY", "category_id (PK)", "1 : N (Child)", "Kategori klasifikasi alat (Cardio, Strength, Fasilitas Umum)."),
        ("FACILITY_ITEM", "item_id (PK), category_id (FK)", "N : 1 (Parent)", "Detail alat fasilitas (nama, foto lokal, jumlah unit, deskripsi fungsi)."),
        ("WARGYM_PROFILE", "gym_id (PK)", "1 : N (Child)", "Identitas resmi gym (nama, alamat Mojosongo Diwek, link GMaps)."),
        ("SERVICE_PRICING", "service_id (PK), gym_id (FK)", "N : 1 (Parent)", "Daftar tarif harga harian, mingguan, bulanan, dan sesi PT."),
        ("SOCIAL_MEDIA", "id (PK), gym_id (FK)", "N : 1 (Parent)", "Tautan media sosial resmi WARGYM (TikTok, Instagram, WhatsApp).")
    ]
    
    for i, row in enumerate(erd_data):
        tbl_row = erd_table.rows[i+1]
        bg = "F8FAFC" if i % 2 == 1 else "FFFFFF"
        for j, val in enumerate(row):
            c = tbl_row.cells[j]
            set_cell_background(c, bg)
            set_cell_margins(c, top=80, bottom=80, left=100, right=100)
            p = c.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r = p.add_run(val)
            r.font.size = Pt(8.5)
            if j == 0:
                r.bold = True
                r.font.color.rgb = RGBColor(15, 23, 42)
            elif j == 1:
                r.font.color.rgb = RGBColor(225, 29, 72)
                r.bold = True
            else:
                r.font.color.rgb = RGBColor(71, 85, 105)

    doc.add_page_break()

    # ==========================================
    # BAB 6: ALUR KERJA SISTEM (WORKFLOW & FLOWCHART)
    # ==========================================
    add_h1("6. ALUR KERJA SISTEM (SYSTEM WORKFLOW & FLOWCHART)")
    
    add_h2("6.1 Alur Navigasi & Eksplorasi Personal Trainer")
    add_p(
        "Alur interaksi pengguna saat menjelajahi profil personal trainer dirancang dengan konsep Progressive Disclosure: menyajikan ringkasan singkat terlebih dahulu, lalu memberikan akses detail lengkap melalui popup modal tanpa memuat ulang halaman:"
    )
    
    flow_steps = [
        ("Tahap 1: Akses Halaman", "Pengunjung membuka URL website /personal-trainer. Vue Router merender halaman secara dinamis."),
        ("Tahap 2: Rendering Kartu Trainer", "Sistem merender 3 kartu pelatih (Kak Ghion, Coach Fanny, Kak Izaa) dalam susunan 3 kolom simetris pada desktop (lg:grid-cols-3) atau vertikal pada mobile."),
        ("Tahap 3: Pemilihan Aksi Pengguna", "Pengguna dapat membaca kutipan dan ringkasan preview (line-clamp-3) di kartu depan, atau mengklik tombol 'Lihat Profil Lengkap'."),
        ("Tahap 4: Pembukaan Modal Interaktif", "State selectedTrainer diaktifkan. Body browser secara otomatis dikunci (overflow-hidden) untuk mencegah scroll ganda pada latar belakang."),
        ("Tahap 5: Eksplorasi Detail Profil", "Pengguna membaca cerita otentik About Me, rincian keahlian, checklist pendekatan, dan komitmen visi pelatih."),
        ("Tahap 6: Konversi ke WhatsApp", "Pengguna menekan tombol CTA 'Hubungi via WhatsApp'. Sistem membuka tab baru ke wa.me dengan nomor resmi pengelola (+62 895-0836-6293) dan pesan pembuka yang sudah terisi otomatis sesuai nama pelatih.")
    ]
    
    for title, desc in flow_steps:
        add_p(desc, bold_prefix=f"{title}: ")

    add_h2("6.2 Alur Otomatisasi CI/CD Deployment Vercel")
    add_p(
        "Sistem menerapkan otomasi integrasi dan penyebaran berkelanjutan (Continuous Integration & Continuous Deployment). Setiap pembaruan yang didorong ke repositori GitHub akan dideploy ke jaringan CDN global secara otomatis:"
    )
    
    if os.path.exists("ci_cd.png"):
        p_img2 = doc.add_paragraph()
        p_img2.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_img2.paragraph_format.space_before = Pt(8)
        p_img2.paragraph_format.space_after = Pt(4)
        run_img2 = p_img2.add_run()
        run_img2.add_picture("ci_cd.png", width=Inches(6.0))
        
        p_cap2 = doc.add_paragraph()
        p_cap2.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_cap2.paragraph_format.space_after = Pt(12)
        r_cap2 = p_cap2.add_run("Gambar 6.1: Sequence Diagram CI/CD Deployment Pipeline GitHub ke Vercel Edge")
        r_cap2.font.size = Pt(8.5)
        r_cap2.font.italic = True
        r_cap2.font.color.rgb = RGBColor(100, 116, 139)

    doc.add_page_break()

    # ==========================================
    # BAB 7: RIWAYAT PUSH GITHUB & LOG PERBAIKAN
    # ==========================================
    add_h1("7. RIWAYAT PUSH GITHUB & LOG PERBAIKAN (CHANGELOG)")
    add_p(
        "Berikut adalah rekam jejak historis seluruh commit pembaruan kode pada branch main repositori resmi gymwarung/arena-profile. Log ini mendokumentasikan apa yang diperbaiki, tanggal dan waktu pengerjaan, serta kontributor terkait:"
    )
    
    commits = [
        ("22", "60d4520", "2026-10-23 15:19", "gymwarung", "Update changelog table with documentation commit", "Pembaruan tabel riwayat commit di berkas dokumentasi agar mencakup commit dokumentasi terbaru."),
        ("21", "1560c0a", "2026-10-23 15:18", "gymwarung", "Dokumentasi teknis sistem lengkap (DOCUMENTATION.md)", "Pembuatan berkas dokumentasi sistem komprehensif mencakup latar belakang bisnis, arsitektur & tech stack, schema data model, Use Case diagram, ERD, flowchart alur sistem, sequence diagram CI/CD, dan changelog push GitHub."),
        ("20", "5180352", "2026-10-23 14:09", "gymwarung", "Tambah card personal trainer Kak Izaa beserta modal profil lengkap", "Menambahkan profil pelatih ke-3 (Kak Izaa) dengan spesialisasi Weight Loss, Body Shaping, Fitness, & Healthy Lifestyle. Memperluas grid kartu personal trainer menjadi 3 kolom simetris pada layar desktop (lg:grid-cols-3). Menyediakan modal popup lengkap dengan filosofi, visi, dan integrasi WhatsApp."),
        ("19", "6b86d28", "2026-10-23 10:58", "gymwarung", "Trigger deploy update card fanny", "Mengirimkan trigger build otomatis ke Vercel setelah repositori baru gymwarung/arena-profile terhubung, memperbarui aset live production."),
        ("18", "e3daca3", "2026-10-23 10:28", "gymwarung", "Card fanny & Sinkronisasi Author", "Memperbarui kartu Coach Fanny agar berpenampilan simetris dengan kartu Kak Ghion (menggunakan format line-clamp-3 pada cerita About Me). Menyinkronkan identitas commit author ke gymwarung <gymwarung@gmail.com> serta memperbarui package-lock.json (wargym-profile)."),
        ("17", "e829190", "2026-10-05 15:41", "musyafak-a", "Ganti gambar fasilitas Smith Machine dan Chest Press dengan aset lokal", "Mengalihkan ketergantungan tautan eksternal gambar alat Smith Machine dan Chest Press ke file aset lokal di /public/images/ untuk kecepatan muat dan stabilitas tampilan."),
        ("16", "322ba56", "2026-10-05 15:02", "musyafak-a", "PERBAIKAN PT", "Penataan tata letak layout dan penyempurnaan padding pada section profil personal trainer."),
        ("15", "81a76bf", "2026-10-05 14:57", "musyafak-a", "Hapus tombol WhatsApp di samping tombol Tutup pada footer popup modal PT", "Merapikan footer modal agar tombol Tutup berdiri bersih di sudut kanan bawah dan tombol CTA utama difokuskan di bagian banner bawah modal body."),
        ("14", "93d61aa", "2026-10-05 14:47", "musyafak-a", "Hapus badge usia/role dan ikon dumbbell di kedua card personal trainer", "Mengeliminasi elemen badge yang kurang relevan di kartu utama agar desain lebih minimalis, elegan, dan fokus pada nama serta tag keahlian."),
        ("13", "e662abc", "2026-10-05 11:50", "musyafak-a", "Buat kedua card personal trainer (Ghion & Fanny) text-only tanpa foto", "Standardisasi tampilan kartu pelatih berbasis tipografi kuat (text-only aesthetic) berlatar belakang kaca (glass-panel) agar tampilan seimbang tanpa foto placeholder."),
        ("12", "ff649a8", "2026-10-05 11:33", "musyafak-a", "Update card trainer Dika menjadi Fanny dengan deskripsi menyusul", "Pergantian data trainer dari Dika ke Coach Fanny pada struktur data trainer."),
        ("11", "c0411d6", "2026-10-05 11:25", "musyafak-a", "Update card Ghion dengan preview dan popup modal profil lengkap", "Implementasi modal interaktif untuk Coach Ghion yang memuat perjalanan mengatasi autoimun, spesialisasi kebugaran longevity, prinsip latihan, dan visi kepelatihan."),
        ("10", "330cc05", "2026-10-05 10:36", "musyafak-a", "Update gambar card fasilitas", "Integrasi gambar lokal fasilitas pendukung: Musholla (musholla.png), Warung Kopi (warung.jpg), Samsak Tinju (samsak.png), dan Kipas Angin (kipas.png)."),
        ("9", "5994a9b", "2026-10-05 10:17", "musyafak-a", "Sesuaikan dan tambahkan fasilitas baru", "Pemutakhiran daftar inventaris alat: Abductor + Adductor (1 unit), Kamar Mandi/Toilet, Musholla, Free Wi-Fi, dan Sirkulasi Udara Kipas Angin."),
        ("8", "44605a3", "2026-10-05 10:03", "musyafak-a", "Update deskripsi Cerita Kami dengan profil lengkap WARGYM Mojosongo", "Penulisan narasi otentik pada halaman Team.vue tentang asal-usul nama WARGYM (Warung Gym), visi kebersamaan, dan prinsip sehat tidak harus mahal."),
        ("7", "abc9d2c", "2026-10-05 09:57", "musyafak-a", "Update halaman PT: sisakan 2 card", "Restrukturisasi awal halaman Personal Trainer untuk fokus pada 2 pelatih utama."),
        ("6", "a75783d", "2026-10-03 13:12", "musyafak-a", "Hapus gambar & referensi logo lama, ganti favicon ke WARGYM", "Pembersihan aset lama (branding cleanup), penggantian logo dan favicon resmi WARGYM SVG."),
        ("5", "c684089", "2026-10-03 12:52", "musyafak-a", "Update nomor WhatsApp resmi", "Pengalihan nomor kontak di seluruh komponen web ke nomor resmi pengelola: +62 895-0836-6293."),
        ("4", "ff6546b", "2026-10-03 12:36", "musyafak-a", "isi", "Penyempurnaan konten teks dan metadata dasar halaman."),
        ("3", "fae7bda", "2026-10-03 12:21", "musyafak-a", "first commit", "Inisiasi struktur repositori awal pasca migrasi."),
        ("2", "0c92905", "2026-10-02 14:59", "musyafak-a", "docs: update README with deployment and project guide", "Dokumentasi instruksi setup lokal, script npm, dan konfigurasi build."),
        ("1", "bb1139a", "2026-10-02 14:57", "musyafak-a", "Initial commit: Migrasi company profile WARGYM ke Vue 3 + Vite", "Migrasi arsitektur dasar website dari format statis lama ke arsitektur Single Page Application berbasis Vue 3 dan Vite.")
    ]
    
    ch_table = doc.add_table(rows=len(commits)+1, cols=6)
    ch_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    ch_table.autofit = False
    ch_table.columns[0].width = Inches(0.4)
    ch_table.columns[1].width = Inches(0.8)
    ch_table.columns[2].width = Inches(1.1)
    ch_table.columns[3].width = Inches(0.9)
    ch_table.columns[4].width = Inches(1.4)
    ch_table.columns[5].width = Inches(1.67)
    set_table_borders(ch_table, color="E2E8F0", sz="4")
    
    ch_headers = ["No", "Hash", "Tanggal", "Penulis", "Ringkasan Commit", "Detail Perbaikan & Dampak"]
    for j, h in enumerate(ch_headers):
        cell = ch_table.rows[0].cells[j]
        set_cell_background(cell, "1E293B")
        set_cell_margins(cell, top=100, bottom=100, left=60, right=60)
        p = cell.paragraphs[0]
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        r = p.add_run(h)
        r.bold = True
        r.font.size = Pt(8.5)
        r.font.color.rgb = RGBColor(255, 255, 255)

    for i, row in enumerate(commits):
        tbl_row = ch_table.rows[i+1]
        bg = "F8FAFC" if i % 2 == 1 else "FFFFFF"
        for j, val in enumerate(row):
            c = tbl_row.cells[j]
            set_cell_background(c, bg)
            set_cell_margins(c, top=70, bottom=70, left=60, right=60)
            p = c.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            r = p.add_run(val)
            r.font.size = Pt(7.8)
            if j == 0:
                r.bold = True
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            elif j == 1:
                r.bold = True
                r.font.name = "Consolas"
                r.font.color.rgb = RGBColor(225, 29, 72)
            elif j == 4:
                r.bold = True
                r.font.color.rgb = RGBColor(15, 23, 42)
            else:
                r.font.color.rgb = RGBColor(71, 85, 105)

    doc.add_page_break()

    # ==========================================
    # BAB 8: PETUNJUK PEMELIHARAAN & DEPLOYMENT
    # ==========================================
    add_h1("8. PETUNJUK PEMELIHARAAN & DEPLOYMENT")
    
    add_h2("8.1 Menjalankan di Lingkungan Lokal (Local Development)")
    add_p("Pastikan komputer pengembang telah terpasang Node.js v20.x atau versi LTS yang lebih tinggi:")
    
    local_cmds = """# 1. Masuk ke direktori proyek di terminal
cd c:\\laragon\\www\\arenafitness-profile

# 2. Pasang dependensi pustaka
npm install

# 3. Jalankan server pengembang lokal (Hot Module Replacement aktif)
npm run dev"""
    add_code(local_cmds)
    add_p("Aplikasi lokal akan berjalan pada alamat http://localhost:5173/.")

    add_h2("8.2 Melakukan Validasi Build Produksi")
    add_p("Sebelum melakukan commit atau push ke GitHub, selalu lakukan validasi build untuk memastikan tidak ada sintaks JavaScript yang rusak:")
    
    build_cmds = """# Kompilasi bundel aset produksi ke direktori /dist/
npm run build"""
    add_code(build_cmds)

    add_h2("8.3 Prosedur Rilis & Push ke GitHub")
    add_p(
        "Setiap commit baru yang didorong ke cabang main pada repositori resmi akan secara otomatis memicu proses deployment di Vercel Edge Cloud:"
    )
    
    git_cmds = """# 1. Menambahkan perubahan berkas ke staging area
git add .

# 2. Membuat commit dengan pesan deskriptif
git commit -m "Deskripsi fitur atau perbaikan sistem"

# 3. Mendorong pembaruan ke GitHub (memicu Vercel auto-build)
git push origin main"""
    add_code(git_cmds)
    
    add_callout(
        "Hasil rilis live dapat dipantau langsung pada domain publik: https://wargym-profile.vercel.app. Perubahan akan terefleksi secara global dalam waktu rata-rata 30-45 detik pasca git push.",
        title="LIVE PRODUCTION DEPLOYMENT"
    )

    # Save to file
    output_filename = "DOKUMENTASI_SISTEM_WARGYM.docx"
    doc.save(output_filename)
    print(f"Document saved successfully as: {output_filename}")

if __name__ == "__main__":
    create_document()
