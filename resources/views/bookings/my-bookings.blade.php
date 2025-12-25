@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl">Booking Saya</h1>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">+ Pesan Jasa Baru</a>
</div>

<div class="card">
    @if($bookings->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Service Provider</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $index => $booking)
                    <tr>
                        <td>{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $index + 1 }}</td>
                        <td>{{ $booking->serviceProvider->name }}</td>
                        <td><span class="badge badge-blue">{{ $booking->category->name }}</span></td>
                        <td>{{ $booking->booking_date->format('d/m/Y') }} {{ $booking->booking_time->format('H:i') }}</td>
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
                            <div class="flex gap-2">
                                <a href="{{ route('bookings.show', $booking->uuid) }}" class="btn btn-primary" style="font-size: 0.875rem;">Detail</a>
                                @if($booking->status === 'pending')
                                    <form action="{{ route('bookings.cancel', $booking->uuid) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="font-size: 0.875rem;" onclick="return confirm('Yakin ingin membatalkan booking?')">Batalkan</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $bookings->links() }}
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Anda belum memiliki booking.</p>
        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">Pesan Jasa Sekarang</a>
        </div>
    @endif
</div>
@endsection
