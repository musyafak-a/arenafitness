@extends('layouts.member-front')
@section('title', 'Team Kita | WARGYM Jombang')
@php $activeNav = 'team'; @endphp

@section('content')

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

@endsection
