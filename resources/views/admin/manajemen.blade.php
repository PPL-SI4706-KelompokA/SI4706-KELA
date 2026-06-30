<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare Admin - Manajemen Pengguna</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F8EC; color: #333; }</style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between py-6">
        <div>
            <div class="px-8 mb-10">
                <a href="{{ route('donasi.daftar') }}"><h1 class="text-xl font-extrabold text-[#6B630C] hover:opacity-80">FoodShare</h1></a>
                <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Editorial Admin</p>
            </div>
            <nav class="space-y-1">
                <a href="{{ route('admin.manajemen') }}" class="flex items-center px-8 py-3 bg-[#FCF8E3] text-[#6B630C] border-r-4 border-[#FCD34D] font-bold text-sm">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Manajemen
                </a>
                <a href="{{ route('admin.laporan') }}" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Distribusi
                </a>
                <a href="{{ route('admin.verifikasi') }}" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi
                </a>
                <a href="{{ route('admin.pemberitahuan') }}" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    Pemberitahuan
                </a>
                <a href="{{ route('admin.statistik') }}" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    Statistik
                </a>
            </nav>
        </div>
        <div class="px-8 mb-8">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center text-gray-400 hover:text-gray-600 text-sm font-semibold">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <!-- Topbar -->
        <header class="flex items-center justify-end px-10 py-6 shrink-0">
            <div class="flex items-center space-x-4 text-gray-400">
                <x-admin-notifications />
                <a href="{{ route('profile.show') }}">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=F97316&color=fff" class="w-8 h-8 rounded-full border-2 border-white shadow-sm hover:opacity-90 transition">
                </a>
            </div>
        </header>

        <!-- Content -->
        <div class="px-10 py-4 flex-1 flex flex-col">
            <div class="mb-10">
                <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight mb-2">Manajemen Pengguna</h2>
                <p class="text-gray-500 text-sm font-medium">Monitor and manage the heartbeat of our community. Oversee profiles for both food donors and recipients.</p>
            </div>

            <!-- Filters -->
            <div class="flex justify-between items-center mb-10 gap-6">
                <div class="bg-white rounded-full p-1.5 flex shadow-sm border border-gray-50 animate-fade-in shrink-0">
                    <a href="{{ route('admin.manajemen', array_merge(request()->query(), ['role' => 'Donatur'])) }}" 
                       class="px-8 py-2 font-bold text-sm rounded-full transition {{ request('role', 'Donatur') === 'Donatur' ? 'bg-[#FCF8E3] text-[#6B630C] shadow-sm border border-gray-100' : 'text-gray-500 hover:bg-gray-50' }}">
                       Donatur
                    </a>
                    <a href="{{ route('admin.manajemen', array_merge(request()->query(), ['role' => 'Penerima'])) }}" 
                       class="px-8 py-2 font-bold text-sm rounded-full transition {{ request('role') === 'Penerima' ? 'bg-[#FCF8E3] text-[#6B630C] shadow-sm border border-gray-100' : 'text-gray-500 hover:bg-gray-50' }}">
                       Penerima
                    </a>
                </div>

                <!-- Page specific search form, moved from topbar -->
                <form action="{{ route('admin.manajemen') }}" method="GET" class="relative w-72">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full bg-white rounded-full py-2.5 pl-10 pr-6 text-xs font-semibold text-gray-700 border border-gray-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FCD34D]">
                </form>

                <div class="flex items-center space-x-3 text-sm font-bold shrink-0">
                    <span class="text-gray-400 mr-2 tracking-widest text-[10px] uppercase">Filter:</span>
                    <a href="{{ route('admin.manajemen', array_merge(request()->query(), ['status' => 'all'])) }}" 
                       class="px-6 py-2 rounded-full transition {{ request('status', 'all') === 'all' ? 'bg-[#FCD34D] text-[#6B630C] shadow-sm' : 'bg-[#EAEBCA] text-gray-500 hover:bg-[#dcdcaa]' }}">
                       Semua Status
                    </a>
                    <a href="{{ route('admin.manajemen', array_merge(request()->query(), ['status' => 'aktif'])) }}" 
                       class="px-6 py-2 rounded-full transition {{ request('status') === 'aktif' ? 'bg-[#FCD34D] text-[#6B630C] shadow-sm' : 'bg-[#EAEBCA] text-gray-500 hover:bg-[#dcdcaa]' }}">
                       Aktif
                    </a>
                    <a href="{{ route('admin.manajemen', array_merge(request()->query(), ['status' => 'nonaktif'])) }}" 
                       class="px-6 py-2 rounded-full transition {{ request('status') === 'nonaktif' ? 'bg-[#FCD34D] text-[#6B630C] shadow-sm' : 'bg-[#EAEBCA] text-gray-500 hover:bg-[#dcdcaa]' }}">
                       Nonaktif
                    </a>
                </div>
            </div>

            <!-- User Grid -->
            <div class="grid grid-cols-3 gap-6 mb-10">
                @forelse($users as $user)
                <div class="user-card bg-white rounded-[32px] p-6 shadow-sm border border-gray-50 hover:border-[#FCD34D] hover:shadow-md hover:scale-[1.01] transition-all cursor-pointer"
                     data-id-user="{{ $user->id_user }}"
                     data-nama="{{ $user->nama }}"
                     data-email="{{ $user->email }}"
                     data-role="{{ $user->role }}"
                     data-telp="{{ $user->no_telp ?? '-' }}"
                     data-alamat="{{ $user->alamat ?? '-' }}"
                     data-status="{{ $user->banned_status && $user->banned_status !== 'not_banned' ? 'Banned' : ($user->status_verifikasi === 'Sudah Verifikasi' ? 'Aktif' : 'Nonaktif') }}"
                     data-banned-status="{{ $user->banned_status }}"
                     data-banned-reason="{{ $user->banned_reason }}"
                     data-banned-until="{{ $user->banned_until ? \Carbon\Carbon::parse($user->banned_until)->format('d M Y H:i') : '' }}"
                     data-avatar="{{ $user->foto_url ? asset($user->foto_url) : '' }}">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center">
                            @if($user->foto_url)
                                <img src="{{ asset($user->foto_url) }}" class="w-14 h-14 rounded-full object-cover mr-4 border-2 border-white shadow-sm">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=FCD34D&color=6B630C" class="w-14 h-14 rounded-full mr-4 border-2 border-white shadow-sm">
                            @endif
                            <div>
                                <h4 class="font-extrabold text-gray-800">{{ $user->nama }}</h4>
                                <p class="text-xs font-medium text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="relative">
                            <button onclick="toggleActionMenu(event, {{ $user->id_user }})" class="text-gray-300 hover:text-gray-500 focus:outline-none bg-transparent border-none cursor-pointer p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </button>
                            <div id="action-menu-{{ $user->id_user }}" class="action-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 z-30 overflow-hidden">
                                @if($user->banned_status && $user->banned_status !== 'not_banned')
                                    <button onclick="handleUnban(event, {{ $user->id_user }}, '{{ $user->nama }}')" class="w-full text-left px-5 py-3 hover:bg-gray-50 text-sm font-bold text-green-600 transition flex items-center gap-2 border-none bg-transparent cursor-pointer">
                                        <span>🔓</span> Lepas Ban
                                    </button>
                                @else
                                    <button onclick="openBanModal(event, {{ $user->id_user }}, '{{ $user->nama }}')" class="w-full text-left px-5 py-3 hover:bg-gray-50 text-sm font-bold text-red-600 transition flex items-center gap-2 border-none bg-transparent cursor-pointer">
                                        <span>🚫</span> Ban Akun
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-50 pt-4">
                        <span class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Peran: {{ $user->role }}</span>
                        @if($user->banned_status && $user->banned_status !== 'not_banned')
                            <span class="text-[10px] font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full uppercase tracking-wider">Banned</span>
                        @elseif($user->status_verifikasi === 'Sudah Verifikasi')
                            <span class="text-[10px] font-bold text-[#4CAF50] bg-[#E8F5E9] px-3 py-1 rounded-full uppercase tracking-wider">Aktif</span>
                        @else
                            <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full uppercase tracking-wider">Nonaktif</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-3 bg-white/40 rounded-[32px] p-12 text-center text-gray-500 font-semibold text-sm">
                    Tidak ada pengguna yang ditemukan.
                </div>
                @endforelse
            </div>
 
            <!-- Pagination & Footer -->
            <div class="flex flex-col items-center mt-auto pb-4">
                <div class="mb-8">
                    {{ $users->appends(request()->query())->links() }}
                </div>
                <p class="text-[10px] text-gray-400 font-medium tracking-wide">© 2026 FoodShare Admin Portal. Building stronger communities through shared tables.</p>
            </div>
        </div>
    </main>

    <!-- User Detail Modal -->
    <div id="user-detail-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" id="modal-backdrop"></div>
        
        <!-- Modal Content Container -->
        <div class="relative bg-white rounded-[32px] w-full max-w-md p-8 shadow-2xl z-10 transform scale-95 opacity-0 transition-all duration-300" id="modal-content">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Detail Pengguna</h3>
                <button id="close-modal-btn" class="p-2 hover:bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition border-none outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="flex flex-col items-center mb-8">
                <img id="modal-avatar" src="" class="w-24 h-24 rounded-full border-4 border-[#FCD34D]/20 shadow-md mb-4">
                <h4 id="modal-nama" class="text-2xl font-extrabold text-gray-800 text-center"></h4>
                <div id="modal-role-container">
                    <span id="modal-role-badge" class="inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-2"></span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">📧</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat Email</p>
                        <p id="modal-email" class="text-sm font-semibold text-gray-700 break-all"></p>
                    </div>
                </div>
                
                <div class="bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">📞</div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor Telepon</p>
                        <p id="modal-telp" class="text-sm font-semibold text-gray-700"></p>
                    </div>
                </div>
                
                <div class="bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">📍</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat Lengkap</p>
                        <p id="modal-alamat" class="text-sm font-semibold text-gray-700 leading-relaxed"></p>
                    </div>
                </div>

                <div class="bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">🛡️</div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Status Akun</p>
                        <span id="modal-status" class="inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1"></span>
                    </div>
                </div>

                <div id="modal-ban-details" class="bg-red-50 rounded-2xl p-4 flex items-start gap-4 hidden border border-red-100">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">🚫</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-0.5">Detail Pemblokiran</p>
                        <p class="text-xs font-semibold text-red-700 mb-1">Tipe: <span id="modal-ban-type" class="font-extrabold"></span></p>
                        <p id="modal-ban-expiry-container" class="text-xs font-semibold text-red-700 mb-1 hidden">Hingga: <span id="modal-ban-expiry" class="font-extrabold"></span></p>
                        <p class="text-xs font-semibold text-red-700 mb-3">Alasan: <span id="modal-ban-reason" class="font-normal italic"></span></p>
                        <button id="modal-unban-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-xs shadow-sm transition border-none cursor-pointer">
                            🔓 Lepas Pemblokiran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ban User Modal -->
    <div id="ban-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeBanModal()"></div>
        
        <!-- Modal Content Container -->
        <div class="relative bg-white rounded-[32px] w-full max-w-md p-8 shadow-2xl z-10 transform scale-95 opacity-0 transition-all duration-300" id="ban-modal-content">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Ban Pengguna</h3>
                <button onclick="closeBanModal()" class="p-2 hover:bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition border-none outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="ban-form" method="POST" action="">
                @csrf
                <div class="mb-6">
                    <p class="text-sm font-semibold text-gray-600">Anda akan memblokir akun: <span id="ban-user-name" class="font-extrabold text-gray-800"></span></p>
                </div>

                <!-- Ban Type -->
                <div class="mb-6">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Tipe Blokir</label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-sm text-gray-700">
                            <input type="radio" name="banned_status" value="temporary" checked onchange="toggleBanDuration(true)" class="accent-[#6B630C]">
                            Sementara
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-semibold text-sm text-gray-700">
                            <input type="radio" name="banned_status" value="permanent" onchange="toggleBanDuration(false)" class="accent-[#6B630C]">
                            Permanen
                        </label>
                    </div>
                </div>

                <!-- Ban Duration (Banned Until) -->
                <div class="mb-6" id="duration-container">
                    <label for="banned_until" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Blokir Hingga</label>
                    <input type="datetime-local" name="banned_until" id="banned_until" required
                        class="w-full bg-[#F8F8EC] rounded-2xl py-3 px-4 text-sm font-semibold text-gray-700 border border-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FCD34D]">
                </div>

                <!-- Ban Reason -->
                <div class="mb-8">
                    <label for="banned_reason" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Keterangan / Alasan</label>
                    <textarea name="banned_reason" id="banned_reason" rows="3" required placeholder="Masukkan alasan pemblokiran..."
                        class="w-full bg-[#F8F8EC] rounded-2xl py-3 px-4 text-sm font-semibold text-gray-700 border border-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] resize-none"></textarea>
                </div>

                <div class="flex items-center gap-3 justify-end">
                    <button type="button" onclick="closeBanModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 px-6 rounded-full text-xs transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-xs shadow-md transition">
                        Blokir Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unban User Form -->
    <form id="unban-form" method="POST" action="" class="hidden">
        @csrf
    </form>

    <script>
        // Toggle Action Dropdown Menu
        window.toggleActionMenu = function(event, id_user) {
            event.stopPropagation();
            event.preventDefault();
            
            // Close other open action menus
            document.querySelectorAll('.action-menu').forEach(menu => {
                if (menu.id !== `action-menu-${id_user}`) {
                    menu.classList.add('hidden');
                }
            });
            
            const menu = document.getElementById(`action-menu-${id_user}`);
            if (menu) {
                menu.classList.toggle('hidden');
            }
        };

        // Open Ban Modal
        window.openBanModal = function(event, id_user, nama) {
            event.stopPropagation();
            event.preventDefault();
            
            // Close action menu
            const menu = document.getElementById(`action-menu-${id_user}`);
            if (menu) menu.classList.add('hidden');
            
            const banModal = document.getElementById('ban-modal');
            const banModalContent = document.getElementById('ban-modal-content');
            const banForm = document.getElementById('ban-form');
            const banUserName = document.getElementById('ban-user-name');
            
            // Reset form
            banForm.reset();
            window.toggleBanDuration(true); // Default is temporary
            
            // Set action URL and user name
            banForm.action = `/admin/manajemen/${id_user}/ban`;
            banUserName.textContent = nama;
            
            // Show modal
            banModal.classList.remove('hidden');
            setTimeout(() => {
                banModalContent.classList.remove('scale-95', 'opacity-0');
                banModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        // Close Ban Modal
        window.closeBanModal = function() {
            const banModal = document.getElementById('ban-modal');
            const banModalContent = document.getElementById('ban-modal-content');
            
            banModalContent.classList.remove('scale-100', 'opacity-100');
            banModalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                banModal.classList.add('hidden');
            }, 300);
        };

        // Toggle Banned Duration Input
        window.toggleBanDuration = function(show) {
            const container = document.getElementById('duration-container');
            const input = document.getElementById('banned_until');
            if (show) {
                container.classList.remove('hidden');
                input.required = true;
                input.disabled = false;
            } else {
                container.classList.add('hidden');
                input.required = false;
                input.disabled = true;
            }
        };

        // Handle Unban Submission
        window.handleUnban = function(event, id_user, nama) {
            event.stopPropagation();
            event.preventDefault();
            
            // Close action menu
            const menu = document.getElementById(`action-menu-${id_user}`);
            if (menu) menu.classList.add('hidden');
            
            if (confirm(`Apakah Anda yakin ingin melepas pemblokiran untuk akun "${nama}"?`)) {
                const unbanForm = document.getElementById('unban-form');
                unbanForm.action = `/admin/manajemen/${id_user}/unban`;
                unbanForm.submit();
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('user-detail-modal');
            const backdrop = document.getElementById('modal-backdrop');
            const modalContent = document.getElementById('modal-content');
            const closeBtn = document.getElementById('close-modal-btn');
            
            // Modal elements to fill
            const mAvatar = document.getElementById('modal-avatar');
            const mNama = document.getElementById('modal-nama');
            const mEmail = document.getElementById('modal-email');
            const mTelp = document.getElementById('modal-telp');
            const mAlamat = document.getElementById('modal-alamat');
            const mRoleBadge = document.getElementById('modal-role-badge');
            const mStatus = document.getElementById('modal-status');
            
            function openModal(data) {
                // Populate fields
                mNama.textContent = data.nama;
                mEmail.textContent = data.email;
                mTelp.textContent = data.telp || '-';
                mAlamat.textContent = data.alamat || '-';
                
                // Avatar
                if (data.avatar && data.avatar !== '') {
                    mAvatar.src = data.avatar;
                } else {
                    mAvatar.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.nama)}&background=FCD34D&color=6B630C&size=128`;
                }
                
                // Role Badge styling
                mRoleBadge.textContent = data.role;
                if (data.role === 'Donatur') {
                    mRoleBadge.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-2 bg-amber-100 text-amber-800 border border-amber-200';
                } else if (data.role === 'Penerima') {
                    mRoleBadge.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-2 bg-blue-100 text-blue-800 border border-blue-200';
                } else {
                    mRoleBadge.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-2 bg-gray-100 text-gray-800 border border-gray-200';
                }
                
                // Status Badge styling
                mStatus.textContent = data.status;
                if (data.status === 'Aktif') {
                    mStatus.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1 bg-green-100 text-[#4CAF50]';
                } else if (data.status === 'Banned') {
                    mStatus.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1 bg-red-100 text-red-600 border border-red-200';
                } else {
                    mStatus.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1 bg-gray-200 text-gray-600';
                }
                
                // Ban details display
                const mBanDetails = document.getElementById('modal-ban-details');
                const mBanType = document.getElementById('modal-ban-type');
                const mBanExpiryContainer = document.getElementById('modal-ban-expiry-container');
                const mBanExpiry = document.getElementById('modal-ban-expiry');
                const mBanReason = document.getElementById('modal-ban-reason');
                const mUnbanBtn = document.getElementById('modal-unban-btn');

                if (data.bannedStatus && data.bannedStatus !== 'not_banned') {
                    mBanDetails.classList.remove('hidden');
                    mBanType.textContent = data.bannedStatus === 'permanent' ? 'Permanen' : 'Sementara';
                    mBanReason.textContent = data.bannedReason || 'Tidak ada alasan khusus.';
                    
                    if (data.bannedStatus === 'temporary' && data.bannedUntil) {
                        mBanExpiryContainer.classList.remove('hidden');
                        mBanExpiry.textContent = data.bannedUntil;
                    } else {
                        mBanExpiryContainer.classList.add('hidden');
                    }

                    // Wire up the unban button
                    mUnbanBtn.onclick = function(e) {
                        closeModal();
                        window.handleUnban(e, data.idUser, data.nama);
                    };
                } else {
                    mBanDetails.classList.add('hidden');
                }
                
                // Show modal with animation
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
            
            function closeModal() {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
            
            // Card clicks
            document.querySelectorAll('.user-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Prevent trigger when clicking inner buttons
                    if (e.target.closest('button') || e.target.closest('.relative') || e.target.closest('a')) {
                        return;
                    }
                    
                    const data = {
                        idUser: this.getAttribute('data-id-user'),
                        nama: this.getAttribute('data-nama'),
                        email: this.getAttribute('data-email'),
                        role: this.getAttribute('data-role'),
                        telp: this.getAttribute('data-telp'),
                        alamat: this.getAttribute('data-alamat'),
                        status: this.getAttribute('data-status'),
                        bannedStatus: this.getAttribute('data-banned-status'),
                        bannedReason: this.getAttribute('data-banned-reason'),
                        bannedUntil: this.getAttribute('data-banned-until'),
                        avatar: this.getAttribute('data-avatar')
                    };
                    
                    openModal(data);
                });
            });
            
            // Close handlers
            closeBtn.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Close action menus when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.action-menu') && !e.target.closest('button')) {
                    document.querySelectorAll('.action-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });
        });
    </script>
</body>
</html>>
