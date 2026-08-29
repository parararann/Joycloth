@extends('layouts.admin')
@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')
@section('page-subtitle', $user->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Profile Card --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="card-flat p-6">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-24 h-24 bg-primary-200 border-4 border-dark-950 rounded-full flex items-center justify-center text-dark-950 font-black text-4xl shadow-brutal mb-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-2xl font-black uppercase tracking-tighter text-dark-950">{{ $user->name }}</h2>
                <span class="badge badge-{{ $user->role === 'admin' ? 'primary' : 'secondary' }} mt-2 px-4 py-1.5 uppercase font-black text-xs">
                    {{ strtoupper($user->role) }}
                </span>
            </div>

            <div class="space-y-4 pt-6 border-t-3 border-dark-950">
                <div>
                    <p class="text-dark-600 font-bold uppercase text-[10px] tracking-widest mb-1">Email Address</p>
                    <p class="text-dark-950 font-extrabold">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-dark-600 font-bold uppercase text-[10px] tracking-widest mb-1">Telepon / WhatsApp</p>
                    <p class="text-dark-950 font-extrabold">{{ $user->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-dark-600 font-bold uppercase text-[10px] tracking-widest mb-1">Alamat Utama</p>
                    <p class="text-dark-950 font-bold leading-relaxed">{{ $user->address ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-dark-600 font-bold uppercase text-[10px] tracking-widest mb-1">Bergabung Sejak</p>
                    <p class="text-dark-950 font-extrabold uppercase">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Manage Account --}}
        <div class="card-flat p-6 border-hotpink">
            <h3 class="text-dark-950 font-black uppercase text-sm mb-4">Kelola Akun</h3>
            <div class="space-y-3">
                <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    <label class="form-label text-[10px]">Ubah Role</label>
                    <div class="flex gap-2">
                        <select name="role" class="form-select text-xs">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>USER (Pelanggan)</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>ADMIN (Pengelola)</option>
                        </select>
                        <button type="submit" class="btn-primary py-2 px-4 text-xs">Update</button>
                    </div>
                </form>

                @if($user->id !== auth()->id())
                <div class="pt-4 border-t-2 border-dark-950">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Semua data pesanan akan ikut terpengaruh.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full btn-danger py-2 text-xs">Hapus Akun</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Orders History --}}
    <div class="lg:col-span-2">
        <div class="card-flat min-h-full">
            <div class="p-6 border-b-3 border-dark-950 flex justify-between items-center bg-[#fdfdfb]">
                <h3 class="text-dark-950 font-black uppercase tracking-tight">Riwayat Pesanan</h3>
                <span class="bg-dark-950 text-white text-[10px] font-black px-2 py-1 uppercase tracking-widest">
                    Total: {{ $user->orders->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="uppercase tracking-widest text-[10px]">No. Pesanan</th>
                            <th class="uppercase tracking-widest text-[10px]">Tanggal</th>
                            <th class="uppercase tracking-widest text-[10px]">Total</th>
                            <th class="uppercase tracking-widest text-[10px]">Status</th>
                            <th class="uppercase tracking-widest text-[10px]">Pembayaran</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-dark-950">
                        @forelse($user->orders as $order)
                        <tr class="hover:bg-primary-50 transition-colors">
                            <td class="font-black text-dark-950">{{ $order->order_number }}</td>
                            <td class="text-dark-700 font-bold uppercase text-[11px]">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="font-black text-dark-950">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status_color }} text-[10px] uppercase font-black">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($order->payment)
                                <span class="badge badge-{{ $order->payment->status_color }} text-[10px] uppercase font-black">
                                    {{ $order->payment->status_label }}
                                </span>
                                @else
                                <span class="text-dark-400 font-bold italic text-xs">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-secondary py-1.5 px-3 text-[10px] uppercase font-black">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <p class="text-dark-400 font-bold italic">Belum ada riwayat pesanan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
