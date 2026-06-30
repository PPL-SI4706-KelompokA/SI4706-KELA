@auth
@php
    $user = auth()->user();
    $role = $user->role;
    
    // Calculate statistics based on role
    $stat1_val = 0; $stat1_lbl = 'Total Aktivitas'; $stat1_color_bg = 'bg-[#E8F5E9]'; $stat1_color_txt = 'text-green-700'; $stat1_emoji = '🟢';
    $stat2_val = 0; $stat2_lbl = 'Disetujui'; $stat2_color_bg = 'bg-[#FFF3E0]'; $stat2_color_txt = 'text-amber-700'; $stat2_emoji = '🟠';
    $stat3_val = 0; $stat3_lbl = 'Makanan'; $stat3_color_bg = 'bg-[#E3F2FD]'; $stat3_color_txt = 'text-blue-700'; $stat3_emoji = '🔵';
    $stat4_val = '5.0'; $stat4_lbl = 'Rating'; $stat4_color_bg = 'bg-[#F3E5F5]'; $stat4_color_txt = 'text-purple-700'; $stat4_emoji = '⭐';
    $showStars = false;

    if ($role === 'Penerima') {
        $stat1_lbl = 'Total Request';
        $stat1_val = \App\Models\permintaan::where('id_user', $user->id_user)->count();
        $stat1_emoji = '📅';

        $stat2_lbl = 'Request Diterima';
        $stat2_val = \App\Models\permintaan::where('id_user', $user->id_user)->where('status', 'Disetujui')->count();
        $stat2_emoji = '✅';

        $stat3_lbl = 'Total Makanan Diterima';
        $stat3_val = \App\Models\permintaan::where('id_user', $user->id_user)->where('status', 'Disetujui')->sum('jumlah_permintaan');
        $stat3_emoji = '📁';

        $stat4_lbl = 'Rating Donatur';
        $avgRating = \App\Models\rating::where('id_user', $user->id_user)->avg('nilai_rating');
        $stat4_val = $avgRating ? number_format($avgRating, 1) : '5.0';
        $showStars = true;
    } elseif ($role === 'Donatur') {
        $stat1_lbl = 'Total Donasi';
        $stat1_val = \App\Models\Donasi::where('id_user', $user->id_user)->count();
        $stat1_emoji = '🎁';

        $stat2_lbl = 'Donasi Diterima';
        $stat2_val = \App\Models\permintaan::whereHas('donasi', function($q) use ($user) {
            $q->where('id_user', $user->id_user);
        })->where('status', 'Disetujui')->count();
        $stat2_emoji = '🤝';

        $stat3_lbl = 'Porsi Dibagikan';
        $stat3_val = \App\Models\permintaan::whereHas('donasi', function($q) use ($user) {
            $q->where('id_user', $user->id_user);
        })->where('status', 'Disetujui')->sum('jumlah_permintaan');
        $stat3_emoji = '🍽️';

        $stat4_lbl = 'Rating Donatur';
        $avgRating = \App\Models\rating::whereHas('permintaan.donasi', function($q) use ($user) {
            $q->where('id_user', $user->id_user);
        })->avg('nilai_rating');
        $stat4_val = $avgRating ? number_format($avgRating, 1) : '5.0';
        $showStars = true;
    } else { // Admin
        $stat1_lbl = 'Pengumuman Dibuat';
        $stat1_val = \App\Models\Pemberitahuan::count();
        $stat1_emoji = '📢';

        $stat2_lbl = 'Verifikasi Baru';
        $stat2_val = \App\Models\User::where('status_verifikasi', 'Belum Verifikasi')->count();
        $stat2_emoji = '⚖️';

        $stat3_lbl = 'Total Pengguna';
        $stat3_val = \App\Models\User::count();
        $stat3_emoji = '👥';

        $stat4_lbl = 'Pesan Sistem';
        $stat4_val = \App\Models\Pemberitahuan::where('tipe', 'Maintenance')->count();
        $stat4_emoji = '⚙️';
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F8EC; color: #333; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    @if($role === 'Admin')
        <!-- ================= ADMIN SIDEBAR LAYOUT ================= -->
        <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between py-6 shrink-0 h-screen">
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
            <div class="px-8">
                <button class="w-full bg-[#FCD34D] text-[#6B630C] font-bold py-3 rounded-full mb-8 shadow-sm">Export Data</button>
                <div class="space-y-4">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center text-gray-400 hover:text-gray-600 text-sm font-semibold">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Sign Out
                    </a>
                </div>
            </div>
        </aside>

        <!-- Admin Content Area -->
        <main class="flex-grow flex flex-col h-screen overflow-y-auto">
            <!-- Admin Topbar -->
            <header class="flex items-center justify-between px-10 py-6 shrink-0 bg-transparent">
                <div class="relative w-96">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" placeholder="Search user..." class="w-full bg-white rounded-full py-3 pl-12 pr-6 text-sm font-medium border-none shadow-sm focus:ring-2 focus:ring-[#FCD34D] outline-none">
                </div>
                <div class="flex items-center space-x-8">
                    <div class="flex space-x-6 text-sm font-bold text-gray-500">
                        <a href="{{ route('admin.statistik') }}" class="hover:text-[#6B630C]">Dashboard</a>
                        <a href="#" class="text-[#6B630C] border-b-2 border-[#6B630C] pb-1">Profile</a>
                    </div>
                    <div class="flex items-center space-x-4 text-gray-400">
                        <x-admin-notifications />
                        <a href="{{ route('profile.show') }}">
                            <img src="{{ auth()->user()->foto_url ?: 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=F97316&color=fff' }}" class="w-8 h-8 rounded-full border-2 border-white shadow-sm hover:opacity-90 transition">
                        </a>
                    </div>
                </div>
            </header>
            
            <div class="px-10 py-4 flex-1 flex flex-col justify-center items-center">
    @else
        <!-- ================= REGULAR USER LAYOUT ================= -->
        <main class="flex-grow flex flex-col h-screen overflow-y-auto w-full">
            <!-- Navbar -->
            <nav class="w-full py-6 px-12 flex items-center justify-between shrink-0">
                <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]"><a href="{{ route('donasi.daftar') }}">FoodShare</a></div>
                <div class="hidden md:flex space-x-8 font-semibold text-sm">
                    <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
                    <a href="{{ route('donasi.cari') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Donasi</a>
                </div>
                <x-navbar-icons />
            </nav>

            <div class="px-12 py-10 flex-1 flex flex-col justify-center items-center w-full max-w-5xl mx-auto">
    @endif

                <!-- ================= CORE USER PROFILE CARD CONTENT ================= -->
                @if(session('success'))
                    <div class="w-full max-w-2xl bg-green-100 text-green-700 p-4 rounded-2xl mb-6 text-sm font-semibold border border-green-200 shadow-sm transition animate-fade-in">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Profile Card Container -->
                <div class="w-full max-w-2xl bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 transition duration-300 relative overflow-hidden mb-6">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#F8F8EC] rounded-full blur-3xl opacity-60"></div>
                    
                    <!-- Form Container (Wrapper for View & Edit States) -->
                    <form action="{{ route('profile.update') }}" method="POST" id="profile-edit-form" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- VIEW STATE PANEL -->
                        <div id="view-panel" class="space-y-8">
                            <div class="flex items-center gap-6 text-left flex-col sm:flex-row">
                                <div class="relative w-28 h-28 shrink-0">
                                    <img src="{{ $user->foto_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=FCD34D&color=6B630C&size=128' }}" class="w-full h-full rounded-full object-cover border-4 border-[#FCD34D]/20 shadow-md">
                                    <div class="absolute -bottom-1 -right-1 w-9 h-9 bg-white rounded-full flex items-center justify-center shadow-md border border-gray-100 hover:bg-gray-50 cursor-pointer" onclick="toggleEditMode()">
                                        <span class="text-sm">📷</span>
                                    </div>
                                </div>
                                <div class="flex-grow text-center sm:text-left">
                                    <div class="flex items-center gap-3 mb-2 flex-wrap justify-center sm:justify-start">
                                        <h4 class="text-2xl font-extrabold text-gray-800 tracking-tight">{{ $user->nama }}</h4>
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-green-100 text-green-800 border border-green-200 uppercase tracking-wider">
                                            {{ $user->role }} Aktif
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400 font-semibold flex items-center gap-1.5 justify-center sm:justify-start">
                                        <span>📅</span> Bergabung sejak {{ $user->created_at ? $user->created_at->format('M Y') : 'Baru' }}
                                    </p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2 shrink-0 justify-center w-full sm:w-auto mt-4 sm:mt-0">
                                    <button type="button" onclick="toggleEditMode()" class="bg-[#F8F8EC] hover:bg-gray-100 text-[#6B630C] font-bold py-2 px-6 rounded-full text-xs shadow-sm border border-gray-200/50 transition text-center block">
                                        Edit Profil
                                    </button>
                                    @if(auth()->user()->role !== 'Admin' && auth()->user()->role !== 'admin')
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-regular').submit();" class="text-center bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-6 rounded-full text-xs shadow-sm border border-red-200/50 transition block">
                                            Keluar
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- User info list -->
                            <div class="space-y-4 border-t border-gray-50 pt-6">
                                <!-- Alamat -->
                                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                    <div class="flex items-center gap-3">
                                        <span class="text-base text-gray-400">📍</span>
                                        <span class="text-sm font-bold text-gray-700">{{ $user->alamat ?: 'Belum ditentukan' }}</span>
                                    </div>
                                    <button type="button" onclick="toggleEditMode()" class="p-1.5 hover:bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition border-none cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                </div>

                                <!-- Telepon -->
                                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                    <div class="flex items-center gap-3">
                                        <span class="text-base text-gray-400">📞</span>
                                        <span class="text-sm font-bold text-gray-700">{{ $user->no_telp ?: 'Belum ditentukan' }}</span>
                                    </div>
                                    <button type="button" onclick="toggleEditMode()" class="p-1.5 hover:bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition border-none cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                </div>

                                <!-- Email -->
                                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                    <div class="flex items-center gap-3">
                                        <span class="text-base text-gray-400">✉️</span>
                                        <span class="text-sm font-bold text-gray-700">{{ $user->email }}</span>
                                    </div>
                                    <button type="button" onclick="toggleEditMode()" class="p-1.5 hover:bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition border-none cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- EDIT STATE PANEL (HIDDEN BY DEFAULT) -->
                        <div id="edit-panel" class="space-y-6 hidden">
                            <h4 class="text-lg font-bold text-gray-800">Edit Profil Anda</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                                    <input type="text" name="nama" value="{{ $user->nama }}" required
                                           class="w-full bg-[#F8F8EC] rounded-2xl px-5 py-3 text-sm font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nomor Telepon</label>
                                    <input type="text" name="no_telp" value="{{ $user->no_telp }}" placeholder="Masukkan No Telp..."
                                           class="w-full bg-[#F8F8EC] rounded-2xl px-5 py-3 text-sm font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Alamat Lengkap</label>
                                    <input type="text" name="alamat" value="{{ $user->alamat }}" placeholder="Masukkan Alamat..."
                                           class="w-full bg-[#F8F8EC] rounded-2xl px-5 py-3 text-sm font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Alamat Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}" required
                                           class="w-full bg-[#F8F8EC] rounded-2xl px-5 py-3 text-sm font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Foto Profil</label>
                                    <input type="file" name="foto_profil" accept="image/*"
                                           class="w-full bg-[#F8F8EC] rounded-2xl px-5 py-3 text-sm font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none">
                                </div>
                            </div>

                            <div class="flex items-center gap-3 justify-end pt-4 border-t border-gray-50">
                                <button type="button" onclick="toggleViewMode()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 px-6 rounded-full text-xs transition">
                                    Batal
                                </button>
                                <button type="submit" class="bg-[#6B630C] hover:opacity-95 text-white font-bold py-3 px-8 rounded-full text-xs shadow-md transition">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                    @if(auth()->user()->role !== 'Admin' && auth()->user()->role !== 'admin')
                        <form id="logout-form-regular" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endif
                </div>

                <!-- Aktivitas Statistik Container -->
                <div class="w-full max-w-2xl bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 text-left">
                    <h3 class="text-lg font-bold text-gray-800 tracking-tight mb-6">Statistik Aktivitas</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <!-- Stat Card 1 -->
                        <div class="{{ $stat1_color_bg }} rounded-2xl p-4 flex flex-col justify-between min-h-[120px] transition hover:shadow-sm">
                            <span class="text-xl mb-2">{{ $stat1_emoji }}</span>
                            <div>
                                <span class="text-3xl font-extrabold {{ $stat1_color_txt }} block mb-0.5">{{ $stat1_val }}</span>
                                <span class="text-[9px] font-extrabold text-gray-400 uppercase tracking-wider block leading-snug">{{ $stat1_lbl }}</span>
                            </div>
                        </div>

                        <!-- Stat Card 2 -->
                        <div class="{{ $stat2_color_bg }} rounded-2xl p-4 flex flex-col justify-between min-h-[120px] transition hover:shadow-sm">
                            <span class="text-xl mb-2">{{ $stat2_emoji }}</span>
                            <div>
                                <span class="text-3xl font-extrabold {{ $stat2_color_txt }} block mb-0.5">{{ $stat2_val }}</span>
                                <span class="text-[9px] font-extrabold text-gray-400 uppercase tracking-wider block leading-snug">{{ $stat2_lbl }}</span>
                            </div>
                        </div>

                        <!-- Stat Card 3 -->
                        <div class="{{ $stat3_color_bg }} rounded-2xl p-4 flex flex-col justify-between min-h-[120px] transition hover:shadow-sm">
                            <span class="text-xl mb-2">{{ $stat3_emoji }}</span>
                            <div>
                                <span class="text-3xl font-extrabold {{ $stat3_color_txt }} block mb-0.5">{{ $stat3_val }}</span>
                                <span class="text-[9px] font-extrabold text-gray-400 uppercase tracking-wider block leading-snug">{{ $stat3_lbl }}</span>
                            </div>
                        </div>

                        <!-- Stat Card 4 -->
                        <div class="{{ $stat4_color_bg }} rounded-2xl p-4 flex flex-col justify-between min-h-[120px] transition hover:shadow-sm">
                            <span class="text-xl mb-2">{{ $stat4_emoji }}</span>
                            <div>
                                <span class="text-3xl font-extrabold {{ $stat4_color_txt }} block mb-0.5">{{ $stat4_val }}</span>
                                <span class="text-[9px] font-extrabold text-gray-400 uppercase tracking-wider block leading-snug">{{ $stat4_lbl }}</span>
                                @if($showStars)
                                    <div class="flex items-center gap-0.5 text-amber-400 mt-1">
                                        <span class="text-[10px]">⭐</span><span class="text-[10px]">⭐</span><span class="text-[10px]">⭐</span><span class="text-[10px]">⭐</span><span class="text-[10px]">⭐</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

    @if($role === 'Admin')
            </div>
        </main>
    @else
            </div>
            <!-- Footer -->
            <footer class="w-full bg-[#EFF0E0] py-6 px-12 flex flex-col md:flex-row items-center justify-between text-xs text-gray-400 shrink-0 border-t border-[#E4E5C8]">
                <div>© 2026 FoodShare</div>
                <div class="flex space-x-6 mt-4 md:mt-0 uppercase tracking-widest text-[10px] font-bold">
                    <a href="#" class="hover:text-gray-700">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-gray-700">Ketentuan Layanan</a>
                    <a href="#" class="hover:text-gray-700">Kontak</a>
                </div>
            </footer>
        </main>
    @endif

    <script>
        function toggleEditMode() {
            document.getElementById('view-panel').classList.add('hidden');
            document.getElementById('edit-panel').classList.remove('hidden');
        }

        function toggleViewMode() {
            document.getElementById('edit-panel').classList.add('hidden');
            document.getElementById('view-panel').classList.remove('hidden');
        }
    </script>
</body>
</html>
@endauth
