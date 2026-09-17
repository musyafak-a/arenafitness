@extends('admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cashier-pos.css') }}">
@endpush

@section('content')
    <div class="cashier-page">
        @php
            $dashboardTransactions = $transactions
                ->filter(fn ($transaction) => $transaction->transaction_at?->isToday())
                ->take(5)
                ->values();
            $dashboardReceiptQueue = $receiptQueue
                ->filter(fn ($receipt) => $receipt->transaction_at?->isToday())
                ->filter(fn ($receipt) => $receipt->payment_method === 'qris')
                ->filter(fn ($receipt) => $receipt->payment_status !== 'verified')
                ->take(4)
                ->values();
            $historyMemberTransactions = $transactions
                ->filter(fn ($transaction) => $transaction->transaction_group === 'member_payment')
                ->values();
            $historyDailyPassTransactions = $transactions
                ->filter(fn ($transaction) => $transaction->transaction_group === 'daily_pass')
                ->values();
            $historyProductTransactions = $transactions
                ->filter(fn ($transaction) => $transaction->transaction_group === 'product_sale' || $transaction->product_id)
                ->values();
        @endphp

        @include('cashier.partials.hero_section')

        <section class="cashier-grid">
            @include('cashier.partials.recent_transactions')

            <div class="cashier-side">
                @include('cashier.partials.verification_queue')
                @include('cashier.partials.payment_summary')
            </div>
        </section>
    </div>

    @include('cashier.partials.history_sidebar')
    @include('cashier.partials.transaction_modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/cashier-pos.js') }}"></script>
@endpush
