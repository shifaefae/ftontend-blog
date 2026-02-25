<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EjurnalController extends Controller
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
     * GET /api/ejurnals
     */
    public function index()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'ejurnals');

            $result   = $response->json();
            $ejurnals = $result['data'] ?? [];

            return view('pages.Ejurnal', compact('ejurnals'));

        } catch (\Exception $e) {
            return view('pages.Ejurnal', ['ejurnals' => []])
                ->with('error', 'Gagal memuat data e-jurnal.');
        }
    }

    /**
     * POST /api/ejurnals
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

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');

                $response = $http->attach(
                    'thumbnail',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post(env('API_BASE_URL') . 'ejurnals', $fields);
            } else {
                $response = $http->post(env('API_BASE_URL') . 'ejurnals', $fields);
            }

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('ejurnal.index')
                    ->with('success', 'E-Jurnal berhasil ditambahkan!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * PUT /api/ejurnals/{id}
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
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

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');

                $response = $http->attach(
                    'thumbnail',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post(env('API_BASE_URL') . 'ejurnals/' . $id, $fields);
            } else {
                unset($fields['_method']);
                $response = $http->put(env('API_BASE_URL') . 'ejurnals/' . $id, $fields);
            }

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('ejurnal.index')
                    ->with('success', 'E-Jurnal berhasil diupdate!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal mengupdate.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
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
                    ->with('success', 'E-Jurnal berhasil dihapus!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menghapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }
}