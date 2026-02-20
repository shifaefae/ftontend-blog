@extends('layout.App')

@section('title', 'Dashboard - Portal Blog')

@section('content')
<div class="min-h-screen bg-[#fbfbfc] p-5 font-sans max-w-7xl mx-auto">

    <h1 class="text-[28px] font-bold mb-6 bg-gradient-to-r from-gray-800 to-[#4988C4]
               bg-clip-text text-transparent">
        Dashboard Portal Blog
    </h1>

    <!-- Stats Cards — data dari DashboardController -->
    <div class="flex flex-wrap gap-5 mb-9">
        <div class="flex-1 min-w-[200px] text-center px-9 py-6 rounded-xl
                    bg-gradient-to-r from-[#4988C4] to-[#4988C4]
                    transition-all hover:-translate-y-1 hover:shadow-lg">
            <div class="text-sm text-white/95 mb-2 font-medium">📰 Jumlah Berita</div>
            <div class="text-4xl font-bold text-white">{{ $jumlahBerita }}</div>
        </div>

        <div class="flex-1 min-w-[200px] text-center px-9 py-6 rounded-xl
                    bg-gradient-to-r from-green-500 to-green-600
                    transition-all hover:-translate-y-1 hover:shadow-lg">
            <div class="text-sm text-white/95 mb-2 font-medium">✅ Berita Published</div>
            <div class="text-4xl font-bold text-white">{{ $beritaPublished }}</div>
        </div>

        <div class="flex-1 min-w-[200px] text-center px-9 py-6 rounded-xl
                    bg-gradient-to-r from-yellow-500 to-yellow-600
                    transition-all hover:-translate-y-1 hover:shadow-lg">
            <div class="text-sm text-white/95 mb-2 font-medium">📝 Berita Draft</div>
            <div class="text-4xl font-bold text-white">{{ $beritaDraft }}</div>
        </div>
    </div>

    <!-- Diagram -->
    <div class="relative bg-white rounded-2xl p-8 mb-9
                shadow-[0_4px_20px_rgba(0,0,0,0.08)] border-t-4 border-[#4988C4]">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <span class="text-2xl">📊</span> Diagram Data Viewers
        </h2>
        <canvas id="myChart" class="max-h-[350px] w-full"></canvas>
    </div>

    <!-- Berita Section -->
    <div class="grid grid-cols-1 lg:grid-cols-[73%_25%] gap-6">

        <!-- Berita Terbaru — dari API -->
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <span class="text-2xl">🔥</span> Berita Terbaru
            </h2>

            <div class="relative bg-white rounded-2xl overflow-hidden
                        shadow-[0_4px_20px_rgba(0,0,0,0.08)] border-t-4 border-[#4988C4]">
                <table class="w-full border-collapse text-sm">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-200 border-b-2">
                        <tr class="text-gray-800 font-semibold">
                            <th class="p-4 text-center w-[5%]">No</th>
                            <th class="p-4 text-left w-[12%]">Gambar</th>
                            <th class="p-4 text-left w-[35%]">Judul</th>
                            <th class="p-4 text-left w-[15%]">Penulis</th>
                            <th class="p-4 text-center w-[15%]">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- FIX: Dari API, bukan data statis --}}
                        @forelse($beritaTerbaru as $i => $berita)
                        <tr class="border-b transition hover:bg-gray-50">
                            <td class="p-4 text-center font-semibold text-gray-600">{{ $i + 1 }}</td>
                            <td class="p-4">
                                @if(!empty($berita['thumbnail']))
                                    <img src="{{ env('MEDIA_BASE_URL') . $berita['thumbnail'] }}"
                                         class="w-[80px] h-[60px] rounded-lg object-cover shadow"
                                         onerror="this.src='https://via.placeholder.com/80x60'">
                                @else
                                    <div class="w-[80px] h-[60px] rounded-lg bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 font-medium text-gray-800">
                                {{ $berita['title'] ?? '-' }}
                            </td>
                            <td class="p-4 text-gray-600">
                                {{ $berita['user']['name'] ?? '-' }}
                            </td>
                            <td class="p-4 text-center">
                                @if(($berita['status'] ?? '') === 'published')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white bg-green-500">Published</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white bg-yellow-500">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400">
                                Belum ada berita.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Berita Terpopuler — bisa dikembangkan dari view_count -->
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <span class="text-2xl">⭐</span> Berita Terpopuler
            </h2>

            <div class="bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgba(0,0,0,0.08)]">
                @forelse(array_slice($beritaTerbaru ?? [], 0, 5) as $berita)
                <div class="mb-4 p-4 rounded-xl bg-gradient-to-r from-[#4988C4] to-[#4988C4]
                            border-l-4 border-blue-300 transition-all hover:translate-x-1 hover:shadow-lg">
                    <div class="text-white font-semibold text-sm mb-2 leading-relaxed">
                        {{ $berita['title'] ?? '-' }}
                    </div>
                    <div class="flex justify-between items-center gap-2 text-white text-xs flex-wrap">
                        @if(!empty($berita['kategoris'][0]['name']))
                            <span class="px-2 py-1 rounded-full bg-blue-400/50 font-semibold">
                                {{ strtoupper($berita['kategoris'][0]['name']) }}
                            </span>
                        @endif
                        <span>👁 {{ $berita['view_count'] ?? 0 }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
const ctx = document.getElementById('myChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'],
        datasets: [{
            label: 'Viewers',
            data: [879, 3595, 2569, 1995, 3678, 500, 4278],
            borderColor: '#4988C4',
            backgroundColor: 'rgba(73,136,196,0.15)',
            borderWidth: 3,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#2d3748' } } }
    }
});
</script>
@endpush
@endsection