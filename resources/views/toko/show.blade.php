@extends('layouts.app')
@section('title', $toko->nama_toko)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header toko --}}
    <div class="bg-gradient-to-br from-relove-100 to-white rounded-3xl border border-relove-100 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row gap-6 sm:items-center">
            @if($toko->user && $toko->user->foto_profil)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($toko->user->foto_profil) }}" class="w-20 h-20 rounded-2xl object-cover shrink-0">
            @else
                <span class="grid place-items-center w-20 h-20 rounded-2xl bg-relove-300 text-white text-3xl font-black shrink-0">{{ strtoupper(substr($toko->nama_toko, 0, 1)) }}</span>
            @endif
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

    {{-- Rating & Ulasan --}}
    <section class="mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Rating & Ulasan</h2>
        <div class="grid lg:grid-cols-3 gap-6 items-start">

            {{-- KIRI: ringkasan + form --}}
            <div class="space-y-4 lg:sticky lg:top-20">
                {{-- Skor & distribusi --}}
                <div class="bg-white rounded-2xl border border-relove-100 p-6">
                    <div class="flex items-end gap-3">
                        <p class="text-5xl font-extrabold text-relove-600 leading-none">{{ $jumlahReview ? number_format($ratingRata, 1) : '–' }}</p>
                        <div class="pb-1">
                            <p class="text-relove-500 text-lg leading-none">{{ str_repeat('★', (int) round($ratingRata)) }}<span class="text-gray-200">{{ str_repeat('★', 5 - (int) round($ratingRata)) }}</span></p>
                            <p class="text-xs text-gray-400 mt-1">{{ $jumlahReview }} ulasan</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-1.5">
                        @foreach($distribusi as $bintang => $jml)
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 w-3 text-right">{{ $bintang }}</span>
                                <svg viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-relove-400"><path d="M12 1.5l3.09 6.26 6.91 1-5 4.87 1.18 6.87L12 17.5l-6.18 3.25L7 13.13l-5-4.87 6.91-1z"/></svg>
                                <div class="flex-1 h-2 rounded-full bg-relove-50 overflow-hidden">
                                    <div class="h-full bg-relove-400 rounded-full" style="width: {{ $jumlahReview ? round($jml / $jumlahReview * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-[11px] text-gray-400 w-6 text-right">{{ $jml }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Badge kepuasan --}}
                @if($jumlahReview)
                    <div class="bg-relove-50 border border-relove-100 rounded-2xl p-4">
                        <p class="font-bold text-relove-600 text-sm">{{ $persenPuas }}% Pembeli Puas</p>
                        <p class="text-xs text-gray-500 mt-0.5">Berdasarkan kualitas produk dan pelayanan penjual.</p>
                    </div>
                @endif

                {{-- Form tulis ulasan --}}
                @if($transaksiBelumDiulas)
                    <div class="bg-white rounded-2xl border border-relove-100 p-5">
                        <h3 class="font-bold text-gray-800 mb-1">Tulis Ulasan</h3>
                        <p class="text-xs text-gray-400 mb-3">Untuk pembelian: {{ $transaksiBelumDiulas->barang?->nama_barang ?? 'barang ini' }}</p>
                        <form action="{{ url('/transaksi/' . $transaksiBelumDiulas->id_transaksi . '/review') }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="flex gap-1 text-3xl" data-star-picker>
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer leading-none">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden" {{ $i === 5 ? 'checked' : '' }} required>
                                        <span data-star="{{ $i }}" class="text-gray-300 transition-colors">★</span>
                                    </label>
                                @endfor
                            </div>
                            <textarea name="ulasan" rows="3" placeholder="Ceritakan pengalaman belanjamu di sini…"
                                      class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">{{ old('ulasan') }}</textarea>
                            <button type="submit" class="w-full rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5">Kirim Ulasan</button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- KANAN: daftar ulasan --}}
            <div class="lg:col-span-2">
                @if($reviews->isEmpty())
                    <div class="bg-white rounded-2xl border border-dashed border-relove-200 p-12 text-center text-gray-400">
                        Belum ada ulasan untuk toko ini.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($reviews as $review)
                            @php($p = $review->pembeli)
                            @php($brg = $review->transaksi?->barang)
                            @php($brgFoto = $brg?->fotoBarangs->first())
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

                                        @if($brg)
                                            <a href="{{ url('/barang/' . $brg->id_barang) }}" class="mt-3 inline-flex items-center gap-2 bg-relove-50 rounded-xl p-2 pr-3 border border-relove-100 hover:border-relove-300 transition max-w-full">
                                                <span class="grid place-items-center w-9 h-9 rounded-lg bg-white overflow-hidden shrink-0">
                                                    @if($brgFoto)<img src="{{ \Illuminate\Support\Facades\Storage::url($brgFoto->path_foto) }}" class="w-full h-full object-cover">@else<span class="text-relove-300">👕</span>@endif
                                                </span>
                                                <span class="min-w-0">
                                                    <span class="block text-xs font-semibold text-gray-700 truncate">{{ $brg->nama_barang }}</span>
                                                    <span class="block text-[11px] text-relove-500">Lihat Produk →</span>
                                                </span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<script>
    document.querySelectorAll('[data-star-picker]').forEach(function (pick) {
        const stars = pick.querySelectorAll('[data-star]');
        const paint = function (val) {
            stars.forEach(function (s) {
                const on = Number(s.dataset.star) <= Number(val);
                s.classList.toggle('text-relove-500', on);
                s.classList.toggle('text-gray-300', !on);
            });
        };
        stars.forEach(function (s) {
            s.addEventListener('mouseenter', function () { paint(s.dataset.star); });
            s.addEventListener('click', function () {
                pick.querySelector('input[value="' + s.dataset.star + '"]').checked = true;
                paint(s.dataset.star);
            });
        });
        pick.addEventListener('mouseleave', function () {
            const checked = pick.querySelector('input:checked');
            paint(checked ? checked.value : 0);
        });
        const checked = pick.querySelector('input:checked');
        paint(checked ? checked.value : 5);
    });
</script>
@endsection
