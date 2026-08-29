<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Joycloth') - Streetwear & Custom Apparel</title>
    <meta name="description" content="@yield('description', 'Joycloth - High-quality screen printing and custom apparel services. Streetwear, t-shirts, jackets, and more.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white" x-data>

    {{-- ========== NAVBAR ========== --}}
    <nav id="navbar" class="navbar fixed top-0 inset-x-0 z-50 transition-all duration-300" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-9 h-9 bg-primary-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L2 7l8 5 8-5-8-5zM2 13l8 5 8-5M2 10l8 5 8-5"/>
                        </svg>
                    </div>
                    <span class="text-dark-950 font-display font-extrabold text-2xl uppercase tracking-tighter">JOY<span class="text-primary-600">CLOTH</span></span>
                </a>

                {{-- Nav Links (Desktop Only) --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 text-dark-950 hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all font-bold uppercase tracking-wide">Home</a>
                    <a href="{{ route('products.index') }}" class="px-4 py-2 text-dark-950 hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all font-bold uppercase tracking-wide">Catalog</a>
                    <a href="{{ route('designs.index') }}" class="px-4 py-2 text-dark-950 hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all font-bold uppercase tracking-wide">Designs</a>
                    <a href="{{ route('about') }}" class="px-4 py-2 text-dark-950 hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all font-bold uppercase tracking-wide">About</a>
                </div>

                {{-- Right Side Actions --}}
                <div class="flex items-center gap-2">
                    @auth
                        {{-- Cart Icon --}}
                        <a href="{{ route('cart.index') }}" class="relative p-2 text-dark-950 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @php $cartCount = count(session()->get('cart', [])); @endphp
                            @if($cartCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-primary-500 text-white text-xs rounded-full flex items-center justify-center font-bold">{{ $cartCount }}</span>
                            @endif
                        </a>

                        {{-- Chat Icon (Desktop) --}}
                        <a href="{{ route('chat.index') }}" class="hidden sm:block p-2 text-dark-950 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </a>

                        {{-- User Dropdown (Desktop) --}}
                        <div class="hidden md:block relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 text-dark-950 hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all font-bold">
                                <div class="w-7 h-7 bg-white rounded-none border-2 border-dark-950 flex items-center justify-center font-bold text-xs overflow-hidden shadow-brutal-sm">
                                    <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <span class="font-bold max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-xl shadow-xl py-2 z-50">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-dark-300 hover:text-white hover:bg-dark-700 transition-colors text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </a>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-dark-300 hover:text-white hover:bg-dark-700 transition-colors text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    My Orders
                                </a>
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-primary-400 hover:text-primary-300 hover:bg-dark-700 transition-colors text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    Admin Panel
                                </a>
                                @endif
                                <hr class="border-dark-700 my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-red-400 hover:text-red-300 hover:bg-dark-700 transition-colors text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Guest links desktop --}}
                        <a href="{{ route('login') }}" class="hidden md:block px-4 py-2 text-dark-950 hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all font-bold uppercase tracking-wide">Login</a>
                        <a href="{{ route('register') }}" class="hidden md:block btn-primary btn-sm uppercase tracking-wider">Register</a>
                    @endauth

                    {{-- Hamburger Button (Mobile) --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-dark-950 hover:bg-primary-300 border-2 border-dark-950 transition-all" aria-label="Toggle Menu">
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== MOBILE MENU DROPDOWN ===== --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             @click.outside="mobileOpen = false"
             class="md:hidden bg-white border-t-3 border-dark-950 shadow-brutal">

            {{-- Nav Links --}}
            <div class="px-4 pt-3 pb-2 space-y-1">
                <a href="{{ route('home') }}" @click="mobileOpen = false" class="block px-4 py-3 text-dark-950 font-bold uppercase hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all">Home</a>
                <a href="{{ route('products.index') }}" @click="mobileOpen = false" class="block px-4 py-3 text-dark-950 font-bold uppercase hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all">Catalog</a>
                <a href="{{ route('designs.index') }}" @click="mobileOpen = false" class="block px-4 py-3 text-dark-950 font-bold uppercase hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all">Designs</a>
                <a href="{{ route('about') }}" @click="mobileOpen = false" class="block px-4 py-3 text-dark-950 font-bold uppercase hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all">About</a>
            </div>

            {{-- Divider --}}
            <div class="border-t-2 border-dark-200 mx-4"></div>

            {{-- Auth Section --}}
            <div class="px-4 pt-2 pb-4 space-y-1">
                @auth
                    {{-- User Info --}}
                    <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 border-2 border-dark-200 mb-2">
                        <div class="w-10 h-10 border-2 border-dark-950 overflow-hidden flex-shrink-0">
                            <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-dark-950 text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-dark-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <a href="{{ route('chat.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-4 py-3 text-dark-950 font-bold hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Live Chat
                    </a>
                    <a href="{{ route('orders.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-4 py-3 text-dark-950 font-bold hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        My Orders
                    </a>
                    <a href="{{ route('profile.edit') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-4 py-3 text-dark-950 font-bold hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        My Profile
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-4 py-3 text-primary-600 font-bold hover:bg-primary-300 border-2 border-transparent hover:border-dark-950 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Admin Panel
                    </a>
                    @endif
                    <div class="border-t-2 border-dark-200 pt-2 mt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 font-bold hover:bg-red-50 border-2 border-transparent hover:border-red-300 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" @click="mobileOpen = false" class="block px-4 py-3 text-center text-dark-950 font-bold uppercase border-2 border-dark-950 hover:bg-primary-300 transition-all">Login</a>
                    <a href="{{ route('register') }}" @click="mobileOpen = false" class="block px-4 py-3 text-center bg-primary-500 text-white font-bold uppercase border-2 border-dark-950 hover:bg-primary-600 transition-all shadow-brutal">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ========== FLASH NOTIFICATIONS ========== --}}
    @if(session('success') || session('error') || session('warning'))
    <div class="fixed top-20 right-4 z-50 space-y-2 animate-slide-up">
        @if(session('success'))
        <div class="alert-success shadow-lg max-w-sm" x-data="{ show: true }" x-show="show" x-transition>
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert-danger shadow-lg max-w-sm" x-data="{ show: true }" x-show="show" x-transition>
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
        </div>
        @endif
    </div>
    @endif

    {{-- ========== MAIN CONTENT ========== --}}
    <main class="pt-16">
        @yield('content')
    </main>

    {{-- ========== FOOTER ========== --}}
    <footer class="bg-dark-900 text-dark-300 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- Brand --}}
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 bg-primary-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2L2 7l8 5 8-5-8-5z"/></svg>
                        </div>
                        <span class="text-white font-display font-extrabold text-3xl uppercase tracking-tighter">JOY<span class="text-primary-500">CLOTH</span></span>
                    </div>
                    <p class="text-dark-400 leading-relaxed mb-6 max-w-sm">
                        High-quality custom screen printing and convection services. We provide custom orders for t-shirts, jackets, sweaters, jerseys, and other custom apparel.
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2"><svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg><span>Ruko Marakas Square CC7/11, Taman Wisata No.11, Bahagia, Babelan, Bekasi Regency, West Java 17610</span></div>
                        <div class="flex items-center gap-2"><svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><span>muhhfar2501@gmail.com</span></div>
                    </div>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-primary-400 transition-colors">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-primary-400 transition-colors">Product Catalog</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-primary-400 transition-colors">About Us</a></li>
                        @auth
                        <li><a href="{{ route('orders.index') }}" class="hover:text-primary-400 transition-colors">My Orders</a></li>
                        <li><a href="{{ route('chat.index') }}" class="hover:text-primary-400 transition-colors">Live Chat</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Rekening Bank --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Payment</h4>
                    <div class="bg-dark-800 rounded-xl p-4 text-sm space-y-2">
                        <p class="text-dark-400 text-xs uppercase tracking-wider font-semibold mb-3">Official Account</p>
                        <div>
                            <p class="text-white font-semibold">Bank BCA</p>
                            <p class="text-primary-400 font-mono text-lg">6631361118</p>
                            <p class="text-dark-400">Account Holder: Muhammad Farhan</p>
                        </div>
                    </div>
                    <p class="text-xs text-dark-500 mt-3">Payment via bank transfer. Upload payment proof in the order page.</p>
                </div>
            </div>

            <div class="border-t border-dark-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-dark-600 text-sm font-medium">&copy; {{ date('Y') }} Joycloth. All rights reserved.</p>
                <p class="text-dark-600 text-xs">Made with ❤️ for Indonesian SMEs</p>
            </div>
        </div>
    </footer>

    {{-- Mobile menu script --}}
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>

    {{-- ========== FLOATING CHAT SHORTCUT ========== --}}
    @auth
    @if(!request()->routeIs('chat.index'))
    <a href="{{ route('chat.index') }}" 
       class="fixed bottom-6 right-6 z-[60] group flex items-center gap-3 animate-slide-up">
        {{-- Notification Tooltip --}}
        <div class="bg-dark-950 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 border-2 border-dark-950 shadow-brutal-sm opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0 hidden sm:block">
            Chat with us
        </div>
        {{-- Floating Bubble --}}
        <div class="w-14 h-14 bg-primary-400 border-3 border-dark-950 shadow-brutal flex items-center justify-center group-hover:-translate-y-1 group-hover:shadow-brutal-lg transition-all active:translate-y-0 active:shadow-brutal">
            <svg class="w-7 h-7 text-dark-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            @php $unreadCount = \App\Models\ChatMessage::forUser(auth()->id())->where('sender_type', 'admin')->unread()->count(); @endphp
            @if($unreadCount > 0)
            <div class="absolute -top-2 -left-2 w-6 h-6 bg-[#FF0000] border-2 border-dark-950 text-dark-950 text-[10px] font-black flex items-center justify-center animate-bounce shadow-brutal-sm">
                {{ $unreadCount }}
            </div>
            @endif
        </div>
    </a>
    @endif
    @endauth

    @stack('scripts')
</body>
</html>
