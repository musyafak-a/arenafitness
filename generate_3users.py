import matplotlib.pyplot as plt
import matplotlib.patches as patches

def generate_layout_1():
    # Layout 1: Left has Owner/Admin (top) and Kasir (bottom); Right has Member (center)
    fig_w, fig_h = 20.0, 13.0
    fig, ax = plt.subplots(figsize=(fig_w, fig_h), dpi=300)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')
    fig.patch.set_facecolor('white')
    ax.set_facecolor('white')

    sys_left, sys_right = 14.0, 86.0
    sys_top, sys_bottom = 96.0, 7.5
    sys_w = sys_right - sys_left
    sys_h = sys_top - sys_bottom

    # System boundary & header
    boundary = patches.Rectangle((sys_left, sys_bottom), sys_w, sys_h,
                                 edgecolor='black', facecolor='white', linewidth=1.8, zorder=1)
    ax.add_patch(boundary)

    header_h = 4.0
    header_y = sys_top - header_h
    ax.plot([sys_left, sys_right], [header_y, header_y], color='black', linewidth=1.5, zorder=2)
    title_text = "Sistem Informasi Manajemen Layanan Gym Terintegrasi (Web & Mobile) WARGYM Jombang"
    ax.text((sys_left + sys_right) / 2, sys_top - (header_h / 2), title_text,
            ha='center', va='center', fontsize=12.5, fontweight='bold', color='black', zorder=3)

    asp = fig_w / fig_h

    def draw_stick_figure(ax, x, y, name, scale=1.0):
        head_diam_x = 1.9 * scale
        head_diam_y = head_diam_x * asp
        head_center_y = y + 2.5 * scale
        head = patches.Ellipse((x, head_center_y), head_diam_x, head_diam_y,
                               edgecolor='black', facecolor='white', linewidth=1.6, zorder=10)
        ax.add_patch(head)
        torso_top = head_center_y - (head_diam_y / 2)
        torso_bottom = y - 2.0 * scale
        ax.plot([x, x], [torso_top, torso_bottom], color='black', linewidth=1.8, zorder=10)
        arm_w = 2.4 * scale
        arm_y = y + 0.3 * scale
        ax.plot([x - arm_w, x + arm_w], [arm_y, arm_y], color='black', linewidth=1.8, zorder=10)
        leg_w = 1.8 * scale
        leg_bottom = y - 4.6 * scale
        ax.plot([x, x - leg_w], [torso_bottom, leg_bottom], color='black', linewidth=1.8, zorder=10)
        ax.plot([x, x + leg_w], [torso_bottom, leg_bottom], color='black', linewidth=1.8, zorder=10)
        ax.text(x, leg_bottom - 1.2 * scale, name, ha='center', va='top', fontsize=11.5, fontweight='bold', color='black', zorder=10)
        return (x, y + 0.3 * scale)

    # Left actors: Owner/Admin (top), Kasir (bottom)
    pt_owner_admin = draw_stick_figure(ax, 7.0, 68.0, "Owner / Admin", scale=1.0)
    pt_kasir = draw_stick_figure(ax, 7.0, 24.0, "Kasir", scale=1.0)

    # Right actor: Member (vertically centered)
    pt_member = draw_stick_figure(ax, 93.0, 48.0, "Member", scale=1.0)

    # Left Column: 10 use cases
    # Top 6 for Owner/Admin, Bottom 4 for Kasir
    left_uc_y = [88.5 - i * 7.5 for i in range(10)]
    left_usecases = [
        "Lihat Dashboard Operasional & Finansial",
        "Cetak atau Ekspor Laporan Rekapitulasi",
        "Kelola Master Paket Membership",
        "Kelola Data Member dan Langganan",
        "Kelola Master Suplemen dan Mutasi Stok",
        "Catat Pos Pengeluaran Operasional",
        "Kelola Pengumuman dan Fasilitas Gym",
        "Transaksi Kasir POS dan Cetak Struk",
        "Validasi Presensi Check-in Member",
        "Cetak Rekap Transaksi Kasir per Shift"
    ]

    # Right Column: 8 use cases for Member (centered from y=81 down to y=28.5)
    right_uc_y = [84.75 - i * 7.5 for i in range(8)]
    right_usecases = [
        "Akses Informasi Profil & Fasilitas Gym",
        "Tampilkan Kartu Digital dan Barcode QR",
        "Presensi Mandiri (Self Check-in)",
        "Checkout Perpanjangan Membership Online",
        "Akses Panduan Gerakan dan Otot Target",
        "Pencatatan Beban Latihan dan Rest Timer",
        "Pantau Grafik Progressive Overload",
        "Kirim Kritik, Saran, dan Masukan Layanan"
    ]

    ellipse_w = 21.0
    ellipse_h = 4.0
    left_col_x = 29.5
    right_col_x = 70.5

    left_pts = {}
    right_pts = {}

    for text, y in zip(left_usecases, left_uc_y):
        el = patches.Ellipse((left_col_x, y), ellipse_w, ellipse_h,
                             edgecolor='black', facecolor='white', linewidth=1.2, zorder=5)
        ax.add_patch(el)
        ax.text(left_col_x, y, text, ha='center', va='center', fontsize=8.8, color='black', zorder=6)
        left_pts[text] = (left_col_x - ellipse_w / 2, y)

    for text, y in zip(right_usecases, right_uc_y):
        el = patches.Ellipse((right_col_x, y), ellipse_w, ellipse_h,
                             edgecolor='black', facecolor='white', linewidth=1.2, zorder=5)
        ax.add_patch(el)
        ax.text(right_col_x, y, text, ha='center', va='center', fontsize=8.8, color='black', zorder=6)
        right_pts[text] = (right_col_x + ellipse_w / 2, y)

    # Connections
    # Owner/Admin to top 7
    for uc in left_usecases[:7]:
        ax.plot([pt_owner_admin[0], left_pts[uc][0]], [pt_owner_admin[1], left_pts[uc][1]],
                color='black', linewidth=1.1, zorder=3)

    # Kasir to bottom 3 + Validasi + Transaksi
    for uc in left_usecases[7:]:
        ax.plot([pt_kasir[0], left_pts[uc][0]], [pt_kasir[1], left_pts[uc][1]],
                color='black', linewidth=1.1, zorder=3)

    # Member to all 8 right usecases
    for uc in right_usecases:
        ax.plot([pt_member[0], right_pts[uc][0]], [pt_member[1], right_pts[uc][1]],
                color='black', linewidth=1.1, zorder=3)

    plt.tight_layout()
    plt.savefig("wargym_usecase_3users_layout1.png", bbox_inches='tight', dpi=300, facecolor='white')
    print("Generated layout 1 successfully!")

