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
            // BE: POST /api/login  — butuh X-API-KEY header
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

            // BE return: { success, message, token, user }
            if ($response->failed() || !($result['success'] ?? false)) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', $result['message'] ?? 'Email atau password salah.');
            }

            // Simpan ke session
            session([
                'api_token' => $result['token'],
                'user'      => $result['user'],   // { id, name, email, role, ... }
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
        // BE: POST /api/logout — butuh Bearer token
        try {
            Http::timeout(10)
                ->withHeaders([
                    'X-API-KEY'     => env('API_KEY'),
                    'Authorization' => 'Bearer ' . session('api_token'),
                    'Accept'        => 'application/json',
                ])
                ->post(env('API_BASE_URL') . 'logout');
        } catch (\Exception $e) {
            // Tetap logout meski API gagal
        }

        session()->flush();
        return redirect()->route('login');
    }
}