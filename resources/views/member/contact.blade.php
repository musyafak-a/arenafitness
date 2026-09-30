@extends('layouts.member-front')
@section('title', 'Kontak Kami | WARGYM Jombang')
@php 
$activeNav = 'contact'; 

$phone = $contact['phone'] ?? '0821-3006-6694';
$waNumber = $contact['whatsapp_number'] ?? '6282130066694';
$waChannelUrl = $contact['whatsapp_channel_url'] ?? 'https://whatsapp.com/channel/0029Vb7ysaX30LKV0mIDbu2t';
$igHandle = $contact['instagram_handle'] ?? '@wargym_team';
$igUrl = $contact['instagram_url'] ?? 'https://www.instagram.com/wargym_team?utm_source=ig_web_button_share_sheet&stkn=ZDNlZDc0MzIxNw==';
$fbName = $contact['facebook_name'] ?? 'WARGYM Jombang';
$fbUrl = $contact['facebook_url'] ?? 'https://facebook.com/wargym.jombang';
$address = $contact['address'] ?? 'Sambong Dukuh, Kec. Jombang, Kabupaten Jombang, Jawa Timur, Indonesia';
$mapsEmbed = $contact['maps_embed_url'] ?? 'https://maps.google.com/maps?q=-7.5717763,112.2367804+(WARGYM+WARUNG+GYM)&t=&z=17&ie=UTF8&iwloc=&output=embed';
$mapsDirection = $contact['maps_direction_url'] ?? 'https://maps.app.goo.gl/SfncoYX75q97MA3p7';
$hours = $contact['hours'] ?? '24 JAM (Senin - Minggu)';
$lat = $contact['coordinates']['lat'] ?? '-7.5717763';
$lng = $contact['coordinates']['lng'] ?? '112.2367804';
@endphp

@section('content')

