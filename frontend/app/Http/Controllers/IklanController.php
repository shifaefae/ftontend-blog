<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IklanController extends Controller
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
     * GET /api/iklans — PUBLIC
     * Response: { success, data: [ { id, name, thumbnail, link, position, priority, status, user } ], media }
     */
    public function index()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'iklans');

            $result = $response->json();
            $iklans = $result['data'] ?? [];

            return view('pages.Iklan', compact('iklans'));

        } catch (\Exception $e) {
            return view('pages.Iklan', ['iklans' => []])
                ->with('error', 'Gagal memuat data iklan.');
        }
    }

    /**
     * POST /api/iklans
     * BE field: name, thumbnail (WAJIB file), link, position, priority, status
     * Posisi: Sesuai BE — position bukan tipe
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'thumbnail' => 'required|image|max:2048',
            'position'  => 'required|in:1:1 Slide,3:1 Kanan,3:1 Kiri',
            'link'      => 'nullable|url',
            'priority'  => 'nullable|integer|min:1',
            'status'    => 'required|in:active,inactive',
        ]);

        try {
            $file = $request->file('thumbnail');

            $response = Http::timeout(30)
                ->withHeaders($this->apiHeaders())
                ->attach(
                    'thumbnail',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )
                ->post(env('API_BASE_URL') . 'iklans', [
                    'name'     => $request->name,
                    'link'     => $request->link ?? '',
                    'position' => $request->position,
                    'priority' => $request->priority ?? 1,
                    'status'   => $request->status,
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('iklan.list')
                    ->with('success', $result['message'] ?? 'Iklan berhasil ditambahkan');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menambahkan iklan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * GET /api/iklans/{id}
     */
    public function edit($id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'iklans/' . $id);

            if ($response->failed()) abort(404);

            $iklan = $response->json()['data'] ?? null;
            if (!$iklan) abort(404);

            return view('pages.editiklan', compact('iklan'));

        } catch (\Exception $e) {
            abort(500);
        }
    }

    /**
     * PUT /api/iklans/{id}
     * BE field: name, thumbnail (optional file), link, position, priority, status
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'position'  => 'required|in:1:1 Slide,3:1 Kanan,3:1 Kiri',
            'link'      => 'nullable|url',
            'priority'  => 'nullable|integer|min:1',
            'status'    => 'required|in:active,inactive',
        ]);

        try {
            $fields = [
                'name'     => $request->name,
                'link'     => $request->link ?? '',
                'position' => $request->position,
                'priority' => $request->priority ?? 1,
                'status'   => $request->status,
            ];

            if ($request->hasFile('thumbnail')) {
                $file     = $request->file('thumbnail');
                $response = Http::timeout(30)
                    ->withHeaders($this->apiHeaders())
                    ->attach(
                        'thumbnail',
                        file_get_contents($file->getRealPath()),
                        $file->getClientOriginalName()
                    )
                    ->post(env('API_BASE_URL') . 'iklans/' . $id, array_merge($fields, ['_method' => 'PUT']));
            } else {
                $response = Http::timeout(30)
                    ->withHeaders($this->apiHeaders())
                    ->put(env('API_BASE_URL') . 'iklans/' . $id, $fields);
            }

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('iklan.list')
                    ->with('success', $result['message'] ?? 'Iklan berhasil diupdate');
            }

            return back()->with('error', $result['message'] ?? 'Gagal update iklan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * DELETE /api/iklans/{id}
     */
    public function destroy($id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->delete(env('API_BASE_URL') . 'iklans/' . $id);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('iklan.list')
                    ->with('success', $result['message'] ?? 'Iklan berhasil dihapus');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menghapus iklan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }
}