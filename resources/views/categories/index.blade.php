@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl">Kategori</h1>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
    @endif
</div>

<div class="card">
    @if($categories->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Service Provider</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $index => $category)
                    <tr>
                        <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?? '-' }}</td>
                        <td><span class="badge badge-green">{{ $category->serviceProviders->count() }}</span></td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('categories.show', $category->uuid) }}" class="btn btn-primary" style="font-size: 0.875rem;">Lihat</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('categories.edit', $category->uuid) }}" class="btn btn-success" style="font-size: 0.875rem;">Edit</a>
                                    <form action="{{ route('categories.destroy', $category->uuid) }}" method="POST" style="display: inline;">
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
            {{ $categories->appends(request()->query())->links() }}
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Belum ada kategori.</p>
    @endif
</div>
@endsection
