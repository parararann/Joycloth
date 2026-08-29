<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('cari')) {
            $query->where('name', 'like', '%' . $request->cari . '%');
        }
        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        $products   = $query->orderBy('sort_order', 'asc')->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories  = Category::active()->get();
        $sablonTypes = ['No Printing (Plain)','Manual Screen Print','DTF Print','Plastisol Print','Rubber Print','Discharge Print','Embroidery'];
        $sizes       = ['XS','S','M','L','XL','XXL','XXXL'];
        $colors      = ['Black', 'White', 'Navy', 'Maroon', 'Grey', 'Army Green', 'Yellow', 'Red'];
        $sleeveTypes = ['Short Sleeve', 'Long Sleeve', '3/4 Sleeve'];
        return view('admin.products.create', compact('categories', 'sablonTypes', 'sizes', 'colors', 'sleeveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:200',
            'description'  => 'required|string',
            'material'     => 'nullable|string|max:200',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'min_order'    => 'required|integer|min:1',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'sizes'        => 'nullable|array',
            'colors'       => 'nullable|array',
            'sablon_types' => 'nullable|array',
            'sleeve_types' => 'nullable|array',
            'is_active'    => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $validated['sizes'] = $request->input('sizes', []);
        $validated['colors'] = $request->input('colors', []);
        $validated['sablon_types'] = $request->input('sablon_types', []);
        $validated['sleeve_types'] = $request->input('sleeve_types', []);

        Product::create($validated);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Product added successfully!');
    }

    public function edit(Product $produk)
    {
        $categories  = Category::active()->get();
        $sablonTypes = ['No Printing (Plain)','Manual Screen Print','DTF Print','Plastisol Print','Rubber Print','Discharge Print','Embroidery'];
        $sizes       = ['XS','S','M','L','XL','XXL','XXXL'];
        $colors      = ['Black', 'White', 'Navy', 'Maroon', 'Grey', 'Army Green', 'Yellow', 'Red'];
        $sleeveTypes = ['Short Sleeve', 'Long Sleeve', '3/4 Sleeve'];
        return view('admin.products.edit', compact('produk', 'categories', 'sablonTypes', 'sizes', 'colors', 'sleeveTypes'));
    }

    public function update(Request $request, Product $produk)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:200',
            'description'  => 'required|string',
            'material'     => 'nullable|string|max:200',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'min_order'    => 'required|integer|min:1',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'sizes'        => 'nullable|array',
            'colors'       => 'nullable|array',
            'sablon_types' => 'nullable|array',
            'sleeve_types' => 'nullable|array',
            'is_active'    => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $validated['sizes'] = $request->input('sizes', []);
        $validated['colors'] = $request->input('colors', []);
        $validated['sablon_types'] = $request->input('sablon_types', []);
        $validated['sleeve_types'] = $request->input('sleeve_types', []);

        $produk->update($validated);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $produk)
    {
        $produk->delete();
        return redirect()->route('admin.produk.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:products,id'
        ]);

        foreach ($request->ids as $index => $id) {
            Product::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
