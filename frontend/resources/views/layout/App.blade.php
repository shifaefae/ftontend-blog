<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>@yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    @stack('styles')
    
    <style>
        /* --- 1. GLOBAL RESET & SCROLL --- */
        * {
            box-sizing: border-box;
        }
        
        body {
            background-color: #f3f4f6;
            overflow-x: hidden; /* Mencegah scroll ke samping */
            width: 100%;
        }

        /* --- 2. NAVBAR --- */
        .navbar {
            background-color: #1C4D8D;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            z-index: 50; /* Z-index tinggi agar di atas sidebar */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
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

        /* Toggle Button */
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
            transition: all 0.3s ease;
            margin-left: 15px;
        }
        .toggle-btn:hover { transform: scale(1.05); background-color: rgba(255, 255, 255, 0.3); }

        /* User Dropdown */
        .user-dropdown {
            display: flex; align-items: center; gap: 12px; cursor: pointer;
            padding: 8px 16px; border-radius: 8px; position: relative;
        }
        .user-dropdown:hover { background-color: rgba(255, 255, 255, 0.1); }
        
        .user-avatar {
            width: 36px; height: 36px; background-color: #f59e0b;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 14px;
        }
        .user-name { color: #ffffff; font-weight: 500; font-size: 14px; }
        .dropdown-arrow { color: #ffffff; font-size: 12px; }

        .dropdown-menu {
            display: none; position: absolute; top: 60px; right: 0;
            background-color: #ffffff; border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); min-width: 200px;
            overflow: hidden; z-index: 100;
        }
        .dropdown-menu.active { display: block; }
        .dropdown-item {
            padding: 12px 20px; cursor: pointer; transition: 0.3s;
            color: #374151; font-size: 14px; display: flex; align-items: center; gap: 10px;
        }
        .dropdown-item:hover { background-color: #f3f4f6; color: #1C4D8D; }


        /* --- 3. SIDEBAR STYLES --- */
        .sidebar {
            min-height: 100vh;
            background: #111F4D;
            box-shadow: 2px 0 30px rgba(0, 0, 0, 0.05);
            transition: width 0.3s ease;
            width: 16rem; /* 256px */
            position: fixed;
            top: 0;
            left: 0;
            z-index: 40; /* Di bawah navbar */
            padding-top: 90px; /* Ruang navbar + sedikit gap */
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed { width: 5rem; /* 80px */ }

        /* Menu Items */
        .menu-item {
            display: flex; align-items: center; padding: 12px 24px;
            color: #4b5563; transition: all 0.2s ease; text-decoration: none;
            white-space: nowrap; /* Mencegah teks turun baris */
        }
        .menu-item:hover { background-color: #f3f4f6; color: #1C4D8D; }
        
        .menu-item.active {
            color: #1C4D8D; font-weight: 600;
            border-right: 4px solid #1C4D8D; /* Pindah border ke kanan agar lebih modern */
            background: #eff6ff;
        }

        /* Submenu */
        .submenu-item {
            padding: 10px 10px 10px 3.5rem; display: flex; align-items: center;
            font-size: 13px; color: #6b7280; transition: 0.2s;
        }
        .submenu-item:hover { color: #1C4D8D; }

        /* Logout Button */
        .logout-container {
            margin-top: auto; /* Dorong ke paling bawah */
            padding: 20px;
            background: white;
            border-top: 1px solid #f3f4f6;
        }
        
        .btn-logout {
            width: 100%;
            background: linear-gradient(90deg, #ef4444, #ec4899);
            color: white; border-radius: 8px; padding: 10px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: 0.3s; border: none; cursor: pointer;
        }
        .btn-logout:hover { shadow: 0 4px 12px rgba(239, 68, 68, 0.3); transform: translateY(-1px); }

        /* --- 4. COLLAPSED LOGIC --- */
        /* Sembunyikan Text saat Collapsed */
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .logout-text,
        .sidebar.collapsed .chevron-icon,
        .sidebar.collapsed .app-name { 
            display: none; 
        }

        /* Center Icon saat Collapsed */
        .sidebar.collapsed .menu-item { justify-content: center; padding-left: 0; padding-right: 0; }
        
        /* Sembunyikan Submenu saat Collapsed */
        .sidebar.collapsed ul[id$="Submenu"] { display: none !important; }
        
        /* Logout jadi icon saja */
        .sidebar.collapsed .logout-container { padding: 20px 10px; }
        .sidebar.collapsed .btn-logout { justify-content: center; padding: 10px; }


        /* --- 5. MAIN CONTENT AREA --- */
        .main-content {
            margin-left: 16rem; /* Default margin selebar sidebar */
            padding: 30px;
            min-height: 100vh;
            padding-top: 100px; /* Navbar height + gap */
            transition: margin-left 0.3s ease;
            width: calc(100% - 16rem); /* Pastikan lebar tidak offside */
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 5rem;
            width: calc(100% - 5rem);
        }

        /* --- 6. RESPONSIVE (Mobile) --- */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); } /* Sembunyikan sidebar di mobile */
            .sidebar.active-mobile { transform: translateX(0); } /* Munculkan jika aktif */
            
            .main-content { margin-left: 0; width: 100%; padding: 20px; padding-top: 90px; }
            .sidebar.collapsed ~ .main-content { margin-left: 0; width: 100%; }
            
            /* Navbar adjustments */
            .app-name { display: none; } /* Hemat ruang di HP */
        }
    </style>
</head>
<body>

    @include('component.Navbar')

    <div class="flex">
        
        @include('component.Sidebar')

        <main class="main-content">
            @yield('content')
        </main>
        
    </div>

    @stack('scripts')
    <script>
        // --- Logic Dropdown User ---
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('active');
        }

        // Close dropdown jika klik di luar
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const menu = document.getElementById('dropdownMenu');
            if (dropdown && !dropdown.contains(e.target)) {
                menu.classList.remove('active');
            }
        });

        // --- Logic Sidebar Collapse ---
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const icon = document.getElementById('toggleIcon');
            
            sidebar.classList.toggle('collapsed');
            
            // Ganti icon panah
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
        }

        // --- Logic Submenu ---
        function toggleSubmenu(id) {
            const sidebar = document.getElementById('sidebar');
            // Jangan buka submenu jika sidebar sedang collapsed
            if (sidebar.classList.contains('collapsed')) return; 

            const submenu = document.getElementById(id);
            const icon = document.getElementById('blogIcon'); 
            
            submenu.classList.toggle('hidden');
            if(icon) icon.classList.toggle('rotate-180');
        }
    </script>

</body>
</html>