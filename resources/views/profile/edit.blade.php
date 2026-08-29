@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="section-title">My Profile</h1>
        <p class="section-subtitle">Manage your personal information and shipping address</p>
    </div>

    <div class="card-flat p-6 sm:p-8">
        <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('put')

            {{-- AVATAR UPLOAD --}}
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b-2 border-dark-950 border-dashed mb-6">
                <div class="relative group">
                    <div class="w-32 h-32 bg-white border-3 border-dark-950 shadow-brutal overflow-hidden">
                        <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                    <label for="avatar" class="absolute -bottom-2 -right-2 w-10 h-10 bg-primary-400 border-2 border-dark-950 shadow-brutal-sm flex items-center justify-center cursor-pointer hover:bg-primary-500 transition-colors">
                        <svg class="w-5 h-5 text-dark-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                    </label>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="text-dark-950 font-black text-xl uppercase tracking-tight">Profile Picture</h3>
                    <p class="text-dark-500 text-sm mb-2">Upload a square photo to personalize your account.</p>
                    <p class="text-[10px] text-dark-400 font-bold uppercase tracking-widest">JPG, PNG (Max 2MB)</p>
                </div>
            </div>

            <div>
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="form-label">WhatsApp Number</label>
                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="Example: 081234567890" />
                @error('phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="form-label">Full Address</label>
                <textarea id="address" name="address" rows="4" class="form-input">{{ old('address', $user->address) }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-4 border-t-2 border-dark-950">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
