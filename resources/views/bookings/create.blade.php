@extends('layouts.app')

@section('title', 'Pesan Jasa')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl">Pesan Jasa</h1>
    <p style="color: #6b7280; margin-top: 0.5rem;">Pilih kategori dan service provider yang Anda inginkan</p>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <h2 class="text-xl mb-4">Filter Berdasarkan Kategori</h2>
    <form method="GET" action="{{ route('bookings.create') }}">
        <div class="flex gap-2" style="align-items: flex-end;">
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label for="category_id">Kategori Jasa</label>
                <select id="category_id" name="category_id" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

@if($serviceProviders->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        @foreach($serviceProviders as $provider)
            <div class="card">
                @if($provider->photo)
                    <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                @else
                    <div style="width: 100%; height: 200px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 3rem; margin-bottom: 1rem;">📷</div>
                @endif

                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">{{ $provider->name }}</h3>
                <p class="badge badge-blue" style="margin-bottom: 0.75rem;">{{ $provider->category->name }}</p>

                @if($provider->phone)
                    <p style="color: #6b7280; margin-bottom: 0.5rem;">
                        📞 {{ $provider->phone }}
                    </p>
                @endif

                @if($provider->address)
                    <p style="color: #6b7280; margin-bottom: 1rem;">
                        📍 {{ Str::limit($provider->address, 50) }}
                    </p>
                @endif

                <div style="display: flex; gap: 0.5rem; margin-top: auto;">
                    <a href="{{ route('service-providers.show', $provider->uuid) }}" class="btn btn-secondary" style="flex: 1; text-align: center;">Detail</a>
                    <button onclick="showBookingForm('{{ $provider->id }}', '{{ $provider->name }}', '{{ $provider->category->name }}')" class="btn btn-primary" style="flex: 1;">Pesan Sekarang</button>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #6b7280; font-size: 1.125rem;">Tidak ada service provider yang tersedia untuk kategori ini.</p>
    </div>
@endif

<!-- Modal Form Booking -->
<div id="bookingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
        <div class="card" style="max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 class="text-xl">Form Pemesanan</h2>
                <button onclick="closeBookingForm()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">&times;</button>
            </div>

            <div style="background: #f3f4f6; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                <p style="margin-bottom: 0.5rem;"><strong>Service Provider:</strong> <span id="modalProviderName"></span></p>
                <p><strong>Kategori:</strong> <span id="modalCategoryName"></span></p>
            </div>

            <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                @csrf
                <input type="hidden" name="service_provider_id" id="serviceProviderId">

                <div class="form-group">
                    <label for="customer_name">Nama Lengkap *</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ auth()->user()->name }}" required minlength="3" maxlength="100">
                    @error('customer_name')
                        <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_phone">Nomor Telepon *</label>
                    <input type="tel" id="customer_phone" name="customer_phone" required pattern="[0-9+\-\(\)\s]+" minlength="10" maxlength="20" placeholder="Contoh: 081234567890">
                    <small style="color: #6b7280;">Format: hanya angka, +, -, (, ), dan spasi. Minimal 10 digit.</small>
                    @error('customer_phone')
                        <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_address">Alamat</label>
                    <textarea id="customer_address" name="customer_address" placeholder="Alamat lengkap untuk pengerjaan jasa" maxlength="500"></textarea>
                    <small style="color: #6b7280;">Maksimal 500 karakter.</small>
                    @error('customer_address')
                        <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="booking_date">Tanggal Booking *</label>
                    <input type="date" id="booking_date" name="booking_date" min="{{ date('Y-m-d') }}" required>
                    @error('booking_date')
                        <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="booking_time">Waktu Booking *</label>
                    <input type="time" id="booking_time" name="booking_time" required>
                    @error('booking_time')
                        <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Catatan Tambahan</label>
                    <textarea id="notes" name="notes" placeholder="Catatan atau permintaan khusus" maxlength="500"></textarea>
                    <small style="color: #6b7280;">Maksimal 500 karakter.</small>
                    @error('notes')
                        <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Konfirmasi Pemesanan</button>
                    <button type="button" onclick="closeBookingForm()" class="btn btn-secondary" style="width: 100%; margin-top: 0.5rem;">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showBookingForm(providerId, providerName, categoryName) {
    document.getElementById('serviceProviderId').value = providerId;
    document.getElementById('modalProviderName').textContent = providerName;
    document.getElementById('modalCategoryName').textContent = categoryName;
    document.getElementById('bookingModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeBookingForm() {
    document.getElementById('bookingModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('bookingForm').reset();
}
</script>
@endsection
