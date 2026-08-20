<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Daftar percakapan user
    public function index()
    {
        $me = auth()->id();

        $chats = Chat::with(['barang.fotoBarangs', 'pengirim', 'penerima'])
            ->where('id_pengirim', $me)
            ->orWhere('id_penerima', $me)
            ->orderByDesc('id_chat')
            ->get();

        // Kelompokkan per (barang + lawan bicara), ambil pesan terbaru tiap grup
        $percakapan = $chats->groupBy(function ($chat) use ($me) {
            $lawan = $chat->id_pengirim === $me ? $chat->id_penerima : $chat->id_pengirim;
            return $chat->id_barang . '-' . $lawan;
        })->map(function ($grup) use ($me) {
            $terbaru = $grup->first();
            $lawan = $terbaru->id_pengirim === $me ? $terbaru->penerima : $terbaru->pengirim;
            return (object) [
                'barang' => $terbaru->barang,
                'lawan' => $lawan,
                'pesan_terakhir' => $terbaru,
            ];
        })->values();

        return view('chat.index', compact('percakapan'));
    }

    // Mulai chat dari halaman barang -> redirect ke thread dgn penjual
    public function mulai($barangId)
    {
        $barang = Barang::with('toko.user')->findOrFail($barangId);
        $penjual = $barang->toko?->user;

        abort_unless($penjual, 404);

        if ($penjual->id_user === auth()->id()) {
            return redirect('/barang/' . $barangId)->with('error', 'Itu barangmu sendiri.');
        }

        return redirect("/chat/{$barangId}/{$penjual->id_user}");
    }

    // Tampilkan thread
    public function show($barangId, $lawanId)
    {
        $me = auth()->id();
        $barang = Barang::with(['fotoBarangs', 'toko'])->findOrFail($barangId);
        $lawan = User::findOrFail($lawanId);

        $pesan = Chat::where('id_barang', $barangId)
            ->where(function ($q) use ($me, $lawanId) {
                $q->where(fn ($x) => $x->where('id_pengirim', $me)->where('id_penerima', $lawanId))
                  ->orWhere(fn ($x) => $x->where('id_pengirim', $lawanId)->where('id_penerima', $me));
            })
            ->orderBy('id_chat')
            ->get();

        return view('chat.show', compact('barang', 'lawan', 'pesan'));
    }

    // Kirim pesan
    public function store(Request $request, $barangId, $lawanId)
    {
        $data = $request->validate([
            'pesan' => ['required', 'string', 'max:2000'],
        ]);

        Barang::findOrFail($barangId);
        User::findOrFail($lawanId);

        Chat::create([
            'id_pengirim' => auth()->id(),
            'id_penerima' => $lawanId,
            'id_barang' => $barangId,
            'pesan' => $data['pesan'],
        ]);

        return redirect("/chat/{$barangId}/{$lawanId}");
    }
}
