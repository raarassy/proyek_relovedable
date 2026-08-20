<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Daftar transaksi: sebagai pembeli & sebagai penjual
    public function index()
    {
        $me = auth()->id();

        $pembelian = Transaksi::with(['barang.fotoBarangs', 'penjual', 'review'])
            ->where('id_pembeli', $me)
            ->latest('id_transaksi')
            ->get();

        $penjualan = Transaksi::with(['barang.fotoBarangs', 'pembeli'])
            ->where('id_penjual', $me)
            ->latest('id_transaksi')
            ->get();

        return view('transaksi.index', compact('pembelian', 'penjualan'));
    }

    // Penjual menandai barang terjual ke pembeli (deal terjadi di luar app)
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang' => ['required', 'exists:barangs,id_barang'],
            'id_pembeli' => ['required', 'exists:users,id_user'],
        ]);

        $barang = Barang::with('toko')->findOrFail($data['id_barang']);

        // Hanya penjual pemilik barang yang boleh menandai
        abort_unless($barang->toko?->id_user === auth()->id(), 403);

        if ($data['id_pembeli'] == auth()->id()) {
            return back()->with('error', 'Pembeli tidak boleh dirimu sendiri.');
        }

        DB::transaction(function () use ($barang, $data) {
            Transaksi::create([
                'id_barang' => $barang->id_barang,
                'id_pembeli' => $data['id_pembeli'],
                'id_penjual' => auth()->id(),
                'status_transaksi' => 'selesai',
            ]);

            $barang->update(['status_barang' => 'terjual']);
        });

        return back()->with('success', 'Barang ditandai terjual. Pembeli kini bisa memberi ulasan.');
    }
}
