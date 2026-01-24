<!-- resources/views/blog/edit.blade.php -->
@extends('layout.app')

@section('title', 'Edit Blog - Portal Blog')

@section('content')
<div style="padding: 20px; font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto;">
    
    <div style="background-color: #f0f0f0; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #333; font-size: 20px;">Edit Blog</h2>
    </div>
    
    <form action="{{ route('blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: flex; gap: 20px;">
            <!-- Left Column - Editor -->
            <div style="flex: 2; background-color: #f5f5f5; padding: 20px; border-radius: 8px;">
                <!-- Title Input -->
                <div style="margin-bottom: 20px;">
                    <input type="text" 
                           name="judul" 
                           placeholder="Tulis judul blog di sini" 
                           value="{{ old('judul', $blog->judul) }}"
                           required
                           style="width: 100%; 
                                  padding: 15px; 
                                  font-size: 16px; 
                                  border: 1px solid #ddd; 
                                  border-radius: 6px; 
                                  box-sizing: border-box;
                                  background-color: white;">
                </div>
                
                <!-- Rich Text Editor Toolbar -->
                <div style="background-color: white; 
                            border: 1px solid #ddd; 
                            border-bottom: none; 
                            padding: 10px; 
                            border-radius: 6px 6px 0 0;
                            display: flex;
                            gap: 5px;
                            align-items: center;">
                    <select id="formatBlock" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer;">
                        <option value="p">Normal</option>
                        <option value="h1">Heading 1</option>
                        <option value="h2">Heading 2</option>
                        <option value="h3">Heading 3</option>
                    </select>
                    <button type="button" onclick="formatDoc('bold')" style="padding: 6px 10px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer; font-weight: bold;">B</button>
                    <button type="button" onclick="formatDoc('italic')" style="padding: 6px 10px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer; font-style: italic;">I</button>
                    <button type="button" onclick="formatDoc('underline')" style="padding: 6px 10px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer; text-decoration: underline;">U</button>
                    <button type="button" onclick="formatDoc('createLink')" style="padding: 6px 10px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer;">🔗</button>
                    <button type="button" onclick="formatDoc('justifyLeft')" style="padding: 6px 10px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer;">☰</button>
                    <button type="button" onclick="formatDoc('justifyCenter')" style="padding: 6px 10px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer;">☰</button>
                    <button type="button" onclick="formatDoc('justifyRight')" style="padding: 6px 10px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer;">☰</button>
                    <input type="color" id="textColor" onchange="formatDoc('foreColor', this.value)" style="width: 40px; height: 30px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer;">
                </div>
                
                <!-- Content Editor -->
                <div id="editor" 
                     contenteditable="true" 
                     style="min-height: 400px; 
                            background-color: white; 
                            border: 1px solid #ddd; 
                            border-radius: 0 0 6px 6px; 
                            padding: 20px; 
                            font-size: 14px;
                            line-height: 1.6;
                            overflow-y: auto;">
                    {!! old('konten', $blog->konten) !!}
                </div>
                
                <input type="hidden" name="konten" id="content">
            </div>
            
            <!-- Right Column - Sidebar -->
            <div style="flex: 1;">
                <!-- Author -->
                <div style="background-color: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; color: #666; font-size: 14px;">Penulis</label>
                    <input type="text" 
                           name="penulis" 
                           value="{{ old('penulis', $blog->penulis) }}"
                           required
                           style="width: 100%; 
                                  padding: 10px; 
                                  border: 1px solid #ddd; 
                                  border-radius: 6px; 
                                  box-sizing: border-box;
                                  font-size: 14px;">
                </div>
                
                <!-- Category -->
                <div style="background-color: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; color: #666; font-size: 14px;">Kategori</label>
                    <select name="kategori" 
                            required
                            style="width: 100%; 
                                   padding: 10px; 
                                   border: 1px solid #ddd; 
                                   border-radius: 6px; 
                                   box-sizing: border-box;
                                   font-size: 14px;
                                   cursor: pointer;">
                        <option value="">Pilih Kategori</option>
                        <option value="Tutorial" {{ old('kategori', $blog->kategori) == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                        <option value="Web Development" {{ old('kategori', $blog->kategori) == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                        <option value="JavaScript" {{ old('kategori', $blog->kategori) == 'JavaScript' ? 'selected' : '' }}>JavaScript</option>
                        <option value="Backend" {{ old('kategori', $blog->kategori) == 'Backend' ? 'selected' : '' }}>Backend</option>
                        <option value="PHP" {{ old('kategori', $blog->kategori) == 'PHP' ? 'selected' : '' }}>PHP</option>
                    </select>
                </div>
                
                <!-- Tags -->
                <div style="background-color: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; color: #666; font-size: 14px;">Tag</label>
                    <input type="text" 
                           name="tags" 
                           placeholder="Pisahkan dengan koma"
                           value="{{ old('tags', $blog->tags ?? '') }}"
                           style="width: 100%; 
                                  padding: 10px; 
                                  border: 1px solid #ddd; 
                                  border-radius: 6px; 
                                  box-sizing: border-box;
                                  font-size: 14px;">
                </div>
                
                <!-- Status -->
                <div style="background-color: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; color: #666; font-size: 14px;">Status</label>
                    <select name="status" 
                            style="width: 100%; 
                                   padding: 10px; 
                                   border: 1px solid #ddd; 
                                   border-radius: 6px; 
                                   box-sizing: border-box;
                                   font-size: 14px;
                                   cursor: pointer;">
                        <option value="draft" {{ old('status', $blog->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $blog->status ?? 'draft') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                
                <!-- Featured Image -->
                <div style="background-color: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #666; font-size: 14px;">Gambar</label>
                    
                    @if(isset($blog->gambar) && $blog->gambar)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ asset('storage/' . $blog->gambar) }}" 
                             alt="Current Image" 
                             style="width: 100%; border-radius: 6px; max-height: 200px; object-fit: cover;">
                    </div>
                    @endif
                    
                    <input type="file" 
                           name="gambar" 
                           accept="image/*"
                           style="width: 100%; 
                                  padding: 10px; 
                                  border: 1px solid #ddd; 
                                  border-radius: 6px; 
                                  box-sizing: border-box;
                                  font-size: 14px;
                                  cursor: pointer;">
                    <small style="color: #999; font-size: 12px; display: block; margin-top: 5px;">Biarkan kosong jika tidak ingin mengubah gambar</small>
                </div>
                
                <!-- Action Buttons -->
                <div style="display: flex; gap: 10px;">
                    <button type="submit" 
                            style="flex: 1;
                                   padding: 12px; 
                                   background-color: #4CAF50; 
                                   color: white; 
                                   border: none; 
                                   border-radius: 6px; 
                                   font-size: 14px; 
                                   cursor: pointer;
                                   font-weight: 500;
                                   transition: background-color 0.3s;">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('blog.list') }}" 
                       style="flex: 1;
                              padding: 12px; 
                              background-color: #999; 
                              color: white; 
                              border: none; 
                              border-radius: 6px; 
                              font-size: 14px; 
                              cursor: pointer;
                              text-decoration: none;
                              text-align: center;
                              display: block;
                              font-weight: 500;
                              transition: background-color 0.3s;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    button:hover, 
    input[type="submit"]:hover {
        opacity: 0.9;
    }
    
    #editor:focus {
        outline: none;
        border-color: #4CAF50;
    }
    
    #formatBlock option {
        padding: 5px;
    }
</style>

<script>
    const editor = document.getElementById('editor');
    const formatBlockSelect = document.getElementById('formatBlock');
    
    // Format document
    function formatDoc(cmd, value = null) {
        if (cmd === 'createLink') {
            value = prompt('Enter URL:', 'http://');
            if (!value) return;
        }
        document.execCommand(cmd, false, value);
        editor.focus();
    }
    
    // Handle format block
    formatBlockSelect.addEventListener('change', function() {
        formatDoc('formatBlock', this.value);
        this.value = 'p';
    });
    
    // Save content before submit
    const form = editor.closest('form');
    form.addEventListener('submit', function(e) {
        document.getElementById('content').value = editor.innerHTML;
    });
    
    // Placeholder behavior
    editor.addEventListener('focus', function() {
        if (this.innerHTML.trim() === '' || this.innerHTML === 'Tulis isi blog di sini...') {
            this.innerHTML = '';
        }
    });
    
    editor.addEventListener('blur', function() {
        if (this.innerHTML.trim() === '') {
            this.innerHTML = 'Tulis isi blog di sini...';
        }
    });
</script>
@endsection