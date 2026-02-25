<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private function apiHeaders(): array
    {
        return [
            'X-API-KEY'                  => env('API_KEY'),
            'Authorization'              => 'Bearer ' . session('api_token'),
            'Accept'                     => 'application/json',
            'ngrok-skip-browser-warning' => 'true',
        ];
    }

    public function index()
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $jumlahBerita    = 0;
        $beritaPublished = 0;
        $beritaDraft     = 0;
        $beritaTerbaru   = [];
        $beritaPopuler   = [];
        $chartLabels     = [];
        $chartData       = [];

        // 1. Semua berita — untuk statistik count & tabel terbaru
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas/admin');

            if ($response->successful()) {
                $beritas         = $response->json()['data'] ?? [];
                $jumlahBerita    = count($beritas);
                $beritaPublished = count(array_filter($beritas, fn($b) => ($b['status'] ?? '') === 'published'));
                $beritaDraft     = count(array_filter($beritas, fn($b) => ($b['status'] ?? '') === 'draft'));
                $beritaTerbaru   = array_slice($beritas, 0, 5);
            }
        } catch (\Exception $e) {}

        // 2. Berita terpopuler — sorted by view_count DESC
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas/populer', ['limit' => 5]);

            if ($response->successful()) {
                $beritaPopuler = $response->json()['data'] ?? [];
            }
        } catch (\Exception $e) {}

        // 3. Statistik viewers per hari — untuk diagram Chart.js
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas/statistik', ['days' => 7]);

            if ($response->successful()) {
                $stat        = $response->json()['data'] ?? [];
                $chartLabels = $stat['labels'] ?? [];
                $chartData   = $stat['views']  ?? [];
            }
        } catch (\Exception $e) {}

        return view('pages.Dashboard', compact(
            'jumlahBerita',
            'beritaPublished',
            'beritaDraft',
            'beritaTerbaru',
            'beritaPopuler',
            'chartLabels',
            'chartData'
        ));
    }
}