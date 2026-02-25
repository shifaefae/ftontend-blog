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
        <div class="bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-bold text-[#4988C4] mb-6">Tambah Iklan</h2>

            <form action="{{ route('iklan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Iklan</label>
                    <input name="name" class="w-full border rounded-xl p-3"
                           placeholder="Nama iklan" required>
                </div>

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

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar (Wajib)</label>

                    <label for="thumbnail_input"
                           class="flex items-center gap-3 w-full border-2 border-dashed border-[#4988C4] rounded-xl p-3 cursor-pointer hover:bg-blue-50 transition">
                        <i class="fas fa-cloud-upload-alt text-[#4988C4] text-xl"></i>
                        <span id="thumbnail_label" class="text-gray-500 text-sm truncate">Pilih gambar...</span>
                    </label>

                    <input type="file" name="thumbnail" id="thumbnail_input" accept="image/*"
                           class="hidden" required
                           onchange="previewThumbnail(this, 'thumbnail_label', 'thumbnail_preview_wrap', 'thumbnail_preview')">

                    <div id="thumbnail_preview_wrap" class="hidden mt-3 border-2 border-[#4988C4] rounded-xl overflow-hidden">
                        <img id="thumbnail_preview" src="#" alt="Preview"
                             class="w-full max-h-48 object-cover">
                    </div>
                </div>

                <button class="mt-4 w-full bg-[#4988C4] text-white py-3 rounded-xl font-bold">
                    Upload Iklan
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-[#4988C4]">Daftar Iklan</h2>
                <div class="relative w-1/2">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" id="searchIklan" oninput="searchIklan()"
                           placeholder="Cari iklan..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition">
                </div>
            </div>

            <div class="overflow-auto border rounded-xl">
                <table class="w-full table-fixed text-base">
                    <colgroup>
                        <col class="w-10">
                        <col class="w-28">
                        <col class="w-24">
                        <col class="w-20">
                        <col class="w-20">
                        <col class="w-32">
                        <col class="w-24">
                        <col class="w-20">
                    </colgroup>
                    <thead class="bg-[#4988C4] text-white">
                        <tr>
                            <th class="p-3 text-center">No</th>
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-center">Posisi</th>
                            <th class="p-3 text-center">Prioritas</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Link</th>
                            <th class="p-3 text-center">Gambar</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bodyIklan">
                        @forelse ($iklans as $iklan)
                        <tr class="border-b align-middle">
                            <td class="p-3 text-center">{{ $loop->iteration }}</td>

                            <td class="p-3 truncate" title="{{ $iklan['name'] ?? '' }}">{{ $iklan['name'] ?? '-' }}</td>

                            <td class="p-3 text-center">
                                @php
                                    $positions = [
                                        'slide_1x1' => '1:1 Slide',
                                        'right_3x1' => '3:1 Kanan',
                                        'left_3x1'  => '3:1 Kiri',
                                    ];
                                @endphp
                                {{ $positions[$iklan['position']] ?? '-' }}
                            </td>

                            <td class="p-3 text-center">{{ $iklan['priority'] ?? '-' }}</td>

                            <td class="p-3 text-center">
                                @if(($iklan['status'] ?? '') === 'active')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Active</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">Inactive</span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                @if(!empty($iklan['link']))
                                    <a href="{{ $iklan['link'] }}" target="_blank"
                                       title="{{ $iklan['link'] }}"
                                       class="text-[#4988C4] hover:underline text-xs truncate block">
                                        {{ $iklan['link'] }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            {{-- Thumbnail: path dari DB adalah relatif misal "iklans/file.jpg" --}}
                            {{-- Gabungkan dengan MEDIA env + /storage/ --}}
                            <td class="p-3 text-center">
                               @php
                                    $thumbUrl = null;
                                    $mediaBase = rtrim(env('MEDIA', config('app.url')), '/');
                                    $thumb = $iklan['thumbnail'] ?? null;

                                    if (!empty($thumb)) {
                                        if (str_starts_with($thumb, 'http')) {
                                            $rawUrl = $thumb;
                                        } else {
                                            $rawUrl = $mediaBase . '/storage/' . ltrim($thumb, '/');
                                        }
                                        // Proxy lewat web server sendiri agar ngrok tidak blokir
                                        $thumbUrl = url('/proxy-image?url=' . urlencode($rawUrl));
                                    }
                                @endphp

                                @if($thumbUrl)
                                    <img src="{{ $thumbUrl }}"
                                        class="w-16 h-12 rounded-lg object-cover shadow mx-auto"
                                        loading="lazy"
                                        referrerpolicy="no-referrer"
                                        crossorigin="anonymous"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-16 h-12 rounded-lg bg-gray-200 items-center justify-center mx-auto"
                                        style="display:none">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @else
                                    <div class="w-16 h-12 rounded-lg bg-gray-200 flex items-center justify-center mx-auto">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('iklan.edit', $iklan['id']) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>

                                    {{-- Form hapus: gunakan data-attribute agar aman dari masalah quote --}}
                                    <form id="form-hapus-{{ $loop->index }}"
                                          action="{{ route('iklan.destroy', $iklan['id']) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                data-form="form-hapus-{{ $loop->index }}"
                                                data-nama="{{ $iklan['name'] ?? 'iklan ini' }}"
                                                onclick="confirmHapus(this)"
                                                class="text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-gray-500">
                                Data iklan belum tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                <span id="infoIklan" class="text-sm text-gray-500"></span>
                <div class="flex gap-2">
                    <button id="prevIklan"
                            onclick="changePage(-1)"
                            class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        Prev
                    </button>
                    <button id="nextIklan"
                            onclick="changePage(1)"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Include popup hapus global --}}
