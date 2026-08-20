@extends('layouts.app')
@section('title', 'Tambah Barang')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Upload Barang</h1>
    <p class="text-sm text-gray-400 mb-6">Lengkapi detail barang preloved-mu.</p>

    <form action="{{ url('/barang/store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-relove-100 p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Foto Barang <span class="text-gray-300">(maksimal 8)</span></label>
            <input type="file" name="foto[]" id="fotoInput" multiple accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-3 file:rounded-full file:border-0 file:bg-relove-100 file:text-relove-600 file:px-4 file:py-2 file:font-semibold">
            <p id="fotoInfo" class="text-xs text-gray-400 mt-1.5">Bisa pilih beberapa sekaligus. Maksimal 8 foto, maks 4MB per foto, format JPG/PNG/WebP.</p>
            <div id="fotoPreview" class="flex flex-wrap gap-2 mt-3"></div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required
                   class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
                <select name="kategori" required
                        class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
                    <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih kategori…</option>
                    @foreach(\App\Models\Barang::KATEGORI as $kat)
                        <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga') }}" required min="0"
                       class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Kondisi</label>
                <select name="kondisi" required
                        class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">
                    <option value="baru">Baru</option>
                    <option value="seperti_baru">Seperti Baru</option>
                    <option value="bekas" selected>Bekas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Metode Transaksi</label>
                <div class="flex flex-col gap-2 mt-2">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="bisa_cod" value="1" {{ old('bisa_cod') ? 'checked' : '' }}
                               class="rounded border-relove-300 text-relove-500 focus:ring-relove-300">
                        COD (ketemuan langsung)
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="bisa_ekspedisi" value="1" {{ old('bisa_ekspedisi', true) ? 'checked' : '' }}
                               class="rounded border-relove-300 text-relove-500 focus:ring-relove-300">
                        Ekspedisi (kirim via kurir)
                    </label>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4" required
                      class="w-full rounded-xl border border-relove-200 px-4 py-2.5 text-sm focus:border-relove-400 focus:ring-2 focus:ring-relove-200 outline-none">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ url('/barang') }}" class="rounded-xl border border-relove-200 text-gray-600 font-semibold px-5 py-2.5 text-sm hover:bg-relove-50">Batal</a>
            <button type="submit" class="flex-1 rounded-xl bg-relove-500 hover:bg-relove-600 text-white font-semibold py-2.5">Publikasikan Barang</button>
        </div>
    </form>
</div>

<script>
(function () {
    const input = document.getElementById('fotoInput');
    const info = document.getElementById('fotoInfo');
    const preview = document.getElementById('fotoPreview');
    if (!input) return;
    const MAX = 8;

    // Penampung file yang dikumpulkan (biar pilih satu-satu tetap numpuk, nggak ke-replace)
    let store = new DataTransfer();

    input.addEventListener('change', function () {
        let ditolak = 0;
        Array.from(input.files).forEach(function (file) {
            if (!file.type.startsWith('image/')) return;
            if (store.items.length >= MAX) { ditolak++; return; }
            store.items.add(file);
        });
        input.files = store.files; // sinkron ke input untuk submit
        render(ditolak);
    });

    function hapus(index) {
        const baru = new DataTransfer();
        Array.from(store.files).forEach(function (file, i) {
            if (i !== index) baru.items.add(file);
        });
        store = baru;
        input.files = store.files;
        render(0);
    }

    function render(ditolak) {
        preview.innerHTML = '';
        Array.from(store.files).forEach(function (file, i) {
            const wrap = document.createElement('div');
            wrap.className = 'relative';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-20 h-20 rounded-xl object-cover border border-relove-100';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = '×';
            btn.title = 'Hapus foto';
            btn.className = 'absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-red-500 text-white text-xs leading-none grid place-items-center shadow';
            btn.addEventListener('click', function () { hapus(i); });

            wrap.appendChild(img);
            wrap.appendChild(btn);
            preview.appendChild(wrap);
        });

        const n = store.files.length;
        if (ditolak > 0) {
            info.textContent = `Maksimal ${MAX} foto. ${ditolak} foto terakhir tidak ditambahkan.`;
            info.className = 'text-xs text-red-500 mt-1.5 font-medium';
        } else if (n >= MAX) {
            info.textContent = `Batas ${MAX} foto tercapai.`;
            info.className = 'text-xs text-relove-600 mt-1.5 font-medium';
        } else if (n > 0) {
            info.textContent = `${n} dari ${MAX} foto dipilih. Bisa tambah lagi.`;
            info.className = 'text-xs text-gray-500 mt-1.5';
        } else {
            info.textContent = `Bisa pilih beberapa sekaligus. Maksimal ${MAX} foto, maks 4MB per foto, format JPG/PNG/WebP.`;
            info.className = 'text-xs text-gray-400 mt-1.5';
        }
    }
})();
</script>
@endsection
