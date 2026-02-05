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
                <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
                    <i class="fas fa-list mr-2"></i>Daftar Kategori
                </h2>

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

                <!-- PAGINATION KATEGORI -->
                <div class="flex justify-between items-center mt-4">
                    <span id="kategoriInfo" class="text-gray-500"></span>
                    <div class="flex gap-2">
                        <button onclick="prevKategori()"
                            class="px-4 py-2 border rounded-xl">Prev</button>
                        <button onclick="nextKategori()"
                            class="px-4 py-2 border rounded-xl text-[#4988C4]">Next</button>
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
                <h2 class="text-2xl font-bold text-[#4988C4] mb-4">
                    <i class="fas fa-bookmark mr-2"></i>Daftar Tag
                </h2>

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

                <!-- PAGINATION TAG -->
                <div class="flex justify-between items-center mt-4">
                    <span id="tagInfo" class="text-gray-500"></span>
                    <div class="flex gap-2">
                        <button onclick="prevTag()"
                            class="px-4 py-2 border rounded-xl">Prev</button>
                        <button onclick="nextTag()"
                            class="px-4 py-2 border rounded-xl text-[#4988C4]">Next</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
/* ========= DATA DUMMY ========= */
const kategoriData = Array.from({length:15},(_,i)=>({
    nama:`Kategori ${i+1}`,
    desc:`Deskripsi kategori ${i+1}`,
    jumlah:Math.floor(Math.random()*30)+1
}));

const tagData = Array.from({length:15},(_,i)=>({
    nama:`Tag ${i+1}`,
    jumlah:Math.floor(Math.random()*40)+1
}));

const perPage = 10;
let kategoriPage = 1;
let tagPage = 1;

/* ========= RENDER KATEGORI ========= */
function renderKategori(){
    const start=(kategoriPage-1)*perPage;
    const data=kategoriData.slice(start,start+perPage);
    const tbody=document.getElementById('tabelKategori');
    tbody.innerHTML='';
    data.forEach(k=>{
        tbody.innerHTML+=`
        <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-semibold"><i class="fas fa-folder text-[#4988C4] mr-2"></i>${k.nama}</td>
            <td class="p-4">${k.desc}</td>
            <td class="p-4 text-center"><span class="px-3 py-1 bg-[#4988C4] text-white rounded-full text-xs">${k.jumlah}</span></td>
            <td class="p-4 text-center">
                <i class="fas fa-ellipsis-v cursor-pointer"></i>
            </td>
        </tr>`;
    });
    document.getElementById('kategoriInfo').innerText=
        `Hal ${kategoriPage} / ${Math.ceil(kategoriData.length/perPage)}`;
}

function prevKategori(){ if(kategoriPage>1){kategoriPage--;renderKategori();}}
function nextKategori(){ if(kategoriPage<Math.ceil(kategoriData.length/perPage)){kategoriPage++;renderKategori();}}

/* ========= RENDER TAG ========= */
function renderTag(){
    const start=(tagPage-1)*perPage;
    const data=tagData.slice(start,start+perPage);
    const tbody=document.getElementById('tabelTag');
    tbody.innerHTML='';
    data.forEach(t=>{
        tbody.innerHTML+=`
        <tr class="border-b hover:bg-gray-50">
            <td class="p-4 font-semibold"><i class="fas fa-tag text-[#4988C4] mr-2"></i>${t.nama}</td>
            <td class="p-4 text-center"><span class="px-3 py-1 bg-[#4988C4] text-white rounded-full text-xs">${t.jumlah}</span></td>
            <td class="p-4 text-center">
                <i class="fas fa-ellipsis-v cursor-pointer"></i>
            </td>
        </tr>`;
    });
    document.getElementById('tagInfo').innerText=
        `Hal ${tagPage} / ${Math.ceil(tagData.length/perPage)}`;
}

function prevTag(){ if(tagPage>1){tagPage--;renderTag();}}
function nextTag(){ if(tagPage<Math.ceil(tagData.length/perPage)){tagPage++;renderTag();}}

document.addEventListener('DOMContentLoaded',()=>{
    renderKategori();
    renderTag();
});
</script>
@endsection
