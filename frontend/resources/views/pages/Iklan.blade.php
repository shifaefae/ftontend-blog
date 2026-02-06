@extends('layout.App')

@section('title', 'Iklan - Portal Blog')

@section('content')
<div class="min-h-screen bg-white p-6">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="flex items-center gap-4 text-4xl font-bold text-black">
            <span class="p-4 bg-white rounded-xl shadow">
                <i class="fas fa-ad text-[#4988C4] text-3xl"></i>
            </span>
            Kelola Iklan
        </h1>
    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FORM -->
        <div class="relative bg-white rounded-2xl shadow p-8">
            <div class="absolute top-0 left-0 w-full h-1 bg-[#4988C4] rounded-t-2xl"></div>

            <h2 class="text-2xl font-bold text-[#4988C4] mb-6">Tambah Iklan</h2>

            <form class="flex flex-col gap-6">
                <input id="inputJudul" type="text" placeholder="Judul"
                    class="rounded-xl border-2 border-[#4988C4] px-4 py-3">

                <select id="inputTipe"
                    class="rounded-xl border-2 border-[#4988C4] px-4 py-3">
                    <option value="">Pilih Tipe</option>
                    <option>1:1 Slide</option>
                    <option>3:1 Kanan</option>
                    <option>3:1 Kiri</option>
                </select>

                <input id="inputLink" type="text" placeholder="Link URL"
                    class="rounded-xl border-2 border-[#4988C4] px-4 py-3">

                <input id="inputGambar" type="file" accept="image/*" class="hidden"
                    onchange="previewGambar(event)">
                <label for="inputGambar"
                    class="cursor-pointer text-center border-2 border-[#4988C4]
                           rounded-xl py-3 text-[#4988C4]">
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
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-8 relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-[#4988C4] rounded-t-2xl"></div>

            <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                <h2 class="text-2xl font-bold text-[#4988C4]">Daftar Iklan</h2>

                <div class="relative w-72">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[#4988C4] text-lg">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        id="searchIklan"
                        placeholder="Cari iklan..."
                        onkeyup="filterIklan()"
                        class="w-full pl-12 pr-5 py-3 rounded-full
                               border-2 border-[#4988C4]
                               focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40">
                </div>
            </div>

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
            <div class="flex items-center justify-between mt-6">
                <span id="pageInfo" class="text-gray-500"></span>

                <div class="flex gap-3">
                    <button onclick="prevPage()"
                        class="px-5 py-2 rounded-xl border">
                        Prev
                    </button>
                    <button onclick="nextPage()"
                        class="px-5 py-2 rounded-xl border text-[#4988C4]">
                        Next
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
/* ================= DATA DUMMY (25 DATA) ================= */
const dataIklan = [
    ...Array.from({ length: 25 }, (_, i) => ({
        judul: `Iklan Promo ${i + 1}`,
        tipe: i % 3 === 0 ? '1:1 Slide' : i % 3 === 1 ? '3:1 Kanan' : '3:1 Kiri',
        link: 'https://example.com/iklan-' + (i + 1),
        gambar: `https://picsum.photos/seed/iklan${i}/200/200`
    }))
];

let currentPage = 1;
const perPage = 10; // ✅ 10 DATA PER HALAMAN
let keyword = '';

document.addEventListener('DOMContentLoaded', renderTable);

/* ================= SEARCH ================= */
function filterIklan() {
    keyword = searchIklan.value.toLowerCase();
    currentPage = 1;
    renderTable();
}

/* ================= PREVIEW ================= */
function previewGambar(e) {
    previewImage.src = URL.createObjectURL(e.target.files[0]);
    previewContainer.classList.remove('hidden');
}

/* ================= ADD ================= */
function tambahIklan() {
    dataIklan.unshift({
        judul: inputJudul.value,
        tipe: inputTipe.value,
        link: inputLink.value,
        gambar: URL.createObjectURL(inputGambar.files[0])
    });
    renderTable();
}

/* ================= RENDER ================= */
function renderTable() {
    tabelIklan.innerHTML = '';

    const filtered = dataIklan.filter(d =>
        d.judul.toLowerCase().includes(keyword) ||
        d.tipe.toLowerCase().includes(keyword)
    );

    const totalPage = Math.ceil(filtered.length / perPage) || 1;
    const start = (currentPage - 1) * perPage;

    filtered.slice(start, start + perPage).forEach((d, i) => {
        tabelIklan.innerHTML += `
        <tr class="border-b hover:bg-blue-50">
            <td class="p-4 text-center">${start + i + 1}</td>
            <td class="p-4 font-semibold">${d.judul}</td>
            <td class="p-4 text-center text-[#4988C4] font-bold">${d.tipe}</td>
            <td class="p-4 text-center">
                <a href="${d.link}" target="_blank" class="underline text-[#4988C4]">
                    ${d.link}
                </a>
            </td>
            <td class="p-4 text-center">
                <img src="${d.gambar}" class="w-20 h-20 rounded object-cover mx-auto">
            </td>
            <td class="p-4 text-center text-xl">⋮</td>
        </tr>`;
    });

    pageInfo.textContent = `Hal ${currentPage} / ${totalPage}`;
}

/* ================= PAGINATION ================= */
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
}

function nextPage() {
    const totalPage = Math.ceil(
        dataIklan.filter(d =>
            d.judul.toLowerCase().includes(keyword) ||
            d.tipe.toLowerCase().includes(keyword)
        ).length / perPage
    );
    if (currentPage < totalPage) {
        currentPage++;
        renderTable();
    }
}
</script>
@endsection
