<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    /**
     * Simulasi data admin (dummy data)
     */
    private function getDummyData(): Collection
    {
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = [
                'id' => $i,
                'name' => 'Admin ' . $i,
                'email' => 'admin' . $i . '@mail.com',
                'password' => '********',
                'photo' => 'https://i.pravatar.cc/100?img=' . $i,
            ];
        }

        return collect($data);
    }

    /**
     * Menampilkan list admin + search + pagination
     */
    public function index(Request $request)
    {
        // ✅ WAJIB: data dikirim ke blade sebagai $admins
        $admins = $this->getDummyData();

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $admins = $admins->filter(function ($admin) use ($search) {
                return str_contains(strtolower($admin['name']), $search)
                    || str_contains(strtolower($admin['email']), $search);
            });
        }

        // 📄 PAGINATION MANUAL
        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $currentItems = $admins
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $admins = new LengthAwarePaginator(
            $currentItems,
            $admins->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        // ✅ INI KUNCI UTAMA (mengirim $admins ke Blade)
        return view('pages.Admin', compact('admins'));
    }

    /**
     * Simulasi tambah admin
     */
    public function store(Request $request)
    {
        return back()->with('success', '[SIMULASI] Admin berhasil ditambahkan');
    }

    /**
     * Simulasi update admin
     */
    public function update(Request $request, $id)
    {
        return back()->with('success', '[SIMULASI] Admin berhasil diupdate');
    }

    /**
     * Simulasi hapus admin
     */
    public function destroy($id)
    {
        return back()->with('success', '[SIMULASI] Admin berhasil dihapus');
    }
}
