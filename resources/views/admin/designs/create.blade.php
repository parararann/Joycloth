@extends('layouts.admin')
@section('title', 'Add Design Reference')
@section('page-title', 'Add Design Reference')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.desain.index') }}" class="btn-outline btn-sm inline-flex">← Back</a>
</div>

<div class="card-flat p-6 max-w-2xl">
    <form action="{{ route('admin.desain.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <label class="form-label">Design Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="form-input">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="form-label">Design Image</label>
            <input type="file" name="image" required accept="image/*" class="w-full text-sm text-dark-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-2 file:border-dark-950 file:text-sm file:font-bold file:bg-primary-300 file:text-dark-950 hover:file:bg-primary-400">
            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="form-label">Description / Design Style</label>
            <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 border-2 border-dark-950 text-primary-500 focus:ring-0 rounded-none shadow-brutal-sm">
                <span class="font-bold text-dark-900">Activate This Design (Show on Frontend)</span>
            </label>
        </div>

        <div class="pt-4 border-t-2 border-dark-950">
            <button type="submit" class="btn-primary">Save Design</button>
        </div>
    </form>
</div>
@endsection
