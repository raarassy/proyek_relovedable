<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Review;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load('toko');

        $jumlahFavorit = $user->favorits()->count();
        $jumlahMengikuti = Follow::where('id_pengikut', $user->id_user)->count();

        $mengikuti = Follow::with('diikuti.toko')
            ->where('id_pengikut', $user->id_user)
            ->latest('id_follow')
            ->get();

        return view('profil.show', compact('user', 'jumlahFavorit', 'jumlahMengikuti', 'mengikuti'));
    }

    // Profil publik pengguna lain (mis. penjual melihat pembeli)
    public function publik($id)
    {
        // Profil sendiri diarahkan ke halaman profil pribadi
        if (auth()->id() === (int) $id) {
            return redirect('/profil');
        }

        $user = User::with('toko')->findOrFail($id);

        $jumlahPembelian = Transaksi::where('id_pembeli', $user->id_user)
            ->where('status_transaksi', 'selesai')
            ->count();

        $ulasan = Review::with('penjual.toko', 'transaksi.barang')
            ->where('id_pembeli', $user->id_user)
            ->latest('tanggal_review')
            ->get();

        return view('profil.publik', compact('user', 'jumlahPembelian', 'ulasan'));
    }

    public function edit()
    {
        $user = auth()->user();

        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id_user, 'id_user')],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id_user, 'id_user')],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $update = [
            'nama' => $data['nama'],
            'username' => $data['username'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'] ?? null,
        ];

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $update['foto_profil'] = $request->file('foto_profil')->store('profil', 'public');
        }

        if (! empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $user->update($update);

        return redirect('/profil')->with('success', 'Profil diperbarui.');
    }
}
