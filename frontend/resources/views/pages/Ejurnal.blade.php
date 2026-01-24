@extends('layout.app')

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
            background: #a5b4fc;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #818cf8;
        }
        
        /* Sticky header untuk tabel */
        .sticky-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent flex items-center gap-3">
                <i class="fas fa-book text-indigo-600"></i>
                Kelola E-Jurnal
            </h1>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Tambah Jurnal -->
            <div class="lg:col-span-4 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 flex flex-col">
                <h2 class="text-xl font-bold text-gray-800 mb-5 pb-3 border-b-2 border-indigo-100">
                    Tambah Jurnal
                </h2>
                <div class="space-y-4 flex-1 flex flex-col">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Judul</label>
                        <input 
                            type="text" 
                            id="inputJudul" 
                            placeholder="Masukkan judul jurnal" 
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea 
                            id="inputDeskripsi" 
                            rows="4"
                            placeholder="Masukkan deskripsi jurnal" 
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm resize-none"
                        ></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pengguna</label>
                        <input 
                            type="text" 
                            id="inputUserName" 
                            placeholder="Masukkan nama user" 
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar</label>
                        <div class="flex items-center gap-3">
                            <input 
                                type="file" 
                                id="inputGambar" 
                                accept="image/*"
                                class="hidden"
                                onchange="previewGambar(event)"
                            >
                            <label for="inputGambar" class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-lg text-sm text-gray-500 cursor-pointer hover:border-indigo-500 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-image"></i>
                                <span id="namaFile">Pilih gambar</span>
                            </label>
                        </div>
                        <div id="previewContainer" class="hidden mt-3">
                            <img id="previewImage" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                        </div>
                    </div>
                    
                    <div class="flex-1"></div>
                    
                    <button 
                        onclick="tambahJurnal()" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-2.5 rounded-lg hover:from-indigo-700 hover:to-purple-700 transform hover:scale-[1.02] transition-all duration-200 shadow-md hover:shadow-lg text-sm mt-4"
                    >
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                </div>
            </div>

            <!-- Tabel Jurnal -->
            <div class="lg:col-span-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 flex flex-col" style="height: 580px;">
                <h2 class="text-xl font-bold text-gray-800 mb-5 pb-3 border-b-2 border-indigo-100 flex-shrink-0">
                    Tabel Jurnal
                </h2>
                <div class="overflow-x-auto overflow-y-scroll flex-1 scrollbar-thin scrollbar-thumb-indigo-300 scrollbar-track-gray-100">
                    <table class="w-full text-sm min-w-[700px]">
                        <thead class="sticky-header">
                            <tr class="border-b-2 border-gray-100">
                                <th class="text-center py-3 px-2 font-semibold text-gray-700 w-12">No</th>
                                <th class="text-left py-3 px-3 font-semibold text-gray-700 min-w-[120px]">Judul</th>
                                <th class="text-left py-3 px-3 font-semibold text-gray-700 min-w-[160px]">Deskripsi</th>
                                <th class="text-left py-3 px-3 font-semibold text-gray-700 min-w-[100px]">Nama Pengguna</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-700 w-20">Gambar</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-700 w-16">Edit</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-700 w-16">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="tabelJurnal">
                            <tr class="border-b border-gray-50 hover:bg-indigo-50 transition-colors" id="jurnal-1">
                                <td class="py-3 px-2 font-medium text-gray-800 text-center">1</td>
                                <td class="py-3 px-3 font-medium text-gray-800">Penelitian AI</td>
                                <td class="py-3 px-3 text-gray-600 text-xs">
                                    <div class="mb-2">• Studi implementasi AI dalam pendidikan modern</div>
                                    <div class="mb-2">• Analisis penggunaan machine learning untuk personalisasi pembelajaran</div>
                                    <div>• Evaluasi efektivitas chatbot AI sebagai asisten pengajaran</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="text-gray-700 text-sm font-medium">
                                        John Doe
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/6366f1/ffffff?text=AI+Education')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/8b5cf6/ffffff?text=AI+Learning')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/a855f7/ffffff?text=AI+Chatbot')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="editJurnal(1, 'Penelitian AI', 'Studi implementasi AI dalam pendidikan modern. Analisis penggunaan machine learning untuk personalisasi pembelajaran. Evaluasi efektivitas chatbot AI sebagai asisten pengajaran.', 'John Doe', 'https://via.placeholder.com/400x300/6366f1/ffffff?text=AI+Education')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="hapusJurnal(1)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-indigo-50 transition-colors" id="jurnal-2">
                                <td class="py-3 px-2 font-medium text-gray-800 text-center">2</td>
                                <td class="py-3 px-3 font-medium text-gray-800">Machine Learning</td>
                                <td class="py-3 px-3 text-gray-600 text-xs">
                                    <div class="mb-2">• Analisis prediktif menggunakan algoritma ML</div>
                                    <div class="mb-2">• Penerapan neural network untuk pattern recognition</div>
                                    <div>• Optimasi model dengan deep learning techniques</div>
                                </td>
                                <td class="py-3 px-3">
                                   <span class="text-gray-700 text-sm font-medium">
                                       Jane Smith 
                                   </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/8b5cf6/ffffff?text=ML+Prediction')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/a855f7/ffffff?text=Neural+Network')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="editJurnal(2, 'Machine Learning', 'Analisis prediktif menggunakan algoritma ML. Penerapan neural network untuk pattern recognition. Optimasi model dengan deep learning techniques.', 'Jane Smith', 'https://via.placeholder.com/400x300/8b5cf6/ffffff?text=ML+Prediction')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="hapusJurnal(2)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-indigo-50 transition-colors" id="jurnal-3">
                                <td class="py-3 px-2 font-medium text-gray-800 text-center">3</td>
                                <td class="py-3 px-3 font-medium text-gray-800">Data Science</td>
                                <td class="py-3 px-3 text-gray-600 text-xs">
                                    <div class="mb-2">• Pengolahan big data untuk analisis bisnis</div>
                                    <div class="mb-2">• Visualisasi data dengan tools modern seperti Tableau</div>
                                    <div>• Implementasi data mining untuk customer insights</div>
                                </td>
                                <td class="py-3 px-3">
                                   <span class="text-gray-700 text-sm font-medium">
                                       Bob Johnson
                                   </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/ec4899/ffffff?text=Big+Data')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/f472b6/ffffff?text=Data+Visualization')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/fb7185/ffffff?text=Data+Mining')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="editJurnal(3, 'Data Science', 'Pengolahan big data untuk analisis bisnis. Visualisasi data dengan tools modern seperti Tableau. Implementasi data mining untuk customer insights.', 'Bob Johnson', 'https://via.placeholder.com/400x300/ec4899/ffffff?text=Big+Data')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="hapusJurnal(3)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-indigo-50 transition-colors" id="jurnal-4">
                                <td class="py-3 px-2 font-medium text-gray-800 text-center">4</td>
                                <td class="py-3 px-3 font-medium text-gray-800">Cloud Computing</td>
                                <td class="py-3 px-3 text-gray-600 text-xs">
                                    <div class="mb-2">• Implementasi cloud untuk efisiensi infrastruktur</div>
                                    <div class="mb-2">• Migrasi sistem legacy ke cloud platform AWS</div>
                                    <div>• Keamanan data dalam cloud storage</div>
                                </td>
                                <td class="py-3 px-3">
                                   <span class="text-gray-700 text-sm font-medium">
                                        Alice Bown
                                   </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/14b8a6/ffffff?text=Cloud+Infrastructure')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/06b6d4/ffffff?text=AWS+Migration')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="editJurnal(4, 'Cloud Computing', 'Implementasi cloud untuk efisiensi infrastruktur. Migrasi sistem legacy ke cloud platform AWS. Keamanan data dalam cloud storage.', 'Alice Brown', 'https://via.placeholder.com/400x300/14b8a6/ffffff?text=Cloud+Infrastructure')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="hapusJurnal(4)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-indigo-50 transition-colors" id="jurnal-5">
                                <td class="py-3 px-2 font-medium text-gray-800 text-center">5</td>
                                <td class="py-3 px-3 font-medium text-gray-800">Cybersecurity</td>
                                <td class="py-3 px-3 text-gray-600 text-xs">
                                    <div class="mb-2">• Strategi keamanan siber di era digital</div>
                                    <div class="mb-2">• Teknik penetration testing untuk menguji sistem</div>
                                    <div>• Implementasi zero trust architecture</div>
                                </td>
                                <td class="py-3 px-3">
                                   <span class="text-gray-700 text-sm font-medium">
                                        David Wilson
                                   </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/f59e0b/ffffff?text=Cyber+Security')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/f97316/ffffff?text=Penetration+Test')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/ea580c/ffffff?text=Zero+Trust')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="editJurnal(5, 'Cybersecurity', 'Strategi keamanan siber di era digital. Teknik penetration testing untuk menguji sistem. Implementasi zero trust architecture.', 'David Wilson', 'https://via.placeholder.com/400x300/f59e0b/ffffff?text=Cyber+Security')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="hapusJurnal(5)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-indigo-50 transition-colors" id="jurnal-6">
                                <td class="py-3 px-2 font-medium text-gray-800 text-center">6</td>
                                <td class="py-3 px-3 font-medium text-gray-800">IoT Technology</td>
                                <td class="py-3 px-3 text-gray-600 text-xs">
                                    <div class="mb-2">• Penerapan Internet of Things dalam industri</div>
                                    <div class="mb-2">• Smart home automation dengan IoT sensors</div>
                                    <div>• Industrial IoT untuk monitoring produksi</div>
                                </td>
                                <td class="py-3 px-3">
                                   <span class="text-gray-700 text-sm font-medium">
                                        Sarah Lee
                                   </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/10b981/ffffff?text=IoT+Industry')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/059669/ffffff?text=Smart+Home')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="editJurnal(6, 'IoT Technology', 'Penerapan Internet of Things dalam industri. Smart home automation dengan IoT sensors. Industrial IoT untuk monitoring produksi.', 'Sarah Lee', 'https://via.placeholder.com/400x300/10b981/ffffff?text=IoT+Industry')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="hapusJurnal(6)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-indigo-50 transition-colors" id="jurnal-7">
                                <td class="py-3 px-2 font-medium text-gray-800 text-center">7</td>
                                <td class="py-3 px-3 font-medium text-gray-800">Blockchain</td>
                                <td class="py-3 px-3 text-gray-600 text-xs">
                                    <div class="mb-2">• Implementasi blockchain dalam sistem keuangan</div>
                                    <div class="mb-2">• Smart contracts untuk otomasi transaksi</div>
                                    <div>• Cryptocurrency dan teknologi distributed ledger</div>
                                </td>
                                <td class="py-3 px-3">
                                   <span class="text-gray-700 text-sm font-medium">
                                        Michael Chen
                                   </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/06b6d4/ffffff?text=Blockchain+Finance')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/0891b2/ffffff?text=Smart+Contracts')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/0e7490/ffffff?text=Cryptocurrency')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="editJurnal(7, 'Blockchain', 'Implementasi blockchain dalam sistem keuangan. Smart contracts untuk otomasi transaksi. Cryptocurrency dan teknologi distributed ledger.', 'Michael Chen', 'https://via.placeholder.com/400x300/06b6d4/ffffff?text=Blockchain+Finance')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="hapusJurnal(7)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-indigo-50 transition-colors" id="jurnal-8">
                                <td class="py-3 px-2 font-medium text-gray-800 text-center">8</td>
                                <td class="py-3 px-3 font-medium text-gray-800">Quantum Computing</td>
                                <td class="py-3 px-3 text-gray-600 text-xs">
                                    <div class="mb-2">• Masa depan komputasi dengan teknologi quantum</div>
                                    <div class="mb-2">• Quantum algorithms untuk problem solving kompleks</div>
                                    <div>• Aplikasi quantum computing dalam kriptografi</div>
                                </td>
                                <td class="py-3 px-3">
                                   <span class="text-gray-700 text-sm font-medium">
                                        Emma Davis
                                   </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <div class="flex gap-1 justify-center">
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/a855f7/ffffff?text=Quantum+Computing')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button onclick="lihatGambar('https://via.placeholder.com/400x300/9333ea/ffffff?text=Quantum+Algorithms')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="editJurnal(8, 'Quantum Computing', 'Masa depan komputasi dengan teknologi quantum. Quantum algorithms untuk problem solving kompleks. Aplikasi quantum computing dalam kriptografi.', 'Emma Davis', 'https://via.placeholder.com/400x300/a855f7/ffffff?text=Quantum+Computing')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button onclick="hapusJurnal(8)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jurnal -->
    <div id="modalEditJurnal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-5 rounded-t-2xl sticky top-0">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Edit Jurnal
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" id="editIdJurnal">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Judul</label>
                    <input type="text" id="editJudul" 
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="editDeskripsi" rows="4"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm resize-none"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pengguna</label>
                    <input type="text" id="editUserName" 
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar</label>
                    <div class="flex items-center gap-3">
                        <input 
                            type="file" 
                            id="editInputGambar" 
                            accept="image/*"
                            class="hidden"
                            onchange="previewEditGambar(event)"
                        >
                        <label for="editInputGambar" class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-lg text-sm text-gray-500 cursor-pointer hover:border-indigo-500 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-image"></i>
                            <span>Ganti gambar</span>
                        </label>
                    </div>
                    <div id="editPreviewContainer" class="mt-3">
                        <img id="editPreviewImage" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                    </div>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button onclick="simpanEditJurnal()" 
                        class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-2.5 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md text-sm">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button onclick="tutupModalEditJurnal()" 
                        class="flex-1 bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg hover:bg-gray-300 transition-all text-sm">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Gambar -->
    <div id="modalLihatGambar" class="hidden fixed inset-0 bg-black bg-opacity-75 backdrop-blur-sm flex items-center justify-center z-50 p-4" onclick="tutupModalLihatGambar()">
        <div class="max-w-4xl w-full">
            <div class="bg-white rounded-2xl p-4">
                <img id="gambarModal" class="w-full h-auto rounded-lg">
            </div>
        </div>
    </div>

    <script>
        let jurnalCounter = 9;
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
            const userName = document.getElementById('inputNamaPengguna').value.trim();
            
            if (judul === '' || deskripsi === '' || NamaPengguna === '') {
                alert('Semua field harus diisi!');
                return;
            }

            if (!currentImage) {
                alert('Silakan pilih gambar!');
                return;
            }

            const tabel = document.getElementById('tabelJurnal');
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-50 hover:bg-indigo-50 transition-colors';
            row.id = `jurnal-${jurnalCounter}`;
            row.innerHTML = `
                <td class="py-3 px-2 font-medium text-gray-800 text-center">${jurnalCounter}</td>
                <td class="py-3 px-3 font-medium text-gray-800">${judul}</td>
                <td class="py-3 px-3 text-gray-600 text-xs">${deskripsi}</td>
                <td class="py-3 px-3">
                    <span class="bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 px-2 py-1 rounded-full text-xs font-semibold">${NamaPengguna}</span>
                </td>
                <td class="py-3 px-2 text-center">
                    <button onclick="lihatGambar('${currentImage}')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                        <i class="fas fa-image"></i>
                    </button>
                </td>
                <td class="py-3 px-2 text-center">
                    <button onclick="editJurnal(${jurnalCounter}, '${judul}', '${deskripsi}', '${namapengguna}', '${currentImage}')" class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 p-2 rounded-lg transition-all">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
                <td class="py-3 px-2 text-center">
                    <button onclick="hapusJurnal(${jurnalCounter})" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tabel.appendChild(row);

            // Reset form
            document.getElementById('inputJudul').value = '';
            document.getElementById('inputDeskripsi').value = '';
            document.getElementById('inputNamaPengguna').value = '';
            document.getElementById('inputGambar').value = '';
            document.getElementById('namaFile').textContent = 'Pilih gambar';
            document.getElementById('previewContainer').classList.add('hidden');
            currentImage = null;
            jurnalCounter++;
        }

        function editJurnal(id, judul, deskripsi, NamaPengguna, gambar) {
            currentEditId = id;
            document.getElementById('editIdJurnal').value = id;
            document.getElementById('editJudul').value = judul;
            document.getElementById('editDeskripsi').value = deskripsi;
            document.getElementById('editNamaPengguna').value = NamaPengguna;
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
            const userName = document.getElementById('editNamaPengguna').value.trim();
            
            if (judul === '' || deskripsi === '' || NamaPengguna === '') {
                alert('Semua field harus diisi!');
                return;
            }

            const row = document.getElementById(`jurnal-${currentEditId}`);
            if (row) {
                row.cells[1].textContent = judul;
                row.cells[2].textContent = deskripsi;
                row.cells[2].className = 'py-3 px-3 text-gray-600 text-xs';
                row.cells[3].innerHTML = `<span class="bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 px-2 py-1 rounded-full text-xs font-semibold">${userName}</span>`;
                row.cells[4].querySelector('button').onclick = () => lihatGambar(editgambar);
                row.cells[5].querySelector('button').onclick = () => editJurnal(currentEditId, judul, deskripsi, Namapengguna, editgambar);
            }

            tutupModalEditJurnal();
        }

        function hapusJurnal(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jurnal ini?')) {
                const row = document.getElementById(`jurnal-${id}`);
                if (row) {
                    row.remove();
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