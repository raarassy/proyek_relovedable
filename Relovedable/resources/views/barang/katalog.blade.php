@extends('layouts.app')
@section('title', 'Katalog')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header + search (mobile) --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Katalog Preloved</h1>
        <p class="text-gray-400 text-sm">{{ $barangs->total() }} barang tersedia</p>
    </div>

    <form action="{{ url('/katalog') }}" method="GET" class="md:hidden mb-4">
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari barang..."
               class="w-full rounded-full border border-relove-200 px-4 py-2 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
    </form>

    {{-- Filter kategori --}}
    @if($kategoriList->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ url('/katalog') }}{{ $q ? '?q=' . urlencode($q) : '' }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium {{ ! $kategori ? 'bg-relove-500 text-white' : 'bg-white border border-relove-200 text-gray-600 hover:bg-relove-50' }}">
                Semua
            </a>
            @foreach($kategoriList as $kat)
                <a href="{{ url('/katalog') }}?kategori={{ urlencode($kat) }}{{ $q ? '&q=' . urlencode($q) : '' }}"
                   class="rounded-full px-4 py-1.5 text-sm font-medium {{ $kategori === $kat ? 'bg-relove-500 text-white' : 'bg-white border border-relove-200 text-gray-600 hover:bg-relove-50' }}">
                    {{ $kat }}
                </a>
            @endforeach
        </div>
    @endif

    @if($barangs->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-relove-200 p-16 text-center text-gray-400">
            Tidak ada barang yang cocok{{ $q ? ' dengan "' . $q . '"' : '' }}.
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($barangs as $barang)
                <x-barang-card :barang="$barang" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $barangs->links() }}
        </div>
    @endif
</div>
@endsection
