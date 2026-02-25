@extends('layout.App')

@section('title', 'Iklan - Portal Blog')

@section('content')
<div class="min-h-screen bg-white p-6">

    <div class="mb-8">
        <h1 class="flex items-center gap-4 text-4xl font-bold text-black">
            <span class="p-4 bg-white rounded-xl shadow">
                <i class="fas fa-ad text-[#4988C4] text-3xl"></i>
            </span>
            Kelola Iklan
        </h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FORM TAMBAH -->
        {{-- FIX: field disesuaikan dengan BE: name, thumbnail (file WAJIB), position, priority, link, status --}}
        <div class="bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-bold text-[#4988C4] mb-6">Tambah Iklan</h2>

            <form action="{{ route('iklan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Iklan</label>
                    <input name="name" class="w-full border rounded-xl p-3"
                           placeholder="Nama iklan" required>
                </div>

                {{-- BE field: position (bukan tipe) --}}
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Posisi</label>
                    <select name="position" class="w-full border rounded-xl p-3" required>
                        <option value="">Pilih Posisi</option>
                        <option value="slide_1x1">1:1 Slide</option>
                        <option value="right_3x1">3:1 Kanan</option>
                        <option value="left_3x1">3:1 Kiri</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Prioritas</label>
                    <input type="number" name="priority" class="w-full border rounded-xl p-3"
                           placeholder="1" min="1" value="1">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Link (optional)</label>
                    <input name="link" class="w-full border rounded-xl p-3" placeholder="https://...">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border rounded-xl p-3" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                {{-- BE: thumbnail WAJIB untuk store --}}
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar (Wajib)</label>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="w-full border rounded-xl p-3" required>
                </div>

                <button class="mt-4 w-full bg-[#4988C4] text-white py-3 rounded-xl font-bold">
                    Upload Iklan
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-bold text-[#4988C4] mb-4">Daftar Iklan</h2>

            <div class="overflow-auto border rounded-xl">
                <table class="w-full">
                    <thead class="bg-[#4988C4] text-white">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Posisi</th>
                            <th class="p-3">Prioritas</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Gambar</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($iklans as $iklan)
                        <tr class="border-b">
                            <td class="p-3 text-center">{{ $loop->iteration }}</td>

                            {{-- FIX: BE return 'name' bukan 'nama' atau 'judul' --}}
                            <td class="p-3">{{ $iklan['name'] ?? '-' }}</td>

                            {{-- FIX: BE return 'position' bukan 'tipe' --}}
                            <td class="p-3 text-center">
                                @php
                                    $positions = [
                                        'slide_1x1' => '1:1 Slide',
                                        'right_3x1' => '3:1 Kanan',
                                        'left_3x1'  => '3:1 Kiri',
                                    ];
                                @endphp

                                <p>{{ $positions[$iklan['position']] ?? '-' }}</p>
                            </td>

                            <td class="p-3 text-center">{{ $iklan['priority'] ?? '-' }}</td>

                            <td class="p-3 text-center">
                                @if(($iklan['status'] ?? '') === 'active')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Active</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">Inactive</span>
                                @endif
                            </td>

                            {{-- FIX: Gabungkan MEDIA_BASE_URL + thumbnail path dari BE --}}
                            <td class="p-4">
                              @php
                                $thumbUrl = null;

                                if (!empty($iklan['thumbnail'])) {

                                    if (str_starts_with($iklan['thumbnail'], 'http')) {
                                        $thumbUrl = $iklan['thumbnail'];

                                    } else {
                                        $thumbUrl = rtrim(env('MEDIA'), '/') 
                                            . '/storage/' 
                                            . ltrim($iklan['thumbnail'], '/');
                                    }
                                }
                                @endphp

                               @if($thumbUrl)
                                <img src="{{ $thumbUrl }}"
                                    class="w-[80px] h-[60px] rounded-lg object-cover shadow"
                                    loading="lazy">
                            @else
                                <div class="w-[80px] h-[60px] rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            </td>

                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('iklan.edit', $iklan['id']) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>

                                    <form action="{{ route('iklan.destroy', $iklan['id']) }}"
                                          method="POST"
                                          onsubmit="return .'popuphapus.blade.php'">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500">
                                Data iklan belum tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection