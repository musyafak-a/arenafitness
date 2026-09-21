<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Invoice {{ $transaction->invoice }} | Arena Fitness</title>
    <script src="{{ asset('js/tailwind.min.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Hanken+Grotesk:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        background: '#131313',
                        'surface-container': '#1f1f1f',
                        'surface-container-lowest': '#0e0e0e',
                        'surface-variant': '#353535',
                        'on-background': '#e2e2e2',
                        'on-surface-variant': '#ebbbb4',
                        primary: '#ffb4a8',
                        'brand-red': '#ff5540',
                    },
                    fontFamily: {
                        display: ['Oswald'],
                        mono: ['JetBrains Mono'],
                        body: ['Hanken Grotesk'],
                    },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass-panel {
            background: linear-gradient(135deg, rgba(31,31,31,.74), rgba(14,14,14,.52));
            border: 1px solid rgba(255,255,255,.13);
            box-shadow: 0 24px 80px rgba(0,0,0,.45);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }
    </style>
</head>
<body class="bg-[#131313] text-[#e2e2e2] font-body selection:bg-brand-red selection:text-black p-5 md:p-16">
    <div class="max-w-3xl mx-auto glass-panel p-8 md:p-12">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-12 border-b border-white/10 pb-8">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/arena-fitness-logo.jpg') }}" alt="Arena Fitness" class="h-12 rounded-lg">
                <div>
                    <h1 class="font-display text-3xl uppercase italic text-white leading-none">Arena <span class="text-brand-red">Fitness</span></h1>
                    <p class="font-mono text-[10px] text-[#ebbbb4] uppercase tracking-widest mt-1">Official Receipt</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <p class="font-mono text-xs text-[#ebbbb4] mb-1">INVOICE NO</p>
                <p class="font-mono text-xl text-white">{{ $transaction->invoice }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div>
                <p class="font-mono text-xs text-[#ebbbb4] mb-2 uppercase">Ditagihkan Kepada</p>
                <p class="text-lg text-white font-semibold">{{ $transaction->customer_name }}</p>
                <p class="text-sm text-[#ebbbb4] mt-1">Member Arena Fitness</p>
            </div>
            <div class="md:text-right">
                <p class="font-mono text-xs text-[#ebbbb4] mb-2 uppercase">Tanggal Transaksi</p>
                <p class="text-lg text-white">{{ $transaction->transaction_at->format('d M Y, H:i') }}</p>
                <div class="mt-4 inline-block">
                    @php
                        $status = strtolower($transaction->payment_status);
                        $isSuccess = in_array($status, ['verified', 'paid', 'success', 'berhasil']);
                        $isPending = in_array($status, ['pending']);
                    @endphp
                    @if($isSuccess)
                        <span class="bg-green-500/10 text-green-300 border border-green-500/20 px-4 py-2 font-mono text-xs uppercase tracking-widest">LUNAS</span>
                    @elseif($isPending)
                        <span class="bg-yellow-500/10 text-yellow-300 border border-yellow-500/20 px-4 py-2 font-mono text-xs uppercase tracking-widest">MENUNGGU PEMBAYARAN</span>
                    @else
                        <span class="bg-red-500/10 text-red-300 border border-red-500/20 px-4 py-2 font-mono text-xs uppercase tracking-widest">BATAL / GAGAL</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="border border-white/10 bg-black/20 rounded-sm mb-12 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/10 bg-white/5">
                        <th class="px-6 py-4 font-mono text-[#ebbbb4] uppercase text-[10px] tracking-widest">Deskripsi</th>
                        <th class="px-6 py-4 font-mono text-[#ebbbb4] uppercase text-[10px] tracking-widest text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <tr>
                        <td class="px-6 py-5 text-white">{{ $transaction->description }}</td>
                        <td class="px-6 py-5 text-white font-mono text-right">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-white/5">
                        <td class="px-6 py-4 font-mono text-[#ebbbb4] uppercase text-xs tracking-widest text-right">Total Tagihan</td>
                        <td class="px-6 py-4 text-brand-red font-mono text-xl text-right">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-white/10">
            <a href="{{ route('member.membership') }}" class="font-mono text-xs text-[#ebbbb4] hover:text-white uppercase tracking-widest flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Membership
            </a>
            
            <button onclick="window.print()" class="bg-white/10 border border-white/20 hover:bg-white hover:text-black transition-colors px-6 py-3 font-mono text-xs uppercase tracking-widest flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">print</span>
                Cetak Struk
            </button>
        </div>
    </div>
</body>
</html>
