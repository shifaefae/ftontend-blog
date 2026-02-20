<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KategoriController extends Controller
{
    private function apiHeaders(): array
    {
        return [
            'X-API-KEY'     => env('API_KEY'),
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept'        => 'application/json',
        ];
    }

    /**
     * GET /api/kategoris-tags — ambil semua kategori & tag sekaligus
     * Response: { success, data: { Kategoris: [...], tags: [...] } }
     */
    public function index()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'kategoris-tags');

            $data      = $response->json()['data'] ?? [];
            $kategoris = $data['Kategoris'] ?? [];
            $tags      = $data['tags']       ?? [];

        } catch (\Exception $e) {
            $kategoris = [];
            $tags      = [];
        }

        return view('pages.Kategori', compact('kategoris', 'tags'));
    }

    // ===================== KATEGORI =====================

    /**
     * POST /api/kategoris
     * BE field: name, description (optional)
     * BE otomatis generate slug dari name
     */
    public function storeKategori(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->post(env('API_BASE_URL') . 'kategoris', [
                    'name'        => $request->name,
                    'description' => $request->description ?? '',
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('kategori.index')
                    ->with('success', $result['message'] ?? 'Kategori berhasil ditambahkan!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menambahkan kategori.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * PUT /api/kategoris/{id}
     * BE field: name, description (optional)
     */
    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->put(env('API_BASE_URL') . 'kategoris/' . $id, [
                    'name'        => $request->name,
                    'description' => $request->description ?? '',
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('kategori.index')
                    ->with('success', $result['message'] ?? 'Kategori berhasil diupdate!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal mengupdate kategori.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * DELETE /api/kategoris/{id}
     * BE: gagal jika kategori masih dipakai berita
     */
    public function destroyKategori($id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->delete(env('API_BASE_URL') . 'kategoris/' . $id);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('kategori.index')
                    ->with('success', $result['message'] ?? 'Kategori berhasil dihapus!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menghapus kategori.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    // ===================== TAG =====================

    /**
     * POST /api/tags
     * BE field: name
     */
    public function storeTag(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->post(env('API_BASE_URL') . 'tags', [
                    'name' => $request->name,
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('kategori.index')
                    ->with('success', $result['message'] ?? 'Tag berhasil ditambahkan!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menambahkan tag.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * PUT /api/tags/{id}
     * BE field: name
     */
    public function updateTag(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->put(env('API_BASE_URL') . 'tags/' . $id, [
                    'name' => $request->name,
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('kategori.index')
                    ->with('success', $result['message'] ?? 'Tag berhasil diupdate!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal mengupdate tag.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * DELETE /api/tags/{id}
     */
    public function destroyTag($id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->delete(env('API_BASE_URL') . 'tags/' . $id);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('kategori.index')
                    ->with('success', $result['message'] ?? 'Tag berhasil dihapus!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menghapus tag.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }
}