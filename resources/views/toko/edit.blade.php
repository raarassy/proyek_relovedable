@extends('layouts.app')
@section('title', 'Edit Toko')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Toko</h1>

    <form action="{{ url('/toko/update') }}" method="POST"
          class="bg-white rounded-2xl border border-relove-100 p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Toko</label>
            <input type="text" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" required
                   class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Toko</label>
            <input type="text" name="alamat_toko" value="{{ old('alamat_toko', $toko->alamat_toko) }}"
                   class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Toko</label>
            <textarea name="deskripsi_toko" rows="4"
                      class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">{{ old('deskripsi_toko', $toko->deskripsi_toko) }}</textarea>
        </div>

        <div class="flex gap-3">
            <a href="{{ url('/toko/' . $toko->id_toko) }}" class="rounded-xl border border-relove-200 text-gray-600 font-semibold px-5 py-2.5 text-sm hover:bg-relove-50">Batal</a>
            <button type="submit" class="flex-1 rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5">Simpan</button>
        </div>
    </form>
</div>
@endsection
