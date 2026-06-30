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
        <div class="px-10 py-4 flex-1">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <div class="flex items-center text-[10px] font-bold text-[#6B630C] tracking-widest uppercase mb-1">ADMINISTRATIVE OVERVIEW</div>
                    <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight leading-tight mb-2">Laporan Distribusi<br>Makanan</h2>
                    <p class="text-gray-500 text-sm font-medium">Monitoring and managing community food distribution efforts across the network.</p>
                </div>
                <form action="{{ route('admin.laporan.export-pdf') }}" method="GET" class="bg-white p-4 px-6 rounded-[24px] border border-gray-100 shadow-sm flex items-end gap-4 shrink-0">
                    <div class="flex flex-col">
                        <label for="start_date" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="bg-[#F8F8EC] rounded-xl px-4 py-2 text-xs font-semibold text-gray-700 border border-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] cursor-pointer">
                    </div>
                    <div class="flex flex-col">
                        <label for="end_date" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" class="bg-[#F8F8EC] rounded-xl px-4 py-2 text-xs font-semibold text-gray-700 border border-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] cursor-pointer">
                    </div>
                    <button type="submit" class="bg-[#FCD34D] hover:bg-[#fbc316] text-[#6B630C] font-bold px-6 py-2 rounded-xl transition duration-200 shadow-sm flex items-center h-[34px] border-none outline-none gap-2 text-xs shrink-0 mb-[1px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh PDF
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-12 gap-8 mb-8">
                <!-- Total Penyaluran Card -->
                <div class="col-span-6 bg-white rounded-[40px] p-10 relative overflow-hidden shadow-sm flex flex-col justify-center">
                    <div class="absolute right-0 bottom-0 w-1/2 h-full bg-gradient-to-l from-[#F8F8EC] to-transparent pointer-events-none"></div>
                    <img src="{{ asset('food_distribution.png') }}" class="absolute right-0 bottom-0 h-48 w-48 object-cover rounded-tl-[40px] opacity-80 mix-blend-multiply">
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-[#6B630C] rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span class="font-bold text-[#6B630C] tracking-wide">Total Penyaluran</span>
                        </div>
                        <h3 class="text-7xl font-extrabold text-gray-800 leading-none mb-6">{{ number_format($totalPenyaluran, 0, ',', '.') }}</h3>
                        <p class="text-sm text-gray-600 font-medium max-w-[200px] leading-relaxed mb-8">Porsi makanan bergizi telah didistribusikan ke komunitas dalam 30 hari terakhir.</p>
                        
                        <span class="inline-flex items-center px-4 py-2 bg-[#E8F5E9] text-[#4CAF50] rounded-full text-xs font-bold shadow-sm">
                            <span class="w-2 h-2 bg-[#4CAF50] rounded-full mr-2 animate-pulse"></span> {{ $growthText }}
                        </span>
                    </div>
                </div>

                <!-- Distribusi Terbaru -->
                <div class="col-span-6 flex flex-col">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Distribusi Terbaru</h3>
                    <div class="space-y-4 flex-1">
                        @forelse($distribusiTerbaru as $index => $item)
                        @php
                            $borderColors = ['border-[#FCD34D]', 'border-gray-200', 'border-gray-200', 'border-[#EAEBCA]'];
                            $borderColor = $borderColors[$index] ?? 'border-gray-200';
                        @endphp
                        <div class="bg-white rounded-[24px] p-5 px-6 flex items-center shadow-sm border-l-4 {{ $borderColor }}">
                            <div class="w-12 h-12 bg-[#F8F8EC] rounded-full flex justify-center items-center mr-4 text-[#6B630C]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800">{{ $item->user->nama ?? 'Penerima' }}</h4>
                                <p class="text-xs font-medium text-gray-400 mt-0.5">
                                    {{ $item->donasi->nama_makanan ?? 'Makanan' }} • <span class="realtime-time" data-timestamp="{{ $item->created_at->toIso8601String() }}">{{ $item->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <h4 class="font-extrabold text-2xl text-gray-800">{{ $item->jumlah_permintaan }}</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Porsi</p>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white rounded-[24px] p-8 text-center text-gray-400 font-semibold text-sm">
                            Belum ada riwayat distribusi penyaluran makanan.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>


            
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            startDateInput.addEventListener('change', function() {
                if (startDateInput.value) {
                    endDateInput.min = startDateInput.value;
                } else {
                    endDateInput.removeAttribute('min');
                }
            });

            endDateInput.addEventListener('change', function() {
                if (endDateInput.value) {
                    startDateInput.max = endDateInput.value;
                } else {
                    startDateInput.removeAttribute('max');
                }
            });
        });
    </script>
</body>
</html>
