@extends('layouts.app')
@section('title', 'Order Details ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-dark-400 mb-8">
        <a href="{{ route('orders.index') }}" class="hover:text-primary-600">My Orders</a>
        <span>/</span>
        <span class="text-dark-700">{{ $order->order_number }}</span>
    </nav>

    {{-- Header --}}
    <div class="card-flat p-6 mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="font-display font-bold text-2xl text-dark-900">{{ $order->order_number }}</h1>
                <p class="text-dark-400 text-sm mt-1">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <span class="badge badge-{{ $order->status_color }} text-sm px-4 py-1.5">{{ $order->status_label }}</span>
        </div>

        {{-- Tracking Timeline --}}
        <div class="mt-8">
            <h3 class="font-semibold text-dark-700 mb-4 text-sm uppercase tracking-wider">Order Tracking</h3>
            <div class="relative">
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-dark-200"></div>
                @php
                    $steps = [
                        ['pending',    '📝', 'Order Placed',      $order->created_at],
                        ['confirmed',  '✅', 'Admin Confirmed',   $order->confirmed_at],
                        ['processing', '⚙️', 'Processing',        null],
                        ['shipped',    '🚚', 'Shipped',           $order->shipped_at],
                        ['completed',  '🎉', 'Completed',         $order->completed_at],
                    ];
                    $statusOrder = array_keys(\App\Models\Order::STATUSES);
                    $currentIndex = array_search($order->status, $statusOrder);
                @endphp
                <div class="space-y-4 pl-12">
                    @foreach($steps as $i => [$status, $icon, $label, $time])
                    @php $isDone = array_search($status, $statusOrder) <= $currentIndex; @endphp
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-8 w-8 h-8 rounded-full flex items-center justify-center text-sm border-2
                            {{ $isDone ? 'bg-primary-500 border-primary-500 text-white' : 'bg-white border-dark-200 text-dark-400' }}">
                            {{ $isDone ? '✓' : ($i+1) }}
                        </div>
                        <div class="{{ $isDone ? '' : 'opacity-40' }}">
                            <p class="font-semibold text-dark-800 {{ $isDone ? '' : 'text-dark-400' }}">{{ $icon }} {{ $label }}</p>
                            @if($isDone && $time)
                            <p class="text-xs text-dark-400 mt-0.5">{{ $time->format('d M Y, H:i') }}</p>
                            @elseif(!$isDone)
                            <p class="text-xs text-dark-400">Pending...</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Alamat Pengiriman --}}
        <div class="card-flat p-5">
            <h3 class="font-semibold text-dark-800 mb-3 flex items-center gap-2">🏠 Shipping Address</h3>
            <p class="font-semibold text-dark-900">{{ $order->recipient_name }}</p>
            <p class="text-dark-600 text-sm">{{ $order->recipient_phone }}</p>
            <p class="text-dark-600 text-sm mt-1">{{ $order->shipping_address }}, {{ $order->city }} {{ $order->postal_code }}</p>
        </div>

        {{-- Info Pembayaran --}}
        <div class="card-flat p-5">
            <h3 class="font-semibold text-dark-800 mb-3">💳 Payment</h3>
            @if($order->payment)
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-dark-500">Status</span>
                    <span class="badge badge-{{ $order->payment->status_color }}">{{ $order->payment->status_label }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-dark-500">Amount</span>
                    <span class="font-semibold">{{ $order->payment->formatted_amount }}</span>
                </div>
                @if($order->payment->status === 'rejected' && $order->payment->rejection_reason)
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-2">
                    <p class="text-red-600 text-xs"><strong>Rejection Reason:</strong> {{ $order->payment->rejection_reason }}</p>
                </div>
                @endif
            </div>
            @else
            <p class="text-dark-400 text-sm">No payment data yet</p>
            @endif
        </div>
    </div>

    {{-- Item Pesanan --}}
    <div class="card-flat p-6 mb-6">
        <h3 class="font-display font-bold text-dark-900 mb-5">Order Items</h3>
        <div class="divide-y divide-dark-100">
            @foreach($order->details as $detail)
            <div class="flex gap-4 py-4 first:pt-0 last:pb-0">
                <div class="w-16 h-16 flex-shrink-0 bg-dark-100 rounded-xl overflow-hidden">
                    @if($detail->product)
                    <img src="{{ $detail->product->image_url }}" alt="" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-dark-900">{{ $detail->product_name }}</p>
                    <div class="flex flex-wrap gap-2 mt-1">
                        <span class="badge badge-info text-xs">{{ $detail->size }}</span>
                        <span class="badge bg-white text-dark-800 border border-dark-200 text-xs">{{ $detail->color }}</span>
                        @if($detail->sleeve_type)
                        <span class="badge bg-amber-100 text-amber-700 border border-amber-200 text-xs">{{ $detail->sleeve_type }}</span>
                        @endif
                        <span class="badge badge-primary text-xs">{{ $detail->sablon_type }}</span>
                        <span class="text-dark-400 text-xs">x{{ $detail->quantity }} pcs</span>
                    </div>
                    @if($detail->notes)
                    <p class="text-dark-400 text-xs mt-1">📝 {{ $detail->notes }}</p>
                    @endif
                    @if($detail->design_url)
                    <a href="{{ $detail->design_url }}" target="_blank" class="text-primary-600 text-xs hover:underline mt-1 inline-block">🎨 View Design</a>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs text-dark-400">{{ $detail->formatted_price }}/pcs</p>
                    <p class="font-bold text-dark-900">{{ $detail->formatted_subtotal }}</p>

                    @if($order->status === 'completed' && $detail->product_id)
                        @php
                            $hasReviewed = \App\Models\Review::where('order_id', $order->id)
                                ->where('product_id', $detail->product_id)
                                ->exists();
                        @endphp

                        @if(!$hasReviewed)
                            <button @click="$dispatch('open-review-modal', {product_id: {{ $detail->product_id }}, product_name: '{{ $detail->product_name }}'})" 
                                    class="mt-2 text-[10px] font-black uppercase tracking-widest bg-yellow-300 border-2 border-dark-950 px-2 py-1 shadow-brutal-sm hover:bg-yellow-400 transition-all">
                                ⭐ Give Review
                            </button>
                        @else
                            <span class="mt-2 block text-[10px] font-bold text-green-600 uppercase tracking-widest">✅ Reviewed</span>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Review Modal --}}
        <div x-data="{ open: false, product_id: '', product_name: '' }" 
             @open-review-modal.window="open = true; product_id = $event.detail.product_id; product_name = $event.detail.product_name"
             x-show="open" 
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-dark-950/80 backdrop-blur-sm" @click="open = false"></div>
            <div class="relative bg-[#f4f4f0] border-4 border-dark-950 p-6 w-full max-w-md shadow-brutal-lg">
                <h3 class="font-display font-black text-xl text-dark-950 uppercase tracking-tight mb-4">Review: <span class="text-primary-600" x-text="product_name"></span></h3>
                
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="product_id" :value="product_id">
                    
                    <div class="mb-4">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Perfect)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Good)</option>
                            <option value="3">⭐⭐⭐ (3 - OK)</option>
                            <option value="2">⭐⭐ (2 - Poor)</option>
                            <option value="1">⭐ (1 - Very Bad)</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Comment</label>
                        <textarea name="comment" rows="4" class="form-input" placeholder="What do you think about this product?" required></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="open = false" class="btn-secondary flex-1">Cancel</button>
                        <button type="submit" class="btn-primary flex-1">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="border-t border-dark-200 mt-4 pt-4 space-y-2">
            <div class="flex justify-between text-sm text-dark-600">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-dark-600">
                <span>Shipping</span>
                <span>{{ $order->shipping_cost > 0 ? 'Rp '.number_format($order->shipping_cost,0,',','.') : 'Confirmed by Admin' }}</span>
            </div>
            <div class="flex justify-between font-bold text-dark-900 text-base pt-2 border-t border-dark-200">
                <span>Total</span>
                <span class="text-primary-600">{{ $order->formatted_total }}</span>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap gap-4">
        @if($order->canPay())
        <a href="{{ route('orders.payment', $order->order_number) }}" class="btn-primary">
            💳 Upload Payment Proof
        </a>
        @endif
        <a href="{{ route('orders.index') }}" class="btn-secondary">← Back</a>
        <a href="{{ route('chat.index') }}" class="btn-outline">💬 Ask via Chat</a>
    </div>
</div>
@endsection
