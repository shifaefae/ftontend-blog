@extends('layout.App')

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
      background: #ffffff;
      min-height: 100vh;
    }
    .card-hover {
      transition: all 0.3s ease;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(73, 136, 196, 0.3);
    }
    .gradient-border::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: #4988C4;
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
    <h1 class="text-5xl font-bold flex items-center gap-4 drop-shadow-lg" style="color: #000000;">
      <div class="p-4 rounded-2xl shadow-2xl" style="background-color: #ffffff;">
        <i class="fas fa-ad text-4xl" style="color: #4988C4;"></i>
      </div>
      Kelola Iklan
    </h1>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

    <!-- FORM TAMBAH IKLAN -->
    <div class="rounded-2xl shadow-xl card-hover p-8 flex flex-col relative gradient-border animate-slide" style="background-color: #ffffff;">
      <h2 class="text-3xl font-bold mb-6 flex items-center gap-3" style="color: #4988C4;">
        <i class="fas fa-plus-circle"></i>
        Tambah Iklan
      </h2>

      <form id="formTambah" class="flex flex-col flex-1 space-y-6">
        <div>
          <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
            <i class="fas fa-heading"></i>
            Judul Iklan
          </label>
          <input 
            type="text" 
            id="inputJudul"
            placeholder="Masukkan judul iklan" 
            class="w-full rounded-xl px-4 py-3 focus:ring-4 outline-none transition-all text-base" 
            style="border: 2px solid #4988C4; background-color: #ffffff;"
            onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
            onblur="this.style.boxShadow='none'"
          />
        </div>

        <div>
          <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
            <i class="fas fa-layer-group"></i>
            Tipe Iklan
          </label>
          <select 
            id="inputTipe"
            class="w-full rounded-xl px-4 py-3 focus:ring-4 outline-none transition-all text-base"
            style="border: 2px solid #4988C4; background-color: #ffffff;"
            onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
            onblur="this.style.boxShadow='none'">
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
          <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
            <i class="fas fa-link"></i>
            Link URL
          </label>
          <input 
            type="text" 
            id="inputLink"
            placeholder="https://example.com" 
            class="w-full rounded-xl px-4 py-3 focus:ring-4 outline-none transition-all text-base" 
            style="border: 2px solid #4988C4; background-color: #ffffff;"
            onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
            onblur="this.style.boxShadow='none'"
          />
        </div>

        <div>
          <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
            <i class="fas fa-image"></i>
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
            <label for="inputGambar" class="flex-1 px-4 py-3 rounded-xl text-base cursor-pointer transition-all flex items-center justify-center gap-2 font-medium" style="border: 2px solid #4988C4; color: #4988C4; background-color: #ffffff;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
              <i class="fas fa-cloud-upload-alt text-xl"></i>
              <span id="namaFile">Pilih gambar</span>
            </label>
          </div>
          <div id="previewContainer" class="hidden mt-4">
            <img id="previewImage" class="w-full h-48 object-cover rounded-xl shadow-lg" style="border: 2px solid #4988C4;">
          </div>
        </div>

        <div class="flex-1"></div>

        <button 
          type="button"
          onclick="tambahIklan()" 
          class="w-full text-white font-bold py-4 rounded-xl transform hover:scale-105 transition-all shadow-lg hover:shadow-2xl text-lg"
          style="background-color: #4988C4;"
          onmouseover="this.style.backgroundColor='#3a6ea0'"
          onmouseout="this.style.backgroundColor='#4988C4'"
        >
          <i class="fas fa-paper-plane mr-2"></i>Upload Iklan
        </button>
      </form>
    </div>

    <!-- TABEL IKLAN -->
    <div class="rounded-2xl shadow-xl card-hover p-8 lg:col-span-2 flex flex-col relative gradient-border animate-slide" style="animation-delay: 0.1s; background-color: #ffffff;">
      <h2 class="text-3xl font-bold mb-6 flex items-center gap-3" style="color: #4988C4;">
        <i class="fas fa-list"></i>
        Daftar Iklan
      </h2>

      <div class="overflow-y-auto max-h-[600px] rounded-xl shadow-inner" style="border: 2px solid #4988C4;">
        <table class="min-w-full">
          <thead class="text-white sticky top-0 shadow-lg" style="background-color: #4988C4;">
            <tr>
              <th class="px-6 py-4 font-bold text-base">No</th>
              <th class="px-6 py-4 font-bold text-left text-base">Judul</th>
              <th class="px-6 py-4 font-bold text-base">Tipe</th>
              <th class="px-6 py-4 font-bold text-base">Link</th>
              <th class="px-6 py-4 font-bold text-base">Gambar</th>
              <th class="px-6 py-4 font-bold text-base">Aksi</th>
            </tr>
          </thead>
          <tbody id="tabelIklan">
            <tr class="transition-all" style="background-color: #ffffff;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
              <td class="px-6 py-5 text-center font-semibold text-base" style="border-bottom: 1px solid #4988C4;">1</td>
              <td class="px-6 py-5 font-semibold text-base" style="border-bottom: 1px solid #4988C4; color: #4988C4;">Pemerintah Resmi Naikkan UMK 2026</td>
              <td class="px-6 py-5 text-center" style="border-bottom: 1px solid #4988C4;">
                <span class="text-sm font-bold" style="color: #4988C4;">3:1 Tengah</span>
              </td>
              <td class="px-6 py-5" style="border-bottom: 1px solid #4988C4;">
                <a href="https://berita.com/1" target="_blank" class="font-medium flex items-center gap-2 text-sm hover:underline" style="color: #4988C4;">
                  <i class="fas fa-external-link-alt"></i>
                  berita.com/1
                </a>
              </td>
              <td class="px-6 py-5" style="border-bottom: 1px solid #4988C4;">
                <img src="https://picsum.photos/seed/1/120/80" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all w-[140px] h-[90px]" />
              </td>
              <td class="px-6 py-5 text-center" style="border-bottom: 1px solid #4988C4;">
                <button onclick="editIklan(this, 'Pemerintah Resmi Naikkan UMK 2026', '3:1 Tengah', 'https://berita.com/1')" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-4 py-2 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="hapusIklan(this)" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr class="transition-all" style="background-color: #ffffff;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
              <td class="px-6 py-5 text-center font-semibold text-base" style="border-bottom: 1px solid #4988C4;">2</td>
              <td class="px-6 py-5 font-semibold text-base" style="border-bottom: 1px solid #4988C4; color: #4988C4;">Harga BBM Terbaru Berlaku Nasional</td>
              <td class="px-6 py-5 text-center" style="border-bottom: 1px solid #4988C4;">
                <span class="text-sm font-bold" style="color: #4988C4;">1:1 Slide</span>
              </td>
              <td class="px-6 py-5" style="border-bottom: 1px solid #4988C4;">
                <a href="https://berita.com/2" target="_blank" class="font-medium flex items-center gap-2 text-sm hover:underline" style="color: #4988C4;">
                  <i class="fas fa-external-link-alt"></i>
                  berita.com/2
                </a>
              </td>
              <td class="px-6 py-5" style="border-bottom: 1px solid #4988C4;">
                <img src="https://picsum.photos/seed/2/120/80" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all w-[140px] h-[90px]" />
              </td>
              <td class="px-6 py-5 text-center" style="border-bottom: 1px solid #4988C4;">
                <button onclick="editIklan(this, 'Harga BBM Terbaru Berlaku Nasional', '1:1 Slide', 'https://berita.com/2')" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-4 py-2 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="hapusIklan(this)" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr class="transition-all" style="background-color: #ffffff;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
              <td class="px-6 py-5 text-center font-semibold text-base" style="border-bottom: 1px solid #4988C4;">3</td>
              <td class="px-6 py-5 font-semibold text-base" style="border-bottom: 1px solid #4988C4; color: #4988C4;">Timnas Indonesia Lolos Piala Asia</td>
              <td class="px-6 py-5 text-center" style="border-bottom: 1px solid #4988C4;">
                <span class="text-sm font-bold" style="color: #4988C4;">3:1 Kanan</span>
              </td>
              <td class="px-6 py-5" style="border-bottom: 1px solid #4988C4;">
                <a href="https://berita.com/3" target="_blank" class="font-medium flex items-center gap-2 text-sm hover:underline" style="color: #4988C4;">
                  <i class="fas fa-external-link-alt"></i>
                  berita.com/3
                </a>
              </td>
              <td class="px-6 py-5" style="border-bottom: 1px solid #4988C4;">
                <img src="https://picsum.photos/seed/3/120/80" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all w-[140px] h-[90px]" />
              </td>
              <td class="px-6 py-5 text-center" style="border-bottom: 1px solid #4988C4;">
                <button onclick="editIklan(this, 'Timnas Indonesia Lolos Piala Asia', '3:1 Kanan', 'https://berita.com/3')" class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white px-4 py-2 rounded-lg font-semibold mr-2 shadow-md hover:shadow-lg transition-all">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="hapusIklan(this)" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
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
<div id="modal" class="fixed inset-0 hidden flex items-center justify-center z-50 p-4" style="background-color: rgba(73, 136, 196, 0.6); backdrop-filter: blur(8px);">
  <div class="rounded-3xl shadow-2xl w-full max-w-lg animate-slide" style="background-color: #ffffff;">
    <div class="text-white p-6 rounded-t-3xl" style="background-color: #4988C4;">
      <h3 class="text-2xl font-bold flex items-center gap-3">
        <i class="fas fa-edit"></i>
        Edit Iklan
      </h3>
    </div>

    <form class="p-8 space-y-5">
      <div>
        <label class="block text-sm font-bold mb-3" style="color: #4988C4;">Judul</label>
        <input id="modalJudul" type="text" class="w-full rounded-xl px-4 py-3 focus:ring-4 outline-none text-base" style="border: 2px solid #4988C4; background-color: #ffffff;" onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'" onblur="this.style.boxShadow='none'" />
      </div>

      <div>
        <label class="block text-sm font-bold mb-3" style="color: #4988C4;">Tipe Iklan</label>
        <select id="modalTipe" class="w-full rounded-xl px-4 py-3 focus:ring-4 outline-none text-base" style="border: 2px solid #4988C4; background-color: #ffffff;" onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'" onblur="this.style.boxShadow='none'">
          <option>1:1 Slide</option>
          <option>3:1 Kanan</option>
          <option>3:1 Kiri</option>
          <option>3:1 Tengah</option>
          <option>1:3 Atas</option>
          <option>1:3 Tengah</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-bold mb-3" style="color: #4988C4;">Link</label>
        <input id="modalLink" type="text" placeholder="https://example.com" class="w-full rounded-xl px-4 py-3 focus:ring-4 outline-none text-base" style="border: 2px solid #4988C4; background-color: #ffffff;" onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'" onblur="this.style.boxShadow='none'" />
      </div>

      <div>
        <label class="block text-sm font-bold mb-3" style="color: #4988C4;">Gambar Baru (opsional)</label>
        <input type="file" id="modalGambar" accept="image/*" class="w-full text-base rounded-xl px-4 py-3" style="border: 2px solid #4988C4; background-color: #ffffff;" />
      </div>

      <div class="pt-4 flex justify-end gap-3">
        <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-xl font-semibold transition-all text-base" style="border: 2px solid #4988C4; color: #4988C4; background-color: #ffffff;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
          <i class="fas fa-times mr-2"></i>Batal
        </button>
        <button type="button" onclick="simpanEdit()" class="px-6 py-3 rounded-xl text-white font-semibold shadow-lg transition-all text-base" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
          <i class="fas fa-check mr-2"></i>Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(73, 136, 196, 0.6); backdrop-filter: blur(8px);">
  <div class="rounded-3xl shadow-2xl max-w-md w-full m-4 text-center p-10" style="background-color: #ffffff;">
    <div class="text-7xl mb-6" style="color: #4988C4;">✓</div>
    <h3 class="text-2xl font-bold mb-2" id="successMessage" style="color: #4988C4;">Berhasil!</h3>
    <button onclick="tutupSuccess()" class="mt-6 text-white px-10 py-3 rounded-xl font-semibold hover:shadow-lg transition-all text-base" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
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

    const tr = document.createElement('tr');
    tr.className = 'transition-all';
    tr.style.backgroundColor = '#ffffff';
    tr.onmouseover = function() { this.style.backgroundColor = 'rgba(73, 136, 196, 0.1)'; };
    tr.onmouseout = function() { this.style.backgroundColor = '#ffffff'; };
    
    tr.innerHTML = `
      <td class="px-6 py-5 text-center font-semibold text-base" style="border-bottom: 1px solid #4988C4;">${rowCount}</td>
      <td class="px-6 py-5 font-semibold text-base" style="border-bottom: 1px solid #4988C4; color: #4988C4;">${judul}</td>
      <td class="px-6 py-5 text-center" style="border-bottom: 1px solid #4988C4;">
        <span class="text-sm font-bold" style="color: #4988C4;">${tipe}</span>
      </td>
      <td class="px-6 py-5" style="border-bottom: 1px solid #4988C4;">
        <a href="${link}" target="_blank" class="font-medium flex items-center gap-2 text-sm hover:underline" style="color: #4988C4;">
          <i class="fas fa-external-link-alt"></i>
          ${link.substring(0, 30)}...
        </a>
      </td>
      <td class="px-6 py-5" style="border-bottom: 1px solid #4988C4;">
        <img src="${URL.createObjectURL(gambar)}" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all w-[140px] h-[90px]" />
      </td>
      <td class="py-4 px-4 text-center">
          <button onclick="editJurnal(${jurnalCounter}, '${judul}', '${deskripsi}', '${userName}', '${currentImage}')" class="text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
              <i class="fas fa-edit"></i>
          </button>
      </td>
      <td class="py-4 px-4 text-center">
          <button onclick="hapusJurnal(${jurnalCounter})" class="text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all" style="background-color: #dc2626;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
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

  function editIklan(btn, judul, tipe, link) {
    currentRow = btn.closest('tr');
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modalJudul').value = judul;
    document.getElementById('modalTipe').value = tipe;
    document.getElementById('modalLink').value = link;
  }

  function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    currentRow = null;
  }

  function simpanEdit() {
    if (!currentRow) return;
    
    const judul = document.getElementById('modalJudul').value.trim();
    const tipe = document.getElementById('modalTipe').value;
    const link = document.getElementById('modalLink').value.trim();
    const gambarFile = document.getElementById('modalGambar').files[0];
    
    if (!judul || !tipe || !link) {
      alert('⚠️ Semua field harus diisi!');
      return;
    }
    
    // Update judul
    currentRow.cells[1].innerHTML = `<span class="font-semibold text-base" style="color: #4988C4;">${judul}</span>`;
    
    // Update tipe
    currentRow.cells[2].innerHTML = `<span class="text-sm font-bold" style="color: #4988C4;">${tipe}</span>`;
    
    // Update link
    currentRow.cells[3].innerHTML = `<a href="${link}" target="_blank" class="font-medium flex items-center gap-2 text-sm hover:underline" style="color: #4988C4;">
      <i class="fas fa-external-link-alt"></i>
      ${link.substring(0, 30)}...
    </a>`;
    
    // Update gambar jika ada file baru
    if (gambarFile) {
      const reader = new FileReader();
      reader.onload = function(e) {
        currentRow.cells[4].innerHTML = `<img src="${e.target.result}" class="rounded-lg object-cover shadow-md hover:shadow-xl transition-all w-[140px] h-[90px]" />`;
      };
      reader.readAsDataURL(gambarFile);
    }
    
    closeModal();
    showSuccess('Iklan berhasil diupdate!');
  }

  function hapusIklan(btn) {
    if (confirm('⚠️ Yakin ingin menghapus iklan ini?')) {
      const row = btn.closest('tr');
      row.remove();
      
      // Update nomor urut
      const tbody = document.getElementById('tabelIklan');
      const rows = tbody.getElementsByTagName('tr');
      for (let i = 0; i < rows.length; i++) {
        rows[i].cells[0].textContent = i + 1;
      }
      
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