@extends('layout.App')

@section('title', 'Admin - Portal Blog')

@section('content')

<div class="p-6 max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-users-cog text-[#4988C4]"></i>
            Kelola Admin
        </h1>

        <div class="flex gap-3 items-center">

            <!-- SEARCH -->
            <div class="relative w-72">
                <span class="absolute inset-y-0 left-4 flex items-center text-[#4988C4]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z"/>
                    </svg>
                </span>

                <input type="text"
                       id="searchInput"
                       onkeyup="filterAdmin()"
                       placeholder="Cari admin..."
                       class="w-full pl-12 pr-4 py-2.5
                              border border-[#4988C4]/40
                              rounded-full text-sm
                              focus:outline-none
                              focus:border-[#4988C4]
                              focus:ring-2 focus:ring-[#4988C4]/30">
            </div>

            <button onclick="openTambah()"
                    class="bg-[#4988C4] hover:bg-[#3b73a5]
                           text-white px-5 py-2.5 rounded-full
                           text-sm font-semibold transition">
                + Tambah Admin
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#4988C4] text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Foto</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelAdmin"></tbody>
        </table>

        <!-- PAGINATION -->
        <div class="flex justify-between items-center mt-6 text-sm">
            <span id="pageInfo" class="text-gray-600">
                Hal 1 / 1
            </span>

            <div class="flex gap-2">
                <button onclick="prevPage()" id="btnPrev"
                        class="px-4 py-2 border rounded-lg
                               hover:bg-gray-100 disabled:opacity-50">
                    Prev
                </button>

                <button onclick="nextPage()" id="btnNext"
                        class="px-4 py-2 border rounded-lg
                               hover:bg-gray-100 disabled:opacity-50">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL TAMBAH & EDIT ================= -->
<div id="adminModal"
     class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-xl rounded-2xl p-8">
        <h2 id="modalTitle" class="text-xl font-bold mb-6">Tambah Admin</h2>

        <form id="adminForm" onsubmit="submitAdmin(event)" class="space-y-4">

            <!-- FOTO -->
            <div class="flex justify-center">
                <label class="cursor-pointer relative">
                    <img id="previewFoto"
                         src="https://via.placeholder.com/120"
                         class="w-24 h-24 rounded-full object-cover border">
                    <input type="file" id="fotoAdmin" class="hidden"
                           accept="image/*" onchange="previewImage(event)">
                </label>
            </div>

            <input id="namaAdmin" placeholder="Nama"
                   class="w-full border px-4 py-2.5 rounded-lg" required>

            <input id="emailAdmin" type="email" placeholder="Email"
                   class="w-full border px-4 py-2.5 rounded-lg" required>

            <input id="passwordAdmin" type="password" placeholder="Password"
                   class="w-full border px-4 py-2.5 rounded-lg" required>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-gray-600 hover:text-black">
                    Batal
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-[#4988C4] text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= POPUP HAPUS (SUDAH DIRAPIKAN) ================= -->
<div id="popupHapus"
     class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-red-600 text-2xl">🗑</span>
            <h3 class="text-xl font-bold text-red-600">
                Konfirmasi Hapus
            </h3>
        </div>

        <p id="popupHapusText" class="text-gray-600 mb-6">
            Apakah Anda yakin ingin menghapus data ini?
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closePopupHapus()"
                    class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">
                Batal
            </button>
            <button id="btnHapusConfirm"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700
                           text-white rounded-lg font-semibold">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
/* ================= DATA ================= */
let admins = Array.from({length:15},(_,i)=>({
    id:i+1,
    nama:`Admin ${i+1}`,
    email:`admin${i+1}@mail.com`,
    password:'secret',
    foto:`https://i.pravatar.cc/150?img=${i+5}`
}));

let filteredAdmins=[...admins];
let currentPage=1;
const perPage=10;

let editMode=false;
let editIndex=null;

/* ================= RENDER ================= */
function renderTable(){
    const start=(currentPage-1)*perPage;
    const data=filteredAdmins.slice(start,start+perPage);
    tabelAdmin.innerHTML='';

    data.forEach((a,i)=>{
        tabelAdmin.innerHTML+=`
        <tr class="border-b hover:bg-gray-50 transition">
            <td class="px-4 py-3">${start+i+1}</td>
            <td class="px-4 py-3">
                <img src="${a.foto}" class="w-9 h-9 rounded-full object-cover">
            </td>
            <td class="px-4 py-3 font-medium">${a.nama}</td>
            <td class="px-4 py-3 text-gray-600">${a.email}</td>
            <td class="px-4 py-3 text-center relative">
                <button onclick="toggleAksi(event,${a.id})"
                        class="text-xl px-2">⋮</button>

                <div id="aksi-${a.id}"
                     class="hidden absolute right-4 top-9 bg-white
                            border rounded-lg shadow-md w-32 z-20">
                    <button onclick="openEdit(${a.id})"
                            class="block w-full text-left px-4 py-2 hover:bg-blue-50">
                         Edit
                    </button>
                    <button onclick="hapusData(${a.id})"
                            class="block w-full text-left px-4 py-2
                                   text-red-600 hover:bg-red-50">
                        🗑 Hapus
                    </button>
                </div>
            </td>
        </tr>`;
    });

    updatePagination();
}

/* ================= DROPDOWN AKSI ================= */
function toggleAksi(e,id){
    e.stopPropagation();
    document.querySelectorAll('[id^="aksi-"]').forEach(el=>{
        if(el.id!==`aksi-${id}`) el.classList.add('hidden');
    });
    document.getElementById(`aksi-${id}`).classList.toggle('hidden');
}
document.addEventListener('click',()=>{
    document.querySelectorAll('[id^="aksi-"]').forEach(el=>el.classList.add('hidden'));
});

/* ================= SEARCH ================= */
function filterAdmin(){
    const key=searchInput.value.toLowerCase();
    filteredAdmins=admins.filter(a =>
        a.nama.toLowerCase().includes(key) ||
        a.email.toLowerCase().includes(key)
    );
    currentPage=1;
    renderTable();
}

/* ================= PAGINATION ================= */
function updatePagination(){
    const totalPage=Math.ceil(filteredAdmins.length/perPage)||1;
    pageInfo.innerText=`Hal ${currentPage} / ${totalPage}`;
    btnPrev.disabled=currentPage===1;
    btnNext.disabled=currentPage===totalPage;
}
function nextPage(){currentPage++;renderTable();}
function prevPage(){currentPage--;renderTable();}

/* ================= MODAL ================= */
function openTambah(){
    editMode=false;
    adminForm.reset();
    previewFoto.src='https://via.placeholder.com/120';
    modalTitle.innerText='Tambah Admin';
    adminModal.classList.remove('hidden');
}
function openEdit(id){
    const a=admins.find(x=>x.id===id);
    editIndex=admins.indexOf(a);
    editMode=true;
    modalTitle.innerText='Edit Admin';
    namaAdmin.value=a.nama;
    emailAdmin.value=a.email;
    passwordAdmin.value=a.password;
    previewFoto.src=a.foto;
    adminModal.classList.remove('hidden');
}
function closeModal(){adminModal.classList.add('hidden');}

/* ================= SIMPAN ================= */
function submitAdmin(e){
    e.preventDefault();
    if(editMode){
        Object.assign(admins[editIndex],{
            nama:namaAdmin.value,
            email:emailAdmin.value,
            password:passwordAdmin.value,
            foto:previewFoto.src
        });
    }else{
        admins.unshift({
            id:Date.now(),
            nama:namaAdmin.value,
            email:emailAdmin.value,
            password:passwordAdmin.value,
            foto:previewFoto.src
        });
    }
    filteredAdmins=[...admins];
    renderTable();
    closeModal();
}

/* ================= FOTO ================= */
function previewImage(e){
    const r=new FileReader();
    r.onload=()=>previewFoto.src=r.result;
    r.readAsDataURL(e.target.files[0]);
}

/* ================= HAPUS ================= */
function hapusData(id){
    popupHapusText.innerText='Apakah Anda yakin ingin menghapus data ini?';
    popupHapus.classList.remove('hidden');
    btnHapusConfirm.onclick=()=>{
        admins=admins.filter(a=>a.id!==id);
        filteredAdmins=[...admins];
        renderTable();
        closePopupHapus();
    }
}
function closePopupHapus(){popupHapus.classList.add('hidden');}

document.addEventListener('DOMContentLoaded',renderTable);
</script>

@endsection
