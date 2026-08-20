<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Favorit;
use App\Models\Follow;
use App\Models\Review;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Admin =====
        User::create([
            'nama' => 'Admin Relovedable',
            'username' => 'admin',
            'email' => 'admin@relovedable.test',
            'password' => Hash::make('password'),
            'no_hp' => '081200000000',
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        // ===== Penjual + Toko + Barang =====
        $tokoData = [
            ['FurShop', 'Bandung', 'Preloved branded pilihan, kondisi terjaga.'],
            ['ThriftKlik', 'Jakarta', 'Outfit kekinian harga mahasiswa.'],
            ['LemariLama', 'Yogyakarta', 'Vintage & retro finds setiap minggu.'],
        ];

        $kategori = Barang::KATEGORI;
        $kondisi = ['baru', 'seperti_baru', 'bekas'];

        $penjuals = [];
        foreach ($tokoData as $i => [$namaToko, $kota, $deskripsi]) {
            $penjual = User::create([
                'nama' => 'Penjual ' . ($i + 1),
                'username' => 'penjual' . ($i + 1),
                'email' => 'penjual' . ($i + 1) . '@relovedable.test',
                'password' => Hash::make('password'),
                'no_hp' => '08130000000' . $i,
                'role' => 'penjual',
                'status_akun' => 'aktif',
            ]);
            $penjuals[] = $penjual;

            $toko = Toko::create([
                'id_user' => $penjual->id_user,
                'nama_toko' => $namaToko,
                'alamat_toko' => $kota,
                'deskripsi_toko' => $deskripsi,
            ]);

            for ($b = 1; $b <= 5; $b++) {
                Barang::create([
                    'id_toko' => $toko->id_toko,
                    'nama_barang' => $kategori[array_rand($kategori)] . ' Preloved #' . $b,
                    'kategori' => $kategori[array_rand($kategori)],
                    'harga' => rand(3, 30) * 10000,
                    'deskripsi' => 'Barang preloved berkualitas, dirawat dengan baik. Cocok untuk gaya kasual maupun formal.',
                    'kondisi' => $kondisi[array_rand($kondisi)],
                    'bisa_cod' => (bool) rand(0, 1),
                    'bisa_ekspedisi' => true,
                    'status_barang' => 'tersedia',
                ]);
            }
        }

        // ===== Pembeli =====
        $pembelis = collect();
        for ($i = 1; $i <= 5; $i++) {
            $pembelis->push(User::create([
                'nama' => 'Pembeli ' . $i,
                'username' => 'pembeli' . $i,
                'email' => 'pembeli' . $i . '@relovedable.test',
                'password' => Hash::make('password'),
                'no_hp' => '08210000000' . $i,
                'role' => 'pembeli',
                'status_akun' => 'aktif',
            ]));
        }

        $semuaBarang = Barang::all();

        // ===== Favorit acak =====
        foreach ($pembelis as $pembeli) {
            foreach ($semuaBarang->random(4) as $barang) {
                Favorit::firstOrCreate([
                    'id_barang' => $barang->id_barang,
                    'id_user' => $pembeli->id_user,
                ]);
            }
        }

        // ===== Follow acak =====
        foreach ($pembelis as $pembeli) {
            foreach (collect($penjuals)->random(2) as $penjual) {
                Follow::firstOrCreate([
                    'id_pengikut' => $pembeli->id_user,
                    'id_diikuti' => $penjual->id_user,
                ]);
            }
        }

        // ===== Transaksi selesai + Review (1 per penjual) =====
        foreach ($penjuals as $idx => $penjual) {
            $barang = $semuaBarang->firstWhere('id_toko', $penjual->toko->id_toko);
            $pembeli = $pembelis[$idx % $pembelis->count()];

            $barang->update(['status_barang' => 'terjual']);

            $transaksi = Transaksi::create([
                'id_barang' => $barang->id_barang,
                'id_pembeli' => $pembeli->id_user,
                'id_penjual' => $penjual->id_user,
                'metode' => $barang->bisa_cod ? collect(['cod', 'ekspedisi'])->random() : 'ekspedisi',
                'status_transaksi' => 'selesai',
            ]);

            Review::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'id_pembeli' => $pembeli->id_user,
                'id_penjual' => $penjual->id_user,
                'rating' => rand(4, 5),
                'ulasan' => 'Barang sesuai foto, penjual ramah & fast respond. Recommended!',
            ]);
        }

        // ===== Transaksi selesai BELUM diulas (uji tombol "Beri Ulasan") =====
        // Pembeli1 dapat 1 transaksi selesai tanpa review -> tombol "Beri Ulasan" muncul.
        $penjualUji = $penjuals[0];
        $barangUji = $semuaBarang
            ->where('id_toko', $penjualUji->toko->id_toko)
            ->firstWhere('status_barang', 'tersedia');

        if ($barangUji) {
            $barangUji->update(['status_barang' => 'terjual']);

            Transaksi::create([
                'id_barang' => $barangUji->id_barang,
                'id_pembeli' => $pembelis->first()->id_user,
                'id_penjual' => $penjualUji->id_user,
                'metode' => $barangUji->bisa_cod ? 'cod' : 'ekspedisi',
                'status_transaksi' => 'selesai',
            ]);
            // sengaja TANPA Review
        }
    }
}
