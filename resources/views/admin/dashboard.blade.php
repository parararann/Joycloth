@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Summary of your store activity')

@section('content')

{{-- ====== STAT CARDS ====== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">

    <div class="stat-card">
        <div class="stat-icon bg-blue-500/10 text-blue-400">📦</div>
        <div>
            <p class="text-dark-600 font-bold text-sm uppercase tracking-wider">Total Orders</p>
            <p class="text-dark-950 font-display font-extrabold text-3xl">{{ number_format($stats['total_orders']) }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-amber-500/10 text-amber-400">⏳</div>
        <div>
            <p class="text-dark-600 font-bold text-sm uppercase tracking-wider">Pending Confirmation</p>
            <p class="text-dark-950 font-display font-extrabold text-3xl">{{ $stats['pending_orders'] }}</p>
            @if($stats['pending_orders'] > 0)
            <a href="{{ route('admin.orders.index', ['status'=>'pending']) }}" class="text-amber-400 text-xs hover:underline">View →</a>
            @endif
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-emerald-500/10 text-emerald-400">💰</div>
        <div>
            <p class="text-dark-600 font-bold text-sm uppercase tracking-wider">Total Revenue</p>
            <p class="text-dark-950 font-display font-extrabold text-2xl">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-violet-500/10 text-violet-400">👥</div>
        <div>
            <p class="text-dark-600 font-bold text-sm uppercase tracking-wider">Total Customers</p>
            <p class="text-dark-950 font-display font-extrabold text-3xl">{{ $stats['total_users'] }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-primary-500/10 text-primary-400">🗂️</div>
        <div>
            <p class="text-dark-600 font-bold text-sm uppercase tracking-wider">Total Products</p>
            <p class="text-dark-950 font-display font-extrabold text-3xl">{{ $stats['total_products'] }}</p>
        </div>
    </div>

    <div class="stat-card {{ $stats['unread_chats'] > 0 ? 'border border-primary-500/30' : '' }}">
        <div class="stat-icon bg-rose-500/10 text-rose-400">💬</div>
        <div>
            <p class="text-dark-600 font-bold text-sm uppercase tracking-wider">Unread Messages</p>
            <p class="text-dark-950 font-display font-extrabold text-3xl">{{ $stats['unread_chats'] }}</p>
            @if($stats['unread_chats'] > 0)
            <a href="{{ route('admin.chat.index') }}" class="text-rose-400 text-xs hover:underline">Reply now →</a>
            @endif
        </div>
    </div>
</div>

{{-- ====== CHARTS ====== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- Chart Pesanan --}}
    <div class="card-flat bg-white border-3 border-dark-950 p-6 shadow-brutal-sm">
        <h3 class="text-dark-950 font-extrabold uppercase tracking-wide mb-4">📊 Orders Last 7 Days</h3>
        <canvas id="orderChart" height="150"></canvas>
    </div>

    {{-- Chart Pendapatan --}}
    <div class="card-flat bg-white border-3 border-dark-950 p-6 shadow-brutal-sm">
        <h3 class="text-dark-950 font-extrabold uppercase tracking-wide mb-4">💹 Revenue Last 7 Days</h3>
        <canvas id="revenueChart" height="150"></canvas>
    </div>
</div>

{{-- ====== TABLES ====== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Orders --}}
    <div class="card-flat bg-white border-3 border-dark-950 shadow-brutal-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b-3 border-dark-950 bg-primary-200">
            <h3 class="text-dark-950 font-extrabold uppercase tracking-wide">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-primary-400 text-sm hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr class="bg-dark-900/50 border-dark-700">
                        <th class="text-dark-400">Order</th>
                        <th class="text-dark-400">Customer</th>
                        <th class="text-dark-400">Status</th>
                        <th class="text-dark-400">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr class="border-b-2 border-dark-950 hover:bg-primary-50 transition-colors">
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary-400 hover:underline font-mono text-xs">{{ $order->order_number }}</a>
                        </td>
                        <td class="text-dark-800 font-bold">{{ $order->user->name }}</td>
                        <td><span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                        <td class="text-dark-950 font-extrabold">{{ $order->formatted_total }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-dark-500 py-6">No orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Payments --}}
    <div class="card-flat bg-white border-3 border-dark-950 shadow-brutal-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b-3 border-dark-950 bg-primary-200">
            <h3 class="text-dark-950 font-extrabold uppercase tracking-wide">Payments Need Verification</h3>
            <a href="{{ route('admin.payments.index', ['status'=>'uploaded']) }}" class="text-primary-400 text-sm hover:underline">View All</a>
        </div>
        <div class="divide-y-2 divide-dark-950">
            @forelse($pendingPayments as $payment)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-primary-50 transition-colors">
                <div>
                    <p class="text-dark-950 text-sm font-bold">{{ $payment->order->user->name }}</p>
                    <p class="text-dark-600 text-xs font-bold">{{ $payment->order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-emerald-400 font-semibold text-sm">{{ $payment->formatted_amount }}</p>
                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-primary-400 text-xs hover:underline">Verify →</a>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-dark-500 text-sm">No payments need verification</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($chartLabels);
const orderData = @json($chartData);
const revenueData = @json($revenueData);

// Shared chart options
const sharedOptions = {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { color: 'rgba(0,0,0,0.1)' }, ticks: { color: '#000', font: { size: 11, weight: 'bold' } } },
        y: { grid: { color: 'rgba(0,0,0,0.1)' }, ticks: { color: '#000', font: { size: 11, weight: 'bold' } }, beginAtZero: true }
    }
};

// Order Chart
new Chart(document.getElementById('orderChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            data: orderData,
            backgroundColor: 'rgba(249, 115, 22, 0.5)',
            borderColor: '#f97316',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: sharedOptions
});

// Revenue Chart
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            data: revenueData,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#10b981',
        }]
    },
    options: {
        ...sharedOptions,
        scales: {
            ...sharedOptions.scales,
            y: {
                ...sharedOptions.scales.y,
                ticks: {
                    ...sharedOptions.scales.y.ticks,
                    callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K'
                }
            }
        }
    }
});
</script>
@endpush
