<!-- POPUP HAPUS GLOBAL -->
<div id="popupHapus"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl w-full max-w-md p-6 shadow-2xl animate-fadeIn mx-4">

        <!-- HEADER -->
        <h3 class="text-xl font-bold text-red-600 mb-4 flex items-center gap-2">
            <i class="fas fa-trash"></i>
            Konfirmasi Hapus
        </h3>

        <!-- ICON WARNING -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
        </div>

        <!-- PESAN -->
        <p id="popupHapusMessage" class="text-gray-700 mb-6 text-center">
            Apakah Anda yakin ingin menghapus data ini?
        </p>

        <!-- BUTTON -->
        <div class="flex justify-center gap-3">
            <button
                type="button"
                onclick="closePopupHapus()"
                class="px-6 py-2 rounded-lg border border-gray-300 font-semibold text-gray-600 hover:bg-gray-100 transition">
                Batal
            </button>

            <button
                type="button"
                id="popupHapusConfirmBtn"
                class="px-6 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                <i class="fas fa-trash mr-1"></i> Hapus
            </button>
        </div>

    </div>
</div>

<script>
// Pastikan tidak double-declare jika include berkali-kali
if (typeof popupHapusCallback === 'undefined') {
    var popupHapusCallback = null;
}

/**
 * Buka popup hapus
 * @param {Function} callback - Fungsi yang dipanggil saat klik Hapus
 * @param {String}   message  - Pesan konfirmasi (opsional)
 */
function openPopupHapus(callback, message) {
    message = message || 'Apakah Anda yakin ingin menghapus data ini?';
    popupHapusCallback = callback;

    document.getElementById('popupHapusMessage').innerText = message;

    var popup = document.getElementById('popupHapus');
    popup.classList.remove('hidden');
    popup.classList.add('flex');
}

/**
 * Tutup popup hapus
 */
function closePopupHapus() {
    var popup = document.getElementById('popupHapus');
    popup.classList.add('hidden');
    popup.classList.remove('flex');
    popupHapusCallback = null;
}

// Tutup popup saat klik di luar modal (backdrop)
document.getElementById('popupHapus').addEventListener('click', function (e) {
    if (e.target === this) {
        closePopupHapus();
    }
});

// Tombol konfirmasi hapus
document.getElementById('popupHapusConfirmBtn').addEventListener('click', function () {
    if (typeof popupHapusCallback === 'function') {
        popupHapusCallback();
    }
    closePopupHapus();
});

// Tutup popup dengan tombol Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closePopupHapus();
    }
});
</script>