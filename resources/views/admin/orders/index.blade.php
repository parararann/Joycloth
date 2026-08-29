@extends('layouts.admin')
@section('title', 'Order Management')
@section('page-title', 'Order Management')

@section('content')

{{-- Filter Bar --}}
<form method="GET" class="card-flat p-4 mb-6 flex flex-wrap gap-3">
    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Search order no. or name..." class="form-input flex-1 min-w-48">
    <select name="status" class="form-select w-48">
        <option value="">All Statuses</option>
        @foreach($statuses as $key => $label)
        <option value="{{ $key }}" {{ request('status') === $key ? 'selected':'' }}>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary btn-sm">Filter</button>
    @if(request()->hasAny(['cari','status']))
    <a href="{{ route('admin.orders.index') }}" class="btn-secondary btn-sm">Reset</a>
    @endif
</form>

<div class="card-flat overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Order No.</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><span class="font-mono text-accent text-xs font-bold">{{ $order->order_number }}</span></td>
                <td>
                    <p class="text-dark-950 font-bold text-sm">{{ $order->user->name }}</p>
                    <p class="text-dark-600 text-xs font-bold">{{ $order->user->email }}</p>
                </td>
                <td class="text-primary-600 font-extrabold">{{ $order->formatted_total }}</td>
                <td>
                    @if($order->payment)
                    <span class="badge badge-{{ $order->payment->status_color }}">{{ $order->payment->status_label }}</span>
                    @else
                    <span class="badge badge-warning">Not Paid</span>
                    @endif
                </td>
                <td><span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                <td class="text-dark-700 font-bold text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-primary btn-sm">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-dark-500 py-12">No orders yet</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $orders->links() }}</div>
</div>
@endsection
