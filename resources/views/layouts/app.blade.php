<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Relovedable') — Hemat, Stylish, Ramah Lingkungan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-800 antialiased flex flex-col">

    <nav class="sticky top-0 z-40 bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-14 flex items-center gap-6">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0 transition hover:opacity-90">
            <img src="{{ asset('images/logo-relovedable.png') }}" alt="Relovedable" class="w-10 h-10 sm:w-[42px] sm:h-[42px] object-contain">
            <span class="text-base font-extrabold text-relove-600 tracking-tight">Relovedable</span>        
        </a>

            {{-- Nav links --}}
            <div class="flex items-center gap-1 hidden sm:flex">
                <a href="{{ url('/') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->is('/') || request()->is('') ? 'text-relove-600 bg-relove-50' : 'text-gray-500 hover:text-relove-600 hover:bg-relove-50' }}">Home</a>
                <a href="{{ url('/katalog') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->is('katalog*') ? 'text-relove-600 bg-relove-50' : 'text-gray-500 hover:text-relove-600 hover:bg-relove-50' }}">Jelajahi</a>
                @auth
                @unless(auth()->user()->isAdmin())
                <a href="{{ url('/chat') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request()->is('chat*') ? 'text-relove-600 bg-relove-50' : 'text-gray-500 hover:text-relove-600 hover:bg-relove-50' }}">Chat</a>
                @endunless
                @endauth
            </div>

            <div class="flex items-center gap-2 ml-auto">
                @guest
                    <a href="{{ url('/login') }}" class="text-sm font-semibold text-gray-600 hover:text-relove-600 px-3 py-1.5">Masuk</a>
                    <a href="{{ url('/register') }}" class="rounded-full bg-relove-500 hover:bg-relove-600 text-white text-sm font-semibold px-4 py-1.5">Daftar</a>
                @else
                    @unless(auth()->user()->isAdmin())
                    <a href="{{ url('/favorit') }}" title="Favorit"
                       class="grid place-items-center w-9 h-9 rounded-full transition {{ request()->is('favorit*') ? 'text-relove-500 bg-relove-50' : 'text-gray-500 hover:text-relove-500 hover:bg-relove-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                        </svg>
                    </a>
                    @endunless

                    <details class="relative">
                        <summary class="list-none cursor-pointer">
                            @php($u = auth()->user())
                            @if($u->foto_profil)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($u->foto_profil) }}"
                                     class="w-9 h-9 rounded-full object-cover border-2 border-relove-200">
                            @else
                                <span class="grid place-items-center w-9 h-9 rounded-full bg-relove-100 text-relove-600 font-bold text-sm">{{ strtoupper(substr($u->nama, 0, 1)) }}</span>
                            @endif
                        </summary>
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 text-sm z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="font-semibold truncate text-gray-800">{{ $u->nama }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ '@' . $u->username }}</p>
                            </div>
                            <a href="{{ url('/profil') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-relove-50 hover:text-relove-600">Profil Saya</a>
                            @unless($u->isAdmin())
                            <a href="{{ url('/favorit') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-relove-50 hover:text-relove-600">Favorit Saya</a>
                            <a href="{{ url('/chat') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-relove-50 hover:text-relove-600">Pesan</a>
                            @endunless
                            @if($u->isPenjual() && $u->toko)
                                <a href="{{ url('/toko/' . $u->toko->id_toko) }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-relove-50 hover:text-relove-600">Toko Saya</a>
                                <a href="{{ url('/barang') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-relove-50 hover:text-relove-600">Kelola Barang</a>
                            @elseif(!$u->isAdmin())
                                <a href="{{ url('/penjual/daftar') }}" class="flex items-center gap-2 px-4 py-2 text-relove-600 font-medium hover:bg-relove-50">Jadi Penjual</a>
                            @endif
                            @if($u->isAdmin())
                                <a href="{{ url('/admin/verifikasi') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-relove-50">Verifikasi Penjual</a>
                                <a href="{{ url('/admin/akun') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:bg-relove-50">Verifikasi Akun</a>
                            @endif
                            <form action="{{ url('/logout') }}" method="POST" class="border-t border-gray-100 mt-1 pt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50">Keluar</button>
                            </form>
                        </div>
                    </details>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success') || session('error') || $errors->any())
        <div class="max-w-7xl mx-auto w-full px-4 mt-4 space-y-2">
            @if(session('success'))
                <div class="rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <footer class="mt-12 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
            <div class="flex items-center gap-2 mb-1">
                {{-- Ukuran gambar/ikon dinaikkan dari w-7 h-7 menjadi w-10 h-10 (40px) --}}
                <img src="{{ asset('images/logo-relovedable.png') }}" alt="Relovedable" class="w-10 h-10 object-contain">
                
                {{-- Ukuran teks tetap bawaan asli, hanya disesuaikan sedikit tracking-nya agar serasi dengan navbar --}}
                <span class="font-extrabold text-relove-600 tracking-tight">Relovedable</span>
            </div>
                <p class="text-xs text-gray-400 max-w-xs">Pilihan terbaik untuk fashion berkelanjutan. Temukan harta karun unik dan berikan kehidupan kedua bagi pakaian favoritmu.</p>
                <p class="text-xs text-gray-300 mt-3">&copy; {{ date('Y') }} Relovedable. Lovable Selection.</p>
            </div>
            <div class="sm:text-right">
                <p class="text-sm font-semibold text-gray-700 mb-2">Ikuti Kami</p>
                <div class="flex gap-3 sm:justify-end">
                    <a href="https://instagram.com/relovedable.id" target="_blank" rel="noopener" title="Instagram @relovedable.id"
                       class="grid place-items-center w-8 h-8 rounded-full bg-relove-100 text-relove-600 hover:bg-relove-500 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.72 3.72 0 0 1-1.38-.9c-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16Zm0 1.95c-3.14 0-3.51.01-4.75.07-.89.04-1.37.19-1.69.31-.43.17-.73.36-1.05.68-.32.32-.51.62-.68 1.05-.12.32-.27.8-.31 1.69-.06 1.24-.07 1.61-.07 4.75s.01 3.51.07 4.75c.04.89.19 1.37.31 1.69.17.43.36.73.68 1.05.32.32.62.51 1.05.68.32.12.8.27 1.69.31 1.24.06 1.61.07 4.75.07s3.51-.01 4.75-.07c.89-.04 1.37-.19 1.69-.31.43-.17.73-.36 1.05-.68.32-.32.51-.62.68-1.05.12-.32.27-.8.31-1.69.06-1.24.07-1.61.07-4.75s-.01-3.51-.07-4.75c-.04-.89-.19-1.37-.31-1.69a2.82 2.82 0 0 0-.68-1.05 2.82 2.82 0 0 0-1.05-.68c-.32-.12-.8-.27-1.69-.31-1.24-.06-1.61-.07-4.75-.07Zm0 3.32a4.57 4.57 0 1 1 0 9.14 4.57 4.57 0 0 1 0-9.14Zm0 7.54a2.97 2.97 0 1 0 0-5.94 2.97 2.97 0 0 0 0 5.94Zm5.82-7.74a1.07 1.07 0 1 1-2.14 0 1.07 1.07 0 0 1 2.14 0Z"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/62859159788313" target="_blank" rel="noopener" title="WhatsApp 0859159788313"
                       class="grid place-items-center w-8 h-8 rounded-full bg-relove-100 text-relove-600 hover:bg-relove-500 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.87 9.87 0 0 0 4.74 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2Zm0 1.84c2.11 0 4.09.82 5.58 2.31a7.84 7.84 0 0 1 2.31 5.57c0 4.54-3.69 8.23-8.24 8.23-1.5 0-2.97-.4-4.25-1.16l-.31-.18-3.16.83.84-3.08-.2-.32a8.16 8.16 0 0 1-1.26-4.36c0-4.54 3.7-8.23 8.24-8.23Zm-4.53 4.43c-.21 0-.56.08-.85.4-.29.32-1.11 1.09-1.11 2.66s1.14 3.08 1.3 3.29c.16.21 2.25 3.44 5.46 4.82.76.33 1.36.53 1.83.68.77.24 1.47.21 2.02.13.62-.09 1.9-.78 2.17-1.53.27-.75.27-1.39.19-1.53-.08-.13-.29-.21-.61-.37-.32-.16-1.9-.94-2.19-1.04-.29-.11-.51-.16-.72.16-.21.32-.82 1.04-1.01 1.25-.19.21-.37.24-.69.08-.32-.16-1.35-.5-2.57-1.59-.95-.85-1.59-1.89-1.78-2.21-.19-.32-.02-.49.14-.65.14-.14.32-.37.48-.56.16-.19.21-.32.32-.53.11-.21.05-.4-.03-.56-.08-.16-.72-1.74-.99-2.38-.26-.62-.52-.54-.72-.55-.19-.01-.4-.01-.61-.01Z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
