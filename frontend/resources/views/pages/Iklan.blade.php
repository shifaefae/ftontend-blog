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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FORM TAMBAH -->
        <div class="bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-bold text-[#4988C4] mb-6">Tambah Iklan</h2>

            <input id="inputJudul" class="w-full mb-3 border rounded-xl p-3" placeholder="Judul">
            <select id="inputTipe" class="w-full mb-3 border rounded-xl p-3">
                <option value="">Pilih Tipe</option>
                <option>1:1 Slide</option>
                <option>3:1 Kanan</option>
                <option>3:1 Kiri</option>
            </select>
            <input id="inputLink" class="w-full mb-3 border rounded-xl p-3" placeholder="Link">

            <input id="inputGambar" type="file" accept="image/*" class="hidden"
                onchange="previewGambar(event)">
            <label for="inputGambar"
                class="block border rounded-xl p-3 text-center cursor-pointer text-[#4988C4]">
                Pilih Gambar
            </label>

            <div id="previewContainer" class="hidden mt-3">
                <img id="previewImage" class="w-full h-40 object-cover rounded-xl">
            </div>

            <button onclick="tambahIklan()"
                class="mt-4 w-full bg-[#4988C4] text-white py-3 rounded-xl font-bold">
                Upload Iklan
            </button>
        </div>

        <!-- TABLE -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-8">

            <div class="flex justify-between mb-4">
                <h2 class="text-2xl font-bold text-[#4988C4]">Daftar Iklan</h2>
                <input id="searchInput" onkeyup="filterData()"
                    placeholder="Cari iklan..."
                    class="border rounded-full px-4 py-2">
            </div>

            <div class="overflow-auto border rounded-xl">
                <table class="w-full">
                    <thead class="bg-[#4988C4] text-white">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3">Link</th>
                            <th class="p-3">Gambar</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelIklan"></tbody>
                </table>
            </div>

            <div class="flex justify-between mt-4">
                <span id="pageInfo"></span>
                <div class="flex gap-2">
                    <button onclick="prevPage()" class="border px-4 py-2 rounded">Prev</button>
                    <button onclick="nextPage()" class="border px-4 py-2 rounded text-[#4988C4]">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- POPUP EDIT -->
<div id="popupEdit" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Edit Iklan</h3>

        <input id="editJudul" class="w-full border rounded p-2 mb-3">
        <select id="editTipe" class="w-full border rounded p-2 mb-3">
            <option>1:1 Slide</option>
            <option>3:1 Kanan</option>
            <option>3:1 Kiri</option>
        </select>
        <input id="editLink" class="w-full border rounded p-2 mb-3">

        <input id="editGambar" type="file" accept="image/*" class="hidden"
            onchange="previewEditGambar(event)">
        <label for="editGambar"
            class="block border rounded p-2 text-center cursor-pointer text-[#4988C4] mb-3">
            Ganti Gambar
        </label>

        <img id="editPreview" class="w-full h-40 object-cover rounded mb-4">

        <div class="flex justify-end gap-3">
            <button onclick="closeEdit()" class="px-4 py-2 border rounded">Batal</button>
            <button onclick="simpanEdit()" class="px-4 py-2 bg-[#4988C4] text-white rounded">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<!-- POPUP SUCCESS -->
<div id="popupSuccess" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 text-center">
        <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
        <p class="font-bold mb-4">Data berhasil diupdate</p>
        <button onclick="closeSuccess()"
            class="px-6 py-2 bg-[#4988C4] text-white rounded-xl">
            OK
        </button>
    </div>
</div>

@include('component.popuphapus')

<script>
const dataIklan = Array.from({ length: 15 }, (_, i) => ({
    judul: `Iklan Produk ${i + 1}`,
    tipe: ['1:1 Slide', '3:1 Kanan', '3:1 Kiri'][i % 3],
    link: `https://example.com/${i + 1}`,
    gambar: `https://picsum.photos/seed/${i}/200/200`
}));

