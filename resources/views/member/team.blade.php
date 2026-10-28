@extends('layouts.member-front')
@section('title', 'Tentang Kami | WARGYM Jombang')
@php $activeNav = 'team'; @endphp

@section('content')

<main class="pt-20">
    <section class="py-16 md:py-24 bg-[#131313] relative metal-grid min-h-[calc(100vh-250px)]">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16 flex flex-col pt-12">
            <h1 class="font-headline-lg text-4xl sm:text-5xl md:text-6xl text-center uppercase italic tracking-wider text-white mb-16 drop-shadow-lg">
                CERITA <span class="text-brand-red">KAMI</span>
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start max-w-7xl mx-auto">
                <!-- Text Content -->
                <div class="glass-panel p-6 sm:p-10 md:p-12 rounded-lg border border-white/10 lg:col-span-7 xl:col-span-7 order-2 lg:order-1">
                    <!-- Header Info -->
                    <div class="border-b border-white/10 pb-6 mb-8">
                        <div class="inline-block bg-brand-red/10 border border-brand-red/30 text-brand-red font-label-caps text-xs font-bold uppercase tracking-widest px-3 py-1 rounded mb-3">
                            WARGYM MOJOSONGO
                        </div>
                        <h2 class="font-headline-md text-2xl sm:text-3xl text-white uppercase italic tracking-wide mb-3">
                            WARUNG GYM &mdash; <span class="text-brand-red">Sehat, Kuat, Bertumbuh Bersama</span>
                        </h2>
                        <p class="flex items-start gap-2 text-on-surface-variant/80 text-sm">
                            <span class="material-symbols-outlined text-brand-red text-base shrink-0 mt-0.5">location_on</span>
                            <span>Jl. Mojosongo, Diwek, Balong Besuk, Kecamatan Diwek, Kabupaten Jombang, Jawa Timur 61471</span>
                        </p>
                    </div>

                    <!-- Tentang WARGYM -->
                    <div class="mb-10">
                        <h3 class="font-headline-md text-xl text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-red"></span> TENTANG WARGYM
                        </h3>
                        <p class="font-body-md text-on-surface-variant text-base leading-relaxed mb-4">
                            WARGYM (WARUNG GYM) adalah tempat fitness berbasis komunitas yang hadir untuk membuat olahraga dan hidup sehat terasa lebih dekat, nyaman, terjangkau, dan tidak mengintimidasi.
                        </p>
                        <p class="font-body-md text-on-surface-variant text-base leading-relaxed mb-4">
                            Berada di kawasan Mojosongo, Balongbesuk, Jombang, WARGYM menggabungkan konsep gym, fitness, personal training, dan tempat berkumpul dalam satu suasana yang santai dan kekeluargaan.
                        </p>
                        <p class="font-body-md text-white font-semibold text-base leading-relaxed mb-4">
                            WARGYM bukan sekadar tempat untuk mengangkat beban.
                        </p>
                        <p class="font-body-md text-on-surface-variant text-base leading-relaxed mb-4">
                            Kami ingin menciptakan sebuah lingkungan di mana siapa pun—baik pemula, pelajar, pekerja, maupun mereka yang sudah berpengalaman—dapat datang, berlatih, belajar, berkembang, dan menjadi bagian dari komunitas.
                        </p>
                        <p class="font-body-md text-brand-red text-base font-semibold leading-relaxed">
                            Karena kami percaya bahwa hidup sehat tidak harus mahal dan gym tidak harus terasa menakutkan.
                        </p>
                    </div>

                    <!-- Filosofi WARGYM -->
                    <div class="mb-10 bg-white/5 border border-white/10 p-6 rounded-lg">
                        <h3 class="font-headline-md text-xl text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-red"></span> FILOSOFI WARGYM
                        </h3>
                        <p class="font-body-md text-on-surface-variant text-base leading-relaxed mb-4">
                            Nama WAR GYM berasal dari semangat untuk menjadikan gym seperti warung:
                        </p>
                        <ul class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 mb-6 font-body-md text-xs sm:text-sm text-white">
                            <li class="bg-black/40 border border-white/10 px-3 py-2 rounded flex items-center gap-2">
                                <span class="text-brand-red font-bold">&bull;</span> Datang untuk latihan
                            </li>
                            <li class="bg-black/40 border border-white/10 px-3 py-2 rounded flex items-center gap-2">
                                <span class="text-brand-red font-bold">&bull;</span> Bertemu orang
                            </li>
                            <li class="bg-black/40 border border-white/10 px-3 py-2 rounded flex items-center gap-2">
                                <span class="text-brand-red font-bold">&bull;</span> Ngobrol
                            </li>
                            <li class="bg-black/40 border border-white/10 px-3 py-2 rounded flex items-center gap-2">
                                <span class="text-brand-red font-bold">&bull;</span> Ngopi
                            </li>
                            <li class="bg-black/40 border border-white/10 px-3 py-2 rounded flex items-center gap-2">
                                <span class="text-brand-red font-bold">&bull;</span> Bertukar pengalaman
                            </li>
                            <li class="bg-black/40 border border-white/10 px-3 py-2 rounded flex items-center gap-2">
                                <span class="text-brand-red font-bold">&bull;</span> Semangat baru
                            </li>
                        </ul>
                        <p class="font-body-md text-on-surface-variant text-base leading-relaxed mb-4">
                            Konsep inilah yang menjadi karakter WARGYM. Setelah selesai latihan, member tidak harus langsung pulang. Ada ruang untuk beristirahat, bercengkerama, dan membangun relasi dengan sesama anggota.
                        </p>
                        <div class="border-l-2 border-brand-red pl-4 py-1 text-white font-headline-md tracking-wider">
                            <p class="text-base uppercase">Gym menjadi tempat latihan.</p>
                            <p class="text-base uppercase text-brand-red">Komunitas menjadi tempat bertumbuh.</p>
                        </div>
                    </div>

                    <!-- Why WARGYM? -->
                    <div class="mb-10">
                        <h3 class="font-headline-md text-xl text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-red"></span> WHY WARGYM?
                        </h3>
                        
                        <!-- 1. Semua Level -->
                        <div class="mb-6 bg-white/5 border border-white/10 p-5 rounded-lg">
                            <h4 class="font-headline-md text-lg text-white mb-2 flex items-center gap-2">
                                <span>💪</span> GYM UNTUK SEMUA LEVEL
                            </h4>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-2">
                                WARGYM terbuka untuk siapa saja. Mulai dari orang yang belum pernah masuk gym sama sekali, hingga mereka yang sudah memiliki pengalaman latihan dan ingin terus meningkatkan kemampuan.
                            </p>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-1">
                                Tidak perlu merasa minder karena belum tahu cara menggunakan alat.
                            </p>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-2">
                                Tidak perlu merasa takut karena tubuh belum ideal.
                            </p>
                            <p class="font-body-md text-brand-red font-semibold text-sm leading-relaxed">
                                Semua orang memulai dari titik yang berbeda.
                            </p>
                        </div>

                        <!-- 2. Komunitas Welcoming -->
                        <div class="mb-6 bg-white/5 border border-white/10 p-5 rounded-lg">
                            <h4 class="font-headline-md text-lg text-white mb-2 flex items-center gap-2">
                                <span>🤝</span> KOMUNITAS YANG WELCOMING
                            </h4>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-2">
                                Salah satu kekuatan WARGYM adalah suasana komunitasnya. Berdasarkan berbagai ulasan member, WARGYM dikenal memiliki suasana yang ramah, friendly, nyaman, dan kekeluargaan.
                            </p>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-3">
                                Bagi kami, suasana seperti ini penting karena banyak orang sebenarnya ingin mulai olahraga tetapi merasa takut dinilai atau tidak tahu harus mulai dari mana.
                            </p>
                            <div class="bg-brand-red/10 border border-brand-red/20 rounded p-3 text-white text-sm font-semibold">
                                Di WARGYM: <span class="text-brand-red">Datang sendiri bukan berarti sendirian.</span>
                            </div>
                        </div>

                        <!-- 3. Personal Trainer -->
                        <div class="bg-white/5 border border-white/10 p-5 rounded-lg">
                            <h4 class="font-headline-md text-lg text-white mb-2 flex items-center gap-2">
                                <span>🏋️‍♂️</span> PERSONAL TRAINER
                            </h4>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-3">
                                WARGYM menyediakan Personal Trainer yang dapat membantu member, khususnya pemula, memahami teknik latihan dan menjalankan latihan dengan lebih terarah.
                            </p>
                            <p class="font-body-md text-white text-sm font-semibold mb-2">
                                Pendampingan PT dapat membantu member memahami:
                            </p>
                            <div class="grid grid-cols-2 gap-2 mb-3 text-xs text-on-surface-variant">
                                <span class="bg-black/30 p-2 rounded border border-white/5">&bull; Teknik dasar latihan</span>
                                <span class="bg-black/30 p-2 rounded border border-white/5">&bull; Penggunaan alat</span>
                                <span class="bg-black/30 p-2 rounded border border-white/5">&bull; Latihan kekuatan</span>
                                <span class="bg-black/30 p-2 rounded border border-white/5">&bull; Penyusunan program latihan</span>
                                <span class="bg-black/30 p-2 rounded border border-white/5">&bull; Progress latihan</span>
                                <span class="bg-black/30 p-2 rounded border border-white/5">&bull; Fat loss</span>
                                <span class="bg-black/30 p-2 rounded border border-white/5">&bull; Muscle building</span>
                                <span class="bg-black/30 p-2 rounded border border-white/5">&bull; Peningkatan kebugaran</span>
                            </div>
                            <p class="font-body-md text-on-surface-variant text-xs italic">
                                Tujuannya bukan sekadar membuat seseorang berlatih lebih keras, tetapi membantu mereka berlatih dengan lebih tepat.
                            </p>
                        </div>
                    </div>

                    <!-- Fitness Untuk Kehidupan Jangka Panjang -->
                    <div class="mb-10">
                        <h3 class="font-headline-md text-xl text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-red"></span> FITNESS UNTUK KEHIDUPAN JANGKA PANJANG
                        </h3>
                        <p class="font-body-md text-on-surface-variant text-base leading-relaxed mb-4">
                            WARGYM memiliki pandangan bahwa fitness bukan hanya tentang badan bagus. Latihan yang dilakukan secara konsisten dapat menjadi bagian dari gaya hidup yang mendukung:
                        </p>
                        <div class="bg-black/50 border border-brand-red/30 p-4 rounded-lg text-center mb-4">
                            <span class="font-label-caps text-xs sm:text-sm text-brand-red font-bold tracking-wider">
                                Kekuatan &rarr; Kebugaran &rarr; Kesehatan &rarr; Kualitas Hidup &rarr; Longevity
                            </span>
                        </div>
                        <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-4">
                            Kami ingin mengajak masyarakat untuk tidak hanya mengejar perubahan fisik dalam hitungan minggu atau bulan, tetapi membangun kebiasaan yang dapat dipertahankan selama bertahun-tahun.
                        </p>
                        <div class="bg-white/5 border border-white/10 p-4 rounded text-sm text-on-surface-variant">
                            <p class="mb-1">Karena tujuan akhirnya bukan hanya:</p>
                            <p class="text-white font-semibold italic mb-2">"Bagaimana tubuh saya terlihat?"</p>
                            <p class="mb-1">tetapi juga:</p>
                            <p class="text-brand-red font-semibold italic">"Seberapa baik tubuh saya dapat menjalani kehidupan?"</p>
                        </div>
                    </div>

                    <!-- Program dan Tujuan Latihan -->
                    <div class="mb-10">
                        <h3 class="font-headline-md text-xl text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-red"></span> PROGRAM DAN TUJUAN LATIHAN
                        </h3>
                        <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-4">
                            Di WARGYM, setiap member memiliki alasan dan tujuannya masing-masing. Beberapa fokus latihan yang umum dijalankan meliputi:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-white/5 border border-white/10 p-3.5 rounded">
                                <h5 class="text-white font-bold text-sm mb-1 flex items-center gap-2"><span>⚖️</span> FAT LOSS</h5>
                                <p class="text-xs text-on-surface-variant leading-relaxed">Membantu menurunkan lemak tubuh melalui kombinasi latihan beban, kardio, dan pola hidup yang lebih aktif.</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 p-3.5 rounded">
                                <h5 class="text-white font-bold text-sm mb-1 flex items-center gap-2"><span>💪</span> MUSCLE BUILDING</h5>
                                <p class="text-xs text-on-surface-variant leading-relaxed">Membantu membangun dan mempertahankan massa otot agar tubuh lebih padat, kuat, dan proporsional.</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 p-3.5 rounded">
                                <h5 class="text-white font-bold text-sm mb-1 flex items-center gap-2"><span>⚡</span> STRENGTH & FITNESS</h5>
                                <p class="text-xs text-on-surface-variant leading-relaxed">Membangun tubuh yang lebih kuat untuk mendukung aktivitas sehari-hari maupun performa latihan.</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 p-3.5 rounded">
                                <h5 class="text-white font-bold text-sm mb-1 flex items-center gap-2"><span>🧬</span> LONGEVITY</h5>
                                <p class="text-xs text-on-surface-variant leading-relaxed">Menjadikan latihan sebagai investasi jangka panjang untuk mempertahankan kekuatan, massa otot, mobilitas, dan kemampuan tubuh seiring bertambahnya usia.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sehat Tidak Harus Mahal & Bersih Nyaman -->
                    <div class="mb-10 space-y-6">
                        <div class="bg-white/5 border border-white/10 p-5 rounded-lg">
                            <h3 class="font-headline-md text-xl text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brand-red"></span> SEHAT TIDAK HARUS MAHAL
                            </h3>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-3">
                                Salah satu hal yang banyak diapresiasi dalam ulasan member adalah value for money WARGYM. WARGYM berusaha menghadirkan fasilitas dan lingkungan latihan yang nyaman dengan harga yang tetap dapat dijangkau oleh masyarakat sekitar, termasuk pelajar.
                            </p>
                            <p class="font-headline-md text-brand-red text-base italic tracking-wide">
                                "Sehat tidak harus mahal."
                            </p>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mt-2">
                                Yang paling penting bukan seberapa mahal tempat kita berlatih, tetapi seberapa konsisten kita menjalani prosesnya.
                            </p>
                        </div>

                        <div class="bg-white/5 border border-white/10 p-5 rounded-lg">
                            <h3 class="font-headline-md text-xl text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brand-red"></span> BERSIH, NYAMAN, DAN TERUS BERKEMBANG
                            </h3>
                            <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-3">
                                Kebersihan dan kenyamanan menjadi bagian penting dari pengalaman latihan di WARGYM. Kami juga menyadari bahwa sebuah gym harus terus berkembang. Masukan dari member mengenai penambahan fasilitas, alat, maupun pengembangan ruang menjadi bagian dari perjalanan WARGYM untuk terus menjadi lebih baik.
                            </p>
                            <p class="font-body-md text-white font-semibold text-sm">
                                WARGYM tumbuh bersama membernya.
                            </p>
                        </div>
                    </div>

                    <!-- Lebih Dari Sekadar Gym -->
                    <div class="mb-10 bg-white/5 border border-white/10 p-6 rounded-lg">
                        <h3 class="font-headline-md text-xl text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-red"></span> LEBIH DARI SEKADAR GYM
                        </h3>
                        <p class="font-body-md text-on-surface-variant text-sm leading-relaxed mb-4">
                            Ada gym yang hanya menjadi tempat latihan. Kami ingin WARGYM menjadi lebih dari itu.
                        </p>
                        <ul class="space-y-2 text-xs sm:text-sm text-white/90 mb-4 font-body-md">
                            <li class="flex items-center gap-2"><span class="text-brand-red font-bold">&bull;</span> Tempat seseorang pertama kali belajar melakukan squat.</li>
                            <li class="flex items-center gap-2"><span class="text-brand-red font-bold">&bull;</span> Tempat seseorang mulai percaya diri mengangkat beban.</li>
                            <li class="flex items-center gap-2"><span class="text-brand-red font-bold">&bull;</span> Tempat seseorang berhasil menurunkan berat badan.</li>
                            <li class="flex items-center gap-2"><span class="text-brand-red font-bold">&bull;</span> Tempat seseorang membangun otot.</li>
                            <li class="flex items-center gap-2"><span class="text-brand-red font-bold">&bull;</span> Tempat seseorang menemukan teman baru.</li>
                            <li class="flex items-center gap-2"><span class="text-brand-red font-bold">&bull;</span> Tempat seseorang mulai peduli terhadap kesehatannya.</li>
                            <li class="flex items-center gap-2"><span class="text-brand-red font-bold">&bull;</span> Dan mungkin, tempat seseorang memulai perubahan terbesar dalam hidupnya.</li>
                        </ul>
                        <p class="font-headline-md text-brand-red text-base uppercase tracking-wider">
                            WARGYM adalah tempat untuk berproses.
                        </p>
                    </div>

                    <!-- Visi & Misi -->
                    <div class="mb-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-black/40 border border-white/10 p-5 rounded-lg">
                            <h4 class="font-headline-md text-lg text-brand-red uppercase tracking-wider mb-3">VISI</h4>
                            <p class="font-body-md text-on-surface-variant text-xs sm:text-sm leading-relaxed">
                                Menjadi pusat kebugaran berbasis komunitas yang membantu masyarakat membangun tubuh yang lebih kuat, sehat, bugar, dan aktif untuk kualitas hidup yang lebih baik dan lebih panjang.
                            </p>
                        </div>
                        <div class="bg-black/40 border border-white/10 p-5 rounded-lg">
                            <h4 class="font-headline-md text-lg text-brand-red uppercase tracking-wider mb-3">MISI</h4>
                            <ul class="space-y-2 text-[11px] sm:text-xs text-on-surface-variant leading-relaxed">
                                <li class="flex items-start gap-1.5"><span class="text-brand-red font-bold">&check;</span> Menyediakan tempat latihan yang nyaman dan welcoming bagi semua kalangan.</li>
                                <li class="flex items-start gap-1.5"><span class="text-brand-red font-bold">&check;</span> Membuat fitness lebih mudah diakses masyarakat dengan harga yang terjangkau.</li>
                                <li class="flex items-start gap-1.5"><span class="text-brand-red font-bold">&check;</span> Membantu pemula mendapatkan edukasi dan pendampingan latihan yang tepat.</li>
                                <li class="flex items-start gap-1.5"><span class="text-brand-red font-bold">&check;</span> Membangun komunitas fitness yang positif, ramah, dan saling mendukung.</li>
                                <li class="flex items-start gap-1.5"><span class="text-brand-red font-bold">&check;</span> Mendorong masyarakat menjadikan olahraga sebagai bagian dari gaya hidup.</li>
                                <li class="flex items-start gap-1.5"><span class="text-brand-red font-bold">&check;</span> Mengembangkan fasilitas dan layanan berdasarkan kebutuhan serta masukan member.</li>
                                <li class="flex items-start gap-1.5"><span class="text-brand-red font-bold">&check;</span> Mengedukasi masyarakat bahwa kesehatan dan kebugaran adalah investasi jangka panjang.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- WARGYM Community Closing -->
                    <div class="border-t border-white/10 pt-8 text-center space-y-4">
                        <h4 class="font-label-caps text-xs text-brand-red uppercase tracking-[0.3em] font-bold">WARGYM COMMUNITY</h4>
                        <div class="font-headline-lg text-2xl sm:text-3xl text-white uppercase italic tracking-wider">
                            TRAIN TOGETHER. GROW TOGETHER. LIVE STRONGER.
                        </div>
                        <div class="font-body-md text-on-surface-variant text-sm leading-relaxed max-w-xl mx-auto space-y-2">
                            <p>WARGYM bukan tentang siapa yang paling kuat. Bukan tentang siapa yang paling besar. Bukan tentang siapa yang paling cepat mendapatkan hasil.</p>
                            <p class="text-white font-semibold">WARGYM adalah tentang proses.</p>
                            <p>Hari ini mungkin baru belajar. Besok mungkin menjadi lebih kuat. Bulan depan mungkin tubuh mulai berubah.</p>
                            <p class="text-white">Dan beberapa tahun kemudian, kita mungkin menyadari bahwa keputusan sederhana untuk mulai berolahraga telah memberikan sesuatu yang jauh lebih berharga: <span class="text-brand-red font-bold">tubuh yang lebih kuat dan kehidupan yang lebih berkualitas.</span></p>
                        </div>
                        <div class="pt-4 border-t border-white/10">
                            <p class="font-headline-md text-lg text-white uppercase tracking-wider mb-1">WARGYM MOJOSONGO</p>
                            <p class="font-body-md text-xs text-on-surface-variant mb-1">Tempat latihan. Tempat berkumpul. Tempat bertumbuh.</p>
                            <p class="font-body-md text-xs text-white/90 italic mb-4">WARUNG GYM — karena hidup sehat akan terasa lebih menyenangkan ketika dijalani bersama.</p>
                            <span class="inline-block bg-brand-red text-black font-label-caps text-xs font-bold uppercase tracking-widest px-5 py-2 rounded shadow-lg shadow-brand-red/20">
                                START A BETTER LIFE.
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Image Content -->
                <div class="lg:col-span-5 xl:col-span-5 relative group lg:sticky lg:top-28 order-1 lg:order-2 space-y-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-brand-red/10 blur-3xl -z-10 group-hover:bg-brand-red/20 transition-colors duration-700"></div>
                        <img src="{{ asset('images/team2.jpg') }}" alt="Cerita Kami - Team WARGYM" class="w-full h-auto max-h-[500px] lg:max-h-[600px] object-cover rounded-xl border border-white/10 shadow-[0_0_50px_rgba(0,0,0,0.8)] transition-all duration-700 transform group-hover:scale-[1.02]">
                    </div>
                    <div class="glass-panel p-5 border border-white/10 rounded-xl text-center hidden lg:block">
                        <p class="font-headline-md text-lg text-white uppercase tracking-wider">WARGYM MOJOSONGO</p>
                        <p class="text-xs text-on-surface-variant mt-1">Sehat, Kuat, Bertumbuh Bersama</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
