@extends('layouts.app')
@section('title', $toko->nama_toko)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header toko --}}
    <div class="bg-gradient-to-br from-relove-100 to-white rounded-3xl border border-relove-100 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row gap-6 sm:items-center">
            <span class="grid place-items-center w-20 h-20 rounded-2xl bg-relove-300 text-white text-3xl font-black shrink-0">{{ strtoupper(substr($toko->nama_toko, 0, 1)) }}</span>
            <div class="flex-1">
                <h1 class="text-2xl font-extrabold text-gray-900">🏪 {{ $toko->nama_toko }}</h1>
                <p class="text-sm text-gray-500">oleh {{ $toko->user->nama }} &middot; {{ '@' . $toko->user->username }}</p>
                @if($toko->alamat_toko)<p class="text-sm text-gray-400 mt-1">📍 {{ $toko->alamat_toko }}</p>@endif
                @if($toko->deskripsi_toko)<p class="text-sm text-gray-600 mt-2 max-w-xl">{{ $toko->deskripsi_toko }}</p>@endif
            </div>

            <div class="flex flex-col gap-2">
                @if($isOwner)
                    <a href="{{ url('/toko/edit') }}" class="rounded-full border border-relove-300 text-relove-600 font-semibold px-5 py-2 text-sm text-center hover:bg-relove-50">Edit Toko</a>
                    <a href="{{ url('/barang') }}" class="rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold px-5 py-2 text-sm text-center">Kelola Barang</a>
                @elseif(auth()->check())
                    <form action="{{ url('/follow/' . $toko->user->id_user . '/toggle') }}" method="POST">
                        @csrf
                        <button class="rounded-full font-semibold px-6 py-2 text-sm w-full {{ $sedangDiikuti ? 'bg-white border border-relove-300 text-relove-600' : 'bg-relove-500 text-white hover:bg-relove-600' }}">
                            {{ $sedangDiikuti ? 'Mengikuti ✓' : '+ Ikuti' }}
                        </button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold px-6 py-2 text-sm text-center">+ Ikuti</a>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-3 mt-6">
            <div class="bg-white/70 rounded-xl p-3 text-center">
                <p class="text-xl font-extrabold text-relove-600">{{ $jumlahProduk }}</p>
                <p class="text-xs text-gray-400">Produk</p>
            </div>
            <div class="bg-white/70 rounded-xl p-3 text-center">
                <p class="text-xl font-extrabold text-relove-600">{{ $jumlahPengikut }}</p>
                <p class="text-xs text-gray-400">Pengikut</p>
            </div>
            <div class="bg-white/70 rounded-xl p-3 text-center">
                <p class="text-xl font-extrabold text-relove-600">{{ $jumlahReview ? number_format($ratingRata, 1) : '–' }}</p>
                <p class="text-xs text-gray-400">{{ $jumlahReview }} Ulasan</p>
            </div>
        </div>
    </div>

    {{-- Produk --}}
    <section class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Produk Toko</h2>
        @if($toko->barangs->isEmpty())
            <p class="text-gray-400 text-sm">Toko ini belum punya barang.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($toko->barangs as $barang)
                    <x-barang-card :barang="$barang" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- Ulasan --}}
    <section class="mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Ulasan Pembeli</h2>
        @if($reviews->isEmpty())
            <p class="text-gray-400 text-sm">Belum ada ulasan.</p>
        @else
            <div class="space-y-3">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-xl border border-relove-100 p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-sm text-gray-700">{{ $review->pembeli->nama ?? 'Pengguna' }}</p>
                            <p class="text-relove-500 text-sm">{{ str_repeat('⭐', (int) $review->rating) }}</p>
                        </div>
                        @if($review->ulasan)<p class="text-sm text-gray-500 mt-1">{{ $review->ulasan }}</p>@endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
