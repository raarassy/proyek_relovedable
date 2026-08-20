@extends('layouts.app')
@section('title', 'Beri Ulasan')

@section('content')
<div class="max-w-lg mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Beri Ulasan</h1>

    <div class="bg-white rounded-2xl border border-relove-100 p-6">
        {{-- Barang --}}
        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-relove-50">
            @php($foto = $transaksi->barang?->fotoBarangs->first())
            <div class="w-14 h-14 rounded-xl bg-relove-50 overflow-hidden shrink-0">
                @if($foto)<img src="{{ \Illuminate\Support\Facades\Storage::url($foto->path_foto) }}" class="w-full h-full object-cover">@else<div class="w-full h-full grid place-items-center text-relove-300 text-2xl">👕</div>@endif
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $transaksi->barang?->nama_barang }}</p>
                <p class="text-sm text-gray-400">Penjual: {{ $transaksi->penjual->nama ?? '-' }}</p>
            </div>
        </div>

        <form action="{{ url('/transaksi/' . $transaksi->id_transaksi . '/review') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Rating</label>
                <div class="flex gap-1 text-3xl" id="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" {{ $i === 5 ? 'checked' : '' }} required>
                            <span class="text-gray-300 peer-checked:text-relove-500 hover:text-relove-400" data-star="{{ $i }}">⭐</span>
                        </label>
                    @endfor
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Ulasan <span class="text-gray-300">(opsional)</span></label>
                <textarea name="ulasan" rows="4" placeholder="Bagaimana pengalamanmu?"
                          class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">{{ old('ulasan') }}</textarea>
            </div>
            <div class="flex gap-3">
                <a href="{{ url('/transaksi') }}" class="rounded-xl border border-relove-200 text-gray-600 font-semibold px-5 py-2.5 text-sm hover:bg-relove-50">Batal</a>
                <button type="submit" class="flex-1 rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>
@endsection
