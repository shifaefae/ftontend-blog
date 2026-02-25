@extends('layout.App')

@section('title', 'Dashboard - Portal Blog')

@section('content')
<div class="min-h-screen bg-[#fbfbfc] p-5 font-sans max-w-7xl mx-auto">

    <h1 class="text-[28px] font-bold mb-6 bg-gradient-to-r from-gray-800 to-[#4988C4]
               bg-clip-text text-transparent">
        Dashboard Portal Blog
    </h1>

    {{-- Stats Cards --}}
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

    {{-- Diagram Viewers --}}
    <div class="relative bg-white rounded-2xl p-8 mb-9
                shadow-[0_4px_20px_rgba(0,0,0,0.08)] border-t-4 border-[#4988C4]">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <span class="text-2xl">📊</span> Diagram Data Viewers (7 Hari Terakhir)
            </h2>
            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                Total: <strong>{{ number_format(array_sum($chartData)) }}</strong> views
            </span>
        </div>

        @if(count($chartData) > 0 && array_sum($chartData) > 0)
            <canvas id="myChart" class="max-h-[350px] w-full"></canvas>
        @else
            <div class="flex flex-col items-center justify-center h-[200px] text-gray-400">
                <span class="text-5xl mb-3">📉</span>
                <p class="text-sm">Belum ada data views dalam 7 hari terakhir.</p>
                <p class="text-xs mt-1">Data akan muncul saat pembaca membuka berita.</p>
            </div>
            <canvas id="myChart" class="hidden"></canvas>
        @endif
    </div>

    {{-- Grid: Terbaru + Terpopuler --}}
    <div class="grid grid-cols-1 lg:grid-cols-[73%_25%] gap-6">

        {{-- Berita Terbaru --}}
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
                            <th class="p-4 text-left  w-[12%]">Gambar</th>
                            <th class="p-4 text-left  w-[32%]">Judul</th>
                            <th class="p-4 text-left  w-[15%]">Penulis</th>
                            <th class="p-4 text-center w-[11%]">👁 Views</th>
                            <th class="p-4 text-center w-[15%]">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beritaTerbaru as $i => $berita)
                        <tr class="border-b transition hover:bg-gray-50">
                            <td class="p-4 text-center font-semibold text-gray-600">{{ $i + 1 }}</td>

                            <td class="p-4">
                                @php
                                    $thumbUrl = null;
                                    if (!empty($berita['thumbnail'])) {
                                        $raw = str_starts_with($berita['thumbnail'], 'http')
                                            ? $berita['thumbnail']
                                            : env('MEDIA') . '/' . $berita['thumbnail'];
                                        $thumbUrl = '/proxy-image?url=' . urlencode($raw);
                                    }
                                @endphp

                                @if($thumbUrl)
                                    <img src="{{ $thumbUrl }}"
                                         class="w-[80px] h-[60px] rounded-lg object-cover shadow"
                                         loading="lazy">
                                @else
                                    <div class="w-[80px] h-[60px] rounded-lg bg-gray-200
                                                flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="p-4 font-medium text-gray-800 leading-snug">
                                {{ $berita['title'] ?? '-' }}
                            </td>
                            <td class="p-4 text-gray-600">
                                {{ $berita['user']['name'] ?? '-' }}
                            </td>
                            <td class="p-4 text-center font-semibold text-gray-700">
                                {{ number_format($berita['view_count'] ?? 0) }}
                            </td>
                            <td class="p-4 text-center">
                                @if(($berita['status'] ?? '') === 'published')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                 text-white bg-green-500">Published</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                 text-white bg-yellow-500">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">
                                Belum ada berita.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Berita Terpopuler (data real dari /beritas/populer) --}}
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <span class="text-2xl">⭐</span> Berita Terpopuler
            </h2>

            <div class="bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgba(0,0,0,0.08)]">
                @forelse($beritaPopuler as $idx => $berita)
                <div class="mb-4 p-4 rounded-xl bg-gradient-to-r from-[#4988C4] to-[#4988C4]
                            border-l-4 border-blue-300 transition-all
                            hover:translate-x-1 hover:shadow-lg">
                    <div class="text-white font-semibold text-sm mb-2 leading-relaxed">
                        <span class="opacity-60 mr-1">#{{ $idx + 1 }}</span>
                        {{ $berita['title'] ?? '-' }}
                    </div>
                    <div class="flex justify-between items-center gap-2 text-white text-xs flex-wrap">
                        @if(!empty($berita['kategoris'][0]['name']))
                            <span class="px-2 py-1 rounded-full bg-blue-400/50 font-semibold">
                                {{ strtoupper($berita['kategoris'][0]['name']) }}
                            </span>
                        @else
                            <span></span>
                        @endif
                        <span class="font-bold">👁 {{ number_format($berita['view_count'] ?? 0) }}</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                    <span class="text-4xl mb-2">📭</span>
                    <p class="text-sm">Belum ada berita populer.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    const chartLabels = @json($chartLabels);
    const chartData   = @json($chartData);

    const ctx = document.getElementById('myChart');

    if (ctx && !ctx.classList.contains('hidden')) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Viewers',
                    data: chartData,
                    borderColor: '#4988C4',
                    backgroundColor: 'rgba(73,136,196,0.15)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#4988C4',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        labels: { color: '#2d3748', font: { size: 13 } }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.75)',
                        callbacks: {
                            label: ctx => '  ' + ctx.parsed.y.toLocaleString('id-ID') + ' views'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#718096',
                            precision: 0,
                            callback: val => val.toLocaleString('id-ID')
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { color: '#718096' },
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endpush