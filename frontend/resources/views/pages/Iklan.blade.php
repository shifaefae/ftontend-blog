@extends('layout.app')

@section('title', 'Iklan - Portal Blog')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kelola Iklan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 75%, #4facfe 100%);
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
<body class="font-sans">

<div class="p-6">
  <!-- Header -->
  <div class="mb-8 animate-slide">
    <h1 class="text-5xl font-bold text-black flex items-center gap-4 drop-shadow-lg">
      <div class="bg-white p-4 rounded-2xl shadow-2xl">
        <i class="fas fa-ad text-purple-600 text-4xl"></i>
      </div>
      Kelola Iklan
    </h1>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

    <!-- FORM TAMBAH IKLAN -->
    <div class="bg-white rounded-2xl shadow-2xl card-hover p-6 flex flex-col relative gradient-border animate-slide">
      <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-6 flex items-center gap-2">
        <i class="fas fa-plus-circle"></i>
        Tambah Iklan
      </h2>

      <form id="formTambah" class="flex flex-col flex-1 space-y-5">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <i class="fas fa-heading text-purple-600"></i>
            Judul Iklan
          </label>
          <input 
            type="text" 
            id="inputJudul"
            placeholder="Masukkan judul iklan" 
            class="w-full rounded-xl border-2 border-purple-200 px-4 py-3 focus:ring-4 focus:ring-purple-200 focus:border-purple-500 outline-none transition-all" 
          />
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <i class="fas fa-layer-group text-purple-600"></i>
            Tipe Iklan
          </label>
          <select 
            id="inputTipe"
            class="w-full rounded-xl border-2 border-purple-200 px-4 py-3 focus:ring-4 focus:ring-purple-200 focus:border-purple-500 outline-none transition-all">
            <option value="">Pilih Tipe Iklan</option>
            <option>1:1 Slide</option>
            <option>3:1 Kanan</option>
            <option>3:1 Kiri</option>
            <option>3:1 Tengah</option>
            <option>1:3 Atas</option>
            <option>1:3 Tengah</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <i class="fas fa-link text-purple-600"></i>
            Link URL
          </label>
          <input 
            type="text" 
            id="inputLink"
            placeholder="https://example.com" 
            class="w-full rounded-xl border-2 border-purple-200 px-4 py-3 focus:ring-4 focus:ring-purple-200 focus:border-purple-500 outline-none transition-all" 
          />
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <i class="fas fa-image text-purple-600"></i>
            Gambar Iklan
          </label>
          <div class="flex items-center gap-3">
            <input 
              type="file" 
              id="inputGambar" 
              accept="image/*"
              class="hidden"
              onchange="previewGambar(event)"
            >
            <label for="inputGambar" class="flex-1 px-4 py-3 border-2 border-purple-200 rounded-xl text-sm text-gray-500 cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all flex items-center justify-center gap-2">
              <i class="fas fa-cloud-upload-alt text-lg"></i>
              <span id="namaFile">Pilih gambar</span>
            </label>
          </div>
          <div id="previewContainer" class="hidden mt-3">
            <img id="previewImage" class="w-full h-40 object-cover rounded-xl border-2 border-purple-200 shadow-lg">
          </div>
        </div>

        <div class="flex-1"></div>

        <button 
          type="button"
          onclick="tambahIklan()" 
          class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-3 rounded-xl hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all shadow-lg hover:shadow-2xl"
        >
          <i class="fas fa-paper-plane mr-2"></i>Upload Iklan
        </button>
      </form>
    </div>

    <!-- TABEL IKLAN -->
    <div class="bg-white rounded-2xl shadow-2xl card-hover p-6 lg:col-span-2 flex flex-col relative gradient-border animate-slide" style="animation-delay: 0.1s;">
      <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-4 flex items-center gap-2">
        <i class="fas fa-list"></i>
        Daftar Iklan
      </h2>

      <div class="overflow-y-auto max-h-[520px] border-2 border-purple-100 rounded-xl shadow-inner">
        <table class="min-w-full text-sm">
          <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white sticky top-0 shadow-lg">
            <tr>
              <th class="px-4 py-4 font-bold">No</th>
              <th class="px-4 py-4 font-bold text-left">Judul</th>
              <th class="px-4 py-4 font-bold">Tipe</th>
              <th class="px-4 py-4 font-bold">Link</th>
              <th class="px-4 py-4 font-bold">Gambar</th>
              <th class="px-4 py-4 font-bold">Aksi</th>
            </tr>
          </thead>
          <tbody id="tabelIklan" class="divide-y divide-purple-100">
            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all">
              <td class="px-4 py-4 text-center font-semibold">1</td>
              <td class="px-4 py-4 font-semibold text-gray-800">Pemerintah Resmi Naikkan UMK 2026</td>
              <td class="px-4 py-4 text-center">
                <span class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-3 py-1 rounded-full text-xs font-bold">3:1 Tengah</span>
              </td>
              <td class="px-4 py-4">
                <a href="#" class="text-purple-600 hover:text-pink-600 font-medium flex items-center gap-1">
                  <i class="fas fa-external-link-alt text-xs"></i>
                  berita.com/1
                </a>
              </td>
              <td class="px-4 py-4">
                <img src="https://picsum.photos/seed/1/120/80" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all" />
              </td>
              <td class="px-4 py-4 text-center">
                <button onclick="editIklan('Pemerintah Resmi Naikkan UMK 2026', '3:1 Tengah', 'berita.com/1')" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-3 py-1.5 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="hapusIklan(this)" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-3 py-1.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all">
              <td class="px-4 py-4 text-center font-semibold">2</td>
              <td class="px-4 py-4 font-semibold text-gray-800">Harga BBM Terbaru Berlaku Nasional</td>
              <td class="px-4 py-4 text-center">
                <span class="bg-gradient-to-r from-green-500 to-teal-500 text-white px-3 py-1 rounded-full text-xs font-bold">1:1 Slide</span>
              </td>
              <td class="px-4 py-4">
                <a href="#" class="text-purple-600 hover:text-pink-600 font-medium flex items-center gap-1">
                  <i class="fas fa-external-link-alt text-xs"></i>
                  berita.com/2
                </a>
              </td>
              <td class="px-4 py-4">
                <img src="https://picsum.photos/seed/2/120/80" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all" />
              </td>
              <td class="px-4 py-4 text-center">
                <button onclick="editIklan('Harga BBM Terbaru Berlaku Nasional', '1:1 Slide', 'berita.com/2')" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-3 py-1.5 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="hapusIklan(this)" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-3 py-1.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all">
              <td class="px-4 py-4 text-center font-semibold">3</td>
              <td class="px-4 py-4 font-semibold text-gray-800">Timnas Indonesia Lolos Piala Asia</td>
              <td class="px-4 py-4 text-center">
                <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">3:1 Kanan</span>
              </td>
              <td class="px-4 py-4">
                <a href="#" class="text-purple-600 hover:text-pink-600 font-medium flex items-center gap-1">
                  <i class="fas fa-external-link-alt text-xs"></i>
                  berita.com/3
                </a>
              </td>
              <td class="px-4 py-4">
                <img src="https://picsum.photos/seed/3/120/80" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all" />
              </td>
              <td class="px-4 py-4 text-center">
                <button onclick="editIklan('Timnas Indonesia Lolos Piala Asia', '3:1 Kanan', 'berita.com/3')" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-3 py-1.5 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="hapusIklan(this)" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-3 py-1.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
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

