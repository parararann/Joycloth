@extends('layouts.admin')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category: ' . $kategori->name)

@section('content')
<div class="max-w-xl">
<form method="POST" action="{{ route('admin.kategori.update', $kategori) }}" enctype="multipart/form-data" class="space-y-5">
    @csrf @method('PUT')
    <div class="card-flat p-6 space-y-5">
        <div>
            <label class="form-label">Category Name *</label>
            <input type="text" name="name" value="{{ old('name', $kategori->name) }}" required class="form-input">
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-input resize-none">{{ old('description', $kategori->description) }}</textarea>
        </div>
        @if($kategori->image)
        <div>
            <p class="form-label">Current Image</p>
            <img src="{{ asset('storage/'.$kategori->image) }}" class="w-24 h-24 rounded-xl object-cover border-2 border-dark-950 shadow-brutal-sm">
        </div>
        @endif
        <div>
            <label class="form-label">Change Image</label>
            <input type="file" name="image" accept="image/*" class="form-input file:mr-4 file:py-2 file:px-4 file:border-2 file:border-dark-950 file:bg-primary-400 file:text-dark-950 file:font-black shadow-brutal-sm">
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ $kategori->is_active ? 'checked':'' }} class="w-6 h-6 border-2 border-dark-950 text-primary-500 focus:ring-0">
            <label for="is_active" class="text-dark-950 font-extrabold uppercase text-sm">Active Category</label>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Save Changes</button>
        <a href="{{ route('admin.kategori.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
</div>
@endsection
