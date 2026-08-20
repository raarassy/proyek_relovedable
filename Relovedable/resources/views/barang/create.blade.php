@extends('layouts.app')
@section('title', 'Tambah Barang')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Upload Barang</h1>
    <p class="text-sm text-gray-400 mb-6">Lengkapi detail barang preloved-mu.</p>

    <form action="{{ url('/barang/store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-relove-100 p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Foto Barang <span class="text-gray-300">(bisa lebih dari satu)</span></label>
            <input type="file" name="foto[]" multiple accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-3 file:rounded-full file:border-0 file:bg-relove-100 file:text-relove-600 file:px-4 file:py-2 file:font-semibold">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required
                   class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori') }}" required placeholder="Atasan, Celana, dll"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga') }}" required min="0"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kondisi</label>
                <select name="kondisi" required
                        class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
                    <option value="baru">Baru</option>
                    <option value="seperti_baru">Seperti Baru</option>
                    <option value="bekas" selected>Bekas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Metode Transaksi</label>
                <label class="flex items-center gap-2 mt-2.5 text-sm text-gray-600">
                    <input type="checkbox" name="metode_transaksi" value="1" {{ old('metode_transaksi') ? 'checked' : '' }}
                           class="rounded border-relove-300 text-relove-500 focus:ring-relove-300">
                    COD tersedia
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4" required
                      class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ url('/barang') }}" class="rounded-xl border border-relove-200 text-gray-600 font-semibold px-5 py-2.5 text-sm hover:bg-relove-50">Batal</a>
            <button type="submit" class="flex-1 rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5">Publikasikan Barang</button>
        </div>
    </form>
</div>
@endsection
