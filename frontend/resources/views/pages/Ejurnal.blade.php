@extends('layout.App')

@section('title', 'E-Jurnal - Portal Blog')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola E-Jurnal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #ffffff;
            min-height: 100vh;
        }
        
        /* Custom scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4988C4;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #3a6ea0;
        }
        
        /* Sticky header untuk tabel */
        .sticky-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }

        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(73, 136, 196, 0.3);
        }
    </style>
</head>
<body class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-5xl font-bold flex items-center gap-4 drop-shadow-lg" style="color: #000000;">
                <div class="p-4 rounded-2xl shadow-2xl" style="background-color: #ffffff; border: 2px solid #4988C4;">
                    <i class="fas fa-book text-4xl" style="color: #4988C4;"></i>
                </div>
                Kelola E-Jurnal
            </h1>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Tambah Jurnal -->
            <div class="lg:col-span-4 rounded-2xl shadow-xl card-hover p-8 flex flex-col overflow-hidden" style="height: 650px; background-color: #ffffff; border: 2px solid #4988C4;">
                <h2 class="text-3xl font-bold mb-6 pb-4" style="color: #4988C4; border-bottom: 3px solid #4988C4;">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Jurnal
                </h2>
                <div class="space-y-5 flex-1 flex flex-col">
                    <div>
                        <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
                            <i class="fas fa-heading"></i>
                            Judul
                        </label>
                        <input 
                            type="text" 
                            id="inputJudul" 
                            placeholder="Masukkan judul jurnal" 
                            class="w-full px-4 py-3 rounded-xl outline-none transition-all text-base"
                            style="border: 2px solid #4988C4; background-color: #ffffff;"
                            onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
                            onblur="this.style.boxShadow='none'"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
                            <i class="fas fa-align-left"></i>
                            Deskripsi
                        </label>
                        <textarea 
                            id="inputDeskripsi" 
                            rows="3"
                            placeholder="Masukkan deskripsi jurnal" 
                            class="w-full px-4 py-3 rounded-xl outline-none transition-all text-base resize-none"
                            style="border: 2px solid #4988C4; background-color: #ffffff;"
                            onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
                            onblur="this.style.boxShadow='none'"
                        ></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
                            <i class="fas fa-user"></i>
                            Nama Pengguna
                        </label>
                        <input 
                            type="text" 
                            id="inputUserName" 
                            placeholder="Masukkan nama user" 
                            class="w-full px-4 py-3 rounded-xl outline-none transition-all text-base"
                            style="border: 2px solid #4988C4; background-color: #ffffff;"
                            onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
                            onblur="this.style.boxShadow='none'"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
                            <i class="fas fa-image"></i>
                            Gambar
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
                        <div id="previewContainer" class="hidden mt-3">
                            <img id="previewImage" class="w-full h-32 object-cover rounded-xl shadow-lg" style="border: 2px solid #4988C4;">
                        </div>
                    </div>
                    
                    <div class="flex-1"></div>
                    
                    <button 
                        onclick="tambahJurnal()" 
                        class="w-full text-white font-bold py-3 rounded-xl transform hover:scale-105 transition-all shadow-lg hover:shadow-2xl text-base"
                        style="background-color: #4988C4;"
                        onmouseover="this.style.backgroundColor='#3a6ea0'"
                        onmouseout="this.style.backgroundColor='#4988C4'"
                    >
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                </div>
            </div>

            <!-- Tabel Jurnal -->
            <div class="lg:col-span-8 rounded-2xl shadow-xl card-hover p-8 flex flex-col" style="height: 650px; background-color: #ffffff; border: 2px solid #4988C4;">
                <h2 class="text-3xl font-bold mb-6 pb-4 flex-shrink-0" style="color: #4988C4; border-bottom: 3px solid #4988C4;">
                    <i class="fas fa-table mr-2"></i>
                    Tabel Jurnal
                </h2>
                <div class="overflow-x-auto overflow-y-scroll flex-1 scrollbar-thin rounded-xl" style="border: 2px solid #4988C4;">
                    <table class="w-full min-w-[900px]">
                        <thead class="sticky-header" style="background-color: #4988C4;">
                            <tr>
                                <th class="text-center py-4 px-4 font-bold text-white text-base">No</th>
                                <th class="text-left py-4 px-4 font-bold text-white text-base min-w-[140px]">Judul</th>
                                <th class="text-left py-4 px-4 font-bold text-white text-base min-w-[200px]">Deskripsi</th>
                                <th class="text-left py-4 px-4 font-bold text-white text-base min-w-[120px]">Nama Pengguna</th>
                                <th class="text-center py-4 px-4 font-bold text-white text-base">Gambar</th>
                                <th class="text-center py-4 px-4 font-bold text-white text-base">Edit</th>
                                <th class="text-center py-4 px-4 font-bold text-white text-base">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="tabelJurnal" style="background-color: #ffffff;">
                            <tr class="transition-colors" id="jurnal-1" style="background-color: #ffffff; border-bottom: 1px solid #4988C4;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
                                <td class="py-4 px-4 font-semibold text-center text-base" style="color: #4988C4;">1</td>
                                <td class="py-4 px-4 font-semibold text-base" style="color: #4988C4;">Penelitian AI</td>
                                <td class="py-4 px-4 text-sm" style="color: #4988C4;">
                                    <div class="mb-2">• Studi implementasi AI dalam pendidikan modern</div>
                                    <div class="mb-2">• Analisis penggunaan machine learning untuk personalisasi pembelajaran</div>
                                    <div>• Evaluasi efektivitas chatbot AI sebagai asisten pengajaran</div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-semibold" style="color: #4988C4;">
                                        John Doe
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=AI+Education')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=AI+Learning')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=AI+Chatbot')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button onclick="editJurnal(1, 'Penelitian AI', 'Studi implementasi AI dalam pendidikan modern. Analisis penggunaan machine learning untuk personalisasi pembelajaran. Evaluasi efektivitas chatbot AI sebagai asisten pengajaran.', 'John Doe', 'https://via.placeholder.com/400x300/4988C4/ffffff?text=AI+Education')" class="text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button onclick="hapusJurnal(1)" class="text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all" style="background-color: #dc2626;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="transition-colors" id="jurnal-2" style="background-color: #ffffff; border-bottom: 1px solid #4988C4;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
                                <td class="py-4 px-4 font-semibold text-center text-base" style="color: #4988C4;">2</td>
                                <td class="py-4 px-4 font-semibold text-base" style="color: #4988C4;">Machine Learning</td>
                                <td class="py-4 px-4 text-sm" style="color: #4988C4;">
                                    <div class="mb-2">• Analisis prediktif menggunakan algoritma ML</div>
                                    <div class="mb-2">• Penerapan neural network untuk pattern recognition</div>
                                    <div>• Optimasi model dengan deep learning techniques</div>
                                </td>
                                <td class="py-4 px-4">
                                   <span class="text-sm font-semibold" style="color: #4988C4;">
                                       Jane Smith 
                                   </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=ML+Prediction')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=Neural+Network')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button onclick="editJurnal(2, 'Machine Learning', 'Analisis prediktif menggunakan algoritma ML. Penerapan neural network untuk pattern recognition. Optimasi model dengan deep learning techniques.', 'Jane Smith', 'https://via.placeholder.com/400x300/4988C4/ffffff?text=ML+Prediction')" class="text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button onclick="hapusJurnal(2)" class="text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all" style="background-color: #dc2626;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="transition-colors" id="jurnal-3" style="background-color: #ffffff; border-bottom: 1px solid #4988C4;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
                                <td class="py-4 px-4 font-semibold text-center text-base" style="color: #4988C4;">3</td>
                                <td class="py-4 px-4 font-semibold text-base" style="color: #4988C4;">Data Science</td>
                                <td class="py-4 px-4 text-sm" style="color: #4988C4;">
                                    <div class="mb-2">• Pengolahan big data untuk analisis bisnis</div>
                                    <div class="mb-2">• Visualisasi data dengan tools modern seperti Tableau</div>
                                    <div>• Implementasi data mining untuk customer insights</div>
                                </td>
                                <td class="py-4 px-4">
                                   <span class="text-sm font-semibold" style="color: #4988C4;">
                                       Bob Johnson
                                   </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=Big+Data')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=Data+Visualization')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/4988C4/ffffff?text=Data+Mining')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button onclick="editJurnal(3, 'Data Science', 'Pengolahan big data untuk analisis bisnis. Visualisasi data dengan tools modern seperti Tableau. Implementasi data mining untuk customer insights.', 'Bob Johnson', 'https://via.placeholder.com/400x300/4988C4/ffffff?text=Big+Data')" class="text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button onclick="hapusJurnal(3)" class="text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all" style="background-color: #dc2626;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
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

    <!-- Modal Edit Jurnal -->
    <div id="modalEditJurnal" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4" style="background-color: rgba(73, 136, 196, 0.6); backdrop-filter: blur(8px);">
        <div class="rounded-3xl shadow-2xl max-w-lg w-full transform transition-all max-h-[90vh] overflow-y-auto" style="background-color: #ffffff;">
            <div class="text-white p-6 rounded-t-3xl sticky top-0" style="background-color: #4988C4;">
                <h3 class="text-2xl font-bold flex items-center gap-3">
                    <i class="fas fa-edit"></i>
                    Edit Jurnal
                </h3>
            </div>
            <div class="p-8 space-y-5">
                <input type="hidden" id="editIdJurnal">
                
                <div>
                    <label class="block text-sm font-bold mb-3" style="color: #4988C4;">Judul</label>
                    <input type="text" id="editJudul" 
                        class="w-full px-4 py-3 rounded-xl outline-none transition-all text-base"
                        style="border: 2px solid #4988C4; background-color: #ffffff;"
                        onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
                        onblur="this.style.boxShadow='none'">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-3" style="color: #4988C4;">Deskripsi</label>
                    <textarea id="editDeskripsi" rows="4"
                        class="w-full px-4 py-3 rounded-xl outline-none transition-all text-base resize-none"
                        style="border: 2px solid #4988C4; background-color: #ffffff;"
                        onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
                        onblur="this.style.boxShadow='none'"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-3" style="color: #4988C4;">Nama Pengguna</label>
                    <input type="text" id="editUserName" 
                        class="w-full px-4 py-3 rounded-xl outline-none transition-all text-base"
                        style="border: 2px solid #4988C4; background-color: #ffffff;"
                        onfocus="this.style.boxShadow='0 0 0 4px rgba(73, 136, 196, 0.2)'"
                        onblur="this.style.boxShadow='none'">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-3" style="color: #4988C4;">Gambar</label>
                    <div class="flex items-center gap-3">
                        <input 
                            type="file" 
                            id="editInputGambar" 
                            accept="image/*"
                            class="hidden"
                            onchange="previewEditGambar(event)"
                        >
                        <label for="editInputGambar" class="flex-1 px-4 py-3 rounded-xl text-base cursor-pointer transition-all flex items-center justify-center gap-2 font-medium" style="border: 2px solid #4988C4; color: #4988C4; background-color: #ffffff;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
                            <i class="fas fa-image"></i>
                            <span>Ganti gambar</span>
                        </label>
                    </div>
                    <div id="editPreviewContainer" class="mt-4">
                        <img id="editPreviewImage" class="w-full h-40 object-cover rounded-xl shadow-lg" style="border: 2px solid #4988C4;">
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button onclick="simpanEditJurnal()" 
                        class="flex-1 text-white font-semibold py-3 rounded-xl transition-all shadow-md text-base"
                        style="background-color: #4988C4;"
                        onmouseover="this.style.backgroundColor='#3a6ea0'"
                        onmouseout="this.style.backgroundColor='#4988C4'">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button onclick="tutupModalEditJurnal()" 
                        class="flex-1 font-semibold py-3 rounded-xl transition-all text-base"
                        style="border: 2px solid #4988C4; color: #4988C4; background-color: #ffffff;"
                        onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'"
                        onmouseout="this.style.backgroundColor='#ffffff'">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Gambar -->
    <div id="modalLihatGambar" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4" style="background-color: rgba(73, 136, 196, 0.8); backdrop-filter: blur(8px);" onclick="tutupModalLihatGambar()">
        <div class="max-w-4xl w-full">
            <div class="rounded-3xl p-6" style="background-color: #ffffff;">
                <img id="gambarModal" class="w-full h-auto rounded-xl">
            </div>
        </div>
    </div>

    <script>
        let jurnalCounter = 4;
        let currentEditId = null;
        let currentImage = null;
        let editImage = null;

        function previewGambar(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('namaFile').textContent = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentImage = e.target.result;
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('previewContainer').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
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
            row.style.backgroundColor = '#ffffff';
            row.style.borderBottom = '1px solid #4988C4';
            row.onmouseover = function() { this.style.backgroundColor = 'rgba(73, 136, 196, 0.1)'; };
            row.onmouseout = function() { this.style.backgroundColor = '#ffffff'; };
            
            row.innerHTML = `
                <td class="py-4 px-4 font-semibold text-center text-base" style="color: #4988C4;">${jurnalCounter}</td>
                <td class="py-4 px-4 font-semibold text-base" style="color: #4988C4;">${judul}</td>
                <td class="py-4 px-4 text-sm" style="color: #4988C4;">${deskripsi}</td>
                <td class="py-4 px-4">
                    <span class="text-sm font-semibold" style="color: #4988C4;">${userName}</span>
                </td>
                <td class="py-4 px-4 text-center">
                    <button onclick="lihatGambar('${currentImage}')" class="text-white px-3 py-2 rounded-lg transition-all shadow-md" style="background-color: #4988C4;" onmouseover="this.style.backgroundColor='#3a6ea0'" onmouseout="this.style.backgroundColor='#4988C4'">
                        <i class="fas fa-image"></i>
                    </button>
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
            tabel.appendChild(row);

            // Reset form
            document.getElementById('inputJudul').value = '';
            document.getElementById('inputDeskripsi').value = '';
            document.getElementById('inputUserName').value = '';
            document.getElementById('inputGambar').value = '';
            document.getElementById('namaFile').textContent = 'Pilih gambar';
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
                row.cells[1].className = 'py-4 px-4 font-semibold text-base';
                row.cells[1].style.color = '#4988C4';
                
                row.cells[2].textContent = deskripsi;
                row.cells[2].className = 'py-4 px-4 text-sm';
                row.cells[2].style.color = '#4988C4';
                
                row.cells[3].innerHTML = `<span class="text-sm font-semibold" style="color: #4988C4;">${userName}</span>`;
                
                // Update gambar button
                const imgBtn = row.cells[4].querySelector('button');
                imgBtn.onclick = () => lihatGambar(editImage);
                
                // Update edit button
                const editBtn = row.cells[5].querySelector('button');
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
</body>
</html>

@endsection