<nav class="navbar">
    <div class="navbar-brand">
        <span class="app-name">Portal Berita</span>

        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    {{-- USER DROPDOWN --}}
    <div class="user-dropdown" onclick="toggleDropdown()">

        <div class="user-avatar">
            {{ substr(session('user.name', 'SA'), 0, 2) }}
        </div>

        <span class="user-name">
            {{ session('user.name', 'Super Admin') }}
        </span>

        <span class="dropdown-arrow">
            <i class="fas fa-chevron-down"></i>
        </span>

        <div class="dropdown-menu" id="dropdownMenu">

            {{-- PROFILE --}}
            <a href="{{ route('profile') }}" class="dropdown-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>

            <hr class="dropdown-divider">


            {{-- LOGOUT --}}
            <a href="#"
               class="dropdown-item text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-power-off"></i>
                <span>Logout</span>
            </a>

            {{-- FORM LOGOUT (WAJIB ADA) --}}
            <form id="logout-form"
                  action="{{ route('logout') }}"
                  method="POST"
                  style="display:none;">
                @csrf
            </form>

        </div>
    </div>
</nav>

<script>
    function toggleDropdown() {
        document.getElementById('dropdownMenu').classList.toggle('show');
    }

    // Tutup dropdown jika klik di luar
    window.addEventListener('click', function (e) {
        if (!e.target.closest('.user-dropdown')) {
            document.getElementById('dropdownMenu').classList.remove('show');
        }
    });
</script>
