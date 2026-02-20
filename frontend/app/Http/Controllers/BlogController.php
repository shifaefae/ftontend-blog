<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class BlogController extends Controller
{
    /**
     * Header helper — semua request protected butuh ini
     */
    private function apiHeaders(): array
    {
        return [
            'X-API-KEY'     => env('API_KEY'),
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept'        => 'application/json',
        ];
    }

    /**
     * GET /api/beritas — PUBLIC (tapi kita kirim token agar admin bisa lihat draft juga)
     * Response: { success, data: [ { id, title, slug, description, thumbnail, status, user, kategoris, tags } ] }
     */
    public function index()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas');

            $result  = $response->json();
            $beritas = $result['data'] ?? [];

            return view('pages.Listblog', ['blogs' => $beritas]);

        } catch (\Exception $e) {
            return view('pages.Listblog', ['blogs' => []])
                ->with('error', 'Gagal memuat data blog.');
        }
    }

    /**
     * Halaman form tambah blog
     * Ambil kategori & tag dari BE untuk dropdown
     */
    public function create()
    {
        $kategoris = [];
        $tags      = [];

        try {
            // GET /api/kategoris-tags — PUBLIC, ambil kategori + tag sekaligus
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'kategoris-tags');

            if ($response->successful()) {
                $data      = $response->json()['data'] ?? [];
                $kategoris = $data['Kategoris'] ?? [];
                $tags      = $data['tags']       ?? [];
            }
        } catch (\Exception $e) {
            // Biarkan kosong
        }

        return view('pages.Tambahblog', compact('kategoris', 'tags'));
    }

    /**
     * POST /api/beritas
     * BE field: title, description, thumbnail (file), status, kategori_ids[], tag_ids[]
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'status'      => 'required|in:draft,published',
        ]);

        try {
            $http = Http::timeout(30)->withHeaders($this->apiHeaders());

            // Siapkan field sesuai BE StoreBeritaRequest
            $fields = [
                'title'       => $request->title,
                'description' => $request->description,
                'status'      => $request->status,
            ];

            // kategori_ids dan tag_ids dikirim sebagai array
            if ($request->filled('kategori_ids')) {
                foreach ((array) $request->kategori_ids as $kid) {
                    $fields['kategori_ids[]'] = $kid;
                }
            }
            if ($request->filled('tag_ids')) {
                foreach ((array) $request->tag_ids as $tid) {
                    $fields['tag_ids[]'] = $tid;
                }
            }

            if ($request->hasFile('thumbnail')) {
                $file     = $request->file('thumbnail');
                $response = $http->attach(
                    'thumbnail',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post(env('API_BASE_URL') . 'beritas', $fields);
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
            return back()->with('error', 'Server API tidak dapat diakses.')->withInput();
        }
    }

    /**
     * Halaman form edit blog
     * GET /api/beritas/{id}
     * Response: { success, data: { id, title, slug, description, thumbnail, status, user, kategoris, tags } }
     */
    public function edit($id)
    {
        try {
            // Ambil data berita
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'beritas/' . $id);

            if ($response->failed()) {
                abort(404);
            }

            $blog = $response->json()['data'] ?? null;
            if (!$blog) abort(404);

            // Ambil kategori & tag untuk dropdown
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

    /**
     * PUT /api/beritas/{id}
     * BE field: title, description, thumbnail (file, optional), status, kategori_ids[], tag_ids[]
     * CATATAN: Http facade tidak support PUT + multipart, harus spoofing POST + _method=PUT
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'status'      => 'required|in:draft,published',
        ]);

        try {
            $http = Http::timeout(30)->withHeaders($this->apiHeaders());

            $fields = [
                'title'       => $request->title,
                'description' => $request->description,
                'status'      => $request->status,
                '_method'     => 'PUT',
            ];

            if ($request->filled('kategori_ids')) {
                foreach ((array) $request->kategori_ids as $kid) {
                    $fields['kategori_ids[]'] = $kid;
                }
            }
            if ($request->filled('tag_ids')) {
                foreach ((array) $request->tag_ids as $tid) {
                    $fields['tag_ids[]'] = $tid;
                }
            }

            if ($request->hasFile('thumbnail')) {
                $file     = $request->file('thumbnail');
                $response = $http->attach(
                    'thumbnail',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post(env('API_BASE_URL') . 'beritas/' . $id, $fields);
            } else {
                // Tanpa file bisa langsung PUT
                unset($fields['_method']);
                $response = $http->put(env('API_BASE_URL') . 'beritas/' . $id, $fields);
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

    /**
     * DELETE /api/beritas/{id}
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

            return back()->with('error', $result['message'] ?? 'Gagal menghapus blog.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }
}