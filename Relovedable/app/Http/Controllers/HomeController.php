<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class HomeController extends Controller
{
    public function index()
    {
        $rekomendasi = Barang::with(['fotoBarangs', 'toko'])
            ->where('status_barang', 'tersedia')
            ->latest('id_barang')
            ->take(8)
            ->get();

        return view('home', compact('rekomendasi'));
    }
}
