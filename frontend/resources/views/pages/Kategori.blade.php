@extends('layout.App')

@section('title', 'Kategori - Portal Blog')

@section('content')
<div class="min-h-screen bg-[#fbfbfc] p-6">
<div class="max-w-7xl mx-auto">

<!-- HEADER -->
<div class="mb-8">
    <h1 class="flex items-center gap-4 text-4xl font-bold text-black">
        <div class="p-4 rounded-2xl bg-[#4988C4]">
            <i class="fas fa-layer-group text-white text-3xl"></i>
        </div>
        Kelola Kategori & Tag
    </h1>
</div>

<!-- ================= KATEGORI ================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

<!-- Tambah Kategori -->
<div class="bg-white rounded-2xl p-6 shadow-xl">
    <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
        <i class="fas fa-plus-circle mr-2"></i>Tambah Kategori
    </h2>

    <input id="inputKategori" type="text" placeholder="Nama kategori"
        class="w-full mb-3 px-4 py-3 border rounded-xl">

    <textarea id="newCategoryDesc" rows="3" placeholder="Deskripsi"
        class="w-full mb-3 px-4 py-3 border rounded-xl"></textarea>

    <button onclick="tambahKategori()"
        class="w-full bg-[#4988C4] text-white py-3 rounded-xl font-semibold">
        Simpan Kategori
    </button>
</div>

<!-- Tabel Kategori -->
<div class="bg-white rounded-2xl p-6 shadow-xl">
<div class="flex items-center justify-between mb-4">
    <h2 class="flex items-center text-2xl font-bold text-[#4988C4]">
        <i class="fas fa-list mr-2"></i>Daftar Kategori
    </h2>

    <div class="relative">
        <input id="searchKategori" onkeyup="filterKategori()"
            placeholder="Cari kategori..."
            class="pl-10 pr-4 py-2 border rounded-xl">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
    </div>
</div>

