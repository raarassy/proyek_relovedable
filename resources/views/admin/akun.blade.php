@extends('layouts.app')
@section('title', 'Panel Admin — Verifikasi Akun')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Verifikasi Akun</h1>
    <p class="text-sm text-gray-400 mb-4">Setujui atau tolak pendaftaran akun baru sebelum mereka bisa masuk.</p>

    <div class="flex gap-2 mb-6">
        <a href="{{ url('/admin/verifikasi') }}" class="rounded-full px-4 py-1.5 text-sm font-medium {{ request()->is('admin/verifikasi') ? 'bg-relove-500 text-white' : 'bg-white border border-relove-200 text-gray-600 hover:bg-relove-50' }}">Verifikasi Penjual</a>
        <a href="{{ url('/admin/akun') }}" class="rounded-full px-4 py-1.5 text-sm font-medium {{ request()->is('admin/akun') ? 'bg-relove-500 text-white' : 'bg-white border border-relove-200 text-gray-600 hover:bg-relove-50' }}">Verifikasi Akun</a>
    </div>

    @if($akun->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-relove-200 p-16 text-center text-gray-400">
            Belum ada akun pengguna.
        </div>
    @else
        <div class="space-y-3">
            @foreach($akun as $u)
                @php
                    $badge = [
                        'pending' => ['bg-yellow-100 text-yellow-700', 'Pending'],
                        'aktif' => ['bg-green-100 text-green-700', 'Aktif'],
                        'ditolak' => ['bg-red-100 text-red-700', 'Ditolak'],
                    ][$u->status_akun] ?? ['bg-gray-100 text-gray-600', $u->status_akun];
                @endphp
                <div class="bg-white rounded-2xl border border-relove-100 p-5 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        @if($u->foto_profil)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($u->foto_profil) }}" class="w-11 h-11 rounded-full object-cover">
                        @else
                            <span class="grid place-items-center w-11 h-11 rounded-full bg-relove-100 text-relove-600 font-bold">{{ strtoupper(substr($u->nama, 0, 1)) }}</span>
                        @endif
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold text-gray-800">{{ $u->nama }}</p>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $badge[0] }}">{{ $badge[1] }}</span>
                                <span class="text-xs text-gray-400 capitalize">{{ $u->role }}</span>
                            </div>
                            <p class="text-sm text-gray-400">{{ '@' . $u->username }} &middot; {{ $u->email }}@if($u->no_hp) &middot; {{ $u->no_hp }}@endif</p>
                        </div>
                    </div>

                    @if($u->status_akun === 'pending')
                        <div class="flex gap-2">
                            <form action="{{ url('/admin/akun/' . $u->id_user . '/approve') }}" method="POST">
                                @csrf
                                <button class="rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2">Setujui</button>
                            </form>
                            <form action="{{ url('/admin/akun/' . $u->id_user . '/reject') }}" method="POST" onsubmit="return confirm('Tolak akun ini?')">
                                @csrf
                                <button class="rounded-lg border border-red-200 text-red-500 text-sm font-semibold px-4 py-2 hover:bg-red-50">Tolak</button>
                            </form>
                        </div>
                    @elseif($u->status_akun === 'ditolak')
                        <form action="{{ url('/admin/akun/' . $u->id_user . '/approve') }}" method="POST">
                            @csrf
                            <button class="rounded-lg border border-relove-200 text-relove-600 text-sm font-semibold px-4 py-2 hover:bg-relove-50">Aktifkan</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
