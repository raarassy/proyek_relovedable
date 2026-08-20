<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Follow;
use App\Models\FotoBarang;
use App\Models\Review;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        $kategoriList = collect(Barang::KATEGORI);

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

        // Status follow toko (menggantikan favorit)
        $sedangDiikuti = auth()->check() && $penjualId
            ? Follow::where('id_pengikut', auth()->id())->where('id_diikuti', $penjualId)->exists()
            : false;

        // Transaksi selesai milik pembeli (yang login) untuk barang ini yang belum diulas
        // -> jadi pintu masuk tombol "Beri Ulasan" langsung di halaman barang
        $transaksiBelumDiulas = auth()->check()
            ? Transaksi::where('id_pembeli', auth()->id())
                ->where('id_barang', $barang->id_barang)
                ->where('status_transaksi', 'selesai')
                ->whereDoesntHave('review')
                ->latest('id_transaksi')
                ->first()
            : null;

        return view('barang.show', compact('barang', 'reviews', 'ratingRata', 'jumlahReview', 'serupa', 'sedangDiikuti', 'transaksiBelumDiulas'));
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
        $this->pastikanAdaMetode($request);

        $toko = auth()->user()->toko;

        $barang = Barang::create([
            'id_toko' => $toko->id_toko,
            'nama_barang' => $data['nama_barang'],
            'kategori' => $data['kategori'],
            'harga' => $data['harga'],
            'deskripsi' => $data['deskripsi'],
            'kondisi' => $data['kondisi'],
            'bisa_cod' => $request->boolean('bisa_cod'),
            'bisa_ekspedisi' => $request->boolean('bisa_ekspedisi'),
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
        $this->pastikanAdaMetode($request);

        // Total foto (lama + baru) tidak boleh lebih dari 8
        $jumlahLama = $barang->fotoBarangs()->count();
        $jumlahBaru = $request->hasFile('foto') ? count($request->file('foto')) : 0;
        if ($jumlahLama + $jumlahBaru > 8) {
            return back()->withInput()
                ->with('error', "Maksimal 8 foto per barang. Sudah ada {$jumlahLama}, kamu menambah {$jumlahBaru}.");
        }

        $barang->update([
            'nama_barang' => $data['nama_barang'],
            'kategori' => $data['kategori'],
            'harga' => $data['harga'],
            'deskripsi' => $data['deskripsi'],
            'kondisi' => $data['kondisi'],
            'bisa_cod' => $request->boolean('bisa_cod'),
            'bisa_ekspedisi' => $request->boolean('bisa_ekspedisi'),
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
            'kategori' => ['required', Rule::in(Barang::KATEGORI)],
            'harga' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'kondisi' => ['required', 'in:baru,seperti_baru,bekas'],
            'status_barang' => ['nullable', 'in:tersedia,terjual,nonaktif'],
            'foto' => ['nullable', 'array', 'max:8'],
            'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function pastikanAdaMetode(Request $request): void
    {
        if (! $request->boolean('bisa_cod') && ! $request->boolean('bisa_ekspedisi')) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'bisa_cod' => 'Pilih minimal satu metode transaksi (COD atau Ekspedisi).',
            ]);
        }
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
