@extends('layout.App')

@section('title', 'Admin - Portal Blog')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin.css')}}">
</head>
<body class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-users-cog text-[#4988C4]"></i>
                    Kelola Admin
                </h1>
            </div>
            <button onclick="openTambahModal()" class="gradient-bg text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Tambah Admin
            </button>
        </div>

        <!-- Tabel -->
        <div class="bg-white rounded-2xl shadow-xl p-6 card-hover">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800"></h2>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Cari admin..." 
                        class="pl-12 pr-4 py-2 border-2 border-gray-200 rounded-xl focus:border-[#4988C4] focus:ring-4 focus:ring-[#4988C4]/20 outline-none transition-all">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="gradient-bg text-white">
                        <tr>
                            <th class="py-4 px-4 text-left rounded-tl-xl">No</th>
                            <th class="py-4 px-4 text-left">Foto</th>
                            <th class="py-4 px-4 text-left">Nama Admin</th>
                            <th class="py-4 px-4 text-left">Email/Username</th>
                            <th class="py-4 px-4 text-left">Password</th>
                            <th class="py-4 px-4 text-center rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelAdmin">
                        <tr class="border-b hover:bg-[#4988C4]/10 transition-all">
                            <td class="py-4 px-4 font-semibold">1</td>
                            <td class="py-4 px-4">
                                <div class="w-12 h-12 bg-[#4988C4] rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    AD
                                </div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-gray-800">Administrator</td>
                            <td class="py-4 px-4 text-gray-600">admin@example.com</td>
                            <td class="py-4 px-4">
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-mono">••••••••</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <button onclick="editAdmin(1, 'Administrator', 'admin@example.com')" class="bg-[#4988C4] hover:bg-[#3a6fa0] text-white px-4 py-2 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="hapusAdmin(1)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b hover:bg-[#4988C4]/10 transition-all">
                            <td class="py-4 px-4 font-semibold">2</td>
                            <td class="py-4 px-4">
                                <div class="w-12 h-12 bg-[#4988C4] rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    JD
                                </div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-gray-800">John Doe</td>
                            <td class="py-4 px-4 text-gray-600">john.doe@example.com</td>
                            <td class="py-4 px-4">
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-mono">••••••••</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <button onclick="editAdmin(2, 'John Doe', 'john.doe@example.com')" class="bg-[#4988C4] hover:bg-[#3a6fa0] text-white px-4 py-2 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="hapusAdmin(2)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-[#4988C4]/10 transition-all">
                            <td class="py-4 px-4 font-semibold">3</td>
                            <td class="py-4 px-4">
                                <div class="w-12 h-12 bg-[#4988C4] rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    JS
                                </div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-gray-800">Jane Smith</td>
                            <td class="py-4 px-4 text-gray-600">jane.smith@example.com</td>
                            <td class="py-4 px-4">
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-mono">••••••••</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <button onclick="editAdmin(3, 'Jane Smith', 'jane.smith@example.com')" class="bg-[#4988C4] hover:bg-[#3a6fa0] text-white px-4 py-2 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="hapusAdmin(3)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="modalTambah" class="hidden fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full">
            <div class="gradient-bg text-white p-6 rounded-t-3xl">
                <h3 class="text-2xl font-bold"><i class="fas fa-user-plus mr-2"></i>Tambah Admin Baru</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="tambahNama" placeholder="Masukkan nama lengkap" class="w-full px-4 py-3 border-2 rounded-xl outline-none focus:border-[#4988C4]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email/Username</label>
                    <input type="email" id="tambahEmail" placeholder="email@example.com" class="w-full px-4 py-3 border-2 rounded-xl outline-none focus:border-[#4988C4]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" id="tambahPassword" placeholder="Masukkan password" class="w-full px-4 py-3 border-2 rounded-xl outline-none focus:border-[#4988C4]">
                </div>
                <div class="flex gap-3 pt-2">
                    <button onclick="tutupTambahModal()" class="flex-1 bg-gray-400 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button onclick="simpanTambah()" class="flex-1 gradient-bg text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-check mr-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full">
            <div class="bg-[#4988C4] text-white p-6 rounded-t-3xl">
                <h3 class="text-2xl font-bold"><i class="fas fa-edit mr-2"></i>Edit Admin</h3>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" id="editId">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="editNama" class="w-full px-4 py-3 border-2 rounded-xl outline-none focus:border-[#4988C4]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email/Username</label>
                    <input type="email" id="editEmail" class="w-full px-4 py-3 border-2 rounded-xl outline-none focus:border-[#4988C4]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru (opsional)</label>
                    <input type="password" id="editPassword" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-3 border-2 rounded-xl outline-none focus:border-[#4988C4]">
                </div>
                <div class="flex gap-3 pt-2">
                    <button onclick="tutupEditModal()" class="flex-1 bg-gray-400 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button onclick="simpanEdit()" class="flex-1 bg-[#4988C4] hover:bg-[#3a6fa0] text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-check mr-2"></i>Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full m-4 text-center p-8">
            <div class="text-6xl mb-4 text-green-500">✓</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2" id="successMessage">Berhasil!</h3>
            <button onclick="tutupSuccess()" class="mt-4 bg-gradient-to-r from-green-500 to-teal-500 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                OK
            </button>
        </div>
    </div>

    <script>
        let counter = 4;
        let currentId = null;
        const colors = ['bg-[#4988C4]', 'bg-[#4988C4]', 'bg-[#4988C4]', 'bg-[#4988C4]', 'bg-[#4988C4]'];

        function getInitials(name) {
            return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        }

        function openTambahModal() {
            document.getElementById('modalTambah').classList.remove('hidden');
        }

        function tutupTambahModal() {
            document.getElementById('modalTambah').classList.add('hidden');
            document.getElementById('tambahNama').value = '';
            document.getElementById('tambahEmail').value = '';
            document.getElementById('tambahPassword').value = '';
        }

        function simpanTambah() {
            const nama = document.getElementById('tambahNama').value.trim();
            const email = document.getElementById('tambahEmail').value.trim();
            const password = document.getElementById('tambahPassword').value;

            if (!nama || !email || !password) {
                alert('⚠️ Semua field harus diisi!');
                return;
            }

            const color = 'bg-[#4988C4]';
            const initials = getInitials(nama);

            const tbody = document.getElementById('tabelAdmin');
            const tr = document.createElement('tr');
            tr.className = 'border-b hover:bg-[#4988C4]/10 transition-all';
            tr.innerHTML = `
                <td class="py-4 px-4 font-semibold">${counter}</td>
                <td class="py-4 px-4">
                    <div class="w-12 h-12 ${color} rounded-full flex items-center justify-center text-white font-bold text-lg">
                        ${initials}
                    </div>
                </td>
                <td class="py-4 px-4 font-semibold text-gray-800">${nama}</td>
                <td class="py-4 px-4 text-gray-600">${email}</td>
                <td class="py-4 px-4">
                    <span class="bg-gray-200 px-3 py-1 rounded-full text-xs font-mono">••••••••</span>
                </td>
                <td class="py-4 px-4 text-center">
                    <button onclick="editAdmin(${counter}, '${nama}', '${email}')" class="bg-[#4988C4] hover:bg-[#3a6fa0] text-white px-4 py-2 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="hapusAdmin(${counter})" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            counter++;

            tutupTambahModal();
            showSuccess('Admin baru berhasil ditambahkan!');
        }

        function editAdmin(id, nama, email) {
            currentId = id;
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPassword').value = '';
            document.getElementById('modalEdit').classList.remove('hidden');
        }

        function tutupEditModal() {
            document.getElementById('modalEdit').classList.add('hidden');
        }

        function simpanEdit() {
            const nama = document.getElementById('editNama').value.trim();
            const email = document.getElementById('editEmail').value.trim();

            if (!nama || !email) {
                alert('⚠️ Nama dan email harus diisi!');
                return;
            }

            // Update tabel (simulasi)
            tutupEditModal();
            showSuccess('Data admin berhasil diupdate!');
        }

        function hapusAdmin(id) {
            if (confirm('⚠️ Yakin ingin menghapus admin ini?')) {
                showSuccess('Admin berhasil dihapus!');
            }
        }

        function showSuccess(msg) {
            document.getElementById('successMessage').textContent = msg;
            document.getElementById('successModal').classList.remove('hidden');
        }

        function tutupSuccess() {
            document.getElementById('successModal').classList.add('hidden');
        }
    </script>

@endsection