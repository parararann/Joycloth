<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:1000',
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Cek apakah produk ada di order tersebut
        $hasProduct = $order->details()->where('product_id', $request->product_id)->exists();
        if (!$hasProduct) {
            return redirect()->back()->with('error', 'Product not found in this order.');
        }

        // Cek apakah sudah pernah review produk ini di order ini
        $exists = Review::where('order_id', $order->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
            'order_id'   => $order->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }
}
