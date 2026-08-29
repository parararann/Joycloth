<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Design;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Katalog produk dengan filter & search
     */
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->kategori));
        }

        // Search berdasarkan nama produk
        if ($request->filled('cari')) {
            $query->where('name', 'like', '%' . $request->cari . '%');
        }

        // Sorting
        match ($request->get('urut', 'terbaru')) {
            'termurah'  => $query->orderBy('price', 'asc'),
            'termahal'  => $query->orderBy('price', 'desc'),
            'terpopuler' => $query->orderBy('sort_order', 'desc'),
            default     => $query->orderBy('sort_order', 'asc')->orderByDesc('created_at'),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::active()
            ->withCount('activeProducts')
            ->orderBy('sort_order', 'asc')
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Detail produk
     */
    public function show(string $slug)
    {
        $product = Product::active()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        // Produk serupa dari kategori yang sama
        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        // Referensi desain dari toko (diurutkan secara kustom)
        $designs = Design::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderByDesc('created_at')
            ->get();

        return view('products.show', compact('product', 'related', 'designs'));
    }
}
