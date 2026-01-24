<nav class="navbar">
    <div class="navbar-brand">
        <span class="app-name">Portal Berita</span>

        <button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()">
            <i class="fas fa-chevron-left" id="toggleIcon"></i>
        </button>
    </div>

    <div class="user-dropdown" id="userDropdown" onclick="toggleDropdown()">
        
        <div class="user-avatar">
            {{ substr(Auth::user()->name ?? 'SA', 0, 2) }}
        </div>
        
        <span class="user-name">{{ Auth::user()->name ?? 'Super Admin' }}</span>
        
        <span class="dropdown-arrow">
            <i class="fas fa-chevron-down"></i>
        </span>

        <div class="dropdown-menu" id="dropdownMenu">
            
            <a href="" class="dropdown-item">
                <i class="fas fa-user-gear"></i>
                <span>Admin</span>
            </a>

            <a href="" class="dropdown-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>

            <hr class="dropdown-divider">

            <a href="#" class="dropdown-item text-danger" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-power-off"></i>
                <span>Logout</span>
            </a>

            <form id="logout-form" action="" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('show');
    }

    // Menutup dropdown jika klik di luar
    window.onclick = function(event) {
        if (!event.target.closest('.user-dropdown')) {
            var dropdowns = document.getElementsByClassName("dropdown-menu");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>