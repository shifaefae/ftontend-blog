@extends('layout.App')

@section('title', 'Edit Iklan - Portal Blog')

@section('content')
<div class="min-h-screen bg-white p-6">

    <div class="mb-8">
        <h1 class="flex items-center gap-4 text-4xl font-bold text-black">
            <span class="p-4 bg-white rounded-xl shadow">
                <i class="fas fa-edit text-[#4988C4] text-3xl"></i>
            </span>
            Edit Iklan
        </h1>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="max-w-xl bg-white rounded-2xl shadow p-8">

        <form action="{{ route('iklan.update', $iklan['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Iklan</label>
                <input name="name"
                       value="{{ $iklan['name'] ?? '' }}"
                       class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40"
                       placeholder="Nama iklan" required>
            </div>

            {{-- Posisi --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Posisi</label>
                <select name="position" class="w-full border rounded-xl p-3" required>
                    <option value="">Pilih Posisi</option>
                    <option value="slide_1x1" {{ ($iklan['position'] ?? '') === 'slide_1x1' ? 'selected' : '' }}>1:1 Slide</option>
                    <option value="right_3x1" {{ ($iklan['position'] ?? '') === 'right_3x1' ? 'selected' : '' }}>3:1 Kanan</option>
                    <option value="left_3x1"  {{ ($iklan['position'] ?? '') === 'left_3x1'  ? 'selected' : '' }}>3:1 Kiri</option>
                </select>
            </div>

            {{-- Prioritas --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Prioritas</label>
                <input type="number" name="priority"
                       value="{{ $iklan['priority'] ?? 1 }}"
                       class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40"
                       min="1" placeholder="1">
            </div>

            {{-- Link --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Link (optional)</label>
                <input name="link"
                       value="{{ $iklan['link'] ?? '' }}"
                       class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40"
                       placeholder="https://...">
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded-xl p-3" required>
                    <option value="active"   {{ ($iklan['status'] ?? '') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($iklan['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            {{-- Thumbnail — pola sama seperti Editblog --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar (kosongkan jika tidak diubah)</label>

                @php
                    $thumbRaw = $iklan['thumbnail'] ?? '';
                    if (!empty($thumbRaw)) {
                        $thumbFull = str_starts_with($thumbRaw, 'http')
                            ? $thumbRaw
                            : rtrim(env('MEDIA', ''), '/') . '/storage/' . ltrim($thumbRaw, '/');
                        $thumbUrl = route('proxy.image', ['url' => $thumbFull]);
                    } else {
                        $thumbUrl = '';
                    }
                @endphp

                {{-- Satu kotak gambar: tampil lama atau preview baru --}}
                @if(!empty($thumbUrl))
                <div id="thumbWrapper"
                     class="relative rounded-xl overflow-hidden border-2 border-gray-200 shadow mb-3">
                    <img id="singleThumb"
                         src="{{ $thumbUrl }}"
                         class="w-full h-48 object-cover block"
                         alt="Thumbnail iklan">
                    <span id="badgeGambar"
                          class="absolute top-2 left-2 text-xs font-bold px-2 py-0.5 rounded-full
                                 bg-gray-700/70 text-white">
                        Gambar saat ini
                    </span>
                    <button type="button" id="btnBatalGambar" onclick="batalGambar()"
                            class="hidden absolute top-2 right-2 bg-white/90 hover:bg-white
                                   text-gray-700 text-xs font-semibold px-3 py-1 rounded-full shadow
                                   border border-gray-300 transition">
                        ✕ Batalkan
                    </button>
                </div>
                @else
                <div id="thumbWrapper"
                     class="relative rounded-xl overflow-hidden border-2 border-gray-200 shadow mb-3"
                     style="display:none">
                    <img id="singleThumb"
                         src=""
                         class="w-full h-48 object-cover block"
                         alt="Thumbnail iklan">
                    <span id="badgeGambar"
                          class="absolute top-2 left-2 text-xs font-bold px-2 py-0.5 rounded-full
                                 bg-[#4988C4] text-white">
                        ✓ Gambar baru
                    </span>
                    <button type="button" id="btnBatalGambar" onclick="batalGambar()"
                            class="hidden absolute top-2 right-2 bg-white/90 hover:bg-white
                                   text-gray-700 text-xs font-semibold px-3 py-1 rounded-full shadow
                                   border border-gray-300 transition">
                        ✕ Batalkan
                    </button>
                </div>
                @endif

                <label for="inputGambar"
                       class="flex items-center gap-3 w-full border-2 border-dashed border-[#4988C4]
                              rounded-xl p-3 cursor-pointer hover:bg-blue-50 transition">
                    <i class="fas fa-cloud-upload-alt text-[#4988C4] text-xl"></i>
                    <span id="namaFile" class="text-gray-500 text-sm truncate">
                        {{ !empty($thumbUrl) ? 'Ganti gambar' : 'Pilih gambar...' }}
                    </span>
                </label>
                <input type="file" name="thumbnail" id="inputGambar" accept="image/*"
                       class="hidden" onchange="previewGambar(event)">
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 mt-6">
                <a href="{{ route('iklan.list') }}"
                   class="flex-1 text-center py-3 rounded-xl border border-gray-300 font-semibold text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-[#4988C4] text-white py-3 rounded-xl font-bold hover:bg-[#3a77b0] transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
var _thumbSrcLama = (function() {
    var el = document.getElementById('singleThumb');
    return el ? el.src : '';
})();

function previewGambar(event) {
    var file = event.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var wrapper  = document.getElementById('thumbWrapper');
        var img      = document.getElementById('singleThumb');
        var badge    = document.getElementById('badgeGambar');
        var btnBatal = document.getElementById('btnBatalGambar');
        var namaFile = document.getElementById('namaFile');

        wrapper.style.display      = '';
        img.src                    = e.target.result;
        badge.textContent          = '✓ Gambar baru';
        badge.style.backgroundColor = '#4988C4';
        btnBatal.classList.remove('hidden');
        namaFile.textContent       = file.name;
    };
    reader.readAsDataURL(file);
}

function batalGambar() {
    var wrapper  = document.getElementById('thumbWrapper');
    var img      = document.getElementById('singleThumb');
    var badge    = document.getElementById('badgeGambar');
    var btnBatal = document.getElementById('btnBatalGambar');
    var namaFile = document.getElementById('namaFile');
    var input    = document.getElementById('inputGambar');

    input.value = '';

    if (_thumbSrcLama && _thumbSrcLama !== window.location.href) {
        img.src                    = _thumbSrcLama;
        badge.textContent          = 'Gambar saat ini';
        badge.style.backgroundColor = '';
        namaFile.textContent       = 'Ganti gambar';
        btnBatal.classList.add('hidden');
    } else {
        wrapper.style.display = 'none';
        namaFile.textContent  = 'Pilih gambar...';
        btnBatal.classList.add('hidden');
    }
}
</script>

@endsection