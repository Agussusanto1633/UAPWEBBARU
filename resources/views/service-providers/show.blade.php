@extends('layouts.app')

@section('title', 'Detail Service Provider')

@section('content')
<div class="mb-6">
    <a href="{{ route('service-providers.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <div>
            @if($provider->photo)
                <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}" style="width: 100%; border-radius: 8px;">
            @else
                <div style="width: 100%; aspect-ratio: 1; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 3rem;">📷</div>
            @endif
        </div>

        <div>
            <h1 class="text-2xl mb-4">{{ $provider->name }}</h1>

            <div style="margin-bottom: 1rem;">
                <span class="badge badge-blue" style="font-size: 1rem;">{{ $provider->category->name }}</span>
            </div>

            @if($provider->phone)
                <div style="margin-bottom: 1rem;">
                    <strong>Telepon:</strong><br>
                    <span style="color: #2563eb;">{{ $provider->phone }}</span>
                </div>
            @endif

            @if($provider->address)
                <div style="margin-bottom: 1rem;">
                    <strong>Alamat:</strong><br>
                    {{ $provider->address }}
                </div>
            @endif

            @if($provider->description)
                <div style="margin-bottom: 1rem;">
                    <strong>Deskripsi:</strong><br>
                    {{ $provider->description }}
                </div>
            @endif

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('service-providers.edit', $provider->uuid) }}" class="btn btn-success">Edit</a>
                    <form action="{{ route('service-providers.destroy', $provider->uuid) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
