@extends('layouts.app')
@section('title', 'Daftar Penjual')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Daftar sebagai Penjual</h1>
    <p class="text-sm text-gray-400 mb-6">Lengkapi data & verifikasi identitas untuk mulai berjualan.</p>

    {{-- Status pengajuan terakhir --}}
    @if($pengajuan)
        @php
            $badge = [
                'pending' => ['bg-yellow-100 text-yellow-700', 'Menunggu verifikasi admin ⏳'],
                'disetujui' => ['bg-green-100 text-green-700', 'Disetujui ✅'],
                'ditolak' => ['bg-red-100 text-red-700', 'Ditolak ❌'],
            ][$pengajuan->status_verifikasi];
        @endphp
        <div class="mb-6 rounded-2xl border border-relove-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="font-semibold text-gray-700">Pengajuan: {{ $pengajuan->nama_toko }}</p>
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $badge[0] }}">{{ $badge[1] }}</span>
            </div>
            @if($pengajuan->status_verifikasi === 'pending')
                <p class="text-sm text-gray-400 mt-2">Mohon tunggu, admin sedang meninjau pengajuanmu.</p>
            @endif
        </div>
    @endif

    @if(!$pengajuan || $pengajuan->status_verifikasi === 'ditolak')
        <form action="{{ url('/penjual/daftar') }}" method="POST" enctype="multipart/form-data"
              class="bg-white rounded-2xl border border-relove-100 p-6 space-y-5">
            @csrf

            <h2 class="font-semibold text-gray-700">Data Identitas</h2>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">NIK (16 digit)</label>
                <input type="text" name="nik" value="{{ old('nik') }}" required inputmode="numeric" maxlength="16"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Foto KTP</label>
                <input type="file" name="foto_ktp" accept="image/*" required
                       class="w-full text-sm text-gray-500 file:mr-3 file:rounded-full file:border-0 file:bg-relove-100 file:text-relove-600 file:px-4 file:py-2 file:font-semibold">
                <p class="text-xs text-gray-400 mt-1">🔒 KTP disimpan privat & hanya dilihat admin untuk verifikasi.</p>
            </div>

            <h2 class="font-semibold text-gray-700 pt-2">Data Toko</h2>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Toko</label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" required
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Toko <span class="text-gray-300">(opsional)</span></label>
                <input type="text" name="alamat_toko" value="{{ old('alamat_toko') }}"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Toko <span class="text-gray-300">(opsional)</span></label>
                <textarea name="deskripsi_toko" rows="3"
                          class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">{{ old('deskripsi_toko') }}</textarea>
            </div>

            <button type="submit" class="w-full rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5">Kirim Pengajuan</button>
        </form>
    @endif
</div>
@endsection
