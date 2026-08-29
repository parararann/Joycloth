@extends('layouts.admin')
@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-3xl">
<form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="card-flat p-6 space-y-5">
        <h3 class="text-dark-950 font-extrabold uppercase tracking-wide">Product Information</h3>

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Category *</label>
                <select name="category_id" required class="form-select">
                    <option value="">-- Select --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Price (Rp/pcs) *</label>
                <input type="number" name="price" value="{{ old('price') }}" min="0" required class="form-input">
            </div>
            <div>
                <label class="form-label">Min. Order (pcs) *</label>
                <input type="number" name="min_order" value="{{ old('min_order', 12) }}" min="1" required class="form-input">
            </div>
            <div>
                <label class="form-label">Stock (pcs) *</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required class="form-input">
            </div>
            <div>
                <label class="form-label">Material</label>
                <input type="text" name="material" value="{{ old('material') }}" class="form-input" placeholder="Cotton Combed 30s...">
            </div>
            <div class="col-span-2">
                <label class="form-label">Description *</label>
                <textarea name="description" rows="4" required class="form-input resize-none">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div class="card-flat p-6 space-y-5">
        <h3 class="text-dark-950 font-extrabold uppercase tracking-wide">Images & Variants</h3>

        {{-- Upload Image --}}
        <div>
            <label class="form-label">Product Image</label>
            <input type="file" name="image" accept="image/*" class="form-input file:mr-4 file:py-2 file:px-4 file:border-2 file:border-dark-950 file:bg-primary-400 file:text-dark-950 file:font-black shadow-brutal-sm">
        </div>

        {{-- Ukuran --}}
        <div>
            <label class="form-label">Available Sizes</label>
            <div class="flex flex-wrap gap-3">
                @foreach($sizes as $size)
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="sizes[]" value="{{ $size }}"
                           {{ in_array($size, old('sizes', ['S','M','L','XL','XXL'])) ? 'checked':'' }}
                           class="w-5 h-5 border-2 border-dark-950 text-primary-500 focus:ring-0">
                    <span class="text-dark-900 font-bold text-sm uppercase">{{ $size }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Warna --}}
        <div>
            <label class="form-label">Available Colors</label>
            <div class="flex flex-wrap gap-3">
                @foreach($colors as $color)
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="colors[]" value="{{ $color }}"
                           {{ in_array($color, old('colors', $colors)) ? 'checked':'' }}
                           class="w-5 h-5 border-2 border-dark-950 text-primary-500 focus:ring-0">
                    <span class="text-dark-900 font-bold text-sm uppercase">{{ $color }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Jenis Sablon --}}
        <div>
            <label class="form-label">Available Printing Types</label>
            <div class="flex flex-wrap gap-3">
                @foreach($sablonTypes as $type)
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="sablon_types[]" value="{{ $type }}"
                           {{ in_array($type, old('sablon_types', $sablonTypes)) ? 'checked':'' }}
                           class="w-5 h-5 border-2 border-dark-950 text-primary-500 focus:ring-0">
                    <span class="text-dark-900 font-bold text-sm uppercase">{{ $type }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Jenis Lengan --}}
        <div>
            <label class="form-label">Available Sleeve Types</label>
            <div class="flex flex-wrap gap-3">
                @foreach($sleeveTypes as $type)
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="sleeve_types[]" value="{{ $type }}"
                           {{ in_array($type, old('sleeve_types', $sleeveTypes)) ? 'checked':'' }}
                           class="w-5 h-5 border-2 border-dark-950 text-primary-500 focus:ring-0">
                    <span class="text-dark-900 font-bold text-sm uppercase">{{ $type }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Status --}}
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked':'' }} class="w-6 h-6 border-2 border-dark-950 text-primary-500 focus:ring-0">
            <label for="is_active" class="text-dark-950 font-extrabold uppercase text-sm">Active Product (show in catalog)</label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Save Product</button>
        <a href="{{ route('admin.produk.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
</div>
@endsection
