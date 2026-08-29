<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // === STATISTIK RINGKASAN ===
        $stats = [
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::byStatus('pending')->count(),
            'total_revenue'   => Payment::where('status', 'verified')->sum('amount'),
            'total_users'     => User::where('role', 'user')->count(),
            'total_products'  => Product::count(),
            'unread_chats'    => ChatMessage::where('sender_type', 'user')->unread()->count(),
        ];

        // === PESANAN TERBARU ===
        $recentOrders = Order::with('user')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // === PEMBAYARAN MENUNGGU VERIFIKASI ===
        $pendingPayments = Payment::where('status', 'uploaded')
            ->with('order.user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // === GRAFIK PESANAN 7 HARI TERAKHIR (per hari) ===
        $orderChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Lengkapi 7 hari (agar tidak ada hari yang kosong)
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d M');
            $chartData[]   = $orderChart->get($date)?->total ?? 0;
        }

        // === GRAFIK PENDAPATAN 7 HARI TERAKHIR ===
        $revenueChart = Payment::select(
                DB::raw('DATE(verified_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'verified')
            ->where('verified_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $revenueData[] = (float) ($revenueChart->get($date)?->total ?? 0);
        }

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'pendingPayments',
            'chartLabels', 'chartData', 'revenueData'
        ));
    }
}
