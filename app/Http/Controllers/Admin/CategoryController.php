<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order', 'asc')->orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        $data          = $request->only(['name', 'description']);
        $data['slug']  = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Category added successfully!');
    }

    public function edit(Category $kategori)
    {
        return view('admin.categories.edit', compact('kategori'));
    }

    public function update(Request $request, Category $kategori)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $kategori->id,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        $data              = $request->only(['name', 'description']);
        $data['slug']      = Str::slug($request->name);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.kategori.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:categories,id'
        ]);

        foreach ($request->ids as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
