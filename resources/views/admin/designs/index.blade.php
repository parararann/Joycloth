@extends('layouts.admin')
@section('title', 'Design References')
@section('page-title', 'Design References')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.desain.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Design
    </a>
</div>

<div class="card-flat overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th width="60" class="text-center">Order</th>
                <th>Image</th>
                <th>Design Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($designs as $design)
            <tr data-id="{{ $design->id }}">
                <td class="text-center align-middle">
                    <div class="cursor-grab text-dark-400 hover:text-primary-500 text-lg font-bold py-2 select-none" style="cursor: grab;">☰</div>
                </td>
                <td>
                    <div class="w-16 h-16 bg-white border-2 border-dark-950 flex items-center justify-center overflow-hidden p-0.5 shadow-brutal-sm relative">
                        <img src="{{ $design->image_url }}" alt="Img" class="w-full h-full object-contain">
                    </div>
                </td>
                <td>
                    <div class="font-bold text-dark-950">{{ $design->title }}</div>
                    <div class="text-xs text-dark-500 truncate max-w-xs">{{ $design->description }}</div>
                </td>
                <td>
                    @if($design->is_active)
                    <span class="badge badge-primary">Active</span>
                    @else
                    <span class="badge badge-dark text-xs">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.desain.edit', $design->id) }}" class="p-2 bg-yellow-300 text-dark-950 border-2 border-dark-950 hover:bg-yellow-400 transition-colors shadow-brutal-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        <form action="{{ route('admin.desain.destroy', $design->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this design?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-400 text-dark-950 border-2 border-dark-950 hover:bg-red-500 transition-colors shadow-brutal-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-8 text-dark-500 font-medium">No design references yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
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

        fetch('{{ route("admin.desain.reorder") }}', {
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
