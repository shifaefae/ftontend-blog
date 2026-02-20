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

    public function index()
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        // Ambil data user terbaru dari BE
        // BE: GET /api/me — return { success, user }
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->get(env('API_BASE_URL') . 'me');

            if ($response->successful()) {
                $user = $response->json()['user'] ?? session('user');
                // Update session dengan data terbaru
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
     * BE: PUT /api/profile
     * UpdateProfileRequest field: name, email, current_password, password, password_confirmation
     * CATATAN: BE cek current_password hanya jika 'password' diisi
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'                  => 'required',
            'password_baru'                  => 'required|min:8|confirmed',
        ], [
            'password_lama.required'         => 'Password lama wajib diisi.',
            'password_baru.required'         => 'Password baru wajib diisi.',
            'password_baru.min'              => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        try {
            // BE endpoint: PUT /api/profile
            // Field: current_password, password, password_confirmation
            $response = Http::timeout(10)
                ->withHeaders($this->apiHeaders())
                ->put(env('API_BASE_URL') . 'profile', [
                    'current_password'      => $request->password_lama,
                    'password'              => $request->password_baru,
                    'password_confirmation' => $request->password_baru_confirmation,
                ]);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return back()->with('success', $result['message'] ?? 'Password berhasil diperbarui.');
            }

            // BE return 422 jika password lama salah
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