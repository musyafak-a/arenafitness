@extends('layouts.member-front')
@section('title', 'Fasilitas | WARGYM Jombang')
@php 
$activeNav = 'fasilitas'; 

$facilities = [
    ['name' => 'Treadmill', 'qty' => '3 Unit', 'category' => 'cardio', 'img' => 'https://images.unsplash.com/photo-1576678927484-cc907957088c?w=500&h=400&fit=crop'],
    ['name' => 'Samsak / Sandsack', 'qty' => '1 Unit', 'category' => 'cardio', 'img' => 'https://images.unsplash.com/photo-1555597673-b21d5c935865?w=500&h=400&fit=crop'],
    
    ['name' => 'Smith Machine', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1534258936925-c58bed479fcb?w=500&h=400&fit=crop'],
    ['name' => 'Chest Press', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=500&h=400&fit=crop'],
    ['name' => 'Leg Curl + Leg Ext + Triceps', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=500&h=400&fit=crop'],
    ['name' => 'LatPullDown + Seated Row', 'qty' => '2 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1549060279-7e168fcee0c2?w=500&h=400&fit=crop'],
    ['name' => 'Flat Bench Press', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=500&h=400&fit=crop'],
    ['name' => 'Adjustable Bench', 'qty' => '3 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1584863231364-2edc166de576?w=500&h=400&fit=crop'],
    ['name' => 'Abductor + Adductor', 'qty' => '2 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1593079831268-3381b0db4a77?w=500&h=400&fit=crop'],
    ['name' => 'Peck Deck / Butterfly', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?w=500&h=400&fit=crop'],
    ['name' => 'Leg Press + Dips', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=500&h=400&fit=crop'],
    ['name' => 'Alat Sit Up', 'qty' => '1 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=500&h=400&fit=crop'],
    ['name' => 'Dumbbell Set', 'qty' => '1-4kg & 2.5-20kg', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1586401100295-7a8096fd231a?w=500&h=400&fit=crop'],
    ['name' => 'Tempat Pull Up', 'qty' => '2 Unit', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1598971639058-fab3c3109a00?w=500&h=400&fit=crop'],
    ['name' => 'Bumper Plate (Deadlift)', 'qty' => '5-20kg', 'category' => 'strength', 'img' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=500&h=400&fit=crop'],
    
    ['name' => 'Kamar Mandi', 'qty' => '2 Ruang', 'category' => 'lainnya', 'img' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=500&h=400&fit=crop'],
    ['name' => 'Toilet', 'qty' => '2 Ruang', 'category' => 'lainnya', 'img' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?w=500&h=400&fit=crop'],
    ['name' => 'Warung', 'qty' => '1 Unit', 'category' => 'lainnya', 'img' => 'https://images.unsplash.com/photo-1521790797524-b2497295b8a0?w=500&h=400&fit=crop'],
];
@endphp

@section('content')
<main class="pt-20">
    <section class="py-16 md:py-24 bg-[#131313] relative metal-grid min-h-screen" x-data="{ currentCategory: 'all', searchQuery: '' }">
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
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-16 max-w-screen-xl mx-auto w-full">
                <div class="flex flex-nowrap sm:flex-wrap justify-center sm:justify-start gap-1 sm:gap-3 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0">
                    <button @click="currentCategory = 'all'" :class="{ 'active': currentCategory === 'all' }" class="filter-btn shrink-0">Semua</button>
                    <button @click="currentCategory = 'cardio'" :class="{ 'active': currentCategory === 'cardio' }" class="filter-btn shrink-0">Cardio</button>
                    <button @click="currentCategory = 'strength'" :class="{ 'active': currentCategory === 'strength' }" class="filter-btn shrink-0">Strength</button>
                    <button @click="currentCategory = 'lainnya'" :class="{ 'active': currentCategory === 'lainnya' }" class="filter-btn shrink-0">Lainnya</button>
                </div>
                <div class="relative w-full sm:w-48 shrink-0">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[18px]">search</span>
                    <input type="text" x-model="searchQuery" placeholder="CARI..." class="w-full bg-[#1f1f1f] border border-white/10 text-white font-label-caps text-[10px] sm:text-xs py-2 sm:py-3 pl-10 pr-4 rounded-full focus:outline-none focus:border-brand-red transition-colors placeholder:text-on-surface-variant/30">
                </div>
            </div>
            
            <!-- Cards Section -->
            <div class="grid grid-cols-4 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-6">
                @foreach($facilities as $facility)
                    <div x-show="(currentCategory === 'all' || currentCategory === '{{ $facility['category'] }}') && ('{{ strtolower($facility['name']) }}'.includes(searchQuery.toLowerCase()))"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="glass-panel rounded-sm flex flex-col group cursor-default relative overflow-hidden h-36 sm:h-[360px] border border-white/10 hover:border-brand-red/60 transition-all duration-300 p-0">
                        
                        <!-- Top 2/3 Image -->
                        <div class="h-20 sm:h-[240px] w-full relative overflow-hidden bg-black shrink-0">
                            <img src="{{ $facility['img'] }}" alt="{{ $facility['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#131313] via-transparent to-transparent opacity-80"></div>
                        </div>
                         
                        <!-- Bottom 1/3 Text -->
                        <div class="flex flex-col p-2 sm:p-5 flex-grow justify-between relative z-10 bg-[#131313]/50">
                            <h3 class="font-headline-md text-[9px] sm:text-lg text-white tracking-wide uppercase line-clamp-2 leading-tight">
                                {{ $facility['name'] }}
                            </h3>
                            
                            <div class="flex items-center justify-between mt-auto pt-1 sm:pt-3 border-t border-white/10">
                                <span class="font-label-caps text-[7px] sm:text-xs text-brand-red font-bold tracking-widest">
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
@endsection
