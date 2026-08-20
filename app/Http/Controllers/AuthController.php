<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ===== Registrasi =====
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'nama' => $data['nama'],
            'username' => $data['username'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'] ?? null,
            'foto_profil' => $request->file('foto_profil')?->store('profil', 'public'),
            'password' => Hash::make($data['password']),
            'role' => 'pembeli',
            'status_akun' => 'pending',
        ]);

        // Belum login — akun harus diverifikasi admin dulu
        return redirect('/login')->with('success', 'Pendaftaran berhasil! Akunmu menunggu verifikasi admin. Kamu bisa masuk setelah disetujui.');
    }

    // ===== Login =====
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        // Password benar — cek status verifikasi akun
        if (! auth()->user()->isAktif()) {
            $status = auth()->user()->status_akun;
            Auth::logout();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->with('error', $status === 'ditolak'
                    ? 'Akunmu ditolak oleh admin.'
                    : 'Akunmu masih menunggu verifikasi admin.');
        }

        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'Berhasil masuk.');
    }

    // ===== Logout =====
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Kamu sudah keluar.');
    }
}
