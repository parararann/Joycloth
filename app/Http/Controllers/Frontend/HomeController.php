<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Landing page utama
     */
    public function index()
    {
        // Ambil produk unggulan (6 produk aktif dengan sorting kustom)
        $featuredProducts = Product::active()
            ->with('category')
            ->orderBy('sort_order', 'asc')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // Kategori aktif dengan jumlah produk (diurutkan kustom)
        $categories = Category::active()
            ->withCount(['activeProducts'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('name')
            ->get();

        return view('welcome', compact('featuredProducts', 'categories'));
    }

    /**
     * Halaman tentang kami
     */
    public function about()
    {
        return view('about');
    }
}
