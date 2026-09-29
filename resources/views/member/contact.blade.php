<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Kontak Kami | WARGYM Jombang</title>
    <script src="{{ asset('js/tailwind.min.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=JetBrains+Mono:wght@400;500;700&family=Hanken+Grotesk:wght@400;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#ffb4a8',
                        background: '#131313',
                        'surface-container': '#1f1f1f',
                        'surface-variant': '#353535',
                        'on-background': '#e2e2e2',
                        'on-surface': '#e2e2e2',
                        'on-surface-variant': '#ebbbb4',
                        'brand-red': '#ff5540',
                    },
                    fontFamily: {
                        'headline-md': ['Oswald'],
                        'body-md': ['Hanken Grotesk'],
                        'headline-lg': ['Oswald'],
                        'display-xl': ['Oswald'],
                        'label-caps': ['JetBrains Mono'],
                        'body-lg': ['Hanken Grotesk'],
                    }
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; display: inline-block; line-height: 1; }
        .btn-primary { background-color: #ff5540; color: #000; text-transform: uppercase; font-family: 'Oswald', sans-serif; font-weight: 600; letter-spacing: 0.1em; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; padding: 1rem 2.5rem; }
        .btn-primary:hover { background-color: #fff; color: #ff5540; box-shadow: 0 0 20px rgba(255, 85, 64, 0.4); transform: translateY(-2px); }
        .btn-secondary { background-color: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: #fff; text-transform: uppercase; font-family: 'Oswald', sans-serif; font-weight: 600; letter-spacing: 0.1em; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; padding: 0.875rem 2rem; }
        .btn-secondary:hover { background-color: rgba(255,85,64,0.15); border-color: #ff5540; color: #ff5540; transform: translateY(-2px); }
        .glass-panel { background: linear-gradient(135deg, rgba(31,31,31,.72), rgba(14,14,14,.48)); border: 1px solid rgba(255,255,255,.14); box-shadow: 0 24px 80px rgba(0,0,0,.48); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); }
        .glass-tile { background: rgba(255,255,255,.055); border: 1px solid rgba(255,255,255,.12); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); transition: border-color .25s ease, background .25s ease, transform .25s ease; }
        .glass-tile:hover { background: rgba(255,255,255,.085); border-color: rgba(255,85,64,.52); transform: translateY(-2px); }
        .metal-grid { background-image: linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px); background-size: 48px 48px; }
        .nav-link { position: relative; padding-bottom: 6px; }
        .nav-link::after { content: ''; position: absolute; left: 0; bottom: 0; width: 0; height: 2px; background: #ff5540; transition: width .25s ease; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu { display: none; position: absolute; top: 100%; left: 0; min-width: 200px; background-color: #1f1f1f; border: 1px solid rgba(255,255,255,.1); box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 100; padding: 0.5rem 0; }
        .dropdown-menu a { display: block; padding: 0.75rem 1.5rem; color: #e2e2e2; font-family: 'JetBrains Mono', monospace; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.2s ease; }
        .dropdown-menu a:hover { background-color: rgba(255,85,64,.1); color: #ff5540; padding-left: 1.75rem; border-left: 2px solid #ff5540; }
        .footer-link { color: #e2e2e2; transition: color 0.2s ease; margin-bottom: 0.5rem; display: block; }
        .footer-link:hover { color: #ff5540; }
        .badge-tag { font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-brand-red selection:text-white">

@php
$navItems = [
    [
        'title' => 'beranda',
        'url' => route('member.dashboard'),
    ],
    [
        'title' => 'OUR SERVICE',
        'url' => null,
        'children' => [
            ['title' => 'Personal Training', 'url' => route('member.company-profile') . '#service'],
        ]
    ],
    [
        'title' => 'TUTORIAL',
        'url' => null,
        'children' => [
            ['title' => 'Gym Equipment Guide', 'url' => '#'],
            ['title' => 'Workout Plans', 'url' => '#'],
        ]
    ],
    [
        'title' => 'fasilitas',
        'url' => route('member.company-profile') . '#fasilitas',
    ],
    [
        'title' => 'TEAM KITA',
        'url' => null,
        'children' => [
            ['title' => 'Master Trainers', 'url' => '#'],
            ['title' => 'Management', 'url' => '#'],
        ]
    ],
    [
        'title' => 'KONTAK',
        'url' => route('member.contact'),
        'active' => true,
    ]
];

$phone = $contact['phone'] ?? '0821-3006-6694';
$waNumber = $contact['whatsapp_number'] ?? '6282130066694';
$waChannelUrl = $contact['whatsapp_channel_url'] ?? 'https://whatsapp.com/channel/0029Vb7ysaX30LKV0mIDbu2t';
$igHandle = $contact['instagram_handle'] ?? '@wargym_jombang';
$igUrl = $contact['instagram_url'] ?? 'https://instagram.com/wargym_jombang';
$fbName = $contact['facebook_name'] ?? 'WARGYM Jombang';
$fbUrl = $contact['facebook_url'] ?? 'https://facebook.com/wargym.jombang';
$address = $contact['address'] ?? 'Sambong Dukuh, Kec. Jombang, Kabupaten Jombang, Jawa Timur, Indonesia';
$mapsEmbed = $contact['maps_embed_url'] ?? 'https://maps.google.com/maps?q=-7.5717763,112.2367804+(WARGYM+WARUNG+GYM)&t=&z=17&ie=UTF8&iwloc=&output=embed';
$mapsDirection = $contact['maps_direction_url'] ?? 'https://maps.app.goo.gl/SfncoYX75q97MA3p7';
$hours = $contact['hours'] ?? '24 JAM (Senin - Minggu)';
$lat = $contact['coordinates']['lat'] ?? '-7.5717763';
$lng = $contact['coordinates']['lng'] ?? '112.2367804';
@endphp

<!-- Global Header -->
<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 bg-black/95 shadow-2xl border-b border-white/10">
    <div class="flex items-center justify-between h-20 px-6 md:px-16 w-full max-w-screen-2xl mx-auto">
        <a class="flex items-center gap-3" href="{{ route('member.company-profile') }}">
            <span class="font-display-xl text-white uppercase italic text-2xl tracking-tighter leading-none">WAR <span class="text-brand-red">GYM</span></span>
        </a>
        
        <nav class="hidden lg:flex items-center justify-center gap-8 h-full">
            @foreach($navItems as $item)
                @if(isset($item['children']))
                    <div class="dropdown relative h-full flex items-center">
                        <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 cursor-pointer uppercase {{ !empty($item['active']) ? 'active text-white' : '' }}">{{ $item['title'] }} <span class="material-symbols-outlined text-[14px] align-middle">expand_more</span></a>
                        @if(count($item['children']) > 0)
                            <div class="dropdown-menu">
                                @foreach($item['children'] as $child)
                                    <a href="{{ $child['url'] }}">{{ $child['title'] }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="h-full flex items-center">
                        <a class="nav-link font-label-caps text-label-caps {{ !empty($item['active']) ? 'active text-brand-red font-bold' : 'text-on-surface-variant' }} hover:text-brand-red transition-colors py-2 uppercase" href="{{ $item['url'] ?? '#' }}">{{ $item['title'] }}</a>
                    </div>
                @endif
            @endforeach
        </nav>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('member.login') }}" class="hidden sm:inline-flex items-center gap-2 border border-white/20 hover:border-brand-red text-white hover:text-brand-red px-4 py-2 font-label-caps text-xs uppercase tracking-wider transition-all">
                <span class="material-symbols-outlined text-sm">login</span>
                <span>Portal Member</span>
            </a>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = true" class="lg:hidden text-white hover:text-brand-red transition-colors focus:outline-none">
                <span class="material-symbols-outlined text-3xl">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div x-show="mobileMenuOpen" 
         class="fixed inset-0 z-[100] lg:hidden" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/80 backdrop-blur-sm"
             @click="mobileMenuOpen = false"></div>
             
        <!-- Sidebar Panel -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 w-full max-w-sm bg-[#131313] border-l border-brand-red/20 shadow-2xl overflow-y-auto">
             
            <div class="flex items-center justify-between p-6 border-b border-white/10">
                <span class="font-display-xl text-white uppercase italic text-2xl tracking-tighter leading-none">WAR <span class="text-brand-red">GYM</span></span>
                <button @click="mobileMenuOpen = false" class="text-white hover:text-brand-red transition-colors focus:outline-none">
                    <span class="material-symbols-outlined text-3xl">close</span>
                </button>
            </div>
            
            <div class="p-6 flex flex-col gap-6">
                <!-- Mobile Links -->
                @foreach($navItems as $item)
                    <div class="flex flex-col gap-4 {{ !$loop->last ? 'border-b border-white/10 pb-6' : '' }}">
                        @if(isset($item['children']))
                            <span class="font-label-caps text-brand-red text-xs tracking-widest uppercase">{{ $item['title'] }}</span>
                            @foreach($item['children'] as $child)
                                <a href="{{ $child['url'] }}" class="font-headline-md text-white hover:text-brand-red text-xl uppercase transition-colors">{{ $child['title'] }}</a>
                            @endforeach
                        @else
                            <a href="{{ $item['url'] ?? '#' }}" class="font-headline-md {{ !empty($item['active']) ? 'text-brand-red' : 'text-white' }} hover:text-brand-red text-xl uppercase transition-colors">{{ $item['title'] }}</a>
                        @endif
                    </div>
                @endforeach

                <div class="pt-4 border-t border-white/10">
                    <a href="{{ route('member.login') }}" class="btn-primary w-full text-center">
                        Masuk Portal Member
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="pt-20">
    <!-- Hero / Telemetry Header Section -->
    <section class="relative pt-16 pb-14 md:pt-24 md:pb-20 metal-grid border-b border-white/10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-transparent to-background pointer-events-none"></div>

        <div class="max-w-screen-2xl mx-auto px-6 md:px-16 relative z-10">
            <!-- Telemetry Status Bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 pb-6 mb-8">
                <div class="flex items-center gap-3">
                    <span class="inline-flex w-3 h-3 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_12px_#10b981]"></span>
                    <span class="badge-tag text-emerald-400 font-bold">STATUS: OPERASIONAL 24 JAM NON-STOP</span>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-xs font-label-caps text-on-surface-variant/70">
                    <span>BASE: JOMBANG (ID)</span>
                    <span class="text-white/20">|</span>
                    <span>COORDS: {{ $lat }}, {{ $lng }}</span>
                    <span class="text-white/20">|</span>
                    <span class="text-brand-red">SUPPORT LINK ACTIVE</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                <div class="lg:col-span-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-brand-red/10 border border-brand-red/30 text-brand-red font-label-caps text-xs uppercase tracking-widest mb-4">
                        <span class="material-symbols-outlined text-[16px]">terminal</span>
                        <span>[ SYS_COMM // OFFICIAL DIRECTORY ]</span>
                    </div>
                    <h1 class="font-headline-lg text-4xl sm:text-6xl md:text-7xl uppercase italic tracking-tight text-white mb-6 leading-[0.95]">
                        HUBUNGI <span class="text-brand-red">KAMI</span>
                    </h1>
                    <!-- <p class="font-body-md text-on-surface-variant text-base sm:text-lg max-w-2xl leading-relaxed">
                        Pusat layanan komunikasi dan informasi resmi WARGYM Jombang. Terhubung langsung melalui WhatsApp CS, ikuti Instagram & Facebook resmi kami, atau kunjungi langsung gym kami yang buka 24 jam non-stop di Sambong Dukuh.
                    </p> -->
                </div>
                <div class="lg:col-span-4 flex lg:justify-end">
                    <div class="glass-panel p-5 w-full max-w-md border-l-4 border-l-brand-red">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-label-caps text-xs text-white uppercase tracking-wider">Fast Response Hotline</span>
                            <span class="badge-tag text-brand-red">CS-01</span>
                        </div>
                        <p class="font-display-xl text-2xl md:text-3xl text-white tracking-wide mb-2">{{ $phone }}</p>
                        <p class="text-xs text-on-surface-variant/70 font-body-md">Konsultasi pendaftaran member, personal trainer & sewa loker.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Channels Section: WhatsApp, Instagram, Facebook, Operasional -->
    <section class="py-16 md:py-24 bg-[#131313] border-b border-white/10 relative">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
            <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-12">
                <div>
                    <span class="font-label-caps text-brand-red text-xs uppercase tracking-[0.3em] block mb-1">[ 01 // DIRECT CHANNELS ]</span>
                    <h2 class="font-headline-md text-2xl md:text-3xl uppercase tracking-wider text-white">SALURAN RESMI WARGYM</h2>
                </div>
                <span class="hidden md:inline-block font-label-caps text-xs text-on-surface-variant/50 uppercase">VERIFIED TOUCHPOINTS</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- 1. WHATSAPP CARD -->
                <div class="glass-panel p-8 relative flex flex-col justify-between border border-white/15 hover:border-brand-red/60 transition-all duration-300 group">
                    <div class="absolute -top-3 right-6 bg-brand-red text-black font-label-caps text-[10px] font-bold px-3 py-0.5 uppercase tracking-wider shadow-lg">
                        RESPON TERCEPAT
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 group-hover:scale-105 transition-transform">
                                <!-- WhatsApp SVG -->
                                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.99.539 1.78.835 2.801.835 3.178 0 5.767-2.586 5.768-5.766 0-3.18-2.589-5.767-5.773-5.767zm7.551 5.764c0 4.167-3.39 7.557-7.551 7.557-1.326 0-2.58-.344-3.676-.948l-4.355 1.141 1.163-4.248c-.689-1.157-1.054-2.487-1.054-3.856 0-4.167 3.39-7.557 7.551-7.557 4.162 0 7.552 3.39 7.552 7.557zm-3.829 3.518c-.208.587-1.037 1.077-1.428 1.121-.392.043-.902.164-3.037-.673-2.564-1.006-4.204-3.606-4.331-3.774-.128-.168-1.033-1.373-1.033-2.618 0-1.246.654-1.859.886-2.112.232-.253.507-.317.676-.317.169 0 .338.002.486.01.157.008.368-.06.576.438.213.509.728 1.777.791 1.906.063.129.105.281.021.449-.084.168-.127.273-.253.42-.127.147-.267.329-.381.442-.128.127-.26.265-.112.521.148.256.657 1.082 1.411 1.753.971.865 1.79 1.134 2.046 1.261.256.127.406.106.556-.064.15-.17.643-.749.815-1.006.172-.257.344-.213.578-.127.234.086 1.488.701 1.745.829.257.128.428.192.492.299.064.107.064.622-.144 1.209z"/>
                                </svg>
                            </div>
                            <span class="badge-tag text-emerald-400 border border-emerald-500/30 px-2 py-0.5 bg-emerald-500/10">DIRECT CHAT</span>
                        </div>

                        <span class="badge-tag text-on-surface-variant/70 uppercase">Layanan Pelanggan & CS</span>
                        <h3 class="font-headline-md text-2xl uppercase text-white mt-1 mb-2">WHATSAPP OFFICIAL</h3>
                        <p class="font-display-xl text-xl text-brand-red mb-3 tracking-wider">{{ $phone }}</p>
                        <!-- <p class="text-on-surface-variant text-sm font-body-md leading-relaxed mb-6">
                            Konsultasi pendaftaran paket membership, promo bulanan, daily pass, serta booking sesi personal trainer langsung dengan admin kami.
                        </p> -->
                    </div>

                    <div class="space-y-3 pt-4 border-t border-white/10">
                        <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Admin%20WARGYM%2C%20saya%20ingin%20tanya%20informasi%20membership%20dan%20fasilitas%20gym." 
                           target="_blank" 
                           class="btn-primary w-full text-center text-sm py-3">
                            <span class="material-symbols-outlined text-[18px] mr-2">chat</span>
                            Chat WhatsApp Admin
                        </a>
                        <a href="{{ $waChannelUrl }}" 
                           target="_blank" 
                           class="btn-secondary w-full text-center text-xs py-2.5 border-dashed">
                            <span class="material-symbols-outlined text-[16px] mr-2 text-emerald-400">campaign</span>
                            Gabung Saluran WhatsApp Resmi
                        </a>
                    </div>
                </div>

                <!-- 2. INSTAGRAM CARD -->
                <div class="glass-panel p-8 relative flex flex-col justify-between border border-white/15 hover:border-brand-red/60 transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-pink-500/10 border border-pink-500/30 flex items-center justify-center text-pink-400 group-hover:scale-105 transition-transform">
                                <!-- Instagram SVG -->
                                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <span class="badge-tag text-pink-400 border border-pink-500/30 px-2 py-0.5 bg-pink-500/10">SOCIAL FEED</span>
                        </div>

                        <span class="badge-tag text-on-surface-variant/70 uppercase">Galeri & Update Latihan</span>
                        <h3 class="font-headline-md text-2xl uppercase text-white mt-1 mb-2">INSTAGRAM</h3>
                        <p class="font-display-xl text-xl text-brand-red mb-3 tracking-wider">{{ $igHandle }}</p>
                        <!-- <p class="text-on-surface-variant text-sm font-body-md leading-relaxed mb-6">
                            Ikuti highlight latihan member, tutorial penggunaan alat gym, motivasi binaraga harian, dan dokumentasi event internal WARGYM.
                        </p> -->
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <a href="{{ $igUrl }}" 
                           target="_blank" 
                           class="btn-primary w-full text-center text-sm py-3">
                            <span class="material-symbols-outlined text-[18px] mr-2">photo_camera</span>
                            Follow Instagram
                        </a>
                    </div>
                </div>

                <!-- 3. FACEBOOK CARD -->
                <div class="glass-panel p-8 relative flex flex-col justify-between border border-white/15 hover:border-brand-red/60 transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400 group-hover:scale-105 transition-transform">
                                <!-- Facebook SVG -->
                                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                </svg>
                            </div>
                            <span class="badge-tag text-blue-400 border border-blue-500/30 px-2 py-0.5 bg-blue-500/10">KOMUNITAS</span>
                        </div>

                        <span class="badge-tag text-on-surface-variant/70 uppercase">Halaman & Forum Diskusi</span>
                        <h3 class="font-headline-md text-2xl uppercase text-white mt-1 mb-2">FACEBOOK PAGE</h3>
                        <p class="font-display-xl text-xl text-brand-red mb-3 tracking-wider">{{ $fbName }}</p>
                        <!-- <p class="text-on-surface-variant text-sm font-body-md leading-relaxed mb-6">
                            Bergabung dengan komunitas fitness dan angkat beban Jombang. Bagikan pengalaman, diskusi nutrisi suplemen, serta info jadwal kejuaraan lokal.
                        </p> -->
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <a href="{{ $fbUrl }}" 
                           target="_blank" 
                           class="btn-primary w-full text-center text-sm py-3">
                            <span class="material-symbols-outlined text-[18px] mr-2">group</span>
                            Kunjungi Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- Footer Section (Detailed brutalist style matching company-profile) -->
<footer class="bg-[#131313] border-t border-white/5 pt-20 pb-10">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">
            <!-- Brand Column -->
            <div class="lg:col-span-1">
                <a class="flex items-center gap-3 mb-6" href="{{ route('member.company-profile') }}">
                    <span class="font-display-xl text-white uppercase italic text-2xl tracking-tighter leading-none">WAR <span class="text-brand-red">GYM</span></span>
                </a>
                <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-6">
                    Temukan lokasi WARGYM, pilihan kelas kebugaran, fasilitas latihan, dan personal trainer untuk mendukung perjalanan fitness Anda.
                </p>
                <div class="flex items-center gap-3">
                    <a href="{{ $igUrl }}" target="_blank" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors" title="Instagram"><span class="material-symbols-outlined text-[20px]">photo_camera</span></a>
                    <a href="{{ $waChannelUrl }}" target="_blank" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors" title="Saluran WhatsApp"><span class="material-symbols-outlined text-[20px]">smart_display</span></a>
                    <a href="{{ $fbUrl }}" target="_blank" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors" title="Facebook"><span class="material-symbols-outlined text-[20px]">facebook</span></a>
                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors" title="WhatsApp Admin"><span class="material-symbols-outlined text-[20px]">chat</span></a>
                </div>
            </div>

            <!-- Empty Column for Spacing -->
            <div class="hidden lg:block lg:col-span-1"></div>

            <!-- Perusahaan Column -->
            <div class="lg:col-span-1">
                <h4 class="font-headline-md text-white tracking-widest text-lg mb-6">Perusahaan</h4>
                <a href="{{ route('member.company-profile') }}" class="footer-link">Tentang Kami</a>
                <a href="#" class="footer-link">Blog Kesehatan</a>
                <a href="{{ route('member.contact') }}" class="footer-link text-brand-red font-bold">Hubungi Kami</a>
                <a href="#" class="footer-link">Kemitraan</a>
            </div>

            <!-- Layanan Column -->
            <div class="lg:col-span-1">
                <h4 class="font-headline-md text-white tracking-widest text-lg mb-6">Layanan</h4>
                <a href="{{ route('member.company-profile') }}#fasilitas" class="footer-link">Fasilitas</a>
                <a href="#" class="footer-link">Kelas Group</a>
                <a href="#" class="footer-link">Personal Trainer</a>
                <a href="{{ route('member.login') }}" class="footer-link">Portal Member</a>
            </div>

            <!-- Kontak Cepat Column -->
            <div class="lg:col-span-1">
                <h4 class="font-headline-md text-white tracking-widest text-lg mb-6">Kontak Cepat</h4>
                <p class="text-sm font-label-caps text-on-surface-variant mb-2">WhatsApp: <span class="text-white">{{ $phone }}</span></p>
                <p class="text-sm font-label-caps text-on-surface-variant mb-2">Instagram: <span class="text-white">{{ $igHandle }}</span></p>
                <p class="text-sm font-label-caps text-on-surface-variant mb-2">Buka: <span class="text-brand-red font-bold">24 Jam</span></p>
                <a href="{{ $mapsDirection }}" target="_blank" class="text-sm font-bold text-white mt-2 inline-block border-b border-brand-red pb-1 hover:text-brand-red transition-colors">Petunjuk Google Maps &rarr;</a>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center border-t border-white/5 pt-8 text-on-surface-variant/60 font-body-md text-sm">
            <a href="#" class="hover:text-white transition-colors mb-4 md:mb-0">Kebijakan Privasi & Ketentuan Layanan</a>
            <p>&copy; {{ date('Y') }} WARGYM (WARUNG GYM). All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
