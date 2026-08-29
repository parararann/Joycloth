@extends('layouts.admin')
@section('title', 'Payment Management')
@section('page-title', 'Payment Management')

@section('content')

<form method="GET" class="card-flat p-4 mb-6 flex flex-wrap gap-3">
    <select name="status" class="form-select w-52">
        <option value="">All Statuses</option>
        @foreach($statuses as $key => $label)
        <option value="{{ $key }}" {{ request('status') === $key ? 'selected':'' }}>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary btn-sm">Filter</button>
    @if(request('status'))
    <a href="{{ route('admin.payments.index') }}" class="btn-secondary btn-sm">Reset</a>
    @endif
</form>

<div class="card-flat overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Order No.</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Sender Bank</th>
                <th>Status</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td><span class="font-mono text-accent text-xs font-bold">{{ $payment->order->order_number }}</span></td>
                <td>
                    <p class="text-dark-950 font-bold text-sm">{{ $payment->order->user->name }}</p>
                    <p class="text-dark-600 text-xs font-bold">{{ $payment->order->user->email }}</p>
                </td>
                <td class="text-emerald-600 font-extrabold">{{ $payment->formatted_amount }}</td>
                <td class="text-dark-700 font-bold text-sm uppercase">{{ $payment->sender_bank ?? '-' }}</td>
                <td><span class="badge badge-{{ $payment->status_color }}">{{ $payment->status_label }}</span></td>
                <td class="text-dark-700 font-bold text-xs">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn-primary btn-sm">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-dark-500 py-12">No payment data yet</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $payments->links() }}</div>
</div>
@endsection
