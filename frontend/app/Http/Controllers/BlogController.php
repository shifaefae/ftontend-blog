<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class BlogController extends Controller
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
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas/admin');

            $result  = $response->json();
            $beritas = $result['data'] ?? [];

            return view('pages.Listblog', ['blogs' => $beritas]);

        } catch (\Exception $e) {
            return view('pages.Listblog', ['blogs' => []])
                ->with('error', 'Gagal memuat data blog.');
        }
    }

    public function create()
    {
        $kategoris = [];
        $tags      = [];

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'kategoris-tags');

            if ($response->successful()) {
                $data      = $response->json()['data'] ?? [];
                $kategoris = $data['Kategoris'] ?? [];
                $tags      = $data['tags']       ?? [];
            }
        } catch (\Exception $e) {}

        return view('pages.Tambahblog', compact('kategoris', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'status'      => 'required|in:draft,published',
        ]);

        try {
            $http = Http::timeout(30)->withHeaders($this->apiHeaders());

            // ✅ FIX: kirim kategori_ids dan tag_ids sebagai array yang benar
            $fields = [
                'title'        => $request->title,
                'description'  => $request->description,
                'status'       => $request->status,
                'kategori_ids' => (array) $request->input('kategori_ids', []),
                'tag_ids'      => (array) $request->input('tag_ids', []),
            ];

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $http = $http->attach(
                    'thumbnail',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );

                // Untuk multipart + array, harus pakai asMultipart
                $multipart = [];
                foreach ($fields as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            $multipart[] = ['name' => $key . '[]', 'contents' => $item];
                        }
                    } else {
                        $multipart[] = ['name' => $key, 'contents' => $value];
                    }
                }
                $multipart[] = [
                    'name'     => 'thumbnail',
                    'contents' => fopen($request->file('thumbnail')->getRealPath(), 'r'),
                    'filename' => $request->file('thumbnail')->getClientOriginalName(),
                ];

                $response = Http::timeout(30)
                    ->withHeaders($this->apiHeaders())
                    ->asMultipart()
                    ->post(env('API_BASE_URL') . 'beritas', $multipart);
            } else {
                $response = $http->post(env('API_BASE_URL') . 'beritas', $fields);
            }

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('blog.list')
                    ->with('success', $result['message'] ?? 'Blog berhasil ditambahkan!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menambahkan blog.')->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas/' . $id);

            if ($response->failed()) abort(404);

            $blog = $response->json()['data'] ?? null;
            if (!$blog) abort(404);

            $kategoris = [];
            $tags      = [];
            $katResponse = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'kategoris-tags');

            if ($katResponse->successful()) {
                $data      = $katResponse->json()['data'] ?? [];
                $kategoris = $data['Kategoris'] ?? [];
                $tags      = $data['tags']       ?? [];
            }

            return view('pages.Editblog', compact('blog', 'kategoris', 'tags'));

        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'status'      => 'required|in:draft,published',
        ]);

        try {
            // ✅ FIX: kirim kategori_ids dan tag_ids sebagai array yang benar
            $fields = [
                'title'        => $request->title,
                'description'  => $request->description,
                'status'       => $request->status,
                'kategori_ids' => (array) $request->input('kategori_ids', []),
                'tag_ids'      => (array) $request->input('tag_ids', []),
            ];

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $multipart = [];
                foreach ($fields as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            $multipart[] = ['name' => $key . '[]', 'contents' => $item];
                        }
                    } else {
                        $multipart[] = ['name' => $key, 'contents' => $value];
                    }
                }
                $multipart[] = ['name' => '_method', 'contents' => 'PUT'];
                $multipart[] = [
                    'name'     => 'thumbnail',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ];

                $response = Http::timeout(30)
                    ->withHeaders($this->apiHeaders())
                    ->asMultipart()
                    ->post(env('API_BASE_URL') . 'beritas/' . $id, $multipart);
            } else {
                $response = Http::timeout(30)
                    ->withHeaders($this->apiHeaders())
                    ->put(env('API_BASE_URL') . 'beritas/' . $id, $fields);
            }

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('blog.list')
                    ->with('success', $result['message'] ?? 'Blog berhasil diupdate!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal mengupdate blog.')->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->delete(env('API_BASE_URL') . 'beritas/' . $id);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('blog.list')
                    ->with('success', $result['message'] ?? 'Blog berhasil dihapus!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menghapus blog.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }
}