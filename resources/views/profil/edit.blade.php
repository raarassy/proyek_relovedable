@extends('layouts.app')
@section('title', 'Edit Profil')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Profil</h1>

    <form action="{{ url('/profil') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-relove-100 p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Foto profil --}}
        <div class="flex items-center gap-4">
            @if($user->foto_profil)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->foto_profil) }}" class="w-16 h-16 rounded-full object-cover border-2 border-relove-200">
            @else
                <span class="grid place-items-center w-16 h-16 rounded-full bg-relove-200 text-relove-700 text-2xl font-black">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
            @endif
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-600 mb-1">Foto Profil</label>
                <input type="file" name="foto_profil" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-3 file:rounded-full file:border-0 file:bg-relove-100 file:text-relove-600 file:px-4 file:py-2 file:font-semibold">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required
                   class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">No. HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
        </div>

        <hr class="border-relove-50">
        <p class="text-sm font-semibold text-gray-600">Ganti Password <span class="font-normal text-gray-300">(kosongkan jika tidak diubah)</span></p>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Password Baru</label>
                <input type="password" name="password"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi</label>
                <input type="password" name="password_confirmation"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ url('/profil') }}" class="rounded-xl border border-relove-200 text-gray-600 font-semibold px-5 py-2.5 text-sm hover:bg-relove-50">Batal</a>
            <button type="submit" class="flex-1 rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
