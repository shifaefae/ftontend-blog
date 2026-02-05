@extends('layout.App')

@section('title', 'Iklan - Portal Blog')

@section('content')
<div class="min-h-screen bg-white p-6">

    <!-- HEADER -->
    <div class="mb-8 animate-slide">
        <h1 class="flex items-center gap-4 text-4xl font-bold text-black drop-shadow-lg">
            <span class="p-4 bg-white rounded-xl shadow-xl">
                <i class="fas fa-ad text-[#4988C4] text-3xl"></i>
            </span>
            Kelola Iklan
        </h1>
    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FORM -->
        <div class="relative bg-white rounded-2xl shadow-xl p-8 animate-slide">
            <div class="absolute top-0 left-0 w-full h-1 bg-[#4988C4] rounded-t-2xl"></div>

            <h2 class="text-2xl font-bold text-[#4988C4] mb-6">Tambah Iklan</h2>

            <form class="flex flex-col gap-6">
                <input id="inputJudul" type="text" placeholder="Judul"
                    class="w-full rounded-xl border-2 border-[#4988C4] px-4 py-3">

                <select id="inputTipe"
                    class="w-full rounded-xl border-2 border-[#4988C4] px-4 py-3">
                    <option value="">Pilih Tipe</option>
                    <option>1:1 Slide</option>
                    <option>3:1 Kanan</option>
                    <option>3:1 Kiri</option>
                    <option>3:1 Tengah</option>
                    <option>1:3 Atas</option>
                    <option>1:3 Tengah</option>
                </select>

                <input id="inputLink" type="text" placeholder="Link URL"
                    class="w-full rounded-xl border-2 border-[#4988C4] px-4 py-3">

                <input id="inputGambar" type="file" accept="image/*" class="hidden"
                    onchange="previewGambar(event)">
                <label for="inputGambar"
                    class="cursor-pointer text-center border-2 border-[#4988C4] rounded-xl py-3 text-[#4988C4]">
                    Pilih Gambar
                </label>

                <div id="previewContainer" class="hidden">
                    <img id="previewImage" class="w-full h-40 object-cover rounded-xl">
                </div>

                <button type="button" onclick="tambahIklan()"
                    class="bg-[#4988C4] text-white font-bold py-4 rounded-xl">
                    Upload Iklan
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl p-8 animate-slide">
            <div class="absolute top-0 left-0 w-full h-1 bg-[#4988C4] rounded-t-2xl"></div>

            <h2 class="text-2xl font-bold text-[#4988C4] mb-6">Daftar Iklan</h2>

            <div class="overflow-auto border-2 border-[#4988C4] rounded-xl">
                <table class="w-full">
                    <thead class="bg-[#4988C4] text-white">
                        <tr>
                            <th class="p-4">No</th>
                            <th class="p-4">Judul</th>
                            <th class="p-4">Tipe</th>
                            <th class="p-4">Link</th>
                            <th class="p-4">Gambar</th>
                            <th class="p-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelIklan"></tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="flex items-center justify-between mt-6 px-2">
                <span id="pageInfo" class="text-gray-500 font-medium"></span>

                <div class="flex gap-3">
                    <button id="prevBtn" onclick="prevPage()"
                        class="px-5 py-2 rounded-xl border border-gray-300">
                        Prev
                    </button>
                    <button id="nextBtn" onclick="nextPage()"
                        class="px-5 py-2 rounded-xl border border-[#4988C4] text-[#4988C4] hover:bg-blue-50">
                        Next
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
/* ================= DATA ================= */
let dataIklan = Array.from({ length: 20 }, (_, i) => ({
    judul: 'Iklan ' + (i + 1),
    tipe: '1:1 Slide',
    link: 'https://example.com',
    gambar: 'https://via.placeholder.com/150'
}));

/* ================= PAGINATION CONFIG ================= */
let currentPage = 1;
const perPage = 10;

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', renderTable);

/* ================= PREVIEW ================= */
function previewGambar(e) {
    previewImage.src = URL.createObjectURL(e.target.files[0]);
    previewContainer.classList.remove('hidden');
}

/* ================= ADD DATA ================= */
function tambahIklan() {
    dataIklan.push({
        judul: inputJudul.value,
        tipe: inputTipe.value,
        link: inputLink.value,
        gambar: URL.createObjectURL(inputGambar.files[0])
    });

    currentPage = Math.ceil(dataIklan.length / perPage);
    renderTable();
}

/* ================= RENDER TABLE ================= */
function renderTable() {
    tabelIklan.innerHTML = '';

    const totalPage = Math.ceil(dataIklan.length / perPage);
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;

    dataIklan.slice(start, end).forEach((d, i) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-blue-50';
        row.innerHTML = `
            <td class="p-4 text-center">${start + i + 1}</td>
            <td class="p-4 font-semibold text-[#4988C4]">${d.judul}</td>
            <td class="p-4 text-center font-bold text-[#4988C4]">${d.tipe}</td>
            <td class="p-4 text-center text-[#4988C4]">
                <a href="${d.link}" target="_blank" class="underline">${d.link}</a>
            </td>
            <td class="p-4 text-center">
                <img src="${d.gambar}" class="w-20 h-20 object-cover rounded-lg mx-auto">
            </td>
            <td class="p-4 text-center relative">
                <button onclick="toggleMenu(this)" class="text-2xl text-[#4988C4]">⋮</button>
                <div class="hidden absolute right-4 top-10 bg-white border border-[#4988C4] rounded-xl shadow-xl w-32">
                    <button class="block w-full px-4 py-2 hover:bg-blue-50">Edit</button>
                    <button onclick="hapusData(${start + i})"
                        class="block w-full px-4 py-2 hover:bg-red-50 text-red-600">
                        Hapus
                    </button>
                </div>
            </td>
        `;
        tabelIklan.appendChild(row);
    });

    pageInfo.textContent = `Hal ${currentPage} / ${totalPage}`;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPage;
}

/* ================= PAGINATION ================= */
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
}

function nextPage() {
    const totalPage = Math.ceil(dataIklan.length / perPage);
    if (currentPage < totalPage) {
        currentPage++;
        renderTable();
    }
}

/* ================= DELETE ================= */
function hapusData(index) {
    dataIklan.splice(index, 1);
    const totalPage = Math.ceil(dataIklan.length / perPage);
    if (currentPage > totalPage) currentPage = totalPage || 1;
    renderTable();
}

/* ================= MENU ================= */
function toggleMenu(btn) {
    btn.nextElementSibling.classList.toggle('hidden');
}
</script>
@endsection
