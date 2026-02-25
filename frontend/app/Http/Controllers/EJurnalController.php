<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EjurnalController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_BASE_URL') . 'ejurnals';
    }

    /**
     * ==========================
     * TAMPILKAN DATA
     * ==========================
     */
    public function index(Request $request)
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl);

            if ($response->failed()) {
                throw new \Exception('Gagal mengambil data dari API');
            }

            $ejurnals = $response->json()['data'] ?? $response->json();

            // SEARCH
            if ($request->search) {
                $search = strtolower($request->search);

                $ejurnals = collect($ejurnals)->filter(function ($item) use ($search) {
                    return str_contains(strtolower($item['title'] ?? ''), $search) ||
                           str_contains(strtolower($item['description'] ?? ''), $search);
                })->values()->toArray();
            }

            return view('pages.Ejurnal', compact('ejurnals'));

        } catch (\Exception $e) {
            return view('pages.Ejurnal', [
                'ejurnals' => []
            ])->with('error', $e->getMessage());
        }
    }

    /**
     * ==========================
     * STORE DATA
     * ==========================
     */
    public function store(Request $request)
    {
        try {

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
            ];

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = fopen($request->file('thumbnail')->getPathname(), 'r');
            }

            $response = Http::attach(
                'thumbnail',
                $request->file('thumbnail'),
                $request->file('thumbnail')?->getClientOriginalName()
            )->post($this->apiUrl, $data);

            if ($response->failed()) {
                throw new \Exception('Gagal menyimpan data');
            }

            return redirect()->route('ejurnal.index')
                ->with('success', 'Jurnal berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->route('ejurnal.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * ==========================
     * UPDATE DATA
     * ==========================
     */
    public function update(Request $request, $id)
    {
        try {

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
            ];

            $response = Http::put($this->apiUrl . '/' . $id, $data);

            if ($response->failed()) {
                throw new \Exception('Gagal update data');
            }

            return redirect()->route('ejurnal.index')
                ->with('success', 'Jurnal berhasil diupdate');

        } catch (\Exception $e) {
            return redirect()->route('ejurnal.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * ==========================
     * DELETE DATA
     * ==========================
     */
    public function destroy($id)
    {
        try {

            $response = Http::delete($this->apiUrl . '/' . $id);

            if ($response->failed()) {
                throw new \Exception('Gagal menghapus data');
            }

            return redirect()->route('ejurnal.index')
                ->with('success', 'Jurnal berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->route('ejurnal.index')
                ->with('error', $e->getMessage());
        }
    }
}