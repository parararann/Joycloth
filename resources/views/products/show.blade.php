@extends('layouts.app')
@section('title', $product->name)
@section('description', Str::limit($product->description, 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-dark-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-primary-600">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-primary-600">Catalog</a>
        <span>/</span>
        <span class="text-dark-700">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">

        {{-- ====== PRODUCT IMAGE ====== --}}
        <div>
            <div class="aspect-square bg-dark-100 rounded-3xl overflow-hidden shadow-xl">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
        </div>

        {{-- ====== PRODUCT INFO & ORDER FORM ====== --}}
        <div>
            <span class="badge badge-primary mb-3">{{ $product->category->name }}</span>
            <h1 class="font-display font-black text-3xl text-dark-900 mb-3">{{ $product->name }}</h1>

            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-4xl font-display font-black text-primary-600">{{ $product->formatted_price }}</span>
                <span class="text-dark-400 text-sm">/pcs (min. {{ $product->min_order }} pcs)</span>
            </div>

            <div class="mb-6 flex items-center gap-2">
                @if($product->stock <= 0)
                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-600 border-2 border-red-500 font-bold text-[10px] uppercase tracking-wider rounded-lg">
                        ⚠️ Out of Stock
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-600 border-2 border-green-500 font-bold text-[10px] uppercase tracking-wider rounded-lg">
                        📦 Available: {{ $product->stock }} pcs
                    </span>
                @endif
            </div>

            {{-- Material --}}
            @if($product->material)
            <div class="bg-dark-50 rounded-xl p-4 mb-6">
                <p class="text-sm text-dark-500 font-medium mb-1">Material</p>
                <p class="text-dark-800">{{ $product->material }}</p>
            </div>
            @endif

            {{-- Deskripsi --}}
            <div class="text-dark-600 leading-relaxed mb-8">{{ $product->description }}</div>

            {{-- ====== FORM PESAN ====== --}}
            @auth
            <form action="{{ route('cart.add') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- Pilih Ukuran --}}
                <div>
                    <label class="form-label">Select Size <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->sizes_list as $size)
                        <label class="cursor-pointer">
                            <input type="radio" name="size" value="{{ $size }}" class="sr-only peer" required>
                            <span class="px-4 py-2 border-2 border-dark-200 rounded-xl text-sm font-semibold text-dark-600
                                         peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:text-primary-700
                                         hover:border-primary-300 transition-all cursor-pointer block">
                                {{ $size }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('size')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                {{-- Pilih Warna --}}
                <div>
                    <label class="form-label">Select Color <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->colors_list as $color)
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" required>
                            <span class="px-4 py-2 border-2 border-dark-200 rounded-xl text-sm font-semibold text-dark-600
                                         peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:text-primary-700
                                         hover:border-primary-300 transition-all cursor-pointer block">
                                {{ $color }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('color')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                {{-- Pilih Jenis Lengan --}}
                @if(!empty($product->sleeve_types_list))
                <div>
                    <label class="form-label">Select Sleeve Type <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->sleeve_types_list as $sleeve)
                        <label class="cursor-pointer">
                            <input type="radio" name="sleeve_type" value="{{ $sleeve }}" class="sr-only peer" required>
                            <span class="px-4 py-2 border-2 border-dark-200 rounded-xl text-sm font-semibold text-dark-600
                                         peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:text-primary-700
                                         hover:border-primary-300 transition-all cursor-pointer block">
                                {{ $sleeve }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('sleeve_type')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Jenis Sablon --}}
                <div x-data="{ 
                    sablonType: '',
                    isNoPrint() { return this.sablonType === 'No Printing (Plain)'; }
                }">
                    <div>
                        <label class="form-label">Print Type <span class="text-red-500">*</span></label>
                        <select name="sablon_type" class="form-select" required x-model="sablonType" @change="$dispatch('sablon-change', sablonType)">
                            <option value="">-- Choose Print Type --</option>
                            @foreach($product->sablon_types_list as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('sablon_type')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-5 mt-5" x-data="{ requiresDesign: true }" @sablon-change.window="requiresDesign = ($event.detail !== 'No Printing (Plain)')">
                        
                        {{-- Jumlah --}}
                        <div>
                            <label class="form-label">Quantity (min. {{ $product->min_order }} pcs) <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="decreaseQty('qty-input')"
                                        class="w-10 h-10 bg-dark-100 hover:bg-dark-200 rounded-xl font-bold text-dark-700 transition-colors flex items-center justify-center">−</button>
                                <input type="number" name="quantity" id="qty-input"
                                       value="{{ $product->min_order }}" min="{{ $product->min_order }}"
                                       class="form-input w-24 text-center font-bold" required>
                                <button type="button" onclick="increaseQty('qty-input')"
                                        class="w-10 h-10 bg-dark-100 hover:bg-dark-200 rounded-xl font-bold text-dark-700 transition-colors flex items-center justify-center">+</button>
                            </div>
                        </div>

                        {{-- Upload / Pilih Desain --}}
                        <div x-data="{ 
                            designSource: 'upload', 
                            selectedDesignId: '', 
                            selectedDesignImage: '', 
                            selectedDesignTitle: '',
                            showModal: false,
                            designs: {{ $designs->toJson() }},
                            selectDesign(id, image, title) {
                                this.selectedDesignId = id;
                                this.selectedDesignImage = image;
                                this.selectedDesignTitle = title;
                                this.showModal = false;
                            }
                        }">
                            <label class="form-label">Print Design <span class="text-red-500" x-show="requiresDesign">*</span></label>
                            
                            <div class="flex gap-3 mb-4" x-show="requiresDesign">
                                <button type="button" @click="designSource = 'upload'; selectedDesignId = ''" 
                                        :class="designSource === 'upload' ? 'bg-primary-400 border-dark-950 shadow-brutal-sm' : 'bg-white border-dark-200 text-dark-500'"
                                        class="flex-1 py-2 border-2 font-bold text-xs uppercase tracking-wider transition-all">
                                    Upload Own
                                </button>
                                <button type="button" @click="designSource = 'reference'; showModal = true"
                                        :class="designSource === 'reference' ? 'bg-primary-400 border-dark-950 shadow-brutal-sm' : 'bg-white border-dark-200 text-dark-500'"
                                        class="flex-1 py-2 border-2 font-bold text-xs uppercase tracking-wider transition-all">
                                    Choose Reference
                                </button>
                            </div>

                            <div x-show="!requiresDesign" class="bg-dark-50 border-2 border-dashed border-dark-200 rounded-xl p-4 text-center text-dark-400 text-sm font-medium">
                                No design needed for plain items
                            </div>

                            <div x-show="requiresDesign">
                                {{-- Hidden inputs --}}
                                <input type="hidden" name="design_id" :value="selectedDesignId">

                                {{-- Option 1: Upload --}}
                                <div x-show="designSource === 'upload'" class="space-y-3">
                                    <div class="border-2 border-dashed border-dark-200 rounded-xl p-5 text-center hover:border-primary-400 transition-colors cursor-pointer"
                                         onclick="document.getElementById('design-input').click()">
                                        <input type="file" name="custom_design" id="design-input" class="sr-only"
                                               accept=".jpg,.jpeg,.png,.pdf,.ai,.cdr"
                                               onchange="previewImage(this, 'design-preview')">
                                        <img id="design-preview" class="hidden w-32 h-32 object-contain mx-auto mb-3 rounded-xl shadow-brutal-sm border-2 border-dark-950">
                                        <div id="design-placeholder">
                                            <svg class="w-10 h-10 text-dark-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <p class="text-dark-500 text-sm">Click to upload design</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Selected Reference Preview --}}
                                <div x-show="designSource === 'reference' && selectedDesignId" class="animate-fade-in">
                                    <div class="flex items-center gap-4 bg-white border-2 border-dark-950 p-3 shadow-brutal-sm mb-2">
                                        <div class="w-16 h-16 bg-dark-50 border-2 border-dark-950 p-1 flex-shrink-0">
                                            <img :src="selectedDesignImage" class="w-full h-full object-contain">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] text-dark-500 font-bold uppercase tracking-widest mb-1">Selected Design</p>
                                            <h4 class="text-dark-950 font-black text-sm uppercase truncate" x-text="selectedDesignTitle"></h4>
                                            <button type="button" @click="showModal = true" class="text-primary-600 font-bold text-xs hover:underline mt-1">Change Design</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Modal for Reference --}}
                            <div x-show="showModal" 
                                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 style="display: none;">
                                
                                <div class="absolute inset-0 bg-dark-950/80 backdrop-blur-sm" @click="showModal = false"></div>
                                
                                <div class="relative bg-[#f4f4f0] border-4 border-dark-950 w-full max-w-4xl max-h-[90vh] flex flex-col shadow-brutal-lg">
                                    {{-- Modal Header --}}
                                    <div class="flex items-center justify-between p-6 border-b-4 border-dark-950 bg-primary-400">
                                        <h3 class="text-2xl font-display font-black text-dark-950 uppercase tracking-tight">CHOOSE DESIGN REFERENCE</h3>
                                        <button type="button" @click="showModal = false" class="w-10 h-10 border-2 border-dark-950 bg-white flex items-center justify-center hover:bg-red-400 transition-colors shadow-brutal-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>

                                    {{-- Modal Body --}}
                                    <div class="flex-1 overflow-y-auto p-6 admin-scrollbar">
                                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                            @foreach($designs as $design)
                                            <div @click="selectDesign('{{ $design->id }}', '{{ $design->image_url }}', '{{ $design->title }}')"
                                                 class="group bg-white border-3 border-dark-950 cursor-pointer transition-all hover:-translate-y-1 hover:shadow-brutal active:translate-y-0 active:shadow-none flex flex-col h-full">
                                                <div class="h-40 bg-white border-b-3 border-dark-950 p-4 flex items-center justify-center overflow-hidden">
                                                    <img src="{{ $design->image_url }}" alt="{{ $design->title }}" class="max-w-full max-h-full object-contain">
                                                </div>
                                                <div class="p-3 bg-white flex-1">
                                                    <h4 class="text-dark-950 font-bold text-[10px] uppercase truncate mb-1">{{ $design->title }}</h4>
                                                    <p class="text-dark-500 text-[9px] line-clamp-2 leading-tight">{{ $design->description }}</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    {{-- Modal Footer --}}
                                    <div class="p-4 border-t-4 border-dark-950 bg-white text-right">
                                        <button type="button" @click="showModal = false" class="btn-outline btn-sm">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="form-label">Additional Notes <span class="text-red-500" x-show="requiresDesign">*</span></label>
                            <textarea name="notes" rows="3" class="form-input resize-none" :required="requiresDesign" placeholder="Example: The image should be a logo, design block on the front or back..."></textarea>
                            @error('notes')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full text-base py-4 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed grayscale' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ $product->stock <= 0 ? 'Sorry, Out of Stock' : 'Add to Cart' }}
                </button>
            </form>
            @else
            <div class="bg-primary-50 border border-primary-200 rounded-2xl p-6 text-center">
                <p class="text-primary-700 font-semibold mb-3">Login to order this product</p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ route('login') }}" class="btn-primary btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn-outline btn-sm">Register</a>
                </div>
            </div>
            @endauth
        </div>
    </div>

    {{-- ====== REVIEWS SECTION ====== --}}
    <div class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="section-title">Customer Reviews</h2>
            <div class="flex items-center gap-2">
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'fill-current' : 'text-dark-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="font-bold text-dark-900">{{ number_format($product->average_rating, 1) }}</span>
                <span class="text-dark-400 text-sm">({{ $product->reviews->count() }} reviews)</span>
            </div>
        </div>

        @if($product->reviews->isEmpty())
            <div class="bg-dark-50 rounded-2xl p-10 text-center border-2 border-dashed border-dark-200">
                <p class="text-dark-500 font-medium">No reviews yet. Be the first to order and review this product!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($product->reviews()->latest()->get() as $review)
                <div class="card-flat p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ $review->user->avatar_url }}" alt="" class="w-10 h-10 bg-white border-2 border-dark-950 object-cover">
                        <div>
                            <p class="font-black text-dark-950 text-sm uppercase tracking-tight">{{ $review->user->name }}</p>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= $review->rating ? 'fill-current' : 'text-dark-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <span class="ml-auto text-[10px] text-dark-400 font-bold uppercase">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-dark-700 text-sm leading-relaxed">"{{ $review->comment }}"</p>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ====== SIMILAR PRODUCTS ====== --}}
    @if($related->isNotEmpty())
    <div>
        <h2 class="section-title mb-8">Similar Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($related as $item)
            <a href="{{ route('products.show', $item->slug) }}" class="product-card group">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-44 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-dark-900 group-hover:text-primary-600 transition-colors mb-1">{{ $item->name }}</h3>
                    <p class="text-primary-600 font-bold">{{ $item->formatted_price }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@push('scripts')
<script>
    function increaseQty(id) {
        const input = document.getElementById(id);
        input.value = parseInt(input.value) + 1;
    }
    function decreaseQty(id) {
        const input = document.getElementById(id);
        const min = parseInt(input.getAttribute('min') || 1);
        if (parseInt(input.value) > min) {
            input.value = parseInt(input.value) - 1;
        }
    }
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById('design-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush
@endsection
