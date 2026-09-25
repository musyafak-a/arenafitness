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

<header class="fixed top-0 w-full z-50 bg-black/95 shadow-2xl border-b border-white/10">
    <div class="flex items-center justify-between h-20 px-6 md:px-16 w-full max-w-screen-2xl mx-auto">
        <a class="flex items-center gap-3" href="{{ route('member.dashboard') }}">
            <span class="font-display-xl text-white uppercase italic text-2xl tracking-tighter leading-none hidden sm:inline">s <span class="text-brand-red">Fitness</span></span>
        </a>
        
        <nav class="hidden lg:flex items-center justify-center gap-8 h-full">
            <div class="dropdown relative h-full flex items-center">
                <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 cursor-pointer">HOME <span class="material-symbols-outlined text-[14px] align-middle">expand_more</span></a>
                <div class="dropdown-menu">
                    <a href="{{ route('member.dashboard') }}">Member Dashboard</a>
                    <a href="{{ route('member.company-profile') }}">Company Profile</a>
                </div>
            </div>
            
            <div class="dropdown relative h-full flex items-center">
                <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 cursor-pointer bg-brand-red/10 text-brand-red px-3">OUR SERVICE <span class="material-symbols-outlined text-[14px] align-middle">expand_more</span></a>
                <div class="dropdown-menu">
                    <a href="#">Personal Training</a>
                    <a href="#">Group Classes</a>
                    <a href="#">Nutrition Plan</a>
                </div>
            </div>
            
            <div class="h-full flex items-center">
                <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2" href="#">BLOGGING</a>
            </div>
            
            <div class="dropdown relative h-full flex items-center">
                <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 cursor-pointer">TUTORIAL <span class="material-symbols-outlined text-[14px] align-middle">expand_more</span></a>
                <div class="dropdown-menu">
                    <a href="#">Gym Equipment Guide</a>
                    <a href="#">Workout Plans</a>
                </div>
            </div>
            
            <div class="dropdown relative h-full flex items-center">
                <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 cursor-pointer">PORTFOLIO <span class="material-symbols-outlined text-[14px] align-middle">expand_more</span></a>
                <div class="dropdown-menu">
                    <a href="#">Transformation Gallery</a>
                    <a href="#">Facilities Showcase</a>
                </div>
            </div>
            
            <div class="dropdown relative h-full flex items-center">
                <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 cursor-pointer">OUR TEAM <span class="material-symbols-outlined text-[14px] align-middle">expand_more</span></a>
                <div class="dropdown-menu">
                    <a href="#">Master Trainers</a>
                    <a href="#">Management</a>
                </div>
            </div>
            
            <div class="h-full flex items-center">
                <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2" href="#">CONTACT US</a>
            </div>
        </nav>
        
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-white hover:text-brand-red cursor-pointer">search</span>
        </div>
    </div>
</header>

