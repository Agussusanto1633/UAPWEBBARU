@extends('layouts.app')

@section('title', 'Edit Service Provider')

@section('content')
<div class="mb-6">
    <a href="{{ route('service-providers.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <h1 class="text-xl mb-6">Edit Service Provider</h1>

    <form method="POST" action="{{ route('service-providers.update', $provider->uuid) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $provider->name) }}" required minlength="3" maxlength="100">
            @error('name')
                <small style="color: #dc2626;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="category_id">Kategori *</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $provider->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <small style="color: #dc2626;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Telepon</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone', $provider->phone) }}" pattern="[0-9+\-\(\)\s]+" minlength="10" maxlength="20" placeholder="Contoh: 081234567890 atau +62-812-3456-7890">
            <small style="color: #6b7280;">Format: hanya angka, +, -, (, ), dan spasi. Minimal 10 digit.</small>
            @error('phone')
                <small style="color: #dc2626;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $provider->email) }}" maxlength="255" placeholder="contoh@email.com">
            @error('email')
                <small style="color: #dc2626;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Alamat</label>
            <textarea id="address" name="address" maxlength="500">{{ old('address', $provider->address) }}</textarea>
            <small style="color: #6b7280;">Maksimal 500 karakter.</small>
            @error('address')
                <small style="color: #dc2626;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" maxlength="1000">{{ old('description', $provider->description) }}</textarea>
            <small style="color: #6b7280;">Maksimal 1000 karakter.</small>
            @error('description')
                <small style="color: #dc2626;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="photo">Foto</label>
            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
            <small style="color: #6b7280;">Maksimal 2MB. Format: JPEG, PNG, JPG, GIF, WEBP</small>
            @error('photo')
                <small style="color: #dc2626;">{{ $message }}</small>
            @enderror
            @if($provider->photo)
                <div style="margin-top: 0.5rem;">
                    <p style="font-size: 0.875rem; color: #6b7280;">Foto saat ini:</p>
                    <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}" class="photo-preview">
                </div>
            @endif
        </div>

        <div class="form-group" style="margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('service-providers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
