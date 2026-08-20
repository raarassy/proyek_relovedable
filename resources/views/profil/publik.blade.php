@extends('layouts.app')
@section('title', 'Profil ' . $user->nama)

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
                @if($user->created_at)<p class="text-sm text-gray-400">Bergabung {{ $user->created_at->translatedFormat('F Y') }}</p>@endif
                <span class="inline-block mt-2 text-xs font-semibold px-3 py-1 rounded-full bg-relove-100 text-relove-600 capitalize">{{ $user->role }}</span>
            </div>
            @if($user->isPenjual() && $user->toko)
                <a href="{{ url('/toko/' . $user->toko->id_toko) }}" class="rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold px-5 py-2 text-sm text-center shrink-0">🏪 Lihat Toko</a>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-3 mt-6">
            <div class="bg-white/70 rounded-xl p-3 text-center">
                <p class="text-xl font-extrabold text-relove-600">{{ $jumlahPembelian }}</p>
                <p class="text-xs text-gray-400">Pembelian Selesai</p>
            </div>
            <div class="bg-white/70 rounded-xl p-3 text-center">
                <p class="text-xl font-extrabold text-relove-600">{{ $ulasan->count() }}</p>
                <p class="text-xs text-gray-400">Ulasan Ditulis</p>
            </div>
        </div>
    </div>

    {{-- Ulasan yang ditulis pengguna --}}
    <section class="mt-8">
        <h2 class="text-lg font-bold text-gray-800 mb-3">Ulasan dari {{ $user->nama }}</h2>
        @if($ulasan->isEmpty())
            <p class="text-gray-400 text-sm">Belum pernah menulis ulasan.</p>
        @else
            <div class="space-y-3">
                @foreach($ulasan as $r)
                    <div class="bg-white rounded-2xl border border-relove-100 p-4">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-semibold text-sm text-gray-800 truncate">
                                {{ $r->transaksi?->barang?->nama_barang ?? 'Barang' }}
                                @if($r->penjual)<span class="text-gray-400 font-normal">· {{ $r->penjual->toko->nama_toko ?? $r->penjual->nama }}</span>@endif
                            </p>
                            <span class="text-xs text-gray-300 shrink-0">{{ $r->tanggal_review ? $r->tanggal_review->diffForHumans() : '' }}</span>
                        </div>
                        <p class="text-relove-500 text-sm leading-none mt-1">{{ str_repeat('★', (int) $r->rating) }}<span class="text-gray-200">{{ str_repeat('★', 5 - (int) $r->rating) }}</span></p>
                        @if($r->ulasan)<p class="text-sm text-gray-600 mt-2 whitespace-pre-line">{{ $r->ulasan }}</p>@endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
