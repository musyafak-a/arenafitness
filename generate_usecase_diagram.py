import matplotlib.pyplot as plt
import matplotlib.patches as patches

# Setup figure
plt.rcParams['font.sans-serif'] = 'Arial'
plt.rcParams['font.family'] = 'sans-serif'

fig, ax = plt.subplots(figsize=(19, 15), dpi=300)
ax.set_xlim(0, 100)
ax.set_ylim(0, 100)
ax.axis('off')

# System Boundary
sys_box = patches.FancyBboxPatch((24, 2), 52, 96, boxstyle="round,pad=1.2,rounding_size=1.5", 
                                 edgecolor="#0284c7", facecolor="#f8fafc", linewidth=2.5, linestyle='-')
ax.add_patch(sys_box)

# System Title Header Box
header_box = patches.FancyBboxPatch((26, 93.5), 48, 4.0, boxstyle="round,pad=0.5,rounding_size=0.8",
                                    edgecolor="#0284c7", facecolor="#0284c7", linewidth=1.5)
ax.add_patch(header_box)
ax.text(50, 95.5, "Platform Layanan Gym Terintegrasi (Web & Mobile) - WARGYM Jombang", 
        ha='center', va='center', fontsize=11.5, fontweight='bold', color='white')

# Actor Drawing Function
def draw_actor(ax, x, y, name, role_type="human", subtitle=None):
    if role_type == "human":
        # Head
        head = patches.Circle((x, y + 2.0), 0.9, edgecolor="#1e293b", facecolor="#ffffff", linewidth=1.8, zorder=5)
        ax.add_patch(head)
        # Body
        ax.plot([x, x], [y + 1.1, y - 1.1], color="#1e293b", linewidth=2, zorder=5)
        # Arms
        ax.plot([x - 1.7, x + 1.7], [y + 0.3, y + 0.3], color="#1e293b", linewidth=2, zorder=5)
        # Legs
        ax.plot([x, x - 1.4], [y - 1.1, y - 2.9], color="#1e293b", linewidth=2, zorder=5)
        ax.plot([x, x + 1.4], [y - 1.1, y - 2.9], color="#1e293b", linewidth=2, zorder=5)
        # Label Box
        box_h = 3.2 if subtitle else 2.2
        box_y = y - 4.8 if subtitle else y - 4.3
        lbl_box = patches.FancyBboxPatch((x - 8.5, box_y), 17, box_h, boxstyle="round,pad=0.3",
                                         edgecolor="#94a3b8", facecolor="#ffffff", linewidth=1.2, zorder=6)
        ax.add_patch(lbl_box)
        if subtitle:
            ax.text(x, box_y + 2.0, name, ha='center', va='center', fontsize=9.2, fontweight='bold', color="#0f172a", zorder=7)
            ax.text(x, box_y + 0.8, subtitle, ha='center', va='center', fontsize=7.5, fontstyle='italic', color="#64748b", zorder=7)
        else:
            ax.text(x, box_y + 1.1, name, ha='center', va='center', fontsize=9.2, fontweight='bold', color="#0f172a", zorder=7)
    else:
        # System actor
        box = patches.FancyBboxPatch((x - 9, y - 2.2), 18, 4.5, boxstyle="round,pad=0.4",
                                     edgecolor="#d97706", facecolor="#fffbeb", linewidth=1.6, zorder=5)
        ax.add_patch(box)
        ax.text(x, y + 0.7, "«external system»", ha='center', va='center', fontsize=7.8, fontstyle='italic', color="#b45309", zorder=6)
        ax.text(x, y - 0.9, name, ha='center', va='center', fontsize=8.8, fontweight='bold', color="#78350f", zorder=6)

# Use Cases Definition (name, y_pos, category_color)
use_cases = [
    # Admin & Management Module (Top)
    ("Kelola Akun Staf & Hak Akses", 90.0, "#0369a1"),
    ("Pantau Dashboard & Finansial Gym", 85.0, "#0369a1"),
    ("Cetak & Ekspor Laporan Rekapitulasi", 80.0, "#0369a1"),
    ("Kelola Master Paket Membership", 75.0, "#0369a1"),
    ("Kelola Data Member & Langganan", 70.0, "#0369a1"),
    ("Kelola Produk Suplemen & Kartu Stok", 65.0, "#0369a1"),
    ("Catat Pos Biaya Operasional", 60.0, "#0369a1"),
    ("Kelola Pengumuman & Kritik/Saran", 55.0, "#0369a1"),
    
    # Cashier POS & Check-in Module (Middle)
    ("Transaksi Kasir POS & Cetak Struk", 50.0, "#0284c7"),
    ("Validasi Presensi Check-in Member", 45.0, "#0284c7"),
    
    # Member & Self-Service Module (Lower)
    ("Akses Informasi Profil & Fasilitas Gym", 40.0, "#0d9488"),
    ("Tampilkan Kartu Digital & Barcode QR", 35.0, "#0d9488"),
    ("Presensi Mandiri (Self-Service Check-in)", 30.0, "#0d9488"),
    ("Checkout Membership Online (Midtrans)", 25.0, "#0d9488"),
    
    # Modul Asisten Latihan Mandiri (Core Innovation - Mobile)
    ("Akses Panduan Gerakan & Otot Target", 20.0, "#7c3aed"),
    ("Pencatatan Beban Latihan & Rest Timer (Logger)", 15.0, "#7c3aed"),
    ("Pantau Grafik Progres (Progressive Overload)", 10.0, "#7c3aed"),
    ("Notifikasi Pengingat Masa Aktif WhatsApp", 5.0, "#16a34a"),
]

