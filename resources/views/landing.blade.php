@extends('layouts.app')
@section('title', 'Selamat Datang')

@section('content')

{{-- ===== HERO ===== --}}
<section class="bg-gradient-to-b from-relove-50 to-white">
    <div class="max-w-7xl mx-auto px-4 py-12 lg:py-20 grid lg:grid-cols-2 gap-10 lg:gap-8 items-center">
        {{-- Kiri --}}
        <div>
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-relove-600 bg-relove-100 rounded-full px-4 py-1.5">
                🩷 Jual-beli Preloved Mahasiswa
            </span>
            <h1 class="mt-5 text-4xl sm:text-5xl font-extrabold text-gray-900 leading-[1.1]">
                Hemat, Stylish, dan <span class="text-relove-500">Ramah</span> Lingkungan
            </h1>
            <p class="mt-4 text-gray-500 text-base max-w-lg">
                Platform jual-beli pakaian preloved untuk mahasiswa. Hemat, ramah lingkungan, dan tetap bergaya — cari & temukan harta karun unikmu di Relovedable.
            </p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ url('/katalog') }}" class="rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold px-7 py-3 shadow-lg transition">Lihat Katalog</a>
            </div>

            <div class="mt-8 flex items-center gap-6 text-sm">
                <div><span class="font-extrabold text-gray-900 text-lg">0%</span> <span class="text-gray-400">biaya admin</span></div>
                <div class="w-px h-8 bg-relove-100"></div>
                <div><span class="font-extrabold text-gray-900 text-lg">100%</span> <span class="text-gray-400">preloved berkualitas</span></div>
            </div>
        </div>

        {{-- Kanan: visual (ganti dengan foto produk jika ada) --}}
        <div class="relative">
            <div class="aspect-[4/5] max-w-sm mx-auto rounded-3xl bg-gradient-to-br from-relove-200 via-relove-100 to-white border border-relove-100 shadow-xl grid grid-cols-2 gap-3 p-5">
                <div class="rounded-2xl bg-white/70 grid place-items-center text-5xl">🧥</div>
                <div class="rounded-2xl bg-white/70 grid place-items-center text-5xl">👗</div>
                <div class="rounded-2xl bg-white/70 grid place-items-center text-5xl">👜</div>
                <div class="rounded-2xl bg-white/70 grid place-items-center text-5xl">👟</div>
            </div>
            <span class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-full px-5 py-2 text-sm font-semibold text-relove-600 border border-relove-100 whitespace-nowrap">✦ Lovable Selection ✦</span>
        </div>
    </div>
</section>

{{-- ===== CARA KERJA ===== --}}
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-14">
        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Cara Kerja Relovedable</h2>
            <p class="mt-2 text-gray-400 text-sm">Tiga langkah mudah mulai berbelanja & berjualan di Relovedable.</p>
        </div>

        <div class="grid sm:grid-cols-3 gap-5">
            @foreach([
                ['📷', '1. Foto & Upload', 'Ambil foto barang preloved-mu (maks 8), isi detail & harga, lalu upload. Barang langsung tampil di katalog.'],
                ['💬', '2. Chat & Nego', 'Pembeli menghubungi penjual lewat fitur chat. Sepakati harga & metode transaksi yang cocok.'],
                ['🚚', '3. COD / Kirim', 'Transaksi lewat COD ketemuan langsung atau kirim via ekspedisi. Selesai, jangan lupa beri ulasan!'],
            ] as [$ikon, $judul, $teks])
                <div class="rounded-3xl border border-relove-100 bg-relove-50/40 p-7 text-center hover:border-relove-300 transition">
                    <div class="mx-auto grid place-items-center w-14 h-14 rounded-2xl bg-relove-100 text-relove-600 text-2xl mb-4">{{ $ikon }}</div>
                    <h3 class="font-bold text-gray-800">{{ $judul }}</h3>
                    <p class="mt-1.5 text-sm text-gray-400">{{ $teks }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== KENAPA JUALAN ===== --}}
<section class="bg-relove-50">
    <div class="max-w-7xl mx-auto px-4 py-14">
        <h2 class="text-center text-2xl sm:text-3xl font-extrabold text-gray-900 mb-10">Kenapa Jualan di Relovedable?</h2>

        <div class="grid md:grid-cols-2 gap-5">
            {{-- Kolom kiri --}}
            <div class="grid gap-5">
                <div class="rounded-3xl bg-gradient-to-br from-relove-500 to-relove-700 text-white p-8">
                    <p class="text-5xl font-black leading-none">0%</p>
                    <h3 class="text-xl font-bold mt-2">Biaya Admin</h3>
                    <p class="mt-2 text-relove-100 text-sm">100% keuntungan masuk ke kantongmu. Nggak ada potongan untuk tiap transaksi yang berhasil.</p>
                </div>

                <div class="rounded-3xl bg-gradient-to-br from-emerald-600 to-emerald-800 text-white p-8">
                    <div class="grid place-items-center w-12 h-12 rounded-full bg-white/15 text-2xl mb-3">⚡</div>
                    <h3 class="text-xl font-bold">Transaksi Cepat & Mudah</h3>
                    <p class="mt-2 text-emerald-100 text-sm">Dukung COD ketemuan maupun kirim via ekspedisi. Tandai terjual & langsung dapat ulasan.</p>
                </div>
            </div>

            {{-- Kolom kanan --}}
            <div class="rounded-3xl bg-relove-100 border border-relove-100 p-8 flex flex-col">
                <h3 class="text-xl font-bold text-gray-900">Komunitas Mahasiswa Terpercaya</h3>
                <p class="mt-2 text-gray-500 text-sm max-w-md">Penjual diverifikasi lewat KTP oleh admin, dan kamu bisa follow toko favoritmu. Jual-beli aman antar sesama mahasiswa.</p>
                <div class="mt-6 flex-1 rounded-2xl bg-gradient-to-br from-relove-200 via-relove-100 to-white grid place-items-center text-6xl min-h-[200px]">🧑‍🤝‍🧑</div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 pb-16 pt-4">
        <div class="rounded-3xl bg-gray-900 text-center px-6 py-14 sm:py-16 relative overflow-hidden">
            <div class="absolute -top-8 -left-8 text-8xl opacity-10 select-none pointer-events-none">🩷</div>
            <div class="absolute -bottom-8 -right-8 text-8xl opacity-10 select-none pointer-events-none">🧥</div>
            <h2 class="relative text-2xl sm:text-3xl font-extrabold text-white">Dapatkan barang keperluanmu tanpa mahal</h2>
            <p class="relative mt-3 text-gray-400 text-sm max-w-xl mx-auto">Gabung bareng mahasiswa lain yang udah hemat & lebih sustainable lewat Relovedable.</p>
            @guest
                <a href="{{ url('/register') }}" class="relative inline-block mt-6 rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold px-8 py-3.5 shadow-lg transition">Daftar Sekarang Secara Gratis</a>
            @else
                <a href="{{ url('/katalog') }}" class="relative inline-block mt-6 rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold px-8 py-3.5 shadow-lg transition">Mulai Belanja Sekarang</a>
            @endguest
        </div>
    </div>
</section>

@endsection
