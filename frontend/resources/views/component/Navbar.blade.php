<nav class="navbar">
    <div class="navbar-brand">
        <span class="app-name">Portal Berita</span>

        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    {{-- USER DROPDOWN --}}
    @php
        $navUser     = session('user');                      // array user dari BE
        $navName     = $navUser['name']     ?? 'Super Admin';
        $navRole     = $navUser['role']     ?? 'user';
        $navThumb    = $navUser['thumbnail'] ?? null;

        $roleLabel = match($navRole) {
            'super_admin' => ['label' => 'Super Admin', 'class' => 'bg-red-100 text-red-600'],
            'admin'       => ['label' => 'Admin',       'class' => 'bg-blue-100 text-blue-600'],
            default       => ['label' => 'User',        'class' => 'bg-gray-100 text-gray-600'],
        };

        // Proxy URL agar gambar ngrok bisa tampil via localhost
        $navFotoUrl = $navThumb
            ? route('proxy.image', ['url' => $navThumb])
            : null;
    @endphp

    <div class="user-dropdown" onclick="toggleDropdown()">

        {{-- Avatar: foto jika ada, inisial jika tidak --}}
        <div class="user-avatar overflow-hidden">
            @if ($navFotoUrl)
                <img src="{{ $navFotoUrl }}"
                     alt="{{ $navName }}"
                     class="w-full h-full object-cover rounded-full">
            @else
                {{ substr($navName, 0, 2) }}
            @endif
        </div>

        {{-- Nama + Badge Role --}}
        <div class="flex flex-col leading-tight">
            <span class="user-name">{{ $navName }}</span>
            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $roleLabel['class'] }} w-fit">
                {{ $roleLabel['label'] }}
            </span>
        </div>

        <span class="dropdown-arrow">
            <i class="fas fa-chevron-down"></i>
        </span>

        <div class="dropdown-menu" id="dropdownMenu">

            {{-- PROFILE --}}
            <a href="{{ route('profile.index') }}" class="dropdown-item">
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

    window.addEventListener('click', function (e) {
        if (!e.target.closest('.user-dropdown')) {
            document.getElementById('dropdownMenu').classList.remove('show');
        }
    });
</script>