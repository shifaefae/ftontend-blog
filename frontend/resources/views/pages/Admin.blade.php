@extends('layout.App')

@section('title', 'Admin - Portal Blog')

@section('content')

<div class="p-6 max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
        <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-users-cog text-[#4988C4]"></i>
            Kelola Admin
        </h1>

        <div class="flex items-center gap-4 flex-wrap">

            <!-- SEARCH -->
            <div class="relative w-72">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[#4988C4] text-lg">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Cari admin..."
                    onkeyup="filterAdmin()"
                    class="w-full pl-12 pr-5 py-3 rounded-full border-2 border-[#4988C4]
                           focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40">
            </div>

            <!-- TAMBAH -->
            <button
                class="bg-[#4988C4] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Admin
            </button>

        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#4988C4] text-white">
                    <tr>
                        <th class="py-4 px-4 text-left">No</th>
                        <th class="py-4 px-4 text-left">Foto</th>
                        <th class="py-4 px-4 text-left">Nama</th>
                        <th class="py-4 px-4 text-left">Email</th>
                        <th class="py-4 px-4 text-left">Password</th>
                        <th class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelAdmin"></tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="flex justify-between items-center mt-6">
            <span id="pageInfo" class="text-gray-500"></span>
            <div class="flex gap-3">
                <button onclick="prevPage()" class="px-4 py-2 border rounded-lg">Prev</button>
                <button onclick="nextPage()" class="px-4 py-2 border rounded-lg text-[#4988C4]">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
/* ================= DATA DUMMY (10 DATA) ================= */
const admins = [
    {id:1,nama:'Super Admin',email:'super@admin.com',foto:'https://i.pravatar.cc/150?img=1',initials:'SA'},
    {id:2,nama:'Admin Konten',email:'konten@admin.com',foto:'https://i.pravatar.cc/150?img=2',initials:'AK'},
    {id:3,nama:'Admin Jurnal',email:'jurnal@admin.com',foto:null,initials:'AJ'},
    {id:4,nama:'Admin User',email:'user@admin.com',foto:'https://i.pravatar.cc/150?img=4',initials:'AU'},
    {id:5,nama:'Admin Iklan',email:'iklan@admin.com',foto:null,initials:'AI'},
    {id:6,nama:'Editor',email:'editor@admin.com',foto:'https://i.pravatar.cc/150?img=6',initials:'ED'},
    {id:7,nama:'Moderator',email:'moderator@admin.com',foto:'https://i.pravatar.cc/150?img=7',initials:'MD'},
    {id:8,nama:'Admin SEO',email:'seo@admin.com',foto:null,initials:'SEO'},
    {id:9,nama:'Admin Media',email:'media@admin.com',foto:'https://i.pravatar.cc/150?img=9',initials:'AM'},
    {id:10,nama:'Admin Support',email:'support@admin.com',foto:null,initials:'AS'},
    {id:11,nama:'Admin Backup',email:'backup@admin.com',foto:'https://i.pravatar.cc/150?img=11',initials:'AB'},
];

/* ================= CONFIG ================= */
let filteredAdmins = [...admins];
let currentPage = 1;
const perPage = 10; // ✅ 10 DATA PER HALAMAN

/* ================= SEARCH ================= */
function filterAdmin(){
    const key = searchInput.value.toLowerCase();
    filteredAdmins = admins.filter(a =>
        a.nama.toLowerCase().includes(key) ||
        a.email.toLowerCase().includes(key)
    );
    currentPage = 1;
    renderTable();
}

/* ================= RENDER ================= */
function renderTable(){
    const tbody = document.getElementById('tabelAdmin');
    tbody.innerHTML = '';

    const start = (currentPage-1)*perPage;
    const data = filteredAdmins.slice(start,start+perPage);

    data.forEach((a,i)=>{
        tbody.innerHTML += `
        <tr class="border-b hover:bg-blue-50">
            <td class="px-4 py-4 font-semibold">${start+i+1}</td>

            <td class="px-4 py-4">
                ${a.foto ? `
                    <img src="${a.foto}" class="w-12 h-12 rounded-full object-cover">
                ` : `
                    <div class="w-12 h-12 rounded-full bg-[#4988C4]
                        flex items-center justify-center text-white font-bold">
                        ${a.initials}
                    </div>
                `}
            </td>

            <td class="px-4 py-4 font-semibold">${a.nama}</td>
            <td class="px-4 py-4 text-gray-600">${a.email}</td>

            <td class="px-4 py-4">
                <span class="bg-gray-200 px-3 py-1 rounded-full text-xs">••••••••</span>
            </td>

            <td class="px-4 py-4 text-center relative">
                <button onclick="toggleDropdown(${a.id},event)"
                    class="text-2xl text-gray-500 hover:text-[#4988C4]">⋮</button>

                <div id="dropdown-${a.id}"
                    class="hidden absolute right-0 mt-2 w-36 bg-white
                           border rounded-xl shadow-lg z-50">
                    <button class="w-full px-4 py-3 text-left hover:bg-blue-50 text-[#4988C4]">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </button>
                    <button class="w-full px-4 py-3 text-left hover:bg-red-50 text-red-600">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </div>
            </td>
        </tr>`;
    });

    updatePagination();
}

/* ================= PAGINATION ================= */
function updatePagination(){
    const total = Math.ceil(filteredAdmins.length/perPage)||1;
    pageInfo.innerText = `Hal ${currentPage} / ${total}`;
}
function prevPage(){ if(currentPage>1){currentPage--;renderTable();} }
function nextPage(){
    const total = Math.ceil(filteredAdmins.length/perPage);
    if(currentPage<total){currentPage++;renderTable();}
}

/* ================= DROPDOWN ================= */
function toggleDropdown(id,e){
    e.stopPropagation();
    document.querySelectorAll('[id^="dropdown-"]').forEach(d=>{
        if(d.id!==`dropdown-${id}`) d.classList.add('hidden');
    });
    document.getElementById(`dropdown-${id}`).classList.toggle('hidden');
}
window.onclick=()=>document.querySelectorAll('[id^="dropdown-"]').forEach(d=>d.classList.add('hidden'));

document.addEventListener('DOMContentLoaded',renderTable);
</script>

@endsection
