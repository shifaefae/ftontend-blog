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

    /**
     * List semua blog (published + draft) - menggunakan endpoint admin
     */
    public function index(Request $request)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas/admin');

            $result  = $response->json();
            $beritas = $result['data'] ?? [];

            // Filter lokal jika ada parameter search
            if ($request->filled('search')) {
                $keyword = strtolower($request->search);
                $beritas = array_filter($beritas, function ($b) use ($keyword) {
                    return str_contains(strtolower($b['title'] ?? ''), $keyword)
                        || str_contains(strtolower($b['description'] ?? ''), $keyword);
                });
                $beritas = array_values($beritas);
            }

            return view('pages.Listblog', ['blogs' => $beritas]);

        } catch (\Exception $e) {
            return view('pages.Listblog', ['blogs' => []])
                ->with('error', 'Gagal memuat data blog: ' . $e->getMessage());
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
            $fields = [
                'title'        => $request->title,
                'description'  => $request->description,
                'status'       => $request->status,
                'kategori_ids' => (array) $request->input('kategori_ids', []),
                'tag_ids'      => (array) $request->input('tag_ids', []),
            ];

            if ($request->hasFile('thumbnail')) {
                $file      = $request->file('thumbnail');
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
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ];

                $response = Http::timeout(30)
                    ->withHeaders($this->apiHeaders())
                    ->asMultipart()
                    ->post(env('API_BASE_URL') . 'beritas', $multipart);
            } else {
                $response = Http::timeout(30)
                    ->withHeaders($this->apiHeaders())
                    ->post(env('API_BASE_URL') . 'beritas', $fields);
            }

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('blog.list')
                    ->with('success', $result['message'] ?? 'Blog berhasil ditambahkan!');
            }

            return back()
                ->with('error', $result['message'] ?? 'Gagal menambahkan blog.')
                ->withInput();

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Server API tidak dapat diakses: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit blog — bisa untuk status published maupun draft
     */
    public function edit($id)
    {
        try {
            // Ambil detail berita (endpoint show by ID — tidak filter status published)
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas/' . $id);

            // Jika endpoint show hanya mengembalikan published, gunakan admin endpoint
            // Fallback: coba dari list admin jika show gagal
            if ($response->failed() || empty($response->json()['data'])) {
                $listResponse = Http::timeout(10)
                    ->withHeaders($this->apiHeaders())
                    ->get(env('API_BASE_URL') . 'beritas/admin');

                if ($listResponse->successful()) {
                    $allBeritas = $listResponse->json()['data'] ?? [];
                    $blog = collect($allBeritas)->firstWhere('id', (int) $id)
                         ?? collect($allBeritas)->firstWhere('id', (string) $id);

                    if (!$blog) abort(404);
                } else {
                    abort(404);
                }
            } else {
                $blog = $response->json()['data'] ?? null;
                if (!$blog) abort(404);
            }

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
            abort(500, 'Gagal memuat data: ' . $e->getMessage());
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
            $fields = [
                'title'        => $request->title,
                'description'  => $request->description,
                'status'       => $request->status,
                'kategori_ids' => (array) $request->input('kategori_ids', []),
                'tag_ids'      => (array) $request->input('tag_ids', []),
            ];

            if ($request->hasFile('thumbnail')) {
                $file      = $request->file('thumbnail');
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
                $multipart[] = ['name' => '_method',   'contents' => 'PUT'];
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

            return back()
                ->with('error', $result['message'] ?? 'Gagal mengupdate blog.')
                ->withInput();

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Server API tidak dapat diakses: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus blog — bisa untuk status published maupun draft
     */
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

            return redirect()->route('blog.list')
                ->with('error', $result['message'] ?? 'Gagal menghapus blog.');

        } catch (\Exception $e) {
            return redirect()->route('blog.list')
                ->with('error', 'Server API tidak dapat diakses: ' . $e->getMessage());
        }
    }
}