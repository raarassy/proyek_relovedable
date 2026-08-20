@extends('layouts.app')
@section('title', 'Beranda')

@section('content')

{{-- ===== HERO ===== --}}
<section class="bg-white px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-relove-500 via-relove-600 to-relove-700 min-h-[220px] flex items-center px-8 py-10">

            {{-- Dekorasi kiri --}}
            <div class="absolute left-6 top-1/2 -translate-y-1/2 opacity-20 text-white text-8xl select-none pointer-events-none hidden md:block">
                🧥
            </div>

            {{-- Konten tengah --}}
            <div class="relative z-10 flex-1 text-center">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white leading-tight">
                    Temukan Preloved Favoritmu 🩷
                </h1>
                <p class="mt-2 text-relove-100 text-sm sm:text-base max-w-lg mx-auto">
                    Ribuan barang berkualitas dari sesama mahasiswa yang siap menemani hari-harimu.
                </p>

                {{-- Search bar --}}
                <form action="{{ url('/katalog') }}" method="GET" class="mt-5 max-w-xl mx-auto">
                    <div class="flex bg-white rounded-full shadow-lg overflow-hidden pl-5 pr-1.5 py-1.5 gap-2">
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Cari jaket vintage, buku kuliah, atau sepatu..."
                               class="flex-1 text-sm text-gray-700 placeholder-gray-400 outline-none bg-transparent min-w-0">
                        <button type="submit"
                                class="shrink-0 bg-relove-500 hover:bg-relove-600 text-white text-sm font-semibold px-5 py-2 rounded-full transition">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- Dekorasi kanan --}}
            <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-15 text-white text-8xl select-none pointer-events-none hidden md:block">
                🩷
            </div>
        </div>
    </div>
</section>

{{-- ===== CATEGORY PILLS ===== --}}
<div class="max-w-7xl mx-auto px-4 mt-4">
    <div class="flex flex-wrap gap-2">
        <a href="{{ url('/katalog') }}"
           class="rounded-full px-4 py-1.5 text-sm font-medium transition bg-relove-500 text-white">
            Semua
        </a>
        @foreach(\App\Models\Barang::KATEGORI as $kat)
            <a href="{{ url('/katalog?kategori=' . urlencode($kat)) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition bg-white border border-gray-200 text-gray-600 hover:border-relove-300 hover:text-relove-600">
                {{ $kat }}
            </a>
        @endforeach
    </div>
</div>

{{-- ===== TICKER ===== --}}
<div class="max-w-7xl mx-auto px-4 mt-4 overflow-hidden">
    <p class="text-center text-xs text-relove-400 font-medium py-2 tracking-wide">
        ✦ Baju ✦ Tas ✦ Sepatu ✦ Aksesoris ✦ Preloved Berkualitas ✦ Baju ✦ Tas ✦ Sepatu ✦ Aksesoris ✦ Preloved Berkualitas ✦
    </p>
</div>

{{-- ===== REKOMENDASI ===== --}}
<section class="max-w-7xl mx-auto px-4 pb-10 mt-2">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Rekomendasi Untukmu</h2>
            <p class="text-xs text-gray-400 mt-0.5">Berdasarkan barang terbaru di Relovedable.</p>
        </div>
        <a href="{{ url('/katalog') }}" class="text-sm font-semibold text-relove-500 hover:text-relove-600 mt-0.5">Eksplorasi Lagi →</a>
    </div>

    @if($rekomendasi->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-relove-200 p-12 text-center text-gray-400">
            Belum ada barang. <a href="{{ url('/penjual/daftar') }}" class="text-relove-600 font-semibold">Jadi penjual pertama!</a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($rekomendasi as $barang)
                <x-barang-card :barang="$barang" />
            @endforeach
        </div>
    @endif
</section>

@endsection
