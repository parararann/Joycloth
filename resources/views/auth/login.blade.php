<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Joycloth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f4f4f0] text-dark-950 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <span class="text-dark-950 font-display font-extrabold text-3xl uppercase tracking-tighter">JOY<span class="text-primary-600">CLOTH</span></span>
            </a>
            <p class="text-dark-600 mt-2 text-sm font-bold uppercase tracking-wider">Login to your account</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white border-3 border-dark-950 rounded-none p-8 shadow-brutal-lg">

            @if(session('status'))
            <div class="alert-success mb-5"><span>{{ session('status') }}</span></div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="form-label text-dark-950">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="form-input bg-white"
                           placeholder="email@example.com">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label text-dark-950">Password</label>
                    <input type="password" name="password" required
                           class="form-input bg-white"
                           placeholder="••••••••">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded-none border-2 border-dark-950 text-accent">
                        <span class="text-dark-700 text-sm font-bold">Remember me</span>
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-dark-950 font-bold text-sm hover:text-accent hover:underline decoration-2">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-primary w-full py-3.5 text-base">
                    Login
                </button>
            </form>

            <p class="text-center text-dark-700 font-bold text-sm mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-dark-950 font-extrabold hover:text-accent hover:underline decoration-2">Register now</a>
            </p>
        </div>

        <p class="text-center text-dark-600 font-bold text-xs mt-6">
            &copy; {{ date('Y') }} Joycloth. All rights reserved.
        </p>
    </div>
</body>
</html>
