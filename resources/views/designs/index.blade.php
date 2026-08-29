@extends('layouts.app')
@section('title', 'Design References')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-extrabold text-dark-950 uppercase tracking-tighter mb-4">DESIGN <span class="text-primary-600">REFERENCES</span></h1>
        <p class="text-dark-600 max-w-2xl mx-auto text-lg">Choose from our collection of exclusive designs for printing, or use them as inspiration for your own custom designs.</p>
    </div>

    @if($designs->isEmpty())
        <div class="card-flat p-16 text-center max-w-2xl mx-auto">
            <div class="text-6xl mb-4">🎨</div>
            <h3 class="text-dark-950 font-bold text-xl mb-2">No Designs Yet</h3>
            <p class="text-dark-500">The design reference collection is currently being prepared. Coming soon!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($designs as $design)
            <div class="card-flat group relative overflow-hidden flex flex-col h-full hover:-translate-y-2 hover:shadow-brutal-lg transition-all duration-300">
                <div class="h-80 bg-white p-6 flex items-center justify-center border-b-3 border-dark-950 overflow-hidden">
                    <img src="{{ $design->image_url }}" alt="{{ $design->title }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-display font-black text-xl text-dark-950 mb-2 uppercase tracking-wide">{{ $design->title }}</h3>
                    <p class="text-dark-500 text-sm flex-1">{{ $design->description }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            {{ $designs->links() }}
        </div>
    @endif
</div>
@endsection
