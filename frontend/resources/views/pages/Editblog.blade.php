@extends('layout.App')

@section('title', 'Edit Blog - Portal Blog')

@section('content')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>
    /* ===== Select2 Custom Styling ===== */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d5db;
        border-radius: 0.75rem;
        min-height: 48px;
        padding: 4px 8px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #4988C4;
        box-shadow: 0 0 0 3px rgba(73, 136, 196, 0.15);
        outline: none;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #4988C4;
        border: none;
        color: #fff;
        border-radius: 0.375rem;
        padding: 2px 10px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255, 255, 255, 0.8);
        margin-right: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #4988C4;
    }
    .select2-dropdown {
        border: 2px solid #4988C4;
        border-radius: 0.5rem;
        box-shadow: 0 8px 24px rgba(73, 136, 196, 0.15);
    }
    .select2-search--dropdown .select2-search__field {
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        padding: 6px 10px;
    }
    .select2-container { width: 100% !important; }

    /* Tag badge style — hijau */
    .tag-badge .select2-selection__choice {
        background-color: #10b981 !important;
    }
</style>

<div class="p-8 font-sans bg-white min-h-screen">

    <div class="mb-8 flex justify-between items-center flex-wrap gap-4">
        <h1 class="text-3xl font-bold text-gray-800">Edit Blog</h1>
        <a href="{{ route('blog.list') }}"
           class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
            ← Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
    @endif

    <form action="{{ route('blog.update', $blog['id']) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
        @csrf
        @method('PUT')

        <!-- JUDUL -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Judul Blog</label>
            <input type="text" name="title"
                   value="{{ old('title', $blog['title'] ?? '') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-300
                          focus:outline-none focus:ring-2 focus:ring-[#4988C4]/20 focus:border-[#4988C4]
                          @error('title') border-red-500 @enderror"
                   required>
            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- KATEGORI — Select2 dropdown -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                    </svg>
                    Kategori
                </span>
            </label>

            @php
                $selectedKategoriIds = collect($blog['kategoris'] ?? [])->pluck('id')->toArray();
            @endphp

            <select id="select-kategori" name="kategori_ids[]" multiple>
                @foreach($kategoris ?? [] as $kat)
                    <option value="{{ $kat['id'] }}"
                        {{ in_array($kat['id'], old('kategori_ids', $selectedKategoriIds)) ? 'selected' : '' }}>
                        {{ $kat['name'] }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Ketik untuk mencari kategori</p>
        </div>

        <!-- TAG — Select2 dropdown -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    Tag
                </span>
            </label>

            @php
                $selectedTagIds = collect($blog['tags'] ?? [])->pluck('id')->toArray();
            @endphp

            <select id="select-tag" name="tag_ids[]" multiple>
                @foreach($tags ?? [] as $tag)
                    <option value="{{ $tag['id'] }}"
                        {{ in_array($tag['id'], old('tag_ids', $selectedTagIds)) ? 'selected' : '' }}>
                        {{ $tag['name'] }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Ketik untuk mencari tag</p>
        </div>

        <!-- FOTO -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Foto Blog</label>

            @if(!empty($blog['thumbnail']))
                <img src="{{ env('MEDIA_BASE_URL') . $blog['thumbnail'] }}"
                     class="w-32 h-32 object-cover rounded-xl shadow mb-3"
                     onerror="this.style.display='none'">
                <p class="text-sm text-gray-500 mb-2">Foto saat ini. Upload baru untuk mengganti.</p>
            @endif

            <input type="file" id="inputGambar" name="thumbnail" accept="image/*"
                   class="hidden" onchange="previewGambar(event)">

            <label for="inputGambar"
                   class="inline-flex items-center gap-2 px-4 py-3 cursor-pointer
                          border-2 border-dashed border-[#4988C4] rounded-xl
                          text-[#4988C4] font-medium transition hover:bg-[#4988C4]/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <span id="namaFile">Pilih gambar baru</span>
            </label>

            <div id="previewContainer" class="hidden mt-3">
                <img id="previewImage"
                     class="w-32 h-32 object-cover rounded-xl border-2 border-[#4988C4] shadow"
                     alt="Preview">
            </div>
        </div>

        <!-- ISI BLOG -->
        <div class="mb-8">
            <label class="block mb-2 font-semibold text-gray-700">Isi Blog</label>
            <textarea name="description" rows="8"
                      class="w-full px-4 py-3 rounded-xl border border-gray-300
                             focus:outline-none focus:ring-2 focus:ring-[#4988C4]/20 focus:border-[#4988C4]
                             @error('description') border-red-500 @enderror"
                      required>{{ old('description', $blog['description'] ?? '') }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- STATUS -->
        <div class="mb-8">
            <label class="block mb-2 font-semibold text-gray-700">Status</label>
            <select name="status"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300
                           focus:outline-none focus:ring-2 focus:ring-[#4988C4]/20 focus:border-[#4988C4]">
                <option value="published" {{ ($blog['status'] ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft"     {{ ($blog['status'] ?? '') === 'draft'     ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <div class="flex gap-4">
            <button type="submit"
                    class="px-8 py-3 bg-[#4988C4] text-white font-semibold rounded-xl
                           shadow-lg shadow-blue-500/40 hover:-translate-y-0.5 transition">
                Update Blog
            </button>
            <a href="{{ route('blog.list') }}"
               class="px-8 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                Batal
            </a>
        </div>
    </form>
</div>

{{-- jQuery (diperlukan Select2) --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // ===== Select2 Kategori (biru) =====
    $('#select-kategori').select2({
        placeholder: 'Pilih kategori...',
        allowClear: true,
        language: {
            noResults: function() { return 'Kategori tidak ditemukan'; },
            searching: function() { return 'Mencari...'; }
        }
    });

    // ===== Select2 Tag (hijau) =====
    $('#select-tag').select2({
        placeholder: 'Pilih tag...',
        allowClear: true,
        language: {
            noResults: function() { return 'Tag tidak ditemukan'; },
            searching: function() { return 'Mencari...'; }
        }
    });

    // Warna badge tag → hijau (terapkan saat init & saat pilih/hapus)
    function applyTagColor() {
        $('#select-tag').next('.select2-container').find('.select2-selection__choice')
            .css({ 'background-color': '#10b981', 'border': 'none' });
    }
    $('#select-tag').on('select2:select select2:unselect', applyTagColor);
    // Terapkan saat halaman pertama kali load (untuk data yang sudah terpilih)
    $(document).ready(function() { applyTagColor(); });

    // ===== Preview Gambar =====
    function previewGambar(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('namaFile').textContent = file.name;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection