@extends('layouts.admin')
@section('title', 'Product Management')
@section('page-title', 'Product Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.produk.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Product
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="card-flat p-4 mb-6 flex flex-wrap gap-3">
    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Search products..." class="form-input flex-1 min-w-40">
    <select name="kategori" class="form-select">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary btn-sm">Search</button>
    @if(request()->hasAny(['cari','kategori']))
    <a href="{{ route('admin.produk.index') }}" class="btn-secondary btn-sm">Reset</a>
    @endif
</form>

{{-- Table --}}
<div class="card-flat overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th width="60" class="text-center">Order</th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Min. Order</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr data-id="{{ $product->id }}">
                <td class="text-center align-middle">
                    <div class="cursor-grab text-dark-400 hover:text-primary-500 text-lg font-bold py-2 select-none" style="cursor: grab;">☰</div>
                </td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 flex-shrink-0 bg-primary-100 border-2 border-dark-950 rounded-xl overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-dark-950 font-bold">{{ $product->name }}</p>
                            <p class="text-dark-600 text-xs font-bold">{{ Str::limit($product->description, 50) }}</p>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-info">{{ $product->category->name }}</span></td>
                <td class="text-primary-600 font-extrabold">{{ $product->formatted_price }}</td>
                <td class="text-dark-700 font-bold">{{ $product->min_order }} pcs</td>
                <td>
                    <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.produk.edit', $product) }}" class="btn-success btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.produk.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-dark-500 py-12">No products yet</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $products->links() }}</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('.data-table tbody');
    if (!tableBody) return;

    let dragRow = null;

    tableBody.querySelectorAll('tr').forEach(row => {
        const grabHandle = row.querySelector('.cursor-grab');
        if (!grabHandle) return;

        grabHandle.addEventListener('mousedown', function() {
            row.setAttribute('draggable', 'true');
        });

        grabHandle.addEventListener('mouseup', function() {
            row.removeAttribute('draggable');
        });

        row.addEventListener('dragstart', function (e) {
            dragRow = this;
            this.classList.add('bg-primary-50', 'opacity-75');
            e.dataTransfer.effectAllowed = 'move';
        });

        row.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            
            const rect = this.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;
            
            if (e.clientY < midpoint) {
                this.parentNode.insertBefore(dragRow, this);
            } else {
                this.parentNode.insertBefore(dragRow, this.nextSibling);
            }
        });

        row.addEventListener('dragend', function () {
            this.classList.remove('bg-primary-50', 'opacity-75');
            this.removeAttribute('draggable');
            saveOrder();
        });
    });

    function saveOrder() {
        const rows = Array.from(tableBody.querySelectorAll('tr[data-id]'));
        const ids = rows.map(r => r.getAttribute('data-id')).filter(id => id);

        fetch('{{ route("admin.produk.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Sequence updated successfully!');
            }
        })
        .catch(err => {
            console.error('Error saving order:', err);
        });
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 bg-green-500 text-white border-2 border-dark-950 font-black text-sm uppercase tracking-tight px-6 py-3 shadow-brutal-sm transition-all transform translate-y-10 opacity-0 z-[9999]';
        toast.innerText = message;
        document.body.appendChild(toast);

        // Trigger reflow
        toast.offsetHeight;

        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 50);

        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }
});
</script>
@endpush

@endsection
