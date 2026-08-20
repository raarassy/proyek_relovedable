<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Favorit;

class FavoritController extends Controller
{
    // Daftar favorit user
    public function index()
    {
        $favorits = Favorit::with(['barang.fotoBarangs', 'barang.toko'])
            ->where('id_user', auth()->id())
            ->latest('id_favorit')
            ->get()
            ->pluck('barang')
            ->filter();

        return view('favorit.index', compact('favorits'));
    }

    // Toggle favorit
    public function toggle($barangId)
    {
        Barang::findOrFail($barangId);

        $favorit = Favorit::where('id_barang', $barangId)
            ->where('id_user', auth()->id())
            ->first();

        if ($favorit) {
            $favorit->delete();
            $pesan = 'Dihapus dari favorit.';
        } else {
            Favorit::create([
                'id_barang' => $barangId,
                'id_user' => auth()->id(),
            ]);
            $pesan = 'Ditambahkan ke favorit ❤️';
        }

        return back()->with('success', $pesan);
    }
}
