<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare Admin - Laporan Distribusi Makanan</title>
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
                <a href="{{ route('admin.laporan') }}" class="flex items-center px-8 py-3 bg-[#FCF8E3] text-[#6B630C] border-r-4 border-[#FCD34D] font-bold text-sm">
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
        <div class="px-8 pb-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center text-gray-400 hover:text-red-500 font-semibold text-sm transition-colors w-full">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">

        <!-- Topbar -->
        <header class="flex items-center justify-between px-10 py-6 shrink-0">
            <div class="relative w-96">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search user by name or email..." class="w-full bg-white rounded-full py-3 pl-12 pr-6 text-sm font-medium border-none shadow-sm focus:ring-2 focus:ring-[#FCD34D] outline-none">
            </div>
            <div class="flex items-center space-x-4 text-gray-400">
                <x-admin-notifications />
                <button><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>
                <a href="{{ route('profile.show') }}">
                    <img src="{{ auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama ?? 'Admin') . '&background=F97316&color=fff' }}" class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm hover:opacity-90 transition">
                </a>
            </div>
        </header>

        <!-- Content -->
        <div class="px-10 py-2 flex-1">

            <!-- Page Header Row -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <p class="text-[10px] font-bold text-[#6B630C] tracking-widest uppercase mb-2">Administrative Overview</p>
                    <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight leading-tight mb-2">Laporan Distribusi<br>Makanan</h2>
                    <p class="text-gray-400 text-sm font-medium">Monitoring and managing community food distribution efforts across the network.</p>
                </div>

                <!-- Date Filter + PDF Card -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 flex items-end gap-5 shrink-0">
                    <div class="flex flex-col">
                        <label for="start_date" class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-2">Tanggal Mulai</label>
                        <input type="date" id="start_date" class="bg-[#F8F8F8] rounded-xl px-4 py-2.5 text-xs font-semibold border-none focus:ring-2 focus:ring-[#FCD34D] outline-none text-gray-600 w-38">
                    </div>
                    <div class="flex flex-col">
                        <label for="end_date" class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-2">Tanggal Selesai</label>
                        <input type="date" id="end_date" class="bg-[#F8F8F8] rounded-xl px-4 py-2.5 text-xs font-semibold border-none focus:ring-2 focus:ring-[#FCD34D] outline-none text-gray-600 w-38">
                    </div>
                    <button onclick="downloadPdf()" class="bg-[#FCD34D] text-[#6B630C] px-6 py-2.5 rounded-full text-xs font-bold shadow-sm flex items-center gap-2 hover:bg-[#fbc316] transition whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh PDF
                    </button>
                </div>
            </div>

            <!-- Main Grid: Total Penyaluran + Distribusi Terbaru -->
            <div class="grid grid-cols-5 gap-8">

                <!-- Total Penyaluran Card -->
                <div class="col-span-2 bg-white rounded-[32px] p-8 relative overflow-hidden shadow-sm flex flex-col">
                    <!-- Icon + Label -->
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-[#E8EBC8] rounded-xl flex items-center justify-center text-[#6B630C]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="font-bold text-[#6B630C] text-sm tracking-wide">Total Penyaluran</span>
                    </div>

                    <!-- Big Number -->
                    <h3 class="text-8xl font-extrabold text-gray-800 leading-none mb-5">{{ number_format($totalPenyaluran, 0, ',', '.') }}</h3>

                    <!-- Description -->
                    <p class="text-sm text-gray-500 font-medium leading-relaxed mb-6 max-w-[200px]">Porsi makanan bergizi telah didistribusikan ke komunitas dalam 30 hari terakhir.</p>

                    <!-- Growth Badge -->
                    <span class="inline-flex items-center px-4 py-2 bg-[#F0F4E8] text-[#6B630C] rounded-full text-xs font-bold w-fit">
                        <span class="w-2 h-2 bg-[#6B630C] rounded-full mr-2"></span>
                        {{ $growthText }} dari bulan lalu
                    </span>

                    <!-- Illustration -->
                    <div class="absolute bottom-0 right-0 w-44 h-44 overflow-hidden rounded-tl-[40px] rounded-br-[32px]">
                        <img src="https://images.unsplash.com/photo-1593113630400-ea4288922559?w=400&h=400&fit=crop&q=80"
                             class="w-full h-full object-cover opacity-90"
                             onerror="this.src='https://images.unsplash.com/photo-1607877742558-6d50bf357cf6?w=400&h=400&fit=crop'">
                    </div>
                </div>

                <!-- Distribusi Terbaru -->
                <div class="col-span-3 flex flex-col">
                    <h3 class="text-xl font-bold text-gray-800 mb-5">Distribusi Terbaru</h3>
                    <div class="space-y-3 flex-1">
                        @forelse($distribusiTerbaru as $index => $item)
                        @php
                            $borderColors = ['border-[#FCD34D]', 'border-[#EAEBCA]', 'border-gray-100', 'border-gray-100', 'border-gray-100'];
                            $borderColor = $borderColors[$index] ?? 'border-gray-100';
                            $nameParts = explode(' ', trim($item->user->nama ?? 'Penerima'));
                            $initials = count($nameParts) >= 2
                                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                : strtoupper(substr($item->user->nama ?? 'PR', 0, 2));
                            $avatarColors = ['#8DB33A', '#FCD34D', '#E8934A', '#4A90D9', '#B05EA3'];
                            $avatarBg = $avatarColors[$index % count($avatarColors)];
                            $textColor = ($avatarBg === '#FCD34D') ? '#6B630C' : 'white';
                        @endphp
                        <div class="bg-white rounded-[20px] p-4 px-6 flex items-center shadow-sm border-l-4 {{ $borderColor }}">
                            <div class="w-11 h-11 rounded-full flex items-center justify-center mr-4 text-xs font-extrabold shrink-0"
                                 style="background-color: {{ $avatarBg }}; color: {{ $textColor }};">
                                {{ $initials }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">{{ $item->user->nama ?? 'Penerima' }}</h4>
                                <p class="text-xs font-medium text-gray-400 mt-0.5">
                                    {{ $item->donasi->nama_makanan ?? 'Makanan' }} •
                                    <span class="realtime-time" data-timestamp="{{ $item->created_at->toIso8601String() }}">{{ $item->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <h4 class="font-extrabold text-2xl text-gray-800">{{ $item->jumlah_permintaan }}</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Porsi</p>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white rounded-[20px] p-10 text-center text-gray-400 font-semibold text-sm shadow-sm flex-1 flex items-center justify-center">
                            Belum ada riwayat distribusi penyaluran makanan.
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        function downloadPdf() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            let url = "{{ route('admin.laporan.print') }}";
            const params = [];
            if (startDate) params.push('start_date=' + startDate);
            if (endDate) params.push('end_date=' + endDate);
            if (params.length > 0) {
                url += '?' + params.join('&');
            }
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
