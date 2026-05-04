<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Donasi - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>

<script>
    function toggleFilter() {
        const modal = document.getElementById('filterModal');
        modal.classList.toggle('hidden');
    }

    // Hubungkan tombol filter di header dengan fungsi ini
    document.querySelector('button:contains("Filter")').onclick = toggleFilter;
</script>

<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen relative pb-20">

    <!-- Navbar -->
    <nav class="w-full py-6 px-12 flex items-center justify-between bg-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
        <div class="flex space-x-8 font-semibold text-sm">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
            <a href="#" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
        </div>
        <div class="flex items-center space-x-6">
            <button class="text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg></button>
            <a href="{{ route('donasi.riwayat') }}" class="hover:opacity-80 transition-opacity">
                <svg class="w-6 h-6 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </a>            
            <div class="w-10 h-10 rounded-full border-2 border-[#FCD34D] overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=User&background=FCD34D&color=5B5C35" class="w-full h-full object-cover">
            </div>
        </div>
    </nav>

    <!-- Search Section -->
    <header class="px-12 py-8">
        <h2 class="text-3xl font-extrabold mb-6">Cari Donasi Makanan</h2>
        
        <form action="{{ route('donasi.cari') }}" method="GET" class="relative max-w-full mb-6">
            <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari Sekarang" 
                class="w-full py-4 px-6 rounded-2xl bg-white border-none shadow-sm focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
            <button type="submit" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>

        <div class="flex space-x-4 mb-10">
            <button onclick="toggleFilter()" class="px-8 py-2 bg-white rounded-full font-bold text-sm shadow-sm border border-gray-100 hover:bg-gray-50">
            Filter
            </button>
            <a href="{{ route('donasi.peta') }}" class="px-8 py-2 bg-white rounded-full font-bold text-sm shadow-sm border border-gray-100 flex items-center hover:bg-gray-50 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Lokasi
            </a>
        </div>
    </header>

    <!-- Grid Hasil Pencarian -->
    <main class="px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($hasilPencarian as $item)
        <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-50 flex flex-col">
            <div class="relative h-56">
                <img src="{{ $item->foto_url ?? 'https://via.placeholder.com/400x300' }}" class="w-full h-full object-cover">
                <span class="absolute top-4 right-4 px-4 py-1 rounded-full text-[10px] font-bold bg-[#4CAF50] text-white uppercase">Tersedia</span>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800 leading-tight">{{ $item->nama_makanan }}</h3>
                    <span class="text-sm font-bold text-[#7C7E3A]">{{ $item->jumlah }} Porsi</span>
                </div>
                <div class="space-y-2 mb-6 text-[12px] font-medium text-gray-400 uppercase tracking-wide">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Exp: {{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d M Y') }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        Bandung
                    </div>
                </div>
                <button class="w-full py-3 bg-[#FCD34D] text-[#5B5C35] font-bold rounded-2xl hover:bg-[#fbc316] transition-all shadow-sm">Lihat Detail</button>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center text-gray-400">Tidak ada hasil yang ditemukan untuk "{{ $q }}".</div>
        @endforelse
    </main>

    <!-- Overlay Background (Blur) -->
<div id="filterModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-[#5B5C35] bg-opacity-40 backdrop-blur-sm px-6">
    
    <!-- Modal Box -->
    <div class="bg-[#F8F8E6] w-full max-w-md rounded-[48px] p-10 shadow-2xl relative border border-white border-opacity-50">
        
        <!-- Close Button -->
        <button onclick="toggleFilter()" class="px-8 py-2 bg-white rounded-full font-bold text-sm shadow-sm border border-gray-100">Filter</button>

        <h3 class="text-2xl font-extrabold text-[#5B5C35] mb-8 leading-tight">Filter<br>Makanan</h3>

        <form action="{{ route('donasi.filter') }}" method="GET">
            <!-- Kategori Makanan -->
            <div class="mb-8">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Kategori Makanan</p>
                <div class="flex flex-wrap gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="kategori" value="" class="hidden peer" checked>
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] peer-checked:text-[#5B5C35] transition-all block">Semua</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="kategori" value="Snack" class="hidden peer">
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] peer-checked:text-[#5B5C35] transition-all block">Snack</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="kategori" value="Makanan" class="hidden peer">
                        <span class="px-6 py-2 rounded-full bg-[#6B630C] text-white text-xs font-bold peer-checked:bg-[#6B630C] block">Makanan</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="kategori" value="Minuman" class="hidden peer">
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] peer-checked:text-[#5B5C35] transition-all block">Minuman</span>
                    </label>
                </div>
            </div>

            <!-- Status Donasi -->
            <div class="mb-12">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Status Donasi</p>
                <div class="flex flex-wrap gap-2">
                    <label class="cursor-pointer">
                        <input type="checkbox" name="status[]" value="Tersedia" class="hidden peer" checked>
                        <span class="px-6 py-2 rounded-full border-2 border-[#6B630C] text-[#6B630C] text-xs font-bold peer-checked:bg-[#F2F3E2] flex items-center transition-all">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Tersedia
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="status[]" value="Dipesan" class="hidden peer">
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] transition-all block border-2 border-transparent">Dipesan</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="status[]" value="Selesai" class="hidden peer">
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] transition-all block border-2 border-transparent">Selesai</span>
                    </label>
                </div>
            </div>

            <!-- Modal Footer Actions -->
            <div class="flex items-center justify-between gap-4">
                <button type="reset" class="text-sm font-bold text-gray-500 hover:text-gray-700">Reset</button>
                <button type="submit" class="flex-grow py-4 bg-[#6B630C] text-white font-extrabold rounded-[20px] shadow-lg shadow-[#6b630c33] hover:bg-[#524d0a] transition-all">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Floating Action Button -->
    <a href="{{ route('donasi.tambah') }}" class="fixed bottom-10 right-12 px-8 py-4 bg-[#FCD34D] text-[#5B5C35] font-bold rounded-full shadow-lg flex items-center hover:scale-105 transition-transform">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
        Tambah Donasi
    </a>

    <!-- Pagination -->
    <div class="flex justify-center mt-12 space-x-2">
        <button class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></button>
        <button class="w-10 h-10 rounded-full bg-[#FCD34D] text-[#5B5C35] font-bold">1</button>
        <button class="w-10 h-10 rounded-full bg-white text-gray-500 font-bold border border-gray-100">2</button>
        <button class="w-10 h-10 rounded-full bg-white text-gray-500 font-bold border border-gray-100">3</button>
        <button class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></button>
    </div>

    <!-- Footer -->
    <footer class="mt-20 py-8 px-12 flex justify-between items-center text-[10px] font-bold text-gray-400 border-t border-[#E4E5C8]">
        <div>© 2026 FoodShare</div>
        <div class="flex space-x-6 uppercase tracking-widest">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>

</body>
</html>