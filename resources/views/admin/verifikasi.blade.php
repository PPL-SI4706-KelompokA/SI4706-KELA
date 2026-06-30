<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare Admin - Verifikasi Donatur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F8EC; color: #333; }</style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between py-6 shrink-0">
        <div>
            <div class="px-8 mb-10">
                <a href="{{ route('donasi.daftar') }}"><h1 class="text-xl font-extrabold text-[#6B630C] hover:opacity-80">FoodShare</h1></a>
                <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Editorial Admin</p>
            </div>
            <nav class="space-y-1">
                <a href="{{ route('admin.manajemen') }}" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Manajemen
                </a>
                <a href="{{ route('admin.laporan') }}" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Distribusi
                </a>
                <a href="{{ route('admin.verifikasi') }}" class="flex items-center px-8 py-3 bg-[#FCF8E3] text-[#6B630C] border-r-4 border-[#FCD34D] font-bold text-sm">
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
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-2xl mb-6 text-sm font-semibold border border-green-200 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex gap-8 flex-1">
                
                <!-- Left Column -->
                <div class="w-[350px] flex flex-col gap-6 shrink-0">
                    <!-- Verification Queue List -->
                    <div class="bg-white rounded-[32px] p-6 shadow-sm">
                        <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-4">DAFTAR ANTRIAN VERIFIKASI</p>
                        <div class="space-y-3 max-h-[180px] overflow-y-auto pr-1">
                            @forelse($unverifiedUsers as $unv)
                                <a href="{{ route('admin.verifikasi', ['user_id' => $unv->id_user]) }}" 
                                   class="flex items-center p-3 rounded-2xl transition {{ $selectedUser && $selectedUser->id_user === $unv->id_user ? 'bg-[#FCF8E3]' : 'hover:bg-gray-50' }}">
                                    @if($unv->foto_url)
                                        <img src="{{ asset($unv->foto_url) }}" class="w-10 h-10 rounded-full object-cover mr-3 border-2 border-white shadow-sm">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($unv->nama) }}&background=FCD34D&color=6B630C" class="w-10 h-10 rounded-full mr-3 border-2 border-white shadow-sm">
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-xs text-gray-800 truncate">{{ $unv->nama }}</h4>
                                        <p class="text-[10px] text-gray-400 truncate">{{ $unv->email }}</p>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-gray-400 font-medium py-2">Tidak ada antrian verifikasi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="flex-grow flex flex-col gap-6">
                    @if($selectedUser)
                    <!-- Profile Card -->
                    <div class="bg-white rounded-[40px] p-8 flex flex-col items-center text-center shadow-sm relative pt-12 mt-12">
                        <div class="absolute -top-12 w-28 h-28 bg-[#112D32] rounded-full border-[6px] border-[#F8F8EC] overflow-hidden flex items-center justify-center">
                            @if($selectedUser->foto_url)
                                <img src="{{ asset($selectedUser->foto_url) }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($selectedUser->nama) }}&background=112D32&color=fff&size=200" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute bottom-2 bg-[#FCD34D] text-[#6B630C] text-[8px] font-bold px-3 py-0.5 rounded-full uppercase tracking-widest border border-white">{{ strtoupper($selectedUser->role) }}</div>
                        </div>
                        <h2 class="text-2xl font-extrabold text-gray-800 mt-2">{{ $selectedUser->nama }}</h2>
                        <p class="text-xs font-medium text-gray-400 mb-6">Donor ID: #SHR-{{ $selectedUser->id_user }}</p>
                        
                        <div class="w-full flex gap-3">
                            <div class="flex-1 bg-gray-50 rounded-2xl py-3 flex items-center justify-center text-xs font-bold text-gray-500">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Gabung {{ $selectedUser->created_at ? $selectedUser->created_at->format('M Y') : 'Baru' }}
                            </div>
                            <div class="flex-1 bg-[#F2FAD2] text-[#5B630C] rounded-2xl py-3 flex items-center justify-center text-xs font-bold border border-[#E1EF9F]">
                                <span class="w-2 h-2 bg-[#89C338] rounded-full mr-2"></span>
                                @if(strtolower($selectedUser->role) === 'donatur')
                                    Donatur Baru
                                @else
                                    Penerima Baru
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info Card -->
                    <div class="bg-white rounded-[40px] p-8 shadow-sm">
                        <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-6 pl-2">INFORMASI KONTAK</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-[#F8F8EC] rounded-full flex items-center justify-center text-[#6B630C] shadow-sm mr-4 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Nomor Telepon</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $selectedUser->no_telp }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-[#F8F8EC] rounded-full flex items-center justify-center text-[#6B630C] shadow-sm mr-4 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Lokasi</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $selectedUser->alamat }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-[#F8F8EC] rounded-full flex items-center justify-center text-[#6B630C] shadow-sm mr-4 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tipe Akun</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $selectedUser->role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="mt-auto bg-[#E8E9C9] rounded-full p-4 pl-8 flex items-center justify-between border border-[#D5D6A8] shadow-sm">
                        <p class="text-xs font-medium text-[#5B630C]">Pastikan seluruh informasi telah diperiksa sebelum memberikan keputusan.</p>
                        <div class="flex gap-4">
                            <form action="{{ route('admin.verifikasi.tolak', $selectedUser->id_user) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-8 py-3 rounded-full font-bold text-gray-500 hover:bg-white/50 transition flex items-center bg-transparent border-none outline-none cursor-pointer">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Tolak
                                </button>
                            </form>
                            <form action="{{ route('admin.verifikasi.setuju', $selectedUser->id_user) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-8 py-3 rounded-full bg-[#6B630C] text-white font-bold shadow-lg flex items-center border-none outline-none cursor-pointer hover:opacity-90">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @if(strtolower($selectedUser->role) === 'donatur')
                                        Terima Donatur
                                    @else
                                        Terima Penerima
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="bg-white rounded-[40px] p-12 text-center text-gray-400 font-semibold shadow-sm flex flex-col justify-center items-center h-full">
                        <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h4 class="text-lg font-bold text-gray-700 mb-1">Semua Donatur Terverifikasi</h4>
                        <p class="text-sm font-medium text-gray-400 max-w-xs">Tidak ada antrian verifikasi donatur baru saat ini.</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </main>
</body>
</html>
