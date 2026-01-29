@extends('layout.App')

@section('title', 'Tambah Blog - Portal Blog')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<div class="tambah-blog-container" style="padding: 30px; font-family: 'Segoe UI', sans-serif; max-width: 1200px; margin: 0 auto; background: #f5f7fa; min-height: 100vh;">   
    <h1 style="font-size: 32px; margin-bottom: 30px; font-weight: 700; color: #2d3748;">Tambah Blog</h1>
    
    <form id="blogForm" action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr; gap: 25px;">
            <input type="text" id="judul" name="judul" placeholder="Judul Blog" required 
                   style="width: 100%; padding: 18px; border-radius: 12px; border: 2px solid #e0e0e0; font-size: 18px;">

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
                
                <div style="background: white; border-radius: 12px; border: 2px solid #e0e0e0; display: flex; flex-direction: column;">
                    <div id="editor" style="height: 450px; font-size: 16px;"></div>
                    
                    <input type="hidden" name="deskripsi" id="deskripsi-hidden">
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <input type="text" name="penulis" placeholder="Penulis" required style="padding: 15px; border-radius: 10px; border: 1px solid #ccc;">
                    
                    <select name="kategori" required style="padding: 15px; border-radius: 10px; border: 1px solid #ccc;">
                        <option value="" disabled selected>Pilih Kategori1</option>
                        <option value="Tutorial">Tutorial</option>
                        <option value="Web Dev">Web Dev</option>
                    </select>

                    <select name="status" required style="padding: 15px; border-radius: 10px; border: 1px solid #ccc;">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>

                    <div>
                      <label class="block text-sm font-bold mb-3 flex items-center gap-2" style="color: #4988C4;">
                        <i class="fas fa-image"></i>
                        Gambar Blog
                      </label>
                      <div class="flex items-center gap-3">
                        <input 
                          type="file" 
                          id="inputGambar"
                          name="gambar"
                          accept="image/*"
                          class="hidden"
                          onchange="previewGambar(event)"
                        >
                        <label for="inputGambar" class="flex-1 px-4 py-3 rounded-xl text-base cursor-pointer transition-all flex items-center justify-center gap-2 font-medium" style="border: 2px solid #4988C4; color: #4988C4; background-color: #ffffff;" onmouseover="this.style.backgroundColor='rgba(73, 136, 196, 0.1)'" onmouseout="this.style.backgroundColor='#ffffff'">
                          <i class="fas fa-cloud-upload-alt text-xl"></i>
                          <span id="namaFile">Pilih gambar</span>
                        </label>
                      </div>
                      <div id="previewContainer" class="hidden mt-4">
                        <img id="previewImage" class="w-full h-48 object-cover rounded-xl shadow-lg" style="border: 2px solid #4988C4;">
                      </div>
                    </div>
                    
                    <button type="submit" id="submit-btn" 
                            style="padding: 20px; border-radius: 12px; border: none; background: #667eea; color: white; font-weight: bold; cursor: pointer;">
                        Upload Blog
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<script>
    // 1. Inisialisasi Quill
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'konten',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // 2. Fungsi Preview Gambar
    function previewGambar(event) {
        const file = event.target.files[0];
        if (file) {
            // Update nama file
            document.getElementById('namaFile').textContent = file.name;
            
            // Tampilkan preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    // 3. Submit Form
    var form = document.getElementById('blogForm');
    form.onsubmit = function() {
        // Ambil konten dari Quill
        var content = document.querySelector('input[name=deskripsi]');
        
        // Set value dari Quill ke hidden input
        content.value = quill.root.innerHTML;
        
        // Validasi konten tidak boleh kosong
        if (quill.getText().trim().length === 0) {
            alert('Konten blog tidak boleh kosong!');
            return false;
        }
        
        return true;
    };
</script>

<style>
    .ql-toolbar.ql-snow {
        border: none !important;
        background: #f8f9fa;
        border-bottom: 1px solid #e0e0e0 !important;
        border-radius: 12px 12px 0 0;
    }
    .ql-container.ql-snow {
        border: none !important;
        border-radius: 0 0 12px 12px;
    }
</style>
@endsection