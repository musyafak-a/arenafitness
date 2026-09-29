<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Company Profile | Fitness</title>
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
        .glass-panel { background: linear-gradient(135deg, rgba(31,31,31,.72), rgba(14,14,14,.48)); border: 1px solid rgba(255,255,255,.14); box-shadow: 0 24px 80px rgba(0,0,0,.48); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); }
        .glass-tile { background: rgba(255,255,255,.055); border: 1px solid rgba(255,255,255,.12); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); transition: border-color .25s ease, background .25s ease, transform .25s ease; }
        .glass-tile:hover { background: rgba(255,255,255,.085); border-color: rgba(255,85,64,.52); transform: translateY(-2px); }
        .metal-grid { background-image: linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px); background-size: 48px 48px; }
        .hero-vignette { background: linear-gradient(90deg, rgba(0,0,0,.96) 0%, rgba(0,0,0,.5) 50%, rgba(0,0,0,.8) 100%); }
        .nav-link { position: relative; padding-bottom: 6px; }
        .nav-link::after { content: ''; position: absolute; left: 0; bottom: 0; width: 0; height: 2px; background: #ff5540; transition: width .25s ease; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu { display: none; position: absolute; top: 100%; left: 0; min-width: 200px; background-color: #1f1f1f; border: 1px solid rgba(255,255,255,.1); box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 100; padding: 0.5rem 0; }
        .dropdown-menu a { display: block; padding: 0.75rem 1.5rem; color: #e2e2e2; font-family: 'JetBrains Mono', monospace; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.2s ease; }
        .dropdown-menu a:hover { background-color: rgba(255,85,64,.1); color: #ff5540; padding-left: 1.75rem; border-left: 2px solid #ff5540; }
        .footer-link { color: #e2e2e2; transition: color 0.2s ease; margin-bottom: 0.5rem; display: block; }
        .footer-link:hover { color: #ff5540; }
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
            ['title' => 'Personal Training', 'url' => '#'],
            // ['title' => 'Group Classes', 'url' => '#'],
            // ['title' => 'Nutrition Plan', 'url' => '#'],
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
        'url' => '#',
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
    ]
];
@endphp

<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 bg-black/95 shadow-2xl border-b border-white/10">
    <div class="flex items-center justify-between h-20 px-6 md:px-16 w-full max-w-screen-2xl mx-auto">
        <a class="flex items-center gap-3" href="{{ route('member.dashboard') }}">
            <span class="font-display-xl text-white uppercase italic text-2xl tracking-tighter leading-none hidden sm:inline">WAR <span class="text-brand-red">GYM</span></span>
        </a>
        
        <nav class="hidden lg:flex items-center justify-center gap-8 h-full">
            @foreach($navItems as $item)
                @if(isset($item['children']))
                    <div class="dropdown relative h-full flex items-center">
                        <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 cursor-pointer uppercase {{ $item['title'] === 'OUR SERVICE' ? 'bg-brand-red/10 text-brand-red px-3' : '' }}">{{ $item['title'] }} <span class="material-symbols-outlined text-[14px] align-middle">expand_more</span></a>
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
                        <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 uppercase" href="{{ $item['url'] ?? '#' }}">{{ $item['title'] }}</a>
                    </div>
                @endif
            @endforeach
        </nav>
        
        <div class="flex items-center gap-4">
            
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
                            <a href="{{ $item['url'] ?? '#' }}" class="font-headline-md text-white hover:text-brand-red text-xl uppercase transition-colors">{{ $item['title'] }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</header>

<main class="pt-20">
    <!-- Carousel / Hero Section -->
    <section class="relative min-h-[600px] flex items-center overflow-hidden metal-grid" x-data="{ currentSlide: 0 }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % 3 }, 5000)">
        <!-- Slide 1 cihuy -->
        <div class="absolute inset-0 z-0 transition-opacity duration-1000" :class="currentSlide === 0 ? 'opacity-100' : 'opacity-0'">
            <img class="w-full h-full object-cover brightness-125" src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=2200&q=85" />
            <div class="absolute inset-0 hero-vignette"></div>
        </div>
        <!-- Slide 2 -->
        <div class="absolute inset-0 z-0 transition-opacity duration-1000" :class="currentSlide === 1 ? 'opacity-100' : 'opacity-0'">
            <img class="w-full h-full object-cover brightness-125" src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&w=1920&q=80" />
            <div class="absolute inset-0 hero-vignette"></div>
        </div>
        <!-- Slide 3 -->
        <div class="absolute inset-0 z-0 transition-opacity duration-1000" :class="currentSlide === 2 ? 'opacity-100' : 'opacity-0'">
            <img class="w-full h-full object-cover brightness-125" src="https://images.unsplash.com/photo-1576678927484-cc907957088c?auto=format&fit=crop&w=1920&q=80" />
            <div class="absolute inset-0 hero-vignette"></div>
        </div>

        <div class="relative z-10 w-full max-w-screen-2xl mx-auto px-6 md:px-16 py-20">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 border border-brand-red/40 bg-brand-red/10 px-4 py-2 rounded-full mb-6">
                    <span class="w-2 h-2 rounded-full bg-brand-red"></span>
                    <span class="font-label-caps text-xs text-brand-red uppercase tracking-widest">Kualitas Terjamin</span>
                </div>
                
                <h1 class="font-display-xl text-[60px] md:text-[80px] lg:text-[100px] uppercase italic font-bold tracking-tighter leading-[1] mb-6 text-white drop-shadow-lg">
                    DARI RANCANGAN<br/>
                    <span class="text-brand-red">HINGGA</span><br/>
                    PRODUK JADI
                </h1>
                
                <p class="font-body-lg text-lg text-on-surface-variant max-w-2xl mb-10 leading-relaxed">
                    Kami menangani proses lengkap: perancangan, pengerjaan komponen, perakitan mesin, uji coba, hingga finishing berkualitas tinggi.
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="btn-primary gap-2">
                        <span class="material-symbols-outlined text-[20px]">settings</span>
                        Lihat Proses
                    </a>
                    <a href="#" class="inline-flex items-center gap-2 glass-tile px-6 py-4 font-label-caps text-xs uppercase tracking-widest text-white hover:text-brand-red border-white/20">
                        <span class="material-symbols-outlined text-[20px]">info</span>
                        Tentang Kami
                    </a>
                </div>
            </div>
            
            <div class="absolute left-6 top-1/2 -translate-y-1/2 hidden md:flex cursor-pointer" @click="currentSlide = (currentSlide - 1 + 3) % 3">
                <div class="w-12 h-12 rounded-full border border-white/20 bg-black/40 flex items-center justify-center hover:bg-brand-red hover:text-black transition-all text-white">
                    <span class="material-symbols-outlined">chevron_left</span>
                </div>
            </div>
            <div class="absolute right-6 top-1/2 -translate-y-1/2 hidden md:flex cursor-pointer" @click="currentSlide = (currentSlide + 1) % 3">
                <div class="w-12 h-12 rounded-full border border-white/20 bg-black/40 flex items-center justify-center hover:bg-brand-red hover:text-black transition-all text-white">
                    <span class="material-symbols-outlined">chevron_right</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Umum Section -->
    <section class="py-16 md:py-24 bg-[#0a0a0a] border-b border-white/5 metal-grid relative z-10">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
            <div class="glass-panel border border-white/10 p-8 md:p-12 relative overflow-hidden">
                <!-- Decorative background elements inside panel -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-brand-red/10 rounded-full blur-[80px] pointer-events-none"></div>
                <div class="absolute -bottom-8 -left-8 text-[120px] font-display-xl text-white/[0.03] leading-none pointer-events-none select-none">HOURS</div>
                
                <div class="relative z-10 flex flex-col lg:flex-row gap-12 lg:items-center justify-between">
                    
                    <!-- Title Section -->
                    <div class="lg:w-1/3">
                        <span class="font-label-caps text-brand-red tracking-[0.3em] uppercase block mb-3 flex items-center gap-2">
                            <span class="w-8 h-[1px] bg-brand-red"></span>
                            Waktu Buka
                        </span>
                        <h2 class="font-headline-lg text-4xl md:text-5xl uppercase italic text-white mb-4">UMUM</h2>
                        <p class="font-body-md text-on-surface-variant max-w-sm">
                            Kunjungi WARGYM pada jam operasional kami. Kami siap melayani rutinitas kebugaran Anda.
                        </p>
                    </div>
                    
                    <!-- Schedule Cards -->
                    <div class="lg:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Senin-Sabtu -->
                        <div class="border border-white/10 bg-white/5 p-6 md:p-8 hover:border-white/20 transition-colors flex flex-col justify-center">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-symbols-outlined text-white/50 text-2xl">calendar_month</span>
                                <h3 class="font-headline-md text-xl text-white tracking-wide uppercase">Senin - Sabtu</h3>
                            </div>
                            <div class="flex items-end gap-3 mt-auto">
                                <span class="font-display-xl text-4xl md:text-5xl text-brand-red leading-none">07:00</span>
                                <span class="text-white/30 text-2xl mb-1">-</span>
                                <span class="font-display-xl text-4xl md:text-5xl text-white leading-none">21:00</span>
                            </div>
                        </div>
                        
                        <!-- Minggu -->
                        <div class="border border-brand-red/30 bg-brand-red/[0.05] p-6 md:p-8 hover:border-brand-red/50 transition-colors flex flex-col justify-center relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-0 h-0 border-t-[40px] border-l-[40px] border-t-brand-red/40 border-l-transparent"></div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-symbols-outlined text-brand-red/80 text-2xl">event</span>
                                <h3 class="font-headline-md text-xl text-white tracking-wide uppercase">Minggu</h3>
                            </div>
                            <div class="flex items-end gap-3 mt-auto relative z-10">
                                <span class="font-display-xl text-4xl md:text-5xl text-brand-red leading-none group-hover:scale-105 transition-transform origin-bottom-left">10:00</span>
                                <span class="text-white/30 text-2xl mb-1">-</span>
                                <span class="font-display-xl text-4xl md:text-5xl text-white leading-none group-hover:scale-105 transition-transform origin-bottom-left">21:00</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Gear Section -->
    <section class="py-20 md:py-28 bg-[#131313] metal-grid">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <span class="font-label-caps text-brand-red tracking-[0.3em] uppercase block mb-4">Premium Gear</span>
                    <h2 class="font-headline-lg text-4xl md:text-5xl uppercase italic mb-4">FASILITAS ELITE</h2>
                    <p class="font-body-lg text-on-surface-variant max-w-xl">
                        Peralatan kelas dunia dengan spesifikasi kompetisi, dirancang untuk keamanan maksimal dan hasil yang optimal.
                    </p>
                </div>
                <div class="h-[1px] flex-grow bg-surface-variant mx-8 hidden md:block"></div>
                <span class="font-label-caps text-brand-red uppercase tracking-widest border border-brand-red/40 bg-brand-red/10 px-4 py-2 shrink-0">Training Floor</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach([
                    ['Treadmill', 'Kardio Performa Tinggi', 'https://images.unsplash.com/photo-1576678927484-cc907957088c?auto=format&fit=crop&w=900&q=80'],
                    ['Smith Machine', 'Latihan Beban Terpadu', 'https://images.unsplash.com/photo-1534258936925-c58bed479fcb?auto=format&fit=crop&w=900&q=80'],
                    ['Pec Deck', 'Isolasi Otot Dada', 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=900&q=80'],
                    ['Leg Press', 'Power Majemuk Kaki', 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=900&q=80'],
                    ['Leg Extension', 'Definisi Quadriceps', 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=80'],
                    ['Mesin Sit Up', 'Core & Abs Station', 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=900&q=80'],
                    ['Twister Core', 'Stabilitas Rotasi', 'https://images.unsplash.com/photo-1593079831268-3381b0db4a77?auto=format&fit=crop&w=900&q=80'],
                    ['Preacher Curl', 'Fokus Bicep Maksimal', 'https://images.unsplash.com/photo-1584863231364-2edc166de576?auto=format&fit=crop&w=900&q=80'],
                ] as [$title, $desc, $image])
                    <div class="group relative overflow-hidden aspect-square border border-white/10 bg-white/5 backdrop-blur-md shadow-[0_18px_60px_rgba(0,0,0,.28)]">
                        <img alt="{{ $title }}" class="absolute inset-0 w-full h-full object-cover brightness-125 group-hover:brightness-150 group-hover:scale-110 transition-all duration-700" src="{{ $image }}" loading="lazy"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent opacity-80"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full translate-y-2 group-hover:translate-y-0 transition-transform">
                            <h3 class="font-headline-md text-xl uppercase text-white">{{ $title }}</h3>
                            <p class="text-[10px] font-label-caps text-brand-red opacity-0 group-hover:opacity-100 transition-opacity">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Rating / Google Reviews Section -->
    <section class="py-20 md:py-28 bg-[#0e0e0e] border-t border-white/5 relative overflow-hidden">
        <!-- Subtle background glow -->
        <div class="absolute -top-40 right-0 w-96 h-96 bg-brand-red/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-screen-2xl mx-auto px-6 md:px-16 relative z-10">
            <!-- Header Section with Google Rating Summary -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-14">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 mb-3">
                        <!-- Google "G" Icon -->
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span class="font-label-caps text-xs text-white/80 tracking-widest uppercase">Ulasan Google Maps Terverifikasi</span>
                    </div>
                    <h2 class="font-display-xl text-5xl md:text-7xl uppercase italic text-white leading-none">
                        APA KATA<br/><span class="text-brand-red">MEREKA?</span>
                    </h2>
                </div>

                <!-- Google Rating Badge Card -->
                <div class="glass-panel p-6 border border-white/10 flex flex-col sm:flex-row items-start sm:items-center gap-6 bg-gradient-to-br from-white/[0.07] to-white/[0.02]">
                    <div class="flex items-center gap-4">
                        <div class="text-4xl md:text-5xl font-display-xl font-bold text-white tracking-tight">4.9</div>
                        <div>
                            <div class="flex text-[#fbbf24] text-lg tracking-wider">
                                ★ ★ ★ ★ ★
                            </div>
                            <p class="font-label-caps text-[11px] text-on-surface-variant uppercase tracking-wider mt-1">78+ Ulasan di Google Maps</p>
                        </div>
                    </div>
                    <div class="h-10 w-[1px] bg-white/10 hidden sm:block"></div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <!-- <a href="https://maps.app.goo.gl/SfncoYX75q97MA3p7" target="_blank" rel="noopener noreferrer"
                           class="btn-primary text-xs uppercase tracking-wider py-3 px-5 flex items-center justify-center gap-2 whitespace-nowrap">
                            <span class="material-symbols-outlined text-[18px]">rate_review</span>
                            Tulis Ulasan
                        </a>
                        <a href="https://maps.app.goo.gl/SfncoYX75q97MA3p7" target="_blank" rel="noopener noreferrer"
                           class="px-4 py-3 rounded border border-white/20 bg-white/5 hover:bg-white/10 text-white text-xs font-label-caps uppercase tracking-wider transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                            Buka Maps
                        </a> -->
                    </div>
                </div>
            </div>
            
            <!-- 4 Review Tertinggi (Grid 2 Kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                @php
                    $googleReviews = [
                        [
                            'name' => "a'yunul fuadah",
                            'role' => '2 ulasan · 1 foto',
                            'rating' => 5,
                            'time' => 'Sebulan lalu',
                            'avatar' => null,
                            'text' => 'Gym terworth it di jombang cuman 5k doang🤩 Sehat ga harus mahal🤗 Buat pemula maupun yg udah advanced, wargym bener² bikin nyaman dan welcoming🫶✨',
                            'link' => 'https://maps.app.goo.gl/DD854AmcPe3CxXWZ7',
                        ],
                        [
                            'name' => 'Bintang Ilmanna',
                            'role' => 'Member WARGYM',
                            'rating' => 5,
                            'time' => '3 bulan lalu',
                            'avatar' => null,
                            'text' => 'Tempat gymnya bersih, alat alatnya juga cukup lengkap. Ada PT harian juga. PT nya profesional dan membantu sekali buat Pemula. Yang menarik kalau beres nge gym bisa cangkruk kaya di warung sambil istirahat.',
                            'link' => 'https://maps.app.goo.gl/VnvJkbAYRgqnrp2BA',
                        ],
                        [
                            'name' => 'ghiona ghionaw',
                            'role' => '1 ulasan',
                            'rating' => 5,
                            'time' => '4 bulan lalu',
                            'avatar' => null,
                            'text' => 'Tempat sangat nyaman, komunitas nya friendly banget, cocok buat pemula dan kantong pelajar, harganya murah meriah. cuma 5k per kedatangan. 60k per bulan. josjis pokoknya. gaspol ndangak!',
                            'link' => 'https://maps.app.goo.gl/9GGB3eGPq5Q5PAi6A',
                        ],
                        [
                            'name' => 'Muhammad Rofiqi',
                            'role' => '7 ulasan · 12 foto',
                            'rating' => 5,
                            'time' => '5 bulan lalu',
                            'avatar' => null,
                            'text' => 'tempat nyaman, alat mendukung sekali, bisa nongkrong bareng teman" dan bisa untuk cari teman. suasananya bagaikan bareng keluarga sendiri 💪😍🤙',
                            'link' => 'https://maps.app.goo.gl/b3hktbtyFkjkwPLf6',
                        ],
                    ];
                @endphp

                @foreach($googleReviews as $review)
                    <div class="glass-panel p-8 border border-white/10 hover:border-brand-red/60 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <!-- Reviewer Meta Header -->
                            <div class="flex items-center justify-between mb-6 gap-2">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="relative shrink-0">
                                        @if($review['avatar'])
                                            <img src="{{ $review['avatar'] }}" class="w-14 h-14 rounded-full object-cover" alt="{{ $review['name'] }}">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-white/10 text-white font-display flex items-center justify-center text-2xl uppercase">
                                                {{ substr($review['name'], 0, 1) }}
                                            </div>
                                        @endif
                                        <!-- Mini Google Icon Badge -->
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white flex items-center justify-center shadow-md">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24">
                                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ $review['link'] }}" target="_blank" rel="noopener noreferrer" class="font-headline-md text-lg text-white hover:text-brand-red transition-colors flex items-center gap-1.5 flex-wrap">
                                            <span class="truncate">{{ $review['name'] }}</span>
                                            <span class="material-symbols-outlined text-brand-red text-base shrink-0" title="Ulasan Terverifikasi">verified</span>
                                        </a>
                                        <p class="font-label-caps text-[11px] text-on-surface-variant/70 uppercase tracking-wider mt-0.5 truncate">{{ $review['role'] }}</p>
                                    </div>
                                </div>

                                <!-- Star Rating -->
                                <div class="flex flex-col items-end shrink-0">
                                    <div class="text-[#fbbf24] text-lg tracking-wider whitespace-nowrap">
                                        @for($i = 0; $i < $review['rating']; $i++)★@endfor
                                    </div>
                                    <span class="text-[10px] font-label-caps text-on-surface-variant/50 uppercase tracking-widest mt-1">{{ $review['time'] }}</span>
                                </div>
                            </div>

                            <!-- Review Text -->
                            <p class="font-body-md text-on-surface-variant italic mb-6 leading-relaxed">
                                "{{ $review['text'] }}"
                            </p>
                        </div>

                        <!-- Card Footer -->
                        <div class="pt-4 border-t border-white/5 flex items-center justify-end text-xs text-on-surface-variant/60 font-label-caps">
                            <a href="{{ $review['link'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1 group/btn">
                                <span class="group-hover/btn:underline">Lihat di Maps</span>
                                <span class="material-symbols-outlined text-[14px] group-hover/btn:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bottom Call to Action Banner -->
            <div class="glass-panel p-6 md:p-8 border border-white/10 flex flex-col md:flex-row items-center justify-between gap-6 bg-gradient-to-r from-brand-red/10 via-transparent to-white/5">
                <div class="flex items-center gap-4 text-center md:text-left">
                    <div class="w-12 h-12 rounded-full bg-brand-red/20 border border-brand-red/40 flex items-center justify-center text-brand-red shrink-0 hidden sm:flex">
                        <span class="material-symbols-outlined text-2xl">thumb_up</span>
                    </div>
                    <div>
                        <h4 class="font-headline-md text-xl uppercase text-white">Sudah Pernah Berlatih di WARGYM?</h4>
                        <p class="font-body-md text-sm text-on-surface-variant mt-1">Bantu calon member lain dengan membagikan ulasan dan pengalaman Anda di Google Maps.</p>
                    </div>
                </div>
                <a href="https://maps.app.goo.gl/SfncoYX75q97MA3p7" target="_blank" rel="noopener noreferrer" class="btn-primary text-xs uppercase tracking-wider py-3.5 px-6 whitespace-nowrap shrink-0 flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">reviews</span>
                    Beri Ulasan Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Maps Section -->
    <section class="py-20 md:py-28 bg-[#1f1f1f] border-t border-white/10">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-4 glass-panel p-6 md:p-8">
                    <span class="font-label-caps text-brand-red tracking-[0.4em] uppercase block mb-4">Base Operations</span>
                    <h2 class="font-headline-lg text-4xl uppercase italic mb-8 leading-tight text-white">LOKASI KAMI</h2>
                    <div class="space-y-10">
                        <div class="flex gap-5">
                            <div class="bg-brand-red/10 p-3 h-fit border border-brand-red/30 text-brand-red">
                                <span class="material-symbols-outlined text-brand-red">location_on</span>
                            </div>
                            <div>
                                <p class="font-headline-md text-xl uppercase text-white mb-2">WARGYM (WARUNG GYM)</p>
                                <p class="text-on-surface-variant font-body-md leading-relaxed">
                                    Sambong Dukuh,<br/>
                                    Kec. Jombang, Kabupaten Jombang,<br/>
                                    Jawa Timur, Indonesia
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="bg-brand-red/10 p-3 h-fit border border-brand-red/30 text-brand-red">
                                <span class="material-symbols-outlined text-brand-red">schedule</span>
                            </div>
                            <div>
                                <p class="font-headline-md text-xl uppercase text-white mb-2">Jam Operasional</p>
                                <p class="text-on-surface-variant font-body-md">Senin - Minggu: <span class="text-brand-red font-bold">24 JAM</span></p>
                                <p class="text-[10px] font-label-caps text-on-surface-variant/50 mt-1 uppercase italic">Selalu Siap Saat Anda Butuhkan</p>
                            </div>
                        </div>
                    </div>
                    <a class="mt-10 btn-primary w-full" href="https://maps.app.goo.gl/SfncoYX75q97MA3p7" target="_blank">
                        <span class="material-symbols-outlined text-[20px] mr-2">near_me</span>
                        Petunjuk Jalan
                    </a>
                </div>
                <div class="lg:col-span-8">
                    <div class="relative group">
                        <div class="relative border border-white/10 overflow-hidden bg-white/5 backdrop-blur-md shadow-[0_24px_80px_rgba(0,0,0,.42)]">
                            <iframe
                                class="w-full h-[360px] md:h-[520px] transition-all duration-700"
                                src="https://maps.google.com/maps?q=WARGYM%20WARUNG%20GYM,%20Jombang&t=&z=17&ie=UTF8&iwloc=B&output=embed"
                                title="Lokasi Fitness"
                                loading="lazy"></iframe>
                            <div class="absolute top-6 left-6 glass-panel p-4 flex items-center gap-4">
                                <div class="w-3 h-3 bg-brand-red rounded-full animate-pulse shadow-[0_0_10px_#ff5540]"></div>
                                <span class="font-label-caps text-xs uppercase tracking-[0.2em] text-white"> Location</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer Section (Detailed like Image 4) -->
<footer class="bg-[#131313] border-t border-white/5 pt-20 pb-10">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">
            <!-- Brand Column -->
            <div class="lg:col-span-1">
                <a class="flex items-center gap-3 mb-6" href="{{ route('member.dashboard') }}">
                    <span class="font-display-xl text-white uppercase italic text-2xl tracking-tighter leading-none">WAR <span class="text-brand-red">GYM</span></span>
                </a>
                <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-6">
                    Temukan lokasi WARGYM, pilihan kelas kebugaran, fasilitas latihan, dan personal trainer untuk mendukung perjalanan fitness Anda.
                </p>
                <div class="flex items-center gap-3">
                    <a href="https://www.instagram.com/wargym_team?utm_source=ig_web_button_share_sheet&stkn=ZDNlZDc0MzIxNw==" target="_blank" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors" title="Instagram"><span class="material-symbols-outlined text-[20px]">photo_camera</span></a>
                    <a href="https://whatsapp.com/channel/0029Vb7ysaX30LKV0mIDbu2t" target="_blank" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors" title="Saluran WhatsApp"><span class="material-symbols-outlined text-[20px]">smart_display</span></a>
                    <a href="https://facebook.com/wargym.jombang" target="_blank" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors" title="Facebook"><span class="material-symbols-outlined text-[20px]">facebook</span></a>
                    <a href="https://wa.me/6282130066694" target="_blank" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors" title="WhatsApp Admin"><span class="material-symbols-outlined text-[20px]">chat</span></a>
                </div>
            </div>

            <!-- Empty Column for Spacing -->
            <div class="hidden lg:block lg:col-span-1"></div>

            <!-- Perusahaan Column -->
            <div class="lg:col-span-1">
                <h4 class="font-headline-md text-white tracking-widest text-lg mb-6">Perusahaan</h4>
                <a href="{{ route('member.company-profile') }}" class="footer-link">Tentang Kami</a>
                <a href="#" class="footer-link">Blog Kesehatan</a>
                <a href="{{ route('member.contact') }}" class="footer-link">Hubungi Kami</a>
                <a href="#" class="footer-link">Kemitraan</a>
            </div>

            <!-- Layanan Column -->
            <div class="lg:col-span-1">
                <h4 class="font-headline-md text-white tracking-widest text-lg mb-6">Layanan</h4>
                <a href="#" class="footer-link">Fasilitas</a>
                <a href="#" class="footer-link">Kelas Group</a>
                <a href="#" class="footer-link">Personal Trainer</a>
                <a href="#" class="footer-link">Free Trial</a>
            </div>

            <!-- Cabang & Download Column
            <div class="lg:col-span-1">
                <h4 class="font-headline-md text-white tracking-widest text-lg mb-6">Cabang</h4>
                <a href="#" class="footer-link">Kediri</a>
                <a href="#" class="footer-link">Nganjuk</a>
                <a href="#" class="footer-link">Pare</a>
                <a href="#" class="footer-link font-bold text-white mt-2 inline-block border-b border-brand-red pb-1">Lihat Semua</a>


            </div> -->
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center border-t border-white/5 pt-8 text-on-surface-variant/60 font-body-md text-sm">
            <a href="#" class="hover:text-white transition-colors mb-4 md:mb-0">Kebijakan Privasi</a>
            <p>&copy; 2026 Fitness. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
