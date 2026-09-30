<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Fasilitas | WARGYM Jombang</title>
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
        
        .filter-btn {
            font-family: 'JetBrains Mono', monospace;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.1em;
            padding: 0.5rem 1.5rem;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px;
            color: #fff;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.05);
        }
        .filter-btn.active, .filter-btn:hover {
            border-color: #ff5540;
            background: rgba(255,85,64,0.1);
            color: #ff5540;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-brand-red selection:text-white" x-data="{ currentCategory: 'all' }">

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
            ['title' => 'Personal Training', 'url' => route('member.personal-trainer')],
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
        'url' => route('member.fasilitas'),
        'active' => true,
    ],
    [
        'title' => 'TEAM KITA',
        'url' => route('member.team'),
    ],
    [
        'title' => 'KONTAK',
        'url' => route('member.contact'),
    ]
];

$facilities = [
    ['name' => 'Treadmill', 'qty' => '3 Unit', 'category' => 'cardio', 'img' => 'https://images.unsplash.com/photo-1576678927484-cc907cb5f334?w=500&h=400&fit=crop'],
    ['name' => 'Samsak / Sandsack', 'qty' => '1 Unit', 'category' => 'cardio', 'img' => 'https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?w=500&h=400&fit=crop'],
    
    ['name' => 'Smith Machine', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1584466977710-1ce99bb4d336?w=500&h=400&fit=crop'],
    ['name' => 'Chest Press', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=500&h=400&fit=crop'],
    ['name' => 'Leg Curl + Leg Ext + Triceps', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=500&h=400&fit=crop'],
    ['name' => 'LatPullDown + Seated Row', 'qty' => '2 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1596357395217-80de13130e92?w=500&h=400&fit=crop'],
    ['name' => 'Flat Bench Press', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=500&h=400&fit=crop'],
    ['name' => 'Adjustable Bench', 'qty' => '3 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=500&h=400&fit=crop'],
    ['name' => 'Abductor + Adductor', 'qty' => '2 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=500&h=400&fit=crop'],
    ['name' => 'Peck Deck / Butterfly', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1596357395217-80de13130e92?w=500&h=400&fit=crop'],
    ['name' => 'Leg Press + Dips', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=500&h=400&fit=crop'],
    ['name' => 'Alat Sit Up', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=500&h=400&fit=crop'],
    ['name' => 'Dumbbell Set', 'qty' => '1-4kg & 2.5-20kg', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=500&h=400&fit=crop'],
    ['name' => 'Tempat Pull Up', 'qty' => '2 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1598971639058-fab3c3109a00?w=500&h=400&fit=crop'],
    ['name' => 'Bumper Plate (Deadlift)', 'qty' => '5-20kg', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=500&h=400&fit=crop'],
    
    ['name' => 'Kamar Mandi', 'qty' => '2 Ruang', 'category' => 'lainnya', 'img' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=500&h=400&fit=crop'],
    ['name' => 'Toilet', 'qty' => '2 Ruang', 'category' => 'lainnya', 'img' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=500&h=400&fit=crop'],
    ['name' => 'Warung', 'qty' => '1 Unit', 'category' => 'lainnya', 'img' => 'https://images.unsplash.com/photo-1521790797524-b2497295b8a0?w=500&h=400&fit=crop'],
];
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

        <div class="hidden lg:flex items-center gap-4">
            @if(session('auth.role') === 'member')
                <a href="{{ route('member.dashboard') }}" class="btn-secondary text-xs px-4 py-2">Dashboard</a>
            @else
                <a href="{{ route('member.login') }}" class="btn-primary text-xs px-4 py-2">Masuk</a>
            @endif
        </div>

        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white p-2">
            <span class="material-symbols-outlined text-3xl" x-text="mobileMenuOpen ? 'close' : 'menu'"></span>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-collapse class="lg:hidden bg-[#1f1f1f] border-t border-white/10">
        <div class="px-6 py-4 space-y-4">
            @foreach($navItems as $item)
                @if(isset($item['children']))
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-left font-label-caps text-white uppercase py-2">
                            {{ $item['title'] }}
                            <span class="material-symbols-outlined" x-text="open ? 'expand_less' : 'expand_more'"></span>
                        </button>
                        <div x-show="open" class="pl-4 pb-2 space-y-2 mt-2">
                            @foreach($item['children'] as $child)
                                <a href="{{ $child['url'] }}" class="block font-label-caps text-sm text-on-surface-variant hover:text-brand-red uppercase py-1">{{ $child['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['url'] ?? '#' }}" class="block font-label-caps text-white uppercase py-2 {{ !empty($item['active']) ? 'text-brand-red font-bold' : '' }}">{{ $item['title'] }}</a>
                @endif
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
</header>

<main class="pt-20">
    <section class="py-16 md:py-24 bg-[#131313] relative metal-grid min-h-screen">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16 flex flex-col pt-12">
            
            <!-- Header Section -->
            <div class="text-center mb-12">
                <span class="font-label-caps text-brand-red text-xs uppercase tracking-[0.3em] block mb-4">[ INVENTORY ]</span>
                <h1 class="font-headline-lg text-4xl sm:text-5xl md:text-6xl uppercase italic tracking-wider text-white drop-shadow-lg">
                    KITA <span class="text-brand-red">PUNYA</span>
                </h1>
                <p class="font-body-md text-on-surface-variant text-lg mt-4 max-w-2xl mx-auto">
                    Beragam fasilitas kelas dunia yang siap memaksimalkan pengalaman latihan harian Anda.
                </p>
            </div>

            <!-- Filter Section -->
            <div class="flex flex-wrap justify-center gap-3 mb-16">
                <button @click="currentCategory = 'all'" :class="{ 'active': currentCategory === 'all' }" class="filter-btn">Semua</button>
                <button @click="currentCategory = 'cardio'" :class="{ 'active': currentCategory === 'cardio' }" class="filter-btn">Cardio</button>
                <button @click="currentCategory = 'strength'" :class="{ 'active': currentCategory === 'strength' }" class="filter-btn">Strength</button>
                <button @click="currentCategory = 'lainnya'" :class="{ 'active': currentCategory === 'lainnya' }" class="filter-btn">Lainnya</button>
            </div>
            
            <!-- Cards Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($facilities as $facility)
                    <div x-show="currentCategory === 'all' || currentCategory === '{{ $facility['category'] }}'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="glass-panel rounded-2xl flex flex-col group cursor-default relative overflow-hidden h-[360px] border border-white/10 hover:border-brand-red/60 transition-all duration-300 p-0">
                        
                        <!-- Top 2/3 Image -->
                        <div class="h-[240px] w-full relative overflow-hidden bg-black shrink-0">
                            <img src="{{ $facility['img'] }}" alt="{{ $facility['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-100">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#131313] to-transparent"></div>
                        </div>
                         
                        <!-- Bottom 1/3 Text -->
                        <div class="flex flex-col p-5 flex-grow justify-between relative z-10 bg-[#131313]/50">
                            <h3 class="font-headline-md text-lg text-white tracking-wide uppercase line-clamp-2 leading-tight">
                                {{ $facility['name'] }}
                            </h3>
                            
                            <div class="flex items-center justify-between mt-auto pt-3 border-t border-white/10">
                                <span class="font-label-caps text-xs text-brand-red font-bold tracking-widest">
                                    {{ $facility['qty'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-[#0a0a0a] border-t border-white/10 py-12 md:py-16 relative z-10">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-16 grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-8">
        <div class="md:col-span-1">
            <a class="flex items-center gap-3 mb-6" href="{{ route('member.company-profile') }}">
                <span class="font-display-xl text-white uppercase italic text-3xl tracking-tighter leading-none">WAR <span class="text-brand-red">GYM</span></span>
            </a>
            <p class="text-on-surface-variant font-body-md text-sm leading-relaxed mb-6">
                Fasilitas premium dengan standar internasional. Dirancang khusus untuk komunitas fitness elit dan enthusiast di Jombang.
            </p>
        </div>
        
        <div class="md:col-span-1">
            <h4 class="font-label-caps text-white mb-6 uppercase tracking-widest text-sm">Navigasi</h4>
            <div class="space-y-3 font-label-caps text-xs">
                <a href="{{ route('member.company-profile') }}" class="footer-link">Beranda</a>
                <a href="{{ route('member.company-profile') }}#service" class="footer-link">Layanan</a>
                <a href="{{ route('member.fasilitas') }}" class="footer-link">Fasilitas</a>
                <a href="{{ route('member.team') }}" class="footer-link">Team Kita</a>
            </div>
        </div>
        
        <div class="md:col-span-2">
            <h4 class="font-label-caps text-white mb-6 uppercase tracking-widest text-sm">Informasi Kontak</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 font-body-md text-sm text-on-surface-variant">
                <div>
                    <p class="text-white font-bold mb-1 font-label-caps text-xs">ALAMAT</p>
                    <p>Sambong Dukuh,<br/>Kec. Jombang, Kab. Jombang</p>
                </div>
                <div>
                    <p class="text-white font-bold mb-1 font-label-caps text-xs">OPERASIONAL</p>
                    <p class="text-brand-red font-bold">24 JAM NON-STOP</p>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-screen-2xl mx-auto px-6 md:px-16 mt-12 pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="font-label-caps text-[10px] text-on-surface-variant/50 uppercase tracking-widest">
            &copy; {{ date('Y') }} WARGYM Jombang. All rights reserved.
        </p>
        <div class="flex items-center gap-2 font-label-caps text-[10px] text-on-surface-variant/50">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            SYSTEM ONLINE
        </div>
    </div>
</footer>

</body>
</html>
