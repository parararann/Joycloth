<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * CartController - Mengelola keranjang belanja berbasis Session
 */
class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang
     */
    public function index()
    {
        $cart  = session()->get('cart', []);
        
        // Ambil data desain untuk ditampilkan di keranjang jika ada
        $designIds = collect($cart)->pluck('design_id')->filter()->unique();
        $designs = Design::whereIn('id', $designIds)->get()->keyBy('id');

        $total = collect($cart)->sum(fn($item) => $item['subtotal']);
        return view('cart.index', compact('cart', 'total', 'designs'));
    }

    /**
     * Tambah produk ke keranjang
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'quantity'     => 'required|integer|min:1',
            'size'         => 'required|string',
            'color'        => 'required|string',
            'sleeve_type'  => 'nullable|string',
            'sablon_type'  => 'required|string',
            'custom_design' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240',
            'design_id'    => 'nullable|exists:designs,id',
            'notes'        => $request->sablon_type === 'No Printing (Plain)' ? 'nullable|string|max:500' : 'required|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart    = session()->get('cart', []);

        // Handle upload file desain
        $designPath = null;
        if ($request->hasFile('custom_design')) {
            $designPath = $request->file('custom_design')
                ->store('designs', 'public');
        }

        // Key unik: product_id + size + color + sleeve_type + sablon_type + design_id (jika ada)
        $sleevePart = $request->sleeve_type ? '_' . Str::slug($request->sleeve_type) : '';
        $key = $request->product_id . '_' . $request->size . '_' . $request->color . $sleevePart . '_' . $request->sablon_type . ($request->design_id ? '_ref' . $request->design_id : '');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
            $cart[$key]['subtotal']  = $cart[$key]['quantity'] * $cart[$key]['unit_price'];
        } else {
            $cart[$key] = [
                'product_id'    => $product->id,
                'product_name'  => $product->name,
                'product_image' => $product->image_url,
                'unit_price'    => $product->price,
                'quantity'      => $request->quantity,
                'size'          => $request->size,
                'color'         => $request->color,
                'sleeve_type'   => $request->sleeve_type,
                'sablon_type'   => $request->sablon_type,
                'custom_design' => $designPath,
                'design_id'     => $request->design_id,
                'notes'         => $request->notes,
                'subtotal'      => $product->price * $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update jumlah item di keranjang
     */
    public function update(Request $request, string $key)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $request->quantity;
            $cart[$key]['subtotal'] = $cart[$key]['unit_price'] * $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json([
            'success'      => true,
            'subtotal'     => 'Rp ' . number_format($cart[$key]['subtotal'], 0, ',', '.'),
            'subtotal_raw' => $cart[$key]['subtotal'],
            'total'        => 'Rp ' . number_format(collect($cart)->sum('subtotal'), 0, ',', '.'),
            'count'        => count($cart),
        ]);
    }

    /**
     * Hapus satu item dari keranjang
     */
    public function remove(string $key)
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }

    /**
     * Kosongkan seluruh keranjang
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully.');
    }
}
