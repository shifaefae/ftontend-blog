@extends('layout.App')
@section('title','Kelola Admin')
@section('content')

<div class="p-6 max-w-7xl mx-auto space-y-6">

{{-- ALERT --}}
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl">
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
</div>
@endif

{{-- ✅ DEBUG PANEL — hapus setelah berhasil --}}
@if(isset($debugError) && $debugError)
<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-xl text-sm font-mono">
    <strong>⚠️ DEBUG ERROR:</strong><br>{{ $debugError }}
</div>
@endif

{{-- HEADER --}}
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Admin & User</h1>
    <button onclick="openTambah()"
        class="bg-[#4988C4] text-white px-6 py-2 rounded-full font-semibold hover:bg-[#3a6ea0] transition">
        + Tambah User
    </button>
</div>

<p class="text-sm text-gray-500">Total: <strong>{{ $total }}</strong> user terdaftar</p>

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-[#4988C4] text-white">
<tr>
    <th class="px-4 py-3 text-left">No</th>
    <th class="px-4 py-3 text-left">Nama</th>
    <th class="px-4 py-3 text-left">Email</th>
    <th class="px-4 py-3 text-center">Role</th>
    <th class="px-4 py-3 text-center">Aksi</th>
</tr>
</thead>
<tbody>
@forelse($users as $i => $user)
<tr class="border-b hover:bg-gray-50">
    <td class="px-4 py-3 text-gray-600">{{ (($currentPage - 1) * 10) + $i + 1 }}</td>
    <td class="px-4 py-3 font-medium text-gray-800">
        {{ $user['name'] ?? '-' }}
        @if(session('user.id') == ($user['id'] ?? null))
            <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Anda</span>
        @endif
    </td>
    <td class="px-4 py-3 text-gray-500">{{ $user['email'] ?? '-' }}</td>
    <td class="px-4 py-3 text-center">
        @php $role = $user['role'] ?? 'user'; @endphp
        @if($role === 'super_admin')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">Super Admin</span>
        @elseif($role === 'admin')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Admin</span>
        @else
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">User</span>
        @endif
    </td>
    <td class="px-4 py-3 text-center">
        <div class="flex justify-center gap-3">
            <button onclick="openEdit('{{ $user['id'] }}','{{ addslashes($user['name'] ?? '') }}','{{ addslashes($user['email'] ?? '') }}','{{ $user['role'] ?? 'user' }}')"
                class="text-blue-600 hover:text-blue-800 font-medium text-sm">Edit</button>
            @if(session('user.id') != ($user['id'] ?? null))
                <form action="{{ route('admin.destroy', $user['id']) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus {{ addslashes($user['name'] ?? '') }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Hapus</button>
                </form>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center py-8 text-gray-400">
        <i class="fas fa-users text-3xl mb-2 block"></i>
        Belum ada data user.
    </td>
</tr>
@endforelse
</tbody>
</table>

{{-- PAGINATION --}}
<div class="flex justify-between items-center px-6 py-4 text-sm text-gray-600">
    <div>Hal <strong>{{ $currentPage }}</strong> / <strong>{{ $lastPage }}</strong></div>
    <div class="flex gap-2">
        @if($currentPage > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}"
               class="px-4 py-2 border rounded-xl hover:bg-gray-50">Prev</a>
        @else
            <span class="px-4 py-2 border rounded-xl text-gray-300">Prev</span>
        @endif
        @if($currentPage < $lastPage)
            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}"
               class="px-4 py-2 border rounded-xl hover:bg-gray-50">Next</a>
        @else
            <span class="px-4 py-2 border rounded-xl text-gray-300">Next</span>
        @endif
    </div>
</div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modalTambah" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">
        <i class="fas fa-user-plus mr-2 text-[#4988C4]"></i>Tambah User
    </h2>
    <form action="{{ route('admin.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border px-4 py-3 rounded-xl outline-none focus:ring-2 focus:ring-[#4988C4]/30"
                   placeholder="Nama lengkap" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border px-4 py-3 rounded-xl outline-none focus:ring-2 focus:ring-[#4988C4]/30"
                   placeholder="email@contoh.com" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
            <select name="role" class="w-full border px-4 py-3 rounded-xl outline-none" required>
                <option value="">-- Pilih Role --</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full border px-4 py-3 rounded-xl outline-none focus:ring-2 focus:ring-[#4988C4]/30"
                   placeholder="Min. 8 karakter" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full border px-4 py-3 rounded-xl outline-none focus:ring-2 focus:ring-[#4988C4]/30"
                   placeholder="Ulangi password" required>
        </div>
        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-[#4988C4] text-white py-3 rounded-xl font-semibold hover:bg-[#3a6ea0] transition">
                Simpan
            </button>
            <button type="button" onclick="closeTambah()"
                    class="flex-1 border py-3 rounded-xl font-semibold text-gray-600 hover:bg-gray-50 transition">
                Batal
            </button>
        </div>
    </form>
</div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEdit" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">
        <i class="fas fa-user-edit mr-2 text-[#4988C4]"></i>Edit User
    </h2>
    <form id="formEdit" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="editName"
                   class="w-full border px-4 py-3 rounded-xl outline-none" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="editEmail"
                   class="w-full border px-4 py-3 rounded-xl outline-none" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
            <select name="role" id="editRole" class="w-full border px-4 py-3 rounded-xl outline-none" required>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak ingin ganti)</span>
            </label>
            <input type="password" name="password"
                   class="w-full border px-4 py-3 rounded-xl outline-none" placeholder="Min. 8 karakter">
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                   class="w-full border px-4 py-3 rounded-xl outline-none" placeholder="Ulangi password baru">
        </div>
        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-[#4988C4] text-white py-3 rounded-xl font-semibold hover:bg-[#3a6ea0] transition">
                Update
            </button>
            <button type="button" onclick="closeEdit()"
                    class="flex-1 border py-3 rounded-xl font-semibold text-gray-600 hover:bg-gray-50 transition">
                Batal
            </button>
        </div>
    </form>
</div>
</div>

<script>
function openTambah() {
    document.getElementById('modalTambah').classList.remove('hidden');
    document.getElementById('modalTambah').classList.add('flex');
}
function closeTambah() {
    document.getElementById('modalTambah').classList.add('hidden');
    document.getElementById('modalTambah').classList.remove('flex');
}
function openEdit(id, name, email, role) {
    document.getElementById('formEdit').action = `/admin/${id}`;
    document.getElementById('editName').value  = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value  = role;
    document.getElementById('modalEdit').classList.remove('hidden');
    document.getElementById('modalEdit').classList.add('flex');
}
function closeEdit() {
    document.getElementById('modalEdit').classList.add('hidden');
    document.getElementById('modalEdit').classList.remove('flex');
}
document.getElementById('modalTambah').addEventListener('click', function(e) {
    if (e.target === this) closeTambah();
});
document.getElementById('modalEdit').addEventListener('click', function(e) {
    if (e.target === this) closeEdit();
});
</script>

@endsection
