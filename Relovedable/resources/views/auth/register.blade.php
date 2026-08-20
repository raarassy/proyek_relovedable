@extends('layouts.app')
@section('title', 'Daftar')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl shadow-xl border border-relove-100 p-8">
        <div class="text-center mb-6">
            <span class="inline-grid place-items-center w-14 h-14 rounded-2xl bg-relove-500 text-white text-2xl font-black mb-3">R</span>
            <h1 class="text-2xl font-extrabold text-gray-800">Buat Akun</h1>
            <p class="text-sm text-gray-400 mt-1">Gabung dan mulai belanja preloved</p>
        </div>

        <form action="{{ url('/register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required autofocus
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">No. HP <span class="text-gray-300">(opsional)</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <button type="submit"
                    class="w-full rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5 transition">
                Daftar
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun?
            <a href="{{ url('/login') }}" class="text-relove-600 font-semibold hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection
