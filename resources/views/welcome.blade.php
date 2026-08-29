@extends('layouts.app')
@section('title', 'Home')

@section('content')
{{-- HERO SECTION --}}
<section class="hero-gradient relative border-b-2 sm:border-b-3 border-dark-950 overflow-hidden py-12 sm:py-16 lg:py-24 flex items-center">
    <!-- Decorative Marquee -->
    <div class="absolute top-0 left-[-5%] w-[110%] bg-accent border-b-2 sm:border-b-3 border-dark-950 py-1.5 sm:py-2 z-10 overflow-hidden transform -rotate-2 translate-y-4 sm:translate-y-8">
        <div class="animate-marquee font-display font-bold text-dark-950 text-xs sm:text-base md:text-xl tracking-widest uppercase">
            &nbsp;JOYCLOTH STUDIO • STREETWEAR & CUSTOM APPAREL • PREMIUM QUALITY • BOLD DESIGN • JOYCLOTH STUDIO • STREETWEAR & CUSTOM APPAREL • PREMIUM QUALITY • BOLD DESIGN •
        </div>
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-20 mt-8 sm:mt-12 lg:mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
            
            {{-- Top on Mobile / Left on Desktop: Text & Actions --}}
            <div class="max-w-2xl animate-slide-up">
                <div class="inline-block px-3 sm:px-4 py-1.5 sm:py-2 bg-primary-300 border-2 border-dark-950 shadow-brutal-sm font-bold uppercase tracking-widest text-xs sm:text-sm mb-4 sm:mb-6 transform -rotate-2">
                    🔥 New Drop Available
                </div>
                <h1 class="text-5xl sm:text-7xl lg:text-8xl font-display font-extrabold text-dark-950 leading-[0.92] uppercase tracking-tighter mb-4 sm:mb-6">
                    WEAR<br>YOUR<br><span class="text-accent underline decoration-4 sm:decoration-8 underline-offset-4">VIBE</span>
                </h1>
                <p class="text-base sm:text-xl text-dark-800 font-medium mb-6 sm:mb-8 max-w-xl">
                    Custom apparel and streetwear vendor for those who dare to stand out. Let's make something sick.
                </p>
                <div class="flex flex-wrap gap-3 sm:gap-4">
                    <a href="{{ route('products.index') }}" class="btn-primary text-sm sm:text-base px-6 sm:px-8 py-3 sm:py-4">Explore Catalog</a>
                    @auth
                    <a href="{{ route('chat.index') }}" class="btn-secondary text-sm sm:text-base px-6 sm:px-8 py-3 sm:py-4">Custom Order</a>
                    @else
                    <a href="{{ route('login') }}" class="btn-secondary text-sm sm:text-base px-6 sm:px-8 py-3 sm:py-4">Join Now</a>
                    @endauth
                </div>
            </div>
            
            {{-- Bottom on Mobile / Right on Desktop: Image Container --}}
            <div class="relative block max-w-xs sm:max-w-md mx-auto lg:max-w-none w-full animate-fade-in pt-4 sm:pt-0" style="animation-delay: 0.2s">
                <!-- Neobrutalist Image Container -->
                <div class="relative z-10 bg-white p-3 sm:p-4 border-2 sm:border-3 border-dark-950 shadow-brutal sm:shadow-brutal-lg transform rotate-2 sm:rotate-3 hover:rotate-0 transition-transform duration-300">
                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Streetwear Fashion" class="w-full h-56 sm:h-72 lg:h-auto object-cover border-2 border-dark-950 filter contrast-125 saturate-150">
                    <div class="absolute -bottom-4 sm:-bottom-6 -left-3 sm:-left-6 bg-primary-400 border-2 sm:border-3 border-dark-950 shadow-brutal-sm sm:shadow-brutal px-3 sm:px-6 py-1.5 sm:py-3 font-display font-bold text-sm sm:text-2xl uppercase transform -rotate-6">
                        Est. 2024
                    </div>
                </div>
                <!-- Abstract decorations -->
                <div class="absolute -top-4 -right-4 sm:-top-8 sm:-right-8 w-16 h-16 sm:w-28 sm:h-28 bg-accent rounded-full border-2 sm:border-3 border-dark-950 -z-10 animate-pulse-slow"></div>
                <div class="absolute -bottom-4 -right-2 sm:-bottom-8 sm:-right-4 w-12 h-12 sm:w-20 sm:h-20 bg-blue-500 rounded-none border-2 sm:border-3 border-dark-950 -z-10 transform rotate-45"></div>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="py-12 sm:py-20 bg-white border-b-2 sm:border-b-3 border-dark-950">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="text-center mb-10 sm:mb-14">
            <h2 class="section-title">Collections</h2>
            <p class="section-subtitle">Find your perfect fit</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
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
                    <div class="absolute inset-0 bg-dark-950 translate-x-2 sm:translate-x-3 translate-y-2 sm:translate-y-3"></div>
                    
                    {{-- Main Card --}}
                    <div class="{{ $style['color'] }} border-2 sm:border-3 border-dark-950 p-6 sm:p-8 h-full flex flex-col relative z-10 transition-all duration-200 group-hover:-translate-y-1 sm:group-hover:-translate-y-2 {{ $style['rotation'] }}">
                        <div class="flex justify-between items-start mb-8 sm:mb-12">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white border-2 sm:border-3 border-dark-950 shadow-brutal-sm flex items-center justify-center text-2xl sm:text-4xl">
                                {{ $style['icon'] }}
                            </div>
                            <div class="px-2.5 sm:px-3 py-1 bg-dark-950 text-white text-[10px] font-black uppercase tracking-widest">
                                Premium
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <p class="text-dark-950 font-black text-[10px] sm:text-xs uppercase tracking-[0.2em] mb-1 opacity-70">{{ $style['label'] }}</p>
                            <h3 class="font-display font-black text-2xl sm:text-3xl uppercase tracking-tighter text-dark-950 leading-none">{{ $category->name }}</h3>
                        </div>

                        {{-- Hover Arrow --}}
                        <div class="absolute bottom-4 sm:bottom-6 right-4 sm:right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-dark-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12 border-2 sm:border-3 border-dashed border-dark-950 bg-[#f4f4f0]">
                    <p class="text-dark-600 font-bold uppercase text-lg sm:text-xl">No categories yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-12 sm:py-20 bg-dark-900 text-white border-b-2 sm:border-b-3 border-dark-950">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="text-center mb-10 sm:mb-14">
            <div class="inline-block px-3 sm:px-4 py-1.5 sm:py-2 bg-accent text-white border-2 border-dark-950 shadow-brutal-sm font-bold uppercase tracking-widest text-xs sm:text-sm mb-4 transform rotate-2">
                PROCESS
            </div>
            <h2 class="text-3xl sm:text-5xl md:text-6xl font-display font-extrabold uppercase tracking-tighter">How We Do It</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <div class="bg-white text-dark-950 border-2 sm:border-3 border-dark-950 shadow-brutal-sm sm:shadow-brutal p-6 sm:p-8">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-primary-400 border-2 border-dark-950 shadow-brutal-sm flex items-center justify-center text-2xl sm:text-3xl font-display font-black mb-4 sm:mb-6">1</div>
                <h3 class="text-xl sm:text-2xl font-display font-bold uppercase tracking-wide mb-2 sm:mb-4">Pick a Blank</h3>
                <p class="text-xs sm:text-sm font-medium text-dark-700">Choose from our premium catalog of heavy-weight tees, hoodies, or streetwear blanks.</p>
            </div>
            
            <div class="bg-white text-dark-950 border-2 sm:border-3 border-dark-950 shadow-brutal-sm sm:shadow-brutal p-6 sm:p-8">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-400 border-2 border-dark-950 shadow-brutal-sm flex items-center justify-center text-2xl sm:text-3xl font-display font-black mb-4 sm:mb-6">2</div>
                <h3 class="text-xl sm:text-2xl font-display font-bold uppercase tracking-wide mb-2 sm:mb-4">Send Design</h3>
                <p class="text-xs sm:text-sm font-medium text-dark-700">Hit us up in the chat, send your graphics, and let's discuss print methods (Plastisol, DTF, Embroidery).</p>
            </div>
            
            <div class="bg-white text-dark-950 border-2 sm:border-3 border-dark-950 shadow-brutal-sm sm:shadow-brutal p-6 sm:p-8">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-accent border-2 border-dark-950 shadow-brutal-sm text-white flex items-center justify-center text-2xl sm:text-3xl font-display font-black mb-4 sm:mb-6">3</div>
                <h3 class="text-xl sm:text-2xl font-display font-bold uppercase tracking-wide mb-2 sm:mb-4">We Print & Ship</h3>
                <p class="text-xs sm:text-sm font-medium text-dark-700">We produce your gear with sick quality control and ship it straight to your door.</p>
            </div>
        </div>
    </div>
</section>

{{-- CALL TO ACTION --}}
<section class="py-12 sm:py-20 bg-primary-400 border-b-2 sm:border-b-3 border-dark-950 text-center relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <h2 class="text-3xl sm:text-5xl md:text-7xl font-display font-extrabold text-dark-950 uppercase tracking-tighter mb-6 sm:mb-8">Ready to flex?</h2>
        <a href="{{ route('chat.index') }}" class="btn-secondary text-base sm:text-xl px-8 sm:px-12 py-3.5 sm:py-5 hover:bg-accent hover:text-white inline-block">
            Start a Project
        </a>
    </div>
</section>
@endsection
