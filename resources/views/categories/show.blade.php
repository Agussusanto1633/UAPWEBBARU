@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
<div class="mb-6">
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <h1 class="text-2xl mb-4">{{ $category->name }}</h1>

    @if($category->description)
        <p style="color: #6b7280; margin-bottom: 1.5rem;">{{ $category->description }}</p>
    @endif

    <div style="margin-bottom: 1.5rem;">
        <span class="badge badge-green" style="font-size: 1rem;">{{ $category->serviceProviders->count() }} Service Provider</span>
    </div>

    <div style="margin-top: 2rem;">
        <h2 class="text-xl mb-4">Service Providers dalam Kategori Ini</h2>
        @if($category->serviceProviders->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->serviceProviders as $index => $provider)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $provider->name }}</td>
                            <td>{{ $provider->phone ?? '-' }}</td>
                            <td>
                                <a href="{{ route('service-providers.show', $provider->uuid) }}" class="btn btn-primary" style="font-size: 0.875rem;">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #6b7280; padding: 2rem;">Belum ada service provider dalam kategori ini.</p>
        @endif
    </div>

    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.edit', $category->uuid) }}" class="btn btn-success">Edit Kategori</a>
            <form action="{{ route('categories.destroy', $category->uuid) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
            </form>
        @endif
    </div>
</div>
@endsection
