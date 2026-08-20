<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class HomeController extends Controller
{
    // Root: tamu lihat landing, yang sudah login lihat beranda katalog
    public function index()
    {
        return auth()->check() ? $this->beranda() : $this->landing();
    }

    // Landing page (marketing) — selalu bisa diakses di /landing-page
    public function landing()
    {
        return view('landing');
    }

    // Beranda katalog + rekomendasi — selalu bisa diakses di /beranda
    public function beranda()
    {
        $rekomendasi = Barang::with(['fotoBarangs', 'toko'])
            ->where('status_barang', 'tersedia')
            ->latest('id_barang')
            ->take(8)
            ->get();

        return view('home', compact('rekomendasi'));
    }
}
