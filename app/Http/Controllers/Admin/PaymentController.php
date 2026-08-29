<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.user'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();
        $statuses = Payment::STATUSES;

        return view('admin.payments.index', compact('payments', 'statuses'));
    }

    public function show(int $id)
    {
        $payment = Payment::with(['order.user', 'order.details.product', 'verifiedBy'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verifikasi pembayaran (approve)
     */
    public function verify(int $id)
    {
        $payment = Payment::with('order.details.product')->findOrFail($id);
        
        // Cek jika sudah terverifikasi sebelumnya untuk menghindari pengurangan stok ganda
        if ($payment->status === 'verified') {
            return redirect()->back()->with('error', 'This payment has already been verified.');
        }

        $payment->update([
            'status'      => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Update status order menjadi confirmed jika masih pending
        if ($payment->order->status === 'pending') {
            $payment->order->update([
                'status'       => 'confirmed',
                'confirmed_at' => now(),
            ]);
        }

        return redirect()->route('admin.payments.show', $id)
            ->with('success', 'Payment successfully verified!');
    }

    /**
     * Tolak pembayaran
     */
    public function reject(Request $request, int $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.payments.show', $id)
            ->with('success', 'Payment successfully rejected.');
    }
}
