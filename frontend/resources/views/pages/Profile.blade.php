@extends('layout.App')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6">

    <h1 class="text-3xl font-bold text-gray-800">Profile Saya</h1>

    {{-- ===== ALERT GLOBAL ERROR ===== --}}
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 002 0V9a1 1 0 00-2 0zm0-4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- ===== KARTU FOTO & UPLOAD ===== --}}
    {{-- Cropper.js CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">

    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-5">Foto Profil</h2>

        {{-- Alert sukses foto --}}
        @if (session('success_photo'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success_photo') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update.photo') }}" enctype="multipart/form-data" id="formFoto">
            @csrf
            {{-- Input hidden untuk hasil crop (base64 → dikonversi ke file di JS) --}}
            <input type="hidden" name="thumbnail" id="croppedInput">

            <div class="flex flex-col sm:flex-row items-center gap-6">

                {{-- Foto profil saat ini (klik untuk buka crop) --}}
                <div class="relative group cursor-pointer shrink-0" onclick="document.getElementById('fotoInput').click()">
                    @php
                        $fotoSrc = isset($user['thumbnail'])
                            ? route('proxy.image', ['url' => $user['thumbnail']])
                            : 'https://ui-avatars.com/api/?name='.urlencode($user['name']).'&size=128&background=EFF6FF&color=3B82F6';
                    @endphp
                    <img id="fotoPreview"
                         src="{{ $fotoSrc }}"
                         alt="Foto Profil"
                         class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 shadow-md transition group-hover:opacity-70">

                    <div class="absolute inset-0 flex items-center justify-center rounded-full bg-black/0 group-hover:bg-black/30 transition">
                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-2 flex-1">
                    <p class="text-sm text-gray-500">Klik foto untuk memilih gambar. Kamu bisa crop & zoom sebelum menyimpan.</p>
                    <p class="text-xs text-gray-400">Format: JPG, PNG, WEBP · Maks. 2MB</p>
                    <input type="file" id="fotoInput" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">

                    @error('thumbnail')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </div>

    {{-- ===== MODAL CROP ===== --}}
    {{-- Gunakan inline style (bukan Tailwind hidden) agar Cropper bisa baca dimensi --}}
    <div id="modalCrop"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(0,0,0,0.65);
                align-items:center; justify-content:center; padding:1rem;">
        <div id="modalDialog" style="background:#fff; border-radius:1rem; width:100%; max-width:520px;
                    box-shadow:0 25px 60px rgba(0,0,0,0.4);
                    display:flex; flex-direction:column;
                    max-height:95vh; overflow-y:auto; margin:0 auto;">

            {{-- Header --}}
            <div id="modalHeader"
                 style="display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid #f3f4f6;">
                <span style="font-weight:600; color:#1f2937;">✂️ Sesuaikan Foto Profil</span>
                <button onclick="tutupModal()" style="color:#9ca3af; background:none; border:none; cursor:pointer; font-size:1.25rem; line-height:1;">✕</button>
            </div>

            {{-- Petunjuk --}}
            <div style="background:#eff6ff; padding:0.5rem 1.25rem; font-size:0.75rem; color:#2563eb; border-bottom:1px solid #dbeafe;">
                🖱️ <b>Geser</b> foto &nbsp;|&nbsp; 🔍 <b>Scroll</b> untuk zoom &nbsp;|&nbsp; 🔄 <b>Putar</b> foto &nbsp;|&nbsp; ↩️ <b>Reset</b> posisi
            </div>

            {{-- Area crop — tinggi tetap agar Cropper bisa baca dimensi --}}
            <div id="areaCrop" style="width:100%; height:300px; background:#1f2937; overflow:hidden; position:relative;">
                <img id="gambarCrop" src="" alt="crop"
                     style="display:block; max-width:100%; max-height:100%;">
            </div>

            {{-- Kontrol --}}
            <div style="padding:0.75rem 1.25rem; border-top:1px solid #f3f4f6;">
                {{-- Zoom --}}
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
                    <span style="font-size:0.75rem; color:#6b7280; min-width:2rem;">Zoom</span>
                    <button onclick="zoomBy(-0.15)"
                            style="width:2rem; height:2rem; border-radius:50%; background:#f3f4f6; border:none; cursor:pointer; font-size:1.1rem; font-weight:bold;">−</button>
                    <input type="range" id="sliderZoom" min="-50" max="50" value="0" step="1"
                           style="flex:1; accent-color:#3b82f6;"
                           oninput="zoomBy(this.value/500)">
                    <button onclick="zoomBy(0.15)"
                            style="width:2rem; height:2rem; border-radius:50%; background:#f3f4f6; border:none; cursor:pointer; font-size:1.1rem; font-weight:bold;">+</button>
                </div>
                {{-- Putar --}}
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <span style="font-size:0.75rem; color:#6b7280; min-width:2rem;">Putar</span>
                    <button onclick="if(cropperInstance) cropperInstance.rotate(-90)"
                            style="font-size:0.75rem; padding:0.3rem 0.75rem; background:#f3f4f6; border:none; border-radius:0.5rem; cursor:pointer;">← −90°</button>
                    <button onclick="if(cropperInstance) cropperInstance.rotate(90)"
                            style="font-size:0.75rem; padding:0.3rem 0.75rem; background:#f3f4f6; border:none; border-radius:0.5rem; cursor:pointer;">+90° →</button>
                    <button onclick="if(cropperInstance) { cropperInstance.reset(); document.getElementById('sliderZoom').value=0; }"
                            style="font-size:0.75rem; padding:0.3rem 0.75rem; background:none; border:none; color:#3b82f6; cursor:pointer; margin-left:auto;">Reset</button>
                </div>
            </div>

            {{-- Preview --}}
            <div style="display:flex; align-items:center; gap:1rem; padding:0.75rem 1.25rem; border-top:1px solid #f3f4f6;">
                <span style="font-size:0.75rem; color:#6b7280;">Preview:</span>
                <div id="previewCrop"
                     style="width:64px; height:64px; border-radius:50%; overflow:hidden; border:2px solid #bfdbfe; background:#f3f4f6; flex-shrink:0;"></div>
                <div id="previewCropSq"
                     style="width:64px; height:64px; border-radius:0.5rem; overflow:hidden; border:2px solid #e5e7eb; background:#f3f4f6; flex-shrink:0;"></div>
                <span style="font-size:0.7rem; color:#9ca3af;">⬅ tampilan di profil &amp; navbar</span>
            </div>

            {{-- Tombol aksi --}}
            <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding:1rem 1.25rem; border-top:1px solid #f3f4f6;">
                <button onclick="tutupModal()"
                        style="padding:0.5rem 1.25rem; font-size:0.875rem; background:#f3f4f6; border:none; border-radius:0.5rem; cursor:pointer; font-weight:500;">
                    Batal
                </button>
                <button onclick="simpanCrop()"
                        style="padding:0.5rem 1.25rem; font-size:0.875rem; background:#2563eb; color:#fff; border:none; border-radius:0.5rem; cursor:pointer; font-weight:500;">
                    💾 Simpan Foto
                </button>
            </div>
        </div>
    </div>

    {{-- ===== KARTU INFO PROFIL ===== --}}
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-5">Informasi Profil</h2>

        {{-- Alert sukses info --}}
        @if (session('success_info'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success_info') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update.info') }}" class="space-y-5 max-w-lg">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name"
                       value="{{ old('name', $user['name'] ?? '') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300
                              @error('name') border-red-500 @else border-gray-300 @enderror"
                       placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email"
                       value="{{ old('email', $user['email'] ?? '') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300
                              @error('email') border-red-500 @else border-gray-300 @enderror"
                       placeholder="Masukkan email">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Role (read-only) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <p class="text-gray-700 font-medium bg-gray-50 px-4 py-2 rounded border border-gray-200 capitalize">
                    {{ $user['role'] ?? 'user' }}
                </p>
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-lg shadow-blue-500/30">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- ===== KARTU GANTI PASSWORD ===== --}}
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-5">Ubah Password</h2>

        {{-- Alert sukses password --}}
        @if (session('success_password'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success_password') }}
            </div>
        @endif

        {{-- Alert error validasi password --}}
        @if ($errors->has('password_lama') || $errors->has('password_baru'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded text-sm">
                {{ $errors->first('password_lama') ?: $errors->first('password_baru') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update.password') }}" class="space-y-5 max-w-lg">
            @csrf
            @method('PUT')

            {{-- Password Lama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                <div class="relative">
                    <input type="password" name="password_lama" id="passwordLama"
                           class="w-full border rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-300
                                  @error('password_lama') border-red-500 @else border-gray-300 @enderror"
                           required>
                    <button type="button" onclick="togglePassword('passwordLama', 'eyeLama')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="eyeLama" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Password Baru --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_baru" id="passwordBaru"
                           class="w-full border rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-300
                                  @error('password_baru') border-red-500 @else border-gray-300 @enderror"
                           required>
                    <button type="button" onclick="togglePassword('passwordBaru', 'eyeBaru')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="eyeBaru" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                {{-- Indikator kekuatan password --}}
                <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div id="strengthBar" class="h-full rounded-full transition-all duration-300 w-0"></div>
                </div>
                <p id="strengthText" class="text-xs mt-1 text-gray-400"></p>
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_baru_confirmation" id="passwordKonfirmasi"
                           class="w-full border rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-300 border-gray-300"
                           required>
                    <button type="button" onclick="togglePassword('passwordKonfirmasi', 'eyeKonfirmasi')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="eyeKonfirmasi" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <p id="matchText" class="text-xs mt-1"></p>
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-lg shadow-blue-500/30">
                    Simpan Password
                </button>
            </div>
        </form>
    </div>

</div>

{{-- ===== SCRIPTS ===== --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
    // ── CROP ──────────────────────────────────────────────
    let cropperInstance = null;

    document.getElementById('fotoInput').addEventListener('change', function () {
        if (!this.files || !this.files[0]) return;

        if (this.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran file melebihi 2MB. Pilih gambar yang lebih kecil.');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            // 1. Destroy cropper lama
            if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }

            // 2. Tampilkan modal dulu (flex) agar area crop punya dimensi
            const modal = document.getElementById('modalCrop');
            modal.style.display = 'flex';
            document.getElementById('sliderZoom').value = 0;

            // 3. Reset & set gambar — PENTING: hapus src lama dulu
            const img = document.getElementById('gambarCrop');
            img.removeAttribute('src');
            img.src = '';

            // 4. Init Cropper setelah gambar benar-benar load
            //    Gunakan event load pada Image baru, bukan langsung di img tag
            const tmpImg = new Image();
            tmpImg.onload = function () {
                img.src = e.target.result;
                // Beri waktu browser render gambar ke DOM
                setTimeout(function () {
                    cropperInstance = new Cropper(img, {
                        aspectRatio: 1,
                        viewMode: 0,
                        dragMode: 'move',
                        cropBoxResizable: true,
                        cropBoxMovable: true,
                        autoCropArea: 0.85,
                        guides: true,
                        center: true,
                        highlight: true,
                        background: true,
                        movable: true,
                        zoomable: true,
                        rotatable: true,
                        scalable: true,
                        preview: '#previewCrop',
                        ready: function () {
                            const box = document.querySelector('.cropper-view-box');
                            if (box) { box.style.borderRadius = '50%'; box.style.outline = '3px solid #3b82f6'; }
                            const face = document.querySelector('.cropper-face');
                            if (face) face.style.borderRadius = '50%';
                        }
                    });
                }, 150);
            };
            tmpImg.src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    });

    // Zoom helper
    function zoomBy(delta) {
        if (cropperInstance) cropperInstance.zoom(parseFloat(delta));
    }

    // Zoom via scroll mouse di area crop
    document.getElementById('areaCrop').addEventListener('wheel', function (e) {
        e.preventDefault();
        if (!cropperInstance) return;
        cropperInstance.zoom(e.deltaY < 0 ? 0.1 : -0.1);
    }, { passive: false });

    function tutupModal() {
        const modal = document.getElementById('modalCrop');
        modal.style.display = 'none';
        document.getElementById('fotoInput').value = '';
        if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
    }



    function simpanCrop() {
        if (!cropperInstance) { alert('Foto belum siap, tunggu sebentar.'); return; }

        const canvas = cropperInstance.getCroppedCanvas({
            width: 400, height: 400,
            fillColor: '#fff',         // background putih untuk PNG transparan
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        if (!canvas) { alert('Gagal membuat hasil crop.'); return; }

        // Tampilkan preview sementara di halaman
        document.getElementById('fotoPreview').src = canvas.toDataURL();
        tutupModal();

        canvas.toBlob(function (blob) {
            const formData = new FormData(document.getElementById('formFoto'));
            formData.delete('thumbnail');
            formData.append('thumbnail', blob, 'profil.jpg');

            fetch(document.getElementById('formFoto').action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json().catch(() => null))
            .then(data => {
                if (data && data.success === false) {
                    alert('Gagal menyimpan foto: ' + (data.message ?? ''));
                } else {
                    window.location.reload();
                }
            })
            .catch(() => window.location.reload());
        }, 'image/jpeg', 0.92);
    }

    // Tutup modal klik backdrop
    document.getElementById('modalCrop').addEventListener('click', function (e) {
        if (e.target === this) tutupModal();
    });

    // ── TOGGLE PASSWORD ────────────────────────────────────
    function togglePassword(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye   = document.getElementById(eyeId);
        input.type  = input.type === 'password' ? 'text' : 'password';
        eye.style.opacity = input.type === 'text' ? '0.4' : '1';
    }

    // ── KEKUATAN PASSWORD ──────────────────────────────────
    document.getElementById('passwordBaru').addEventListener('input', function () {
        const val = this.value;
        const bar = document.getElementById('strengthBar');
        const txt = document.getElementById('strengthText');

        let strength = 0;
        if (val.length >= 8)           strength++;
        if (/[A-Z]/.test(val))         strength++;
        if (/[0-9]/.test(val))         strength++;
        if (/[^A-Za-z0-9]/.test(val))  strength++;

        const levels = [
            { w: '0%',    color: '',               label: '' },
            { w: '25%',   color: 'bg-red-500',     label: 'Lemah' },
            { w: '50%',   color: 'bg-yellow-400',  label: 'Sedang' },
            { w: '75%',   color: 'bg-blue-400',    label: 'Kuat' },
            { w: '100%',  color: 'bg-green-500',   label: 'Sangat Kuat' },
        ];

        bar.className   = 'h-full rounded-full transition-all duration-300 ' + (levels[strength]?.color ?? '');
        bar.style.width = levels[strength]?.w ?? '0%';
        txt.textContent = levels[strength]?.label ?? '';
        txt.className   = 'text-xs mt-1 ' + (strength < 2 ? 'text-red-500' : strength < 4 ? 'text-yellow-500' : 'text-green-600');
    });

    // ── COCOKKAN KONFIRMASI PASSWORD ───────────────────────
    document.getElementById('passwordKonfirmasi').addEventListener('input', function () {
        const baru      = document.getElementById('passwordBaru').value;
        const matchText = document.getElementById('matchText');
        if (!this.value) { matchText.textContent = ''; return; }
        if (this.value === baru) {
            matchText.textContent = '✓ Password cocok';
            matchText.className   = 'text-xs mt-1 text-green-600';
        } else {
            matchText.textContent = '✗ Password tidak cocok';
            matchText.className   = 'text-xs mt-1 text-red-500';
        }
    });
</script>
@endsection