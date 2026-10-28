import matplotlib.pyplot as plt
import matplotlib.patches as patches

# Setup figure with pure black and white styling, matching Zidan's diagram
plt.rcParams['font.sans-serif'] = 'Arial'
plt.rcParams['font.family'] = 'sans-serif'

fig_w = 20.0
fig_h = 13.0
fig, ax = plt.subplots(figsize=(fig_w, fig_h), dpi=300)
ax.set_xlim(0, 100)
ax.set_ylim(0, 100)
ax.axis('off')

# Set background to pure white
fig.patch.set_facecolor('white')
ax.set_facecolor('white')

# 1. Main System Boundary Box (Solid black border, white fill)
sys_left = 13.5
sys_right = 86.5
sys_top = 96.0
sys_bottom = 7.5
sys_w = sys_right - sys_left
sys_h = sys_top - sys_bottom

# Outer rectangle
boundary = patches.Rectangle((sys_left, sys_bottom), sys_w, sys_h,
                             edgecolor='black', facecolor='white', linewidth=1.8, zorder=1)
ax.add_patch(boundary)

# Header dividing line
header_h = 4.0
header_y = sys_top - header_h
ax.plot([sys_left, sys_right], [header_y, header_y], color='black', linewidth=1.5, zorder=2)

# Header Title Text
title_text = "Sistem Informasi Manajemen Layanan Gym Terintegrasi (Web & Mobile) WARGYM Jombang"
ax.text((sys_left + sys_right) / 2, sys_top - (header_h / 2), title_text,
        ha='center', va='center', fontsize=12.5, fontweight='bold', color='black', zorder=3)

# Exact aspect ratio multiplier to ensure geometric circle on 20x13 canvas:
# pixel_w = (dx / 100) * (fig_w * dpi), pixel_h = (dy / 100) * (fig_h * dpi)
# pixel_w == pixel_h  =>  dx * fig_w == dy * fig_h  =>  dy = dx * (fig_w / fig_h)
asp = fig_w / fig_h  # 20.0 / 13.0 = 1.53846

# Function to draw clean UML Stick Figure
def draw_stick_figure(ax, x, y, name, scale=1.0):
    # Head (Geometric circle via Ellipse with aspect ratio compensation)
    head_diam_x = 1.9 * scale
    head_diam_y = head_diam_x * asp
    head_center_y = y + 2.5 * scale
    head = patches.Ellipse((x, head_center_y), head_diam_x, head_diam_y,
                           edgecolor='black', facecolor='white', linewidth=1.6, zorder=10)
    ax.add_patch(head)
    
    # Body (Torso)
    torso_top = head_center_y - (head_diam_y / 2)
    torso_bottom = y - 2.0 * scale
    ax.plot([x, x], [torso_top, torso_bottom], color='black', linewidth=1.8, zorder=10)
    
    # Chest point (for association lines origin)
    chest_y = y + 0.3 * scale
    
    # Arms (horizontal line)
    arm_w = 2.4 * scale
    arm_y = y + 0.3 * scale
    ax.plot([x - arm_w, x + arm_w], [arm_y, arm_y], color='black', linewidth=1.8, zorder=10)
    
    # Legs (inverted V)
    leg_w = 1.8 * scale
    leg_bottom = y - 4.6 * scale
    ax.plot([x, x - leg_w], [torso_bottom, leg_bottom], color='black', linewidth=1.8, zorder=10)
    ax.plot([x, x + leg_w], [torso_bottom, leg_bottom], color='black', linewidth=1.8, zorder=10)
    
    # Name Label below feet
    ax.text(x, leg_bottom - 1.2 * scale, name, ha='center', va='top', fontsize=11.5, fontweight='bold', color='black', zorder=10)
    
    return (x, chest_y)

# Draw Actors
# Left actors: Owner (top), Admin (bottom)
actor_owner_pt = draw_stick_figure(ax, 6.8, 78.5, "Owner", scale=1.0)
actor_admin_pt = draw_stick_figure(ax, 6.8, 38.5, "Admin", scale=1.0)

# Right actors: Kasir (top), Member (bottom)
actor_kasir_pt = draw_stick_figure(ax, 93.2, 74.5, "Kasir", scale=1.0)
actor_member_pt = draw_stick_figure(ax, 93.2, 33.5, "Member", scale=1.0)

# Left Column Use Cases (10 items)
# Spaced perfectly from y=88.5 down to y=20.1
left_uc_y = [88.5 - i * 7.6 for i in range(10)]

left_usecases = [
    "Lihat Dashboard Operasional & Finansial",
    "Cetak atau Ekspor Laporan Rekapitulasi",
    "Kelola Akun Staf dan Hak Akses",
    "Kelola Master Paket Membership",
    "Kelola Data Member dan Langganan",
    "Kelola Master Suplemen dan Mutasi Stok",
    "Catat Pos Pengeluaran Operasional",
    "Pantau Log Mutasi dan Stok Opname",
    "Kelola Pengumuman dan Informasi Fasilitas",
    "Kelola Kritik, Saran, dan Masukan Member"
]

