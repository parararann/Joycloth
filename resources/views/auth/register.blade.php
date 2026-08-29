<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Joycloth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f4f4f0] text-dark-950 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <span class="text-dark-950 font-display font-extrabold text-3xl uppercase tracking-tighter">JOY<span class="text-primary-600">CLOTH</span></span>
            </a>
            <p class="text-dark-600 mt-2 text-sm font-bold uppercase tracking-wider">Create a new account</p>
        </div>

        <div class="bg-white border-3 border-dark-950 rounded-none p-8 shadow-brutal-lg">

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="form-label text-dark-950">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="form-input bg-white"
                           placeholder="John Doe">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label text-dark-950">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="form-input bg-white"
                           placeholder="email@example.com">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label text-dark-950">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           class="form-input bg-white"
                           placeholder="08xx xxxx xxxx">
                    @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label text-dark-950">Password</label>
                    <input type="password" name="password" required
                           class="form-input bg-white"
                           placeholder="Min. 8 characters">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label text-dark-950">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="form-input bg-white"
                           placeholder="Repeat password">
                </div>

                <button type="submit" class="btn-primary w-full py-3.5 text-base">
                    Register Now
                </button>
            </form>

            <p class="text-center text-dark-700 font-bold text-sm mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-dark-950 font-extrabold hover:text-accent hover:underline decoration-2">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
