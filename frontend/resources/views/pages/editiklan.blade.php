@extends('layout.App')

@section('title', 'Edit Iklan - Portal Blog')

@section('content')
<div class="min-h-screen bg-white p-6">

    <div class="mb-8">
        <h1 class="flex items-center gap-4 text-4xl font-bold text-black">
            <span class="p-4 bg-white rounded-xl shadow">
                <i class="fas fa-edit text-[#4988C4] text-3xl"></i>
            </span>
            Edit Iklan
        </h1>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="max-w-xl bg-white rounded-2xl shadow p-8">

        <form action="{{ route('iklan.update', $iklan['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Iklan</label>
                <input name="name"
                       value="{{ $iklan['name'] ?? '' }}"
                       class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40"
                       placeholder="Nama iklan" required>
            </div>

            {{-- Posisi --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Posisi</label>
                <select name="position" class="w-full border rounded-xl p-3" required>
                    <option value="">Pilih Posisi</option>
                    <option value="slide_1x1" {{ ($iklan['position'] ?? '') === 'slide_1x1' ? 'selected' : '' }}>1:1 Slide</option>
                    <option value="right_3x1" {{ ($iklan['position'] ?? '') === 'right_3x1' ? 'selected' : '' }}>3:1 Kanan</option>
                    <option value="left_3x1"  {{ ($iklan['position'] ?? '') === 'left_3x1'  ? 'selected' : '' }}>3:1 Kiri</option>
                </select>
            </div>

            {{-- Prioritas --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Prioritas</label>
                <input type="number" name="priority"
                       value="{{ $iklan['priority'] ?? 1 }}"
                       class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40"
                       min="1" placeholder="1">
            </div>

            {{-- Link --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Link (optional)</label>
                <input name="link"
                       value="{{ $iklan['link'] ?? '' }}"
                       class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40"
                       placeholder="https://...">
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded-xl p-3" required>
                    <option value="active"   {{ ($iklan['status'] ?? '') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($iklan['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            {{-- Thumbnail --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar (kosongkan jika tidak diubah)</label>

                {{-- Gambar saat ini --}}
                @php
                    $mediaBase = rtrim(env('MEDIA', ''), '/');
                    $currentThumb = null;

                    if (!empty($iklan['thumbnail'])) {
                        $thumb = $iklan['thumbnail'];
                        if (str_starts_with($thumb, 'http')) {
                            $currentThumb = $thumb;
                        } else {
                            $currentThumb = $mediaBase . '/storage/' . ltrim($thumb, '/') . '?ngrok-skip-browser-warning=true';
                        }
                    }
                @endphp

                @if($currentThumb)
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                        <img src="{{ $currentThumb }}"
                             class="w-32 h-24 rounded-xl object-cover border shadow"
                             onerror="this.src='{{ asset('images/default-iklan-thumbnail.jpg') }}'">
                    </div>
                @endif

                <label for="edit_thumbnail_input"
                       class="flex items-center gap-3 w-full border-2 border-dashed border-[#4988C4] rounded-xl p-3 cursor-pointer hover:bg-blue-50 transition">
                    <i class="fas fa-cloud-upload-alt text-[#4988C4] text-xl"></i>
                    <span id="edit_thumbnail_label" class="text-gray-500 text-sm truncate">Pilih gambar baru...</span>
                </label>

                <input type="file" name="thumbnail" id="edit_thumbnail_input" accept="image/*"
                       class="hidden"
                       onchange="previewThumbnail(this, 'edit_thumbnail_label', 'edit_preview_wrap', 'edit_preview')">

                <div id="edit_preview_wrap" class="hidden mt-3 border-2 border-[#4988C4] rounded-xl overflow-hidden">
                    <img id="edit_preview" src="#" alt="Preview"
                         class="w-full max-h-48 object-cover">
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 mt-6">
                <a href="{{ route('iklan.list') }}"
                   class="flex-1 text-center py-3 rounded-xl border border-gray-300 font-semibold text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-[#4988C4] text-white py-3 rounded-xl font-bold hover:bg-[#3a77b0] transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

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
</script>

@endsection