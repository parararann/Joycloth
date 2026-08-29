@extends('layouts.app')
@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="section-title mb-2">Shopping Cart</h1>
    <p class="section-subtitle mb-8">{{ count($cart) }} items in cart</p>

    @if(empty($cart))
    <div class="card-flat p-16 text-center">
        <div class="text-7xl mb-4">🛒</div>
        <h3 class="text-dark-700 font-semibold text-xl mb-2">Cart is Empty</h3>
        <p class="text-dark-400 mb-6">Let's add some products to your cart!</p>
        <a href="{{ route('products.index') }}" class="btn-primary">Start Shopping</a>
    </div>
    @else
    <form id="checkout-form" action="{{ route('checkout') }}" method="GET">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-4">
                {{-- Select All --}}
                <div class="flex items-center gap-3 px-5 py-3 bg-dark-50 border-2 border-dark-950 shadow-brutal-sm">
                    <input type="checkbox" id="select-all" class="w-5 h-5 border-2 border-dark-950 text-primary-600 focus:ring-0 cursor-pointer" checked onchange="toggleAll(this)">
                    <label for="select-all" class="text-sm font-black uppercase tracking-tight cursor-pointer">Select All Items</label>
                </div>

                @foreach($cart as $key => $item)
                <div class="card-flat p-5 flex gap-4" id="cart-item-{{ $key }}">
                    {{-- Checkbox --}}
                    <div class="flex items-center pr-2">
                        <input type="checkbox" name="selected_items[]" value="{{ $key }}" 
                               class="item-checkbox w-5 h-5 border-2 border-dark-950 text-primary-600 focus:ring-0 cursor-pointer" 
                               checked onchange="updateSummary()">
                    </div>

                    {{-- Image --}}
                    <div class="w-20 h-20 flex-shrink-0 bg-dark-100 rounded-xl overflow-hidden">
                        <img src="{{ $item['product_image'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-dark-900 truncate">{{ $item['product_name'] }}</h4>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <span class="badge badge-info">{{ $item['size'] }}</span>
                            <span class="badge bg-white text-dark-800 border border-dark-200">{{ $item['color'] }}</span>
                            @if(isset($item['sleeve_type']))
                            <span class="badge bg-amber-100 text-amber-700 border border-amber-200">{{ $item['sleeve_type'] }}</span>
                            @endif
                            <span class="badge badge-primary">{{ $item['sablon_type'] }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between mt-3">
                            {{-- Qty Control --}}
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="updateCart('{{ $key }}', Math.max(1, parseInt(document.getElementById('qty-{{ $key }}').value)-1))"
                                        class="w-7 h-7 bg-dark-100 hover:bg-dark-200 rounded-lg text-sm font-bold transition-colors flex items-center justify-center">−</button>
                                <input type="number" id="qty-{{ $key }}" value="{{ $item['quantity'] }}" min="1"
                                       class="w-16 text-center text-sm font-semibold border border-dark-200 rounded-lg py-1"
                                       onchange="updateCart('{{ $key }}', this.value)">
                                <button type="button" onclick="updateCart('{{ $key }}', parseInt(document.getElementById('qty-{{ $key }}').value)+1)"
                                        class="w-7 h-7 bg-dark-100 hover:bg-dark-200 rounded-lg text-sm font-bold transition-colors flex items-center justify-center">+</button>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-primary-600 font-bold item-subtotal" data-key="{{ $key }}" data-subtotal="{{ $item['subtotal'] }}" id="subtotal-{{ $key }}">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                                <button type="button" onclick="removeItem('{{ $key }}')" class="text-red-400 hover:text-red-600 transition-colors p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="card-flat p-6 sticky top-24">
                    <h3 class="font-display font-bold text-dark-900 text-lg mb-5">Summary</h3>

                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between text-sm text-dark-600">
                            <span>Selected Subtotal</span>
                            <span id="selected-total" class="font-semibold text-dark-950">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-dark-600">
                            <span>Shipping Fee</span>
                            <span class="text-amber-600 font-medium text-right text-xs">Confirmed by Admin</span>
                        </div>
                        <hr class="border-dark-200">
                        <div class="flex justify-between font-bold text-dark-900">
                            <span>Estimated Total</span>
                            <span class="text-primary-600" id="selected-total-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" id="checkout-btn" class="btn-primary w-full text-base py-3.5">
                        Checkout Selected →
                    </button>

                    <a href="{{ route('products.index') }}" class="block text-center text-sm text-dark-400 hover:text-dark-700 mt-3 transition-colors">
                        ← Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Form hidden untuk delete --}}
    <form id="delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleAll(source) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
    updateSummary();
}

function updateSummary() {
    let total = 0;
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    checkboxes.forEach(cb => {
        const key = cb.value;
        const subtotalElement = document.getElementById(`subtotal-${key}`);
        // Ambil nilai subtotal murni dari atribut data
        const subtotal = parseFloat(subtotalElement.getAttribute('data-subtotal'));
        total += subtotal;
    });

    // Update tampilan total
    const formattedTotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total).replace('IDR', 'Rp');
    document.getElementById('selected-total').textContent = formattedTotal;
    document.getElementById('selected-total-bold').textContent = formattedTotal;

    // Disable button jika tidak ada yang dipilih
    checkoutBtn.disabled = checkboxes.length === 0;
    checkoutBtn.style.opacity = checkboxes.length === 0 ? '0.5' : '1';
}

function updateCart(key, qty) {
    qty = Math.max(1, parseInt(qty));
    document.getElementById(`qty-${key}`).value = qty;

    fetch(`/keranjang/${key}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const subtotalElement = document.getElementById(`subtotal-${key}`);
            subtotalElement.textContent = data.subtotal;
            // Update data-subtotal attribute for JS calculation
            subtotalElement.setAttribute('data-subtotal', data.subtotal_raw);
            updateSummary();
        }
    });
}

function removeItem(key) {
    if (confirm('Remove this item?')) {
        const form = document.getElementById('delete-form');
        form.action = `/keranjang/${key}`;
        form.submit();
    }
}

// Inisialisasi summary saat halaman dimuat
document.addEventListener('DOMContentLoaded', updateSummary);
</script>
@endpush
