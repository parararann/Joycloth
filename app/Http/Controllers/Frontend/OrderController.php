<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Tampilkan halaman checkout
     */
    public function checkout(Request $request)
    {
        $selectedKeys = $request->input('selected_items', []);
        if (empty($selectedKeys)) {
            return redirect()->route('cart.index')
                ->with('error', 'Please select at least one item to checkout.');
        }

        $cart = session()->get('cart', []);
        $filteredCart = array_intersect_key($cart, array_flip($selectedKeys));

        if (empty($filteredCart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Selected items are no longer in your cart.');
        }

        $subtotal = collect($filteredCart)->sum('subtotal');
        $user     = auth()->user();

        return view('checkout.index', compact('filteredCart', 'subtotal', 'user', 'selectedKeys'));
    }

    /**
     * Proses pembuatan pesanan baru
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'recipient_name'    => 'required|string|max:100',
            'recipient_phone'   => 'required|string|max:20',
            'shipping_address'  => 'required|string',
            'city'              => ['required', 'string', 'max:100', function ($attribute, $value, $fail) {
                $allowed = ['jakarta', 'bogor', 'depok', 'tangerang', 'bekasi'];
                $isAllowed = false;
                foreach ($allowed as $city) {
                    if (stripos($value, $city) !== false) {
                        $isAllowed = true;
                        break;
                    }
                }
                if (!$isAllowed) {
                    $fail('Sorry, currently we only serve shipping to the JABODETABEK area.');
                }
            }],
            'postal_code'       => 'nullable|string|max:10',
            'notes'             => 'nullable|string|max:500',
            'shipping_method'   => 'required|in:manual,gosend',
            'shipping_cost'     => 'nullable|numeric|min:0',
            'selected_items'    => 'required|array|min:1',
        ]);

        $cart = session()->get('cart', []);
        $selectedKeys = $request->input('selected_items', []);
        $filteredCart = array_intersect_key($cart, array_flip($selectedKeys));

        if (empty($filteredCart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Selected items are no longer available in your cart.');
        }

        DB::beginTransaction();
        try {
            $subtotal     = collect($filteredCart)->sum('subtotal');
            $shippingCost = $request->shipping_method === 'gosend' ? (float) $request->shipping_cost : 0;
            $total        = $subtotal + $shippingCost;

            // Buat order header
            $order = Order::create([
                'user_id'          => auth()->id(),
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shippingCost,
                'shipping_method'  => $request->shipping_method,
                'total_amount'     => $total,
                'recipient_name'   => $request->recipient_name,
                'recipient_phone'  => $request->recipient_phone,
                'shipping_address' => $request->shipping_address,
                'city'             => $request->city,
                'postal_code'      => $request->postal_code,
                'notes'            => $request->notes,
            ]);

            // Buat detail untuk setiap item yang dipilih dan kurangi stok
            foreach ($filteredCart as $item) {
                OrderDetail::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'product_name'  => $item['product_name'],
                    'unit_price'    => $item['unit_price'],
                    'quantity'      => $item['quantity'],
                    'size'          => $item['size'],
                    'color'         => $item['color'] ?? null,
                    'sleeve_type'   => $item['sleeve_type'] ?? null,
                    'sablon_type'   => $item['sablon_type'],
                    'custom_design' => $item['custom_design'] ?? null,
                    'design_id'     => $item['design_id'] ?? null,
                    'notes'         => $item['notes'] ?? null,
                    'subtotal'      => $item['subtotal'],
                ]);

                // KURANGI STOK PRODUK
                $productItem = Product::find($item['product_id']);
                if ($productItem) {
                    $productItem->decrement('stock', $item['quantity']);
                }
            }

            // Hapus HANYA item yang berhasil dipesan dari keranjang
            foreach ($selectedKeys as $key) {
                unset($cart[$key]);
            }
            session()->put('cart', $cart);

            DB::commit();

            return redirect()->route('orders.show', $order->order_number)
                ->with('success', 'Order placed successfully! Please proceed to payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Daftar riwayat pesanan milik user
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['details', 'payment'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Detail / tracking pesanan
     */
    public function show(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['details.product', 'payment'])
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Tampilkan form upload bukti pembayaran
     */
    public function payment(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$order->canPay()) {
            return redirect()->route('orders.show', $orderNumber)
                ->with('error', 'This order cannot be paid.');
        }

        // Info rekening bank dari config/env
        $bankInfo = [
            'bank_name'      => config('app.bank_name', 'Bank BCA'),
            'bank_account'   => config('app.bank_account', '6631361118'),
            'account_holder' => config('app.account_holder', 'Muhammad Farhan'),
        ];

        return view('orders.payment', compact('order', 'bankInfo'));
    }

    /**
     * Proses upload bukti pembayaran
     */
    public function submitPayment(Request $request, string $orderNumber)
    {
        $request->validate([
            'sender_bank'    => 'required|string|max:100',
            'sender_name'    => 'required|string|max:100',
            'payment_proof'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$order->canPay()) {
            return redirect()->route('orders.show', $orderNumber)
                ->with('error', 'This order cannot be paid.');
        }

        // Upload file bukti bayar
        $proofPath = $request->file('payment_proof')
            ->store('payments/proofs', 'public');

        // Buat atau update record payment
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount'         => $order->total_amount,
                'bank_name'      => config('app.bank_name', 'Bank BCA'),
                'bank_account'   => config('app.bank_account', '6631361118'),
                'account_holder' => config('app.account_holder', 'Muhammad Farhan'),
                'sender_bank'    => $request->sender_bank,
                'sender_name'    => $request->sender_name,
                'payment_proof'  => $proofPath,
                'status'         => 'uploaded',
                'paid_at'        => now(),
            ]
        );

        return redirect()->route('orders.show', $orderNumber)
            ->with('success', 'Payment proof submitted! We will verify it shortly.');
    }
}
