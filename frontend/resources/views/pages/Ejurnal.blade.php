@extends('layout.App')

@section('title', 'E-Jurnal - Portal Blog')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ejurnal.css') }}">
@endpush

@section('content')

<div class="page-container">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <div class="title-icon-wrapper">
                <i class="fas fa-book title-icon"></i>
            </div>
            Kelola E-Jurnal
        </h1>
    </div>

    <!-- Grid Layout -->
    <div class="content-grid">
        <!-- Tambah Jurnal -->
        <div class="form-card card-hover">
            <div class="form-header">
                <h2 class="form-title">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Jurnal
                </h2>
            </div>
            
            <!-- Scrollable Form Area -->
            <div class="form-scrollable scrollbar-thin">
                <div class="form-fields">
                    <div class="form-group">
                        <label class="form-label label-with-icon">
                            <i class="fas fa-heading"></i>
                            Judul
                        </label>
                        <input 
                            type="text" 
                            id="inputJudul" 
                            placeholder="Masukkan judul jurnal" 
                            class="form-input"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label label-with-icon">
                            <i class="fas fa-align-left"></i>
                            Deskripsi
                        </label>
                        <textarea 
                            id="inputDeskripsi" 
                            rows="3"
                            placeholder="Masukkan deskripsi jurnal" 
                            class="form-textarea"
                        ></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label label-with-icon">
                            <i class="fas fa-user"></i>
                            Nama Pengguna
                        </label>
                        <input 
                            type="text" 
                            id="inputUserName" 
                            placeholder="Masukkan nama user" 
                            class="form-input"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label label-with-icon">
                            <i class="fas fa-image"></i>
                            Gambar
                        </label>
                        <div class="relative">
                            <input 
                                type="file" 
                                id="inputGambar" 
                                accept="image/*"
                                class="file-input-hidden"
                                onchange="previewGambar(event)"
                            >
                            <label id="labelPilihGambar" for="inputGambar" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                                <span>Pilih gambar</span>
                            </label>
                            <div id="previewContainer" class="preview-container hidden">
                                <div class="relative">
                                    <img id="previewImage" class="preview-image" alt="Preview">
                                    <button type="button" onclick="hapusGambar()" class="delete-preview-btn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fixed Button Area -->
            <div class="form-button-area">
                <button onclick="tambahJurnal()" class="btn-upload">
                    <i class="fas fa-upload mr-2"></i>Upload
                </button>
            </div>
        </div>

        <!-- Tabel Jurnal -->
        <div class="table-card card-hover">
            <h2 class="table-title">
                <i class="fas fa-table"></i>
                Tabel Jurnal
            </h2>
            <div class="table-wrapper scrollbar-thin">
                <table class="data-table">
                    <thead class="sticky-header">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-left min-w-140">Judul</th>
                            <th class="text-left min-w-200">Deskripsi</th>
                            <th class="text-left min-w-120">Nama Pengguna</th>
                            <th class="text-center">Gambar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelJurnal">
                        <tr class="transition-colors" id="jurnal-1">
                            <td class="text-center cell-number">1</td>
                            <td class="cell-title">Penelitian AI</td>
                            <td class="cell-description">
                                <div class="description-item">• Studi implementasi AI dalam pendidikan modern</div>
                                <div class="description-item">• Analisis penggunaan machine learning untuk personalisasi pembelajaran</div>
                                <div class="description-item">• Evaluasi efektivitas chatbot AI sebagai asisten pengajaran</div>
                            </td>
                            <td>
                                <span class="cell-username">John Doe</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=AI+Education')" class="btn-view-image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=AI+Learning')" class="btn-view-image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=AI+Chatbot')" class="btn-view-image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button onclick="editJurnal(1, 'Penelitian AI', 'Studi implementasi AI dalam pendidikan modern. Analisis penggunaan machine learning untuk personalisasi pembelajaran. Evaluasi efektivitas chatbot AI sebagai asisten pengajaran.', 'John Doe', 'https://via.placeholder.com/400x300/4988C4/ffffff?text=AI+Education')" class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="hapusJurnal(1)" class="btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="transition-colors" id="jurnal-2">
                            <td class="text-center cell-number">2</td>
                            <td class="cell-title">Machine Learning</td>
                            <td class="cell-description">
                                <div class="description-item">• Analisis prediktif menggunakan algoritma ML</div>
                                <div class="description-item">• Penerapan neural network untuk pattern recognition</div>
                                <div class="description-item">• Optimasi model dengan deep learning techniques</div>
                            </td>
                            <td>
                               <span class="cell-username">Jane Smith</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=ML+Prediction')" class="btn-view-image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=Neural+Network')" class="btn-view-image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button onclick="editJurnal(2, 'Machine Learning', 'Analisis prediktif menggunakan algoritma ML. Penerapan neural network untuk pattern recognition. Optimasi model dengan deep learning techniques.', 'Jane Smith', 'https://via.placeholder.com/400x300/4988C4/ffffff?text=ML+Prediction')" class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="hapusJurnal(2)" class="btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="transition-colors" id="jurnal-3">
                            <td class="text-center cell-number">3</td>
                            <td class="cell-title">Data Science</td>
                            <td class="cell-description">
                                <div class="description-item">• Pengolahan big data untuk analisis bisnis</div>
                                <div class="description-item">• Visualisasi data dengan tools modern seperti Tableau</div>
                                <div class="description-item">• Implementasi data mining untuk customer insights</div>
                            </td>
                            <td>
                               <span class="cell-username">Bob Johnson</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=Big+Data')" class="btn-view-image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=Data+Visualization')" class="btn-view-image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=Data+Mining')" class="btn-view-image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button onclick="editJurnal(3, 'Data Science', 'Pengolahan big data untuk analisis bisnis. Visualisasi data dengan tools modern seperti Tableau. Implementasi data mining untuk customer insights.', 'Bob Johnson', 'https://via.placeholder.com/400x300/4988C4/ffffff?text=Big+Data')" class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="hapusJurnal(3)" class="btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Jurnal -->
