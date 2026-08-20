@extends('layouts.app')
@section('title', $barang->nama_barang)

@php
    $kondisiLabel = ['baru' => 'Baru', 'seperti_baru' => 'Seperti Baru', 'bekas' => 'Bekas'][$barang->kondisi] ?? $barang->kondisi;
    $fotos = $barang->fotoBarangs;
    $penjual = $barang->toko?->user;
    $isOwner = auth()->check() && $penjual && auth()->id() === $penjual->id_user;
    $isFav = auth()->check() ? $barang->difavoritkanOleh(auth()->id()) : false;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <a href="{{ url('/katalog') }}" class="text-sm text-gray-400 hover:text-relove-600">← Kembali ke katalog</a>

    <div class="grid md:grid-cols-2 gap-8 mt-4">
        {{-- GALERI --}}
        <div>
            <div class="aspect-square rounded-2xl bg-relove-50 border border-relove-100 overflow-hidden">
                @if($fotos->first())
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($fotos->first()->path_foto) }}"
                         alt="{{ $barang->nama_barang }}" class="w-full h-full object-cover" id="foto-utama">
                @else
                    <div class="w-full h-full grid place-items-center text-relove-300 text-6xl">👕</div>
                @endif
            </div>
            @if($fotos->count() > 1)
                <div class="grid grid-cols-5 gap-2 mt-3">
                    @foreach($fotos as $foto)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}"
                             class="aspect-square rounded-lg object-cover border border-relove-100 cursor-pointer hover:border-relove-400"
                             onclick="document.getElementById('foto-utama').src = this.src">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- INFO --}}
        <div>
            <span class="inline-block text-xs font-semibold uppercase tracking-wide text-relove-600 bg-relove-100 rounded-full px-3 py-1">{{ $kondisiLabel }}</span>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-3">{{ $barang->nama_barang }}</h1>
            <p class="text-3xl font-bold text-relove-600 mt-2">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>

            <dl class="mt-5 grid grid-cols-2 gap-y-2 text-sm">
                <dt class="text-gray-400">Kategori</dt>
                <dd class="font-medium text-gray-700">{{ $barang->kategori }}</dd>
                <dt class="text-gray-400">Kondisi</dt>
                <dd class="font-medium text-gray-700">{{ $kondisiLabel }}</dd>
                <dt class="text-gray-400">Metode</dt>
                <dd class="font-medium text-gray-700">{{ $barang->metode_transaksi ? 'COD tersedia' : 'Kirim' }}</dd>
                <dt class="text-gray-400">Status</dt>
                <dd class="font-medium {{ $barang->status_barang === 'tersedia' ? 'text-green-600' : 'text-gray-500' }}">{{ ucfirst($barang->status_barang) }}</dd>
            </dl>

            <div class="mt-6">
                <h3 class="font-semibold text-gray-800 mb-1">Deskripsi</h3>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $barang->deskripsi }}</p>
            </div>

            {{-- Toko --}}
            @if($barang->toko)
                <a href="{{ url('/toko/' . $barang->toko->id_toko) }}"
                   class="mt-6 flex items-center gap-3 bg-relove-50 rounded-2xl p-4 border border-relove-100 hover:border-relove-300 transition">
                    <span class="grid place-items-center w-12 h-12 rounded-full bg-relove-200 text-relove-700 font-bold text-lg">{{ strtoupper(substr($barang->toko->nama_toko, 0, 1)) }}</span>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">🏪 {{ $barang->toko->nama_toko }}</p>
                        <p class="text-xs text-gray-400">
                            @if($jumlahReview > 0)
                                ⭐ {{ number_format($ratingRata, 1) }} ({{ $jumlahReview }} ulasan)
                            @else
                                Belum ada ulasan
                            @endif
                        </p>
                    </div>
                    <span class="text-relove-500 text-sm font-semibold">Kunjungi →</span>
                </a>
            @endif

            {{-- Aksi --}}
            <div class="mt-6 flex gap-3">
                @auth
                    @unless($isOwner)
                        <a href="{{ url('/chat/mulai/' . $barang->id_barang) }}"
                           class="flex-1 text-center rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-3">💬 Chat Penjual</a>
                        <form action="{{ url('/favorit/' . $barang->id_barang . '/toggle') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="rounded-xl border border-relove-200 px-5 py-3 text-lg hover:bg-relove-50">{{ $isFav ? '❤️' : '🤍' }}</button>
                        </form>
                    @else
                        <a href="{{ url('/barang/edit/' . $barang->id_barang) }}"
                           class="flex-1 text-center rounded-xl border border-relove-200 text-relove-600 font-semibold py-3 hover:bg-relove-50">Edit Barang Ini</a>
                    @endunless
                @else
                    <a href="{{ url('/login') }}"
                       class="flex-1 text-center rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-3">Masuk untuk Chat / Favorit</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- REVIEW PENJUAL --}}
    <section class="mt-12">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Ulasan Penjual @if($jumlahReview > 0)<span class="text-relove-500">⭐ {{ number_format($ratingRata, 1) }}</span>@endif</h2>
        @if($reviews->isEmpty())
            <p class="text-gray-400 text-sm">Belum ada ulasan untuk penjual ini.</p>
        @else
            <div class="space-y-3">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-xl border border-relove-100 p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-sm text-gray-700">{{ $review->pembeli->nama ?? 'Pengguna' }}</p>
                            <p class="text-relove-500 text-sm">{{ str_repeat('⭐', (int) $review->rating) }}</p>
                        </div>
                        @if($review->ulasan)
                            <p class="text-sm text-gray-500 mt-1">{{ $review->ulasan }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- PRODUK SERUPA --}}
    @if($serupa->isNotEmpty())
        <section class="mt-12">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Produk Serupa</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($serupa as $item)
                    <x-barang-card :barang="$item" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
