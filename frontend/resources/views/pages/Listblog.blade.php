@extends('layout.App')

@section('title', 'List Blog - Portal Blog')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Blog - Portal Blog</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-add {
            padding: 14px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.5);
        }

        .table-container {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }

        .table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
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
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%);
        }

        td {
            padding: 16px 18px;
            font-size: 14px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .badge-tutorial { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .badge-webdev { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .badge-js { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); }
        .badge-backend { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .badge-php { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }

        .menu-btn {
            padding: 8px 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
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

        .dropdown-item.delete {
            color: #f56565;
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
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        }

        .modal-success::before {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-ok {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">📚 List Blog</h1>
        <button class="btn-add">➕ Tambah Blog</button>
    </div>

    <div class="table-container">
        <div class="search-box">
            <h2 style="font-size: 20px; color: #2d3748; margin: 0;">📋 Daftar Blog</h2>
            <div style="position: relative;">
                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%);">🔍</span>
                <input type="text" class="search-input" placeholder="Cari blog..." onkeyup="searchTable()">
            </div>
        </div>

        <table id="blogTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Tanggal</th>
                    <th>Views</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: 600; color: #4a5568;">1</td>
                    <td style="font-weight: 500; color: #2d3748;">Tutorial Laravel untuk Pemula</td>
                    <td><span class="badge badge-tutorial">Tutorial</span></td>
                    <td style="color: #4a5568;">Admin</td>
                    <td style="color: #718096;">10/01/2026</td>
                    <td style="font-weight: 500; color: #4a5568;">👁 1,250</td>
                    <td style="text-align: center;">
                        <div class="dropdown">
                            <button class="menu-btn" onclick="toggleDropdown(1)">⋮</button>
                            <div id="dropdown-1" class="dropdown-menu">
                                <a href="#" class="dropdown-item">📝 Edit</a>
                                <a href="#" class="dropdown-item delete" onclick="confirmDelete(1)">🗑️ Hapus</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 600; color: #4a5568;">2</td>
                    <td style="font-weight: 500; color: #2d3748;">Tips Optimasi Website</td>
                    <td><span class="badge badge-webdev">Web Dev</span></td>
                    <td style="color: #4a5568;">Admin</td>
                    <td style="color: #718096;">08/01/2026</td>
                    <td style="font-weight: 500; color: #4a5568;">👁 7,340</td>
                    <td style="text-align: center;">
                        <div class="dropdown">
                            <button class="menu-btn" onclick="toggleDropdown(2)">⋮</button>
                            <div id="dropdown-2" class="dropdown-menu">
                                <a href="#" class="dropdown-item">📝 Edit</a>
                                <a href="#" class="dropdown-item delete" onclick="confirmDelete(2)">🗑️ Hapus</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 600; color: #4a5568;">3</td>
                    <td style="font-weight: 500; color: #2d3748;">Mengenal Chart.js dalam 10 Menit</td>
                    <td><span class="badge badge-js">JavaScript</span></td>
                    <td style="color: #4a5568;">Admin</td>
                    <td style="color: #718096;">05/01/2026</td>
                    <td style="font-weight: 500; color: #4a5568;">👁 890</td>
                    <td style="text-align: center;">
                        <div class="dropdown">
                            <button class="menu-btn" onclick="toggleDropdown(3)">⋮</button>
                            <div id="dropdown-3" class="dropdown-menu">
                                <a href="#" class="dropdown-item">📝 Edit</a>
                                <a href="#" class="dropdown-item delete" onclick="confirmDelete(3)">🗑️ Hapus</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 600; color: #4a5568;">4</td>
                    <td style="font-weight: 500; color: #2d3748;">Cara Membuat REST API dengan Laravel</td>
                    <td><span class="badge badge-backend">Backend</span></td>
                    <td style="color: #4a5568;">Admin</td>
                    <td style="color: #718096;">03/01/2026</td>
                    <td style="font-weight: 500; color: #4a5568;">👁 4,560</td>
                    <td style="text-align: center;">
                        <div class="dropdown">
                            <button class="menu-btn" onclick="toggleDropdown(4)">⋮</button>
                            <div id="dropdown-4" class="dropdown-menu">
                                <a href="#" class="dropdown-item">📝 Edit</a>
                                <a href="#" class="dropdown-item delete" onclick="confirmDelete(4)">🗑️ Hapus</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 600; color: #4a5568;">5</td>
                    <td style="font-weight: 500; color: #2d3748;">Design Pattern dalam PHP</td>
                    <td><span class="badge badge-php">PHP</span></td>
                    <td style="color: #4a5568;">Admin</td>
                    <td style="color: #718096;">01/01/2026</td>
                    <td style="font-weight: 500; color: #4a5568;">👁 1,000</td>
                    <td style="text-align: center;">
                        <div class="dropdown">
                            <button class="menu-btn" onclick="toggleDropdown(5)">⋮</button>
                            <div id="dropdown-5" class="dropdown-menu">
                                <a href="#" class="dropdown-item">📝 Edit</a>
                                <a href="#" class="dropdown-item delete" onclick="confirmDelete(5)">🗑️ Hapus</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content modal-delete">
            <div style="font-size: 56px; margin-bottom: 20px;">⚠️</div>
            <h3 style="margin: 0 0 15px 0; color: #2d3748; font-size: 22px;">Konfirmasi Hapus</h3>
            <p style="color: #4a5568; margin: 0;">Anda yakin ingin menghapus blog ini?</p>
            <div class="btn-group">
                <button class="btn btn-cancel" onclick="closeDeleteModal()">Batal</button>
                <button class="btn btn-delete" onclick="executeDelete()">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content modal-success">
            <div style="font-size: 64px; color: #10b981; margin-bottom: 15px;">✓</div>
            <h3 style="margin: 0; color: #2d3748; font-size: 22px;">Blog Berhasil Dihapus</h3>
            <div class="btn-group">
                <button class="btn btn-ok" onclick="closeSuccessModal()">OK</button>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            const input = document.querySelector('.search-input');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('blogTable');
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < td.length - 1; j++) {
                    if (td[j]) {
                        let txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                tr[i].style.display = found ? '' : 'none';
            }
        }

        function toggleDropdown(id) {
            event.stopPropagation();
            const allDropdowns = document.querySelectorAll('.dropdown-menu');
            const currentDropdown = document.getElementById('dropdown-' + id);
            
            allDropdowns.forEach(dropdown => {
                if (dropdown !== currentDropdown) {
                    dropdown.classList.remove('show');
                }
            });
            
            currentDropdown.classList.toggle('show');
        }

        function confirmDelete(id) {
            document.getElementById('deleteModal').classList.add('show');
            const dropdown = document.getElementById('dropdown-' + id);
            if (dropdown) dropdown.classList.remove('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        function executeDelete() {
            closeDeleteModal();
            document.getElementById('successModal').classList.add('show');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('show');
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>

@endsection