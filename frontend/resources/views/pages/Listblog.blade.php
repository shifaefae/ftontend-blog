@extends('layout.App')

@section('title', 'List Blog - Portal Blog')

@section('content')
<div class="p-8 font-sans bg-white min-h-screen">

    <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
        <h1 class="text-3xl font-bold text-gray-800">List Blog</h1>

        <div class="flex items-center gap-3 flex-wrap">
            <form method="GET" action="{{ route('blog.list') }}" class="relative w-80">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[#4988C4] text-lg">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari blog..."
                       class="w-full pl-12 pr-5 py-3 rounded-full border-2 border-[#4988C4]
                              focus:outline-none focus:ring-2 focus:ring-[#4988C4]/40">
            </form>

            <a href="{{ route('blog.tambah') }}"
               class="px-8 py-3 bg-[#4988C4] text-white font-semibold rounded-xl
                      shadow-lg shadow-blue-500/40 hover:-translate-y-0.5 hover:shadow-xl transition">
                Tambah Blog
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
        <div class="absolute top-0 left-0 right-0 h-1 bg-[#4988C4] rounded-t-2xl"></div>

        <table class="w-full border-collapse mt-4">
            <thead class="bg-[#4988C4]">
                <tr>
                    <th class="px-4 py-4 text-white text-left">No</th>
                    <th class="px-4 py-4 text-white text-left">Foto</th>
                    <th class="px-4 py-4 text-white text-left">Judul</th>
                    <th class="px-4 py-4 text-white text-left">Penulis</th>
                    <th class="px-4 py-4 text-white text-left">Kategori</th>
                    <th class="px-4 py-4 text-white text-left">Status</th>
                    <th class="px-4 py-4 text-white text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($blogs as $blog)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-4">{{ $loop->iteration }}</td>

                    {{-- PROXY: request gambar lewat server frontend, bukan langsung dari browser --}}
                    <td class="px-4 py-4">
                        @php
                            $thumbUrl = null;
                            if (!empty($blog['thumbnail'])) {
                                $raw = str_starts_with($blog['thumbnail'], 'http')
                                    ? $blog['thumbnail']
                                    : env('MEDIA') . '/' . $blog['thumbnail'];
                                $thumbUrl = '/proxy-image?url=' . urlencode($raw);
                            }
                        @endphp

                        @if($thumbUrl)
                            <img src="{{ $thumbUrl }}"
                                class="w-14 h-14 rounded-lg object-cover"
                                loading="lazy">
                        @else
                            <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                    </td>

                    <td class="px-4 py-4 font-medium text-gray-800">
                        {{ $blog['title'] ?? '-' }}
                    </td>

                    <td class="px-4 py-4 text-gray-600">
                        {{ $blog['user']['name'] ?? '-' }}
                    </td>

                    <td class="px-4 py-4">
                        @if(!empty($blog['kategoris']))
                            @foreach ($blog['kategoris'] as $kategori)
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm mr-1 mb-1 inline-block">
                                    {{ $kategori['name'] ?? '-' }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>

                    <td class="px-4 py-4">
                        @if(($blog['status'] ?? '') === 'published')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Published</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Draft</span>
                        @endif
                    </td>

                    <td class="px-4 py-4">
                        <div class="flex gap-x-3">
                            <a href="{{ route('blog.edit', ['id' => $blog['id']]) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>

                            <form action="{{ route('blog.destroy', $blog['id']) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus blog ini?')">
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
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        Data blog tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection