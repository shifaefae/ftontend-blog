@extends('layout.App')

@section('title', 'List Blog - Portal Blog')


@push('styles')

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            padding: 30px;
            margin: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .title {
            font-size: 32px;
            margin: 0;
            font-weight: 700;
            color: #333333;
        }

        .btn-add {
            padding: 14px 30px;
            background: #4988C4;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(73, 136, 196, 0.4);
            transition: all 0.3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(73, 136, 196, 0.5);
        }

        .table-container {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
            border: 1px solid #e0e0e0;
        }

        .table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #4988C4;
            border-radius: 16px 16px 0 0;
        }

        .search-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-input {
            padding: 12px 20px 12px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            width: 280px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #4988C4;
            box-shadow: 0 0 0 4px rgba(73, 136, 196, 0.15);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #4988C4;
        }

        th {
            padding: 16px 18px;
            text-align: left;
            font-weight: 600;
            color: white;
            font-size: 14px;
        }

        tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        td {
            padding: 16px 18px;
            font-size: 14px;
            color: #333333;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            background: #4988C4;
        }

        .badge-tutorial { background: #4988C4; }
        .badge-webdev { background: #4988C4; }
        .badge-js { background: #4988C4; }
        .badge-backend { background: #4988C4; }
        .badge-php { background: #4988C4; }

        .menu-btn {
            padding: 8px 12px;
            background: #4988C4;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(73, 136, 196, 0.3);
            transition: all 0.3s;
        }

        .menu-btn:hover {
            transform: translateY(-2px);
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            min-width: 140px;
            z-index: 1000;
            overflow: hidden;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 18px;
            color: #2d3748;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        .dropdown-item.edit:hover {
            background: #e8f4f8;
            color: #4988C4;
        }

        .dropdown-item.delete {
            color: #f56565;
        }

        .dropdown-item.delete:hover {
            background: #fee;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 16px;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            position: relative;
        }

        .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 16px 16px 0 0;
        }

        .modal-delete::before {
            background: #ef4444;
        }

        .modal-success::before {
            background: #10b981;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-cancel {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-ok {
            background: #10b981;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        /* Style untuk foto blog */
        .blog-foto {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .blog-foto-placeholder {
            width: 60px;
            height: 60px;
            background: #4988C4;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Style untuk tombol dropdown (titik 3) */
        .dropdown-toggle {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 5px 10px;
            color: #666666;
            font-weight: bold;
            transition: color 0.2s ease;
        }

        .dropdown-toggle:hover {
            color: #4988C4;
        }
    </style>
@endpush

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 style="font-size: 32px; margin-bottom: 30px; font-weight: 700; color: #333333;">List Blog</h1>
    <a href="{{ route('blog.tambah') }}" class="btn-add" style="padding: 12px 25px; background: #4988C4; color: white; text-decoration: none; border-radius: 8px;"> Tambah Blog</a>
</div>

<div class="table-container">
    <table id="blogTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop data dari Controller --}}
            @forelse($blogs as $index => $blog)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if(isset($blog->foto) && $blog->foto)
                        <img src="{{ asset('storage/' . $blog->foto) }}" alt="{{ $blog->judul }}" class="blog-foto">
                    @else
                        <div class="blog-foto-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </td>
                <td><strong>{{ $blog->judul }}</strong></td>
                <td>{{ $blog->penulis }}</td>
                <td><span class="badge" style="background: #4988C4;">{{ $blog->kategori }}</span></td>
                <td>{{ ucfirst($blog->status) }}</td>
                <td>
                    <div class="dropdown">
                        <button class="dropdown-toggle" onclick="toggleDropdown({{ $blog->id }})">⋮</button>
                        <div id="dropdown-{{ $blog->id }}" class="dropdown-menu">
                            <a href="#" class="dropdown-item edit">Edit</a>
                            <form action="#" method="POST" style="display:inline; margin: 0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item delete" style="width:100%; text-align:left; border:none; background:none; cursor:pointer;">Hapus</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">Belum ada data blog.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    function toggleDropdown(id) {
        event.stopPropagation();
        const current = document.getElementById('dropdown-' + id);
        document.querySelectorAll('.dropdown-menu').forEach(d => {
            if (d !== current) d.classList.remove('show');
        });
        current.classList.toggle('show');
    }

    window.onclick = function() {
        document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('show'));
    }
</script>
@endpush