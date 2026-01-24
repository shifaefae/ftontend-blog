<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* Main Content Area - agar tidak terpotong sidebar */
        .main-content {
            margin-left: 16rem; /* Sama dengan lebar sidebar (w-64 = 16rem) */
            padding: 20px;
            min-height: 100vh;
            background-color: #f3f4f6;
            padding-top: 80px; /* Beri ruang untuk navbar yang fixed */
        }

        /* Ketika sidebar collapsed */
        .sidebar.collapsed ~ .main-content {
            margin-left: 5rem; /* 5rem = lebar sidebar saat collapsed */
        }
        .menu-item {
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background-color: #f3f4f6;
            padding-left: 1.5rem;
        }

        .menu-item.active {
            color: #ef4444;
            font-weight: 600;
            border-left: 3px solid #ef4444;
            padding-left: 1.5rem;
            background: linear-gradient(90deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .menu-item.active i {
            color: #ef4444;
        }

        .submenu-item {
            transition: all 0.2s ease;
            padding-left: 2rem;
        }

        .submenu-item:hover {
            background-color: #1C4D8D;
            padding-left: 2.5rem;
        }

        .btn-logout {
            transition: all 0.3s ease;
            position: absolute; /* Tambahkan position absolute */
            bottom: 30px; /* Jarak dari bawah sidebar - bisa disesuaikan */
            left: 24px; /* Jarak dari kiri */
            right: 24px; /* Jarak dari kanan */
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4) !important;
        }
        /* Container tombol logout di sidebar */
        .sidebar .p-6.border-t {
            position: relative;
            margin-top: auto; /* Dorong ke bawah */
            padding-top: 50px; /* Tambah padding atas */
            padding-bottom: 30px; /* Tambah padding bawah */
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 50%, #3b82f6 100%);
        }

        .sidebar {
            min-height: 100vh;
            box-shadow: 2px 0 30px rgba(0, 0, 0, 0.05);
            transition: width 0.3s ease;
            width: 16rem;
            position: fixed;           /* TAMBAHKAN - sidebar tetap fixed */
            top: 0;                    /* TAMBAHKAN - mulai dari atas */
            left: 0;                   /* TAMBAHKAN - menempel di kiri */
            z-index: 100;              /* TAMBAHKAN - di bawah navbar */
            padding-top: 70px;         /* TAMBAHKAN - beri ruang untuk navbar */
            overflow-y: auto;          /* TAMBAHKAN - scroll jika menu panjang */
        }
        /* TAMBAHKAN CSS BARU INI */
        .sidebar.collapsed {
            width: 5rem; /* Lebar sidebar saat collapsed */
        }
      
        /* Sembunyikan teks menu saat collapsed */
        .sidebar.collapsed .menu-text {
            display: none;
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Sembunyikan submenu saat collapsed */
        .sidebar.collapsed .submenu-item {
            display: none;
        }

        /* Sembunyikan chevron (panah) submenu saat collapsed */
        .sidebar.collapsed .chevron-icon {
            display: none;
        }

        /* Pusatkan menu item saat collapsed (hanya icon) */
        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        /* Hover menu saat collapsed tidak bergeser ke kanan */
        .sidebar.collapsed .menu-item:hover {
            padding-left: 1rem;
        }

        /* Border menu aktif pindah ke kanan saat collapsed */
        .sidebar.collapsed .menu-item.active {
            border-left: none;
            border-right: 3px solid #ef4444;
        }

        /* Sembunyikan teks "Logout" saat collapsed */
        .sidebar.collapsed .logout-text {
            display: none;
        }

        /* Sesuaikan tombol logout saat collapsed */
        .sidebar.collapsed .btn-logout {
            width: calc(5rem - 48px);
            padding: 12px 8px;
            justify-content: center;
        }

        /* Navbar Fixed di Atas */
        .navbar {
            background-color: #1C4D8D;
            padding:1px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            z-index: 200;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 20px;
        }

        .logo {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .app-name {
            font-weight: 600;
            font-size: 18px;
            color: #f3f4f6;
        }

        /* Tombol Toggle di Navbar */
        .toggle-btn {
            width: 32px;
            height: 32px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-left: 15px;
            outline: none; /* Hilangkan outline saat diklik */
        }

        .toggle-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .toggle-btn:active {
            transform: scale(0.95);
        }

        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background-color 0.3s;
            position: relative;
        }

        .user-dropdown:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background-color: #f59e0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-name {
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
        }

        .dropdown-arrow {
            color: #ffffff;
            font-size: 12px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            min-width: 200px;
            overflow: hidden;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            color: #374151;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
        }

       /* Main Content Area - agar tidak terpotong sidebar */
       .main-content {
           margin-left: 16rem; /* 16rem = 256px = lebar sidebar default */
           padding: 20px;
           min-height: 100vh;
           background-color: #f3f4f6;
           padding-top: 80px; /* Ruang untuk navbar fixed (60px) + padding */
           transition: margin-left 0.3s ease; /* Smooth transition saat sidebar collapse */
        }

        /* Ketika sidebar collapsed */
        .sidebar.collapsed ~ .main-content {
            margin-left: 5rem; /* 5rem = 80px = lebar sidebar saat collapsed */
        }

        /* Logout Button - Posisi Fixed di Bawah */
        .btn-logout {
            transition: all 0.3s ease;
            position: fixed; /* Gunakan fixed agar selalu di bawah */
            bottom: 20px; /* Jarak dari bawah layar */
            left: 24px; /* Jarak dari kiri sidebar */
            width: calc(16rem - 48px); /* Lebar = lebar sidebar - padding kiri kanan */
        }

        /* Logout button saat sidebar collapsed */
        .sidebar.collapsed .btn-logout {
            width: calc(5rem - 48px);
            left: 24px;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4) !important;
        }

        /* Container logout - beri ruang di bawah sidebar */
        .sidebar > div:last-child {
            margin-top: auto;
            padding-bottom: 80px; /* Beri ruang agar menu tidak tertutup logout button */
        }

        /* Responsive untuk layar kecil */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding-top: 80px;
            }
    
            .sidebar.collapsed ~ .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar Fixed -->

    <div class="flex">
        <!-- Navbar -->
        <nav class="navbar">
            <!-- Logo, nama aplikasi, dan tombol toggle di kiri -->
            <div class="navbar-brand">
                <div class="logo">PB</div>
                <span class="app-name">Portal Berita</span>
        
                <!-- TAMBAHKAN TOMBOL TOGGLE DI SINI -->
                <button class="toggle-btn" onclick="toggleSidebar()" id="toggleBtn">
                    <i class="fas fa-chevron-left" id="toggleIcon"></i>
                </button>
            </div>

            <!-- User dropdown di kanan -->
            <div class="user-dropdown" id="userDropdown">
                <div class="user-avatar">SA</div>
                <span class="user-name">Super Admin</span>
                <span class="dropdown-arrow">▼</span>
        
                <div class="dropdown-menu" id="dropdownMenu">
                    <div class="dropdown-item" onclick="showPage('admin')">
                        <i class="fas fa-user-gear"></i>
                        <span>Admin</span>
                    </div>
                    <div class="dropdown-item" onclick="showPage('profile')">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </div>
                    <div class="dropdown-item" onclick="logout()">
                        <i class="fas fa-power-off"></i>
                        <span>Logout</span>
                    </div>
                </div>
            </div>
        </nav>
            <!-- Sidebar (TANPA HEADER) -->
            <aside id="sidebar" class="sidebar bg-white flex flex-col">
            
            <!-- Menu -->
            <nav class="flex-1 py-4">
                <ul class="space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="/" class="menu-item flex items-center px-6 py-2.5 text-sm text-gray-700 hover:text-gray-900">
                            <i class="fas fa-tags w-5 text-lg"></i>
                            <span class="ml-3 menu-text">Dashboard</span>
                        </a>
                    </li>

                    <!-- Blog -->
                    <li>
                        <button onclick="toggleSubmenu('blogSubmenu')" class="menu-item flex items-center justify-between w-full px-6 py-2.5 text-sm text-gray-700 hover:text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-newspaper w-5 text-lg"></i>
                                <span class="ml-3 menu-text">Blog</span>
                            </div>
                            <i id="blogIcon" class="fas fa-chevron-right text-xs transition-transform chevron-icon"></i>
                        </button>
                        <ul id="blogSubmenu" class="hidden space-y-1 mt-1">
                            <li>
                                <a href="/blog/tambah" class="submenu-item flex items-center py-2 text-xs text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-file-circle-plus w-4 text-sm"></i>
                                    <span class="ml-2">Tambah Blog</span>
                                </a>
                            </li>
                            <li>
                                <a href="/blog/list" class="submenu-item flex items-center py-2 text-xs text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-list-ul w-4 text-sm"></i>
                                    <span class="ml-2">List Blog</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Kategori -->
                    <li>
                        <a href="/kategori" class="menu-item flex items-center px-6 py-2.5 text-sm text-gray-700 hover:text-gray-900">
                            <i class="fas fa-tags w-5 text-lg"></i>
                            <span class="ml-3 menu-text">Kategori</span>
                        </a>
                    </li>

                    <!-- Iklan -->
                    <li>
                        <a href="/iklan" class="menu-item flex items-center px-6 py-2.5 text-sm text-gray-700 hover:text-gray-900">
                            <i class="fas fa-rectangle-ad w-5 text-lg"></i>
                            <span class="ml-3 menu-text">Iklan</span>
                        </a>
                    </li>

                    <!-- E-Jurnal -->
                    <li>
                        <a href="/ejurnal" class="menu-item flex items-center px-6 py-2.5 text-sm text-gray-700 hover:text-gray-900">
                            <i class="fas fa-book-bookmark w-5 text-lg"></i>
                            <span class="ml-3 menu-text">E-Jurnal</span>
                        </a>
                    </li>

                    <!-- Admin -->
                    <li>
                        <a href="/admin" class="menu-item flex items-center px-6 py-2.5 text-sm text-gray-700 hover:text-gray-900">
                            <i class="fas fa-user-gear w-5 text-lg"></i>
                            <span class="ml-3 menu-text">Admin</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Logout Button -->
            <div class="p-6 border-t border-gray-200">
                <button onclick="logout()" class="btn-logout w-full bg-gradient-to-r from-red-500 to-pink-500 text-white py-2.5 px-4 rounded-lg font-medium flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                    <i class="fas fa-power-off"></i>
                    <span class="logout-text">Logout</span>
                </button>
            </div>
        </aside>
    </div>

    <script>
        // Toggle Dropdown Menu
        const userDropdown = document.getElementById('userDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (userDropdown && dropdownMenu) {
            userDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });

            // Tutup dropdown jika klik di luar
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target)) {
                    dropdownMenu.classList.remove('active');
                }
            });
}
        // Show/Hide Content Pages
        function showPage(pageId) {
            // Hide all content pages
            document.querySelectorAll('.content-page').forEach(page => {
                page.classList.add('hidden');
            });
            
            // Show selected page
            const selectedPage = document.getElementById(pageId + '-content');
            if (selectedPage) {
                selectedPage.classList.remove('hidden');
            }
            
            // Update URL hash
            window.location.hash = pageId;
        }

        // Toggle Sidebar Collapse
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');
            const blogSubmenu = document.getElementById('blogSubmenu');

            sidebar.classList.toggle('collapsed');

            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');

                blogSubmenu.classList.add('hidden');
                document.getElementById('blogIcon').classList.remove('rotate-90');
            } else {
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
            }
        }

        // Toggle Submenu
        function toggleSubmenu(id) {
            const sidebar = document.getElementById('sidebar');
            
            // Don't open submenu if sidebar is collapsed
            if (sidebar.classList.contains('collapsed')) {
                return;
            }
            
            const submenu = document.getElementById(id);
            const icon = document.getElementById(id.replace('Submenu', 'Icon'));
            
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                icon.classList.add('rotate-90');
            } else {
                submenu.classList.add('hidden');
                icon.classList.remove('rotate-90');
            }
        }

        // Logout
        function logout() {
            // Show loading state
            const logoutBtn = document.querySelector('.btn-logout');
            const originalText = logoutBtn.innerHTML;
            
            logoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span class="logout-text ml-2">Logging out...</span>';
            logoutBtn.disabled = true;
            
            // Simulate logout process
            setTimeout(() => {
                // Show success message
                alert('✓ Logout berhasil!\n\nAnda akan diarahkan ke halaman login.');
                
                // Clear session/localStorage if needed
                localStorage.clear();
                sessionStorage.clear();
                
                // Redirect to login page
                window.location.href = '/login.html'; // Ganti dengan URL login Anda
                
                // If redirect fails, reload current page
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }, 1000);
        }
        // Set Active Menu
        function setActiveMenu(element) {
             // Hapus active dari semua menu
             document.querySelectorAll('.menu-item').forEach(item => {
                 item.classList.remove('active');
             });
    
             // Tambah active ke menu yang diklik
             element.classList.add('active');
        }
    </script>
</body>
</html>