<div id="modalEditJurnal" class="modal-edit hidden">
    <div class="modal-content scrollbar-thin">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-edit"></i>
                Edit Jurnal
            </h3>
        </div>
        <div class="modal-body modal-fields">
            <input type="hidden" id="editIdJurnal">
            
            <div class="form-group">
                <label class="form-label">Judul</label>
                <input type="text" id="editJudul" class="modal-input">
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea id="editDeskripsi" rows="4" class="modal-textarea"></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nama Pengguna</label>
                <input type="text" id="editUserName" class="modal-input">
            </div>
            
            <div class="form-group">
                <label class="form-label">Gambar</label>
                <div class="modal-file-wrapper">
                    <input 
                        type="file" 
                        id="editInputGambar" 
                        accept="image/*"
                        class="file-input-hidden"
                        onchange="previewEditGambar(event)"
                    >
                    <label for="editInputGambar" class="modal-file-label">
                        <i class="fas fa-image"></i>
                        <span>Ganti gambar</span>
                    </label>
                </div>
                <div id="editPreviewContainer" class="modal-preview-container">
                    <img id="editPreviewImage" class="modal-preview-image" alt="Preview Edit">
                </div>
            </div>
            
            <div class="modal-actions">
                <button onclick="simpanEditJurnal()" class="btn-save">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <button onclick="tutupModalEditJurnal()" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Gambar -->
<div id="modalLihatGambar" class="modal-image-view hidden" onclick="tutupModalLihatGambar()">
    <div class="modal-image-container">
        <div class="modal-image-wrapper">
            <img id="gambarModal" class="modal-image" alt="Gambar Jurnal">
        </div>
    </div>
</div>

