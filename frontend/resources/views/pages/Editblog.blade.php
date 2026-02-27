@extends('layout.App')

@section('title', 'Edit Blog - Portal Blog')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<div class="min-h-screen bg-[#f5f7fa] px-6 py-8">
    <div class="max-w-6xl mx-auto">

        <div class="mb-8 flex justify-between items-center flex-wrap gap-4">
            <h1 class="text-4xl font-bold text-gray-800">Edit Blog</h1>
            <a href="{{ route('blog.list') }}"
               class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                ← Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
        @endif

        <form id="blogForm" action="{{ route('blog.update', $blog['id']) }}"
              method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <input type="text" name="title" required
                   placeholder="Judul Blog"
                   value="{{ old('title', $blog['title'] ?? '') }}"
                   class="w-full px-5 py-4 text-lg rounded-xl border-2 border-gray-300 bg-white
                          focus:outline-none focus:ring-4 focus:ring-[#4988C4]/20 focus:border-[#4988C4] transition
                          @error('title') border-red-500 @enderror">
            @error('title')<p class="text-red-500 text-xs -mt-3">{{ $message }}</p>@enderror

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ===== Editor Quill ===== --}}
                <div class="lg:col-span-2 bg-white rounded-xl border-2 border-gray-300 overflow-hidden flex flex-col">
                    <div id="editor" class="h-[450px] text-base"></div>
                    <input type="hidden" name="description" id="description-hidden">
                </div>

                {{-- ===== Sidebar ===== --}}
                <div class="flex flex-col gap-5">

                    {{-- ===== DROPDOWN KATEGORI ===== --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                                </svg>
                                Kategori
                            </span>
                        </label>

                        <div class="relative" id="wrapper-kategori">
                            <div id="trigger-kategori"
                                 class="w-full min-h-[48px] px-3 py-2 rounded-xl border-2 border-gray-300 bg-white
                                        cursor-pointer flex flex-wrap gap-1 items-center transition hover:border-[#4988C4]">
                                <span id="placeholder-kategori" class="text-gray-400 text-sm select-none">Pilih kategori...</span>
                                <svg class="ml-auto w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                                     id="chevron-kategori"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <div id="panel-kategori"
                                 class="hidden absolute z-50 mt-1 w-full bg-white rounded-xl border-2 border-[#4988C4]
                                        shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-gray-100">
                                    <input type="text" id="search-kategori"
                                           placeholder="Cari kategori..."
                                           class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200
                                                  focus:outline-none focus:border-[#4988C4]">
                                </div>
                                <ul id="list-kategori" class="max-h-48 overflow-y-auto py-1">
                                    @forelse($kategoris ?? [] as $kat)
                                        <li class="dropdown-item-kategori flex items-center gap-2.5 px-4 py-2.5 cursor-pointer text-sm text-gray-700 hover:bg-gray-50 transition"
                                            data-id="{{ $kat['id'] }}"
                                            data-label="{{ $kat['name'] }}">
                                            <span class="chk w-4 h-4 rounded border-2 border-gray-300 flex items-center justify-center flex-shrink-0 transition"></span>
                                            <span>{{ $kat['name'] }}</span>
                                        </li>
                                    @empty
                                        <li class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ada kategori</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div id="inputs-kategori"></div>
                        </div>
                    </div>

                    {{-- ===== DROPDOWN TAG ===== --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                Tag
                            </span>
                        </label>

                        <div class="relative" id="wrapper-tag">
                            <div id="trigger-tag"
                                 class="w-full min-h-[48px] px-3 py-2 rounded-xl border-2 border-gray-300 bg-white
                                        cursor-pointer flex flex-wrap gap-1 items-center transition hover:border-emerald-400">
                                <span id="placeholder-tag" class="text-gray-400 text-sm select-none">Pilih tag...</span>
                                <svg class="ml-auto w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                                     id="chevron-tag"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            <div id="panel-tag"
                                 class="hidden absolute z-50 mt-1 w-full bg-white rounded-xl border-2 border-emerald-500
                                        shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-gray-100">
                                    <input type="text" id="search-tag"
                                           placeholder="Cari tag..."
                                           class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200
                                                  focus:outline-none focus:border-emerald-400">
                                </div>
                                <ul id="list-tag" class="max-h-48 overflow-y-auto py-1">
                                    @forelse($tags ?? [] as $tag)
                                        <li class="dropdown-item-tag flex items-center gap-2.5 px-4 py-2.5 cursor-pointer text-sm text-gray-700 hover:bg-gray-50 transition"
                                            data-id="{{ $tag['id'] }}"
                                            data-label="{{ $tag['name'] }}">
                                            <span class="chk w-4 h-4 rounded border-2 border-gray-300 flex items-center justify-center flex-shrink-0 transition"></span>
                                            <span>{{ $tag['name'] }}</span>
                                        </li>
                                    @empty
                                        <li class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ada tag</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div id="inputs-tag"></div>
                        </div>
                    </div>

                    {{-- ===== STATUS ===== --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" required
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 bg-white
                                       focus:outline-none focus:ring-4 focus:ring-[#4988C4]/20 focus:border-[#4988C4] transition">
                            <option value="draft"     {{ (old('status', $blog['status'] ?? '')) === 'draft'     ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ (old('status', $blog['status'] ?? '')) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    {{-- ===== UPLOAD GAMBAR ===== --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-[#4988C4] mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Gambar Blog <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>

                        {{-- ===== UPLOAD GAMBAR ===== --}}
                        @php
                            $thumbRaw = $blog['thumbnail'] ?? '';

                            // Jika thumbnail sudah full URL (dari API), pakai langsung
                            // Jika hanya path/filename, gabung dengan env MEDIA
                            if (!empty($thumbRaw)) {
                                $thumbFull = str_starts_with($thumbRaw, 'http')
                                    ? $thumbRaw
                                    : rtrim(env('MEDIA', ''), '/') . '/' . ltrim($thumbRaw, '/');
                                $thumbUrl = route('proxy.image', ['url' => $thumbFull]);
                            } else {
                                $thumbUrl = '';
                            }
                        @endphp

                        {{-- Satu kotak gambar: tampilkan lama atau preview baru --}}
                        @if(!empty($thumbUrl))
                        <div id="thumbWrapper"
                             class="relative rounded-xl overflow-hidden border-2 border-gray-200 shadow mb-3">
                            <img id="singleThumb"
                                 src="{{ $thumbUrl }}"
                                 class="w-full h-48 object-cover block"
                                 alt="Thumbnail">
                            <span id="badgeGambar"
                                  class="absolute top-2 left-2 text-xs font-bold px-2 py-0.5 rounded-full
                                         bg-gray-700/70 text-white">
                            
                            </span>
                            <button type="button" id="btnBatalGambar" onclick="batalGambar()"
                                    class="hidden absolute top-2 right-2 bg-white/90 hover:bg-white
                                           text-gray-700 text-xs font-semibold px-3 py-1 rounded-full shadow
                                           border border-gray-300 transition">
                                ✕ Batalkan
                            </button>
                        </div>
                        @else
                        <div id="thumbWrapper"
                             class="relative rounded-xl overflow-hidden border-2 border-gray-200 shadow mb-3"
                             style="display:none">
                            <img id="singleThumb"
                                 src=""
                                 class="w-full h-48 object-cover block"
                                 alt="Thumbnail">
                            <span id="badgeGambar"
                                  class="absolute top-2 left-2 text-xs font-bold px-2 py-0.5 rounded-full
                                         bg-[#4988C4] text-white">
                                ✓ Gambar baru
                            </span>
                            <button type="button" id="btnBatalGambar" onclick="batalGambar()"
                                    class="hidden absolute top-2 right-2 bg-white/90 hover:bg-white
                                           text-gray-700 text-xs font-semibold px-3 py-1 rounded-full shadow
                                           border border-gray-300 transition">
                                ✕ Batalkan
                            </button>
                        </div>
                        @endif

                        <input type="file" id="inputGambar" name="thumbnail"
                               accept="image/*" class="hidden" onchange="previewGambar(event)">

                        <label for="inputGambar"
                               class="flex items-center justify-center gap-2 px-4 py-4
                                      border-2 border-dashed border-[#4988C4] rounded-xl cursor-pointer
                                      text-[#4988C4] font-medium transition hover:bg-[#4988C4]/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span id="namaFile">
                                {{ !empty($thumbUrl) ? 'Ganti gambar' : 'Pilih gambar' }}
                            </span>
                        </label>

                    <button type="submit"
                            class="mt-4 px-6 py-4 rounded-xl bg-[#4988C4]
                                   text-white font-bold text-lg transition hover:bg-[#3d7ab3] hover:shadow-xl">
                        Update Blog
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
// ============================================================
//  Multi-Select Dropdown — pure vanilla JS
// ============================================================
function initMultiSelect(config) {
    const {
        triggerId, panelId, searchId, listClass,
        placeholderId, chevronId, inputsId,
        badgeColor, fieldName, initialSelected
    } = config;

    const trigger     = document.getElementById(triggerId);
    const panel       = document.getElementById(panelId);
    const searchInput = document.getElementById(searchId);
    const placeholder = document.getElementById(placeholderId);
    const chevron     = document.getElementById(chevronId);
    const inputsDiv   = document.getElementById(inputsId);

    let selected = [];

    // ---- Buka / Tutup ----
    trigger.addEventListener('click', function (e) {
        const isOpen = !panel.classList.contains('hidden');
        closeAll();
        if (!isOpen) {
            panel.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
            trigger.classList.add('border-[#4988C4]', 'ring-4');
            searchInput.focus();
        }
    });

    document.addEventListener('click', function (e) {
        const wrapper = trigger.closest('.relative');
        if (!wrapper.contains(e.target)) {
            panel.classList.add('hidden');
            chevron.style.transform = '';
            trigger.classList.remove('ring-4');
        }
    });

    function closeAll() {}

    // ---- Search ----
    searchInput.addEventListener('input', function () {
        const kw = this.value.toLowerCase();
        document.querySelectorAll('.' + listClass).forEach(function (li) {
            const label = li.dataset.label.toLowerCase();
            li.style.display = label.includes(kw) ? '' : 'none';
        });
    });

    // ---- Klik item ----
    document.querySelectorAll('.' + listClass).forEach(function (li) {
        li.addEventListener('click', function () {
            const id    = this.dataset.id;
            const label = this.dataset.label;
            const idx   = selected.findIndex(s => s.id === id);

            if (idx > -1) {
                selected.splice(idx, 1);
                this.querySelector('.chk').innerHTML = '';
                this.querySelector('.chk').className =
                    'chk w-4 h-4 rounded border-2 border-gray-300 flex items-center justify-center flex-shrink-0 transition';
                this.style.backgroundColor = '';
                this.style.color = '';
                this.style.fontWeight = '';
            } else {
                selected.push({ id, label });
                this.querySelector('.chk').innerHTML =
                    '<svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 12 12"><polyline points="1.5,6 4.5,9 10.5,3"/></svg>';
                this.querySelector('.chk').style.backgroundColor = badgeColor;
                this.querySelector('.chk').style.borderColor = badgeColor;
                this.style.backgroundColor = badgeColor + '15';
                this.style.color = badgeColor;
                this.style.fontWeight = '600';
            }

            renderBadges();
            renderInputs();
        });
    });

    function renderBadges() {
        trigger.querySelectorAll('.badge-item').forEach(b => b.remove());
        placeholder.style.display = selected.length === 0 ? '' : 'none';

        selected.forEach(function (item) {
            const badge = document.createElement('span');
            badge.className = 'badge-item inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold text-white';
            badge.style.backgroundColor = badgeColor;
            badge.innerHTML =
                '<span>' + escHtml(item.label) + '</span>' +
                '<button type="button" class="ml-0.5 hover:opacity-70 leading-none text-base" data-id="' + item.id + '">&times;</button>';

            badge.querySelector('button').addEventListener('click', function (e) {
                e.stopPropagation();
                const rid = this.dataset.id;
                selected = selected.filter(s => s.id !== rid);

                const li = document.querySelector('.' + listClass + '[data-id="' + rid + '"]');
                if (li) {
                    li.querySelector('.chk').innerHTML = '';
                    li.querySelector('.chk').style.backgroundColor = '';
                    li.querySelector('.chk').style.borderColor = '';
                    li.style.backgroundColor = '';
                    li.style.color = '';
                    li.style.fontWeight = '';
                }

                renderBadges();
                renderInputs();
            });

            trigger.insertBefore(badge, chevron);
        });
    }

    function renderInputs() {
        inputsDiv.innerHTML = '';
        selected.forEach(function (item) {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = fieldName + '[]';
            inp.value = item.id;
            inputsDiv.appendChild(inp);
        });
    }

    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    // ---- Restore nilai yang sudah dipilih (edit mode) ----
    if (Array.isArray(initialSelected)) {
        initialSelected.forEach(function (id) {
            const li = document.querySelector('.' + listClass + '[data-id="' + id + '"]');
            if (li) li.click();
        });
    }
}

// ============================================================
//  Init
// ============================================================
document.addEventListener('DOMContentLoaded', function () {

    // Ambil ID yang sudah terpilih dari data blog
    @php
        $selectedKategoriIds = collect($blog['kategoris'] ?? [])->pluck('id')->toArray();
        $selectedTagIds      = collect($blog['tags']     ?? [])->pluck('id')->toArray();
    @endphp

    initMultiSelect({
        triggerId:       'trigger-kategori',
        panelId:         'panel-kategori',
        searchId:        'search-kategori',
        listClass:       'dropdown-item-kategori',
        placeholderId:   'placeholder-kategori',
        chevronId:       'chevron-kategori',
        inputsId:        'inputs-kategori',
        badgeColor:      '#4988C4',
        fieldName:       'kategori_ids',
        initialSelected: @json(old('kategori_ids', $selectedKategoriIds)),
    });

    initMultiSelect({
        triggerId:       'trigger-tag',
        panelId:         'panel-tag',
        searchId:        'search-tag',
        listClass:       'dropdown-item-tag',
        placeholderId:   'placeholder-tag',
        chevronId:       'chevron-tag',
        inputsId:        'inputs-tag',
        badgeColor:      '#10b981',
        fieldName:       'tag_ids',
        initialSelected: @json(old('tag_ids', $selectedTagIds)),
    });

    // ============================================================
    //  Quill Editor — load konten blog yang sudah ada
    // ============================================================
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis konten blog di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Isi editor dengan konten yang sudah ada (HTML dari backend)
    var existingContent = @json(old('description', $blog['description'] ?? ''));
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // ============================================================
    //  Submit
    // ============================================================
    document.getElementById('blogForm').onsubmit = function () {
        const content = quill.getText().trim();
        if (content.length === 0) {
            alert('Konten blog tidak boleh kosong!');
            return false;
        }
        document.getElementById('description-hidden').value = quill.root.innerHTML;
        return true;
    };
});

// ============================================================
//  Gambar — satu <img>, src diganti saat pilih gambar baru
// ============================================================
// Simpan src asli gambar lama untuk fungsi batalkan
var _thumbSrcLama = (function() {
    var el = document.getElementById('singleThumb');
    return el ? el.src : '';
})();

function previewGambar(event) {
    var file = event.target.files[0];
    if (!file) return;

    var reader = new FileReader();
    reader.onload = function (e) {
        var wrapper  = document.getElementById('thumbWrapper');
        var img      = document.getElementById('singleThumb');
        var badge    = document.getElementById('badgeGambar');
        var btnBatal = document.getElementById('btnBatalGambar');
        var namaFile = document.getElementById('namaFile');

        // Tampilkan wrapper, ganti src img dengan preview baru
        wrapper.style.display    = '';
        img.src                  = e.target.result;

        // Update badge & tombol
        badge.textContent          = '✓ Gambar baru';
        badge.style.backgroundColor = '#4988C4';
        btnBatal.classList.remove('hidden');
        namaFile.textContent       = file.name;
    };
    reader.readAsDataURL(file);
}

function batalGambar() {
    var wrapper  = document.getElementById('thumbWrapper');
    var img      = document.getElementById('singleThumb');
    var badge    = document.getElementById('badgeGambar');
    var btnBatal = document.getElementById('btnBatalGambar');
    var namaFile = document.getElementById('namaFile');
    var input    = document.getElementById('inputGambar');

    input.value = '';

    if (_thumbSrcLama && _thumbSrcLama !== window.location.href) {
        // Ada gambar lama — kembalikan
        img.src                    = _thumbSrcLama;
        badge.textContent          = 'Gambar saat ini';
        badge.style.backgroundColor = '';
        namaFile.textContent       = 'Ganti gambar';
        btnBatal.classList.add('hidden');
    } else {
        // Tidak ada gambar lama — sembunyikan wrapper
        wrapper.style.display = 'none';
        namaFile.textContent  = 'Pilih gambar';
        btnBatal.classList.add('hidden');
    }
}
</script>
@endsection