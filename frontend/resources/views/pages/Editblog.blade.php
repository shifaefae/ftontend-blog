@extends('layout.App')

@section('title', 'Edit Blog - Portal Blog')

@section('content')
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

    {{-- FIX: action → blog.update, method PUT --}}
    <form action="{{ route('blog.update', $blog['id']) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
        @csrf
        @method('PUT')

        <!-- JUDUL — BE field: title -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Judul Blog</label>
            <input type="text" name="title"
                   value="{{ old('title', $blog['title'] ?? '') }}"
                   class="w-full px-4 py-3 rounded-xl border @error('title') border-red-500 @enderror"
                   required>
            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- KATEGORI — BE: kategori_ids[] (array of ID) -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Kategori</label>
            <select name="kategori_ids[]" multiple
                    class="w-full px-4 py-3 rounded-xl border" size="4">
                @foreach($kategoris ?? [] as $kat)
                    @php
                        // Cek apakah kategori ini sudah terpilih di berita
                        $selectedIds = collect($blog['kategoris'] ?? [])->pluck('id')->toArray();
                        $isSelected  = in_array($kat['id'], $selectedIds);
                    @endphp
                    <option value="{{ $kat['id'] }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $kat['name'] }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Tahan Ctrl/Cmd untuk pilih lebih dari satu</p>
        </div>

        <!-- TAG — BE: tag_ids[] (array of ID) -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Tag</label>
            <select name="tag_ids[]" multiple
                    class="w-full px-4 py-3 rounded-xl border" size="4">
                @foreach($tags ?? [] as $tag)
                    @php
                        $selectedTagIds = collect($blog['tags'] ?? [])->pluck('id')->toArray();
                        $isTagSelected  = in_array($tag['id'], $selectedTagIds);
                    @endphp
                    <option value="{{ $tag['id'] }}" {{ $isTagSelected ? 'selected' : '' }}>
                        {{ $tag['name'] }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Tahan Ctrl/Cmd untuk pilih lebih dari satu</p>
        </div>

        <!-- FOTO — BE field: thumbnail (optional, hanya jika ingin ganti) -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold text-gray-700">Foto Blog</label>

            @if(!empty($blog['thumbnail']))
                <img src="{{ env('MEDIA_BASE_URL') . $blog['thumbnail'] }}"
                     class="w-32 h-32 object-cover rounded-xl shadow mb-3"
                     onerror="this.style.display='none'">
                <p class="text-sm text-gray-500 mb-2">Foto saat ini. Upload baru untuk mengganti.</p>
            @endif

            <input type="file" name="thumbnail" accept="image/*"
                   class="w-full px-4 py-3 rounded-xl border">
        </div>

        <!-- ISI BLOG — BE field: description -->
        <div class="mb-8">
            <label class="block mb-2 font-semibold text-gray-700">Isi Blog</label>
            <textarea name="description" rows="8"
                      class="w-full px-4 py-3 rounded-xl border @error('description') border-red-500 @enderror"
                      required>{{ old('description', $blog['description'] ?? '') }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- STATUS — BE field: status (published/draft) -->
        <div class="mb-8">
            <label class="block mb-2 font-semibold text-gray-700">Status</label>
            <select name="status" class="w-full px-4 py-3 rounded-xl border">
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
@endsection