<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-[#4988C4]/10 text-[#4988C4]">
<tr>
    <th class="p-4 text-left">Nama</th>
    <th class="p-4 text-left">Deskripsi</th>
    <th class="p-4 text-center">Jumlah</th>
    <th class="p-4 text-center">Aksi</th>
</tr>
</thead>
<tbody id="tabelKategori"></tbody>
</table>
</div>

<div class="flex justify-between items-center mt-4">
    <span id="kategoriInfo" class="text-gray-500"></span>
    <div class="flex gap-2">
        <button onclick="prevKategori()" class="px-4 py-2 border rounded-xl">Prev</button>
        <button onclick="nextKategori()" class="px-4 py-2 border rounded-xl text-[#4988C4]">Next</button>
    </div>
</div>
</div>
</div>

<!-- ================= TAG ================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

<!-- Tambah Tag -->
<div class="bg-white rounded-2xl p-6 shadow-xl">
    <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
        <i class="fas fa-tags mr-2"></i>Tambah Tag
    </h2>

    <input id="inputTag" type="text" placeholder="Nama tag"
        class="w-full mb-3 px-4 py-3 border rounded-xl">

    <button onclick="tambahTag()"
        class="w-full bg-[#4988C4] text-white py-3 rounded-xl font-semibold">
        Simpan Tag
    </button>
</div>

<!-- Tabel Tag -->
<div class="bg-white rounded-2xl p-6 shadow-xl">
<div class="flex items-center justify-between mb-4">
    <h2 class="flex items-center text-2xl font-bold text-[#4988C4]">
        <i class="fas fa-bookmark mr-2"></i>Daftar Tag
    </h2>

    <div class="relative">
        <input id="searchTag" onkeyup="filterTag()"
            placeholder="Cari tag..."
            class="pl-10 pr-4 py-2 border rounded-xl">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
    </div>
</div>

<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-[#4988C4]/10 text-[#4988C4]">
<tr>
    <th class="p-4 text-left">Nama</th>
    <th class="p-4 text-center">Jumlah</th>
    <th class="p-4 text-center">Aksi</th>
</tr>
</thead>
<tbody id="tabelTag"></tbody>
</table>
</div>

<div class="flex justify-between items-center mt-4">
    <span id="tagInfo" class="text-gray-500"></span>
    <div class="flex gap-2">
        <button onclick="prevTag()" class="px-4 py-2 border rounded-xl">Prev</button>
        <button onclick="nextTag()" class="px-4 py-2 border rounded-xl text-[#4988C4]">Next</button>
    </div>
</div>
</div>
</div>

</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div id="modalEdit" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white rounded-xl p-6 w-full max-w-md">
    <h3 id="editTitle" class="text-xl font-bold mb-4"></h3>
    <input id="editInput" class="w-full mb-3 px-4 py-2 border rounded-xl">
    <textarea id="editDesc" class="w-full mb-3 px-4 py-2 border rounded-xl hidden"></textarea>

    <div class="flex justify-end gap-2">
        <button onclick="closeEdit()" class="px-4 py-2 border rounded-xl">Batal</button>
        <button onclick="saveEdit()" class="px-4 py-2 bg-[#4988C4] text-white rounded-xl">Simpan</button>
    </div>
</div>
</div>

<!-- ================= MODAL HAPUS ================= -->
<div id="modalHapus" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white rounded-xl p-6 w-full max-w-md text-center">
    <h3 class="text-xl font-bold mb-3 text-red-600">Hapus Data?</h3>
    <p class="text-gray-600 mb-6">Data yang dihapus tidak bisa dikembalikan.</p>
    <div class="flex justify-center gap-3">
        <button onclick="closeHapus()" class="px-4 py-2 border rounded-xl">Batal</button>
        <button onclick="confirmHapus()" class="px-4 py-2 bg-red-600 text-white rounded-xl">Hapus</button>
    </div>
</div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
const perPage = 10;
let kategoriPage=1, tagPage=1;
let kategoriKeyword='', tagKeyword='';
let editTarget={}, hapusTarget={};

const kategoriData = Array.from({length:15},(_,i)=>({
    nama:`Kategori ${i+1}`, desc:`Deskripsi kategori ${i+1}`,
    jumlah:Math.floor(Math.random()*30)+1
}));
const tagData = Array.from({length:15},(_,i)=>({
    nama:`Tag ${i+1}`, jumlah:Math.floor(Math.random()*40)+1
}));

function renderKategori(){
    const f = kategoriData.filter(k=>k.nama.toLowerCase().includes(kategoriKeyword));
    const d = f.slice((kategoriPage-1)*perPage, kategoriPage*perPage);
    tabelKategori.innerHTML='';
    d.forEach((k,i)=>tabelKategori.innerHTML+=`
<tr class="relative">
<td class="p-4 font-semibold">${k.nama}</td>
<td class="p-4">${k.desc}</td>
<td class="p-4 text-center"><span class="px-3 py-1 bg-[#4988C4] text-white rounded-full text-xs">${k.jumlah}</span></td>
<td class="p-4 text-center relative">
    <i class="fas fa-ellipsis-v cursor-pointer"
       onclick="toggleMenu(this)"></i>
    <div class="absolute right-4 mt-2 bg-white border rounded-xl shadow hidden z-10">
        <button onclick="openEdit('kategori',${(kategoriPage-1)*perPage+i})"
            class="block px-4 py-2 hover:bg-gray-100 w-full text-left">Edit</button>
        <button onclick="openHapus('kategori',${(kategoriPage-1)*perPage+i})"
            class="block px-4 py-2 hover:bg-red-100 text-red-600 w-full text-left">Hapus</button>
    </div>
</td>
</tr>`);
    kategoriInfo.innerText=`Hal ${kategoriPage} / ${Math.ceil(f.length/perPage)||1}`;
}

function renderTag(){
    const f = tagData.filter(t=>t.nama.toLowerCase().includes(tagKeyword));
    const d = f.slice((tagPage-1)*perPage, tagPage*perPage);
    tabelTag.innerHTML='';
    d.forEach((t,i)=>tabelTag.innerHTML+=`
<tr>
<td class="p-4 font-semibold">${t.nama}</td>
<td class="p-4 text-center"><span class="px-3 py-1 bg-[#4988C4] text-white rounded-full text-xs">${t.jumlah}</span></td>
<td class="p-4 text-center relative">
    <i class="fas fa-ellipsis-v cursor-pointer"
       onclick="toggleMenu(this)"></i>
    <div class="absolute right-4 mt-2 bg-white border rounded-xl shadow hidden z-10">
        <button onclick="openEdit('tag',${(tagPage-1)*perPage+i})"
            class="block px-4 py-2 hover:bg-gray-100 w-full text-left">Edit</button>
        <button onclick="openHapus('tag',${(tagPage-1)*perPage+i})"
            class="block px-4 py-2 hover:bg-red-100 text-red-600 w-full text-left">Hapus</button>
    </div>
</td>
</tr>`);
    tagInfo.innerText=`Hal ${tagPage} / ${Math.ceil(f.length/perPage)||1}`;
}

function toggleMenu(el){
    document.querySelectorAll('.absolute.bg-white').forEach(m=>m.classList.add('hidden'));
    el.nextElementSibling.classList.toggle('hidden');
}

function openEdit(type,index){
    editTarget={type,index};
    modalEdit.classList.remove('hidden'); modalEdit.classList.add('flex');
    if(type==='kategori'){
        editTitle.innerText='Edit Kategori';
        editInput.value=kategoriData[index].nama;
        editDesc.value=kategoriData[index].desc;
        editDesc.classList.remove('hidden');
    }else{
        editTitle.innerText='Edit Tag';
        editInput.value=tagData[index].nama;
        editDesc.classList.add('hidden');
    }
}

function saveEdit(){
    if(editTarget.type==='kategori'){
        kategoriData[editTarget.index].nama=editInput.value;
        kategoriData[editTarget.index].desc=editDesc.value;
        renderKategori();
    }else{
        tagData[editTarget.index].nama=editInput.value;
        renderTag();
    }
    closeEdit();
}

function closeEdit(){modalEdit.classList.add('hidden');modalEdit.classList.remove('flex');}

function openHapus(type,index){
    hapusTarget={type,index};
    modalHapus.classList.remove('hidden'); modalHapus.classList.add('flex');
}

function confirmHapus(){
    if(hapusTarget.type==='kategori'){
        kategoriData.splice(hapusTarget.index,1);
        renderKategori();
    }else{
        tagData.splice(hapusTarget.index,1);
        renderTag();
    }
    closeHapus();
}

function closeHapus(){modalHapus.classList.add('hidden');modalHapus.classList.remove('flex');}

function filterKategori(){kategoriKeyword=searchKategori.value.toLowerCase();kategoriPage=1;renderKategori();}
function filterTag(){tagKeyword=searchTag.value.toLowerCase();tagPage=1;renderTag();}
function prevKategori(){if(kategoriPage>1){kategoriPage--;renderKategori();}}
function nextKategori(){kategoriPage++;renderKategori();}
function prevTag(){if(tagPage>1){tagPage--;renderTag();}}
function nextTag(){tagPage++;renderTag();}
function tambahKategori(){
    if(inputKategori.value)
        kategoriData.unshift({nama:inputKategori.value,desc:newCategoryDesc.value,jumlah:0});
    inputKategori.value=''; newCategoryDesc.value='';
    renderKategori();
}
function tambahTag(){
    if(inputTag.value)
        tagData.unshift({nama:inputTag.value,jumlah:0});
    inputTag.value=''; renderTag();
}

document.addEventListener('DOMContentLoaded',()=>{renderKategori();renderTag();});
</script>
@endsection
