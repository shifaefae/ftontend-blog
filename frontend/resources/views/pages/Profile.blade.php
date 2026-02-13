@extends('layout.App')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-8">

    <h1 class="text-3xl font-bold text-gray-800">Profile Saya</h1>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- ALERT ERROR GLOBAL --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
            <p class="font-bold">Gagal</p>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl p-6 space-y-8">

        {{-- FOTO & IDENTITAS --}}
        <div class="flex flex-col md:flex-row items-center gap-6">

            {{-- Foto Profil --}}
            <div class="shrink-0">
                <img src="{{ $user['photo'] ?? 'https://ui-avatars.com/api/?name='.urlencode($user['name']) }}" 
                     alt="Foto Profil"
                     class="w-24 h-24 rounded-full object-cover border-4 border-blue-50 shadow-md">
            </div>

            <div class="text-center md:text-left space-y-1">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $user['name'] }}
                </h2>
                <p class="text-blue-600 font-medium">
                    {{ $user['role'] ?? 'Administrator' }}
                </p>
                <p class="text-gray-500 text-sm">
                    {{ $user['email'] }}
                </p>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- INFO AKUN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Nama Lengkap
                </label>
                <p class="text-gray-800 font-medium bg-gray-50 px-3 py-2 rounded border border-gray-200">
                    {{ $user['name'] }}
                </p>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Email Address
                </label>
                <p class="text-gray-800 font-medium bg-gray-50 px-3 py-2 rounded border border-gray-200">
                    {{ $user['email'] }}
                </p>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- FORM GANTI PASSWORD --}}
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                Ubah Password
            </h3>

            <form method="POST" action="{{ route('profile.update.password') }}" class="space-y-5 max-w-lg">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Lama
                    </label>
                    <input type="password" name="password_lama"
                           class="w-full border rounded-lg px-4 py-2 
                           @error('password_lama') border-red-500 @enderror"
                           required>

                    @error('password_lama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru
                    </label>
                    <input type="password" name="password_baru"
                           class="w-full border rounded-lg px-4 py-2 
                           @error('password_baru') border-red-500 @enderror"
                           required>

                    @error('password_baru')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_baru_confirmation"
                           class="w-full border rounded-lg px-4 py-2"
                           required>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white 
                                   font-medium px-6 py-2.5 rounded-lg 
                                   transition shadow-lg shadow-blue-500/30">
                        Simpan Password
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
