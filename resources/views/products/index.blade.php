@extends('layouts.app')
@section('title', 'Product Catalog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="section-title">Product Catalog</h1>
        <p class="section-subtitle">{{ $products->total() }} products found</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ====== SIDEBAR FILTER ====== --}}
        <aside class="lg:w-64 flex-shrink-0">
            <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                <div class="card-flat p-5 space-y-6">
                    <h3 class="font-display font-bold text-dark-900">Filter</h3>

                    {{-- Search --}}
                    <div>
                        <label class="form-label">Search Products</label>
                        <input type="text" name="cari" value="{{ request('cari') }}"
                               placeholder="Product name..."
                               class="form-input">
                    </div>

                    {{-- Categories --}}
                    <div>
                        <label class="form-label">Category</label>
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('products.index') }}" 
                               class="flex-1 lg:flex-none flex items-center justify-center lg:justify-between px-4 py-3 border-3 {{ !request('kategori') ? 'bg-primary-500 border-dark-950 shadow-brutal-sm text-dark-950 scale-[1.02]' : 'bg-white border-dark-950 text-dark-600 hover:bg-primary-100' }} transition-all text-xs font-black uppercase tracking-widest">
                                <span>ALL</span>
                            </a>
                            @foreach($categories as $cat)
                            <a href="{{ route('products.index', ['kategori' => $cat->slug, 'cari' => request('cari'), 'urut' => request('urut')]) }}" 
                               class="flex-1 lg:flex-none flex items-center justify-center px-4 py-3 border-3 {{ request('kategori') === $cat->slug ? 'bg-primary-500 border-dark-950 shadow-brutal-sm text-dark-950 scale-[1.02]' : 'bg-white border-dark-950 text-dark-600 hover:bg-primary-100' }} transition-all text-xs font-black uppercase tracking-widest">
                                <span>{{ $cat->name }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label class="form-label">Sort By</label>
                        <select name="urut" class="form-select" onchange="document.getElementById('filter-form').submit()">
                            <option value="terbaru" {{ request('urut','terbaru')==='terbaru' ? 'selected':'' }}>Newest</option>
                            <option value="termurah" {{ request('urut')==='termurah' ? 'selected':'' }}>Lowest Price</option>
                            <option value="termahal" {{ request('urut')==='termahal' ? 'selected':'' }}>Highest Price</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary w-full">Apply Filter</button>

                    @if(request()->hasAny(['cari','kategori','urut']))
                    <a href="{{ route('products.index') }}" class="block text-center text-sm text-dark-400 hover:text-dark-700 transition-colors">Reset Filter</a>
                    @endif
                </div>
            </form>
        </aside>

        {{-- ====== PRODUCT GRID ====== --}}
        <div class="flex-1">
            @if($products->isEmpty())
                <div class="card-flat p-16 text-center">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-dark-700 font-semibold text-lg mb-2">No products found</h3>
                    <p class="text-dark-400 text-sm">Try adjusting your filter or search keywords</p>
                    <a href="{{ route('products.index') }}" class="btn-outline btn-sm mt-6 inline-flex">Reset Search</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="product-card group">
                        <div class="overflow-hidden relative">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                <span class="badge badge-primary text-[10px]">{{ $product->category->name }}</span>
                                @if($product->stock <= 0)
                                    <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 font-bold uppercase shadow-brutal-sm border-2 border-dark-950">Out of Stock</span>
                                @else
                                    <span class="bg-accent text-white text-[10px] px-2 py-0.5 font-bold uppercase shadow-brutal-sm border-2 border-dark-950">Stock: {{ $product->stock }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-display font-semibold text-dark-900 group-hover:text-primary-600 transition-colors mb-1">{{ $product->name }}</h3>
                            <p class="text-dark-400 text-sm line-clamp-2 mb-3">{{ $product->description }}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-primary-600 font-bold font-display">{{ $product->formatted_price }}</p>
                                    <p class="text-xs text-dark-400">Min. {{ $product->min_order }} pcs</p>
                                </div>
                                <div class="px-3 py-1.5 bg-primary-50 text-primary-600 rounded-lg text-xs font-semibold">
                                    Order →
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $products->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
