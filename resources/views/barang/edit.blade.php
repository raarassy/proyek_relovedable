@extends('layouts.app')
@section('title', 'Edit Barang')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Edit Barang</h1>
    <p class="text-sm text-gray-400 mb-6">Perbarui detail barangmu.</p>

    {{-- Foto yang sudah ada --}}
    @if($barang->fotoBarangs->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach($barang->fotoBarangs as $foto)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}"
                     class="w-20 h-20 rounded-xl object-cover border border-relove-100">
            @endforeach
        </div>
    @endif

    <form action="{{ url('/barang/update/' . $barang->id_barang) }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-relove-100 p-6 space-y-5">
        @csrf

        @php($sisaSlot = 8 - $barang->fotoBarangs->count())
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Tambah Foto <span class="text-gray-300">(maksimal 8 total)</span></label>
            <input type="file" name="foto[]" multiple accept="image/*" {{ $sisaSlot < 1 ? 'disabled' : '' }}
                   class="w-full text-sm text-gray-500 file:mr-3 file:rounded-full file:border-0 file:bg-relove-100 file:text-relove-600 file:px-4 file:py-2 file:font-semibold">
            <p class="text-xs {{ $sisaSlot < 1 ? 'text-red-500' : 'text-gray-400' }} mt-1.5">
                @if($sisaSlot < 1)
                    Sudah mencapai batas maksimal 8 foto.
                @else
                    Sudah ada {{ $barang->fotoBarangs->count() }} foto, bisa menambah {{ $sisaSlot }} lagi. Maks 4MB per foto.
                @endif
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required
                   class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
                @php($kategoriSekarang = old('kategori', $barang->kategori))
                <select name="kategori" required
                        class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
                    @unless(in_array($kategoriSekarang, \App\Models\Barang::KATEGORI))
                        <option value="{{ $kategoriSekarang }}" selected>{{ $kategoriSekarang }} (lama)</option>
                    @endunless
                    @foreach(\App\Models\Barang::KATEGORI as $kat)
                        <option value="{{ $kat }}" {{ $kategoriSekarang === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $barang->harga) }}" required min="0"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kondisi</label>
                <select name="kondisi" required
                        class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
                    @foreach(['baru' => 'Baru', 'seperti_baru' => 'Seperti Baru', 'bekas' => 'Bekas'] as $val => $label)
                        <option value="{{ $val }}" {{ $barang->kondisi === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                <select name="status_barang"
                        class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
                    @foreach(['tersedia' => 'Tersedia', 'terjual' => 'Terjual', 'nonaktif' => 'Nonaktif'] as $val => $label)
                        <option value="{{ $val }}" {{ $barang->status_barang === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Metode</label>
                <div class="flex flex-col gap-1.5 mt-2 text-sm text-gray-600">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="bisa_cod" value="1" {{ old('bisa_cod', $barang->bisa_cod) ? 'checked' : '' }}
                               class="rounded border-relove-300 text-relove-500 focus:ring-relove-300">
                        COD
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="bisa_ekspedisi" value="1" {{ old('bisa_ekspedisi', $barang->bisa_ekspedisi) ? 'checked' : '' }}
                               class="rounded border-relove-300 text-relove-500 focus:ring-relove-300">
                        Ekspedisi
                    </label>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4" required
                      class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ url('/barang') }}" class="rounded-xl border border-relove-200 text-gray-600 font-semibold px-5 py-2.5 text-sm hover:bg-relove-50">Batal</a>
            <button type="submit" class="flex-1 rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
