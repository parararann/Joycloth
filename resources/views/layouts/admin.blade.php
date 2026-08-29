<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Joycloth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#f4f4f0] text-dark-950" x-data>

<div class="flex h-screen overflow-hidden">

    {{-- ========== SIDEBAR ========== --}}
    <aside class="w-72 bg-white border-r-3 border-dark-950 flex flex-col flex-shrink-0 admin-scrollbar overflow-y-auto"
           x-data="{ open: true }" :class="open ? 'translate-x-0' : '-translate-x-full'"
           id="sidebar">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-dark-800">
            <div class="w-9 h-9 bg-primary-500 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2L2 7l8 5 8-5-8-5z"/></svg>
            </div>
            <div>
                <span class="text-dark-950 font-display font-black text-xl leading-none uppercase tracking-tighter">JOYCLOTH</span>
                <p class="text-dark-500 text-xs mt-0.5">Admin Panel</p>
            </div>
        </div>

        {{-- Admin Info --}}
        <div class="px-4 py-4 border-b-3 border-dark-950">
            <div class="flex items-center gap-3 bg-primary-200 border-2 border-dark-950 shadow-brutal-sm rounded-none px-3 py-2.5">
                <div class="w-9 h-9 bg-white border-2 border-dark-950 rounded-none flex items-center justify-center font-bold text-sm flex-shrink-0 overflow-hidden">
                    <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <p class="text-dark-950 text-sm font-extrabold truncate uppercase">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-dark-700 text-xs font-bold uppercase tracking-widest">Administrator</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1">

            <p class="text-dark-500 text-xs font-semibold uppercase tracking-wider px-4 mb-2">Main</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            <p class="text-dark-500 text-xs font-semibold uppercase tracking-wider px-4 mb-2 mt-4">Catalog</p>

            <a href="{{ route('admin.produk.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products
            </a>

            <a href="{{ route('admin.kategori.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Categories
            </a>

            <a href="{{ route('admin.desain.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.desain.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Design Refs
            </a>

            <p class="text-dark-500 text-xs font-semibold uppercase tracking-wider px-4 mb-2 mt-4">Transactions</p>

            <a href="{{ route('admin.orders.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Orders
            </a>

            <a href="{{ route('admin.payments.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Payments
            </a>

            <p class="text-dark-500 text-xs font-semibold uppercase tracking-wider px-4 mb-2 mt-4">Others</p>

            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users
            </a>

            <a href="{{ route('admin.chat.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }} flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Live Chat
                </div>
                @php
                    $unreadCount = \App\Models\ChatMessage::where('sender_type', 'user')->where('is_read', false)->count();
                @endphp
                @if($unreadCount > 0)
                <span class="chat-badge inline-flex items-center justify-center w-6 h-6 text-[11px] font-black text-dark-950 bg-[#FF0000] border-2 border-dark-950 shadow-brutal-sm animate-bounce group-hover:animate-none">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
                @endif
            </a>
        </nav>

        {{-- Footer Sidebar --}}
        <div class="px-3 py-4 border-t-3 border-dark-950 space-y-1">
            <a href="{{ route('home') }}" target="_blank"
               class="sidebar-link">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Store
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ========== MAIN CONTENT AREA ========== --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <header class="h-16 bg-white border-b-3 border-dark-950 flex items-center justify-between px-6 flex-shrink-0">
            <div>
                <h1 class="text-dark-950 font-extrabold text-xl uppercase tracking-wider">@yield('page-title', 'Dashboard')</h1>
                <p class="text-dark-600 text-xs font-bold">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-dark-950 font-bold text-sm uppercase tracking-wider">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success') || session('error'))
        <div class="px-6 pt-4">
            @if(session('success'))
            <div class="alert-success" x-data="{ show: true }" x-show="show" x-transition>
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto">✕</button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert-danger" x-data="{ show: true }" x-show="show" x-transition>
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="text-sm">{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto">✕</button>
            </div>
            @endif
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6 admin-scrollbar">
            @yield('content')
        </main>
    </div>
</div>

<script>
    // Polling untuk notifikasi chat baru di sidebar admin
    let currentUnreadCount = {{ \App\Models\ChatMessage::where('sender_type', 'user')->where('is_read', false)->count() }};
    
    setInterval(async () => {
        try {
            const res = await fetch('{{ route("admin.chat.unread_count") }}');
            const data = await res.json();
            
            if (data.count !== undefined) {
                // Update badge di sidebar
                const badgeContainer = document.querySelector('a[href*="admin/chat"]');
                if (badgeContainer) {
                    let badge = badgeContainer.querySelector('.chat-badge');
                    
                    if (data.count > 0) {
                        if (!badge) {
                            // Buat badge baru jika belum ada
                            badge = document.createElement('span');
                            badge.className = 'chat-badge inline-flex items-center justify-center w-6 h-6 text-[11px] font-black text-dark-950 bg-[#FF0000] border-2 border-dark-950 shadow-brutal-sm animate-bounce group-hover:animate-none';
                            badgeContainer.appendChild(badge);
                        }
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                        
                        // Jika ada pesan baru, mainkan efek/suara atau notifikasi
                        if (data.count > currentUnreadCount) {
                            console.log('New message received!');
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                    currentUnreadCount = data.count;
                }
            }
        } catch (e) {}
    }, 10000); // Cek setiap 10 detik
</script>

@stack('scripts')
</body>
</html>
