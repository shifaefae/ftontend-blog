<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    private function apiHeaders(): array
    {
        return [
            'X-API-KEY'     => env('API_KEY'),
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept'        => 'application/json',
        ];
    }

    public function index(Request $request)
    {
        $users       = [];
        $currentPage = 1;
        $lastPage    = 1;
        $total       = 0;
        $debugError  = null;

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'users', [
                    'per_page' => 10,
                    'page'     => $request->get('page', 1),
                ]);

            $result = $response->json();

            // ✅ DEBUG — hapus setelah berhasil
            Log::info('Admin index response', [
                'status' => $response->status(),
                'body'   => $result,
            ]);

            if ($response->successful() && ($result['success'] ?? false)) {
                $paginated   = $result['data'];
                $users       = $paginated['data']         ?? [];
                $currentPage = $paginated['current_page'] ?? 1;
                $lastPage    = $paginated['last_page']    ?? 1;
                $total       = $paginated['total']        ?? 0;
            } else {
                // Tangkap error message dari BE
                $debugError = 'Status: ' . $response->status() . ' | Response: ' . json_encode($result);
            }

        } catch (\Exception $e) {
            $debugError = 'Exception: ' . $e->getMessage();
            Log::error('Admin index error', ['error' => $e->getMessage()]);
        }

        return view('pages.Admin', compact('users', 'currentPage', 'lastPage', 'total', 'debugError'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
            'role'                  => 'required|in:super_admin,admin,user',
        ]);

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->post(env('API_BASE_URL') . 'register', [
                    'name'                  => $request->name,
                    'email'                 => $request->email,
                    'password'              => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'role'                  => $request->role,
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('admin.index')
                    ->with('success', 'User berhasil ditambahkan!');
            }

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', $result['message'] ?? 'Gagal menambahkan user.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'role'     => 'required|in:super_admin,admin,user',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            $payload = [
                'name'  => $request->name,
                'email' => $request->email,
                'role'  => $request->role,
            ];

            if ($request->filled('password')) {
                $payload['password']              = $request->password;
                $payload['password_confirmation'] = $request->password_confirmation;
            }

            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->put(env('API_BASE_URL') . 'users/' . $id, $payload);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('admin.index')
                    ->with('success', 'User berhasil diupdate!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal mengupdate user.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    public function destroy($id)
    {
        if (session('user.id') == $id) {
            return back()->with('error', 'Tidak bisa menghapus akun Anda sendiri!');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->delete(env('API_BASE_URL') . 'users/' . $id);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return redirect()->route('admin.index')
                    ->with('success', 'User berhasil dihapus!');
            }

            return back()->with('error', $result['message'] ?? 'Gagal menghapus user.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }
}