@extends('layouts.admin')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')

<form method="GET" class="card-flat p-4 mb-6 flex flex-wrap gap-3">
    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Search name or email..." class="form-input flex-1 min-w-48">
    <select name="role" class="form-select w-40">
        <option value="">All Roles</option>
        <option value="user" {{ request('role')==='user'?'selected':'' }}>User</option>
        <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Admin</option>
    </select>
    <button type="submit" class="btn-primary btn-sm">Search</button>
    @if(request()->hasAny(['cari','role']))
    <a href="{{ route('admin.users.index') }}" class="btn-secondary btn-sm">Reset</a>
    @endif
</form>

<div class="card-flat overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Phone No.</th>
                <th>Role</th>
                <th>Total Orders</th>
                <th>Joined</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-primary-500 border-2 border-dark-950 text-dark-950 shadow-brutal-sm rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-dark-950 font-bold text-sm">{{ $user->name }}</p>
                            <p class="text-dark-600 text-xs font-bold">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="text-dark-800 font-bold text-sm">{{ $user->phone ?? '-' }}</td>
                <td>
                    <span class="badge {{ $user->role === 'admin' ? 'badge-primary' : 'bg-primary-100 text-dark-950 border-2 border-dark-950 font-black' }} text-[10px] uppercase">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="text-dark-950 font-extrabold">{{ $user->orders_count }} orders</td>
                <td class="text-dark-700 font-bold text-xs uppercase">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn-secondary btn-sm">Detail</a>
                        {{-- Update Role --}}
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.role', $user->id) }}">
                            @csrf @method('PUT')
                            <select name="role" class="text-xs bg-white border-2 border-dark-950 text-dark-950 font-bold px-2 py-1 shadow-brutal-sm focus:ring-0" onchange="this.form.submit()">
                                <option value="user" {{ $user->role==='user'?'selected':'' }}>USER</option>
                                <option value="admin" {{ $user->role==='admin'?'selected':'' }}>ADMIN</option>
                            </select>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Delete</button>
                        </form>
                        @else
                        <span class="text-dark-500 text-xs">Your Account</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-dark-500 py-12">No users yet</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection
