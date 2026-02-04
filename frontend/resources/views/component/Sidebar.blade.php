<aside id="sidebar" class="group sidebar bg-[#111F4D] flex flex-col h-screen shadow-lg transition-all duration-300 fixed left-0 top-0 pt-[70px] z-40 w-64 [&.collapsed]:w-20">

    <nav class="flex-1 py-4 overflow-y-auto px-2">
        <ul class="space-y-2 ">

            <li>
                <a href="/" 
                   class="menu-item flex items-center px-6 py-2.5 text-sm hover:bg-gray-100 transition-colors
                   {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 rounded' : 'text-white' }}
                   group-[.collapsed]:justify-center group-[.collapsed]:px-2"> <i class="fas fa-house w-5 text-lg"></i>
                    <span class="ml-3 menu-text font-medium group-[.collapsed]:hidden">Dashboard</span>
                </a>
            </li>

            @php
                $isBlogActive = request()->routeIs('blog.*');
            @endphp
            <li>
                <button onclick="toggleSubmenu('blogSubmenu')"
                    class="menu-item flex items-center justify-between w-full px-6 py-2.5 text-sm hover:bg-gray-100 transition-colors hover:rounded
                    {{ $isBlogActive ? 'bg-blue-50 text-blue-600' : 'text-white' }}
                    group-[.collapsed]:justify-center group-[.collapsed]:px-2">
                    
                    <div class="flex items-center">
                        <i class="fas fa-newspaper w-5 text-lg"></i>
                        <span class="ml-3 menu-text font-medium group-[.collapsed]:hidden">Blog</span>
                    </div>

                    <i id="blogIcon" class="fas fa-chevron-down text-xs transition-transform duration-200 
                    {{ $isBlogActive ? 'rotate-180' : '' }}
                    group-[.collapsed]:hidden"></i>
                </button>

                <ul id="blogSubmenu" class="space-y-2 mt-1 bg-[#111F4D] 
                    {{ $isBlogActive ? '' : 'hidden' }}
                    group-[.collapsed]:hidden"> 
                    
                    <li>
                        <a href="/blog/tambah" 
                           class="submenu-item flex items-center pl-14 pr-6 py-2 text-xs hover:text-blue-600 hover:bg-gray-100 hover:rounded
                           {{ request()->routeIs('blog.create') ? 'text-blue-600 font-bold' : 'text-white' }}">
                            <i class="fas fa-file-circle-plus w-4"></i>
                            <span class="ml-2">Tambah Blog</span>
                        </a>
                    </li>
                    <li>
                        <a href="/blog/list" 
                           class="submenu-item flex items-center pl-14 pr-6 py-2 text-xs hover:text-blue-600 hover:bg-gray-100 hover:rounded
                           {{ request()->routeIs('blog.index') ? 'text-blue-600 font-bold' : 'text-white' }}">
                            <i class="fas fa-list-ul w-4"></i>
                            <span class="ml-2">List Blog</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="/kategori" 
                   class="menu-item flex items-center px-6 py-2.5 text-sm hover:bg-gray-100 transition-colors hover:rounded
                   {{ request()->routeIs('kategori.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-white' }}
                   group-[.collapsed]:justify-center group-[.collapsed]:px-2">
                   
                    <i class="fas fa-tags w-5 text-lg"></i>
                    <span class="ml-3 menu-text font-medium group-[.collapsed]:hidden">Kategori & Tag</span>
                </a>
            </li>

            <li>
                <a href="/iklan" 
                   class="menu-item flex items-center px-6 py-2.5 text-sm hover:bg-gray-100 transition-colors hover:rounded
                   {{ request()->routeIs('iklan.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-white' }}
                   group-[.collapsed]:justify-center group-[.collapsed]:px-2">
                   
                    <i class="fas fa-rectangle-ad w-5 text-lg"></i>
                    <span class="ml-3 menu-text font-medium group-[.collapsed]:hidden">Iklan</span>
                </a>
            </li>

            <li>
                <a href="/ejurnal" 
                   class="menu-item flex items-center px-6 py-2.5 text-sm hover:bg-gray-100 transition-colors hover:rounded
                   {{ request()->routeIs('ejurnal.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-white' }}
                   group-[.collapsed]:justify-center group-[.collapsed]:px-2">
                   
                    <i class="fas fa-book-bookmark w-5 text-lg"></i>
                    <span class="ml-3 menu-text font-medium group-[.collapsed]:hidden">E-Jurnal</span>
                </a>
            </li>

            <li>
                <a href="/admin" 
                   class="menu-item flex items-center px-6 py-2.5 text-sm hover:bg-gray-100 transition-colors hover:rounded
                   {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-white' }}
                   group-[.collapsed]:justify-center group-[.collapsed]:px-2">
                   
                    <i class="fas fa-user-gear w-5 text-lg"></i>
                    <span class="ml-3 menu-text font-medium group-[.collapsed]:hidden">Admin</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>