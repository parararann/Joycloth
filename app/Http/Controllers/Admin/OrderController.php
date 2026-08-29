<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('cari')) {
            $query->where('order_number', 'like', '%' . $request->cari . '%')
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->cari . '%'));
        }

        $orders   = $query->paginate(15)->withQueryString();
        $statuses = Order::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'details.product', 'details.design', 'payment.verifiedBy'])
            ->findOrFail($id);

        $statuses = Order::STATUSES;
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:' . implode(',', array_keys(Order::STATUSES))]);

        $order = Order::findOrFail($id);
        $data  = ['status' => $request->status];

        // Simpan timestamp milestone
        match ($request->status) {
            'confirmed'  => $data['confirmed_at'] = now(),
            'shipped'    => $data['shipped_at']   = now(),
            'completed'  => $data['completed_at'] = now(),
            default      => null,
        };

        // Logic Kembalikan Stok jika DIBATALKAN
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->details as $detail) {
                if ($detail->product) {
                    $detail->product->increment('stock', $detail->quantity);
                }
            }
        }

        $order->update($data);

        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Order status updated successfully!');
    }

    public function confirm(int $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'confirmed', 'confirmed_at' => now()]);

        return redirect()->back()
            ->with('success', 'Order confirmed successfully!');
    }
}