<!-- MODAL EDIT -->
<div id="modal" class="fixed inset-0 hidden bg-black/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg animate-slide">
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white p-6 rounded-t-3xl">
      <h3 class="text-2xl font-bold flex items-center gap-3">
        <i class="fas fa-edit"></i>
        Edit Iklan
      </h3>
    </div>

    <form class="p-6 space-y-4">
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Judul</label>
        <input id="modalJudul" type="text" class="w-full border-2 border-blue-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-200 outline-none" />
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Iklan</label>
        <select id="modalTipe" class="w-full border-2 border-blue-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-200 outline-none">
          <option>1:1 Slide</option>
          <option>3:1 Kanan</option>
          <option>3:1 Kiri</option>
          <option>3:1 Tengah</option>
          <option>1:3 Atas</option>
          <option>1:3 Tengah</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Link</label>
        <input id="modalLink" type="text" placeholder="https://example.com" class="w-full border-2 border-blue-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-200 outline-none" />
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Baru (opsional)</label>
        <input type="file" accept="image/*" class="w-full text-sm border-2 border-blue-200 rounded-xl px-4 py-3" />
      </div>

      <div class="pt-4 flex justify-end gap-3">
        <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-xl border-2 border-gray-300 hover:bg-gray-100 font-semibold transition-all">
          <i class="fas fa-times mr-2"></i>Batal
        </button>
        <button type="button" onclick="simpanEdit()" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold shadow-lg transition-all">
          <i class="fas fa-check mr-2"></i>Simpan
        </button>
      </div>
    </form>
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
  let currentRow = null;

  function previewGambar(event) {
    const file = event.target.files[0];
    if (file) {
      document.getElementById('namaFile').textContent = file.name;
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('previewImage').src = e.target.result;
        document.getElementById('previewContainer').classList.remove('hidden');
      }
      reader.readAsDataURL(file);
    }
  }

  function tambahIklan() {
    const judul = document.getElementById('inputJudul').value.trim();
    const tipe = document.getElementById('inputTipe').value;
    const link = document.getElementById('inputLink').value.trim();
    const gambar = document.getElementById('inputGambar').files[0];

    if (!judul || !tipe || !link || !gambar) {
      alert('⚠️ Semua field harus diisi!');
      return;
    }

    const tbody = document.getElementById('tabelIklan');
    const rowCount = tbody.rows.length + 1;
    const colors = ['from-blue-500 to-cyan-500', 'from-green-500 to-teal-500', 'from-orange-500 to-red-500', 'from-purple-500 to-pink-500'];
    const randomColor = colors[Math.floor(Math.random() * colors.length)];

    const tr = document.createElement('tr');
    tr.className = 'hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all';
    tr.innerHTML = `
      <td class="px-4 py-4 text-center font-semibold">${rowCount}</td>
      <td class="px-4 py-4 font-semibold text-gray-800">${judul}</td>
      <td class="px-4 py-4 text-center">
        <span class="bg-gradient-to-r ${randomColor} text-white px-3 py-1 rounded-full text-xs font-bold">${tipe}</span>
      </td>
      <td class="px-4 py-4">
        <a href="${link}" target="_blank" class="text-purple-600 hover:text-pink-600 font-medium flex items-center gap-1">
          <i class="fas fa-external-link-alt text-xs"></i>
          ${link.substring(0, 30)}...
        </a>
      </td>
      <td class="px-4 py-4">
        <img src="${URL.createObjectURL(gambar)}" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all w-[120px] h-[80px]" />
      </td>
      <td class="px-4 py-4 text-center">
        <button onclick="editIklan('${judul}', '${tipe}', '${link}')" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-3 py-1.5 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
          <i class="fas fa-edit"></i>
        </button>
        <button onclick="hapusIklan(this)" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-3 py-1.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
          <i class="fas fa-trash"></i>
        </button>
      </td>
    `;
    tbody.appendChild(tr);

    document.getElementById('formTambah').reset();
    document.getElementById('namaFile').textContent = 'Pilih gambar';
    document.getElementById('previewContainer').classList.add('hidden');
    
    showSuccess('Iklan berhasil ditambahkan!');
  }

  function editIklan(judul, tipe, link) {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modalJudul').value = judul;
    document.getElementById('modalTipe').value = tipe;
    document.getElementById('modalLink').value = link;
  }

  function closeModal() {
    document.getElementById('modal').classList.add('hidden');
  }

  function simpanEdit() {
    closeModal();
    showSuccess('Iklan berhasil diupdate!');
  }

  function hapusIklan(btn) {
    if (confirm('⚠️ Yakin ingin menghapus iklan ini?')) {
      btn.closest('tr').remove();
      showSuccess('Iklan berhasil dihapus!');
    }
  }

  function showSuccess(msg) {
    document.getElementById('successMessage').textContent = msg;
    document.getElementById('successModal').classList.remove('hidden');
  }

  function tutupSuccess() {
    document.getElementById('successModal').classList.add('hidden');
  }

  document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
</script>

</body>
</html>

@endsection