@include('component.popuphapus')

<script>
function previewThumbnail(input, labelId, wrapId, imgId) {
    const label = document.getElementById(labelId);
    const wrap  = document.getElementById(wrapId);
    const img   = document.getElementById(imgId);

    if (input.files && input.files[0]) {
        const file = input.files[0];
        label.textContent = file.name;

        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            wrap.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

/**
 * Baca data-form dan data-nama dari tombol, lalu buka popup konfirmasi
 */
function confirmHapus(btn) {
    const formId = btn.getAttribute('data-form');
    const nama   = btn.getAttribute('data-nama') || 'iklan ini';

    openPopupHapus(
        function() {
            document.getElementById(formId).submit();
        },
        'Hapus iklan "' + nama + '"?'
    );
}

// ===== PAGINATION =====
const PER_PAGE = 10;
let currentPage = 1;
let allRows = [];

function searchIklan() {
    const keyword = document.getElementById('searchIklan').value.toLowerCase().trim();
    const tbody   = document.getElementById('bodyIklan');
    const allRowsAll = Array.from(tbody.rows);

    allRows = allRowsAll.filter(row => {
        const matches = keyword === '' || row.innerText.toLowerCase().includes(keyword);
        row.style.display = matches ? '' : 'none';
        return matches;
    });

    currentPage = 1;
    renderPagination();
}

function initPagination() {
    const tbody = document.getElementById('bodyIklan');
    allRows = Array.from(tbody.rows);
    renderPagination();
}

function renderPagination() {
    const total     = allRows.length;
    const totalPage = Math.max(1, Math.ceil(total / PER_PAGE));
    const start     = (currentPage - 1) * PER_PAGE;
    const end       = start + PER_PAGE;

    allRows.forEach((row, i) => {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    document.getElementById('infoIklan').textContent =
        total > 0 ? `Hal ${currentPage} / ${totalPage}` : '';

    document.getElementById('prevIklan').disabled = currentPage <= 1;
    document.getElementById('nextIklan').disabled = currentPage >= totalPage;
}

function changePage(dir) {
    const totalPage = Math.max(1, Math.ceil(allRows.length / PER_PAGE));
    currentPage = Math.min(Math.max(1, currentPage + dir), totalPage);
    renderPagination();
}

document.addEventListener('DOMContentLoaded', initPagination);
</script>

@endsection