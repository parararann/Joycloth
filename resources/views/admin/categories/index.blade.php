@extends('layouts.admin')
@section('title', 'Category Management')
@section('page-title', 'Category Management')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.kategori.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Category
    </a>
</div>

<div class="card-flat overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th width="60" class="text-center">Order</th>
                <th>Category</th>
                <th>Slug</th>
                <th>Products</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            <tr data-id="{{ $cat->id }}">
                <td class="text-center align-middle">
                    <div class="cursor-grab text-dark-400 hover:text-primary-500 text-lg font-bold py-2 select-none" style="cursor: grab;">☰</div>
                </td>
                <td>
                    <div class="flex items-center gap-3">
                        @if($cat->image)
                        <img src="{{ asset('storage/'.$cat->image) }}" class="w-10 h-10 rounded-xl object-cover border border-dark-950">
                        @else
                        <div class="w-10 h-10 bg-primary-200 border-2 border-dark-950 rounded-xl flex items-center justify-center text-xl">🧥</div>
                        @endif
                        <span class="text-dark-950 font-bold uppercase">{{ $cat->name }}</span>
                    </div>
                </td>
                <td><code class="text-accent text-xs font-bold bg-primary-50 px-2 py-1 border border-dark-950">{{ $cat->slug }}</code></td>
                <td class="text-dark-700 font-bold">{{ $cat->products_count }} products</td>
                <td><span class="badge {{ $cat->is_active ? 'badge-success' : 'badge-danger' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.kategori.edit', $cat) }}" class="btn-success btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.kategori.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-dark-500 py-12">No categories yet</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $categories->links() }}</div>
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

        fetch('{{ route("admin.kategori.reorder") }}', {
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
