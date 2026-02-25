@extends('layout.App')

@section('title', 'Kategori - Portal Blog')

@section('content')
<div class="min-h-screen bg-[#fbfbfc] p-6">
<div class="max-w-7xl mx-auto">

<div class="mb-8">
    <h1 class="flex items-center gap-4 text-4xl font-bold text-black">
        <div class="p-4 rounded-2xl bg-[#4988C4]">
            <i class="fas fa-layer-group text-white text-3xl"></i>
        </div>
        Kelola Kategori & Tag
    </h1>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
@endif

{{-- ===== KATEGORI ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- FORM TAMBAH KATEGORI --}}
    <div class="bg-white rounded-2xl p-6 shadow-xl">
        <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
            <i class="fas fa-plus-circle mr-2"></i>Tambah Kategori
        </h2>

        {{-- FIX: Kirim ke controller, bukan JS dummy --}}
        <form action="{{ route('kategori.kategori.store') }}" method="POST">
            @csrf
            {{-- BE field: name, description --}}
            <input name="name" type="text" placeholder="Nama kategori"
                   class="w-full mb-3 px-4 py-3 border rounded-xl" required>
            <textarea name="description" rows="3" placeholder="Deskripsi (optional)"
                      class="w-full mb-3 px-4 py-3 border rounded-xl"></textarea>
            <button type="submit"
                    class="w-full bg-[#4988C4] text-white py-3 rounded-xl font-semibold">
                Simpan Kategori
            </button>
        </form>
    </div>

    {{-- TABEL KATEGORI --}}
    <div class="bg-white rounded-2xl p-6 shadow-xl">
        <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
            <i class="fas fa-list mr-2"></i>Daftar Kategori
        </h2>

        {{-- SEARCH KATEGORI --}}
        <div class="relative mb-4">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <i class="fas fa-search text-sm"></i>
            </span>
            <input type="text" id="searchKategori" oninput="searchTable('kategori')"
                   placeholder="Cari kategori..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition">
        </div>

        <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabelKategori">
            <thead class="bg-[#4988C4]/10 text-[#4988C4]">
                <tr>
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-left">Deskripsi</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="bodyKategori">
                @forelse($kategoris as $kat)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-semibold">{{ $kat['name'] }}</td>
                    <td class="p-4 text-gray-500 text-xs">{{ $kat['description'] ?? '-' }}</td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            {{-- Tombol Edit — buka modal --}}
                            <button onclick="openEditKategori('{{ $kat['id'] }}','{{ addslashes($kat['name']) }}','{{ addslashes($kat['description'] ?? '') }}')"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Edit
                            </button>
                            {{-- Hapus --}}
                            <form action="{{ route('kategori.kategori.destroy', $kat['id']) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus kategori {{ $kat['name'] }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="p-4 text-center text-gray-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        {{-- PAGINATION KATEGORI --}}
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
            <span id="infoKategori" class="text-sm text-gray-500"></span>
            <div class="flex gap-2">
                <button id="prevKategori"
                        onclick="changePage('kategori', -1)"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Prev
                </button>
                <button id="nextKategori"
                        onclick="changePage('kategori', 1)"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== TAG ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- FORM TAMBAH TAG --}}
    <div class="bg-white rounded-2xl p-6 shadow-xl">
        <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
            <i class="fas fa-tags mr-2"></i>Tambah Tag
        </h2>
        <form action="{{ route('kategori.tag.store') }}" method="POST">
            @csrf
            {{-- BE field: name --}}
            <input name="name" type="text" placeholder="Nama tag"
                   class="w-full mb-3 px-4 py-3 border rounded-xl" required>
            <button type="submit"
                    class="w-full bg-[#4988C4] text-white py-3 rounded-xl font-semibold">
                Simpan Tag
            </button>
        </form>
    </div>

    {{-- TABEL TAG --}}
    <div class="bg-white rounded-2xl p-6 shadow-xl">
        <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
            <i class="fas fa-bookmark mr-2"></i>Daftar Tag
        </h2>

        {{-- SEARCH TAG --}}
        <div class="relative mb-4">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <i class="fas fa-search text-sm"></i>
            </span>
            <input type="text" id="searchTag" oninput="searchTable('tag')"
                   placeholder="Cari tag..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition">
        </div>

        <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabelTag">
            <thead class="bg-[#4988C4]/10 text-[#4988C4]">
                <tr>
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="bodyTag">
                @forelse($tags as $tag)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-semibold">{{ $tag['name'] }}</td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openEditTag('{{ $tag['id'] }}','{{ addslashes($tag['name']) }}')"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Edit
                            </button>
                            <form action="{{ route('kategori.tag.destroy', $tag['id']) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus tag {{ $tag['name'] }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="2" class="p-4 text-center text-gray-400">Belum ada tag.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        {{-- PAGINATION TAG --}}
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
            <span id="infoTag" class="text-sm text-gray-500"></span>
            <div class="flex gap-2">
                <button id="prevTag"
                        onclick="changePage('tag', -1)"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Prev
                </button>
                <button id="nextTag"
                        onclick="changePage('tag', 1)"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

</div>
</div>

