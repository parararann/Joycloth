@extends('layouts.admin')
@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Info Pembayaran --}}
    <div class="card-flat p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-dark-950 font-black text-xl uppercase tracking-tighter">{{ $payment->order->order_number }}</h3>
                <p class="text-dark-600 font-bold text-xs uppercase">{{ $payment->order->user->name }} • {{ $payment->created_at->format('d M Y, H:i') }}</p>
            </div>
            <span class="badge badge-{{ $payment->status_color }} text-sm px-4 py-1.5">{{ $payment->status_label }}</span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
            <div class="bg-primary-100 border-2 border-dark-950 p-4 shadow-brutal-sm">
                <p class="text-dark-600 font-bold uppercase text-xs mb-1">Transfer Amount</p>
                <p class="text-emerald-600 font-black text-2xl font-display">{{ $payment->formatted_amount }}</p>
            </div>
            <div class="bg-primary-100 border-2 border-dark-950 p-4 shadow-brutal-sm">
                <p class="text-dark-600 font-bold uppercase text-xs mb-1">Destination Bank</p>
                <p class="text-dark-950 font-black uppercase">{{ $payment->bank_name }}</p>
                <p class="text-accent font-mono font-bold">{{ $payment->bank_account }}</p>
            </div>
            <div class="bg-white border-2 border-dark-950 p-4 shadow-brutal-sm">
                <p class="text-dark-600 font-bold uppercase text-xs mb-1">Sender Bank</p>
                <p class="text-dark-950 font-black uppercase">{{ $payment->sender_bank ?? '-' }}</p>
            </div>
            <div class="bg-white border-2 border-dark-950 p-4 shadow-brutal-sm">
                <p class="text-dark-600 font-bold uppercase text-xs mb-1">Sender Name</p>
                <p class="text-dark-950 font-black uppercase">{{ $payment->sender_name ?? '-' }}</p>
            </div>
        </div>

        @if($payment->payment_proof)
        <div>
            <p class="text-dark-950 font-black uppercase text-xs mb-3">Transfer Proof</p>
            <a href="{{ $payment->proof_url }}" target="_blank" class="block w-fit">
                <img src="{{ $payment->proof_url }}" alt="Transfer Proof" class="max-h-80 border-3 border-dark-950 shadow-brutal cursor-zoom-in hover:opacity-90 transition-opacity">
            </a>
        </div>
        @endif

        @if($payment->status === 'rejected' && $payment->rejection_reason)
        <div class="mt-4 bg-red-100 border-2 border-dark-950 p-4 shadow-brutal-sm">
            <p class="text-red-600 font-black uppercase text-xs mb-1">Rejection Reason:</p>
            <p class="text-dark-950 font-bold italic">"{{ $payment->rejection_reason }}"</p>
        </div>
        @endif

        @if($payment->status === 'verified' && $payment->verifiedBy)
        <div class="mt-4 bg-primary-100 border-2 border-dark-950 p-4 shadow-brutal-sm">
            <p class="text-emerald-700 font-bold uppercase text-xs">✅ Verified by <span class="font-black">{{ $payment->verifiedBy->name }}</span> on {{ $payment->verified_at->format('d M Y H:i') }}</p>
        </div>
        @endif
    </div>

    {{-- Actions --}}
    @if($payment->status === 'uploaded')
    <div class="card-flat p-6">
        <h3 class="text-dark-950 font-extrabold uppercase tracking-wide mb-4">Actions</h3>
        <div class="flex gap-3 flex-wrap">
            <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}">
                @csrf @method('PUT')
                <button type="submit" class="btn-success">✅ Verify Payment</button>
            </form>
            <button onclick="document.getElementById('reject-section').classList.toggle('hidden')" class="btn-danger">❌ Reject Payment</button>
        </div>

        <div id="reject-section" class="hidden mt-4">
            <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}" class="space-y-3">
                @csrf @method('PUT')
                <label class="form-label">Rejection Reason *</label>
                <textarea name="rejection_reason" rows="3" required class="form-input resize-none" placeholder="Explain the rejection reason to the customer..."></textarea>
                <button type="submit" class="btn-danger">Confirm Reject</button>
            </form>
        </div>
    </div>
    @endif

    <a href="{{ route('admin.payments.index') }}" class="btn-secondary block text-center">← Back</a>
</div>
@endsection
