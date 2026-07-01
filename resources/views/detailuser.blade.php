@auth
@php
    $user = auth()->user();
    $role = $user->role;
    
    // Konfigurasi Statis & Warna Dinamis sesuai Role
    $stat1_val = 0; $stat1_lbl = 'Total Aktivitas'; $stat1_emoji = '🗓️'; $stat1_bg = 'from-amber-50 to-orange-50/50'; $stat1_border = 'border-amber-100/70'; $stat1_icon_bg = 'bg-amber-500/10'; $stat1_txt = 'text-amber-600';
    $stat2_val = 0; $stat2_lbl = 'Disetujui'; $stat2_emoji = '✨'; $stat2_bg = 'from-emerald-50 to-teal-50/50'; $stat2_border = 'border-emerald-100/70'; $stat2_icon_bg = 'bg-emerald-500/10'; $stat2_txt = 'text-emerald-600';
    $stat3_val = 0; $stat3_lbl = 'Makanan'; $stat3_emoji = '📦'; $stat3_bg = 'from-green-50 to-emerald-50/50'; $stat3_border = 'border-green-100/70'; $stat3_icon_bg = 'bg-green-500/10'; $stat3_txt = 'text-green-600';
    $stat4_val = '5.0'; $stat4_lbl = 'Rating'; $stat4_emoji = '⭐'; $stat4_bg = 'from-purple-50 to-indigo-50/50'; $stat4_border = 'border-purple-100/70'; $stat4_icon_bg = 'bg-purple-500/10'; $stat4_txt = 'text-purple-600';

    if ($role === 'Penerima') {
        $stat1_lbl = 'Total Request';
        $stat1_val = \App\Models\permintaan::where('id_user', $user->id_user)->count();
        $stat1_emoji = '📅';

        $stat2_lbl = 'Request Diterima';
        $stat2_val = \App\Models\permintaan::where('id_user', $user->id_user)->where('status', 'Disetujui')->count();
        $stat2_emoji = '✅';

        $stat3_lbl = 'Total Makanan (Porsi)';
        $stat3_val = \App\Models\permintaan::where('id_user', $user->id_user)->where('status', 'Disetujui')->sum('jumlah_permintaan');
        $stat3_emoji = '🥗';

        $stat4_lbl = 'Rating Donatur';
        $avgRating = \App\Models\rating::where('id_user', $user->id_user)->avg('nilai_rating');
        $stat4_val = $avgRating ? number_format($avgRating, 1) : '5.0';
    } elseif ($role === 'Donatur') {
        $stat1_lbl = 'Total Donasi';
        $stat1_val = \App\Models\Donasi::where('id_user', $user->id_user)->count();
        $stat1_emoji = '🎁';

        $stat2_lbl = 'Donasi Diterima';
        $stat2_val = \App\Models\permintaan::whereHas('donasi', function($q) use ($user) { $q->where('id_user', $user->id_user); })->where('status', 'Disetujui')->count();
        $stat2_emoji = '🤝';

        $stat3_lbl = 'Porsi Dibagikan';
        $stat3_val = \App\Models\permintaan::whereHas('donasi', function($q) use ($user) { $q->where('id_user', $user->id_user); })->where('status', 'Disetujui')->sum('jumlah_permintaan');
        $stat3_emoji = '🍽️';

        $stat4_lbl = 'Rating Anda';
        $avgRating = \App\Models\rating::whereHas('permintaan.donasi', function($q) use ($user) { $q->where('id_user', $user->id_user); })->avg('nilai_rating');
        $stat4_val = $avgRating ? number_format($avgRating, 1) : '5.0';
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFAf5; color: #1E293B; }
        .hero-pattern {
            background-color: #fef08a;
            background-image: radial-gradient(#facc15 0.85px, transparent 0.85px), radial-gradient(#facc15 0.85px, #fef08a 0.85px);
            background-size: 24px 24px;
            background-position: 0 0, 12px 12px;
            opacity: 0.15;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between antialiased overflow-x-hidden">

    <nav class="w-full bg-white/80 backdrop-blur-md border-b border-gray-100 py-4 px-6 md:px-12 flex items-center justify-between sticky top-0 z-50 shadow-sm transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#6B630C] rounded-2xl flex items-center justify-center font-black text-white text-xl shadow-md shadow-[#6B630C]/20 transform hover:rotate-6 transition">F</div>
            <div class="text-xl font-black tracking-tight text-[#6B630C]"><a href="{{ route('donasi.daftar') }}" class="hover:opacity-90">FoodShare</a></div>
        </div>
        <div class="hidden md:flex space-x-8 font-semibold text-sm">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#6B630C] transition relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-[#6B630C] after:left-0 after:-bottom-1 hover:after:w-full after:transition-all">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-gray-500 hover:text-[#6B630C] transition relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-[#6B630C] after:left-0 after:-bottom-1 hover:after:w-full after:transition-all">Donasi</a>
            <a href="#" class="text-gray-500 hover:text-[#6B630C] transition relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-[#6B630C] after:left-0 after:-bottom-1 hover:after:w-full after:transition-all">Pesan</a>
        </div>
        
        <div class="flex items-center gap-4">
            <x-navbar-icons />
            <div class="relative">
                <div onclick="toggleDropdown()" class="w-9 h-9 bg-gradient-to-tr from-amber-400 to-yellow-300 rounded-full flex items-center justify-center font-bold text-xs text-[#6B630C] border-2 border-white shadow-md cursor-pointer">
                    {{ strtoupper(substr($user->nama, 0, 2)) }}
                </div>
                <div id="userDropdown" class="hidden absolute right-0 top-12 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        👤 Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 md:px-8 pt-6 pb-2 space-y-6">
        
        @if(session('success'))
            <div class="w-full bg-emerald-50 text-emerald-800 p-4 rounded-2xl text-sm font-semibold border border-emerald-200 shadow-sm flex items-center gap-3 animate-fade-in">
                <span class="text-lg">🎉</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="w-full bg-gradient-to-r from-amber-200/60 via-amber-100/40 to-orange-100/50 rounded-[36px] p-6 md:p-8 shadow-sm border border-amber-200/30 relative overflow-hidden group">
            <div class="absolute inset-0 hero-pattern pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                        <div class="relative shrink-0">
                            <img
                                src="{{ $user->foto_profil ? asset('storage/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=6B630C&color=FFF&size=128' }}"
                                class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-xl">
                            <div class="absolute bottom-0 right-0 w-7 h-7 bg-yellow-400 rounded-full border-2 border-white flex items-center justify-center text-xs">
                                ✨
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-center sm:justify-start gap-3 flex-wrap">
                                <h2 class="text-3xl font-black text-slate-800">
                                    {{ $user->nama }}
                                </h2>
                                <span class="px-3 py-1 bg-amber-400 text-white rounded-full text-xs font-bold">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </div>

                            <p class="text-slate-500 mt-2">
                                ✉️ {{ $user->email }}
                            </p>

                            <p class="text-slate-500 mt-2 text-sm">
                                📅 Bergabung sejak
                                <span class="font-bold">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-center lg:justify-end">
                        <button
                            type="button"
                            onclick="toggleEditMode()"
                            class="w-full sm:w-auto bg-[#6B630C] hover:bg-[#565008] text-white px-8 py-3 rounded-xl font-bold shadow">
                            ✏️ Edit Profil
                        </button>
                    </div>
                </div>
            </div>
        </div> 

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-7 bg-white rounded-[32px] p-6 md:p-8 shadow-md shadow-slate-100/50 border border-slate-100 transition-all duration-300">
                
                <div id="view-panel" class="space-y-6 transition-all duration-300">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                            <span class="p-2 bg-amber-50 rounded-xl text-lg">👤</span> Informasi Akun
                        </h3>
                        <span class="text-[10px] font-extrabold text-slate-400 tracking-wider uppercase bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">Secure Cloud Data</span>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50/70 to-orange-50/40 border border-amber-100/70 rounded-[24px] p-4 space-y-1">
                        
                        <div class="flex items-center justify-between py-3.5 group hover:bg-white px-4 rounded-2xl transition duration-200">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-white text-slate-500 flex items-center justify-center text-sm shadow-sm group-hover:bg-amber-50 transition">👤</div>
                                <span class="text-slate-500 font-bold text-sm">Nama Lengkap</span>
                            </div>
                            <span class="font-extrabold text-slate-800 text-sm tracking-tight">{{ $user->nama }}</span>
                        </div>

                        <div class="flex items-center justify-between py-3.5 group hover:bg-white px-4 rounded-2xl transition duration-200">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-white text-slate-500 flex items-center justify-center text-sm shadow-sm group-hover:bg-amber-50 transition">✉️</div>
                                <span class="text-slate-500 font-bold text-sm">Alamat Email</span>
                            </div>
                            <span class="font-extrabold text-slate-800 text-sm tracking-tight">{{ $user->email }}</span>
                        </div>

                        <div class="flex items-center justify-between py-3.5 group hover:bg-white px-4 rounded-2xl transition duration-200">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-white text-slate-500 flex items-center justify-center text-sm shadow-sm group-hover:bg-amber-50 transition">📞</div>
                                <span class="text-slate-500 font-bold text-sm">Nomor Telepon</span>
                            </div>
                            <span class="font-extrabold text-slate-800 text-sm tracking-tight">{{ $user->no_telp ?: '08123456789' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-3.5 group hover:bg-white px-4 rounded-2xl transition duration-200">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-white text-slate-500 flex items-center justify-center text-sm shadow-sm group-hover:bg-amber-50 transition">📍</div>
                                <span class="text-slate-500 font-bold text-sm">Alamat Domisili</span>
                            </div>
                            <span class="font-extrabold text-slate-800 text-sm tracking-tight max-w-[240px] text-right truncate">{{ $user->alamat ?: 'korea' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-3.5 group hover:bg-white px-4 rounded-2xl transition duration-200">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-white text-slate-500 flex items-center justify-center text-sm shadow-sm group-hover:bg-amber-50 transition">🛡️</div>
                                <span class="text-slate-500 font-bold text-sm">Hak Akses Peran</span>
                            </div>
                            <span class="font-extrabold text-xs uppercase bg-amber-100 text-amber-900 px-3 py-1 rounded-xl shadow-sm border border-amber-200/30">{{ $user->role ?: 'ADMIN' }}</span>
                        </div>

                    </div>
                </div>

                <div id="edit-panel" class="hidden space-y-6 opacity-0 transform translate-y-2 transition-all duration-300">
                    <div class="pb-4 border-b border-slate-100">
                        <h3 class="text-xl font-black text-slate-800">Perbarui Profil Anda</h3>
                        <p class="text-xs font-semibold text-slate-400 mt-0.5">Pastikan data yang Anda masukkan terdaftar dan valid.</p>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-sm">
                        @csrf
                        
                        <div class="group">
                            <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">
                                Ganti Foto Profil
                            </label>

                            <div class="flex items-center gap-2">
                                <input type="file"
                                       name="foto_profil"
                                       id="foto_profil"
                                       accept="image/*"
                                       class="flex-1 bg-slate-50 border border-slate-200 rounded-2xl p-2.5 text-xs font-semibold text-slate-500">

                                <button
                                    type="button"
                                    id="btnHapusFoto"
                                    onclick="hapusFotoProfil()"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-bold">
                                    🗑 Hapus Foto
                                </button>
                            </div>

                            <input type="hidden" name="hapus_foto" id="hapus_foto" value="0">

                            <p id="statusHapusFoto"
                               class="hidden mt-2 text-xs font-semibold text-red-600">
                               Foto akan dihapus setelah menekan Simpan Perubahan.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" value="{{ $user->nama }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/20 focus:bg-white focus:border-amber-500/40 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Nomor Telepon</label>
                                <input type="text" name="no_telp" value="{{ $user->no_telp }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/20 focus:bg-white focus:border-amber-500/40 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                            <input type="text" name="alamat" value="{{ $user->alamat }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/20 focus:bg-white focus:border-amber-500/40 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/20 focus:bg-white focus:border-amber-500/40 transition-all">
                        </div>
                        <div class="flex gap-3 justify-end pt-4 border-t border-slate-50">
                            <button type="button" onclick="toggleViewMode()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold py-3 px-6 rounded-2xl text-xs transition duration-200">Batal</button>
                            <button type="submit" class="bg-[#6B630C] hover:opacity-95 text-white font-extrabold py-3 px-7 rounded-2xl text-xs shadow-md shadow-[#6B630C]/20 transition duration-200">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-6 flex flex-col justify-between self-stretch">
                
                @if(strtoupper($user->role) !== 'ADMIN')
                    <div class="bg-white rounded-[32px] p-6 shadow-md shadow-slate-100/50 border border-slate-100 flex-1">
                        <h3 class="text-lg font-black text-slate-800 mb-5 flex items-center gap-2">
                            <span class="p-1.5 bg-orange-50 rounded-lg text-base">📊</span> Ringkasan Aktivitas
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br {{ $stat1_bg }} border {{ $stat1_border }} rounded-[24px] p-4 flex flex-col justify-between relative overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                <div class="flex justify-between items-start">
                                    <span class="text-xl p-1.5 bg-white/70 backdrop-blur-sm rounded-xl shadow-sm">{{ $stat1_emoji }}</span>
                                    <div class="w-2.5 h-2.5 rounded-full {{ $stat1_icon_bg }} ring-4 ring-white/40"></div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $stat1_val }}</div>
                                    <div class="text-[9px] font-extrabold text-slate-400 mt-1 uppercase tracking-widest leading-tight">{{ $stat1_lbl }}</div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br {{ $stat2_bg }} border {{ $stat2_border }} rounded-[24px] p-4 flex flex-col justify-between relative overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                <div class="flex justify-between items-start">
                                    <span class="text-xl p-1.5 bg-white/70 backdrop-blur-sm rounded-xl shadow-sm">{{ $stat2_emoji }}</span>
                                    <div class="w-2.5 h-2.5 rounded-full {{ $stat2_icon_bg }} ring-4 ring-white/40"></div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $stat2_val }}</div>
                                    <div class="text-[9px] font-extrabold text-slate-400 mt-1 uppercase tracking-widest leading-tight">{{ $stat2_lbl }}</div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br {{ $stat3_bg }} border {{ $stat3_border }} rounded-[24px] p-4 flex flex-col justify-between relative overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                <div class="flex justify-between items-start">
                                    <span class="text-xl p-1.5 bg-white/70 backdrop-blur-sm rounded-xl shadow-sm">{{ $stat3_emoji }}</span>
                                    <div class="w-2.5 h-2.5 rounded-full {{ $stat3_icon_bg }} ring-4 ring-white/40"></div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $stat3_val }}</div>
                                    <div class="text-[9px] font-extrabold text-slate-400 mt-1 uppercase tracking-widest leading-tight">{{ $stat3_lbl }}</div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br {{ $stat4_bg }} border {{ $stat4_border }} rounded-[24px] p-4 flex flex-col justify-between relative overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                <div class="flex justify-between items-start">
                                    <span class="text-xl p-1.5 bg-white/70 backdrop-blur-sm rounded-xl shadow-sm">{{ $stat4_emoji }}</span>
                                    <div class="w-2.5 h-2.5 rounded-full {{ $stat4_icon_bg }} ring-4 ring-white/40"></div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-3xl font-black text-slate-800 tracking-tight flex items-baseline gap-1">
                                        {{ $stat4_val }} <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-normal">Avg</span>
                                    </div>
                                    <div class="text-[9px] font-extrabold text-slate-400 mt-1 uppercase tracking-widest leading-tight">{{ $stat4_lbl }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-gradient-to-br from-[#FFF8E1] to-[#FFFDF5] rounded-[30px] p-6 border border-[#F3E5AB]/60 flex flex-col sm:flex-row items-center gap-5 relative overflow-hidden shadow-sm mt-6">
                    <div class="space-y-1.5 flex-1 relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="text-base">❤️</span>
                            <h4 class="font-black text-base text-[#6B630C] tracking-tight">Gerakan Berbagi Kebaikan</h4>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed font-semibold">
                            Terima kasih telah bergabung di ekosistem ini. Bersama kita kurangi timbulan sampah makanan (<span class="italic font-bold text-amber-800">food waste</span>) demi masa depan lingkungan yang lebih baik.
                        </p>
                    </div>
                    
                    <div class="w-14 h-14 shrink-0 bg-white rounded-2xl border border-amber-200/50 flex items-center justify-center shadow-sm relative z-10 transform hover:scale-110 transition duration-300">
                        <span class="text-2xl">🍔</span>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="w-full bg-[#ECEEDD] py-5 px-6 md:px-12 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 border-t border-[#DFE2C4] shrink-0 mt-2">
        <div class="font-medium">© 2026 <span class="font-extrabold text-slate-500">FoodShare</span>. Hak Cipta Dilindungi.</div>
        <div class="flex space-x-5 mt-3 sm:mt-0 uppercase tracking-wider text-[10px] font-black">
            <a href="#" class="hover:text-slate-600 transition">Kebijakan Privasi</a>
            <a href="#" class="hover:text-slate-600 transition">Ketentuan Layanan</a>
            <a href="#" class="hover:text-slate-600 transition">Kontak</a>
        </div>
    </footer>

    <script>
        function toggleEditMode() {
            const viewPanel = document.getElementById('view-panel');
            const editPanel = document.getElementById('edit-panel');
            
            viewPanel.classList.add('hidden');
            editPanel.classList.remove('hidden');
            
            setTimeout(() => {
                editPanel.classList.remove('opacity-0', 'translate-y-2');
                editPanel.classList.add('opacity-100', 'translate-y-0');
            }, 50);
        }
        
        function toggleViewMode() {
            const viewPanel = document.getElementById('view-panel');
            const editPanel = document.getElementById('edit-panel');
            
            editPanel.classList.add('opacity-0', 'translate-y-2');
            editPanel.classList.remove('opacity-100', 'translate-y-0');
            
            setTimeout(() => {
                editPanel.classList.add('hidden');
                viewPanel.classList.remove('hidden');
            }, 200);
        }
    </script>

    <script>
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            if (!event.target.closest('.relative')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <script>
    function hapusFotoProfil() {

        if(confirm('Yakin ingin menghapus foto profil?')) {

            document.getElementById('hapus_foto').value = '1';

            document.getElementById('statusHapusFoto')
                .classList.remove('hidden');

            document.getElementById('btnHapusFoto')
                .innerHTML = '✓ Akan Dihapus';

            document.getElementById('btnHapusFoto')
                .classList.remove('bg-red-500');

            document.getElementById('btnHapusFoto')
                .classList.add('bg-green-600');
        }
    }
    </script>
</body>
</html>
@endauth