{{-- MODAL EDIT KATEGORI --}}
<div id="modalEditKategori" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white rounded-xl p-6 w-full max-w-md">
    <h3 class="text-xl font-bold text-[#4988C4] mb-4">Edit Kategori</h3>
    <form id="formEditKategori" method="POST">
        @csrf @method('PUT')
        <input name="name" id="editKategoriName"
               class="w-full mb-3 px-4 py-2 border rounded-xl" placeholder="Nama" required>
        <textarea name="description" id="editKategoriDesc"
                  class="w-full mb-3 px-4 py-2 border rounded-xl" rows="3" placeholder="Deskripsi"></textarea>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModalKategori()"
                    class="px-4 py-2 border rounded-xl">Batal</button>
            <button type="submit"
                    class="px-4 py-2 bg-[#4988C4] text-white rounded-xl">Simpan</button>
        </div>
    </form>
</div>
</div>

{{-- MODAL EDIT TAG --}}
<div id="modalEditTag" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white rounded-xl p-6 w-full max-w-md">
    <h3 class="text-xl font-bold text-[#4988C4] mb-4">Edit Tag</h3>
    <form id="formEditTag" method="POST">
        @csrf @method('PUT')
        <input name="name" id="editTagName"
               class="w-full mb-3 px-4 py-2 border rounded-xl" placeholder="Nama tag" required>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModalTag()"
                    class="px-4 py-2 border rounded-xl">Batal</button>
            <button type="submit"
                    class="px-4 py-2 bg-[#4988C4] text-white rounded-xl">Simpan</button>
        </div>
    </form>
</div>
</div>

<script>
function openEditKategori(id, name, desc) {
    document.getElementById('editKategoriName').value = name;
    document.getElementById('editKategoriDesc').value = desc;
    document.getElementById('formEditKategori').action = `/kategori/kategori/${id}`;
    document.getElementById('modalEditKategori').classList.remove('hidden');
    document.getElementById('modalEditKategori').classList.add('flex');
}
function closeModalKategori() {
    document.getElementById('modalEditKategori').classList.add('hidden');
    document.getElementById('modalEditKategori').classList.remove('flex');
}

function openEditTag(id, name) {
    document.getElementById('editTagName').value = name;
    document.getElementById('formEditTag').action = `/kategori/tag/${id}`;
    document.getElementById('modalEditTag').classList.remove('hidden');
    document.getElementById('modalEditTag').classList.add('flex');
}
function closeModalTag() {
    document.getElementById('modalEditTag').classList.add('hidden');
    document.getElementById('modalEditTag').classList.remove('flex');
}

// ===== PAGINATION =====
const PER_PAGE = 10;
const state = {
    kategori: { page: 1, rows: [] },
    tag:      { page: 1, rows: [] },
};

function searchTable(type) {
    const inputId  = type === 'kategori' ? 'searchKategori' : 'searchTag';
    const bodyId   = type === 'kategori' ? 'bodyKategori'   : 'bodyTag';
    const keyword  = document.getElementById(inputId).value.toLowerCase().trim();
    const tbody    = document.getElementById(bodyId);
    const allRows  = Array.from(tbody.rows);

    // Filter: tampilkan baris yang cocok, sembunyikan yang tidak
    const filtered = allRows.filter(row => {
        const text    = row.innerText.toLowerCase();
        const matches = keyword === '' || text.includes(keyword);
        row.style.display = matches ? '' : 'none';
        return matches;
    });

    // Rebuild state dengan hanya baris yang cocok, reset ke halaman 1
    state[type].rows = filtered;
    state[type].page = 1;
    renderPagination(type);
}

function initPagination(type) {
    const bodyId = type === 'kategori' ? 'bodyKategori' : 'bodyTag';
    const tbody  = document.getElementById(bodyId);
    // Gunakan .rows agar dapat semua <tr> langsung milik tbody ini
    state[type].rows = Array.from(tbody.rows);
    renderPagination(type);
}

function renderPagination(type) {
    const infoId = type === 'kategori' ? 'infoKategori' : 'infoTag';
    const prevId = type === 'kategori' ? 'prevKategori' : 'prevTag';
    const nextId = type === 'kategori' ? 'nextKategori' : 'nextTag';

    const rows      = state[type].rows;
    const total     = rows.length;
    const totalPage = Math.max(1, Math.ceil(total / PER_PAGE));
    const page      = state[type].page;
    const start     = (page - 1) * PER_PAGE;
    const end       = start + PER_PAGE;

    rows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    document.getElementById(infoId).textContent =
        total > 0 ? `Hal ${page} / ${totalPage}` : '';

    document.getElementById(prevId).disabled = page <= 1;
    document.getElementById(nextId).disabled = page >= totalPage;
}

function changePage(type, dir) {
    const rows      = state[type].rows;
    const totalPage = Math.max(1, Math.ceil(rows.length / PER_PAGE));
    state[type].page = Math.min(Math.max(1, state[type].page + dir), totalPage);
    renderPagination(type);
}

document.addEventListener('DOMContentLoaded', function () {
    initPagination('kategori');
    initPagination('tag');
});
</script>
@endsection