def generate_layout_2():
    # Layout 2: Left has Owner/Admin; Right has Kasir (top) and Member (bottom)
    fig_w, fig_h = 20.0, 13.0
    fig, ax = plt.subplots(figsize=(fig_w, fig_h), dpi=300)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')
    fig.patch.set_facecolor('white')
    ax.set_facecolor('white')

    sys_left, sys_right = 14.0, 86.0
    sys_top, sys_bottom = 96.0, 7.5
    sys_w = sys_right - sys_left
    sys_h = sys_top - sys_bottom

    boundary = patches.Rectangle((sys_left, sys_bottom), sys_w, sys_h,
                                 edgecolor='black', facecolor='white', linewidth=1.8, zorder=1)
    ax.add_patch(boundary)

    header_h = 4.0
    header_y = sys_top - header_h
    ax.plot([sys_left, sys_right], [header_y, header_y], color='black', linewidth=1.5, zorder=2)
    title_text = "Sistem Informasi Manajemen Layanan Gym Terintegrasi (Web & Mobile) WARGYM Jombang"
    ax.text((sys_left + sys_right) / 2, sys_top - (header_h / 2), title_text,
            ha='center', va='center', fontsize=12.5, fontweight='bold', color='black', zorder=3)

    asp = fig_w / fig_h

    def draw_stick_figure(ax, x, y, name, scale=1.0):
        head_diam_x = 1.9 * scale
        head_diam_y = head_diam_x * asp
        head_center_y = y + 2.5 * scale
        head = patches.Ellipse((x, head_center_y), head_diam_x, head_diam_y,
                               edgecolor='black', facecolor='white', linewidth=1.6, zorder=10)
        ax.add_patch(head)
        torso_top = head_center_y - (head_diam_y / 2)
        torso_bottom = y - 2.0 * scale
        ax.plot([x, x], [torso_top, torso_bottom], color='black', linewidth=1.8, zorder=10)
        arm_w = 2.4 * scale
        arm_y = y + 0.3 * scale
        ax.plot([x - arm_w, x + arm_w], [arm_y, arm_y], color='black', linewidth=1.8, zorder=10)
        leg_w = 1.8 * scale
        leg_bottom = y - 4.6 * scale
        ax.plot([x, x - leg_w], [torso_bottom, leg_bottom], color='black', linewidth=1.8, zorder=10)
        ax.plot([x, x + leg_w], [torso_bottom, leg_bottom], color='black', linewidth=1.8, zorder=10)
        ax.text(x, leg_bottom - 1.2 * scale, name, ha='center', va='top', fontsize=11.5, fontweight='bold', color='black', zorder=10)
        return (x, y + 0.3 * scale)

    # Left: Owner/Admin (centered vertically on left)
    pt_owner_admin = draw_stick_figure(ax, 7.0, 54.0, "Owner / Admin", scale=1.0)

    # Right: Kasir (top), Member (bottom)
    pt_kasir = draw_stick_figure(ax, 93.0, 74.5, "Kasir", scale=1.0)
    pt_member = draw_stick_figure(ax, 93.0, 33.5, "Member", scale=1.0)

    left_uc_y = [88.5 - i * 7.6 for i in range(10)]
    left_usecases = [
        "Lihat Dashboard Operasional & Finansial",
        "Cetak atau Ekspor Laporan Rekapitulasi",
        "Kelola Master Paket Membership",
        "Kelola Data Member dan Langganan",
        "Kelola Master Suplemen dan Mutasi Stok",
        "Catat Pos Pengeluaran Operasional",
        "Pantau Log Mutasi dan Stok Opname",
        "Kelola Pengumuman dan Informasi Fasilitas",
        "Kelola Kritik, Saran, dan Masukan Member",
        "Kelola Pengaturan Sistem dan Profil Gym"
    ]

    kasir_uc_y = [left_uc_y[0], left_uc_y[1], left_uc_y[2], left_uc_y[3]]
    kasir_usecases = [
        "Transaksi Kasir POS dan Cetak Struk",
        "Validasi Presensi Check-in Member",
        "Kelola Penjualan Suplemen di Kasir",
        "Cetak Rekap Transaksi Kasir per Shift"
    ]

    member_uc_y = [left_uc_y[5], left_uc_y[6], left_uc_y[7], left_uc_y[8], left_uc_y[9]]
    member_usecases = [
        "Akses Kartu Digital dan Barcode QR",
        "Presensi Mandiri (Self Check-in)",
        "Checkout Perpanjangan Membership Online",
        "Akses Panduan Gerakan dan Otot Target",
        "Pencatatan Beban Latihan dan Rest Timer"
    ]

    ellipse_w = 21.0
    ellipse_h = 4.0
    left_col_x = 29.5
    right_col_x = 70.5

    left_pts = {}
    right_pts = {}

    for text, y in zip(left_usecases, left_uc_y):
        el = patches.Ellipse((left_col_x, y), ellipse_w, ellipse_h,
                             edgecolor='black', facecolor='white', linewidth=1.2, zorder=5)
        ax.add_patch(el)
        ax.text(left_col_x, y, text, ha='center', va='center', fontsize=8.8, color='black', zorder=6)
        left_pts[text] = (left_col_x - ellipse_w / 2, y)

    for text, y in zip(kasir_usecases, kasir_uc_y):
        el = patches.Ellipse((right_col_x, y), ellipse_w, ellipse_h,
                             edgecolor='black', facecolor='white', linewidth=1.2, zorder=5)
        ax.add_patch(el)
        ax.text(right_col_x, y, text, ha='center', va='center', fontsize=8.8, color='black', zorder=6)
        right_pts[text] = (right_col_x + ellipse_w / 2, y)

    for text, y in zip(member_usecases, member_uc_y):
        el = patches.Ellipse((right_col_x, y), ellipse_w, ellipse_h,
                             edgecolor='black', facecolor='white', linewidth=1.2, zorder=5)
        ax.add_patch(el)
        ax.text(right_col_x, y, text, ha='center', va='center', fontsize=8.8, color='black', zorder=6)
        right_pts[text] = (right_col_x + ellipse_w / 2, y)

    for uc in left_usecases:
        ax.plot([pt_owner_admin[0], left_pts[uc][0]], [pt_owner_admin[1], left_pts[uc][1]],
                color='black', linewidth=1.1, zorder=3)

    for uc in kasir_usecases:
        ax.plot([pt_kasir[0], right_pts[uc][0]], [pt_kasir[1], right_pts[uc][1]],
                color='black', linewidth=1.1, zorder=3)

    for uc in member_usecases:
        ax.plot([pt_member[0], right_pts[uc][0]], [pt_member[1], right_pts[uc][1]],
                color='black', linewidth=1.1, zorder=3)

    plt.tight_layout()
    plt.savefig("wargym_usecase_3users_layout2.png", bbox_inches='tight', dpi=300, facecolor='white')
    print("Generated layout 2 successfully!")

if __name__ == '__main__':
    generate_layout_1()
    generate_layout_2()
