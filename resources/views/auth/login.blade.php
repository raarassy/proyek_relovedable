@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl shadow-xl border border-relove-100 p-8">
        <div class="text-center mb-6">
            <span class="inline-grid place-items-center w-14 h-14 rounded-2xl bg-relove-500 text-white text-2xl font-black mb-3">R</span>
            <h1 class="text-2xl font-extrabold text-gray-800">Masuk ke Relovedable</h1>
            <p class="text-sm text-gray-400 mt-1">Hemat, Stylish, dan Ramah Lingkungan</p>
        </div>

        <form action="{{ url('/login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-500">
                <input type="checkbox" name="remember" class="rounded border-relove-300 text-relove-500 focus:ring-relove-300">
                Ingat saya
            </label>
            <button type="submit"
                    class="w-full rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5 transition">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Belum punya akun?
            <a href="{{ url('/register') }}" class="text-relove-600 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
