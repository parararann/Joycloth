<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form registrasi
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran user baru
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'     => 'Name is required.',
            'email.required'    => 'Email is required.',
            'email.unique'      => 'Email is already in use.',
            'password.required' => 'Password is required.',
            'password.confirmed'=> 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'user', // Default role: user
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
