@extends('layout.App')

@section('title', 'E-Jurnal - Portal Blog')

@section('content')
<div class="min-h-screen bg-[#fbfbfc] p-6">
<div class="max-w-7xl mx-auto">

{{-- HEADER --}}
<div class="mb-8">
    <h1 class="flex items-center gap-4 text-4xl font-bold text-black">
        <div class="p-4 rounded-2xl bg-[#4988C4]">
            <i class="fas fa-book-bookmark text-white text-3xl"></i>
        </div>
        Kelola E-Jurnal
    </h1>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
@endif

{{-- GRID --}}
<div class="grid grid-cols-1 lg:grid-cols-[35%_63%] gap-6">

    {{-- FORM TAMBAH --}}
    <div class="bg-white rounded-2xl p-6 shadow-xl h-fit">
        <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
            <i class="fas fa-plus-circle mr-2"></i>Tambah E-Jurnal
        </h2>

        <form action="{{ route('ejurnal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input name="title" type="text" placeholder="Judul jurnal"
                   class="w-full mb-3 px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4988C4]/30" required>
            <textarea name="description" rows="3" placeholder="Deskripsi (opsional)"
                      class="w-full mb-3 px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4988C4]/30"></textarea>
            <select name="status" class="w-full mb-3 px-4 py-3 border rounded-xl">
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>

            <div class="mb-4">
                <input type="file" id="inputGambarTambah" name="thumbnail" accept="image/*"
                       class="hidden" onchange="previewGambarTambah(event)">
                <label for="inputGambarTambah"
                       class="flex items-center justify-center gap-2 px-4 py-3 cursor-pointer
                              border-2 border-dashed border-[#4988C4] rounded-xl
                              text-[#4988C4] font-medium hover:bg-[#4988C4]/10 transition">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span id="namaFileTambah">Pilih Gambar (opsional)</span>
                </label>
                <div id="previewWrapTambah" class="hidden mt-3">
                    <img id="previewTambah" class="w-full h-36 object-cover rounded-xl border-2 border-[#4988C4]">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-[#4988C4] text-white py-3 rounded-xl font-semibold hover:bg-[#3a6ea0] transition">
                Simpan E-Jurnal
            </button>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl p-6 shadow-xl">
        <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
            <i class="fas fa-list mr-2"></i>Daftar E-Jurnal
        </h2>

        <div class="relative mb-4">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <i class="fas fa-search text-sm"></i>
            </span>
            <input type="text" id="searchEjurnal" oninput="searchTable('ejurnal')"
                   placeholder="Cari jurnal..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm
                          focus:outline-none focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition">
        </div>

        <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabelEjurnal">
            <thead class="bg-[#4988C4]/10 text-[#4988C4]">
                <tr>
                    <th class="p-3 text-center w-8">No</th>
                    <th class="p-3 text-center w-14">Foto</th>
                    <th class="p-3 text-left">Judul</th>
                    <th class="p-3 text-left">Deskripsi</th>
                    <th class="p-3 text-left">User</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="bodyEjurnal">
                @forelse($ejurnals as $index => $item)
                @php
                    $rawThumb = $item['thumbnail'] ?? '';
                    $thumbList = '';
                    if (!empty($rawThumb)) {
                        $fullThumb = str_starts_with($rawThumb, 'http')
                            ? $rawThumb
                            : rtrim(env('MEDIA', ''), '/') . '/storage/' . ltrim($rawThumb, '/');
                        $thumbList = '/proxy-image?url=' . urlencode($fullThumb);
                    }
                @endphp
                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3 text-center text-gray-500">{{ $index + 1 }}</td>

                    {{-- Foto --}}
                    <td class="p-3">
                        @if($thumbList)
                            <img src="{{ $thumbList }}"
                                 class="w-12 h-12 rounded-lg object-cover" loading="lazy"
                                 onerror="this.outerHTML='<div class=\'w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center\'><i class=\'fas fa-image text-gray-400 text-xs\'></i></div>'">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-xs"></i>
                            </div>
                        @endif
                    </td>

                    <td class="p-3 font-semibold text-gray-800">{{ $item['title'] ?? '-' }}</td>

                    <td class="p-3 text-gray-500 text-xs max-w-[160px]">
                        <span class="line-clamp-2">{{ $item['description'] ?? '-' }}</span>
                    </td>

                    <td class="p-3 text-gray-600 text-xs">{{ $item['user']['name'] ?? '-' }}</td>

                    <td class="p-3 text-center">
                        @if(($item['status'] ?? '') === 'published')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Published</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">Draft</span>
                        @endif
                    </td>

                    {{-- Kirim thumbRaw ke modal agar bisa tampilkan gambar lama --}}
                    <td class="p-3 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openEditEjurnal(
                                        '{{ $item['id'] }}',
                                        '{{ addslashes($item['title'] ?? '') }}',
                                        '{{ addslashes($item['description'] ?? '') }}',
                                        '{{ $item['status'] ?? 'draft' }}',
                                        '{{ addslashes($rawThumb) }}')"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Edit
                            </button>
                            <button type="button"
                                    onclick="confirmHapusJurnal('{{ $item['id'] }}','{{ addslashes($item['title'] ?? 'jurnal ini') }}')"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Hapus
                            </button>
                            <form id="form-hapus-{{ $item['id'] }}"
                                  action="{{ route('ejurnal.destroy', $item['id']) }}"
                                  method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="p-4 text-center text-gray-400">Belum ada e-jurnal.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
            <span id="infoEjurnal" class="text-sm text-gray-500"></span>
            <div class="flex gap-2">
                <button id="prevEjurnal" onclick="changePage('ejurnal', -1)"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Prev
                </button>
                <button id="nextEjurnal" onclick="changePage('ejurnal', 1)"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Next
                </button>
            </div>
        </div>
    </div>

</div>
</div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEditEjurnal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white rounded-xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
    <h3 class="text-xl font-bold text-[#4988C4] mb-4">
        <i class="fas fa-edit mr-2"></i>Edit E-Jurnal
    </h3>
    <form id="formEditEjurnal" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <input name="title" id="editEjurnalTitle"
               class="w-full mb-3 px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4988C4]/30"
               placeholder="Judul" required>
        <textarea name="description" id="editEjurnalDesc" rows="3"
                  class="w-full mb-3 px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4988C4]/30"
                  placeholder="Deskripsi"></textarea>
        <select name="status" id="editEjurnalStatus" class="w-full mb-3 px-4 py-2 border rounded-xl">
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>

        {{-- Area gambar — satu img, ganti src saat pilih baru --}}
        <div class="mb-4">
            <div id="thumbWrapperEdit"
                 class="relative rounded-xl overflow-hidden border-2 border-gray-200 shadow mb-3"
                 style="display:none">
                <img id="singleThumbEdit"
                     src=""
                     class="w-full h-40 object-cover block"
                     alt="Thumbnail">
                <span id="badgeGambarEdit"
                      class="absolute top-2 left-2 text-xs font-bold px-2 py-0.5 rounded-full
                             bg-gray-700/70 text-white">
                    Gambar saat ini
                </span>
                <button type="button" id="btnBatalGambarEdit" onclick="batalGambarEdit()"
                        class="hidden absolute top-2 right-2 bg-white/90 hover:bg-white
                               text-gray-700 text-xs font-semibold px-3 py-1 rounded-full shadow
                               border border-gray-300 transition">
                    ✕ Batalkan
                </button>
            </div>

            <label for="inputGambarEdit"
                   class="flex items-center justify-center gap-2 px-4 py-2 cursor-pointer
                          border-2 border-dashed border-[#4988C4] rounded-xl
                          text-[#4988C4] text-sm font-medium hover:bg-[#4988C4]/10 transition">
                <i class="fas fa-cloud-upload-alt"></i>
                <span id="namaFileEdit">Pilih Gambar (opsional)</span>
            </label>
            <input type="file" id="inputGambarEdit" name="thumbnail" accept="image/*"
                   class="hidden" onchange="previewGambarEdit(event)">
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModalEjurnal()"
                    class="px-4 py-2 border rounded-xl text-gray-600 hover:bg-gray-50">Batal</button>
            <button type="submit"
                    class="px-4 py-2 bg-[#4988C4] text-white rounded-xl hover:bg-[#3a6ea0]">Simpan</button>
        </div>
    </form>
</div>
</div>

@include('component.popuphapus')

<script>
// ============================================================
//  Modal Edit
// ============================================================
var _thumbSrcLamaEdit = '';

function openEditEjurnal(id, title, desc, status, thumbRaw) {
    document.getElementById('editEjurnalTitle').value  = title;
    document.getElementById('editEjurnalDesc').value   = desc;
    document.getElementById('editEjurnalStatus').value = status;
    document.getElementById('formEditEjurnal').action  = '/ejurnal/' + id;
    document.getElementById('inputGambarEdit').value   = '';

    var wrapper  = document.getElementById('thumbWrapperEdit');
    var img      = document.getElementById('singleThumbEdit');
    var badge    = document.getElementById('badgeGambarEdit');
    var btnBatal = document.getElementById('btnBatalGambarEdit');
    var namaFile = document.getElementById('namaFileEdit');

    _thumbSrcLamaEdit = '';

    if (thumbRaw && thumbRaw !== '') {
        var mediaBase = '{{ rtrim(env("MEDIA", ""), "/") }}';
        var fullUrl   = (thumbRaw.indexOf('http') === 0)
                        ? thumbRaw
                        : mediaBase + '/storage/' + thumbRaw.replace(/^\//, '');
        var proxyUrl  = '/proxy-image?url=' + encodeURIComponent(fullUrl);

        _thumbSrcLamaEdit      = proxyUrl;
        img.src                = proxyUrl;
        wrapper.style.display  = '';
        badge.textContent      = 'Gambar saat ini';
        badge.style.backgroundColor = '';
        btnBatal.classList.add('hidden');
        namaFile.textContent   = 'Ganti foto';
    } else {
        wrapper.style.display  = 'none';
        img.src                = '';
        namaFile.textContent   = 'Pilih Gambar (opsional)';
    }

    document.getElementById('modalEditEjurnal').classList.remove('hidden');
    document.getElementById('modalEditEjurnal').classList.add('flex');
}

function closeModalEjurnal() {
    document.getElementById('modalEditEjurnal').classList.add('hidden');
    document.getElementById('modalEditEjurnal').classList.remove('flex');
}

document.getElementById('modalEditEjurnal').addEventListener('click', function(e) {
    if (e.target === this) closeModalEjurnal();
});

function previewGambarEdit(event) {
    var file = event.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var wrapper  = document.getElementById('thumbWrapperEdit');
        var img      = document.getElementById('singleThumbEdit');
        var badge    = document.getElementById('badgeGambarEdit');
        var btnBatal = document.getElementById('btnBatalGambarEdit');
        var namaFile = document.getElementById('namaFileEdit');

        wrapper.style.display       = '';
        img.src                     = e.target.result;
        badge.textContent           = '✓ Gambar baru';
        badge.style.backgroundColor = '#4988C4';
        btnBatal.classList.remove('hidden');
        namaFile.textContent        = file.name;
    };
    reader.readAsDataURL(file);
}

function batalGambarEdit() {
    var wrapper  = document.getElementById('thumbWrapperEdit');
    var img      = document.getElementById('singleThumbEdit');
    var badge    = document.getElementById('badgeGambarEdit');
    var btnBatal = document.getElementById('btnBatalGambarEdit');
    var namaFile = document.getElementById('namaFileEdit');
    var input    = document.getElementById('inputGambarEdit');

    input.value = '';

    if (_thumbSrcLamaEdit) {
        img.src                     = _thumbSrcLamaEdit;
        wrapper.style.display       = '';
        badge.textContent           = 'Gambar saat ini';
        badge.style.backgroundColor = '';
        namaFile.textContent        = 'Ganti foto';
        btnBatal.classList.add('hidden');
    } else {
        wrapper.style.display = 'none';
        img.src               = '';
        namaFile.textContent  = 'Pilih Gambar (opsional)';
        btnBatal.classList.add('hidden');
    }
}

// ============================================================
//  Preview form Tambah
// ============================================================
function previewGambarTambah(event) {
    var file = event.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('namaFileTambah').textContent = file.name;
        document.getElementById('previewTambah').src = e.target.result;
        document.getElementById('previewWrapTambah').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function confirmHapusJurnal(id, judul) {
    openPopupHapus(
        function () { document.getElementById('form-hapus-' + id).submit(); },
        'Apakah Anda yakin ingin menghapus jurnal "' + judul + '"? Tindakan ini tidak dapat dibatalkan.'
    );
}

// ============================================================
//  Pagination
// ============================================================
const PER_PAGE = 10;
const state = { ejurnal: { page: 1, rows: [] } };

function searchTable(type) {
    const keyword = document.getElementById('searchEjurnal').value.toLowerCase().trim();
    const allRows = Array.from(document.getElementById('bodyEjurnal').rows);
    const filtered = allRows.filter(row => {
        const matches = keyword === '' || row.innerText.toLowerCase().includes(keyword);
        row.style.display = matches ? '' : 'none';
        return matches;
    });
    state[type].rows = filtered;
    state[type].page = 1;
    renderPagination(type);
}

function initPagination(type) {
    state[type].rows = Array.from(document.getElementById('bodyEjurnal').rows);
    renderPagination(type);
}

function renderPagination(type) {
    const rows      = state[type].rows;
    const total     = rows.length;
    const totalPage = Math.max(1, Math.ceil(total / PER_PAGE));
    const page      = state[type].page;
    const start     = (page - 1) * PER_PAGE;
    const end       = start + PER_PAGE;
    rows.forEach((row, i) => { row.style.display = (i >= start && i < end) ? '' : 'none'; });
    document.getElementById('infoEjurnal').textContent = total > 0 ? `Hal ${page} / ${totalPage}` : '';
    document.getElementById('prevEjurnal').disabled = page <= 1;
    document.getElementById('nextEjurnal').disabled = page >= totalPage;
}

function changePage(type, dir) {
    const rows      = state[type].rows;
    const totalPage = Math.max(1, Math.ceil(rows.length / PER_PAGE));
    state[type].page = Math.min(Math.max(1, state[type].page + dir), totalPage);
    renderPagination(type);
}

document.addEventListener('DOMContentLoaded', function () { initPagination('ejurnal'); });
</script>
@endsection