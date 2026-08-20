@extends('layouts.app')
@section('title', 'Favorit')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Favorit Saya ❤️</h1>
    <p class="text-sm text-gray-400 mb-6">{{ $favorits->count() }} barang disimpan</p>

    @if($favorits->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-relove-200 p-16 text-center text-gray-400">
            Belum ada favorit. Tekan 🤍 pada barang yang kamu suka.
            <div class="mt-4"><a href="{{ url('/katalog') }}" class="text-relove-600 font-semibold hover:underline">Jelajahi katalog →</a></div>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($favorits as $barang)
                <x-barang-card :barang="$barang" />
            @endforeach
        </div>
    @endif
</div>
@endsection
