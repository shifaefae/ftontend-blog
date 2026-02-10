@extends('layout.App')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-8">

    <h1 class="text-3xl font-bold">Profile Saya</h1>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- CARD PROFILE --}}
    <div class="bg-white shadow rounded-lg p-6 space-y-6">

        {{-- FOTO & IDENTITAS --}}
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold">
                {{ substr(session('user.name', 'SA'), 0, 2) }}
            </div>

            <div>
                <p class="text-xl font-semibold">
                    {{ session('user.name', 'Super Admin') }}
                </p>
                <p class="text-gray-600">
                    {{ session('user.email', 'admin@mail.com') }}
                </p>
            </div>
        </div>

        <hr>

        {{-- INFO AKUN --}}
        <div class="space-y-2">
            <p><strong>Nama:</strong> {{ session('user.name') }}</p>
            <p><strong>Email:</strong> {{ session('user.email') }}</p>
            <p><strong>Password:</strong> ••••••••</p>
        </div>

        <hr>

        {{-- FORM GANTI PASSWORD --}}
        <h2 class="text-xl font-bold">Ubah Password</h2>

        <form method="POST" action="{{ route('profile.update.password') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium">Password Lama</label>
                <input type="password" name="password_lama"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Password Baru</label>
                <input type="password" name="password_baru"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Konfirmasi Password Baru</label>
                <input type="password" name="password_baru_confirmation"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Simpan Password
            </button>
        </form>

    </div>
</div>
@endsection
