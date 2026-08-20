@extends('layouts.app')
@section('title', $barang->nama_barang)

@php
    $kondisiLabel = ['baru' => 'Baru', 'seperti_baru' => 'Seperti Baru', 'bekas' => 'Bekas'][$barang->kondisi] ?? $barang->kondisi;
    $fotos = $barang->fotoBarangs;
    $penjual = $barang->toko?->user;
    $isOwner = auth()->check() && $penjual && auth()->id() === $penjual->id_user;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <a href="{{ url('/katalog') }}" class="text-sm text-gray-400 hover:text-relove-600">← Kembali ke katalog</a>

    <div class="grid md:grid-cols-2 gap-8 mt-4">
        {{-- GALERI --}}
        <div>
            @if($fotos->isEmpty())
                <div class="aspect-square rounded-2xl bg-relove-50 border border-relove-100 grid place-items-center text-relove-300 text-6xl">👕</div>
            @else
                <div id="galeri" data-total="{{ $fotos->count() }}" class="relative aspect-square rounded-2xl bg-relove-50 border border-relove-100 overflow-hidden group">
                    {{-- Track --}}
                    <div id="galeri-track" class="flex h-full transition-transform duration-300 ease-out">
                        @foreach($fotos as $foto)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}"
                                 alt="{{ $barang->nama_barang }}" class="w-full h-full object-cover shrink-0">
                        @endforeach
                    </div>

                    @if($fotos->count() > 1)
                        {{-- Panah --}}
                        <button type="button" onclick="geserGaleri(-1)"
                                class="absolute left-3 top-1/2 -translate-y-1/2 grid place-items-center w-10 h-10 rounded-full bg-white/80 hover:bg-white text-gray-700 shadow opacity-0 group-hover:opacity-100 transition">‹</button>
                        <button type="button" onclick="geserGaleri(1)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 grid place-items-center w-10 h-10 rounded-full bg-white/80 hover:bg-white text-gray-700 shadow opacity-0 group-hover:opacity-100 transition">›</button>

                        {{-- Dots --}}
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                            @foreach($fotos as $i => $foto)
                                <span data-dot="{{ $i }}" class="w-2 h-2 rounded-full bg-white/60 transition-all {{ $i === 0 ? 'bg-white w-4' : '' }}"></span>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($fotos->count() > 1)
                    <div class="flex gap-2 mt-3 overflow-x-auto pb-1 snap-x [scrollbar-width:thin]">
                        @foreach($fotos as $i => $foto)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}"
                                 data-thumb="{{ $i }}"
                                 class="w-20 h-20 shrink-0 snap-start rounded-lg object-cover border-2 border-relove-100 cursor-pointer hover:border-relove-400 transition {{ $i === 0 ? 'border-relove-400' : '' }}"
                                 onclick="pindahGaleri({{ $i }})">
                        @endforeach
                    </div>

                    <script>
                        (function () {
                            const g = document.getElementById('galeri');
                            const total = +g.dataset.total;
                            let idx = 0;
                            const track = document.getElementById('galeri-track');

                            window.pindahGaleri = function (i) {
                                idx = (i + total) % total;
                                track.style.transform = `translateX(-${idx * 100}%)`;
                                document.querySelectorAll('[data-dot]').forEach(d =>
                                    d.classList.toggle('w-4', +d.dataset.dot === idx) ||
                                    d.classList.toggle('bg-white', +d.dataset.dot === idx));
                                document.querySelectorAll('[data-thumb]').forEach(t => {
                                    const aktif = +t.dataset.thumb === idx;
                                    t.classList.toggle('border-relove-400', aktif);
                                    if (aktif) t.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                                });
                            };
                            window.geserGaleri = (step) => pindahGaleri(idx + step);

                            // Swipe di layar sentuh
                            let x0 = null;
                            g.addEventListener('touchstart', e => x0 = e.touches[0].clientX, { passive: true });
                            g.addEventListener('touchend', e => {
                                if (x0 === null) return;
                                const dx = e.changedTouches[0].clientX - x0;
                                if (Math.abs(dx) > 40) geserGaleri(dx < 0 ? 1 : -1);
                                x0 = null;
                            });
                        })();
                    </script>
                @endif
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
                <dd class="font-medium text-gray-700">{{ collect(['COD' => $barang->bisa_cod, 'Ekspedisi' => $barang->bisa_ekspedisi])->filter()->keys()->implode(' & ') ?: '-' }}</dd>
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
                    @if($penjual && $penjual->foto_profil)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($penjual->foto_profil) }}" class="w-12 h-12 rounded-full object-cover shrink-0">
                    @else
                        <span class="grid place-items-center w-12 h-12 rounded-full bg-relove-200 text-relove-700 font-bold text-lg shrink-0">{{ strtoupper(substr($barang->toko->nama_toko, 0, 1)) }}</span>
                    @endif
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
            <div class="mt-6 space-y-3">
                @auth
                    @unless($isOwner)
                        @if($transaksiBelumDiulas)
                            <a href="{{ url('/transaksi/' . $transaksiBelumDiulas->id_transaksi . '/review') }}"
                               class="block text-center rounded-xl bg-amber-400 hover:bg-amber-500 text-white font-semibold py-3">⭐ Beri Ulasan untuk Pembelian Ini</a>
                        @endif
                        <div class="flex gap-3">
                        <a href="{{ url('/chat/mulai/' . $barang->id_barang) }}"
                           class="flex-1 text-center rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-3">💬 Chat Penjual</a>
                        @if($barang->toko)
                            <form action="{{ url('/follow/' . $barang->toko->id_user . '/toggle') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="rounded-xl border px-5 py-3 text-sm font-semibold {{ $sedangDiikuti ? 'border-relove-300 text-relove-600 bg-relove-50' : 'border-relove-200 text-gray-600 hover:bg-relove-50' }}">{{ $sedangDiikuti ? 'Mengikuti ✓' : '+ Ikuti Toko' }}</button>
                            </form>
                        @endif
                        </div>
                    @else
                        <a href="{{ url('/barang/edit/' . $barang->id_barang) }}"
                           class="block text-center rounded-xl border border-relove-200 text-relove-600 font-semibold py-3 hover:bg-relove-50">Edit Barang Ini</a>
                    @endunless
                @else
                    <a href="{{ url('/login') }}"
                       class="block text-center rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-3">Masuk untuk Chat / Favorit</a>
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
            <div class="grid sm:grid-cols-2 gap-3">
                @foreach($reviews as $review)
                    @php($p = $review->pembeli)
                    <div class="bg-white rounded-2xl border border-relove-100 p-5">
                        <div class="flex items-start gap-3">
                            @if($p && $p->foto_profil)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($p->foto_profil) }}" class="w-10 h-10 rounded-full object-cover shrink-0">
                            @else
                                <span class="grid place-items-center w-10 h-10 rounded-full bg-relove-100 text-relove-600 font-bold shrink-0">{{ strtoupper(substr($p->nama ?? 'U', 0, 1)) }}</span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-semibold text-sm text-gray-800 truncate">{{ $p->nama ?? 'Pengguna' }}</p>
                                    <span class="text-xs text-gray-300 shrink-0">{{ $review->tanggal_review ? \Illuminate\Support\Carbon::parse($review->tanggal_review)->diffForHumans() : '' }}</span>
                                </div>
                                <p class="text-relove-500 text-sm leading-none mt-1">{{ str_repeat('★', (int) $review->rating) }}<span class="text-gray-200">{{ str_repeat('★', 5 - (int) $review->rating) }}</span></p>
                                @if($review->ulasan)<p class="text-sm text-gray-600 mt-2 whitespace-pre-line">{{ $review->ulasan }}</p>@endif
                            </div>
                        </div>
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
