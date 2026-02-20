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

        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#4988C4]/10 text-[#4988C4]">
                <tr>
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-left">Deskripsi</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
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

        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#4988C4]/10 text-[#4988C4]">
                <tr>
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
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
</script>
@endsection