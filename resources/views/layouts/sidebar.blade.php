<aside class="w-64 bg-white shadow-lg h-screen sticky top-0">
    <div class="p-4">
        {{-- Menu Items --}}
        <nav class="space-y-2">
            {{-- Catatan Keuangan --}}
            <a href="{{ route('keuangan.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('keuangan.*') ? 'active-link' : 'text-gray-700' }}">
                <i class="fas fa-book w-5"></i>
                <span class="font-medium">Catatan Keuangan</span>
            </a>

            {{-- Divider --}}
            <hr class="my-4 border-gray-200">

            {{-- Settings --}}
            <a href="{{ route('pengaturan.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('pengaturan.*') ? 'active-link' : 'text-gray-700' }}">
                <i class="fas fa-cog w-5"></i>
                <span class="font-medium">Pengaturan</span>
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-colors duration-200 text-gray-700 w-full text-left hover:bg-red-50 hover:text-red-600">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="font-medium">Keluar</span>
                </button>
            </form>
        </nav>
    </div>
</aside>
