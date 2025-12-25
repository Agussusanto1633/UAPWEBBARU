@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="mb-6">
    <a href="{{ auth()->user()->isAdmin() ? route('bookings.index') : route('bookings.my') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <!-- Informasi Booking -->
    <div class="card">
        <h2 class="text-xl mb-4">Informasi Booking</h2>

        <div class="info-item">
            <label>Status</label>
            <div>
                @if($booking->status === 'pending')
                    <span class="badge" style="background: #fef3c7; color: #92400e;">Menunggu</span>
                @elseif($booking->status === 'confirmed')
                    <span class="badge" style="background: #dbeafe; color: #1e40af;">Dikonfirmasi</span>
                @elseif($booking->status === 'completed')
                    <span class="badge badge-green">Selesai</span>
                @else
                    <span class="badge" style="background: #fee2e2; color: #991b1b;">Dibatalkan</span>
                @endif
            </div>
        </div>

        <div class="info-item">
            <label>Tanggal Booking</label>
            <div>{{ $booking->booking_date->format('d F Y') }}</div>
        </div>

        <div class="info-item">
            <label>Waktu Booking</label>
            <div>{{ $booking->booking_time->format('H:i') }} WIB</div>
        </div>

        @if($booking->notes)
            <div class="info-item">
                <label>Catatan</label>
                <div>{{ $booking->notes }}</div>
            </div>
        @endif

        <div class="info-item">
            <label>Dibuat Tanggal</label>
            <div>{{ $booking->created_at->format('d F Y H:i') }}</div>
        </div>
    </div>

    <!-- Informasi Customer -->
    <div class="card">
        <h2 class="text-xl mb-4">Informasi Pelanggan</h2>

        @if(auth()->user()->isAdmin())
            <div class="info-item">
                <label>User Account</label>
                <div>
                    <strong>{{ $booking->user->name }}</strong><br>
                    <small style="color: #6b7280;">{{ $booking->user->email }}</small>
                </div>
            </div>
        @endif

        <div class="info-item">
            <label>Nama Lengkap</label>
            <div>{{ $booking->customer_name }}</div>
        </div>

        <div class="info-item">
            <label>Nomor Telepon</label>
            <div>{{ $booking->customer_phone }}</div>
        </div>

        @if($booking->customer_address)
            <div class="info-item">
                <label>Alamat</label>
                <div>{{ $booking->customer_address }}</div>
            </div>
        @endif
    </div>

    <!-- Informasi Service Provider -->
    <div class="card" style="grid-column: span 2;">
        <h2 class="text-xl mb-4">Informasi Service Provider</h2>

        <div style="display: flex; gap: 1.5rem;">
            @if($booking->serviceProvider->photo)
                <img src="{{ asset('storage/' . $booking->serviceProvider->photo) }}" alt="{{ $booking->serviceProvider->name }}" style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px;">
            @else
                <div style="width: 200px; height: 200px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 3rem;">📷</div>
            @endif

            <div style="flex: 1;">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">{{ $booking->serviceProvider->name }}</h3>
                <span class="badge badge-blue" style="margin-bottom: 1rem; display: inline-block;">{{ $booking->category->name }}</span>

                @if($booking->serviceProvider->phone)
                    <div class="info-item">
                        <label>Telepon</label>
                        <div>{{ $booking->serviceProvider->phone }}</div>
                    </div>
                @endif

                @if($booking->serviceProvider->email)
                    <div class="info-item">
                        <label>Email</label>
                        <div>{{ $booking->serviceProvider->email }}</div>
                    </div>
                @endif

                @if($booking->serviceProvider->address)
                    <div class="info-item">
                        <label>Alamat</label>
                        <div>{{ $booking->serviceProvider->address }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Update Status (Admin Only) -->
    @if(auth()->user()->isAdmin())
        <div class="card" style="grid-column: span 2;">
            <h2 class="text-xl mb-4">Update Status Booking</h2>

            <form action="{{ route('bookings.update-status', $booking->uuid) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="status">Ubah Status</label>
                    <select id="status" name="status" required>
                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    @endif

    <!-- Cancel Booking (User Only) -->
    @if(!auth()->user()->isAdmin() && $booking->status === 'pending')
        <div class="card" style="grid-column: span 2;">
            <h2 class="text-xl mb-4">Batalkan Booking</h2>
            <p style="color: #6b7280; margin-bottom: 1rem;">Jika Anda ingin membatalkan booking ini, klik tombol di bawah.</p>

            <form action="{{ route('bookings.cancel', $booking->uuid) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                @csrf
                <button type="submit" class="btn btn-danger">Batalkan Booking</button>
            </form>
        </div>
    @endif
</div>

<style>
.info-item {
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
}
.info-item:last-child {
    border-bottom: none;
}
.info-item label {
    display: block;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}
.info-item div {
    font-size: 1rem;
}
</style>
@endsection
