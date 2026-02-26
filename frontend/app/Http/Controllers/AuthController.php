<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function show()
    {
        if (session()->has('api_token')) {
            return redirect()->route('dashboard');
        }
        return view('component.Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-API-KEY' => env('API_KEY'),
                    'Accept'    => 'application/json',
                ])
                ->post(env('API_BASE_URL') . 'login', [
                    'email'    => $request->email,
                    'password' => $request->password,
                ]);

            $result = $response->json();

            if ($response->failed() || !($result['success'] ?? false)) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', $result['message'] ?? 'Email atau password salah.');
            }

            // Simpan ke session — tambah 'role' dari data user
            session([
                'api_token' => $result['token'],
                'user'      => $result['user'],
                'role'      => $result['user']['role'] ?? 'user', // ← tambahan ini
                'is_login'  => true,
            ]);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Server API tidak dapat diakses. Coba lagi nanti.');
        }
    }

    public function logout()
    {
        try {
            Http::timeout(10)
                ->withHeaders([
                    'X-API-KEY'     => env('API_KEY'),
                    'Authorization' => 'Bearer ' . session('api_token'),
                    'Accept'        => 'application/json',
                ])
                ->post(env('API_BASE_URL') . 'logout');
        } catch (\Exception $e) {}

        session()->flush();
        return redirect()->route('login');
    }
}