let currentPage = 1, perPage = 10, keyword = '', editIndex = null;

function previewGambar(e){
    previewImage.src = URL.createObjectURL(e.target.files[0]);
    previewContainer.classList.remove('hidden');
}

function tambahIklan(){
    dataIklan.unshift({
        judul: inputJudul.value,
        tipe: inputTipe.value,
        link: inputLink.value,
        gambar: URL.createObjectURL(inputGambar.files[0])
    });
    inputJudul.value = inputTipe.value = inputLink.value = '';
    inputGambar.value = '';
    previewContainer.classList.add('hidden');
    renderTable();
}

function aksiMenu(i){
    return `
    <div class="relative">
        <button onclick="toggleMenu(${i})">⋮</button>
        <div id="menu-${i}" class="hidden absolute right-0 bg-white border rounded shadow">
            <button onclick="openEdit(${i})"
                class="block px-4 py-2 w-full text-left">Edit</button>
            <button onclick="hapusData(${i})"
                class="block px-4 py-2 w-full text-left text-red-600">Hapus</button>
        </div>
    </div>`;
}

function toggleMenu(i){
    document.querySelectorAll('[id^=menu-]').forEach(m=>m.classList.add('hidden'));
    document.getElementById('menu-'+i).classList.toggle('hidden');
}

function openEdit(i){
    editIndex = i;
    editJudul.value = dataIklan[i].judul;
    editTipe.value = dataIklan[i].tipe;
    editLink.value = dataIklan[i].link;
    editPreview.src = dataIklan[i].gambar;
    popupEdit.classList.remove('hidden');
    popupEdit.classList.add('flex');
}

function previewEditGambar(e){
    editPreview.src = URL.createObjectURL(e.target.files[0]);
}

function closeEdit(){
    popupEdit.classList.add('hidden');
}

function simpanEdit(){
    dataIklan[editIndex].judul = editJudul.value;
    dataIklan[editIndex].tipe = editTipe.value;
    dataIklan[editIndex].link = editLink.value;
    if(editGambar.files.length){
        dataIklan[editIndex].gambar = URL.createObjectURL(editGambar.files[0]);
    }
    closeEdit();
    renderTable();
    popupSuccess.classList.remove('hidden');
    popupSuccess.classList.add('flex');
}

function closeSuccess(){
    popupSuccess.classList.add('hidden');
}

function hapusData(i){
    openPopupHapus(()=>{
        dataIklan.splice(i,1);
        renderTable();
    });
}

function filterData(){
    keyword = searchInput.value.toLowerCase();
    currentPage = 1;
    renderTable();
}

function renderTable(){
    tabelIklan.innerHTML='';
    const filtered = dataIklan.filter(d =>
        d.judul.toLowerCase().includes(keyword) ||
        d.tipe.toLowerCase().includes(keyword)
    );
    const start = (currentPage-1)*perPage;
    filtered.slice(start,start+perPage).forEach((d,i)=>{
        tabelIklan.innerHTML += `
        <tr>
            <td class="p-3 text-center">${start+i+1}</td>
            <td class="p-3">${d.judul}</td>
            <td class="p-3 text-center">${d.tipe}</td>
            <td class="p-3 text-center">${d.link}</td>
            <td class="p-3 text-center">
                <img src="${d.gambar}" class="w-14 h-14 mx-auto rounded">
            </td>
            <td class="p-3 text-center">${aksiMenu(start+i)}</td>
        </tr>`;
    });
    pageInfo.innerText = `Hal ${currentPage} / ${Math.ceil(filtered.length/perPage)||1}`;
}

function prevPage(){ if(currentPage>1){currentPage--;renderTable();} }
function nextPage(){
    if(currentPage < Math.ceil(dataIklan.length/perPage)){
        currentPage++; renderTable();
    }
}

document.addEventListener('DOMContentLoaded', renderTable);
</script>
@endsection
