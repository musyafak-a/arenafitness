@extends('layouts.member-front')
@section('title', 'Personal Trainer | WARGYM Jombang')
@php $activeNav = 'personal-trainer'; @endphp

@section('content')

<main class="pt-20">
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
            <div class="mb-12">
                <h2 class="font-headline-md text-3xl md:text-5xl mb-4 text-white uppercase italic tracking-wider">Temukan PT <span class="text-brand-red">sesuai kebutuhanmu!</span></h2>
                <p class="font-body-md text-on-surface-variant text-lg md:text-xl">Capai tujuan fitness lebih cepat dengan bimbingan personal trainer bersertifikasi internasional</p>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-6">
                @foreach([
                    ['HAPPY', 'Weight Management, Fat Loss, Muscle Building, Nutrition, Functional Training, Freestyle Movement, Postural Alignment.', 'https://images.unsplash.com/photo-1594381898411-846e7d193883?w=300&h=300&fit=crop'],
                    ['DIKA', 'Weight Management, Muscle Building, Fat Loss, Endurance, Mobility and Agility, Boxing for Fitness, Postural Alignment, Strength, Functional Training, Nutrition Program.', 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=300&h=300&fit=crop'],
                    ['IMAM', 'Weight Management, Fat Loss, Muscle Building, Nutrition, Functional Training, Freestyle Movement, Postural Alignment, Strength, Power.', 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=300&h=300&fit=crop'],
                    ['JASEP', 'Weight Management, Muscle Building, Rehab, Postural Alignment, Freestyle Movement.', 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=300&h=300&fit=crop'],
                    ['INDAH', 'Weight Management, Muscle Building, Core Building, Endurance, Mobility and Agility, Freestyle Movement, Postural Alignment, Strength, Power, Sport Performance.', 'https://images.unsplash.com/photo-1607962837359-5e7e8f566408?w=300&h=300&fit=crop'],
                    ['ABDUL', 'Weight Management, Muscle Building, Fat Loss, Endurance, Mobility and Agility, Boxing for Fitness, Postural Alignment, Strength, Functional Training, Nutrition Program.', 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=300&h=300&fit=crop']
                ] as [$name, $desc, $img])
                <div class="glass-panel p-2 sm:p-5 flex flex-col border border-white/10 hover:border-brand-red/60 transition-all duration-300 group">
                    <div class="overflow-hidden mb-3 sm:mb-5 border border-white/5 bg-white/5 relative rounded-sm">
                        <img src="{{ $img }}" alt="{{ $name }}" class="w-full h-24 sm:h-48 object-cover group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                        <h3 class="absolute bottom-1 sm:bottom-3 left-2 sm:left-4 font-headline-md text-sm sm:text-2xl text-white uppercase tracking-wider m-0">{{ $name }}</h3>
                    </div>
                    
                    <p class="font-body-md text-[9px] sm:text-[13px] leading-snug sm:leading-relaxed text-on-surface-variant flex-grow mb-3 sm:mb-6 line-clamp-4 sm:line-clamp-none">{{ $desc }}</p>
                    
                    <div class="pt-2 sm:pt-4 border-t border-white/10 mt-auto flex items-center gap-1 sm:gap-2">
                        <span class="material-symbols-outlined text-brand-red text-[12px] sm:text-[16px]">location_on</span>
                        <span class="font-label-caps text-[7px] sm:text-[10px] font-bold text-white uppercase tracking-widest leading-none">WARGYM<span class="hidden sm:inline"> JOMBANG</span></span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</main>

@endsection
