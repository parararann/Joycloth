@extends('layouts.app')
@section('title', 'Upload Payment Proof')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="section-title mb-2">Payment Confirmation</h1>
    <p class="section-subtitle mb-8">Order: <strong>{{ $order->order_number }}</strong></p>

    {{-- Info Rekening --}}
    <div class="card-flat p-6 mb-6 border-l-4 border-primary-500">
        <h3 class="font-display font-bold text-dark-900 text-lg mb-4">📋 Transfer to the Following Account</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center bg-dark-50 rounded-xl p-4">
                <div>
                    <p class="text-dark-500 text-sm">Bank</p>
                    <p class="font-bold text-dark-900 text-lg">{{ $bankInfo['bank_name'] }}</p>
                </div>
            </div>
            <div class="flex justify-between items-center bg-dark-50 rounded-xl p-4">
                <div>
                    <p class="text-dark-500 text-sm">Account Number</p>
                    <p class="font-mono font-black text-dark-900 text-2xl tracking-wider">{{ $bankInfo['bank_account'] }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $bankInfo['bank_account'] }}')" class="badge badge-primary cursor-pointer hover:bg-primary-200 transition-colors">Copy</button>
            </div>
            <div class="flex justify-between items-center bg-dark-50 rounded-xl p-4">
                <div>
                    <p class="text-dark-500 text-sm">Account Holder</p>
                    <p class="font-bold text-dark-900">{{ $bankInfo['account_holder'] }}</p>
                </div>
            </div>
            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                <p class="text-dark-500 text-sm">Amount to Transfer</p>
                <p class="font-display font-black text-primary-600 text-3xl">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                <p class="text-xs text-primary-500 mt-1">*Shipping fee will be confirmed by admin separately</p>
            </div>
        </div>
    </div>

    {{-- Upload Form --}}
    <div class="card-flat p-6">
        <h3 class="font-display font-bold text-dark-900 text-lg mb-5">📤 Upload Transfer Proof</h3>

        <form method="POST" action="{{ route('orders.payment.submit', $order->order_number) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Sender Bank <span class="text-red-500">*</span></label>
                    <input type="text" name="sender_bank" value="{{ old('sender_bank') }}"
                           class="form-input @error('sender_bank') border-red-400 @enderror"
                           placeholder="Example: BCA, Mandiri..." required>
                    @error('sender_bank')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Sender Name <span class="text-red-500">*</span></label>
                    <input type="text" name="sender_name" value="{{ old('sender_name', auth()->user()->name) }}"
                           class="form-input @error('sender_name') border-red-400 @enderror" required>
                    @error('sender_name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- File Upload --}}
            <div>
                <label class="form-label">Transfer Proof <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-dark-200 rounded-xl p-8 text-center hover:border-primary-400 transition-colors cursor-pointer"
                     onclick="document.getElementById('proof-input').click()">
                    <input type="file" id="proof-input" name="payment_proof"
                           accept=".jpg,.jpeg,.png,.pdf" class="sr-only" required
                           onchange="previewImage(this, 'proof-preview')">
                    <img id="proof-preview" class="hidden max-h-48 mx-auto mb-3 rounded-xl object-contain">
                    <div id="proof-placeholder">
                        <svg class="w-14 h-14 text-dark-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-dark-600 font-medium">Click to select transfer proof file</p>
                        <p class="text-dark-400 text-sm mt-1">JPG, PNG, or PDF (max. 5MB)</p>
                    </div>
                </div>
                @error('payment_proof')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="alert-warning">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="text-sm">Make sure the transfer proof is clearly visible. Confirmation takes up to 24 business hours.</span>
            </div>

            <button type="submit" class="btn-primary w-full py-4 text-base">
                📤 Submit Payment Proof
            </button>
        </form>
    </div>

    <a href="{{ route('orders.show', $order->order_number) }}" class="block text-center text-dark-400 hover:text-dark-700 mt-4 text-sm transition-colors">
        ← Back to Order Details
    </a>
</div>

@push('scripts')
<script>
document.getElementById('proof-input')?.addEventListener('change', function() {
    const preview = document.getElementById('proof-preview');
    const placeholder = document.getElementById('proof-placeholder');
    if (this.files[0]) {
        if (this.files[0].type === 'application/pdf') {
            placeholder.innerHTML = '<div class="text-4xl mb-2">📄</div><p class="text-dark-700 font-medium">' + this.files[0].name + '</p>';
        } else {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(this.files[0]);
        }
    }
});
</script>
@endpush
@endsection
