<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Portal Berita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-[#4988C4] to-blue-900 min-h-screen flex items-center justify-center">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Portal Berita</h1>
            <p class="text-gray-500 mt-2">Silakan login untuk melanjutkan</p>
        </div>

        {{-- Error dari AuthController (session 'error') --}}
        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                Server tidak dapat diakses. Coba lagi nanti.
            </div>
        @endif

        {{-- Form POST ke AuthController web — bukan JS fetch --}}
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-5">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Email</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Masukkan email"
                           required
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200
                                  focus:ring-2 focus:ring-[#4988C4] outline-none transition
                                  @error('email') border-red-400 @enderror">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Password</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password"
                           name="password"
                           id="passwordInput"
                           placeholder="Masukkan password"
                           required
                           class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200
                                  focus:ring-2 focus:ring-[#4988C4] outline-none transition">
                    <button type="button"
                            onclick="togglePass()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400
                                   hover:text-[#4988C4] transition focus:outline-none">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full py-3 bg-[#4988C4] text-white font-semibold
                           rounded-xl shadow-lg shadow-blue-500/40 hover:opacity-90 transition">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </form>

        <div class="flex items-center my-5">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="mx-3 text-xs text-gray-400">atau</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <p class="text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#4988C4] font-semibold hover:underline">
                Daftar Sekarang
            </a>
        </p>

        <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} Portal Berita</p>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>

</body>
</html>