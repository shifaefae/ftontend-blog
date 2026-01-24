@extends('layout.App')

@section('title', 'Kategori - Portal Blog')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori & Tag</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: ( #fbfbfc  100%);
            min-height: 100vh;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border-radius: 16px 16px 0 0;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide { animation: slideIn 0.5s ease-out; }
    </style>
</head>
<body class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 animate-slide">
            <h1 class="text-5xl font-bold text-black flex items-center gap-4 drop-shadow-lg">
                <div class="bg-white p-4 rounded-2xl shadow-2xl">
                    <i class="fas fa-layer-group text-purple-600 text-4xl"></i>
                </div>
                Kelola Kategori & Tag
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Tambah Kategori -->
            <div class="bg-white rounded-2xl shadow-2xl card-hover p-6 relative gradient-border">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-5 pb-3 border-b-2 border-purple-100">
                    <i class="fas fa-plus-circle"></i> Tambah Kategori
                </h2>
                <div class="space-y-4">
                    <input type="text" id="inputKategori" placeholder="Masukkan nama kategori" 
                        class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 outline-none">
                    <textarea id="newCategoryDesc"
                                  placeholder="Deskripsi" 
                                  rows="3" 
                                  class="w-full px-4 py-2 bg-white border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-400 resize-none"></textarea>   
                    <button onclick="tambahKategori()" 
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-3 rounded-xl hover:shadow-2xl transform hover:scale-105 transition-all">
                        <i class="fas fa-save mr-2"></i>Simpan Kategori
                    </button>
                </div>
            </div>

            <!-- Tabel Kategori -->
            <div class="bg-white rounded-2xl shadow-2xl card-hover p-6 relative gradient-border">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-5 pb-3 border-b-2 border-purple-100">
                    <i class="fas fa-list"></i> Daftar Kategori
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gradient-to-r from-purple-100 to-pink-100">
                                <th class="text-left py-4 px-4 font-bold text-purple-800 rounded-tl-xl">Nama</th>
                                <th class="text-left py-4 px-4 font-bold text-purple-800">Deskripsi</th>
                                <th class="text-center py-4 px-3 font-bold text-purple-800">Jumlah</th>
                                <th class="text-center py-4 px-3 font-bold text-purple-800">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelKategori">
                            <tr class="border-b hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50">
                                <td class="py-4 px-4 font-semibold flex items-center gap-2">
                                    <i class="fas fa-folder text-purple-600"></i>Teknologi
                                </td>
                                <td class="py-4 px-4 text-gray-600">
                                    Artikel seputar teknologi terkini
                                </td>
                                <td class="py-4 px-3 text-center">

                                    <span class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-1.5 rounded-full text-xs font-bold">12</span>
                                </td>
                                <td class="py-4 px-3 text-center">
                                    <button onclick="editKategori(this, 'Teknologi', 12)" class="text-purple-600 hover:bg-purple-100 p-2 rounded-lg mr-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="hapusKategori(this)" class="text-red-500 hover:bg-red-100 p-2 rounded-lg">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tambah Tag -->
            <div class="bg-white rounded-2xl shadow-2xl card-hover p-6 relative gradient-border">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent mb-5 pb-3 border-b-2 border-blue-100">
                    <i class="fas fa-tags"></i> Tambah Tag
                </h2>
                <div class="space-y-4">
                    <input type="text" id="inputTag" placeholder="Masukkan nama tag" 
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 outline-none">
                    <button onclick="tambahTag()" 
                        class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold py-3 rounded-xl hover:shadow-2xl transform hover:scale-105 transition-all">
                        <i class="fas fa-save mr-2"></i>Simpan Tag
                    </button>
                </div>
            </div>

            <!-- Tabel Tag -->
            <div class="bg-white rounded-2xl shadow-2xl card-hover p-6 relative gradient-border">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent mb-5 pb-3 border-b-2 border-blue-100">
                    <i class="fas fa-bookmark"></i> Daftar Tag
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-100 to-cyan-100">
                                <th class="text-left py-4 px-4 font-bold text-blue-800 rounded-tl-xl">Nama</th>
                                <th class="text-center py-4 px-3 font-bold text-blue-800">Jumlah</th>
                                <th class="text-center py-4 px-3 font-bold text-blue-800">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelTag">
                            <tr class="border-b hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50">
                                <td class="py-4 px-4 font-semibold flex items-center gap-2">
                                    <i class="fas fa-tag text-blue-600"></i>Programming
                                </td>
                                <td class="py-4 px-3 text-center">
                                    <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-1.5 rounded-full text-xs font-bold">25</span>
                                </td>
                                <td class="py-4 px-3 text-center">
                                    <button onclick="editTag(this, 'Programming', 25)" class="text-blue-600 hover:bg-blue-100 p-2 rounded-lg mr-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="hapusTag(this)" class="text-red-500 hover:bg-red-100 p-2 rounded-lg">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div id="modalKategori" class="hidden fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full m-4">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 rounded-t-3xl">
                <h3 class="text-2xl font-bold"><i class="fas fa-edit mr-2"></i>Edit Kategori</h3>
            </div>
            <div class="p-6 space-y-4">
                <input type="text" id="editNamaKategori" class="w-full px-4 py-3 border-2 rounded-xl outline-none">
                <textarea id="editDeskripsiKategori"
                    rows="3"
                    class="w-full px-4 py-3 border-2 rounded-xl outline-none resize-none"
                    placeholder="Deskripsi kategori"></textarea>

                <input type="number" id="editJumlahKategori" class="w-full px-4 py-3 border-2 rounded-xl outline-none">
                <div class="flex gap-3">
                    <button onclick="simpanKategori()" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-xl font-semibold">
                        <i class="fas fa-check mr-2"></i>Simpan
                    </button>
                    <button onclick="tutupModal('modalKategori')" class="flex-1 bg-gray-400 text-white py-3 rounded-xl font-semibold">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tag -->
    <div id="modalTag" class="hidden fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full m-4">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white p-6 rounded-t-3xl">
                <h3 class="text-2xl font-bold"><i class="fas fa-edit mr-2"></i>Edit Tag</h3>
            </div>
            <div class="p-6 space-y-4">
                <input type="text" id="editNamaTag" class="w-full px-4 py-3 border-2 rounded-xl outline-none">
                <input type="number" id="editJumlahTag" class="w-full px-4 py-3 border-2 rounded-xl outline-none">
                <div class="flex gap-3">
                    <button onclick="simpanTag()" class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-3 rounded-xl font-semibold">
                        <i class="fas fa-check mr-2"></i>Simpan
                    </button>
                    <button onclick="tutupModal('modalTag')" class="flex-1 bg-gray-400 text-white py-3 rounded-xl font-semibold">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full m-4 text-center p-8">
            <div class="text-6xl mb-4">✓</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2" id="successMessage">Berhasil!</h3>
            <button onclick="tutupModal('successModal')" class="mt-4 bg-gradient-to-r from-green-500 to-teal-500 text-white px-8 py-3 rounded-xl font-semibold">
                OK
            </button>
        </div>
    </div>

    <script>
        let currentRow = null;
        const colors = ['from-purple-500 to-pink-500', 'from-blue-500 to-cyan-500', 'from-green-500 to-teal-500', 'from-orange-500 to-red-500', 'from-pink-500 to-rose-500', 'from-violet-500 to-purple-500'];

        function tambahKategori() {
            const namaInput = document.getElementById('inputKategori');
            const descInput = document.getElementById('newCategoryDesc');

            const nama = namaInput.value.trim();
            const deskripsi = descInput.value.trim();

            if (!nama) return alert('⚠️ Nama kategori tidak boleh kosong!');

            const tbody = document.getElementById('tabelKategori');
            const tr = document.createElement('tr');
            tr.className = 'border-b hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50';

            const color = colors[Math.floor(Math.random() * colors.length)];

            tr.innerHTML = `
                <td class="py-4 px-4 font-semibold flex items-center gap-2">
                    <i class="fas fa-folder text-purple-600"></i>${nama}
                </td>
                <td class="py-4 px-4 text-gray-600">
                    ${deskripsi || '-'}
                </td>
                <td class="py-4 px-3 text-center">
                    <span class="bg-gradient-to-r ${color} text-white px-4 py-1.5 rounded-full text-xs font-bold">0</span>
                </td>
                <td class="py-4 px-3 text-center">
                    <button onclick="editKategori(this)" class="text-purple-600 hover:bg-purple-100 p-2 rounded-lg mr-2">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="hapusKategori(this)" class="text-red-500 hover:bg-red-100 p-2 rounded-lg">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            namaInput.value = '';
            descInput.value = '';
            showSuccess('Kategori berhasil ditambahkan!');
        }


        function tambahTag() {
            const input = document.getElementById('inputTag');
            const nama = input.value.trim();
            if (!nama) return alert('⚠️ Nama tag tidak boleh kosong!');

            const tbody = document.getElementById('tabelTag');
            const tr = document.createElement('tr');
            tr.className = 'border-b hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50';
            const color = colors[Math.floor(Math.random() * colors.length)];
            tr.innerHTML = `
                <td class="py-4 px-4 font-semibold flex items-center gap-2">
                    <i class="fas fa-tag text-blue-600"></i>${nama}
                </td>
                <td class="py-4 px-3 text-center">
                    <span class="bg-gradient-to-r ${color} text-white px-4 py-1.5 rounded-full text-xs font-bold">0</span>
                </td>
                <td class="py-4 px-3 text-center">
                    <button onclick="editTag(this, '${nama}', 0)" class="text-blue-600 hover:bg-blue-100 p-2 rounded-lg mr-2">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="hapusTag(this)" class="text-red-500 hover:bg-red-100 p-2 rounded-lg">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            input.value = '';
            showSuccess('Tag berhasil ditambahkan!');
        }

        function editKategori(btn, nama, jumlah) {
            currentRow = btn.closest('tr');
            document.getElementById('editNamaKategori').value = nama;
            document.getElementById('editJumlahKategori').value = jumlah;
            document.getElementById('modalKategori').classList.remove('hidden');
        }

        function editTag(btn, nama, jumlah) {
            currentRow = btn.closest('tr');
            document.getElementById('editNamaTag').value = nama;
            document.getElementById('editJumlahTag').value = jumlah;
            document.getElementById('modalTag').classList.remove('hidden');
        }

        function simpanKategori() {
            const nama = document.getElementById('editNamaKategori').value.trim();
            const jumlah = document.getElementById('editJumlahKategori').value;
            if (!nama) return alert('⚠️ Nama tidak boleh kosong!');

            const cells = currentRow.cells;
            cells[0].innerHTML = `<i class="fas fa-folder text-purple-600"></i>${nama}`;
            cells[1].querySelector('span').textContent = jumlah;
            tutupModal('modalKategori');
            showSuccess('Kategori berhasil diupdate!');
        }

        function simpanTag() {
            const nama = document.getElementById('editNamaTag').value.trim();
            const jumlah = document.getElementById('editJumlahTag').value;
            if (!nama) return alert('⚠️ Nama tidak boleh kosong!');

            const cells = currentRow.cells;
            cells[0].innerHTML = `<i class="fas fa-tag text-blue-600"></i>${nama}`;
            cells[1].querySelector('span').textContent = jumlah;
            tutupModal('modalTag');
            showSuccess('Tag berhasil diupdate!');
        }

        function hapusKategori(btn) {
            if (confirm('⚠️ Yakin ingin menghapus kategori ini?')) {
                btn.closest('tr').remove();
                showSuccess('Kategori berhasil dihapus!');
            }
        }

        function hapusTag(btn) {
            if (confirm('⚠️ Yakin ingin menghapus tag ini?')) {
                btn.closest('tr').remove();
                showSuccess('Tag berhasil dihapus!');
            }
        }

        function tutupModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function showSuccess(msg) {
            document.getElementById('successMessage').textContent = msg;
            document.getElementById('successModal').classList.remove('hidden');
            setTimeout(() => tutupModal('successModal'), 2000);
        }

        // Close modal on outside click
        document.querySelectorAll('.fixed').forEach(modal => {
            modal.addEventListener('click', e => {
                if (e.target === modal) tutupModal(modal.id);
            });
        });
    </script>
</body>
</html>

@endsection