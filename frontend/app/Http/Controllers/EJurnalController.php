<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EJurnalController extends Controller
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
     * GET /api/ejurnals — PUBLIC
     * Response: { success, data: [ { id, title, slug, description, thumbnail, status, user, gambarEjurnals } ], media }
     */
    public function index()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'ejurnals');

            $result   = $response->json();
            $ejurnals = $result['data']  ?? [];
            $media    = $result['media'] ?? env('MEDIA_BASE_URL');

            return view('pages.Ejurnal', compact('ejurnals', 'media'));

        } catch (\Exception $e) {
            return view('pages.Ejurnal', ['ejurnals' => [], 'media' => env('MEDIA_BASE_URL')])
                ->with('error', 'Gagal memuat data e-jurnal.');
        }
    }

    /**
     * POST /api/ejurnals
     * BE field: title, description (optional), thumbnail (optional file), status, gambar_ejurnals[] (optional files)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'status'      => 'required|in:draft,published',
        ]);

        try {
            $http = Http::timeout(30)->withHeaders($this->apiHeaders());

            $fields = [
                'title'       => $request->title,
                'description' => $request->description ?? '',
                'status'      => $request->status,
            ];

            // Build multipart jika ada file
            if ($request->hasFile('thumbnail') || $request->hasFile('gambar_ejurnals')) {
                $pendingRequest = $http;

                if ($request->hasFile('thumbnail')) {
                    $f = $request->file('thumbnail');
                    $pendingRequest = $pendingRequest->attach(
                        'thumbnail',
                        file_get_contents($f->getRealPath()),
                        $f->getClientOriginalName()
                    );
                }

                if ($request->hasFile('gambar_ejurnals')) {
                    foreach ($request->file('gambar_ejurnals') as $index => $gambar) {
                        $pendingRequest = $pendingRequest->attach(
                            "gambar_ejurnals[{$index}]",
                            file_get_contents($gambar->getRealPath()),
                            $gambar->getClientOriginalName()
                        );
                    }
                }

                $response = $pendingRequest->post(env('API_BASE_URL') . 'ejurnals', $fields);
            } else {
                $response = $http->post(env('API_BASE_URL') . 'ejurnals', $fields);
            }

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('ejurnal.index')
                    ->with('success', $result['message'] ?? 'E-Jurnal berhasil ditambahkan!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menambahkan e-jurnal.')->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.')->withInput();
        }
    }

    /**
     * PUT /api/ejurnals/{id}
     * BE field: title, description, thumbnail (optional file), status, gambar_ejurnals[] (optional)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'status'      => 'required|in:draft,published',
        ]);

        try {
            $fields = [
                'title'       => $request->title,
                'description' => $request->description ?? '',
                'status'      => $request->status,
                '_method'     => 'PUT',
            ];

            $http = Http::timeout(30)->withHeaders($this->apiHeaders());

            if ($request->hasFile('thumbnail') || $request->hasFile('gambar_ejurnals')) {
                $pendingRequest = $http;

                if ($request->hasFile('thumbnail')) {
                    $f = $request->file('thumbnail');
                    $pendingRequest = $pendingRequest->attach(
                        'thumbnail',
                        file_get_contents($f->getRealPath()),
                        $f->getClientOriginalName()
                    );
                }

                if ($request->hasFile('gambar_ejurnals')) {
                    foreach ($request->file('gambar_ejurnals') as $index => $gambar) {
                        $pendingRequest = $pendingRequest->attach(
                            "gambar_ejurnals[{$index}]",
                            file_get_contents($gambar->getRealPath()),
                            $gambar->getClientOriginalName()
                        );
                    }
                }

                $response = $pendingRequest->post(env('API_BASE_URL') . 'ejurnals/' . $id, $fields);
            } else {
                unset($fields['_method']);
                $response = $http->put(env('API_BASE_URL') . 'ejurnals/' . $id, $fields);
            }

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('ejurnal.index')
                    ->with('success', $result['message'] ?? 'E-Jurnal berhasil diupdate!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal mengupdate e-jurnal.')->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.')->withInput();
        }
    }

    /**
     * DELETE /api/ejurnals/{id}
     */
    public function destroy($id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->delete(env('API_BASE_URL') . 'ejurnals/' . $id);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('ejurnal.index')
                    ->with('success', $result['message'] ?? 'E-Jurnal berhasil dihapus!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menghapus e-jurnal.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * DELETE /api/ejurnals/gambar/{id}
     * Hapus satu gambar dari galeri ejurnal
     */
    public function deleteGambar($id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->delete(env('API_BASE_URL') . 'ejurnals/gambar/' . $id);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return back()->with('success', $result['message'] ?? 'Gambar berhasil dihapus!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menghapus gambar.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }
}