<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare Admin - Statistik Donasi</title>
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
                <a href="{{ route('admin.statistik') }}" class="flex items-center px-8 py-3 bg-[#FCF8E3] text-[#6B630C] border-r-4 border-[#FCD34D] font-bold text-sm">
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
        <div class="px-10 py-4">
            @if(session('success'))
                <div class="bg-green-150 border border-green-250 text-green-700 px-6 py-4 rounded-[20px] mb-6 font-semibold text-sm flex items-center justify-between shadow-sm">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-750 hover:text-green-900 font-bold">&times;</button>
                </div>
            @endif

            <div class="flex justify-between items-end mb-8">
                <div>
                    <div class="flex items-center text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-1">
                        <span>Admin</span>
                        <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-[#6B630C]">Statistics</span>
                    </div>
                    <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Statistik<br>Donasi</h2>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">
                
                <!-- Total Donasi Terkumpul -->
                <div class="col-span-8 bg-[#6B630C] rounded-[40px] p-10 text-white relative overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white opacity-5 rounded-full blur-3xl"></div>
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="font-bold tracking-wide">Total Donasi Terkumpul</span>
                    </div>
                    <div class="flex items-end gap-6 mb-12 relative z-10">
                        <h3 class="text-7xl font-extrabold leading-none">{{ number_format($totalDonasiTerkumpul, 0, ',', '.') }}</h3>
                        <span class="px-3 py-1.5 bg-white/20 rounded-full text-xs font-bold flex items-center mb-2">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> {{ $growthText }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-8 relative z-10 border-t border-white/20 pt-6">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <p class="text-[10px] font-bold text-white/60 tracking-widest uppercase">Target Bulanan</p>
                                <button onclick="openTargetModal({{ $targetBulanan }})" class="hover:text-white/80 text-white/60 transition" title="Ubah Target Bulanan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xl font-bold">{{ number_format($targetBulanan, 0, ',', '.') }} <span class="text-xs font-normal text-white/80">Paket</span></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-white/60 tracking-widest uppercase mb-1">Status Keberhasilan</p>
                            <p class="text-xl font-bold">{{ $statusKeberhasilan }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Donatur Count -->
                <div class="col-span-4 bg-white rounded-[40px] p-8 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-[#FCD34D] rounded-2xl flex items-center justify-center text-[#6B630C]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-bold text-gray-500 tracking-wider">DONATUR</span>
                        </div>
                        <h3 class="text-5xl font-extrabold text-gray-800 mb-2">{{ number_format($totalDonatur, 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium mb-6"><strong class="text-[#4CAF50]">+{{ $donaturBaru }}</strong> pendaftar baru yang bergabung dengan komunitas minggu ini.</p>
                    </div>
                    <div class="flex -space-x-2">
                        @if($totalDonatur > 0)
                            <img src="https://ui-avatars.com/api/?name=D&background=random" class="w-8 h-8 rounded-full border-2 border-white">
                        @endif
                        @if($totalDonatur > 1)
                            <img src="https://ui-avatars.com/api/?name=O&background=random" class="w-8 h-8 rounded-full border-2 border-white">
                        @endif
                        @if($totalDonatur > 2)
                            <img src="https://ui-avatars.com/api/?name=N&background=random" class="w-8 h-8 rounded-full border-2 border-white">
                        @endif
                        @if($totalDonatur > 3)
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">+{{ $totalDonatur - 3 }}</div>
                        @endif
                    </div>
                </div>

                <!-- Donatur Teratas -->
                <div class="col-span-12 bg-[#EAEBCA] rounded-[40px] p-8">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6 px-2">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Donatur Teratas</h3>
                            @if($startDate && $endDate)
                                <p class="text-[10px] font-bold text-[#6B630C]/70 mt-1">Periode: {{ Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</p>
                            @endif
                        </div>
                        
                        <!-- Date Filter Form -->
                        <form action="{{ route('admin.statistik') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                            <div class="flex items-center bg-white/80 backdrop-blur rounded-full px-4 py-1.5 border border-white/40 shadow-sm">
                                <label for="start_date" class="text-[9px] font-bold text-gray-400 uppercase mr-2">Dari</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="bg-transparent border-none text-xs font-semibold text-gray-700 focus:ring-0 p-0 w-28 cursor-pointer" required>
                                <span class="mx-2 text-gray-300">|</span>
                                <label for="end_date" class="text-[9px] font-bold text-gray-400 uppercase mr-2">Sampai</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="bg-transparent border-none text-xs font-semibold text-gray-700 focus:ring-0 p-0 w-28 cursor-pointer" required>
                            </div>
                            <button type="submit" class="bg-[#6B630C] text-white hover:bg-[#524c0a] text-xs font-bold px-5 py-2.5 rounded-full shadow-sm transition">
                                Filter
                            </button>
                            @if($startDate || $endDate)
                                <a href="{{ route('admin.statistik') }}" class="bg-white hover:bg-gray-50 text-gray-750 text-xs font-bold px-5 py-2.5 rounded-full border border-gray-200 transition shadow-sm">
                                    Reset
                                </a>
                            @endif
                            <a href="{{ route('admin.manajemen') }}" class="text-sm font-bold text-[#6B630C] hover:underline flex items-center ml-2">Lihat Semua <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></a>
                        </form>
                    </div>
                    <div class="space-y-3">
                        @forelse($topDonators as $donor)
                        @php
                            $isTopTwo = in_array($donor['rank'], ['01', '02']);
                            $bgClass = $isTopTwo ? 'bg-white shadow-sm' : 'bg-white/60';
                            $statusColor = $donor['status'] === 'Sangat Aktif' ? '#4CAF50' : ($donor['status'] === 'Aktif Berkala' ? '#F59E0B' : '#9CA3AF');
                        @endphp
                        <div class="{{ $bgClass }} rounded-[24px] p-4 px-6 flex items-center">
                            <span class="text-2xl font-bold text-gray-300 w-12">{{ $donor['rank'] }}</span>
                            <img src="{{ $donor['foto'] }}" class="w-12 h-12 rounded-full object-cover mr-4">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">{{ $donor['nama'] }}</h4>
                                <p class="text-[10px] font-bold flex items-center" style="color: {{ $statusColor }};">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background-color: {{ $statusColor }};"></span>
                                    {{ $donor['status'] }}
                                </p>
                            </div>
                            <div class="text-right">
                                <h4 class="font-extrabold text-xl text-gray-800">{{ number_format($donor['total_donasi'], 0, ',', '.') }}</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Paket Donasi</p>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white/40 rounded-[24px] p-8 text-center text-gray-500 font-semibold text-sm">
                            Belum ada data donasi di dalam database.
                        </div>
                        @endforelse
                    </div>
                </div>


            </div>
        </div>
    </main>

    <!-- Target Bulanan Modal -->
    <div id="targetModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[32px] w-full max-w-sm p-8 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-extrabold text-gray-800">Target Bulanan</h3>
                <button onclick="closeTargetModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.statistik.update-target') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="target_bulanan" class="block text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-2">Target Baru (Paket Makanan)</label>
                    <input type="number" name="target_bulanan" id="target_bulanan" min="1" class="w-full bg-gray-50 border border-gray-250 rounded-[20px] px-5 py-3.5 text-sm font-semibold text-gray-800 focus:outline-none focus:border-[#6B630C] focus:ring-1 focus:ring-[#6B630C] transition" required>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeTargetModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold py-3.5 rounded-[20px] transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-[#6B630C] hover:bg-[#524c0a] text-white text-sm font-bold py-3.5 rounded-[20px] transition shadow-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openTargetModal(currentValue) {
            const modal = document.getElementById('targetModal');
            const input = document.getElementById('target_bulanan');
            if (modal && input) {
                input.value = currentValue;
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.querySelector('.transform').classList.remove('scale-95');
                }, 10);
            }
        }

        function closeTargetModal() {
            const modal = document.getElementById('targetModal');
            if (modal) {
                modal.classList.add('opacity-0');
                modal.querySelector('.transform').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    if (startDateInput.value) {
                        endDateInput.min = startDateInput.value;
                    }
                });
                endDateInput.addEventListener('change', function() {
                    if (endDateInput.value) {
                        startDateInput.max = endDateInput.value;
                    }
                });
                if (startDateInput.value) endDateInput.min = startDateInput.value;
                if (endDateInput.value) startDateInput.max = endDateInput.value;
            }
        });
    </script>
</body>
</html>
