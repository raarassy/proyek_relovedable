@extends('layouts.app')
@section('title', 'Chat')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pesan 💬</h1>

    @if($percakapan->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-relove-200 p-16 text-center text-gray-400">
            Belum ada percakapan. Mulai chat dari halaman barang.
        </div>
    @else
        <div class="bg-white rounded-2xl border border-relove-100 divide-y divide-relove-50">
            @foreach($percakapan as $p)
                @php($foto = $p->barang?->fotoBarangs->first())
                <a href="{{ url('/chat/' . $p->barang->id_barang . '/' . $p->lawan->id_user) }}"
                   class="flex items-center gap-3 p-4 hover:bg-relove-50">
                    <div class="w-12 h-12 rounded-xl bg-relove-50 overflow-hidden shrink-0">
                        @if($foto)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full grid place-items-center text-relove-300 text-xl">👕</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-gray-800 truncate">{{ $p->lawan->nama }}</p>
                            <span class="text-xs text-gray-300 shrink-0">{{ $p->pesan_terakhir->waktu_kirim ? \Illuminate\Support\Carbon::parse($p->pesan_terakhir->waktu_kirim)->format('d/m H:i') : '' }}</span>
                        </div>
                        <p class="text-xs text-relove-500 truncate">{{ $p->barang?->nama_barang }}</p>
                        <p class="text-sm text-gray-400 truncate">{{ $p->pesan_terakhir->id_pengirim === auth()->id() ? 'Kamu: ' : '' }}{{ $p->pesan_terakhir->pesan }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
