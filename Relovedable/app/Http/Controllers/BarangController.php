<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\FotoBarang;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    // ===== PUBLIK =====

    // Katalog publik + pencarian + filter kategori
    public function katalog(Request $request)
    {
        $q = $request->string('q')->toString();
        $kategori = $request->string('kategori')->toString();

        $barangs = Barang::with(['fotoBarangs', 'toko'])
            ->where('status_barang', 'tersedia')
            ->when($q, fn ($query) => $query->where('nama_barang', 'like', "%{$q}%"))
            ->when($kategori, fn ($query) => $query->where('kategori', $kategori))
            ->latest('id_barang')
            ->paginate(12)
            ->withQueryString();

        $kategoriList = Barang::query()
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('barang.katalog', compact('barangs', 'kategoriList', 'q', 'kategori'));
    }

    // Detail produk
    public function show($id)
    {
        $barang = Barang::with(['fotoBarangs', 'toko.user'])->findOrFail($id);

        $penjualId = $barang->toko?->id_user;

        // Review yang diterima penjual (agregat)
        $reviews = Review::with('pembeli')
            ->where('id_penjual', $penjualId)
            ->latest('id_review')
            ->take(10)
            ->get();

        $ratingRata = Review::where('id_penjual', $penjualId)->avg('rating');
        $jumlahReview = Review::where('id_penjual', $penjualId)->count();

        // Produk serupa
        $serupa = Barang::with(['fotoBarangs', 'toko'])
            ->where('kategori', $barang->kategori)
            ->where('id_barang', '!=', $barang->id_barang)
            ->where('status_barang', 'tersedia')
            ->take(4)
            ->get();

        return view('barang.show', compact('barang', 'reviews', 'ratingRata', 'jumlahReview', 'serupa'));
    }

    // ===== PENJUAL (butuh role penjual) =====

    // Daftar barang milik toko sendiri
    public function index()
    {
        $toko = auth()->user()->toko;

        $barangs = Barang::with('fotoBarangs')
            ->where('id_toko', $toko->id_toko)
            ->latest('id_barang')
            ->get();

        return view('barang.index', compact('barangs', 'toko'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateBarang($request);

        $toko = auth()->user()->toko;

        $barang = Barang::create([
            'id_toko' => $toko->id_toko,
            'nama_barang' => $data['nama_barang'],
            'kategori' => $data['kategori'],
            'harga' => $data['harga'],
            'deskripsi' => $data['deskripsi'],
            'kondisi' => $data['kondisi'],
            'metode_transaksi' => $request->boolean('metode_transaksi'),
            'status_barang' => 'tersedia',
        ]);

        $this->simpanFoto($request, $barang);

        return redirect('/barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::with('fotoBarangs')->findOrFail($id);
        $this->pastikanPemilik($barang);

        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $this->pastikanPemilik($barang);

        $data = $this->validateBarang($request);

        $barang->update([
            'nama_barang' => $data['nama_barang'],
            'kategori' => $data['kategori'],
            'harga' => $data['harga'],
            'deskripsi' => $data['deskripsi'],
            'kondisi' => $data['kondisi'],
            'metode_transaksi' => $request->boolean('metode_transaksi'),
            'status_barang' => $data['status_barang'] ?? $barang->status_barang,
        ]);

        $this->simpanFoto($request, $barang);

        return redirect('/barang')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::with('fotoBarangs')->findOrFail($id);
        $this->pastikanPemilik($barang);

        // Hapus file foto dari storage
        foreach ($barang->fotoBarangs as $foto) {
            Storage::disk('public')->delete($foto->path_foto);
        }

        $barang->delete(); // cascade hapus foto_barangs di DB

        return redirect('/barang')->with('success', 'Barang dihapus.');
    }

    // ===== Helper =====

    private function validateBarang(Request $request): array
    {
        return $request->validate([
            'nama_barang' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:50'],
            'harga' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'kondisi' => ['required', 'in:baru,seperti_baru,bekas'],
            'status_barang' => ['nullable', 'in:tersedia,terjual,nonaktif'],
            'foto' => ['nullable', 'array'],
            'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function simpanFoto(Request $request, Barang $barang): void
    {
        if (! $request->hasFile('foto')) {
            return;
        }

        foreach ($request->file('foto') as $file) {
            $path = $file->store('barang', 'public');
            FotoBarang::create([
                'id_barang' => $barang->id_barang,
                'path_foto' => $path,
            ]);
        }
    }

    private function pastikanPemilik(Barang $barang): void
    {
        if ($barang->toko?->id_user !== auth()->id()) {
            abort(403, 'Ini bukan barang tokomu.');
        }
    }
}
