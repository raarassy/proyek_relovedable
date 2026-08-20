@extends('layouts.app')
@section('title', 'Panel Admin — Verifikasi')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Verifikasi Penjual</h1>
    <p class="text-sm text-gray-400 mb-6">Tinjau pengajuan & setujui calon penjual.</p>

    @if($pengajuan->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-relove-200 p-16 text-center text-gray-400">
            Belum ada pengajuan.
        </div>
    @else
        <div class="space-y-4">
            @foreach($pengajuan as $p)
                @php
                    $badge = [
                        'pending' => ['bg-yellow-100 text-yellow-700', 'Pending'],
                        'disetujui' => ['bg-green-100 text-green-700', 'Disetujui'],
                        'ditolak' => ['bg-red-100 text-red-700', 'Ditolak'],
                    ][$p->status_verifikasi];
                @endphp
                <div class="bg-white rounded-2xl border border-relove-100 p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-gray-800">🏪 {{ $p->nama_toko }}</h3>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $badge[0] }}">{{ $badge[1] }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $p->user->nama }} &middot; {{ '@' . $p->user->username }} &middot; {{ $p->user->email }}</p>
                            <p class="text-sm text-gray-400">NIK: {{ $p->nik }}</p>
                            @if($p->alamat_toko)<p class="text-sm text-gray-400">📍 {{ $p->alamat_toko }}</p>@endif
                            @if($p->deskripsi_toko)<p class="text-sm text-gray-500 mt-1">{{ $p->deskripsi_toko }}</p>@endif
                        </div>
                        <a href="{{ url('/admin/verifikasi/' . $p->id_verif_pen . '/ktp') }}" target="_blank"
                           class="rounded-lg border border-relove-200 text-relove-600 text-sm px-3 py-1.5 hover:bg-relove-50">Lihat KTP 🔒</a>
                    </div>

                    @if($p->status_verifikasi === 'pending')
                        <div class="flex gap-2 mt-4">
                            <form action="{{ url('/admin/verifikasi/' . $p->id_verif_pen . '/approve') }}" method="POST">
                                @csrf
                                <button class="rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2">Setujui</button>
                            </form>
                            <form action="{{ url('/admin/verifikasi/' . $p->id_verif_pen . '/reject') }}" method="POST" onsubmit="return confirm('Tolak pengajuan ini?')">
                                @csrf
                                <button class="rounded-lg border border-red-200 text-red-500 text-sm font-semibold px-4 py-2 hover:bg-red-50">Tolak</button>
                            </form>
                        </div>
                    @elseif($p->tanggal_verifikasi)
                        <p class="text-xs text-gray-400 mt-3">Diverifikasi: {{ $p->tanggal_verifikasi->format('d M Y H:i') }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
