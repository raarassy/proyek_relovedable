<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\VerifikasiPenjual;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Daftar pengajuan verifikasi penjual
    public function verifikasi()
    {
        $pengajuan = VerifikasiPenjual::with('user')
            ->orderByRaw("FIELD(status_verifikasi, 'pending', 'disetujui', 'ditolak')")
            ->latest('id_verif_pen')
            ->get();

        return view('admin.verifikasi', compact('pengajuan'));
    }

    // Tampilkan foto KTP (privat, hanya admin)
    public function ktp($id)
    {
        $v = VerifikasiPenjual::findOrFail($id);

        abort_unless(Storage::disk('local')->exists($v->foto_ktp), 404);

        return Storage::disk('local')->response($v->foto_ktp);
    }

    // Setujui — jadikan penjual + buat toko
    public function approve($id)
    {
        $v = VerifikasiPenjual::with('user')->findOrFail($id);

        if ($v->status_verifikasi === 'disetujui') {
            return back()->with('error', 'Pengajuan sudah disetujui.');
        }

        DB::transaction(function () use ($v) {
            $v->update([
                'status_verifikasi' => 'disetujui',
                'tanggal_verifikasi' => now(),
            ]);

            $v->user->update(['role' => 'penjual']);

            // Buat toko bila belum ada
            Toko::firstOrCreate(
                ['id_user' => $v->id_user],
                [
                    'nama_toko' => $v->nama_toko,
                    'alamat_toko' => $v->alamat_toko,
                    'deskripsi_toko' => $v->deskripsi_toko,
                ]
            );
        });

        return back()->with('success', 'Penjual disetujui & toko dibuat.');
    }

    // Tolak
    public function reject($id)
    {
        $v = VerifikasiPenjual::findOrFail($id);

        $v->update([
            'status_verifikasi' => 'ditolak',
            'tanggal_verifikasi' => now(),
        ]);

        return back()->with('success', 'Pengajuan ditolak.');
    }
}
