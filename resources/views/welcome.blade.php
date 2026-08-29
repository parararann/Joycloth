@extends('layouts.app')
@section('title', 'Home')

@section('content')
{{-- HERO SECTION --}}
<section class="hero-gradient relative border-b-3 border-dark-950 overflow-hidden min-h-[90vh] flex items-center">
    <!-- Decorative Marquee -->
    <div class="absolute top-0 w-full bg-accent border-b-3 border-dark-950 py-2 z-10 overflow-hidden transform -rotate-2 scale-110 translate-y-8">
        <div class="animate-marquee font-display font-bold text-dark-950 text-xl tracking-widest uppercase">
            &nbsp;JOYCLOTH STUDIO • STREETWEAR & CUSTOM APPAREL • PREMIUM QUALITY • BOLD DESIGN • JOYCLOTH STUDIO • STREETWEAR & CUSTOM APPAREL • PREMIUM QUALITY • BOLD DESIGN •
        </div>
    </div>
    
    <div class="container mx-auto px-4 lg:px-8 relative z-20 mt-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="max-w-2xl animate-slide-up">
                <div class="inline-block px-4 py-2 bg-primary-300 border-2 border-dark-950 shadow-brutal-sm font-bold uppercase tracking-widest text-sm mb-6 transform -rotate-2">
                    🔥 New Drop Available
                </div>
                <h1 class="text-6xl md:text-8xl font-display font-extrabold text-dark-950 leading-[0.9] uppercase tracking-tighter mb-6">
                    WEAR<br>YOUR<br><span class="text-accent underline decoration-8 underline-offset-4">VIBE</span>
                </h1>
                <p class="text-xl md:text-2xl text-dark-800 font-medium mb-10 max-w-xl">
                    Custom apparel and streetwear vendor for those who dare to stand out. Let's make something sick.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="btn-primary text-lg px-8 py-4">Explore Catalog</a>
                    @auth
                    <a href="{{ route('chat.index') }}" class="btn-secondary text-lg px-8 py-4">Custom Order</a>
                    @else
                    <a href="{{ route('login') }}" class="btn-secondary text-lg px-8 py-4">Join Now</a>
                    @endauth
                </div>
            </div>
            
            <div class="relative hidden lg:block animate-fade-in" style="animation-delay: 0.2s">
                <!-- Neobrutalist Image Container -->
                <div class="relative z-10 bg-white p-4 border-3 border-dark-950 shadow-brutal-lg transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Streetwear Fashion" class="w-full h-auto border-2 border-dark-950 filter contrast-125 saturate-150">
                    <div class="absolute -bottom-6 -left-6 bg-primary-400 border-3 border-dark-950 shadow-brutal px-6 py-3 font-display font-bold text-2xl uppercase transform -rotate-6">
                        Est. 2024
                    </div>
                </div>
                <!-- Abstract decorations -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent rounded-full border-3 border-dark-950 -z-10 animate-pulse-slow"></div>
                <div class="absolute -bottom-10 -right-4 w-24 h-24 bg-blue-500 rounded-none border-3 border-dark-950 -z-10 transform rotate-45"></div>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="py-24 bg-white border-b-3 border-dark-950">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="section-title">Collections</h2>
            <p class="section-subtitle">Find your perfect fit</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $categoryStyles = [
                    't-shirt' => [
                        'color' => 'bg-primary-400',
                        'icon' => '👕',
                        'label' => 'T-Shirts',
                        'rotation' => 'rotate-1'
                    ],
                    'jacket' => [
                        'color' => 'bg-blue-400',
                        'icon' => '🧥',
                        'label' => 'Outerwear',
                        'rotation' => '-rotate-2'
                    ],
                    'jersey' => [
                        'color' => 'bg-accent',
                        'icon' => '⚽',
                        'label' => 'Sportswear',
                        'rotation' => 'rotate-2'
                    ],
                    'totebag' => [
                        'color' => 'bg-amber-400',
                        'icon' => '👜',
                        'label' => 'Accessories',
                        'rotation' => '-rotate-1'
                    ]
                ];
            @endphp

            @forelse($categories ?? [] as $category)
                @php 
                    $style = $categoryStyles[$category->slug] ?? [
                        'color' => 'bg-dark-100',
                        'icon' => '📦',
                        'label' => 'Collection',
                        'rotation' => 'rotate-0'
                    ];
                @endphp
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group block relative">
                    {{-- Decorative Shadow --}}
                    <div class="absolute inset-0 bg-dark-950 translate-x-3 translate-y-3"></div>
                    
                    {{-- Main Card --}}
                    <div class="{{ $style['color'] }} border-3 border-dark-950 p-8 h-full flex flex-col relative z-10 transition-all duration-200 group-hover:-translate-y-2 group-hover:-translate-x-1 {{ $style['rotation'] }}">
                        <div class="flex justify-between items-start mb-12">
                            <div class="w-16 h-16 bg-white border-3 border-dark-950 shadow-brutal-sm flex items-center justify-center text-4xl">
                                {{ $style['icon'] }}
                            </div>
                            <div class="px-3 py-1 bg-dark-950 text-white text-[10px] font-black uppercase tracking-widest">
                                Premium
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <p class="text-dark-950 font-black text-xs uppercase tracking-[0.2em] mb-1 opacity-70">{{ $style['label'] }}</p>
                            <h3 class="font-display font-black text-3xl uppercase tracking-tighter text-dark-950 leading-none">{{ $category->name }}</h3>
                        </div>

                        {{-- Hover Arrow --}}
                        <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-8 h-8 text-dark-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12 border-3 border-dashed border-dark-950 bg-[#f4f4f0]">
                    <p class="text-dark-600 font-bold uppercase text-xl">No categories yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-24 bg-dark-900 text-white border-b-3 border-dark-950">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-accent text-white border-2 border-dark-950 shadow-brutal-sm font-bold uppercase tracking-widest text-sm mb-6 transform rotate-2">
                PROCESS
            </div>
            <h2 class="text-5xl md:text-6xl font-display font-extrabold uppercase tracking-tighter">How We Do It</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white text-dark-950 border-3 border-dark-950 shadow-brutal p-8 transform -rotate-1 hover:rotate-0 transition-transform">
                <div class="w-16 h-16 bg-primary-400 border-2 border-dark-950 shadow-brutal-sm flex items-center justify-center text-3xl font-display font-black mb-6">1</div>
                <h3 class="text-2xl font-display font-bold uppercase tracking-wide mb-4">Pick a Blank</h3>
                <p class="font-medium text-dark-700">Choose from our premium catalog of heavy-weight tees, hoodies, or streetwear blanks.</p>
            </div>
            
            <div class="bg-white text-dark-950 border-3 border-dark-950 shadow-brutal p-8 transform rotate-1 hover:rotate-0 transition-transform">
                <div class="w-16 h-16 bg-blue-400 border-2 border-dark-950 shadow-brutal-sm flex items-center justify-center text-3xl font-display font-black mb-6">2</div>
                <h3 class="text-2xl font-display font-bold uppercase tracking-wide mb-4">Send Design</h3>
                <p class="font-medium text-dark-700">Hit us up in the chat, send your graphics, and let's discuss print methods (Plastisol, DTF, Embroidery).</p>
            </div>
            
            <div class="bg-white text-dark-950 border-3 border-dark-950 shadow-brutal p-8 transform -rotate-1 hover:rotate-0 transition-transform">
                <div class="w-16 h-16 bg-accent border-2 border-dark-950 shadow-brutal-sm text-white flex items-center justify-center text-3xl font-display font-black mb-6">3</div>
                <h3 class="text-2xl font-display font-bold uppercase tracking-wide mb-4">We Print & Ship</h3>
                <p class="font-medium text-dark-700">We produce your gear with sick quality control and ship it straight to your door.</p>
            </div>
        </div>
    </div>
</section>

{{-- CALL TO ACTION --}}
<section class="py-24 bg-primary-400 border-b-3 border-dark-950 text-center relative overflow-hidden">
    <div class="absolute top-10 left-10 text-9xl opacity-10 font-display font-black transform -rotate-12 select-none">JOYCLOTH</div>
    <div class="absolute bottom-10 right-10 text-9xl opacity-10 font-display font-black transform rotate-12 select-none">STUDIO</div>
    
    <div class="container mx-auto px-4 relative z-10">
        <h2 class="text-5xl md:text-7xl font-display font-extrabold text-dark-950 uppercase tracking-tighter mb-8">Ready to flex?</h2>
        <a href="{{ route('chat.index') }}" class="btn-secondary text-2xl px-12 py-6 hover:bg-accent hover:text-white inline-block">
            Start a Project
        </a>
    </div>
</section>
@endsection