# Right Column Use Cases
# Top group: Kasir (4 use cases, aligned with rows 0, 1, 2, 3 of left column)
kasir_uc_y = [left_uc_y[0], left_uc_y[1], left_uc_y[2], left_uc_y[3]]
kasir_usecases = [
    "Transaksi Kasir POS dan Cetak Struk",
    "Validasi Presensi Check-in Member",
    "Kelola Penjualan Suplemen di Kasir",
    "Cetak Rekap Transaksi Kasir per Shift"
]

# Bottom group: Member (5 use cases, aligned with rows 5, 6, 7, 8, 9 of left column)
# Row 4 is intentionally left blank as a visual separator, matching Zidan's diagram layout!
member_uc_y = [left_uc_y[5], left_uc_y[6], left_uc_y[7], left_uc_y[8], left_uc_y[9]]
member_usecases = [
    "Akses Kartu Digital dan Barcode QR",
    "Presensi Mandiri (Self Check-in)",
    "Checkout Perpanjangan Membership Online",
    "Akses Panduan Gerakan dan Otot Target",
    "Pencatatan Beban Latihan dan Rest Timer"
]

# Ellipse dimensions
ellipse_w = 21.0
ellipse_h = 4.0
left_col_x = 29.5   # Center of left column ellipses
right_col_x = 70.5  # Center of right column ellipses

# Dictionary to store ellipse connection points
left_pts = {}
right_pts = {}

# Draw Left Column Ellipses
for text, y in zip(left_usecases, left_uc_y):
    el = patches.Ellipse((left_col_x, y), ellipse_w, ellipse_h,
                         edgecolor='black', facecolor='white', linewidth=1.2, zorder=5)
    ax.add_patch(el)
    ax.text(left_col_x, y, text, ha='center', va='center', fontsize=8.8, color='black', zorder=6)
    left_pts[text] = (left_col_x - ellipse_w / 2, y)

# Draw Right Column Ellipses - Kasir
for text, y in zip(kasir_usecases, kasir_uc_y):
    el = patches.Ellipse((right_col_x, y), ellipse_w, ellipse_h,
                         edgecolor='black', facecolor='white', linewidth=1.2, zorder=5)
    ax.add_patch(el)
    ax.text(right_col_x, y, text, ha='center', va='center', fontsize=8.8, color='black', zorder=6)
    right_pts[text] = (right_col_x + ellipse_w / 2, y)

# Draw Right Column Ellipses - Member
for text, y in zip(member_usecases, member_uc_y):
    el = patches.Ellipse((right_col_x, y), ellipse_w, ellipse_h,
                         edgecolor='black', facecolor='white', linewidth=1.2, zorder=5)
    ax.add_patch(el)
    ax.text(right_col_x, y, text, ha='center', va='center', fontsize=8.8, color='black', zorder=6)
    right_pts[text] = (right_col_x + ellipse_w / 2, y)

# ══════════════════════════════════════════════════════════════════════════════
# Draw Association Lines (Solid black lines, straight from actor to ellipse edge)
# ══════════════════════════════════════════════════════════════════════════════

# 1. Owner Lines (Connects to Usecases 1, 2, 3, 4, 5)
owner_targets = [
    "Lihat Dashboard Operasional & Finansial",
    "Cetak atau Ekspor Laporan Rekapitulasi",
    "Kelola Akun Staf dan Hak Akses",
    "Kelola Master Paket Membership",
    "Kelola Data Member dan Langganan"
]
for target in owner_targets:
    target_pt = left_pts[target]
    ax.plot([actor_owner_pt[0], target_pt[0]], [actor_owner_pt[1], target_pt[1]],
            color='black', linewidth=1.1, zorder=3)

# 2. Admin Lines (Connects to Usecases 2, 4, 5, 6, 7, 8, 9, 10)
admin_targets = [
    "Cetak atau Ekspor Laporan Rekapitulasi",
    "Kelola Master Paket Membership",
    "Kelola Data Member dan Langganan",
    "Kelola Master Suplemen dan Mutasi Stok",
    "Catat Pos Pengeluaran Operasional",
    "Pantau Log Mutasi dan Stok Opname",
    "Kelola Pengumuman dan Informasi Fasilitas",
    "Kelola Kritik, Saran, dan Masukan Member"
]
for target in admin_targets:
    target_pt = left_pts[target]
    ax.plot([actor_admin_pt[0], target_pt[0]], [actor_admin_pt[1], target_pt[1]],
            color='black', linewidth=1.1, zorder=3)

# 3. Kasir Lines (Connects to all 4 Kasir Usecases)
for target in kasir_usecases:
    target_pt = right_pts[target]
    ax.plot([actor_kasir_pt[0], target_pt[0]], [actor_kasir_pt[1], target_pt[1]],
            color='black', linewidth=1.1, zorder=3)

# 4. Member Lines (Connects to all 5 Member Usecases)
for target in member_usecases:
    target_pt = right_pts[target]
    ax.plot([actor_member_pt[0], target_pt[0]], [actor_member_pt[1], target_pt[1]],
            color='black', linewidth=1.1, zorder=3)

# Save image
output_path = "wargym_usecase_diagram_bw.png"
plt.tight_layout()
plt.savefig(output_path, bbox_inches='tight', dpi=300, facecolor='white')
print(f"Generated {output_path} successfully!")

