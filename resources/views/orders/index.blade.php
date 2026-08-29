@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="section-title mb-2">My Orders</h1>
    <p class="section-subtitle mb-8">History of all your orders</p>

    @if($orders->isEmpty())
    <div class="card-flat p-16 text-center">
        <div class="text-7xl mb-4">📋</div>
        <h3 class="text-dark-700 font-semibold text-xl mb-2">No Orders Yet</h3>
        <p class="text-dark-400 mb-6">Let's start ordering your custom printed products!</p>
        <a href="{{ route('products.index') }}" class="btn-primary">Start Shopping</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="card-flat p-5 hover:shadow-md transition-shadow">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="font-mono font-bold text-dark-900">{{ $order->order_number }}</span>
                        <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    </div>
                    <p class="text-dark-400 text-sm mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-dark-500">Total</p>
                    <p class="font-display font-bold text-primary-600 text-lg">{{ $order->formatted_total }}</p>
                </div>
            </div>

            {{-- Item preview --}}
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($order->details->take(3) as $detail)
                <div class="flex items-center gap-2 bg-dark-50 rounded-lg px-3 py-1.5 text-sm">
                    <span class="text-dark-700">{{ $detail->product_name }}</span>
                    <span class="text-dark-400">&times;{{ $detail->quantity }}</span>
                </div>
                @endforeach
                @if($order->details->count() > 3)
                <div class="bg-dark-50 rounded-lg px-3 py-1.5 text-sm text-dark-400">
                    +{{ $order->details->count() - 3 }} more items
                </div>
                @endif
            </div>

            {{-- Payment Status --}}
            @if($order->payment)
            <div class="mb-4">
                <span class="text-sm text-dark-500">Payment: </span>
                <span class="badge badge-{{ $order->payment->status_color }}">{{ $order->payment->status_label }}</span>
            </div>
            @endif

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('orders.show', $order->order_number) }}" class="btn-secondary btn-sm">
                    View Details
                </a>
                @if($order->canPay())
                <a href="{{ route('orders.payment', $order->order_number) }}" class="btn-primary btn-sm">
                    💳 Pay Now
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
