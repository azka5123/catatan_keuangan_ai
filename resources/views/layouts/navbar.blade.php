<nav class="bg-white shadow-lg border-b border-gray-200">
    <div class="max-w-full px-4">
        <div class="flex justify-between items-center h-16">
            {{-- Logo / Brand --}}
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-wallet text-blue-600 mr-2"></i>
                    Catatan Keuangan
                </h1>
            </div>

            {{-- User Menu --}}
            <div class="flex items-center space-x-4">
                {{-- Notifications --}}
                {{-- <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-bell text-lg"></i>
                    <span
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
                </button> --}}

                {{-- User Profile --}}
                <div class="relative">
                    <button
                        class="flex items-center space-x-2 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                        <img class="w-8 h-8 rounded-full"
                            src="{{  Auth::user()->photo ? asset('/dist/img/profil/' . Auth::user()->photo) : 'https://placehold.co/400' }}"
                            alt="User Avatar">
                        <span class="font-medium">{{ Auth::user()->name ?? Auth::user()->no_hp }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