<main class="pt-20">
    <!-- Hero / Telemetry Header Section -->
    <section class="relative pt-16 pb-14 md:pt-24 md:pb-20 metal-grid border-b border-white/10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-transparent to-background pointer-events-none"></div>

        <div class="max-w-screen-2xl mx-auto px-6 md:px-16 relative z-10">


            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                <div class="lg:col-span-8">
                    <!-- <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand-red/10 border border-brand-red/30 text-brand-red font-label-caps text-xs uppercase tracking-widest mb-4">
                        <span class="material-symbols-outlined text-[16px]">terminal</span>
                        <span>[ SYS_COMM // OFFICIAL DIRECTORY ]</span>
                    </div> -->
                    <h1 class="font-headline-lg text-4xl sm:text-6xl md:text-7xl uppercase italic tracking-tight text-white mb-6 leading-[0.95]">
                        HUBUNGI <span class="text-brand-red">KAMI</span>
                    </h1>
                    <!-- <p class="font-body-md text-on-surface-variant text-base sm:text-lg max-w-2xl leading-relaxed">
                        Pusat layanan komunikasi dan informasi resmi WARGYM Jombang. Terhubung langsung melalui WhatsApp CS, ikuti Instagram & Facebook resmi kami, atau kunjungi langsung gym kami yang buka 24 jam non-stop di Sambong Dukuh.
                    </p> -->
                </div>

            </div>
        </div>
    </section>

    <!-- Channels Section: WhatsApp, Instagram, Facebook, Operasional -->
    <section class="py-16 md:py-24 bg-[#131313] border-b border-white/10 relative">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
            <!-- <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-12">
                <div>
                    <span class="font-label-caps text-brand-red text-xs uppercase tracking-[0.3em] block mb-1">[ 01 // DIRECT CHANNELS ]</span>
                    <h2 class="font-headline-md text-2xl md:text-3xl uppercase tracking-wider text-white">SALURAN RESMI WARGYM</h2>
                </div>
                <span class="hidden md:inline-block font-label-caps text-xs text-on-surface-variant/50 uppercase">VERIFIED TOUCHPOINTS</span>
            </div> -->

            <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-2 sm:gap-8">
                <!-- 1. WHATSAPP CARD -->
                <div class="glass-panel p-2 sm:p-8 relative flex flex-col justify-between border border-white/15 hover:border-brand-red/60 transition-all duration-300 group">
                    <div class="absolute -top-2 sm:-top-3 right-1 sm:right-6 bg-brand-red text-black font-label-caps text-[6px] sm:text-[10px] font-bold px-1.5 sm:px-3 py-0.5 uppercase tracking-wider shadow-lg">
                        RESPON TERCEPAT
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2 sm:mb-6 mt-3 sm:mt-0">
                            <div class="w-8 h-8 sm:w-14 sm:h-14 bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 group-hover:scale-105 transition-transform">
                                <!-- WhatsApp SVG -->
                                <svg class="w-4 h-4 sm:w-7 sm:h-7 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.99.539 1.78.835 2.801.835 3.178 0 5.767-2.586 5.768-5.766 0-3.18-2.589-5.767-5.773-5.767zm7.551 5.764c0 4.167-3.39 7.557-7.551 7.557-1.326 0-2.58-.344-3.676-.948l-4.355 1.141 1.163-4.248c-.689-1.157-1.054-2.487-1.054-3.856 0-4.167 3.39-7.557 7.551-7.557 4.162 0 7.552 3.39 7.552 7.557zm-3.829 3.518c-.208.587-1.037 1.077-1.428 1.121-.392.043-.902.164-3.037-.673-2.564-1.006-4.204-3.606-4.331-3.774-.128-.168-1.033-1.373-1.033-2.618 0-1.246.654-1.859.886-2.112.232-.253.507-.317.676-.317.169 0 .338.002.486.01.157.008.368-.06.576.438.213.509.728 1.777.791 1.906.063.129.105.281.021.449-.084.168-.127.273-.253.42-.127.147-.267.329-.381.442-.128.127-.26.265-.112.521.148.256.657 1.082 1.411 1.753.971.865 1.79 1.134 2.046 1.261.256.127.406.106.556-.064.15-.17.643-.749.815-1.006.172-.257.344-.213.578-.127.234.086 1.488.701 1.745.829.257.128.428.192.492.299.064.107.064.622-.144 1.209z"/>
                                </svg>
                            </div>
                        </div>

                        <span class="badge-tag text-on-surface-variant/70 uppercase text-[6px] sm:text-xs">Layanan Pelanggan & CS</span>
                        <h3 class="font-headline-md text-xs sm:text-2xl uppercase text-white mt-1 mb-1 sm:mb-2">WHATSAPP OFFICIAL</h3>
                        <p class="font-display-xl text-[8px] sm:text-xl text-brand-red mb-2 sm:mb-3 tracking-wider break-words">{{ $phone }}</p>
                    </div>

                    <div class="space-y-3 pt-2 sm:pt-4 border-t border-white/10 mt-auto">
                        <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Admin%20WARGYM%2C%20saya%20ingin%20tanya%20informasi%20membership%20dan%20fasilitas%20gym." 
                           target="_blank" 
                           class="btn-primary w-full text-center text-[7px] sm:text-sm py-1.5 sm:py-3 px-1 sm:px-4">
                            <span class="material-symbols-outlined text-[10px] sm:text-[18px] mr-1 sm:mr-2">chat</span>
                            Chat<span class="hidden sm:inline"> WhatsApp Admin</span>
                        </a>
                    </div>
                </div>

                <!-- 2. INSTAGRAM CARD -->
                <div class="glass-panel p-2 sm:p-8 relative flex flex-col justify-between border border-white/15 hover:border-brand-red/60 transition-all duration-300 group">
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2 sm:mb-6 mt-3 sm:mt-0">
                            <div class="w-8 h-8 sm:w-14 sm:h-14 bg-pink-500/10 border border-pink-500/30 flex items-center justify-center text-pink-400 group-hover:scale-105 transition-transform">
                                <!-- Instagram SVG -->
                                <svg class="w-4 h-4 sm:w-7 sm:h-7 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                        </div>

                        <span class="badge-tag text-on-surface-variant/70 uppercase text-[6px] sm:text-xs">Galeri & Update Latihan</span>
                        <h3 class="font-headline-md text-xs sm:text-2xl uppercase text-white mt-1 mb-1 sm:mb-2">INSTAGRAM</h3>
                        <p class="font-display-xl text-[8px] sm:text-xl text-brand-red mb-2 sm:mb-3 tracking-wider break-words">{{ $igHandle }}</p>
                    </div>

                    <div class="pt-2 sm:pt-4 border-t border-white/10 mt-auto">
                        <a href="{{ $igUrl }}" 
                           target="_blank" 
                           class="btn-primary w-full text-center text-[7px] sm:text-sm py-1.5 sm:py-3 px-1 sm:px-4">
                            <span class="material-symbols-outlined text-[10px] sm:text-[18px] mr-1 sm:mr-2">photo_camera</span>
                            Follow<span class="hidden sm:inline"> Instagram</span>
                        </a>
                    </div>
                </div>

                <!-- 3. FACEBOOK CARD -->
                <div class="glass-panel p-2 sm:p-8 relative flex flex-col justify-between border border-white/15 hover:border-brand-red/60 transition-all duration-300 group">
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2 sm:mb-6 mt-3 sm:mt-0">
                            <div class="w-8 h-8 sm:w-14 sm:h-14 bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400 group-hover:scale-105 transition-transform">
                                <!-- Facebook SVG -->
                                <svg class="w-4 h-4 sm:w-7 sm:h-7 fill-current" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                </svg>
                            </div>
                        </div>

                        <span class="badge-tag text-on-surface-variant/70 uppercase text-[6px] sm:text-xs">Halaman & Forum Diskusi</span>
                        <h3 class="font-headline-md text-xs sm:text-2xl uppercase text-white mt-1 mb-1 sm:mb-2">FACEBOOK PAGE</h3>
                        <p class="font-display-xl text-[8px] sm:text-xl text-brand-red mb-2 sm:mb-3 tracking-wider break-words">{{ $fbName }}</p>
                    </div>

                    <div class="pt-2 sm:pt-4 border-t border-white/10 mt-auto">
                        <a href="{{ $fbUrl }}" 
                           target="_blank" 
                           class="btn-primary w-full text-center text-[7px] sm:text-sm py-1.5 sm:py-3 px-1 sm:px-4">
                            <span class="material-symbols-outlined text-[10px] sm:text-[18px] mr-1 sm:mr-2">group</span>
                            Kunjungi<span class="hidden sm:inline"> Facebook</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

@endsection
