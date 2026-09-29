<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Team Kita | WARGYM Jombang</title>
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
        'url' => route('member.company-profile') . '#fasilitas',
    ],
    [
        'title' => 'TEAM KITA',
        'url' => route('member.team'),
        'active' => true,
    ],
    [
        'title' => 'KONTAK',
        'url' => route('member.contact'),
    ]
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
    <section class="py-16 md:py-24 bg-[#131313] relative metal-grid min-h-[calc(100vh-250px)]">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16 flex flex-col pt-12">
            <h1 class="font-headline-lg text-4xl sm:text-5xl md:text-6xl text-center uppercase italic tracking-wider text-white mb-16 drop-shadow-lg">
                CERITA <span class="text-brand-red">KAMI</span>
            </h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Text Content -->
                <div class="glass-panel p-8 md:p-12 rounded-lg border border-white/10">
                    <p class="font-body-md text-on-surface-variant text-lg leading-relaxed mb-6 text-justify">
                        Lahir di kota Jombang yang penuh energi, WARGYM memelopori standar baru kebugaran dan kini telah hadir sebagai pusat kebugaran elit di wilayah ini. Kami menghadirkan konsep gaya hidup sehat yang segar melalui ruang latihan yang nyaman, memotivasi, dan penuh energi positif untuk membantu setiap individu mencapai tujuan kebugarannya. Dengan konsep mega gym, kami memadukan peralatan modern, fasilitas berkelas, serta pilihan keanggotaan yang terjangkau, sehingga siapa pun dapat menikmati pengalaman latihan terbaik di setiap level.
                    </p>
                    <p class="font-body-md text-on-surface-variant text-lg leading-relaxed text-justify">
                        Di WARGYM, kami berkomitmen untuk memberikan pengalaman kebugaran yang unggul dan sesuai dengan beragam kebutuhan. Setiap anggota mendapatkan dukungan penuh dari instruktur dan Personal Trainer yang berpengalaman, profesional, dan berdedikasi untuk memastikan hasil terbaik. Baik berlatih secara mandiri maupun mengikuti program kelas, instruktur kami selalu menghadirkan sesi yang penuh energi dan berkualitas tinggi untuk memaksimalkan setiap sesi latihan.
                    </p>
                </div>
                
                <!-- Image Content -->
                <div class="relative group h-full flex flex-col justify-center">
                    <div class="absolute inset-0 bg-brand-red/10 blur-3xl -z-10 group-hover:bg-brand-red/20 transition-colors duration-700"></div>
                    <img src="{{ asset('images/team2.jpg') }}" alt="Cerita Kami - Team WARGYM" class="w-full h-auto max-h-[600px] object-cover rounded-xl border border-white/10 shadow-[0_0_50px_rgba(0,0,0,0.8)] transition-all duration-700 transform group-hover:scale-[1.02]">
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-[#0a0a0a] border-t border-white/10 py-12 md:py-16">
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
                <a href="{{ route('member.company-profile') }}#fasilitas" class="footer-link">Fasilitas</a>
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
