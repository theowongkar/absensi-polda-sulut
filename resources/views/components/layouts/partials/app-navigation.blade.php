<div x-show="sidebarOpen" @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/50 z-20 transition-opacity scrollbar-custom md:hidden"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>

{{-- Sidebar Utama --}}
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 transform bg-black text-white transition duration-300 md:translate-x-0 md:static md:inset-0 flex flex-col">

    {{-- Header Sidebar --}}
    <div class="flex items-center mb-5 space-x-3 px-4 py-4">
        {{-- Logo Gambar --}}
        <img src="{{ asset('img/application-logo.svg') }}" alt="Logo Pemprov Sulut" class="w-15 h-15 object-contain">

        {{-- Tulisan --}}
        <div class="leading-tight">
            <h2 class="text-3xl font-bold">POLDA</h2>
            <span class="text-[#ffdd19]">Sulawesi Utara</span>
        </div>
    </div>

    {{-- Navigasi Menu --}}
    <nav class="flex-1 overflow-y-auto px-4 space-y-3">
        {{-- Menu --}}
        <div>
            <h1 class="mb-1 text-xs text-[#ffdd19] font-bold uppercase">Menu</h1>
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-2 text-sm font-semibold rounded-md hover:bg-yellow-100 {{ Route::is('dashboard') ? 'bg-gray-100 text-gray-800 animate-pulse' : 'text-white hover:text-gray-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-house-door-fill" viewBox="0 0 16 16">
                    <path
                        d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5" />
                </svg>
                <span>Dashboard</span>
            </a>
            @can('manage-employee')
                <a href="{{ route('dashboard.employee.index') }}"
                    class="flex items-center space-x-3 px-4 py-2 text-sm font-semibold rounded-md hover:bg-yellow-100 {{ Route::is('dashboard.employee.*') ? 'bg-gray-100 text-gray-800 animate-pulse' : 'text-white hover:text-gray-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path
                            d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                    </svg>
                    <span>Data Pegawai</span>
                @endcan
            </a>
        </div>

        {{-- Absensi --}}
        <div>
            <h1 class="mb-1 text-xs text-[#ffdd19] font-bold uppercase">Absensi</h1>
            <a href="{{ route('dashboard.my-attendance.index') }}"
                class="flex items-center space-x-3 px-4 py-2 text-sm font-semibold rounded-md hover:bg-yellow-100 {{ Route::is('dashboard.my-attendance.index') ? 'bg-gray-100 text-gray-800 animate-pulse' : 'text-white hover:text-gray-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-eyeglasses" viewBox="0 0 16 16">
                    <path
                        d="M4 6a2 2 0 1 1 0 4 2 2 0 0 1 0-4m2.625.547a3 3 0 0 0-5.584.953H.5a.5.5 0 0 0 0 1h.541A3 3 0 0 0 7 8a1 1 0 0 1 2 0 3 3 0 0 0 5.959.5h.541a.5.5 0 0 0 0-1h-.541a3 3 0 0 0-5.584-.953A2 2 0 0 0 8 6c-.532 0-1.016.208-1.375.547M14 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0" />
                </svg>
                <span>Presensi Saya</span>
            </a>
            <a href="{{ route('dashboard.my-attendance.create') }}"
                class="flex items-center space-x-3 px-4 py-2 text-sm font-semibold rounded-md hover:bg-yellow-100 {{ Route::is('dashboard.my-attendance.create') ? 'bg-gray-100 text-gray-800 animate-pulse' : 'text-white hover:text-gray-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-upload" viewBox="0 0 16 16">
                    <path
                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                    <path
                        d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                </svg>
                <span>Input Presensi</span>
            </a>
            @can('manage-attendance')
                <a href="{{ route('dashboard.attendance.index') }}"
                    class="flex items-center space-x-3 px-4 py-2 text-sm font-semibold rounded-md hover:bg-yellow-100 {{ Route::is('dashboard.attendance.*') ? 'bg-gray-100 text-gray-800 animate-pulse' : 'text-white hover:text-gray-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-clipboard-data" viewBox="0 0 16 16">
                        <path
                            d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0z" />
                        <path
                            d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                        <path
                            d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                    </svg>
                    <span>Kelola Presensi</span>
                @endcan
            </a>
        </div>
    </nav>
</div>
