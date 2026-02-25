<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IklanController extends Controller
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

    /**
     * GET /iklans — halaman list
     */
    public function index()
    {
        try {
            $url = env('API_BASE_URL') . 'iklans';

            $response = Http::timeout(15)
                ->withHeaders($this->apiHeaders())
                ->get($url);

            // Log untuk debug — cek di storage/logs/laravel.log
            Log::info('[Iklan] index', [
                'url'    => $url,
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500), // batasi 500 char
            ]);

            if ($response->failed()) {
                return view('pages.Iklan', ['iklans' => []])
                    ->with('error', 'API error HTTP ' . $response->status() . ': ' . $response->body());
            }

            $result = $response->json();
            $iklans = $result['data'] ?? [];

            return view('pages.Iklan', compact('iklans'));

        } catch (\Exception $e) {
            Log::error('[Iklan] index exception: ' . $e->getMessage());
            return view('pages.Iklan', ['iklans' => []])
                ->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * POST /iklans
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'thumbnail' => 'required|image|max:2048',
            'position'  => 'required|in:slide_1x1,right_3x1,left_3x1',
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

            return back()->with('error', $result['message'] ?? 'Gagal menambahkan iklan. HTTP: ' . $response->status());

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses: ' . $e->getMessage());
        }
    }

    /**
     * GET /iklans/{id}/edit
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
     * PUT /iklans/{id}
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'position'  => 'required|in:slide_1x1,right_3x1,left_3x1',
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
                $file = $request->file('thumbnail');
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

            return back()->with('error', $result['message'] ?? 'Gagal update iklan. HTTP: ' . $response->status());

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses: ' . $e->getMessage());
        }
    }

    /**
     * DELETE /iklans/{id}
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