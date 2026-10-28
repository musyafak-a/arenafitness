@extends('layouts.member-front')
@section('title', 'Tutorial | WARGYM Jombang')
@php $activeNav = 'tutorial'; @endphp

@section('content')
<main class="pt-20">
    <section class="py-16 md:py-24 bg-[#131313] relative metal-grid min-h-[calc(100vh-250px)]">
        <div class="max-w-screen-2xl mx-auto px-6 md:px-16 flex flex-col items-center justify-center pt-12">
            <span class="font-label-caps text-brand-red text-xs uppercase tracking-[0.3em] block mb-4">[ COMING SOON ]</span>
            <h1 class="font-headline-lg text-4xl sm:text-5xl md:text-6xl text-center uppercase italic tracking-wider text-white mb-8 drop-shadow-lg">
                TUTORIAL <span class="text-brand-red">GYM</span>
            </h1>
            <p class="font-body-md text-on-surface-variant text-lg text-center max-w-xl mb-12">
                Konten tutorial dan panduan latihan sedang dalam tahap pengembangan. Nantikan konten menarik dari tim WARGYM!
            </p>
            <a href="{{ route('member.company-profile') }}" class="btn-primary text-sm">
                <span class="material-symbols-outlined text-[18px] mr-2">arrow_back</span>
                Kembali ke Beranda
            </a>
        </div>
    </section>
</main>
@endsection
