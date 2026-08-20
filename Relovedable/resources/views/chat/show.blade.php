@extends('layouts.app')
@section('title', 'Chat — ' . $lawan->nama)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <a href="{{ url('/chat') }}" class="text-sm text-gray-400 hover:text-relove-600">← Semua pesan</a>

    <div class="bg-white rounded-2xl border border-relove-100 mt-3 overflow-hidden flex flex-col" style="height: 70vh;">
        {{-- Header thread --}}
        <div class="flex items-center gap-3 p-4 border-b border-relove-50 bg-relove-50/50">
            @php($foto = $barang->fotoBarangs->first())
            <div class="w-11 h-11 rounded-xl bg-relove-50 overflow-hidden shrink-0">
                @if($foto)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full grid place-items-center text-relove-300">👕</div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800">{{ $lawan->nama }}</p>
                <a href="{{ url('/barang/' . $barang->id_barang) }}" class="text-xs text-relove-500 hover:underline truncate block">{{ $barang->nama_barang }} — Rp {{ number_format($barang->harga, 0, ',', '.') }}</a>
            </div>

        </div>

        {{-- Pesan --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-relove-50/30" id="kotak-pesan">
            @forelse($pesan as $p)
                @php($mine = $p->id_pengirim === auth()->id())
                <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] rounded-2xl px-4 py-2 text-sm {{ $mine ? 'bg-relove-500 text-white rounded-br-sm' : 'bg-white border border-relove-100 text-gray-700 rounded-bl-sm' }}">
                        <p class="whitespace-pre-line">{{ $p->pesan }}</p>
                        <p class="text-[10px] mt-1 {{ $mine ? 'text-relove-100' : 'text-gray-300' }}">{{ $p->waktu_kirim ? \Illuminate\Support\Carbon::parse($p->waktu_kirim)->format('H:i') : '' }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 text-sm mt-8">Belum ada pesan. Sapa penjual! 👋</p>
            @endforelse
        </div>

        {{-- Input --}}
        <form action="{{ url('/chat/' . $barang->id_barang . '/' . $lawan->id_user) }}" method="POST"
              class="p-3 border-t border-relove-50 flex gap-2">
            @csrf
            <input type="text" name="pesan" required autocomplete="off" placeholder="Tulis pesan..."
                   class="flex-1 rounded-full border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            <button type="submit" class="rounded-full bg-relove-500 hover:bg-relove-600 text-white font-semibold px-5">Kirim</button>
        </form>
    </div>
</div>

<script>
    // Auto-scroll ke pesan terbaru
    const kotak = document.getElementById('kotak-pesan');
    if (kotak) kotak.scrollTop = kotak.scrollHeight;
</script>
@endsection
