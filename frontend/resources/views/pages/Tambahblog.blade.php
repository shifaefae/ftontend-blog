@extends('layout.App')

@section('title', 'Tambah Blog - Portal Blog')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<div class="min-h-screen bg-[#f5f7fa] px-6 py-8">
    <div class="max-w-6xl mx-auto">

        <h1 class="text-4xl font-bold text-gray-800 mb-8">Tambah Blog</h1>

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
        @endif

        <form id="blogForm" action="{{ route('blog.store') }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- BE field: title --}}
            <input type="text" name="title" required
                   placeholder="Judul Blog"
                   value="{{ old('title') }}"
                   class="w-full px-5 py-4 text-lg rounded-xl border-2 border-gray-300
                          focus:outline-none focus:ring-4 focus:ring-[#4988C4]/20">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Editor Quill -->
                <div class="lg:col-span-2 bg-white rounded-xl border-2 border-gray-300 overflow-hidden flex flex-col">
                    <div id="editor" class="h-[450px] text-base"></div>
                    {{-- BE field: description --}}
                    <input type="hidden" name="description" id="description-hidden">
                </div>

                <!-- Sidebar -->
                <div class="flex flex-col gap-4">

                    {{-- BE field: kategori_ids[] (array of ID dari /api/kategoris-tags) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_ids[]" multiple
                                class="w-full px-4 py-3 rounded-lg border border-gray-300
                                       focus:outline-none focus:ring-2 focus:ring-[#4988C4]/20" size="4">
                            @foreach($kategoris ?? [] as $kat)
                                <option value="{{ $kat['id'] }}">{{ $kat['name'] }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Ctrl/Cmd untuk pilih lebih dari satu</p>
                    </div>

                    {{-- BE field: tag_ids[] (array of ID) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tag</label>
                        <select name="tag_ids[]" multiple
                                class="w-full px-4 py-3 rounded-lg border border-gray-300
                                       focus:outline-none focus:ring-2 focus:ring-[#4988C4]/20" size="4">
                            @foreach($tags ?? [] as $tag)
                                <option value="{{ $tag['id'] }}">{{ $tag['name'] }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Ctrl/Cmd untuk pilih lebih dari satu</p>
                    </div>

                    {{-- BE field: status --}}
                    <select name="status" required
                            class="px-4 py-3 rounded-lg border border-gray-300
                                   focus:outline-none focus:ring-2 focus:ring-[#4988C4]/20">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>

                    <!-- Upload Gambar — BE field: thumbnail -->
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-[#4988C4] mb-3">
                            <i class="fas fa-image"></i> Gambar Blog (optional)
                        </label>

                        <input type="file" id="inputGambar" name="thumbnail"
                               accept="image/*" class="hidden" onchange="previewGambar(event)">

                        <label for="inputGambar"
                               class="flex items-center justify-center gap-2 px-4 py-4
                                      border-2 border-[#4988C4] rounded-xl cursor-pointer
                                      text-[#4988C4] font-medium transition hover:bg-[#4988C4]/10">
                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                            <span id="namaFile">Pilih gambar</span>
                        </label>

                        <div id="previewContainer" class="hidden mt-4">
                            <img id="previewImage"
                                 class="w-full h-48 object-cover rounded-xl border-2 border-[#4988C4] shadow-lg"
                                 alt="Preview">
                        </div>
                    </div>

                    <button type="submit"
                            class="mt-4 px-6 py-4 rounded-xl bg-[#4988C4]
                                   text-white font-bold text-lg transition hover:bg-[#3d7ab3] hover:shadow-xl">
                        Upload Blog
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
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

    document.getElementById('blogForm').onsubmit = function () {
        const content = quill.getText().trim();
        if (content.length === 0) {
            alert('Konten blog tidak boleh kosong!');
            return false;
        }
        // Isi hidden field description sebelum submit
        document.getElementById('description-hidden').value = quill.root.innerHTML;
        return true;
    };
</script>
@endsection