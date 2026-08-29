@extends('layouts.admin')
@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', $order->order_number)

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ====== LEFT COLUMN ====== --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- Order Items --}}
        <div class="card-flat p-6">
            <h3 class="text-dark-950 font-extrabold mb-4 uppercase tracking-wide">📦 Order Items</h3>
            <div class="divide-y divide-dark-950">
                @foreach($order->details as $detail)
                <div class="flex gap-4 py-4 first:pt-0 last:pb-0">
                    <div class="w-14 h-14 flex-shrink-0 bg-primary-100 border-2 border-dark-950 rounded-xl overflow-hidden">
                        @if($detail->product)
                        <img src="{{ $detail->product->image_url }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-dark-950 font-bold uppercase">{{ $detail->product_name }}</p>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <span class="badge badge-info text-xs">{{ $detail->size }}</span>
                            <span class="badge bg-white text-dark-800 border-2 border-dark-950 font-black text-xs">{{ $detail->color }}</span>
                            @if($detail->sleeve_type)
                            <span class="badge bg-amber-100 text-amber-700 border-2 border-dark-950 font-black text-xs">{{ $detail->sleeve_type }}</span>
                            @endif
                            <span class="badge badge-primary text-xs">{{ $detail->sablon_type }}</span>
                            <span class="text-dark-600 font-bold text-xs uppercase">×{{ $detail->quantity }} pcs</span>
                        </div>
                        @if($detail->notes)
                        <p class="text-dark-400 text-xs mt-1">📝 {{ $detail->notes }}</p>
                        @endif
                        @if($detail->design_url)
                        <a href="{{ $detail->design_url }}" target="_blank" class="text-primary-400 text-xs hover:underline mt-1 inline-block">🎨 View Design File (Upload)</a>
                        @endif

                        @if($detail->design)
                        <div class="mt-2 p-2 bg-dark-50 border border-dark-200 rounded-lg flex items-center gap-3">
                            <img src="{{ $detail->design->image_url }}" alt="" class="w-10 h-10 object-contain bg-white border border-dark-950">
                            <div>
                                <p class="text-[10px] text-dark-500 font-bold uppercase leading-none mb-1">Store Design Ref.</p>
                                <p class="text-xs text-dark-950 font-black uppercase">{{ $detail->design->title }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-dark-600 font-bold text-xs uppercase tracking-wider">{{ $detail->formatted_price }}/pcs</p>
                        <p class="text-primary-600 font-black text-lg">{{ $detail->formatted_subtotal }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="border-t-3 border-dark-950 mt-4 pt-4 space-y-2">
                <div class="flex justify-between text-sm text-dark-600 font-bold uppercase tracking-wider"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm text-dark-600 font-bold uppercase tracking-wider"><span>Shipping</span><span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-dark-950 font-black text-xl pt-2 border-t-3 border-dark-950 uppercase tracking-tighter">
                    <span>Total</span><span class="text-primary-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Info Pengiriman --}}
        <div class="card-flat p-6">
            <h3 class="text-dark-950 font-extrabold mb-4 uppercase tracking-wide">🚚 Shipping Data</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-dark-600 font-bold uppercase text-xs">Recipient</p><p class="text-dark-950 font-extrabold">{{ $order->recipient_name }}</p></div>
                <div><p class="text-dark-600 font-bold uppercase text-xs">Phone</p><p class="text-dark-950 font-extrabold">{{ $order->recipient_phone }}</p></div>
                <div class="col-span-2"><p class="text-dark-600 font-bold uppercase text-xs">Address</p><p class="text-dark-950 font-bold">{{ $order->shipping_address }}, {{ $order->city }} {{ $order->postal_code }}</p></div>
                @if($order->notes)
                <div class="col-span-2"><p class="text-dark-600 font-bold uppercase text-xs">Notes</p><p class="text-dark-800 font-medium italic">"{{ $order->notes }}"</p></div>
                @endif
            </div>
        </div>

        {{-- Bukti Bayar --}}
        @if($order->payment && $order->payment->payment_proof)
        <div class="card-flat p-6">
            <h3 class="text-dark-950 font-extrabold mb-4 uppercase tracking-wide">💳 Payment Proof</h3>
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div><p class="text-dark-600 font-bold uppercase text-xs">Sender Bank</p><p class="text-dark-950 font-extrabold uppercase">{{ $order->payment->sender_bank }}</p></div>
                <div><p class="text-dark-600 font-bold uppercase text-xs">Sender Name</p><p class="text-dark-950 font-extrabold uppercase">{{ $order->payment->sender_name }}</p></div>
                <div><p class="text-dark-600 font-bold uppercase text-xs">Amount</p><p class="text-primary-600 font-black text-lg">{{ $order->payment->formatted_amount }}</p></div>
                <div><p class="text-dark-600 font-bold uppercase text-xs">Payment Time</p><p class="text-dark-950 font-bold uppercase">{{ $order->payment->paid_at?->format('d M Y H:i') }}</p></div>
            </div>
            <a href="{{ $order->payment->proof_url }}" target="_blank">
                <img src="{{ $order->payment->proof_url }}" alt="Payment proof" class="max-h-64 rounded-xl object-contain cursor-zoom-in hover:opacity-90 transition-opacity">
            </a>

            @if($order->payment->status === 'uploaded')
            <div class="flex gap-3 mt-4">
                <form method="POST" action="{{ route('admin.payments.verify', $order->payment->id) }}">
                    @csrf @method('PUT')
                    <button type="submit" class="btn-success">✅ Verify Payment</button>
                </form>
                <button onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="btn-danger">❌ Reject</button>
            </div>
            <form id="reject-form" method="POST" action="{{ route('admin.payments.reject', $order->payment->id) }}" class="hidden mt-4 space-y-3">
                @csrf @method('PUT')
                <textarea name="rejection_reason" rows="2" required class="form-input resize-none" placeholder="Rejection reason..."></textarea>
                <button type="submit" class="btn-danger btn-sm">Confirm Reject</button>
            </form>
            @endif
        </div>
        @endif
    </div>

    {{-- ====== RIGHT COLUMN ====== --}}
    <div class="space-y-6">

        {{-- Update Status --}}
        <div class="card-flat p-6">
            <h3 class="text-dark-950 font-extrabold mb-4 uppercase tracking-wide">⚙️ Update Status</h3>
            <div class="mb-4">
                <p class="text-dark-600 font-bold text-xs uppercase mb-2">Current Status</p>
                <span class="badge badge-{{ $order->status_color }} text-sm px-4 py-1.5">{{ $order->status_label }}</span>
            </div>
            <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-3">
                @csrf @method('PUT')
                <select name="status" class="form-select w-full">
                    @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ $order->status === $key ? 'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary w-full">Update Status</button>
            </form>
        </div>

        {{-- Info Pelanggan --}}
        <div class="card-flat p-6">
            <h3 class="text-dark-950 font-extrabold mb-4 uppercase tracking-wide">👤 Customer</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-primary-400 border-2 border-dark-950 rounded-full flex items-center justify-center text-dark-950 font-black shadow-brutal-sm">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-dark-950 font-extrabold uppercase text-sm">{{ $order->user->name }}</p>
                    <p class="text-dark-600 font-bold text-xs uppercase">{{ $order->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.show', $order->user->id) }}" class="btn-secondary btn-sm w-full text-center">View Profile</a>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="btn-secondary w-full text-center block">← Back</a>
    </div>
</div>
@endsection
