@extends('layouts.member-front')
@section('title', 'Personal Trainer | WARGYM Jombang')
@php $activeNav = 'service'; @endphp

@section('content')

<main class="pt-20" x-data="{
    activeModal: null,
    openModal(id) {
        this.activeModal = id;
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        this.activeModal = null;
        document.body.style.overflow = '';
    }
}" @keydown.escape.window="closeModal()">
    <!-- Hero Banner -->
    <div class="relative w-full h-[400px] md:h-[500px] border-b border-white/10 metal-grid bg-[#131313]">
        <div class="absolute inset-0 bg-gradient-to-t from-[#131313] via-transparent to-transparent z-10 opacity-70"></div>
        <img src="{{ asset('images/team1.jpg') }}" alt="Personal Trainer" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 flex flex-col justify-end items-center z-20 pb-16 px-6">
            <span class="font-label-caps text-brand-red text-xs uppercase tracking-[0.3em] block mb-2">[ ELITE COACHING ]</span>
            <h1 class="font-headline-lg text-4xl sm:text-6xl md:text-7xl uppercase italic tracking-tight text-white mb-2 drop-shadow-lg">
                PERSONAL <span class="text-brand-red">TRAINER</span>
            </h1>
        </div>
    </div>

    <!-- Personal Trainers Section -->
    <section class="py-16 md:py-24 bg-[#131313] relative">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16">
            <div class="mb-12 text-center md:text-left">
                <span class="font-label-caps text-brand-red text-xs uppercase tracking-[0.3em] block mb-3">[ PROFIL COACH ]</span>
                <h2 class="font-headline-md text-3xl md:text-5xl mb-4 text-white uppercase italic tracking-wider">
                    Temukan PT <span class="text-brand-red">sesuai kebutuhanmu!</span>
                </h2>
                <p class="font-body-md text-on-surface-variant text-lg md:text-xl max-w-3xl">
                    Capai tujuan fitness lebih aman, terukur, dan berkelanjutan dengan bimbingan personal trainer berpengalaman. Klik card untuk melihat profil lengkap.
                </p>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                
                <!-- CARD GHION (TEXT ONLY) -->
                <div 
                    @click="openModal('ghion')" 
                    class="glass-panel p-6 sm:p-8 flex flex-col justify-between border border-white/10 hover:border-brand-red/70 transition-all duration-300 group cursor-pointer rounded-xl relative hover:shadow-[0_10px_35px_rgba(255,85,64,0.15)]"
                >
                    <div>
                        <!-- Header Name -->
                        <div class="mb-5 pb-5 border-b border-white/10">
                            <h3 class="font-headline-md text-3xl sm:text-4xl text-white uppercase tracking-wider m-0 leading-tight group-hover:text-brand-red transition-colors">
                                KAK GHION
                            </h3>
                        </div>

                        <!-- Spesialisasi Tags Preview -->
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            @foreach(['Longevity', 'Healthy Aging', 'Fitness', 'Fat Loss', 'Muscle Building'] as $tag)
                                <span class="px-2.5 py-1 bg-white/5 border border-white/10 text-brand-red font-label-caps text-[10px] uppercase tracking-wider rounded">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Deskripsi Preview -->
                        <div class="p-4 bg-white/5 rounded-lg border border-white/5 mb-4">
                            <p class="font-body-md text-xs sm:text-sm leading-relaxed text-on-surface-variant line-clamp-3">
                                Personal Trainer yang berfokus pada kebugaran, kesehatan, pengelolaan berat badan, massa otot, serta longevity—membangun tubuh yang kuat dan sehat untuk kualitas hidup jangka panjang.
                            </p>
                        </div>
                    </div>

                    <!-- CTA Action Button -->
                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-brand-red group-hover:text-white transition-colors">
                        <span class="font-headline-md text-xs sm:text-sm tracking-wider uppercase flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                            Buka Profil Lengkap
                        </span>
                        <span class="material-symbols-outlined text-[18px] transform group-hover:translate-x-1 transition-transform">
                            arrow_forward
                        </span>
                    </div>
                </div>

                <!-- CARD FANNY (TEXT ONLY) -->
                <div 
                    @click="openModal('fanny')" 
                    class="glass-panel p-6 sm:p-8 flex flex-col justify-between border border-white/10 hover:border-brand-red/70 transition-all duration-300 group cursor-pointer rounded-xl relative hover:shadow-[0_10px_35px_rgba(255,85,64,0.15)]"
                >
                    <div>
                        <!-- Header Name -->
                        <div class="mb-5 pb-5 border-b border-white/10">
                            <h3 class="font-headline-md text-3xl sm:text-4xl text-white uppercase tracking-wider m-0 leading-tight group-hover:text-brand-red transition-colors">
                                COACH FANNY
                            </h3>
                        </div>

                        <!-- Spesialisasi Tags Preview -->
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            @foreach(['Beginner Friendly', 'Women Fitness', 'Strength Training', 'Body Composition'] as $tag)
                                <span class="px-2.5 py-1 bg-white/5 border border-white/10 text-brand-red font-label-caps text-[10px] uppercase tracking-wider rounded">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Deskripsi Preview -->
                        <div class="p-4 bg-white/5 rounded-lg border border-white/5 mb-4">
                            <p class="font-body-md text-xs sm:text-sm leading-relaxed text-on-surface-variant line-clamp-3">
                                Saya pernah berada di titik ketika berat badan saya 53 kg dengan tinggi badan 148 cm. Kini, berat badan saya berada di angka 42 kg. Perjalanan ini mengajarkan saya bahwa perubahan fisik bukan hanya tentang angka di timbangan, tetapi juga tentang proses mengenal tubuh, membangun disiplin, dan menerapkan gaya hidup yang lebih sehat.
                            </p>
                        </div>
                    </div>

                    <!-- CTA Action Button -->
                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-brand-red group-hover:text-white transition-colors">
                        <span class="font-headline-md text-xs sm:text-sm tracking-wider uppercase flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                            Buka Profil Lengkap
                        </span>
                        <span class="material-symbols-outlined text-[18px] transform group-hover:translate-x-1 transition-transform">
                            arrow_forward
                        </span>
                    </div>
                </div>

                <!-- CARD IZAA (TEXT ONLY) -->
                <div 
                    @click="openModal('izaa')" 
                    class="glass-panel p-6 sm:p-8 flex flex-col justify-between border border-white/10 hover:border-brand-red/70 transition-all duration-300 group cursor-pointer rounded-xl relative hover:shadow-[0_10px_35px_rgba(255,85,64,0.15)]"
                >
                    <div>
                        <!-- Header Name -->
                        <div class="mb-5 pb-5 border-b border-white/10">
                            <h3 class="font-headline-md text-3xl sm:text-4xl text-white uppercase tracking-wider m-0 leading-tight group-hover:text-brand-red transition-colors">
                                KAK IZAA
                            </h3>
                        </div>

                        <!-- Spesialisasi Tags Preview -->
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            @foreach(['Weight Loss', 'Body Shaping', 'Healthy Lifestyle', 'Fitness'] as $tag)
                                <span class="px-2.5 py-1 bg-white/5 border border-white/10 text-brand-red font-label-caps text-[10px] uppercase tracking-wider rounded">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Deskripsi Preview -->
                        <div class="p-4 bg-white/5 rounded-lg border border-white/5 mb-4">
                            <p class="font-body-md text-xs sm:text-sm leading-relaxed text-on-surface-variant line-clamp-3">
                                Personal Trainer yang berdedikasi membantu Anda memiliki tubuh lebih sehat, bugar, dan percaya diri melalui latihan yang aman, bertahap, dan sesuai kemampuan.
                            </p>
                        </div>
                    </div>

                    <!-- CTA Action Button -->
                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-brand-red group-hover:text-white transition-colors">
                        <span class="font-headline-md text-xs sm:text-sm tracking-wider uppercase flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                            Buka Profil Lengkap
                        </span>
                        <span class="material-symbols-outlined text-[18px] transform group-hover:translate-x-1 transition-transform">
                            arrow_forward
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- POPUP MODAL: PROFIL LENGKAP COACH GHION   -->
    <!-- ========================================== -->
    <div 
        x-show="activeModal === 'ghion'" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-6 bg-black/85 backdrop-blur-md"
        @click.self="closeModal"
        style="display: none;"
    >
        <div class="relative w-full max-w-4xl max-h-[92vh] bg-[#141414] border border-white/15 rounded-2xl shadow-[0_0_60px_rgba(255,85,64,0.18)] flex flex-col overflow-hidden text-left">
            
            <!-- Modal Header (Tanpa Foto) -->
            <div class="sticky top-0 z-20 bg-[#161616]/95 backdrop-blur-md px-6 py-4 border-b border-white/10 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-headline-md text-lg sm:text-2xl text-white uppercase tracking-wider m-0">
                            GHION
                        </h3>
                        <span class="px-2 py-0.5 bg-brand-red text-black text-[10px] font-bold rounded uppercase">
                            32 TAHUN
                        </span>
                    </div>
                    <p class="text-xs text-brand-red font-medium">
                        Personal Trainer / Fitness Coach
                    </p>
                </div>

                <!-- Close Button -->
                <button 
                    @click="closeModal" 
                    class="p-2 rounded-full bg-white/5 hover:bg-brand-red hover:text-black text-white/70 transition-colors flex items-center justify-center"
                    title="Tutup (Esc)"
                >
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-8 text-on-surface">
                
                <!-- Top Summary Badge -->
                <div class="bg-white/5 border border-white/10 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="font-label-caps text-brand-red text-[10px] uppercase tracking-widest block mb-1">PROFIL PELATIH GYM</span>
                        <h4 class="font-headline-md text-white text-base sm:text-lg uppercase">
                            WARGYM PERSONAL TRAINER
                        </h4>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(['Longevity', 'Healthy Aging', 'Fitness', 'Fat Loss', 'Muscle Building'] as $tag)
                            <span class="px-2.5 py-1 bg-brand-red/10 border border-brand-red/30 text-brand-red text-xs font-semibold rounded-full">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- 1. PERJALANAN & PENGALAMAN PRIBADI -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">history_edu</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            PERJALANAN & PENGALAMAN PRIBADI
                        </h4>
                    </div>
                    <p class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Perjalanan saya di dunia fitness berakar dari pengalaman hidup yang sangat personal. Sejak usia 18 tahun, saya telah bergelut dengan kondisi autoimun yang mempengaruhi kesehatan dan kemampuan fisik saya.
                    </p>
                    <p class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Pengalaman hidup dengan kondisi tersebut membawa saya pada pemahaman yang lebih dalam mengenai tubuh manusia: bagaimana tubuh merespons stres, pentingnya proses pemulihan (recovery), batas kemampuan fisik, serta bagaimana latihan harus dirancang untuk mendukung kesehatan jangka panjang, bukan sekadar memaksakan performa sesaat.
                    </p>
                    <div class="p-4 bg-brand-red/10 border border-brand-red/30 rounded-xl">
                        <p class="font-body-md text-xs sm:text-sm text-white leading-relaxed italic">
                            "Kondisi ini membentuk cara pandang saya terhadap olahraga—bahwa latihan bukan sekadar mengejar target estetika jangka pendek, melainkan sebuah investasi kesehatan untuk mempertahankan fungsi tubuh agar tetap optimal sepanjang hidup."
                        </p>
                    </div>
                </div>

                <!-- 2. FOKUS KEPELATIHAN -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">vital_signs</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            FOKUS KEPELATIHAN: LONGEVITY & HEALTHY AGING
                        </h4>
                    </div>
                    <p class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Saya memandang fitness melalui lensa <strong class="text-white">longevity</strong>—yaitu upaya memperpanjang tidak hanya usia harapan hidup (lifespan), tetapi juga kualitas hidup yang sehat, aktif, dan bebas ketergantungan (healthspan).
                    </p>
                    <p class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Latihan dirancang untuk membantu klien:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach([
                            'Mempertahankan dan meningkatkan massa otot (melawan sarcopenia)',
                            'Meningkatkan kepadatan tulang dan stabilitas sendi',
                            'Menjaga mobilitas, fleksibilitas, dan keseimbangan tubuh',
                            'Meningkatkan kapasitas kardiovaskular dan stamina harian',
                            'Menjaga kemandirian fungsional hingga usia lanjut'
                        ] as $point)
                            <div class="flex items-start gap-2.5 p-3 bg-white/5 rounded-lg border border-white/5">
                                <span class="text-brand-red font-bold text-sm mt-0.5">✓</span>
                                <span class="text-xs sm:text-sm text-white/90">{{ $point }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. SPESIALISASI UTAMA -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">fitness_center</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            SPESIALISASI UTAMA
                        </h4>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white/5 border border-white/10 p-4 rounded-xl space-y-1.5 hover:border-brand-red/50 transition-colors">
                            <h5 class="font-headline-md text-base text-white uppercase flex items-center gap-2">
                                <span>🧬</span> Longevity Fitness
                            </h5>
                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Pendekatan latihan terencana untuk mendukung kesehatan metabolik, mobilitas sendi, fungsi gerak, dan kualitas hidup jangka panjang.
                            </p>
                        </div>
                        <div class="bg-white/5 border border-white/10 p-4 rounded-xl space-y-1.5 hover:border-brand-red/50 transition-colors">
                            <h5 class="font-headline-md text-base text-white uppercase flex items-center gap-2">
                                <span>⚖️</span> Fat Loss & Body Recomposition
                            </h5>
                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Membantu menurunkan kadar lemak tubuh secara bertahap dan berkelanjutan sambil mempertahankan massa otot tanpa metode ekstrem yang merugikan metabolisme.
                            </p>
                        </div>
                        <div class="bg-white/5 border border-white/10 p-4 rounded-xl space-y-1.5 hover:border-brand-red/50 transition-colors">
                            <h5 class="font-headline-md text-base text-white uppercase flex items-center gap-2">
                                <span>❤️</span> Health & Fitness
                            </h5>
                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Membangun kebugaran secara menyeluruh melalui peningkatan kekuatan, daya tahan, mobilitas, koordinasi, dan kapasitas fisik untuk menunjang aktivitas sehari-hari.
                            </p>
                        </div>
                        <div class="bg-white/5 border border-white/10 p-4 rounded-xl space-y-1.5 hover:border-brand-red/50 transition-colors">
                            <h5 class="font-headline-md text-base text-white uppercase flex items-center gap-2">
                                <span>💪</span> Strength Training
                            </h5>
                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Mengembangkan kekuatan dan massa otot sebagai salah satu fondasi penting untuk menjaga kemampuan fisik, terutama dalam menghadapi proses penuaan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 4. PENDEKATAN LATIHAN -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">tune</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            PENDEKATAN LATIHAN
                        </h4>
                    </div>

                    <p class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Saya percaya bahwa setiap tubuh memiliki cerita dan kebutuhan yang berbeda. Karena itu, program latihan disesuaikan dengan kondisi dan tujuan masing-masing klien, termasuk:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        @foreach([
                            'Usia dan tingkat kebugaran',
                            'Pengalaman latihan',
                            'Komposisi tubuh',
                            'Tujuan fat loss atau muscle gain',
                            'Kekuatan dan kemampuan gerak',
                            'Aktivitas sehari-hari',
                            'Kemampuan recovery',
                            'Riwayat kondisi kesehatan yang relevan',
                            'Target jangka pendek dan jangka panjang'
                        ] as $point)
                            <div class="flex items-center gap-2.5 p-2.5 bg-white/5 rounded-lg border border-white/5">
                                <span class="text-brand-red font-bold">✓</span>
                                <span class="text-xs sm:text-sm text-white/90">{{ $point }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Principle Workflow -->
                    <div class="bg-black/50 border border-brand-red/30 p-4 rounded-xl text-center space-y-2">
                        <span class="font-label-caps text-brand-red text-[10px] uppercase tracking-widest block">PRINSIP KERJA</span>
                        <div class="font-headline-md text-xs sm:text-base text-white tracking-wider flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                            <span class="px-2.5 py-1 bg-white/10 rounded">Measure</span>
                            <span class="text-brand-red">&gt;</span>
                            <span class="px-2.5 py-1 bg-white/10 rounded">Plan</span>
                            <span class="text-brand-red">&gt;</span>
                            <span class="px-2.5 py-1 bg-white/10 rounded">Train</span>
                            <span class="text-brand-red">&gt;</span>
                            <span class="px-2.5 py-1 bg-white/10 rounded">Adapt</span>
                            <span class="text-brand-red">&gt;</span>
                            <span class="px-2.5 py-1 bg-brand-red text-black font-bold rounded">Progress</span>
                        </div>
                        <p class="text-xs sm:text-sm text-on-surface-variant pt-2">
                            Latihan dimulai dari kemampuan yang dimiliki, kemudian dikembangkan secara bertahap berdasarkan respons tubuh dan perkembangan klien.
                        </p>
                    </div>
                </div>

                <!-- 5. FILOSOFI LATIHAN -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">psychology</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            FILOSOFI LATIHAN
                        </h4>
                    </div>

                    <blockquote class="p-4 sm:p-5 bg-white/5 border-l-4 border-brand-red rounded-r-xl">
                        <p class="font-headline-md text-base sm:text-lg text-white italic tracking-wide">
                            "Bukan sekadar membentuk tubuh untuk hari ini, tetapi membangun tubuh yang mampu menemani kita menjalani kehidupan lebih panjang dengan kualitas yang lebih baik."
                        </p>
                    </blockquote>

                    <p class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Fitness bukan hanya mengenai six-pack, angka timbangan, atau seberapa berat beban yang dapat diangkat. Fitness adalah tentang kemampuan untuk:
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="p-3 bg-white/5 rounded-lg border border-white/5 text-center">
                            <p class="text-xs sm:text-sm font-medium text-white">Bergerak dengan baik</p>
                        </div>
                        <div class="p-3 bg-white/5 rounded-lg border border-white/5 text-center">
                            <p class="text-xs sm:text-sm font-medium text-white">Menjadi lebih kuat</p>
                        </div>
                        <div class="p-3 bg-white/5 rounded-lg border border-white/5 text-center">
                            <p class="text-xs sm:text-sm font-medium text-white">Memiliki stamina</p>
                        </div>
                        <div class="p-3 bg-white/5 rounded-lg border border-white/5 text-center">
                            <p class="text-xs sm:text-sm font-medium text-white">Mempertahankan massa otot</p>
                        </div>
                        <div class="p-3 bg-white/5 rounded-lg border border-white/5 text-center">
                            <p class="text-xs sm:text-sm font-medium text-white">Menjaga fungsi tubuh</p>
                        </div>
                        <div class="p-3 bg-white/5 rounded-lg border border-white/5 text-center">
                            <p class="text-xs sm:text-sm font-medium text-white">Tetap mandiri & aktif seiring usia</p>
                        </div>
                    </div>

                    <p class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Pengalaman saya sendiri mengajarkan bahwa kondisi tubuh dapat berubah, tetapi dengan pendekatan yang tepat, konsistensi, dan perhatian terhadap kondisi kesehatan, kita dapat terus berusaha membangun kemampuan fisik yang lebih baik.
                    </p>
                </div>

                <!-- 6. VISI & MISI KEPELATIHAN -->
                <div class="bg-gradient-to-br from-[#1d1414] to-[#121212] border border-brand-red/30 p-5 sm:p-6 rounded-xl space-y-4">
                    <span class="font-label-caps text-brand-red text-xs uppercase tracking-widest block">VISI & MISI</span>
                    <h4 class="font-headline-md text-lg text-white uppercase tracking-wide">
                        Membangun Gaya Hidup Kebugaran yang Bertanggung Jawab
                    </h4>
                    <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">
                        Misi saya sebagai pelatih adalah mendampingi setiap individu menemukan jalur latihan yang aman, efektif, dan berkelanjutan—membantu mereka membangun kekuatan, menjaga kesehatan, dan menjalani hidup dengan vitalitas yang lebih baik.
                    </p>
                </div>

                <!-- Slogan & CTA Banner -->
                <div class="bg-gradient-to-r from-brand-red/20 via-black to-brand-red/20 border border-brand-red/40 p-6 rounded-2xl text-center space-y-3">
                    <p class="font-headline-md text-base sm:text-lg text-brand-red italic tracking-wider font-semibold">
                        #LongevityFitness &bull; #HealthyAging &bull; #TrainForLife
                    </p>
                    <h3 class="font-headline-lg text-xl sm:text-2xl text-white uppercase tracking-widest drop-shadow">
                        SIAP MEMULAI PERJALANAN ANDA?
                    </h3>
                    <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto">
                        Hubungi untuk konsultasi awal mengenai kondisi fisik, tujuan latihan, dan program yang sesuai untuk Anda di WARGYM Mojosongo.
                    </p>
                    <div class="pt-2">
                        <a 
                            href="https://wa.me/6289508366293?text=Halo%20Coach%20Ghion%20WARGYM%2C%20saya%20tertarik%20untuk%20konsultasi%20program%20Longevity%20Personal%20Trainer."
                            target="_blank"
                            class="btn-primary inline-flex items-center gap-2 py-3 px-8 text-xs sm:text-sm tracking-widest rounded-lg shadow-lg"
                        >
                            <span class="material-symbols-outlined text-[18px]">chat</span>
                            KONSULTASI DENGAN COACH GHION
                        </a>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 z-20 bg-[#161616]/95 backdrop-blur-md px-6 py-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs text-on-surface-variant font-label-caps">
                    WARGYM MOJOSONGO &bull; PERSONAL TRAINER
                </span>
                <button 
                    @click="closeModal" 
                    class="px-5 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-xs uppercase font-headline-md tracking-wider transition-colors"
                >
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- POPUP MODAL: PROFIL COACH FANNY            -->
    <!-- ========================================== -->
    <div 
        x-show="activeModal === 'fanny'" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-6 bg-black/85 backdrop-blur-md"
        @click.self="closeModal"
        style="display: none;"
    >
        <div class="relative w-full max-w-3xl max-h-[92vh] bg-[#141414] border border-white/15 rounded-2xl shadow-[0_0_60px_rgba(255,85,64,0.18)] flex flex-col overflow-hidden text-left">
            
            <!-- Modal Header -->
            <div class="sticky top-0 z-20 bg-[#161616]/95 backdrop-blur-md px-6 py-4 border-b border-white/10 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-headline-md text-xl sm:text-2xl text-white uppercase tracking-wider m-0">
                            FANNY
                        </h3>
                        <span class="px-2 py-0.5 bg-brand-red text-black text-[10px] font-bold rounded uppercase">
                            WOMEN PT
                        </span>
                    </div>
                    <p class="text-xs text-brand-red font-medium">
                        Beginner Friendly Women Fitness
                    </p>
                </div>

                <button 
                    @click="closeModal" 
                    class="p-2 rounded-full bg-white/5 hover:bg-brand-red hover:text-black text-white/70 transition-colors flex items-center justify-center"
                    title="Tutup (Esc)"
                >
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-8 text-on-surface">
                
                <!-- Tagline Badge -->
                <div class="bg-white/5 border border-white/10 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="font-label-caps text-brand-red text-[10px] uppercase tracking-widest block mb-1">PROFIL PELATIH GYM</span>
                        <h4 class="font-headline-md text-white text-base sm:text-lg uppercase">
                            COACH FANNY &bull; WOMEN PT
                        </h4>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(['Beginner Friendly', 'Women Fitness', 'Strength Training', 'Body Composition'] as $tag)
                            <span class="px-2.5 py-1 bg-brand-red/10 border border-brand-red/30 text-brand-red text-xs font-semibold rounded-full">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- 1. Perjalanan & Motivasi Pribadi -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">person</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            PERJALANAN & MOTIVASI PRIBADI
                        </h4>
                    </div>

                    <!-- Highlight Transformasi Fisik -->
                    <div class="bg-brand-red/10 border border-brand-red/30 p-4 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-red text-black font-headline-md text-lg flex items-center justify-center font-bold shrink-0">
                                FT
                            </div>
                            <div>
                                <span class="font-label-caps text-brand-red text-[10px] uppercase tracking-wider block">TRANSFORMASI BERAT BADAN</span>
                                <div class="font-headline-md text-base sm:text-lg text-white flex items-center gap-2">
                                    <span class="line-through text-white/50">53 KG</span>
                                    <span class="text-brand-red font-bold">&rarr;</span>
                                    <span class="text-brand-red font-bold">42 KG</span>
                                    <span class="text-xs text-white/60 font-body-md font-normal px-2 py-0.5 bg-white/10 rounded">TB 148 CM</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-white/80 italic bg-black/40 px-3 py-1.5 rounded-lg border border-white/5 self-start sm:self-center">
                            Transformasi Nyata &bull; Disiplin &bull; Gaya Hidup Sehat
                        </span>
                    </div>

                    <div class="space-y-3 font-body-md text-sm sm:text-base leading-relaxed text-on-surface-variant">
                        <p>
                            Saya pernah berada di titik ketika berat badan saya 53 kg dengan tinggi badan 148 cm. Kini, berat badan saya berada di angka 42 kg.
                        </p>
                        <p>
                            Perjalanan ini mengajarkan saya bahwa perubahan fisik bukan hanya tentang angka di timbangan, tetapi juga tentang proses mengenal tubuh, membangun disiplin, dan menerapkan gaya hidup yang lebih sehat.
                        </p>
                        <p>
                            Dari pengalaman pribadi tersebut, saya ingin membantu lebih banyak perempuan memulai perjalanan fitness mereka. Saya percaya setiap perempuan memiliki kondisi, kemampuan, dan tujuan yang berbeda. Karena itu, latihan seharusnya tidak menjadi ajang membandingkan diri, melainkan proses untuk menjadi lebih kuat dan sehat.
                        </p>
                    </div>
                </div>

                <!-- 2. Quote Filosofi -->
                <div class="bg-gradient-to-br from-[#1d1414] to-[#121212] border border-brand-red/30 p-5 sm:p-6 rounded-xl space-y-3">
                    <div class="flex items-center gap-2 text-brand-red">
                        <span class="material-symbols-outlined text-[22px]">format_quote</span>
                        <span class="font-label-caps text-xs uppercase tracking-widest text-brand-red font-bold">FILOSOFI SAYA</span>
                    </div>
                    <blockquote class="p-3.5 bg-brand-red/10 border-l-4 border-brand-red rounded-r-lg">
                        <p class="font-headline-md text-base sm:text-lg text-white italic tracking-wide">
                            "Bagi saya perempuan tidak harus menjadi kurus untuk menjadi sehat dan kuat."
                        </p>
                    </blockquote>
                </div>

                <!-- 3. Pendekatan Latihan -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">sports_gymnastics</span>
                        <h4 class="font-headline-md text-lg text-white uppercase tracking-wider">
                            PENDEKATAN LATIHAN
                        </h4>
                    </div>
                    <p class="font-body-md text-sm sm:text-base leading-relaxed text-on-surface-variant">
                        Saya membantu perempuan memulai perjalanan fitness/strength training dengan pendekatan yang realistis, mulai dari mengenal gerakan dasar, meningkatkan kekuatan otot, memperbaiki kebugaran, hingga membangun komposisi tubuh yang lebih baik.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                        @foreach([
                            'Mengenal Gerakan Dasar',
                            'Meningkatkan Kekuatan Otot',
                            'Memperbaiki Kebugaran Fisik',
                            'Membangun Komposisi Tubuh Lebih Baik',
                            'Pendekatan Realistis & Bertahap',
                            'Latihan Ramah Pemula & Bebas Intimidasi'
                        ] as $point)
                            <div class="flex items-center gap-2.5 p-2.5 bg-white/5 rounded-lg border border-white/5">
                                <span class="text-brand-red font-bold">✓</span>
                                <span class="text-xs sm:text-sm text-white/90">{{ $point }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Slogan & CTA Banner -->
                <div class="bg-gradient-to-r from-brand-red/20 via-black to-brand-red/20 border border-brand-red/40 p-6 rounded-2xl text-center space-y-3">
                    <p class="font-headline-md text-base sm:text-lg text-brand-red italic tracking-wider font-semibold">
                        #Train for Health, Build for Life
                    </p>
                    <h3 class="font-headline-lg text-xl sm:text-2xl text-white uppercase tracking-widest drop-shadow">
                        MULAI PERJALANAN FITNESSMU
                    </h3>
                    <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto">
                        Siap mendampingi Anda latihan dengan aman, nyaman, dan terukur di WARGYM Mojosongo.
                    </p>
                    <div class="pt-2">
                        <a 
                            href="https://wa.me/6289508366293?text=Halo%20Coach%20Fanny%20WARGYM%2C%20saya%20tertarik%20untuk%20konsultasi%20program%20Women%20Personal%20Trainer."
                            target="_blank"
                            class="btn-primary inline-flex items-center gap-2 py-3 px-8 text-xs sm:text-sm tracking-widest rounded-lg shadow-lg"
                        >
                            <span class="material-symbols-outlined text-[18px]">chat</span>
                            KONSULTASI DENGAN COACH FANNY
                        </a>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 z-20 bg-[#161616]/95 backdrop-blur-md px-6 py-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs text-on-surface-variant font-label-caps">
                    WARGYM MOJOSONGO &bull; WOMEN PERSONAL TRAINER
                </span>
                <button 
                    @click="closeModal" 
                    class="px-5 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-xs uppercase font-headline-md tracking-wider transition-colors"
                >
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- POPUP MODAL: PROFIL COACH IZAA             -->
    <!-- ========================================== -->
    <div 
        x-show="activeModal === 'izaa'" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-6 bg-black/85 backdrop-blur-md"
        @click.self="closeModal"
        style="display: none;"
    >
        <div class="relative w-full max-w-3xl max-h-[92vh] bg-[#141414] border border-white/15 rounded-2xl shadow-[0_0_60px_rgba(255,85,64,0.18)] flex flex-col overflow-hidden text-left">
            
            <!-- Modal Header -->
            <div class="sticky top-0 z-20 bg-[#161616]/95 backdrop-blur-md px-6 py-4 border-b border-white/10 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-headline-md text-xl sm:text-2xl text-white uppercase tracking-wider m-0">
                            KAK IZAA
                        </h3>
                        <span class="px-2 py-0.5 bg-brand-red text-black text-[10px] font-bold rounded uppercase">
                            PERSONAL TRAINER
                        </span>
                    </div>
                    <p class="text-xs text-brand-red font-medium">
                        Personal Trainer / Fitness Coach
                    </p>
                </div>

                <button 
                    @click="closeModal" 
                    class="p-2 rounded-full bg-white/5 hover:bg-brand-red hover:text-black text-white/70 transition-colors flex items-center justify-center"
                    title="Tutup (Esc)"
                >
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-8 text-on-surface">
                
                <!-- Tagline Badge -->
                <div class="bg-white/5 border border-white/10 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="font-label-caps text-brand-red text-[10px] uppercase tracking-widest block mb-1">PROFIL PELATIH GYM</span>
                        <h4 class="font-headline-md text-white text-base sm:text-lg uppercase">
                            KAK IZAA &bull; PERSONAL TRAINER
                        </h4>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(['Weight Loss', 'Body Shaping', 'Healthy Lifestyle', 'Fitness'] as $tag)
                            <span class="px-2.5 py-1 bg-brand-red/10 border border-brand-red/30 text-brand-red text-xs font-semibold rounded-full">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- 1. TENTANG SAYA -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">person</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            TENTANG SAYA
                        </h4>
                    </div>
                    <div class="space-y-3 font-body-md text-sm sm:text-base leading-relaxed text-on-surface-variant">
                        <p>
                            Saya adalah seorang Personal Trainer yang memiliki minat dalam dunia olahraga dan kebugaran. Saya ingin membantu orang lain memiliki tubuh yang lebih sehat, bugar, dan percaya diri melalui latihan yang sesuai dengan kemampuan masing-masing.
                        </p>
                        <p>
                            Bagi saya, fitness bukan hanya tentang memiliki bentuk tubuh yang ideal, tetapi juga tentang menjaga kesehatan, meningkatkan kekuatan, dan membangun kebiasaan hidup sehat.
                        </p>
                    </div>
                </div>

                <!-- 2. SPESIALISASI UTAMA -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">fitness_center</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            SPESIALISASI UTAMA
                        </h4>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white/5 border border-white/10 p-4 rounded-xl space-y-1.5 hover:border-brand-red/50 transition-colors">
                            <h5 class="font-headline-md text-base text-white uppercase flex items-center gap-2">
                                <span>⚖️</span> Weight Loss (Menurunkan Berat Badan)
                            </h5>
                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Membantu mengurangi berat badan dan lemak tubuh melalui latihan yang teratur serta pola hidup sehat.
                            </p>
                        </div>
                        <div class="bg-white/5 border border-white/10 p-4 rounded-xl space-y-1.5 hover:border-brand-red/50 transition-colors">
                            <h5 class="font-headline-md text-base text-white uppercase flex items-center gap-2">
                                <span>🔥</span> Body Shaping (Membentuk Tubuh)
                            </h5>
                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Membantu membentuk tubuh agar lebih kencang, kuat, dan proporsional sesuai dengan tujuan masing-masing.
                            </p>
                        </div>
                        <div class="bg-white/5 border border-white/10 p-4 rounded-xl space-y-1.5 hover:border-brand-red/50 transition-colors">
                            <h5 class="font-headline-md text-base text-white uppercase flex items-center gap-2">
                                <span>💪</span> Fitness & Strength Training
                            </h5>
                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Membantu meningkatkan kekuatan otot, stamina, dan kebugaran tubuh melalui latihan yang dilakukan secara bertahap.
                            </p>
                        </div>
                        <div class="bg-white/5 border border-white/10 p-4 rounded-xl space-y-1.5 hover:border-brand-red/50 transition-colors">
                            <h5 class="font-headline-md text-base text-white uppercase flex items-center gap-2">
                                <span>🌿</span> Healthy Lifestyle (Pola Hidup Sehat)
                            </h5>
                            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Membantu membangun kebiasaan hidup sehat melalui olahraga rutin, istirahat cukup, dan pola makan yang seimbang.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3. PENDEKATAN LATIHAN -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                        <span class="material-symbols-outlined text-brand-red text-[20px]">tune</span>
                        <h4 class="font-headline-md text-xl text-white uppercase tracking-wider">
                            PENDEKATAN LATIHAN
                        </h4>
                    </div>
                    <p class="font-body-md text-sm sm:text-base leading-relaxed text-on-surface-variant">
                        Saya percaya bahwa setiap orang memiliki kondisi tubuh dan kemampuan yang berbeda. Oleh karena itu, latihan perlu disesuaikan dengan kebutuhan dan tujuan masing-masing. Saya mengutamakan latihan yang aman, nyaman, bertahap, dan konsisten agar hasilnya dapat dirasakan dalam jangka panjang.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                        @foreach([
                            'Latihan aman & bebas cedera',
                            'Nyaman & sesuai kemampuan individu',
                            'Progres bertahap & terukur',
                            'Konsisten untuk jangka panjang'
                        ] as $point)
                            <div class="flex items-center gap-2.5 p-2.5 bg-white/5 rounded-lg border border-white/5">
                                <span class="text-brand-red font-bold">✓</span>
                                <span class="text-xs sm:text-sm text-white/90">{{ $point }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 4. FILOSOFI LATIHAN -->
                <div class="bg-gradient-to-br from-[#1d1414] to-[#121212] border border-brand-red/30 p-5 sm:p-6 rounded-xl space-y-3">
                    <div class="flex items-center gap-2 text-brand-red">
                        <span class="material-symbols-outlined text-[22px]">psychology</span>
                        <span class="font-label-caps text-xs uppercase tracking-widest text-brand-red font-bold">FILOSOFI LATIHAN</span>
                    </div>
                    <blockquote class="p-3.5 bg-brand-red/10 border-l-4 border-brand-red rounded-r-lg">
                        <p class="font-headline-md text-base sm:text-lg text-white italic tracking-wide">
                            "Olahraga bukan hanya tentang memiliki tubuh yang ideal, tetapi juga tentang mencintai diri sendiri dengan menjaga kesehatan dan kebugaran tubuh."
                        </p>
                    </blockquote>
                    <p class="font-body-md text-xs sm:text-sm text-white/80 leading-relaxed pt-1">
                        Saya percaya bahwa perubahan kecil yang dilakukan secara konsisten dapat memberikan hasil yang baik bagi tubuh dan kesehatan.
                    </p>
                </div>

                <!-- 5. VISI -->
                <div class="bg-white/5 border border-white/10 p-5 sm:p-6 rounded-xl space-y-3">
                    <span class="font-label-caps text-brand-red text-xs uppercase tracking-widest block">VISI COACH</span>
                    <h4 class="font-headline-md text-base sm:text-lg text-white uppercase tracking-wide">
                        "Membantu orang lain menjadi lebih sehat, aktif, kuat, dan percaya diri melalui olahraga serta kebiasaan hidup sehat."
                    </h4>
                    <div class="flex flex-wrap gap-2 pt-2">
                        @foreach(['Stay Healthy.', 'Stay Strong.', 'Be Confident.'] as $motto)
                            <span class="px-3 py-1 bg-white/10 text-white font-headline-md text-xs sm:text-sm tracking-wider uppercase rounded">
                                {{ $motto }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- 6. SLOGAN & CTA BANNER -->
                <div class="bg-gradient-to-r from-brand-red/20 via-black to-brand-red/20 border border-brand-red/40 p-6 rounded-2xl text-center space-y-3">
                    <p class="font-headline-md text-base sm:text-lg text-brand-red italic tracking-wider font-semibold">
                        🔥 STRONG BODY, HEALTHY LIFE.
                    </p>
                    <h3 class="font-headline-lg text-xl sm:text-2xl text-white uppercase tracking-widest drop-shadow">
                        MULAI PROGRAM LATIHANMU
                    </h3>
                    <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto">
                        Siap membantu dan mendampingi Anda latihan yang aman, nyaman, dan bertahap di WARGYM Mojosongo.
                    </p>
                    <div class="pt-2">
                        <a 
                            href="https://wa.me/6289508366293?text=Halo%20Kak%20Izaa%20WARGYM%2C%20saya%20tertarik%20untuk%20konsultasi%20program%20latihan%20Personal%20Trainer."
                            target="_blank"
                            class="btn-primary inline-flex items-center gap-2 py-3 px-8 text-xs sm:text-sm tracking-widest rounded-lg shadow-lg"
                        >
                            <span class="material-symbols-outlined text-[18px]">chat</span>
                            KONSULTASI DENGAN KAK IZAA
                        </a>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 z-20 bg-[#161616]/95 backdrop-blur-md px-6 py-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs text-on-surface-variant font-label-caps">
                    WARGYM MOJOSONGO &bull; PERSONAL TRAINER
                </span>
                <button 
                    @click="closeModal" 
                    class="px-5 py-2 rounded bg-white/10 hover:bg-white/20 text-white text-xs uppercase font-headline-md tracking-wider transition-colors"
                >
                    Tutup
                </button>
            </div>

        </div>
    </div>

</main>

@endsection

@section('styles')
<style>
/* Custom smooth scrollbar for popup */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #181818;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #333;
    border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #ff5540;
}
</style>
@endsection