uc_coords = {}

# Draw Use Cases
for name, y, col in use_cases:
    ellipse = patches.FancyBboxPatch((30, y - 1.6), 40, 3.2, boxstyle="round,pad=0.3,rounding_size=1.6",
                                     edgecolor=col, facecolor="#ffffff", linewidth=1.4, zorder=4)
    ax.add_patch(ellipse)
    ax.text(50, y, name, ha='center', va='center', fontsize=8.8, fontweight='600', color=col, zorder=5)
    uc_coords[name] = (30, 70, y)

# Actors Definition (x, y, type, subtitle)
actors = {
    "Master Admin": (12, 85, "human", "(Owner)"),
    "Admin Gym": (12, 65, "human", "(Staf Admin)"),
    "Kasir": (12, 47, "human", "(Point of Sale)"),
    "Member Gym": (88, 26, "human", "(Web & Mobile)"),
    "Payment Gateway": (88, 12, "system", None),
    "WhatsApp Gateway": (88, 5, "system", None),
}

for name, (ax_x, ax_y, atype, sub) in actors.items():
    draw_actor(ax, ax_x, ax_y, name, atype, sub)

# Associations (Actor, UseCase, LineStyle, LineColor)
connections = [
    # Master Admin
    ("Master Admin", "Kelola Akun Staf & Hak Akses", "-", "#334155"),
    ("Master Admin", "Pantau Dashboard & Finansial Gym", "-", "#334155"),
    ("Master Admin", "Cetak & Ekspor Laporan Rekapitulasi", "-", "#334155"),
    ("Master Admin", "Kelola Master Paket Membership", "-", "#334155"),
    ("Master Admin", "Kelola Data Member & Langganan", "-", "#334155"),

    # Admin Gym
    ("Admin Gym", "Cetak & Ekspor Laporan Rekapitulasi", "-", "#64748b"),
    ("Admin Gym", "Kelola Master Paket Membership", "-", "#64748b"),
    ("Admin Gym", "Kelola Data Member & Langganan", "-", "#64748b"),
    ("Admin Gym", "Kelola Produk Suplemen & Kartu Stok", "-", "#64748b"),
    ("Admin Gym", "Catat Pos Biaya Operasional", "-", "#64748b"),
    ("Admin Gym", "Kelola Pengumuman & Kritik/Saran", "-", "#64748b"),
    ("Admin Gym", "Transaksi Kasir POS & Cetak Struk", "-", "#64748b"),
    ("Admin Gym", "Validasi Presensi Check-in Member", "-", "#64748b"),

    # Kasir
    ("Kasir", "Transaksi Kasir POS & Cetak Struk", "-", "#0284c7"),
    ("Kasir", "Validasi Presensi Check-in Member", "-", "#0284c7"),

    # Member Gym (Web & Mobile)
    ("Member Gym", "Akses Informasi Profil & Fasilitas Gym", "-", "#0d9488"),
    ("Member Gym", "Tampilkan Kartu Digital & Barcode QR", "-", "#0d9488"),
    ("Member Gym", "Presensi Mandiri (Self-Service Check-in)", "-", "#0d9488"),
    ("Member Gym", "Checkout Membership Online (Midtrans)", "-", "#0d9488"),
    ("Member Gym", "Akses Panduan Gerakan & Otot Target", "-", "#7c3aed"),
    ("Member Gym", "Pencatatan Beban Latihan & Rest Timer (Logger)", "-", "#7c3aed"),
    ("Member Gym", "Pantau Grafik Progres (Progressive Overload)", "-", "#7c3aed"),

    # External Systems
    ("Payment Gateway", "Checkout Membership Online (Midtrans)", "--", "#d97706"),
    ("WhatsApp Gateway", "Notifikasi Pengingat Masa Aktif WhatsApp", "--", "#16a34a"),
]

# Draw Association Lines
for act_name, uc_name, style, col in connections:
    act_x, act_y, atype, _ = actors[act_name]
    left_x, right_x, uc_y = uc_coords[uc_name]

    if act_x < 50:
        start_x = act_x + 3.0 if atype == "human" else act_x + 9.0
        start_y = act_y - 0.5
        end_x = left_x
        end_y = uc_y
    else:
        start_x = act_x - 3.0 if atype == "human" else act_x - 9.0
        start_y = act_y - 0.5
        end_x = right_x
        end_y = uc_y

    ax.plot([start_x, end_x], [start_y, end_y], linestyle=style, color=col, linewidth=1.2, alpha=0.85, zorder=2)

plt.tight_layout()
plt.savefig("wargym_usecase_diagram.png", bbox_inches='tight', dpi=300)
print("Updated wargym_usecase_diagram.png successfully!")
