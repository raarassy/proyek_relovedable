<?php

namespace App\Http\Controllers;

use App\Models\VerifikasiPenjual;
use Illuminate\Http\Request;

class VerifikasiPenjualController extends Controller
{
    // Form daftar sebagai penjual
    public function create()
    {
        $user = auth()->user();

        if ($user->isPenjual()) {
            return redirect('/barang')->with('success', 'Kamu sudah menjadi penjual.');
        }

        $pengajuan = $user->verifikasiPenjual; // terbaru

        return view('penjual.daftar', compact('pengajuan'));
    }

    // Submit pengajuan
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->isPenjual()) {
            return redirect('/barang');
        }

        // Cegah pengajuan ganda yang masih pending
        $pending = $user->verifikasiPenjual;
        if ($pending && $pending->status_verifikasi === 'pending') {
            return back()->with('error', 'Pengajuanmu masih menunggu verifikasi admin.');
        }

        $data = $request->validate([
            'nik' => ['required', 'digits:16'],
            'foto_ktp' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'nama_toko' => ['required', 'string', 'max:100'],
            'alamat_toko' => ['nullable', 'string', 'max:255'],
            'deskripsi_toko' => ['nullable', 'string'],
        ]);

        // Simpan KTP di disk privat (tidak dapat diakses publik)
        $pathKtp = $request->file('foto_ktp')->store('ktp', 'local');

        VerifikasiPenjual::create([
            'id_user' => $user->id_user,
            'nik' => $data['nik'],
            'foto_ktp' => $pathKtp,
            'status_verifikasi' => 'pending',
            'nama_toko' => $data['nama_toko'],
            'alamat_toko' => $data['alamat_toko'] ?? null,
            'deskripsi_toko' => $data['deskripsi_toko'] ?? null,
        ]);

        return redirect('/penjual/daftar')->with('success', 'Pengajuan dikirim! Tunggu verifikasi admin.');
    }
}
