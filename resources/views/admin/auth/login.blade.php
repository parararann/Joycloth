@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-dark-50">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h2 class="font-display font-black text-4xl text-dark-950 uppercase tracking-tighter">
                JOYCLOTH <span class="text-primary-500">ADMIN</span>
            </h2>
            <p class="mt-2 text-dark-500 font-medium">Please login to management panel</p>
        </div>

        <div class="card-flat p-8 bg-white">
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="form-input @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 font-bold uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required
                           class="form-input @error('password') border-red-500 @enderror">
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-primary-500 border-2 border-dark-950 rounded focus:ring-0">
                    <label for="remember_me" class="ml-2 block text-sm text-dark-700 font-bold uppercase">Remember me</label>
                </div>

                <button type="submit" class="btn-primary w-full py-4 text-lg">
                    LOGIN TO PANEL →
                </button>
            </form>
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm text-dark-400 hover:text-primary-600 font-bold uppercase transition-colors">
                ← Back to Main Site
            </a>
        </div>
    </div>
</div>
@endsection
