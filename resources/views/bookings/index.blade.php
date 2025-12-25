@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl">Riwayat Booking</h1>
    <p style="color: #6b7280; margin-top: 0.5rem;">Log pemesanan dari semua user</p>
</div>

<!-- Filter Status -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('bookings.index') }}" class="flex gap-2" style="align-items: flex-end;">
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label for="status">Filter Status</label>
            <select id="status" name="status" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
    </form>
</div>

<div class="card">
    @if($bookings->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Service Provider</th>
                    <th>Kategori</th>
                    <th>Tanggal Booking</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $index => $booking)
                    <tr>
                        <td>{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $index + 1 }}</td>
                        <td>
                            <div>
                                <strong>{{ $booking->user->name }}</strong><br>
                                <small style="color: #6b7280;">{{ $booking->user->email }}</small>
                            </div>
                        </td>
                        <td>{{ $booking->serviceProvider->name }}</td>
                        <td><span class="badge badge-blue">{{ $booking->category->name }}</span></td>
                        <td>{{ $booking->booking_date->format('d M Y') }}<br><small>{{ $booking->booking_time->format('H:i') }}</small></td>
                        <td>
                            @if($booking->status === 'pending')
                                <span class="badge" style="background: #fef3c7; color: #92400e;">Menunggu</span>
                            @elseif($booking->status === 'confirmed')
                                <span class="badge" style="background: #dbeafe; color: #1e40af;">Dikonfirmasi</span>
                            @elseif($booking->status === 'completed')
                                <span class="badge badge-green">Selesai</span>
                            @else
                                <span class="badge" style="background: #fee2e2; color: #991b1b;">Dibatalkan</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('bookings.show', $booking->uuid) }}" class="btn btn-primary" style="font-size: 0.875rem;">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $bookings->appends(request()->except('page'))->links() }}
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Belum ada booking.</p>
    @endif
</div>
@endsection
