@extends('layouts.app')

@section('title', 'Service Providers')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl">Service Providers</h1>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('service-providers.create') }}" class="btn btn-primary">+ Tambah Service Provider</a>
    @endif
</div>

<!-- Filter Kategori -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('service-providers.index') }}">
        <div class="flex gap-2" style="align-items: flex-end;">
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label for="category">Filter Kategori</label>
                <select id="category" name="category" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if(request('category'))
                <a href="{{ route('service-providers.index') }}" class="btn btn-secondary">Reset Filter</a>
            @endif
        </div>
    </form>
</div>

<div class="card">
    @if($serviceProviders->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serviceProviders as $index => $provider)
                    <tr>
                        <td>{{ ($serviceProviders->currentPage() - 1) * $serviceProviders->perPage() + $index + 1 }}</td>
                        <td>
                            @if($provider->photo)
                                <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @else
                                <div style="width: 50px; height: 50px; background: #e5e7eb; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6b7280;">-</div>
                            @endif
                        </td>
                        <td>{{ $provider->name }}</td>
                        <td><span class="badge badge-blue">{{ $provider->category->name }}</span></td>
                        <td>{{ $provider->phone ?? '-' }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('service-providers.show', $provider->uuid) }}" class="btn btn-primary" style="font-size: 0.875rem;">Lihat</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('service-providers.edit', $provider->uuid) }}" class="btn btn-success" style="font-size: 0.875rem;">Edit</a>
                                    <form action="{{ route('service-providers.destroy', $provider->uuid) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="font-size: 0.875rem;" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $serviceProviders->appends(request()->query())->links() }}
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Belum ada service provider.</p>
    @endif
</div>
@endsection
