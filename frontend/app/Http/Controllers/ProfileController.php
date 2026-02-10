<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.Profile');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        // contoh user dari session (sesuaikan jika pakai auth)
        $user = session('user');

        if (!Hash::check($request->password_lama, $user['password'])) {
            return back()->withErrors(['password_lama' => 'Password lama salah']);
        }

        // update password ke session (atau database)
        $user['password'] = Hash::make($request->password_baru);
        session(['user' => $user]);

        return back()->with('success', 'Password berhasil diubah');
    }
}
