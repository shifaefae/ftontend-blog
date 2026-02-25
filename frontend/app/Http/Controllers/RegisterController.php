<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
     private function apiHeaders(): array
    {
        return [
            'X-API-KEY'     => env('API_KEY'),
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept'        => 'application/json',
        ];
    }
    
    public function show()
    {
        if (session()->has('api_token')) {
            return redirect()->route('dashboard');
        }
        return view('component.Register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-API-KEY'                   => env('API_KEY'),
                    'Accept'                      => 'application/json',
                    'ngrok-skip-browser-warning'  => 'true',
                ])
                ->post(env('API_BASE_URL') . 'register', [
                    'name'                  => $request->name,
                    'email'                 => $request->email,
                    'password'              => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    // role TIDAK dikirim — backend hardcode 'user'
                ]);

            $result = $response->json();

            if ($response->status() === 422) {
                return back()
                    ->withInput($request->only('name', 'email'))
                    ->withErrors($result['errors'] ?? [])
                    ->with('error', $result['message'] ?? 'Validasi gagal.');
            }

            if ($response->failed() || !($result['success'] ?? false)) {
                return back()
                    ->withInput($request->only('name', 'email'))
                    ->with('error', $result['message'] ?? 'Registrasi gagal.');
            }

            // Simpan ke session — langsung login setelah register
            session([
                'api_token' => $result['token'],
                'user'      => $result['user'],
                'is_login'  => true,
            ]);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('name', 'email'))
                ->with('error', 'Server tidak dapat diakses. Coba lagi nanti.');
        }
    }
}
