@extends('layout.App')
@section('title','Kelola Admin')
@section('content')

<div class="p-6 max-w-7xl mx-auto space-y-6">

{{-- ALERT --}}
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl">
    {{ session('success') }}
</div>
@endif

{{-- HEADER --}}
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold">Kelola Admin</h1>

    <div class="flex gap-3">
        <form>
            <input name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari admin..."
                   class="border px-4 py-2 rounded-full text-sm w-56">
        </form>

        <button onclick="openTambah()"
            class="bg-[#4988C4] text-white px-6 py-2 rounded-full font-semibold">
            + Tambah Admin
        </button>
    </div>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-[#4988C4] text-white">
<tr>
    <th class="px-4 py-3">No</th>
    <th class="px-4 py-3">Foto</th>
    <th class="px-4 py-3 text-left">Nama</th>
    <th class="px-4 py-3 text-left">Email</th>
    <th class="px-4 py-3 text-left">Password</th>
    <th class="px-4 py-3 text-center">Aksi</th>
</tr>
</thead>
<tbody>
@foreach($admins as $admin)
<tr class="border-b hover:bg-gray-50">
<td class="px-4 py-3">
    {{ $loop->iteration + $admins->firstItem() - 1 }}
</td>
<td class="px-4 py-3">
    <img src="{{ $admin['photo'] }}" class="w-10 h-10 rounded-full mx-auto">
</td>
<td class="px-4 py-3">{{ $admin['name'] }}</td>
<td class="px-4 py-3">{{ $admin['email'] }}</td>
<td class="px-4 py-3">{{ $admin['password'] }}</td>

<td class="px-4 py-3 text-center relative">
    <button onclick="toggleMenu({{ $admin['id'] }})"
        class="text-xl font-bold">⋮</button>

    <div id="menu{{ $admin['id'] }}"
        class="hidden absolute right-6 top-10 bg-white border rounded-xl shadow w-32 z-50">
        <button onclick="openEdit('{{ $admin['id'] }}','{{ $admin['name'] }}','{{ $admin['email'] }}')"
            class="block w-full px-4 py-2 text-left hover:bg-gray-100">
            Edit
        </button>
        <button onclick="confirmHapus('{{ $admin['id'] }}')"
            class="block w-full px-4 py-2 text-left text-red-600 hover:bg-gray-100">
            Hapus
        </button>
    </div>
</td>
</tr>
@endforeach
</tbody>
</table>

{{-- PAGINATION --}}
<div class="flex justify-between items-center px-6 py-4 text-sm text-gray-600">
    <div>Hal {{ $admins->currentPage() }} / {{ $admins->lastPage() }}</div>
    <div class="flex gap-2">
        @if($admins->onFirstPage())
            <span class="px-4 py-2 border rounded-xl text-gray-400">Prev</span>
        @else
            <a href="{{ $admins->previousPageUrl() }}" class="px-4 py-2 border rounded-xl">Prev</a>
        @endif

        @if($admins->hasMorePages())
            <a href="{{ $admins->nextPageUrl() }}" class="px-4 py-2 border rounded-xl">Next</a>
        @else
            <span class="px-4 py-2 border rounded-xl text-gray-400">Next</span>
        @endif
    </div>
</div>
</div>

{{-- MODAL TAMBAH / EDIT --}}
<div id="adminModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
<div class="bg-white w-full max-w-md rounded-2xl p-6">
<h2 id="modalTitle" class="text-xl font-bold mb-6">Tambah Admin</h2>

<form id="adminForm" method="POST" enctype="multipart/form-data" autocomplete="off">
@csrf
<div id="methodField"></div>

{{-- FOTO --}}
<div class="flex flex-col items-center mb-6">
    <img id="previewFoto"
         src="https://via.placeholder.com/120"
         class="w-28 h-28 rounded-full object-cover border mb-3">
    <input type="file" accept="image/*" onchange="previewImage(event)">
</div>

<input id="namaAdmin" name="name"
class="w-full border p-3 mb-3 rounded-xl" placeholder="Nama">

<input id="emailAdmin" name="email"
class="w-full border p-3 mb-3 rounded-xl" placeholder="Email">

<input type="password" name="password"
autocomplete="new-password"
class="w-full bg-blue-50 border p-3 mb-3 rounded-xl"
placeholder="Password">

<input type="password" name="new_password" id="newPassword"
autocomplete="new-password"
class="w-full border p-3 mb-4 rounded-xl hidden"
placeholder="Password Baru">

<button class="w-full bg-[#4988C4] text-white py-3 rounded-xl font-semibold">
Simpan
</button>

<button type="button" onclick="closeModal()"
class="w-full mt-3 text-gray-500">
Batal
</button>
</form>
</div>
</div>

{{-- MODAL HAPUS --}}
<div id="popupHapus" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
<div class="bg-white p-6 rounded-xl text-center">
<h3 class="font-bold text-red-600 mb-4">Yakin hapus admin?</h3>
<form id="formHapus" method="POST">
@csrf @method('DELETE')
<button class="bg-red-600 text-white px-6 py-2 rounded-xl">
Ya, Hapus
</button>
</form>
<button onclick="closePopupHapus()" class="mt-3">Batal</button>
</div>
</div>

<script>
function previewImage(e){
    const r = new FileReader();
    r.onload = () => previewFoto.src = r.result;
    r.readAsDataURL(e.target.files[0]);
}

function toggleMenu(id){
    document.querySelectorAll('[id^="menu"]').forEach(e=>e.classList.add('hidden'));
    document.getElementById('menu'+id).classList.toggle('hidden');
}

function openTambah(){
    adminForm.action = "{{ route('admin.store') }}";
    methodField.innerHTML = "";
    adminForm.reset();
    previewFoto.src = "https://via.placeholder.com/120";
    newPassword.classList.add('hidden');
    modalTitle.innerText = "Tambah Admin";
    adminModal.classList.remove('hidden');
}

function openEdit(id,n,e){
    adminForm.action = "/admin/"+id;
    methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    adminForm.reset();
    namaAdmin.value = n;
    emailAdmin.value = e;
    newPassword.classList.remove('hidden');
    previewFoto.src = "https://via.placeholder.com/120";
    modalTitle.innerText = "Edit Admin";
    adminModal.classList.remove('hidden');
}

function closeModal(){ adminModal.classList.add('hidden'); }
function confirmHapus(id){ formHapus.action="/admin/"+id; popupHapus.classList.remove('hidden'); }
function closePopupHapus(){ popupHapus.classList.add('hidden'); }
</script>

@endsection
