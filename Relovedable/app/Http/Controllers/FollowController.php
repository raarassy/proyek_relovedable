<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;

class FollowController extends Controller
{
    // Ikuti / berhenti mengikuti seorang penjual
    public function toggle($userId)
    {
        if ((int) $userId === (int) auth()->id()) {
            return back()->with('error', 'Tidak bisa mengikuti diri sendiri.');
        }

        User::findOrFail($userId);

        $follow = Follow::where('id_pengikut', auth()->id())
            ->where('id_diikuti', $userId)
            ->first();

        if ($follow) {
            $follow->delete();
            $pesan = 'Berhenti mengikuti.';
        } else {
            Follow::create([
                'id_pengikut' => auth()->id(),
                'id_diikuti' => $userId,
            ]);
            $pesan = 'Kamu mengikuti toko ini.';
        }

        return back()->with('success', $pesan);
    }
}
