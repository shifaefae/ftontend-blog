<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data berita dari BE untuk statistik dashboard
        // BE: GET /api/beritas — PUBLIC, tidak butuh token
        // Tapi kita kirim token juga agar bisa lihat semua status (draft + published)
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-API-KEY'     => env('API_KEY'),
                    'Authorization' => 'Bearer ' . session('api_token'),
                    'Accept'        => 'application/json',
                ])
                ->get(env('API_BASE_URL') . 'beritas');

            $result  = $response->json();
            $beritas = $result['data'] ?? [];

            // Hitung statistik dari data berita
            $jumlahBerita    = count($beritas);
            $beritaPublished = count(array_filter($beritas, fn($b) => ($b['status'] ?? '') === 'published'));
            $beritaDraft     = count(array_filter($beritas, fn($b) => ($b['status'] ?? '') === 'draft'));

            // Ambil 5 berita terbaru untuk tabel dashboard
            $beritaTerbaru = array_slice($beritas, 0, 5);

        } catch (\Exception $e) {
            $jumlahBerita    = 0;
            $beritaPublished = 0;
            $beritaDraft     = 0;
            $beritaTerbaru   = [];
        }

        return view('pages.Dashboard', compact(
            'jumlahBerita',
            'beritaPublished',
            'beritaDraft',
            'beritaTerbaru'
        ));
    }
}