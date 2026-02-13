<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan Halaman Profil
     */
    public function index()
    {
        //  Cek apakah user sudah login
        if (!Session::has('is_login')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login dulu.');
        }

        //  Ambil data user dari session
        $user = Session::get('user');

        // Kirim ke view (lebih clean daripada panggil session di blade)
        return view('pages.Profile', compact('user'));
    }

    /**
     * Update Password (Versi Aman)
     */
    public function updatePassword(Request $request)
    {
        //  1. Validasi Input
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        //  2. Pastikan user login
        if (!Session::has('is_login')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login dulu.');
        }

        //  3. Ambil user dari session
        $user = Session::get('user');

        //  4. Cek password lama (AMAN)
        if (!Hash::check($request->password_lama, $user['password'])) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.'
            ]);
        }

        //  5. Hash password baru
        $user['password'] = Hash::make($request->password_baru);

        //  6. Update session
        Session::put('user', $user);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