<main class="pt-20">
    <!-- Carousel / Hero Section -->
    <section class="relative min-h-[600px] flex items-center overflow-hidden metal-grid" x-data="{ currentSlide: 0 }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % 3 }, 5000)">
        <!-- Slide 1 cihuy -->
        <div class="absolute inset-0 z-0 transition-opacity duration-1000" :class="currentSlide === 0 ? 'opacity-100' : 'opacity-0'">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=2200&q=85" />
            <div class="absolute inset-0 hero-vignette"></div>
        </div>
        <!-- Slide 2 -->
        <div class="absolute inset-0 z-0 transition-opacity duration-1000" :class="currentSlide === 1 ? 'opacity-100' : 'opacity-0'">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&w=1920&q=80" />
            <div class="absolute inset-0 hero-vignette"></div>
        </div>
        <!-- Slide 3 -->
        <div class="absolute inset-0 z-0 transition-opacity duration-1000" :class="currentSlide === 2 ? 'opacity-100' : 'opacity-0'">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1576678927484-cc907957088c?auto=format&fit=crop&w=1920&q=80" />
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
                        <img alt="{{ $title }}" class="absolute inset-0 w-full h-full object-cover grayscale opacity-50 group-hover:opacity-100 group-hover:scale-110 group-hover:grayscale-0 transition-all duration-700" src="{{ $image }}" loading="lazy"/>
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

    <!-- Rating / Testimonial Section -->
    <section class="py-20 md:py-28 bg-[#0e0e0e]">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
            <span class="font-label-caps text-on-surface-variant tracking-[0.2em] uppercase block mb-2">Kata Member Kami</span>
            <h2 class="font-display-xl text-5xl md:text-7xl uppercase italic mb-12 text-white leading-none">
                APA KATA<br/><span class="text-brand-red">MEREKA?</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Testimonial 1 -->
                <div class="glass-panel p-8 border border-white/5 hover:border-brand-red/50 transition-colors">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/150?img=1" class="w-16 h-16 rounded-full border-2 border-brand-red object-cover" alt="Avatar">
                        <div class="flex text-[#ffb4a8] text-xl">
                            ★ ★ ★ ★ ★
                        </div>
                    </div>
                    <p class="font-body-md text-on-surface-variant italic mb-8 leading-relaxed">
                        "Nge-gym di Fitness sangat menyenangkan, rasanya seperti family-space banget. Personal Trainer dan staff di sini seru-seru. Alat-alatnya lengkap dan fasilitas lengkap."
                    </p>
                    <div>
                        <h4 class="font-headline-md text-xl text-white">Marion Jola</h4>
                        <p class="font-label-caps text-[11px] text-on-surface-variant/60 uppercase tracking-widest mt-1">Member Kediri - Artis & Penyanyi</p>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="glass-panel p-8 border border-white/5 hover:border-brand-red/50 transition-colors">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-16 h-16 rounded-full border-2 border-brand-red object-cover" alt="Avatar">
                        <div class="flex text-[#ffb4a8] text-xl">
                            ★ ★ ★ ★ ★
                        </div>
                    </div>
                    <p class="font-body-md text-on-surface-variant italic mb-8 leading-relaxed">
                        " Fitness Kediri jadi tempat gym yang paling asik, dingin, dan Personal Trainer-nya juga very helpful."
                    </p>
                    <div>
                        <h4 class="font-headline-md text-xl text-white">Dennis Talakua</h4>
                        <p class="font-label-caps text-[11px] text-on-surface-variant/60 uppercase tracking-widest mt-1">Member Kediri - Atlet Sepakbola</p>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="glass-panel p-8 border border-white/5 hover:border-brand-red/50 transition-colors">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/150?img=5" class="w-16 h-16 rounded-full border-2 border-brand-red object-cover" alt="Avatar">
                        <div class="flex text-[#ffb4a8] text-xl">
                            ★ ★ ★ ★ ★
                        </div>
                    </div>
                    <p class="font-body-md text-on-surface-variant italic mb-8 leading-relaxed">
                        "Pengalamannya seru, alat-alatnya lengkap, dan Personal Trainer sangat kooperatif menyesuaikan program dengan kebutuhan saya."
                    </p>
                    <div>
                        <h4 class="font-headline-md text-xl text-white">fitness</h4>
                        <p class="font-label-caps text-[11px] text-on-surface-variant/60 uppercase tracking-widest mt-1">Member  Nganjuk</p>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="glass-panel p-8 border border-white/5 hover:border-brand-red/50 transition-colors">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/150?img=8" class="w-16 h-16 rounded-full border-2 border-brand-red object-cover" alt="Avatar">
                        <div class="flex text-[#ffb4a8] text-xl">
                            ★ ★ ★ ★ ★
                        </div>
                    </div>
                    <p class="font-body-md text-on-surface-variant italic mb-8 leading-relaxed">
                        " Fitness sangat nyaman. Alat gym modern, selalu dijaga kebersihannya. Pelayanan CS dan semua staf juga ramah."
                    </p>
                    <div>
                        <h4 class="font-headline-md text-xl text-white">Awanda Sentosa</h4>
                        <p class="font-label-caps text-[11px] text-on-surface-variant/60 uppercase tracking-widest mt-1">Member  Pare</p>
                    </div>
                </div>
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
                    <a class="mt-10 btn-primary w-full" href="https://share.google/CjsjDpiT7wWuKf6Oq" target="_blank">
                        <span class="material-symbols-outlined text-[20px] mr-2">near_me</span>
                        Petunjuk Jalan
                    </a>
                </div>
                <div class="lg:col-span-8">
                    <div class="relative group">
                        <div class="relative border border-white/10 overflow-hidden bg-white/5 backdrop-blur-md shadow-[0_24px_80px_rgba(0,0,0,.42)]">
                            <iframe
                                class="w-full h-[360px] md:h-[520px] transition-all duration-700"
                                src="https://maps.google.com/maps?q=WARGYM+(WARUNG+GYM)&t=&z=17&ie=UTF8&iwloc=&output=embed"
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
                    <span class="font-display-xl text-white uppercase italic text-2xl tracking-tighter leading-none">s <span class="text-brand-red">Fitness</span></span>
                </a>
                <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-6">
                    Temukan lokasi Fitness, pilihan kelas kebugaran, fasilitas latihan, dan personal trainer untuk mendukung perjalanan fitness Anda.
                </p>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors"><span class="material-symbols-outlined text-[20px]">photo_camera</span></a>
                    <a href="#" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors"><span class="material-symbols-outlined text-[20px]">smart_display</span></a>
                    <a href="#" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors"><span class="material-symbols-outlined text-[20px]">facebook</span></a>
                    <a href="#" class="w-10 h-10 rounded-md bg-[#1f1f1f] flex items-center justify-center text-white hover:bg-brand-red hover:text-black transition-colors"><span class="material-symbols-outlined text-[20px]">music_note</span></a>
                </div>
            </div>

            <!-- Empty Column for Spacing -->
            <div class="hidden lg:block lg:col-span-1"></div>

            <!-- Perusahaan Column -->
            <div class="lg:col-span-1">
                <h4 class="font-headline-md text-white tracking-widest text-lg mb-6">Perusahaan</h4>
                <a href="#" class="footer-link">Tentang Kami</a>
                <a href="#" class="footer-link">Blog Kesehatan</a>
                <a href="#" class="footer-link">Hubungi Kami</a>
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

            <!-- Cabang & Download Column -->
            <div class="lg:col-span-1">
                <h4 class="font-headline-md text-white tracking-widest text-lg mb-6">Cabang</h4>
                <a href="#" class="footer-link">Kediri</a>
                <a href="#" class="footer-link">Nganjuk</a>
                <a href="#" class="footer-link">Pare</a>
                <a href="#" class="footer-link font-bold text-white mt-2 inline-block border-b border-brand-red pb-1">Lihat Semua</a>


            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center border-t border-white/5 pt-8 text-on-surface-variant/60 font-body-md text-sm">
            <a href="#" class="hover:text-white transition-colors mb-4 md:mb-0">Kebijakan Privasi</a>
            <p>&copy; 2026 Fitness. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
