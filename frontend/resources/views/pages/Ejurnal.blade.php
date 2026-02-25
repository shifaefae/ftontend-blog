@extends('layout.App')

@section('title', 'E-Jurnal - Portal Blog')

@section('content')

<div class="bg-white min-h-screen p-6">
<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="flex items-center gap-4 text-5xl font-bold drop-shadow-lg">
            <div class="p-4 rounded-xl border-2 border-[#4988C4] shadow-2xl bg-white">
                <i class="fas fa-book text-4xl text-[#4988C4]"></i>
            </div>
            Kelola E-Jurnal
        </h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- FORM TAMBAH -->
        <div class="lg:col-span-4 h-[650px] bg-white border-2 border-[#4988C4] rounded-xl shadow-xl flex flex-col">

            <div class="p-8 pb-4">
                <h2 class="text-3xl font-bold text-[#4988C4] border-b-4 border-[#4988C4] pb-4">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Jurnal
                </h2>
            </div>

            <form action="{{ route('ejurnal.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col">
                @csrf

                <div class="flex-1 overflow-y-auto px-8 space-y-5">

                    <div>
                        <label class="font-bold text-sm text-[#4988C4] mb-2 block">Judul</label>
                        <input name="title" type="text" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-[#4988C4] focus:ring-4 focus:ring-[#4988C4]/20">
                    </div>

                    <div>
                        <label class="font-bold text-sm text-[#4988C4] mb-2 block">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-3 rounded-xl border-2 border-[#4988C4] focus:ring-4 focus:ring-[#4988C4]/20"></textarea>
                    </div>

                    <div>
                        <label class="font-bold text-sm text-[#4988C4] mb-2 block">Status</label>
                        <select name="status"
                            class="w-full px-4 py-3 rounded-xl border-2 border-[#4988C4] focus:ring-4 focus:ring-[#4988C4]/20">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 font-bold text-sm text-[#4988C4] mb-2">
                            <i class="fas fa-image"></i> Gambar 
                        </label>

                        <input type="file" name="thumbnail" id="inputGambar" accept="image/*"
                            class="hidden" onchange="previewGambar(event)">

                        <label for="inputGambar"
                            id="labelPilihGambar"
                            class="block cursor-pointer text-center px-4 py-3 rounded-xl border-2 border-[#4988C4]
                                   text-[#4988C4] font-semibold hover:bg-[#4988C4]/10 transition">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>Pilih Gambar
                        </label>

                        <div id="previewContainer" class="hidden mt-3 relative">
                            <img id="previewImage"
                                 class="w-full h-48 object-contain rounded-xl border-2 border-[#4988C4] shadow-lg bg-gray-50">
                            <button type="button" onclick="hapusGambar()"
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600
                                       text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg">
                                ✕
                            </button>
                        </div>
                    </div>

                </div>

                <div class="p-8 pt-4">
                    <button type="submit"
                        class="w-full bg-[#4988C4] text-white font-bold py-3 rounded-xl shadow-lg hover:bg-[#3a6ea0] transition">
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                </div>
            </form>
        
        </div>

        <!-- TABLE -->
        <div class="lg:col-span-8 h-[650px] bg-white border-2 border-[#4988C4] rounded-xl shadow-xl p-8 flex flex-col">

            <div class="flex items-center justify-between border-b-4 border-[#4988C4] pb-4 mb-6">
                
                <h2 class="text-3xl font-bold text-[#4988C4] flex items-center gap-2">
                    <i class="fas fa-table"></i>
                    Tabel Jurnal
                </h2>

                <!-- SEARCH BAR (TERHUBUNG KE CONTROLLER & API) -->
                <form method="GET" action="{{ route('ejurnal.index') }}" class="relative w-64">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari jurnal..."
                        class="w-full pl-10 pr-4 py-2 rounded-xl border-2 border-[#4988C4]
                               focus:outline-none focus:ring-4 focus:ring-[#4988C4]/20">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#4988C4]"></i>
                </form>

            </div>

            <div class="flex-1 overflow-x-auto overflow-y-auto border-2 border-[#4988C4] rounded-xl">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-[#4988C4] text-white">
                        <tr>
                            <th class="p-4 text-center">No</th>
                            <th class="p-4 text-left">Judul</th>
                            <th class="p-4 text-left">Deskripsi</th>
                            <th class="p-4 text-left">User</th>
                            <th class="p-4 text-center">Gambar</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ejurnals as $index => $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 text-center">{{ $index + 1 }}</td>
                            <td class="p-4">{{ $item['title'] ?? '-' }}</td>
                            <td class="p-4">{{ $item['description'] ?? '-' }}</td>
                            <td class="p-4">{{ $item['user']['name'] ?? '-' }}</td>
                            <td class="p-4 text-center">
                                @if(!empty($item['thumbnail']))
                                    <img src="{{ env('MEDIA_BASE_URL') . $item['thumbnail'] }}"
                                         class="w-20 h-12 object-cover rounded mx-auto">
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if(($item['status'] ?? '') === 'published')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                        Published
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('ejurnal.destroy', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800 font-semibold">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-6 text-gray-500">
                                Data e-jurnal tidak ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
</div>

@endsection