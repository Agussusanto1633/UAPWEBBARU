@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl">Dashboard</h1>
    <p>Selamat datang, {{ auth()->user()->name }}! 👋</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="border-left: 4px solid #2563eb;">
        <h3 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Total Service Providers</h3>
        <p style="font-size: 2rem; font-weight: bold; color: #2563eb;">{{ $totalServiceProviders }}</p>
        <a href="{{ route('service-providers.index') }}" style="color: #2563eb; text-decoration: none; font-size: 0.875rem;">Lihat semua →</a>
    </div>

    <div class="card" style="border-left: 4px solid #10b981;">
        <h3 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Total Kategori</h3>
        <p style="font-size: 2rem; font-weight: bold; color: #10b981;">{{ $totalCategories }}</p>
        <a href="{{ route('categories.index') }}" style="color: #10b981; text-decoration: none; font-size: 0.875rem;">Lihat semua →</a>
    </div>

    @if(auth()->user()->isAdmin())
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <h3 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Total Booking</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #f59e0b;">{{ $totalBookings }}</p>
            <a href="{{ route('bookings.index') }}" style="color: #f59e0b; text-decoration: none; font-size: 0.875rem;">Lihat semua →</a>
        </div>

        <div class="card" style="border-left: 4px solid #8b5cf6;">
            <h3 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Booking Status</h3>
            <div style="display: flex; gap: 1rem; margin-top: 0.75rem;">
                <div>
                    <p style="font-size: 1.25rem; font-weight: bold; color: #f59e0b;">{{ $pendingBookings }}</p>
                    <p style="font-size: 0.75rem; color: #6b7280;">Menunggu</p>
                </div>
                <div>
                    <p style="font-size: 1.25rem; font-weight: bold; color: #2563eb;">{{ $confirmedBookings }}</p>
                    <p style="font-size: 0.75rem; color: #6b7280;">Konfirmasi</p>
                </div>
                <div>
                    <p style="font-size: 1.25rem; font-weight: bold; color: #10b981;">{{ $completedBookings }}</p>
                    <p style="font-size: 0.75rem; color: #6b7280;">Selesai</p>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Service Providers per Kategori -->
<div class="card" style="margin-bottom: 2rem;">
    <h2 class="text-xl mb-4">Service Providers per Kategori</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        @foreach($categoriesWithCount as $category)
            <div style="padding: 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 600; color: white;">{{ $category->name }}</h3>
                    <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">
                        {{ $category->service_providers_count }} Provider{{ $category->service_providers_count != 1 ? 's' : '' }}
                    </span>
                </div>
                @if($category->description)
                    <p style="font-size: 0.875rem; color: rgba(255,255,255,0.9); margin-top: 0.5rem;">{{ Str::limit($category->description, 80) }}</p>
                @endif
                <a href="{{ route('service-providers.index') }}?category={{ $category->id }}" style="display: inline-block; margin-top: 1rem; color: white; text-decoration: underline; font-size: 0.875rem;">
                    Lihat providers →
                </a>
            </div>
        @endforeach
        
        @if($categoriesWithCount->count() === 0)
            <p style="text-align: center; color: #6b7280; padding: 2rem; grid-column: 1 / -1;">Belum ada kategori.</p>
        @endif
    </div>
</div>

@if(auth()->user()->isAdmin())
    <div class="card" style="margin-bottom: 2rem;">
        <h2 class="text-xl mb-4">Booking Terbaru</h2>
        @if($recentBookings->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Service Provider</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->serviceProvider->name }}</td>
                            <td><span class="badge badge-blue">{{ $booking->category->name }}</span></td>
                            <td>{{ $booking->booking_date->format('d M Y') }}</td>
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
        @else
            <p style="text-align: center; color: #6b7280; padding: 2rem;">Belum ada booking.</p>
        @endif
    </div>
@endif

<div class="card">
    <h2 class="text-xl mb-4">Service Provider Terbaru</h2>
    @if($recentServiceProviders->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentServiceProviders as $provider)
                    <tr>
                        <td>{{ $provider->name }}</td>
                        <td>{{ $provider->category->name }}</td>
                        <td>{{ $provider->phone ?? '-' }}</td>
                        <td>
                            <a href="{{ route('service-providers.show', $provider->uuid) }}" class="btn btn-primary" style="font-size: 0.875rem;">Lihat</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Belum ada service provider.</p>
    @endif
</div>
@endsection

