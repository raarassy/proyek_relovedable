@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Kartu profil --}}
    <div class="bg-gradient-to-br from-relove-100 to-white rounded-3xl border border-relove-100 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row gap-5 sm:items-center">
            @if($user->foto_profil)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($user->foto_profil) }}" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow shrink-0">
            @else
                <span class="grid place-items-center w-20 h-20 rounded-full bg-relove-300 text-white text-3xl font-black shrink-0">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
            @endif
            <div class="flex-1">
                <h1 class="text-2xl font-extrabold text-gray-900">{{ $user->nama }}</h1>
                <p class="text-sm text-gray-500">{{ '@' . $user->username }}</p>
                <p class="text-sm text-gray-400">{{ $user->email }}@if($user->no_hp) &middot; {{ $user->no_hp }}@endif</p>
                <span class="inline-block mt-2 text-xs font-semibold px-3 py-1 rounded-full bg-relove-100 text-relove-600 capitalize">{{ $user->role }}</span>
            </div>
            <a href="{{ url('/profil/edit') }}" class="rounded-full border border-relove-300 text-relove-600 font-semibold px-5 py-2 text-sm text-center hover:bg-relove-50">Edit Profil</a>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-6">
            <div class="bg-white/70 rounded-xl p-3 text-center">
                <p class="text-xl font-extrabold text-relove-600">{{ $jumlahMengikuti }}</p>
                <p class="text-xs text-gray-400">Toko Diikuti</p>
            </div>
            <a href="{{ url('/chat') }}" class="bg-white/70 rounded-xl p-3 text-center hover:bg-white">
                <p class="text-xl font-extrabold text-relove-600">💬</p>
                <p class="text-xs text-gray-400">Pesan</p>
            </a>
        </div>
    </div>

    {{-- Aksi cepat --}}
    <div class="grid sm:grid-cols-3 gap-3 mt-6">
        <a href="{{ url('/chat') }}" class="bg-white rounded-2xl border border-relove-100 p-4 hover:border-relove-300 text-center">💬<p class="font-semibold text-sm mt-1">Pesan</p></a>
        <a href="{{ url('/katalog') }}" class="bg-white rounded-2xl border border-relove-100 p-4 hover:border-relove-300 text-center">🛍️<p class="font-semibold text-sm mt-1">Jelajahi</p></a>
        @if($user->isPenjual() && $user->toko)
            <a href="{{ url('/toko/' . $user->toko->id_toko) }}" class="bg-white rounded-2xl border border-relove-100 p-4 hover:border-relove-300 text-center">🏪<p class="font-semibold text-sm mt-1">Toko Saya</p></a>
        @elseif(!$user->isAdmin())
            <a href="{{ url('/penjual/daftar') }}" class="bg-white rounded-2xl border border-relove-100 p-4 hover:border-relove-300 text-center">✨<p class="font-semibold text-sm mt-1 text-relove-600">Jadi Penjual</p></a>
        @endif
        @if($user->isAdmin())
            <a href="{{ url('/admin/verifikasi') }}" class="bg-white rounded-2xl border border-relove-100 p-4 hover:border-relove-300 text-center">🛡️<p class="font-semibold text-sm mt-1">Panel Admin</p></a>
        @endif
    </div>

    {{-- Mengikuti --}}
    <section class="mt-8">
        <h2 class="text-lg font-bold text-gray-800 mb-3">Toko yang Diikuti</h2>
        @if($mengikuti->isEmpty())
            <p class="text-gray-400 text-sm">Belum mengikuti toko mana pun.</p>
        @else
            <div class="space-y-2">
                @foreach($mengikuti as $f)
                    @continue(! $f->diikuti)
                    <div class="bg-white rounded-xl border border-relove-100 p-3 flex items-center gap-3">
                        <span class="grid place-items-center w-10 h-10 rounded-full bg-relove-200 text-relove-700 font-bold">{{ strtoupper(substr($f->diikuti->nama, 0, 1)) }}</span>
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-gray-800">{{ $f->diikuti->toko->nama_toko ?? $f->diikuti->nama }}</p>
                            <p class="text-xs text-gray-400">{{ '@' . $f->diikuti->username }}</p>
                        </div>
                        @if($f->diikuti->toko)
                            <a href="{{ url('/toko/' . $f->diikuti->toko->id_toko) }}" class="text-relove-500 text-sm font-semibold">Lihat →</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
