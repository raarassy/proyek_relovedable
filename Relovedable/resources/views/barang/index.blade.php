@extends('layouts.app')
@section('title', 'Kelola Barang')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Barang</h1>
            <p class="text-sm text-gray-400">🏪 {{ $toko->nama_toko }}</p>
        </div>
        <a href="{{ url('/barang/create') }}" class="rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold px-5 py-2.5 text-sm">+ Tambah Barang</a>
    </div>

    @if($barangs->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-relove-200 p-16 text-center text-gray-400">
            Belum ada barang. Klik "Tambah Barang" untuk mulai jualan.
        </div>
    @else
        <div class="bg-white rounded-2xl border border-relove-100 divide-y divide-relove-50">
            @foreach($barangs as $barang)
                @php($foto = $barang->fotoBarangs->first())
                <div class="flex items-center gap-4 p-4">
                    <div class="w-16 h-16 rounded-xl bg-relove-50 overflow-hidden shrink-0">
                        @if($foto)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full grid place-items-center text-relove-300 text-2xl">👕</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ url('/barang/' . $barang->id_barang) }}" class="font-semibold text-gray-800 hover:text-relove-600 line-clamp-1">{{ $barang->nama_barang }}</a>
                        <p class="text-sm text-relove-600 font-bold">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $barang->status_barang === 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ ucfirst($barang->status_barang) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/barang/edit/' . $barang->id_barang) }}" class="rounded-lg border border-relove-200 text-relove-600 text-sm px-3 py-1.5 hover:bg-relove-50">Edit</a>
                        <form action="{{ url('/barang/' . $barang->id_barang) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 text-red-500 text-sm px-3 py-1.5 hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
