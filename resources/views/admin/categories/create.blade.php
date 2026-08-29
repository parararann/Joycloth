@extends('layouts.admin')
@section('title', 'Add Category')
@section('page-title', 'Add Category')

@section('content')
<div class="max-w-xl">
<form method="POST" action="{{ route('admin.kategori.store') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    <div class="card-flat p-6 space-y-5">
        <div>
            <label class="form-label">Category Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Example: T-shirt, Jacket...">
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-input resize-none">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="form-label">Category Image</label>
            <input type="file" name="image" accept="image/*" class="form-input file:mr-4 file:py-2 file:px-4 file:border-2 file:border-dark-950 file:bg-primary-400 file:text-dark-950 file:font-black shadow-brutal-sm">
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-6 h-6 border-2 border-dark-950 text-primary-500 focus:ring-0">
            <label for="is_active" class="text-dark-950 font-extrabold uppercase text-sm">Active Category</label>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Save</button>
        <a href="{{ route('admin.kategori.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
</div>
@endsection
