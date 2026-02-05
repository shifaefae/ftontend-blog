@extends('layout.App')

@section('title', 'Admin - Portal Blog')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css')}}">

<div class="p-6 max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-users-cog text-[#4988C4]"></i>
            Kelola Admin
        </h1>
        <button onclick="openTambahModal()"
            class="gradient-bg text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition">
            <i class="fas fa-plus-circle mr-2"></i>Tambah Admin
        </button>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="gradient-bg text-white">
                    <tr>
                        <th class="py-4 px-4 text-left">No</th>
                        <th class="py-4 px-4 text-left">Foto</th>
                        <th class="py-4 px-4 text-left">Nama Admin</th>
                        <th class="py-4 px-4 text-left">Email</th>
                        <th class="py-4 px-4 text-left">Password</th>
                        <th class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelAdmin"></tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="flex items-center justify-between mt-6 px-2">
            <span id="pageInfo" class="text-gray-500 font-medium"></span>
            <div class="flex gap-3">
                <button id="prevBtn" onclick="prevPage()"
                    class="px-6 py-2 rounded-xl border border-gray-300 text-gray-500">
                    Prev
                </button>
                <button id="nextBtn" onclick="nextPage()"
                    class="px-6 py-2 rounded-xl border border-[#4988C4]
                           text-[#4988C4] hover:bg-blue-50 transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
/* ================= DATA DUMMY ================= */
const admins = Array.from({ length: 15 }, (_, i) => ({
    id: i + 1,
    nama: `Admin ${i + 1}`,
    email: `admin${i + 1}@example.com`,
    initials: `A${i + 1}`
}));

/* ================= PAGINATION ================= */
let currentPage = 1;
const perPage = 10;

function renderTable() {
    const tbody = document.getElementById('tabelAdmin');
    tbody.innerHTML = '';

    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const data = admins.slice(start, end);

    data.forEach((admin, index) => {
        tbody.innerHTML += `
        <tr class="border-b hover:bg-[#4988C4]/10 transition">
            <td class="py-4 px-4 font-semibold">${start + index + 1}</td>
            <td class="py-4 px-4">
                <div class="w-12 h-12 bg-[#4988C4] rounded-full
                            flex items-center justify-center
                            text-white font-bold">
                    ${admin.initials}
                </div>
            </td>
            <td class="py-4 px-4 font-semibold text-gray-800">${admin.nama}</td>
            <td class="py-4 px-4 text-gray-600">${admin.email}</td>
            <td class="py-4 px-4">
                <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-mono">
                    ••••••••
                </span>
            </td>

            <!-- AKSI DROPDOWN -->
            <td class="py-4 px-4 text-center relative">
                <button onclick="toggleDropdown(${admin.id}, event)"
                    class="text-2xl font-bold text-gray-500 hover:text-[#4988C4]">
                    ⋮
                </button>

                <div id="dropdown-${admin.id}"
                    class="hidden absolute right-6 mt-2 w-36 bg-white
                           border rounded-xl shadow-lg z-50 overflow-hidden">
                    <button class="block w-full px-4 py-3 text-left
                                   hover:bg-blue-50 text-[#4988C4]">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </button>
                    <button class="block w-full px-4 py-3 text-left
                                   hover:bg-red-50 text-red-600">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </div>
            </td>
        </tr>
        `;
    });

    updatePagination();
}

function updatePagination() {
    const totalPage = Math.ceil(admins.length / perPage);
    document.getElementById('pageInfo').innerText =
        `Hal ${currentPage} / ${totalPage}`;

    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPage;
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
}

function nextPage() {
    const totalPage = Math.ceil(admins.length / perPage);
    if (currentPage < totalPage) {
        currentPage++;
        renderTable();
    }
}

/* ================= DROPDOWN ================= */
function toggleDropdown(id, event) {
    event.stopPropagation();

    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        if (el.id !== `dropdown-${id}`) el.classList.add('hidden');
    });

    document.getElementById(`dropdown-${id}`).classList.toggle('hidden');
}

window.addEventListener('click', () => {
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        el.classList.add('hidden');
    });
});

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', renderTable);
</script>
@endsection