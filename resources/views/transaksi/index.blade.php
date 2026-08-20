@extends('layouts.app')
@section('title', 'Transaksi')

@php
    $statusBadge = function ($s) {
        return [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'dibayar' => 'bg-blue-100 text-blue-700',
            'dikirim' => 'bg-indigo-100 text-indigo-700',
            'selesai' => 'bg-green-100 text-green-700',
            'dibatalkan' => 'bg-red-100 text-red-700',
        ][$s] ?? 'bg-gray-100 text-gray-600';
    };
@endphp

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 space-y-10">

    {{-- PEMBELIAN --}}
    <section>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Pembelian Saya</h1>
        @if($pembelian->isEmpty())
            <p class="text-gray-400 text-sm">Belum ada pembelian.</p>
        @else
            <div class="space-y-3">
                @foreach($pembelian as $t)
                    @php($foto = $t->barang?->fotoBarangs->first())
                    <div class="bg-white rounded-2xl border border-relove-100 p-4 flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-relove-50 overflow-hidden shrink-0">
                            @if($foto)<img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}" class="w-full h-full object-cover">@else<div class="w-full h-full grid place-items-center text-relove-300 text-2xl">👕</div>@endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ $t->barang?->nama_barang ?? 'Barang dihapus' }}</p>
                            <p class="text-sm text-gray-400">Penjual: @if($t->penjual)<a href="{{ url('/pengguna/' . $t->penjual->id_user) }}" class="text-relove-500 hover:underline font-medium">{{ $t->penjual->nama }}</a>@else - @endif@if($t->metode) &middot; {{ $t->metode === 'cod' ? 'COD' : 'Ekspedisi' }}@endif</p>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusBadge($t->status_transaksi) }}">{{ ucfirst($t->status_transaksi) }}</span>
                        </div>
                        @if($t->status_transaksi === 'selesai')
                            @if($t->review)
                                <span class="text-sm text-relove-500 font-semibold shrink-0">Sudah diulas ⭐</span>
                            @else
                                <a href="{{ url('/transaksi/' . $t->id_transaksi . '/review') }}" class="rounded-lg bg-relove-500 hover:bg-relove-600 text-white text-sm font-semibold px-4 py-2 shrink-0">Beri Ulasan</a>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- PENJUALAN --}}
    @if($penjualan->isNotEmpty())
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Penjualan Saya</h2>
            <div class="space-y-3">
                @foreach($penjualan as $t)
                    @php($foto = $t->barang?->fotoBarangs->first())
                    <div class="bg-white rounded-2xl border border-relove-100 p-4 flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-relove-50 overflow-hidden shrink-0">
                            @if($foto)<img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}" class="w-full h-full object-cover">@else<div class="w-full h-full grid place-items-center text-relove-300 text-2xl">👕</div>@endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ $t->barang?->nama_barang ?? 'Barang dihapus' }}</p>
                            <p class="text-sm text-gray-400">Pembeli: @if($t->pembeli)<a href="{{ url('/pengguna/' . $t->pembeli->id_user) }}" class="text-relove-500 hover:underline font-medium">{{ $t->pembeli->nama }}</a>@else - @endif@if($t->metode) &middot; {{ $t->metode === 'cod' ? 'COD' : 'Ekspedisi' }}@endif</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusBadge($t->status_transaksi) }} shrink-0">{{ ucfirst($t->status_transaksi) }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
