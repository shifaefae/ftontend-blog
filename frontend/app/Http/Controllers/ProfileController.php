<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
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
     * Tampilkan halaman profil.
     * Ambil data user terbaru dari BE: GET /api/me
     */
    public function index()
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'me');

            if ($response->successful()) {
                $user = $response->json()['user'] ?? session('user');
                session(['user' => $user]);
            } else {
                $user = session('user');
            }
        } catch (\Exception $e) {
            $user = session('user');
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login ulang.');
        }

        return view('pages.Profile', compact('user'));
    }

    /**
     * Update nama & email.
     * BE endpoint: PUT /api/profile
     */
    public function updateInfo(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'name.string'    => 'Nama harus berupa teks.',
            'name.max'       => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->put(env('API_BASE_URL') . 'profile', [
                    'name'  => $request->name,
                    'email' => $request->email,
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                // Update session dengan data terbaru dari BE
                session(['user' => $result['user'] ?? session('user')]);
                return back()->with('success_info', $result['message'] ?? 'Profil berhasil diperbarui.');
            }

            // BE return 422 jika email sudah dipakai
            if ($response->status() === 422) {
                $errors = $result['errors'] ?? [];
                if (!empty($errors['email'])) {
                    return back()->withErrors(['email' => $errors['email'][0]])->withInput();
                }
                return back()->with('error', $result['message'] ?? 'Gagal memperbarui profil.')->withInput();
            }

            return back()->with('error', $result['message'] ?? 'Gagal memperbarui profil.')->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.')->withInput();
        }
    }

    /**
     * Upload foto profil.
     * BE endpoint: POST /api/profile/photo
     * Field: thumbnail (file)
     */
    public function updatePhoto(Request $request)
    {
        $isAjax = $request->ajax() || $request->wantsJson() || $request->hasHeader('X-Requested-With');

        $request->validate([
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'thumbnail.required' => 'Foto wajib dipilih.',
            'thumbnail.image'    => 'File harus berupa gambar.',
            'thumbnail.mimes'    => 'Format foto harus jpg, jpeg, png, atau webp.',
            'thumbnail.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        if (!session()->has('api_token')) {
            if ($isAjax) return response()->json(['success' => false, 'message' => 'Silakan login dulu.'], 401);
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders($this->apiHeaders())
                ->attach(
                    'thumbnail',
                    file_get_contents($request->file('thumbnail')->getRealPath()),
                    $request->file('thumbnail')->getClientOriginalName()
                )
                ->post(env('API_BASE_URL') . 'profile/photo');

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                // Update thumbnail di session
                $user = session('user');
                $user['thumbnail'] = $result['thumbnail'] ?? $user['thumbnail'];
                session(['user' => $user]);

                if ($isAjax) return response()->json(['success' => true, 'message' => 'Foto profil berhasil diperbarui.']);
                return back()->with('success_photo', 'Foto profil berhasil diperbarui.');
            }

            if ($isAjax) return response()->json(['success' => false, 'message' => $result['message'] ?? 'Gagal mengupload foto.']);
            return back()->with('error', $result['message'] ?? 'Gagal mengupload foto.');

        } catch (\Exception $e) {
            if ($isAjax) return response()->json(['success' => false, 'message' => 'Server API tidak dapat diakses.'], 500);
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }

    /**
     * Update password.
     * BE endpoint: PUT /api/profile
     * Field: current_password, password, password_confirmation
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ], [
            'password_lama.required'  => 'Password lama wajib diisi.',
            'password_baru.required'  => 'Password baru wajib diisi.',
            'password_baru.min'       => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->put(env('API_BASE_URL') . 'profile', [
                    'current_password'      => $request->password_lama,
                    'password'              => $request->password_baru,
                    'password_confirmation' => $request->password_baru_confirmation,
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return back()->with('success_password', $result['message'] ?? 'Password berhasil diperbarui.');
            }

            if ($response->status() === 422) {
                return back()->withErrors([
                    'password_lama' => $result['message'] ?? 'Password lama tidak sesuai.',
                ]);
            }

            return back()->with('error', $result['message'] ?? 'Gagal memperbarui password.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server API tidak dapat diakses.');
        }
    }
}