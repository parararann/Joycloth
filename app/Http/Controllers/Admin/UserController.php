<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')->orderByDesc('created_at');

        if ($request->filled('cari')) {
            $query->where('name', 'like', '%' . $request->cari . '%')
                  ->orWhere('email', 'like', '%' . $request->cari . '%');
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(int $id)
    {
        $user = User::with(['orders.payment'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, int $id)
    {
        $request->validate(['role' => 'required|in:admin,user']);

        $user = User::findOrFail($id);

        // Jangan ubah role diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()
            ->with('success', 'User role updated successfully!');
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
