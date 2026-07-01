<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare Admin - Pemberitahuan & Maintenance</title>
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
                <a href="{{ route('admin.verifikasi') }}" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi
                </a>
                <a href="{{ route('admin.pemberitahuan') }}" class="flex items-center px-8 py-3 bg-[#FCF8E3] text-[#6B630C] border-r-4 border-[#FCD34D] font-bold text-sm">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    Pemberitahuan
                </a>
                <a href="{{ route('admin.statistik') }}" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    Statistik
                </a>
            </nav>
        </div>
        <div class="px-8 mt-6">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center text-gray-400 hover:text-gray-600 text-sm font-semibold transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <!-- Topbar -->
        <header class="flex items-center justify-between px-10 py-6 shrink-0">
            <div class="relative w-96">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search announcement..." class="w-full bg-white rounded-full py-3 pl-12 pr-6 text-sm font-medium border-none shadow-sm focus:ring-2 focus:ring-[#FCD34D] outline-none">
            </div>
            <div class="flex items-center space-x-8">
                {{-- Removed Dashboard, Alerts, Settings links --}}
                <div class="flex items-center space-x-4 text-gray-400">
                    <x-admin-notifications />
                    <button><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>
                    <a href="{{ route('profile.show') }}">
                        <img src="{{ auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama ?? 'Admin') . '&background=F97316&color=fff' }}" class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm hover:opacity-90 transition">
                    </a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="px-10 py-4 flex-1 flex flex-col">
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-2xl mb-6 text-sm font-semibold border border-green-200 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-end mb-8">
                <div>
                    <div class="flex items-center text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-1">
                        <span>Admin</span>
                        <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-[#6B630C]">Pemberitahuan</span>
                    </div>
                    <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Kelola<br>Pemberitahuan</h2>
                </div>
            </div>

            <div class="flex gap-8 flex-1">
                
                <!-- Left Column (Create Form) -->
                <div class="w-[400px] shrink-0">
                    <div class="bg-white rounded-[32px] p-8 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <span class="text-xl">📢</span> Buat Pemberitahuan Baru
                        </h3>
                        <form action="{{ route('admin.pemberitahuan.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Judul</label>
                                <input type="text" name="judul" required placeholder="Contoh: Maintenance Sistem Utama" 
                                       class="w-full bg-[#F8F8EC] rounded-2xl px-5 py-3.5 text-sm font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tipe Pemberitahuan</label>
                                <select name="tipe" required 
                                        class="w-full bg-[#F8F8EC] rounded-2xl px-5 py-3.5 text-sm font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none appearance-none cursor-pointer">
                                    <option value="Maintenance">Maintenance Sistem</option>
                                    <option value="Informasi">Informasi Lain / Pengumuman</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pesan / Detail</label>
                                <textarea name="pesan" required rows="4" placeholder="Jelaskan isi pengumuman atau detail maintenance di sini..."
                                          class="w-full bg-[#F8F8EC] rounded-2xl px-5 py-3.5 text-sm font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mulai</label>
                                    <input type="datetime-local" name="tanggal_mulai" 
                                           class="w-full bg-[#F8F8EC] rounded-2xl px-4 py-3 text-xs font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Selesai</label>
                                    <input type="datetime-local" name="tanggal_selesai" 
                                           class="w-full bg-[#F8F8EC] rounded-2xl px-4 py-3 text-xs font-medium border-none shadow-inner focus:ring-2 focus:ring-[#FCD34D] outline-none">
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-[#6B630C] text-white font-bold py-4 rounded-full shadow-lg hover:opacity-95 transition flex items-center justify-center gap-2 border-none outline-none cursor-pointer mt-4">
                                <span>🚀</span> Kirim & Umumkan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Column (List Announcements) -->
                <div class="flex-grow flex flex-col">
                    <div class="bg-white rounded-[32px] p-8 shadow-sm flex-1 flex flex-col">
                        <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-6">DAFTAR PEMBERITAHUAN AKTIF / DIJADWALKAN</p>
                        
                        <div id="announcements-list" class="space-y-4 flex-1 overflow-y-auto max-h-[500px] pr-2">
                            @forelse($announcements as $ann)
                                @php
                                    $isMaintenance = $ann->tipe === 'Maintenance';
                                    $badgeBg = $isMaintenance ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-blue-100 text-blue-800 border-blue-200';
                                    $emoji = $isMaintenance ? '⚠️' : '📢';
                                @endphp
                                <div id="ann-card-{{ $ann->id_pemberitahuan }}" class="ann-card bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-start justify-between gap-4 transition-all duration-300">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0 {{ $isMaintenance ? 'bg-amber-50' : 'bg-blue-50' }}">
                                            {{ $emoji }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3 mb-1">
                                                <h4 class="font-extrabold text-sm text-gray-800">{{ $ann->judul }}</h4>
                                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase tracking-wider {{ $badgeBg }}">{{ $ann->tipe }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 font-medium leading-relaxed mb-3">{{ $ann->pesan }}</p>
                                            
                                            <div class="flex gap-4 text-[10px] font-bold text-gray-400 uppercase tracking-wide">
                                                @if($ann->tanggal_mulai)
                                                    <span class="flex items-center"><svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Mulai: {{ \Carbon\Carbon::parse($ann->tanggal_mulai)->format('d M Y H:i') }}</span>
                                                @endif
                                                @if($ann->tanggal_selesai)
                                                    <span class="flex items-center"><svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Selesai: {{ \Carbon\Carbon::parse($ann->tanggal_selesai)->format('d M Y H:i') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('admin.pemberitahuan.destroy', $ann->id_pemberitahuan) }}" method="POST" class="delete-ann-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-transparent text-gray-400 hover:text-red-500 rounded-full hover:bg-red-50 transition border-none outline-none cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="py-12 text-center text-gray-400 font-semibold flex flex-col items-center justify-center">
                                    <span class="text-4xl mb-3">🔔</span>
                                    <h4 class="text-sm font-bold text-gray-700">Tidak Ada Pemberitahuan</h4>
                                    <p class="text-xs font-medium text-gray-400 max-w-xs mt-1">Gunakan form di sebelah kiri untuk menyebarkan pengumuman baru.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const listContainer = document.getElementById('announcements-list');
            if (!listContainer) return;
            
            listContainer.addEventListener('submit', function(e) {
                if (e.target.classList.contains('delete-ann-form')) {
                    e.preventDefault();
                    
                    if (!confirm('Apakah Anda yakin ingin menghapus pemberitahuan ini?')) {
                        return;
                    }
                    
                    const form = e.target;
                    const action = form.action;
                    const card = form.closest('.ann-card');
                    const csrfToken = form.querySelector('input[name="_token"]').value;
                    
                    fetch(action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams(new FormData(form))
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Fade out card and remove from DOM smoothly
                            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease, max-height 0.4s ease';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.95)';
                            
                            setTimeout(() => {
                                card.remove();
                                // Check if list is empty now
                                const remainingCards = listContainer.querySelectorAll('.ann-card');
                                if (remainingCards.length === 0) {
                                    listContainer.innerHTML = `
                                        <div class="py-12 text-center text-gray-400 font-semibold flex flex-col items-center justify-center">
                                            <span class="text-4xl mb-3">🔔</span>
                                            <h4 class="text-sm font-bold text-gray-700">Tidak Ada Pemberitahuan</h4>
                                            <p class="text-xs font-medium text-gray-400 max-w-xs mt-1">Gunakan form di sebelah kiri untuk menyebarkan pengumuman baru.</p>
                                        </div>
                                    `;
                                }
                            }, 400);
                        } else {
                            alert(data.message || 'Gagal menghapus pemberitahuan.');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting announcement:', error);
                        alert('Terjadi kesalahan saat menghapus pemberitahuan.');
                    });
                }
            });
        });
    </script>
</body>
</html>
