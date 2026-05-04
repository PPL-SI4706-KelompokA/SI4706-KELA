<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Permintaan - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8F8E6] text-[#1A1A1A] antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full py-6 px-12 flex items-center justify-between bg-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
        <div class="flex space-x-8 font-semibold text-sm text-[#5B5C35]">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Donasi</a>
            <a href="#" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
        </div>
        <div class="flex items-center space-x-6">
            <button class="text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg></button>
            <button class="text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>
            <div class="w-10 h-10 rounded-full border-2 border-[#FCD34D] overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=Devota&background=FCD34D&color=5B5C35" class="w-full h-full object-cover">
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="px-12 pt-12 pb-8">
        <h2 class="text-4xl font-extrabold text-[#333333] mb-2">Permintaan Donasi</h2>
        <p class="text-gray-500 font-medium">Detail permohonan bantuan makanan dari komunitas Anda.</p>
    </header>

    <!-- Main Card Container -->
    <main class="px-12 pb-20">
        <div class="bg-white rounded-[48px] overflow-hidden shadow-sm flex flex-col md:flex-row max-w-6xl mx-auto">
            
            <!-- Left Side: Food Image -->
            <div class="md:w-5/12 p-8">
                <div class="relative rounded-[32px] overflow-hidden h-full min-h-[500px]">
                    <img src="{{ $permintaan->donasi->foto_url ?? 'https://via.placeholder.com/600x800' }}" alt="Food" class="w-full h-full object-cover">
                    <div class="absolute top-6 left-6 px-5 py-2 bg-[#FCD34D] rounded-full text-[12px] font-bold text-[#5B5C35] flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 17a1 1 0 01-1.447.894L5 15.618V10a1 1 0 012 0v4.382l3.553 1.776A1 1 0 0111 17z"></path></svg>
                        Food
                    </div>
                </div>
            </div>

            <!-- Right Side: Details & Actions -->
            <div class="md:w-7/12 p-12 flex flex-col justify-center">
                <h3 class="text-3xl font-extrabold text-gray-800 mb-8">{{ $permintaan->donasi->nama_makanan ?? 'Nasi Goreng Spesial' }}</h3>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-10">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-[#F2F3E2] flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-[#7C7E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 118 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Tersedia</p>
                            <p class="text-sm font-bold text-gray-700">{{ $permintaan->donasi->jumlah ?? '3' }} porsi tersedia</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-[#F2F3E2] flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-[#7C7E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Kadaluarsa</p>
                            <p class="text-sm font-bold text-gray-700">{{ $permintaan->donasi->tanggal_kadaluarsa ?? '11 Jan 2026' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center col-span-2">
                        <div class="w-10 h-10 rounded-full bg-[#F2F3E2] flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-[#7C7E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Lokasi Penjemputan</p>
                            <p class="text-sm font-bold text-gray-700">Jl. Dago, Bandung</p>
                        </div>
                    </div>
                </div>

                <!-- Recipient Card -->
                <div class="bg-[#F2F3E2] rounded-[32px] p-8 mb-10 relative">
                    <h4 class="text-sm font-bold text-[#7C7E3A] mb-6">Detail Penerima</h4>
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 rounded-full border-2 border-white overflow-hidden mr-4">
                            <img src="https://ui-avatars.com/api/?name=Edra+Ataloma&background=FCD34D&color=5B5C35" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-800">Edra Ataloma</p>
                            <p class="text-xs font-semibold text-[#7C7E3A]">Meminta 1 Porsi</p>
                        </div>
                        <button class="absolute right-8 top-16 w-10 h-10 bg-[#FCD34D] rounded-full flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-[#5B5C35]" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 2V3z"></path></svg>
                        </button>
                    </div>

                    <!-- Note Bubble -->
                    <div class="bg-white rounded-2xl p-4 mb-4 relative shadow-sm">
                        <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest mb-1">Catatan</p>
                        <p class="text-sm font-medium italic text-gray-600">"Mohon bantuannya untuk keluarga saya."</p>
                    </div>

                    <div class="flex justify-between text-[11px] font-bold text-gray-400 uppercase tracking-wider px-2">
                        <span>0829213883</span>
                        <span>Hari ini, 10:30</span>
                    </div>
                </div>

                <!-- Action Buttons (FR07) -->
                <div class="flex gap-4">
                    <form action="{{ route('permintaan.konfirmasi', $permintaan->id ?? 1) }}" method="POST" class="w-1/2">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Ditolak">
                        <button type="submit" class="w-full py-4 rounded-full border-2 border-gray-200 font-bold text-gray-500 hover:bg-gray-50 transition-colors">
                            Tolak
                        </button>
                    </form>
                    <form action="{{ route('permintaan.konfirmasi', $permintaan->id ?? 1) }}" method="POST" class="w-1/2">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Disetujui">
                        <button type="submit" class="w-full py-4 rounded-full bg-[#6B630C] text-white font-bold shadow-lg shadow-[#6b630c33] flex items-center justify-center hover:bg-[#524d0a] transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Terima Permintaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-8 px-12 flex justify-between items-center text-[10px] font-bold text-gray-400 border-t border-[#E4E5C8]">
        <div>© 2026 FoodShare</div>
        <div class="flex space-x-6 uppercase tracking-widest">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>

</body>
</html>