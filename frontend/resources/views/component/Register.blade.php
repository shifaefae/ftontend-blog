<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - Portal Berita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-[#4988C4] to-blue-900 min-h-screen flex items-center justify-center py-10">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Portal Berita</h1>
            <p class="text-gray-500 mt-2">Buat akun baru untuk mulai membaca</p>
        </div>

        {{-- Error dari controller --}}
        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                Server tidak dapat diakses. Coba lagi nanti.
            </div>
        @endif

        {{-- Form POST ke RegisterController --}}
        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-5">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap"
                           required
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200
                                  focus:ring-2 focus:ring-[#4988C4] outline-none transition
                                  @error('name') border-red-400 @enderror">
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Email</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           required
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200
                                  focus:ring-2 focus:ring-[#4988C4] outline-none transition
                                  @error('email') border-red-400 @enderror">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Password</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password"
                           name="password"
                           id="passwordInput"
                           placeholder="Minimal 8 karakter"
                           required
                           class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200
                                  focus:ring-2 focus:ring-[#4988C4] outline-none transition
                                  @error('password') border-red-400 @enderror">
                    <button type="button" onclick="togglePass('passwordInput','eyeIcon1')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400
                                   hover:text-[#4988C4] transition focus:outline-none">
                        <i class="fas fa-eye" id="eyeIcon1"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password"
                           name="password_confirmation"
                           id="passwordConfirm"
                           placeholder="Ulangi password"
                           required
                           class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200
                                  focus:ring-2 focus:ring-[#4988C4] outline-none transition">
                    <button type="button" onclick="togglePass('passwordConfirm','eyeIcon2')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400
                                   hover:text-[#4988C4] transition focus:outline-none">
                        <i class="fas fa-eye" id="eyeIcon2"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-[#4988C4] text-white font-semibold
                           rounded-xl shadow-lg shadow-blue-500/40 hover:opacity-90 transition">
                <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
            </button>
        </form>

        <div class="flex items-center my-5">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="mx-3 text-xs text-gray-400">atau</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <p class="text-center text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[#4988C4] font-semibold hover:underline">
                Login di sini
            </a>
        </p>

        <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} Portal Berita</p>
    </div>

    <script>
        function togglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>

</body>
</html>