@extends('layouts.app')
@section('title', 'About Us')

@section('content')
<div class="pt-24 pb-16 hero-gradient relative overflow-hidden">
    <div class="hero-glow absolute inset-0"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-dark-950 mb-6 animate-slide-up uppercase tracking-tighter">About <span class="text-accent underline decoration-8 underline-offset-4">Joycloth</span></h1>
        <p class="text-dark-800 max-w-2xl mx-auto text-lg md:text-xl font-bold animate-slide-up" style="animation-delay: 0.1s">
            Get to know Joycloth better, a trusted screen printing and streetwear convection service for your brand and community needs.
        </p>
    </div>
</div>

<div class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-dark-100 p-8 md:p-12">
            <h2 class="text-2xl font-display font-bold text-dark-900 mb-6">Our Story</h2>
            <div class="prose max-w-none text-dark-600 space-y-4">
                <p>
                    <strong>Joycloth</strong> started from a vision to simplify the screen printing and convection ordering process in the digital era with a strong streetwear aesthetic. We realized that local brands and communities often struggle to find trusted vendors who understand premium quality and current trends.
                </p>
                <p>
                    Therefore, we are here with an integrated, transparent, and very easy-to-use ordering information system. Through this platform, we not only sell high-quality custom apparel but also offer an <strong>efficient shopping and consultation experience</strong>.
                </p>
                
                <h3 class="text-xl font-bold text-dark-900 mt-8 mb-4">Why Choose Us?</h3>
                <ul class="space-y-3 list-disc pl-5">
                    <li><strong>Guaranteed Quality:</strong> Choice raw materials (such as Cotton Combed, Taslan, Fleece) and durable premium screen printing inks.</li>
                    <li><strong>Transparent System:</strong> You can track order progress (from design, production, to shipping) in real-time on the website.</li>
                    <li><strong>Easy Consultation:</strong> Integrated Live Chat feature allows you to discuss designs, prices, or order details directly with our admin without switching apps.</li>
                    <li><strong>Custom Flexibility:</strong> From single t-shirts, community uniforms, to large bulk orders (thousands of pcs), we handle them with high professionalism.</li>
                </ul>
                
                <h3 class="text-xl font-bold text-dark-900 mt-8 mb-4">Vision & Mission</h3>
                <p>
                    <strong>Our vision</strong> is to become the number one digital screen printing platform and convection vendor in Indonesia that bridges custom apparel needs with technology. 
                </p>
                <p>
                    <strong>Our mission</strong> includes maintaining customer satisfaction, innovating in order management technology, and empowering the local creative apparel industry.
                </p>
            </div>
            
            <div class="mt-12 pt-8 border-t border-dark-100 text-center">
                <h3 class="text-lg font-semibold text-dark-900 mb-4">Have questions or want to start creating your design?</h3>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('products.index') }}" class="btn-primary">View Catalog</a>
                    @auth
                    <a href="{{ route('chat.index') }}" class="btn-outline">Chat with Admin</a>
                    @else
                    <a href="{{ route('login') }}" class="btn-outline">Login to Chat</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