@push('scripts')
<script>
    let jurnalCounter = 4;
    let currentEditId = null;
    let currentImage = null;
    let editImage = null;

    function previewGambar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentImage = e.target.result;
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('labelPilihGambar').classList.add('hidden');
                document.getElementById('previewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function hapusGambar() {
        document.getElementById('inputGambar').value = '';
        document.getElementById('previewImage').src = '';
        document.getElementById('labelPilihGambar').classList.remove('hidden');
        document.getElementById('previewContainer').classList.add('hidden');
        currentImage = null;
    }

    function previewEditGambar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                editImage = e.target.result;
                document.getElementById('editPreviewImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function tambahJurnal() {
        const judul = document.getElementById('inputJudul').value.trim();
        const deskripsi = document.getElementById('inputDeskripsi').value.trim();
        const userName = document.getElementById('inputUserName').value.trim();
        
        if (judul === '' || deskripsi === '' || userName === '') {
            alert('Semua field harus diisi!');
            return;
        }

        if (!currentImage) {
            alert('Silakan pilih gambar!');
            return;
        }

        const tabel = document.getElementById('tabelJurnal');
        const row = document.createElement('tr');
        row.className = 'transition-colors';
        row.id = `jurnal-${jurnalCounter}`;
        
        row.innerHTML = `
            <td class="text-center cell-number">${jurnalCounter}</td>
            <td class="cell-title">${judul}</td>
            <td class="cell-description">${deskripsi}</td>
            <td>
                <span class="cell-username">${userName}</span>
            </td>
            <td class="text-center">
                <button onclick="lihatGambar('${currentImage}')" class="btn-view-image">
                    <i class="fas fa-image"></i>
                </button>
            </td>
            <td class="text-center">
                <div class="btn-group">
                    <button onclick="editJurnal(${jurnalCounter}, '${judul}', '${deskripsi}', '${userName}', '${currentImage}')" class="btn-edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="hapusJurnal(${jurnalCounter})" class="btn-delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tabel.appendChild(row);

        // Reset form
        document.getElementById('inputJudul').value = '';
        document.getElementById('inputDeskripsi').value = '';
        document.getElementById('inputUserName').value = '';
        document.getElementById('inputGambar').value = '';
        document.getElementById('labelPilihGambar').classList.remove('hidden');
        document.getElementById('previewContainer').classList.add('hidden');
        currentImage = null;
        jurnalCounter++;
    }

    function editJurnal(id, judul, deskripsi, userName, gambar) {
        currentEditId = id;
        document.getElementById('editIdJurnal').value = id;
        document.getElementById('editJudul').value = judul;
        document.getElementById('editDeskripsi').value = deskripsi;
        document.getElementById('editUserName').value = userName;
        document.getElementById('editPreviewImage').src = gambar;
        editImage = gambar;
        document.getElementById('modalEditJurnal').classList.remove('hidden');
    }

    function tutupModalEditJurnal() {
        document.getElementById('modalEditJurnal').classList.add('hidden');
        currentEditId = null;
        editImage = null;
    }

    function simpanEditJurnal() {
        const judul = document.getElementById('editJudul').value.trim();
        const deskripsi = document.getElementById('editDeskripsi').value.trim();
        const userName = document.getElementById('editUserName').value.trim();
        
        if (judul === '' || deskripsi === '' || userName === '') {
            alert('Semua field harus diisi!');
            return;
        }

        const row = document.getElementById(`jurnal-${currentEditId}`);
        if (row) {
            row.cells[1].textContent = judul;
            row.cells[1].className = 'cell-title';
            
            row.cells[2].textContent = deskripsi;
            row.cells[2].className = 'cell-description';
            
            row.cells[3].innerHTML = `<span class="cell-username">${userName}</span>`;
            
            // Update gambar button
            const imgBtn = row.cells[4].querySelector('button');
            imgBtn.onclick = () => lihatGambar(editImage);
            
            // Update edit button
            const editBtn = row.cells[5].querySelector('button:first-child');
            editBtn.onclick = () => editJurnal(currentEditId, judul, deskripsi, userName, editImage);
        }

        tutupModalEditJurnal();
    }

    function hapusJurnal(id) {
        if (confirm('Apakah Anda yakin ingin menghapus jurnal ini?')) {
            const row = document.getElementById(`jurnal-${id}`);
            if (row) {
                row.remove();
                
                // Update nomor urut
                const tbody = document.getElementById('tabelJurnal');
                const rows = tbody.getElementsByTagName('tr');
                for (let i = 0; i < rows.length; i++) {
                    rows[i].cells[0].textContent = i + 1;
                }
            }
        }
    }

    function lihatGambar(url) {
        document.getElementById('gambarModal').src = url;
        document.getElementById('modalLihatGambar').classList.remove('hidden');
    }

    function tutupModalLihatGambar() {
        document.getElementById('modalLihatGambar').classList.add('hidden');
    }

    // Tutup modal edit saat klik di luar
    document.getElementById('modalEditJurnal').addEventListener('click', function(e) {
        if (e.target === this) {
            tutupModalEditJurnal();
        }
    });
</script>
@endpush

@endsection