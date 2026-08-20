<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Form ulasan untuk sebuah transaksi
    public function create($transaksiId)
    {
        $transaksi = Transaksi::with(['barang.fotoBarangs', 'penjual'])->findOrFail($transaksiId);

        $this->pastikanBolehReview($transaksi);

        return view('review.create', compact('transaksi'));
    }

    // Simpan ulasan
    public function store(Request $request, $transaksiId)
    {
        $transaksi = Transaksi::findOrFail($transaksiId);

        $this->pastikanBolehReview($transaksi);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'ulasan' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_pembeli' => auth()->id(),
            'id_penjual' => $transaksi->id_penjual,
            'rating' => $data['rating'],
            'ulasan' => $data['ulasan'] ?? null,
        ]);

        // Balik ke halaman barang -> rating & ulasan baru langsung terlihat terupdate
        return redirect('/barang/' . $transaksi->id_barang)->with('success', 'Terima kasih atas ulasanmu! ⭐');
    }

    private function pastikanBolehReview(Transaksi $transaksi): void
    {
        // Hanya pembeli, transaksi selesai, dan belum pernah review
        abort_unless($transaksi->id_pembeli === auth()->id(), 403);
        abort_unless($transaksi->status_transaksi === 'selesai', 403, 'Transaksi belum selesai.');

        if ($transaksi->review) {
            abort(403, 'Kamu sudah memberi ulasan untuk transaksi ini.');
        }
    }
}
