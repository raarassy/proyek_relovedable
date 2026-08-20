@props(['barang'])

@php
    $foto   = $barang->fotoBarangs->first();
    $rating = $barang->toko ? $barang->toko->ratingRata() : null;
    $lokasi = $barang->toko?->alamat_toko;

    // Followers toko (ditampilkan di card)
    $followers = $barang->toko
        ? \App\Models\Follow::where('id_diikuti', $barang->toko->id_user)->count()
        : 0;

    // Apakah user saat ini mengikuti toko ini
    $diikuti = auth()->check() && $barang->toko
        ? \App\Models\Follow::where('id_pengikut', auth()->id())
            ->where('id_diikuti', $barang->toko->id_user)
            ->exists()
        : false;

    $kondisiLabel = [
        'baru'         => 'Baru',
        'seperti_baru' => 'Seperti Baru',
        'bekas'        => 'Bekas Layak',
    ][$barang->kondisi] ?? ucfirst($barang->kondisi);
@endphp

<div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

    {{-- Gambar --}}
    <a href="{{ url('/barang/' . $barang->id_barang) }}" class="block relative">
        <div class="aspect-square bg-gray-50 overflow-hidden">
            @if($foto)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}"
                     alt="{{ $barang->nama_barang }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            @else
                <div class="w-full h-full grid place-items-center text-4xl text-gray-200">👕</div>
            @endif
        </div>

        {{-- Badge kondisi (atas kiri gambar) --}}
        <span class="absolute top-2 left-2 text-[10px] font-semibold text-gray-600 bg-white/90 rounded-full px-2.5 py-0.5 shadow-sm leading-tight">
            {{ $kondisiLabel }}
        </span>
    </a>

    {{-- Tombol follow toko (atas kanan gambar) --}}
    @if($barang->toko)
        @auth
            <form action="{{ url('/follow/' . $barang->toko->id_user . '/toggle') }}" method="POST"
                  class="absolute top-2 right-2">
                @csrf
                <button type="submit"
                        class="grid place-items-center w-8 h-8 rounded-full shadow transition
                               {{ $diikuti ? 'bg-relove-500 text-white' : 'bg-white/90 text-gray-400 hover:text-relove-500' }}"
                        title="{{ $diikuti ? 'Berhenti mengikuti' : 'Ikuti toko' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="{{ $diikuti ? 'currentColor' : 'none' }}"
                         stroke="currentColor" stroke-width="2"
                         class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                </button>
            </form>
        @else
            {{-- Guest: link ke login --}}
            <a href="{{ url('/login') }}"
               class="absolute top-2 right-2 grid place-items-center w-8 h-8 rounded-full bg-white/90 shadow text-gray-400 hover:text-relove-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
            </a>
        @endauth
    @endif

    {{-- Info produk --}}
    <div class="p-3">
        <a href="{{ url('/barang/' . $barang->id_barang) }}" class="block">
            <h3 class="text-sm font-semibold text-gray-800 line-clamp-1 leading-snug">{{ $barang->nama_barang }}</h3>
            <p class="text-relove-600 font-bold text-sm mt-1">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
        </a>

        <div class="flex items-center justify-between mt-1.5">
            {{-- Lokasi --}}
            @if($lokasi)
                <span class="flex items-center gap-0.5 text-[11px] text-gray-400 min-w-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 shrink-0 text-relove-300">
                        <path fill-rule="evenodd" d="M8 1a5 5 0 1 0 0 10A5 5 0 0 0 8 1Zm0 1.5a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7Zm0 5.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" clip-rule="evenodd"/>
                    </svg>
                    <span class="truncate">{{ $lokasi }}</span>
                </span>
            @else
                <span></span>
            @endif

            {{-- Followers toko --}}
            <span class="flex items-center gap-0.5 text-[11px] text-gray-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-relove-400">
                    <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
                <span>{{ $followers }}</span>
            </span>
        </div>

        {{-- Rating --}}
        @if($rating)
            <div class="flex items-center gap-0.5 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 text-amber-400">
                    <path d="M8 1.25a.75.75 0 0 1 .673.418l1.42 2.877 3.174.461a.75.75 0 0 1 .416 1.28l-2.296 2.237.542 3.162a.75.75 0 0 1-1.088.79L8 10.747l-2.84 1.493a.75.75 0 0 1-1.088-.79l.542-3.162L2.317 6.05a.75.75 0 0 1 .416-1.28l3.174-.46 1.42-2.878A.75.75 0 0 1 8 1.25Z"/>
                </svg>
                <span class="text-[11px] font-medium text-gray-500">{{ number_format($rating, 1) }}</span>
            </div>
        @endif
    </div>
</div>
