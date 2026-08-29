@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="section-title mb-2">Checkout</h1>
    <p class="section-subtitle mb-4">Complete your shipping data</p>
    
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8 flex gap-3 items-center">
        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-amber-800 text-sm font-medium">
            <span class="font-bold">Important:</span> Currently we only serve shipping for <span class="font-bold">JABODETABEK</span> area (Jakarta, Bogor, Depok, Tangerang, Bekasi).
        </p>
    </div>

    <form id="checkout-form" method="POST" action="{{ route('order.place') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        {{-- Selected Items Keys --}}
        @foreach($selectedKeys as $key)
        <input type="hidden" name="selected_items[]" value="{{ $key }}">
        @endforeach

        {{-- ====== DATA PENGIRIMAN ====== --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card-flat p-6">
                <h3 class="font-display font-bold text-dark-900 text-lg mb-5 flex items-center gap-2">
                    <span class="w-8 h-8 bg-primary-500 text-white rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                    Recipient Data
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Recipient Name <span class="text-red-500">*</span></label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name', $user->name) }}"
                               class="form-input @error('recipient_name') border-red-400 @enderror" required>
                        @error('recipient_name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Recipient Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="recipient_phone" value="{{ old('recipient_phone', $user->phone) }}"
                               class="form-input @error('recipient_phone') border-red-400 @enderror" placeholder="08xx xxxx xxxx" required>
                        @error('recipient_phone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="form-label">Full Address <span class="text-red-500">*</span></label>
                    <textarea name="shipping_address" rows="3" required
                              class="form-input resize-none @error('shipping_address') border-red-400 @enderror"
                              placeholder="Street name, house number, rt/rw, sub-district, district...">{{ old('shipping_address', $user->address) }}</textarea>
                    @error('shipping_address')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="form-label">City (JABODETABEK only) <span class="text-red-500">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}"
                               class="form-input @error('city') border-red-400 @enderror" 
                               placeholder="Example: South Jakarta / Bekasi" required>
                        @error('city')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                               class="form-input" maxlength="10" placeholder="12345">
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="card-flat p-6">
                <h3 class="font-display font-bold text-dark-900 text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-primary-500 text-white rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                    Order Notes
                </h3>
                <textarea name="notes" rows="3" class="form-input resize-none"
                          placeholder="Additional notes for the entire order (optional)...">{{ old('notes') }}</textarea>
            </div>

            {{-- ====== SHIPPING METHOD ====== --}}
            <div class="card-flat p-6">
                <h3 class="font-display font-bold text-dark-900 text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-primary-500 text-white rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                    Shipping Method
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Manual --}}
                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="shipping_method" value="manual" class="peer sr-only" checked onchange="updateShipping('manual', 0)">
                        <div class="w-full bg-white border-2 border-dark-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 p-4 rounded-xl transition-all">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-2xl">🚛</span>
                                <span class="font-bold text-dark-900">Admin Confirmation</span>
                            </div>
                            <p class="text-xs text-dark-500 leading-relaxed">Admin will contact you for manual shipping options (JNE, TIKI, etc.) after checking the weights.</p>
                            <div class="mt-2 text-sm font-bold text-dark-900">To be calculated</div>
                        </div>
                    </label>

                    {{-- Gojek --}}
                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="shipping_method" value="gosend" class="peer sr-only" onchange="calculateGojek()">
                        <div class="w-full bg-white border-2 border-dark-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 p-4 rounded-xl transition-all">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-[#00AA13] rounded-lg flex items-center justify-center text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                </div>
                                <span class="font-bold text-dark-900">GoSend (Gojek)</span>
                            </div>
                            <p class="text-xs text-dark-500 leading-relaxed mb-1">Instant delivery for JABODETABEK area. Real-time estimation.</p>
                            <p class="text-[10px] font-black text-primary-700 uppercase tracking-widest mb-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Hours: 08:00 - 17:00
                            </p>
                            <div class="mt-auto text-sm font-bold text-[#00AA13]" id="gosend-price">Enter City Above...</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Info Pembayaran --}}
            <div class="card-flat p-6 border-l-4 border-primary-500">
                <h3 class="font-display font-bold text-dark-900 mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 bg-primary-500 text-white rounded-lg flex items-center justify-center text-sm font-bold">4</span>
                    Payment Method
                </h3>
                <div class="flex items-center gap-3 bg-dark-50 rounded-xl p-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xs">BCA</div>
                    <div>
                        <p class="font-semibold text-dark-900">BCA Bank Transfer</p>
                        <p class="text-dark-500 text-sm">Account details will be shown after the order is placed</p>
                    </div>
                    <span class="ml-auto badge badge-success">Available</span>
                </div>
            </div>
        </div>

        {{-- ====== ORDER SUMMARY ====== --}}
        <div>
            <div class="card-flat p-6 sticky top-24">
                <h3 class="font-display font-bold text-dark-900 text-lg mb-5">Order Summary</h3>

                <div class="space-y-3 mb-5 max-h-64 overflow-y-auto">
                    @foreach($filteredCart as $item)
                    <div class="flex gap-3 text-sm">
                        <div class="w-12 h-12 flex-shrink-0 bg-dark-100 rounded-lg overflow-hidden border border-dark-200">
                            <img src="{{ $item['product_image'] }}" alt="" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-dark-800 truncate">{{ $item['product_name'] }}</p>
                            <p class="text-dark-400 text-[10px] uppercase font-bold tracking-tight">
                                {{ $item['size'] }} · {{ $item['color'] }} 
                                @if(isset($item['sleeve_type'])) · {{ $item['sleeve_type'] }} @endif
                                · {{ $item['quantity'] }} pcs
                            </p>
                            <p class="text-primary-600 font-bold">Rp {{ number_format($item['subtotal'], 0, '.', ',') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr class="border-dark-200 mb-4">

                <div class="space-y-2 mb-5 text-sm">
                    <div class="flex justify-between text-dark-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, '.', ',') }}</span>
                    </div>
                    <div class="flex justify-between text-dark-600">
                        <span>Shipping</span>
                        <span id="shipping-label" class="text-amber-600 font-bold">To be confirmed</span>
                    </div>
                    <hr class="border-dark-200">
                    <div class="flex justify-between font-bold text-dark-900 text-base">
                        <span>Total</span>
                        <span id="total-label" class="text-primary-600">Rp {{ number_format($subtotal, 0, '.', ',') }}</span>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-4 text-base">
                    ✓ Place Order ({{ count($filteredCart) }} Items)
                </button>

                <p class="text-xs text-dark-400 text-center mt-3">
                    By ordering, you agree to our terms & conditions
                </p>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    const cityDistances = {
        'jakarta': 15,
        'selatan': 15,
        'pusat': 12,
        'timur': 18,
        'barat': 20,
        'utara': 22,
        'bekasi': 5,
        'depok': 25,
        'tangerang': 35,
        'bogor': 45
    };

    function calculateGojek() {
        const cityInput = document.getElementsByName('city')[0].value.toLowerCase();
        let distance = 0;
        
        if (!cityInput) {
            document.getElementById('gosend-price').innerText = 'Please enter city first';
            updateShipping('gosend', 0);
            return;
        }

        for (const [key, val] of Object.entries(cityDistances)) {
            if (cityInput.includes(key)) {
                distance = val;
                break;
            }
        }

        if (distance === 0) {
            document.getElementById('gosend-price').innerText = 'Outside GoSend Coverage';
            updateShipping('gosend', 0);
            return;
        }

        // Mock GoSend formula: 10,000 base + 2,500/km
        const cost = 10000 + (distance * 2500);
        document.getElementById('gosend-price').innerText = 'Est. Rp ' + cost.toLocaleString('id-ID');
        updateShipping('gosend', cost);
    }

    function updateShipping(method, cost) {
        document.getElementById('shipping-label').innerText = cost > 0 ? 'Rp ' + cost.toLocaleString('id-ID') : 'To be confirmed';
        document.getElementById('total-label').innerText = 'Rp ' + (subtotal + cost).toLocaleString('id-ID');
        
        // Add hidden input for shipping cost if needed
        let costInput = document.getElementById('shipping-cost-input');
        if (!costInput) {
            costInput = document.createElement('input');
            costInput.type = 'hidden';
            costInput.name = 'shipping_cost';
            costInput.id = 'shipping-cost-input';
            document.getElementById('checkout-form').appendChild(costInput);
        }
        costInput.value = cost;
    }

    // Re-calculate if city changes
    document.getElementsByName('city')[0].addEventListener('input', () => {
        if (document.querySelector('input[name="shipping_method"]:checked')?.value === 'gosend') {
            calculateGojek();
        }
    });
</script>
@endpush
@endsection
