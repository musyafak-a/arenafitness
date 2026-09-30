@extends('layouts.member-front')
@section('title', 'Informasi & Layanan | WARGYM Jombang')
@php 
$activeNav = 'service';
@endphp

@section('content')
<main class="pt-20">
    <!-- Hero Header -->
    <section class="relative pt-16 pb-14 md:pt-24 md:pb-20 metal-grid border-b border-white/10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-transparent to-background pointer-events-none"></div>
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16 relative z-10 text-center">
            <h1 class="font-headline-lg text-4xl sm:text-6xl md:text-7xl uppercase italic tracking-tight text-white mb-6 leading-[0.95]">
                INFORMASI <span class="text-brand-red">LAYANAN</span>
            </h1>
        </div>
    </section>

    <!-- Section 1: Jam Operasional -->
    <section class="py-16 bg-[#131313] border-b border-white/10 relative">
        <div class="max-w-screen-xl mx-auto px-6 md:px-16">
            <div class="mb-12 text-center">
                <h2 class="font-headline-md text-3xl uppercase tracking-wider text-white">JAM OPERASIONAL</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-2 gap-2 sm:gap-6 max-w-4xl mx-auto">
                <!-- Senin-Sabtu -->
                <div class="glass-panel p-3 sm:p-6 md:p-8 flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-outlined text-white/50 text-2xl sm:text-4xl mb-2 sm:mb-4">calendar_month</span>
                    <h3 class="font-headline-md text-[10px] sm:text-2xl text-white tracking-wide uppercase mb-1 sm:mb-2">Senin - Sabtu</h3>
                    <div class="flex items-end gap-1.5 sm:gap-3">
                        <span class="font-display-xl text-xl sm:text-4xl md:text-5xl text-brand-red leading-none">07:00</span>
                        <span class="text-white/30 text-sm sm:text-2xl mb-0 sm:mb-1">-</span>
                        <span class="font-display-xl text-xl sm:text-4xl md:text-5xl text-white leading-none">21:00</span>
                    </div>
                </div>
                <!-- Minggu -->
                <div class="glass-panel border-brand-red/30 p-3 sm:p-6 md:p-8 flex flex-col items-center justify-center text-center relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-0 h-0 border-t-[20px] sm:border-t-[40px] border-l-[20px] sm:border-l-[40px] border-t-brand-red/40 border-l-transparent"></div>
                    <span class="material-symbols-outlined text-brand-red/80 text-2xl sm:text-4xl mb-2 sm:mb-4">event</span>
                    <h3 class="font-headline-md text-[10px] sm:text-2xl text-white tracking-wide uppercase mb-1 sm:mb-2">Minggu</h3>
                    <div class="flex items-end gap-1.5 sm:gap-3 relative z-10">
                        <span class="font-display-xl text-xl sm:text-4xl md:text-5xl text-brand-red leading-none">10:00</span>
                        <span class="text-white/30 text-sm sm:text-2xl mb-0 sm:mb-1">-</span>
                        <span class="font-display-xl text-xl sm:text-4xl md:text-5xl text-white leading-none">21:00</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Harga / Pricing -->
    <section class="py-16 bg-[#131313] border-b border-white/10 relative">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
            <div class="mb-12 text-center">
                <h2 class="font-headline-md text-3xl uppercase tracking-wider text-white">DAFTAR HARGA</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Harian PT -->
                <div class="glass-panel p-6 md:p-8 flex flex-col border border-white/10 hover:border-brand-red/50 transition-colors relative">
                    <h3 class="font-headline-md text-2xl text-white uppercase mb-2">HARIAN PT</h3>
                    <div class="font-body-md text-sm text-on-surface-variant mb-6">Paket harian dengan Personal Trainer</div>
                    
                    <ul class="flex flex-col gap-3 mb-8 flex-grow">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">Pendampingan PT 1-on-1</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">Akses semua alat GYM</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">Program latihan hari tersebut</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="font-body-md text-brand-red text-sm font-bold">Rp</span>
                            <span class="font-display-xl text-4xl text-white">30.000</span>
                            <span class="font-body-md text-on-surface-variant text-sm">/hari</span>
                        </div>
                        <a href="https://wa.me/6289508366293" class="btn-secondary w-full text-center text-sm py-3">DAFTAR SEKARANG</a>
                    </div>
                </div>

                <!-- Card 2: Harian GYM -->
                <div class="glass-panel p-6 md:p-8 flex flex-col border border-white/10 hover:border-white/50 transition-colors relative">
                    <h3 class="font-headline-md text-2xl text-white uppercase mb-2">HARIAN GYM</h3>
                    <div class="font-body-md text-sm text-on-surface-variant mb-6">Paket latihan mandiri harian</div>
                    
                    <ul class="flex flex-col gap-3 mb-8 flex-grow">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">Akses semua alat GYM</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-white/30 text-[20px]">close</span>
                            <span class="text-white/50 text-sm line-through">Pendampingan PT</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-white/30 text-[20px]">close</span>
                            <span class="text-white/50 text-sm line-through">Program latihan</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="font-body-md text-brand-red text-sm font-bold">Rp</span>
                            <span class="font-display-xl text-4xl text-white">5.000</span>
                            <span class="font-body-md text-on-surface-variant text-sm">/hari</span>
                        </div>
                        <a href="{{ route('member.contact') }}" class="btn-secondary w-full text-center text-sm py-3">DAFTAR SEKARANG</a>
                    </div>
                </div>

                <!-- Card 3: Membership PT -->
                <div class="glass-panel p-6 md:p-8 flex flex-col border border-brand-red/50 relative overflow-hidden">
                    <div class="absolute -right-12 top-6 bg-brand-red text-black font-label-caps font-bold text-[10px] uppercase tracking-wider py-1 px-12 rotate-45">REKOMENDASI</div>
                    <h3 class="font-headline-md text-2xl text-white uppercase mb-2">MEMBERSHIP PT</h3>
                    <div class="font-body-md text-sm text-on-surface-variant mb-6">Paket bulanan dengan Personal Trainer</div>
                    
                    <ul class="flex flex-col gap-3 mb-8 flex-grow">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">10x Pertemuan sebulan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">Program latihan terstruktur</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">Bebas pakai alat GYM</span>
                        </li>
                        <li class="flex items-start gap-3 mt-2 bg-brand-red/10 p-2 border border-brand-red/20 rounded">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">warning</span>
                            <span class="text-brand-red text-xs font-bold leading-relaxed">Sebulan harus habis. Sisa pertemuan = Hangus</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="font-body-md text-brand-red text-sm font-bold">Rp</span>
                            <span class="font-display-xl text-4xl text-white">250.000</span>
                            <span class="font-body-md text-on-surface-variant text-sm">/bln</span>
                        </div>
                        <a href="https://wa.me/6289508366293" class="btn-primary w-full text-center text-sm py-3">Daftar Sekarang</a>
                    </div>
                </div>

                <!-- Card 4: Membership GYM -->
                <div class="glass-panel p-6 md:p-8 flex flex-col border border-white/10 hover:border-white/50 transition-colors relative">
                    <h3 class="font-headline-md text-2xl text-white uppercase mb-2">MEMBERSHIP GYM</h3>
                    <div class="font-body-md text-sm text-on-surface-variant mb-6">Paket bulanan latihan mandiri</div>
                    
                    <ul class="flex flex-col gap-3 mb-8 flex-grow">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">Akses tanpa batas 1 bulan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-brand-red text-[20px]">check</span>
                            <span class="text-white/80 text-sm">Bebas semua alat GYM</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-white/30 text-[20px]">close</span>
                            <span class="text-white/50 text-sm line-through">Pendampingan PT</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="font-body-md text-brand-red text-sm font-bold">Rp</span>
                            <span class="font-display-xl text-4xl text-white">70.000</span>
                            <span class="font-body-md text-on-surface-variant text-sm">/bln</span>
                        </div>
                        <a href="{{ route('member.contact') }}" class="btn-secondary w-full text-center text-sm py-3">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Info Tambahan -->
    <section class="py-16 bg-[#131313] border-b border-white/10 relative">
        <div class="max-w-screen-xl mx-auto px-6 md:px-16">
            <div class="glass-panel p-8 md:p-12 border-l-4 border-l-brand-red">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="bg-brand-red/10 p-4 rounded-full flex-shrink-0">
                        <span class="material-symbols-outlined text-brand-red text-4xl">info</span>
                    </div>
                    <div class="w-full">
                        <h2 class="font-headline-md text-2xl uppercase tracking-wider text-white mb-6">INFORMASI PENTING</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="font-headline-md text-lg text-brand-red uppercase mb-2">Jadwal Personal Trainer (PT)</h3>
                                <p class="font-body-md text-on-surface-variant">
                                    Untuk memakai jasa PT (Personal Trainer) usahakan janjian dulu di nomer WhatsApp berikut: <br>
                                    <a href="https://wa.me/6289508366293" target="_blank" class="inline-flex items-center gap-2 mt-3 text-white hover:text-brand-red transition-colors bg-white/5 px-4 py-2 border border-white/10 rounded">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.99.539 1.78.835 2.801.835 3.178 0 5.767-2.586 5.768-5.766 0-3.18-2.589-5.767-5.773-5.767zm7.551 5.764c0 4.167-3.39 7.557-7.551 7.557-1.326 0-2.58-.344-3.676-.948l-4.355 1.141 1.163-4.248c-.689-1.157-1.054-2.487-1.054-3.856 0-4.167 3.39-7.557 7.551-7.557 4.162 0 7.552 3.39 7.552 7.557zm-3.829 3.518c-.208.587-1.037 1.077-1.428 1.121-.392.043-.902.164-3.037-.673-2.564-1.006-4.204-3.606-4.331-3.774-.128-.168-1.033-1.373-1.033-2.618 0-1.246.654-1.859.886-2.112.232-.253.507-.317.676-.317.169 0 .338.002.486.01.157.008.368-.06.576.438.213.509.728 1.777.791 1.906.063.129.105.281.021.449-.084.168-.127.273-.253.42-.127.147-.267.329-.381.442-.128.127-.26.265-.112.521.148.256.657 1.082 1.411 1.753.971.865 1.79 1.134 2.046 1.261.256.127.406.106.556-.064.15-.17.643-.749.815-1.006.172-.257.344-.213.578-.127.234.086 1.488.701 1.745.829.257.128.428.192.492.299.064.107.064.622-.144 1.209z"/></svg> 
                                        0895-0836-6293
                                    </a>
                                </p>
                            </div>
                            
                            <hr class="border-white/10">
                            
                            <div>
                                <h3 class="font-headline-md text-lg text-brand-red uppercase mb-2">Sistem Membership</h3>
                                <p class="font-body-md text-on-surface-variant">
                                    Masa aktif berlaku per bulan (per 30 hari). <br>
                                    <span class="text-white mt-1 block">"Masa aktif sesuai dengan tanggal pendaftaran."</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
