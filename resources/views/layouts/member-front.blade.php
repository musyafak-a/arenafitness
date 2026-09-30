<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'WARGYM Jombang')</title>
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
        .glass-panel { background: linear-gradient(135deg, rgba(31,31,31,.72), rgba(14,14,14,.48)); border: 1px solid rgba(255,255,255,.14); box-shadow: 0 24px 80px rgba(0,0,0,.48); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); transition: all 0.3s ease; }
        .glass-panel:hover { border-color: rgba(255,85,64,.5); box-shadow: 0 10px 40px rgba(255,85,64,0.1); transform: translateY(-4px); }
        .glass-tile { background: rgba(255,255,255,.055); border: 1px solid rgba(255,255,255,.12); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); transition: border-color .25s ease, background .25s ease, transform .25s ease; }
        .glass-tile:hover { background: rgba(255,255,255,.085); border-color: rgba(255,85,64,.52); transform: translateY(-2px); }
        .metal-grid { background-image: linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px); background-size: 48px 48px; }
        .hero-vignette { background: linear-gradient(90deg, rgba(0,0,0,.4) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,.2) 100%); }
        .nav-link { position: relative; padding-bottom: 6px; }
        .nav-link::after { content: ''; position: absolute; left: 0; bottom: 0; width: 0; height: 2px; background: #ff5540; transition: width .25s ease; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu { display: none; position: absolute; top: 100%; left: 0; min-width: 200px; background-color: #1f1f1f; border: 1px solid rgba(255,255,255,.1); box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 100; padding: 0.5rem 0; }
        .dropdown-menu a { display: block; padding: 0.75rem 1.5rem; color: #e2e2e2; font-family: 'JetBrains Mono', monospace; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.2s ease; }
        .dropdown-menu a:hover { background-color: rgba(255,85,64,.1); color: #ff5540; padding-left: 1.75rem; border-left: 2px solid #ff5540; }
        .footer-link { color: #e2e2e2; transition: color 0.2s ease; margin-bottom: 0.5rem; display: block; }
        .footer-link:hover { color: #ff5540; }
        
        .filter-btn {
            font-family: 'JetBrains Mono', monospace;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
            padding: 0.35rem 0.75rem;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px;
            color: #fff;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.05);
        }
        @media (min-width: 640px) {
            .filter-btn {
                font-size: 12px;
                letter-spacing: 0.1em;
                padding: 0.5rem 1.5rem;
            }
        }
        .filter-btn.active, .filter-btn:hover {
            border-color: #ff5540;
            background: rgba(255,85,64,0.1);
            color: #ff5540;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-background text-on-background font-body-md selection:bg-brand-red selection:text-white" @yield('body-attrs')>

@php
$navItems = [
    [
        'title' => 'beranda',
        'url' => route('member.company-profile'),
        'id' => 'home'
    ],
    [
        'title' => 'OUR SERVICE',
        'url' => null,
        'id' => 'service',
        'children' => [
            ['title' => 'Personal Training', 'url' => route('member.personal-trainer')],
        ]
    ],
    [
        'title' => 'TUTORIAL',
        'url' => null,
        'id' => 'tutorial',
        'children' => [
            ['title' => 'Gym Equipment Guide', 'url' => '#'],
            ['title' => 'Workout Plans', 'url' => '#'],
        ]
    ],
    [
        'title' => 'fasilitas',
        'url' => route('member.fasilitas'),
        'id' => 'fasilitas'
    ],
    [
        'title' => 'TEAM KITA',
        'url' => route('member.team'),
        'id' => 'team'
    ],
    [
        'title' => 'KONTAK',
        'url' => route('member.contact'),
        'id' => 'contact'
    ]
];
$activeNav = $activeNav ?? '';
@endphp

<!-- Global Header -->
<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 w-full z-50 bg-black/95 shadow-2xl border-b border-white/10">
    <div class="flex items-center justify-between h-20 px-6 md:px-16 w-full max-w-screen-2xl mx-auto">
        <a class="flex items-center gap-3" href="{{ route('member.company-profile') }}">
            <span class="font-display-xl text-white uppercase italic text-2xl tracking-tighter leading-none hidden sm:inline">WAR <span class="text-brand-red">GYM</span></span>
        </a>
        
        <nav class="hidden lg:flex items-center justify-center gap-8 h-full">
            @foreach($navItems as $item)
                @if(isset($item['children']))
                    <div class="dropdown relative h-full flex items-center">
                        <a class="nav-link font-label-caps text-label-caps text-on-surface-variant hover:text-brand-red transition-colors py-2 cursor-pointer uppercase {{ $activeNav === $item['id'] ? 'active text-white' : '' }}">{{ $item['title'] }} <span class="material-symbols-outlined text-[14px] align-middle">expand_more</span></a>
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
                        <a class="nav-link font-label-caps text-label-caps {{ $activeNav === $item['id'] ? 'active text-brand-red font-bold' : 'text-on-surface-variant' }} hover:text-brand-red transition-colors py-2 uppercase" href="{{ $item['url'] ?? '#' }}">{{ $item['title'] }}</a>
                    </div>
                @endif
            @endforeach
        </nav>
        
        <div class="flex items-center gap-4">
            @if(session('auth.role') === 'member')
                <a href="{{ route('member.dashboard') }}" class="btn-secondary text-xs px-4 py-2 hidden lg:flex">Dashboard</a>
            @else
                <a href="{{ route('member.login') }}" class="btn-primary text-xs px-4 py-2 hidden lg:flex">Masuk</a>
            @endif
            
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
                            <a href="{{ $item['url'] ?? '#' }}" class="font-headline-md {{ $activeNav === $item['id'] ? 'text-brand-red' : 'text-white' }} hover:text-brand-red text-xl uppercase transition-colors">{{ $item['title'] }}</a>
                        @endif
                    </div>
                @endforeach
                
                <div class="pt-4 border-t border-white/10 flex flex-col gap-3">
                    @if(session('auth.role') === 'member')
                        <a href="{{ route('member.dashboard') }}" class="btn-secondary text-center w-full">Dashboard</a>
                    @else
                        <a href="{{ route('member.login') }}" class="btn-primary text-center w-full">Masuk Member</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>

@yield('content')

<!-- Footer Section (Detailed like Image 4) -->
<footer class="bg-[#131313] border-t border-white/5 pt-20 pb-10 relative z-10">
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
                <a href="{{ route('member.fasilitas') }}" class="footer-link">Fasilitas</a>
                <a href="#" class="footer-link">Kelas Group</a>
                <a href="{{ route('member.personal-trainer') }}" class="footer-link">Personal Trainer</a>
                <a href="#" class="footer-link">Free Trial</a>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center border-t border-white/5 pt-8 text-on-surface-variant/60 font-body-md text-sm">
            <a href="#" class="hover:text-white transition-colors mb-4 md:mb-0">Kebijakan Privasi</a>
            <p>&copy; {{ date('Y') }} WARGYM Jombang. All rights reserved.</p>
        </div>
    </div>
</footer>

@yield('scripts')
</body>
</html>
