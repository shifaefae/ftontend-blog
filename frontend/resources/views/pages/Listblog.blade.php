@extends('layout.App')

@section('title', 'List Blog - Portal Blog')

@section('content')
<div class="p-8 font-sans bg-white min-h-screen">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
        <h1 class="text-3xl font-bold text-gray-800">List Blog</h1>

        <a href="{{ route('blog.tambah') }}"
           class="px-8 py-3 bg-[#4988C4] text-white font-semibold rounded-xl
                  shadow-lg shadow-blue-500/40
                  hover:-translate-y-0.5 hover:shadow-xl transition">
            Tambah Blog
        </a>
    </div>

    <!-- TABLE -->
    <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
        <div class="absolute top-0 left-0 right-0 h-1 bg-[#4988C4] rounded-t-2xl"></div>

        <table class="w-full border-collapse mt-4">
            <thead>
                <tr class="bg-[#4988C4]">
                    <th class="px-4 py-4 text-left text-sm font-semibold text-white">No</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-white">Foto</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-white">Judul</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-white">Penulis</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-white">Kategori</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-white">Status</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-white">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>

        <!-- PAGINATION -->
        <div class="flex items-center justify-between mt-6 px-2">
            <span id="pageInfo" class="text-gray-500 font-medium"></span>

            <div class="flex gap-3">
                <button id="prevBtn"
                        onclick="prevPage()"
                        class="px-6 py-2 rounded-xl border border-gray-300 text-gray-500">
                    Prev
                </button>
                <button id="nextBtn"
                        onclick="nextPage()"
                        class="px-6 py-2 rounded-xl border border-[#4988C4]
                               text-[#4988C4] hover:bg-blue-50 transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ================= DUMMY DATA (15) ================= */
const blogs = Array.from({ length: 15 }, (_, i) => ({
    id: i + 1,
    foto: 'https://via.placeholder.com/80',
    judul: 'Judul Blog ' + (i + 1),
    penulis: 'Admin',
    kategori: 'Edukasi',
    status: i % 2 === 0 ? 'publish' : 'draft'
}));

/* ================= PAGINATION ================= */
let currentPage = 1;
const perPage = 10;

function renderTable() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const paginated = blogs.slice(start, end);

    paginated.forEach((blog, index) => {
        tbody.innerHTML += `
        <tr class="border-b hover:bg-gray-50 transition">
            <td class="px-4 py-4 text-sm text-gray-700">${start + index + 1}</td>

            <td class="px-4 py-4">
                <img src="${blog.foto}"
                     class="w-14 h-14 object-cover rounded-lg shadow">
            </td>

            <td class="px-4 py-4 text-sm font-semibold text-gray-800">
                ${blog.judul}
            </td>

            <td class="px-4 py-4 text-sm text-gray-700">
                ${blog.penulis}
            </td>

            <td class="px-4 py-4">
                <span class="px-3 py-1 rounded-full bg-[#4988C4]
                             text-white text-xs font-semibold">
                    ${blog.kategori}
                </span>
            </td>

            <td class="px-4 py-4 text-sm text-gray-700 capitalize">
                ${blog.status}
            </td>

            <td class="px-4 py-4 relative">
                <button onclick="toggleDropdown(${blog.id}, event)"
                        class="text-xl font-bold text-gray-500 hover:text-[#4988C4]">
                    ⋮
                </button>

                <div id="dropdown-${blog.id}"
                     class="hidden absolute right-0 mt-2 w-36 bg-white
                            border border-gray-200 rounded-lg
                            shadow-xl overflow-hidden z-50">
                    <a href="#"
                       class="block px-4 py-3 text-sm text-gray-700
                              hover:bg-blue-50 hover:text-[#4988C4]">
                        Edit
                    </a>
                    <button class="w-full text-left px-4 py-3 text-sm
                                   text-red-600 hover:bg-red-50">
                        Hapus
                    </button>
                </div>
            </td>
        </tr>
        `;
    });

    updatePagination();
}

function updatePagination() {
    const totalPage = Math.ceil(blogs.length / perPage);
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
    const totalPage = Math.ceil(blogs.length / perPage);
    if (currentPage < totalPage) {
        currentPage++;
        renderTable();
    }
}

/* ================= DROPDOWN ================= */
function toggleDropdown(id, event) {
    event.stopPropagation();
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        if (el.id !== 'dropdown-' + id) el.classList.add('hidden');
    });
    document.getElementById('dropdown-' + id).classList.toggle('hidden');
}

window.addEventListener('click', () => {
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        el.classList.add('hidden');
    });
});

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', renderTable);
</script>
@endpush
