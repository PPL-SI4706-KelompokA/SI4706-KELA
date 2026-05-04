<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Makanan - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">

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
            <a href="{{ route('donasi.riwayatpenerimaan') }}" class="hover:opacity-80 transition-opacity">
                <svg class="w-6 h-6 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </a>
            <div class="w-10 h-10 rounded-full border-2 border-[#FCD34D] overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=User&background=FCD34D&color=5B5C35" class="w-full h-full object-cover">
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow px-12 py-8 flex flex-col lg:flex-row gap-12 items-start justify-center">
        
        <!-- Left Side: Food Detail -->
        <div class="w-full lg:w-1/2 max-w-2xl">
            <div class="relative rounded-[40px] overflow-hidden shadow-sm mb-8 bg-white h-[450px]">
                <img src="{{ $donasi->foto_url ?? 'https://via.placeholder.com/800x600' }}" alt="Food Image" class="w-full h-full object-cover">
                <span class="absolute top-6 left-6 px-6 py-2.5 rounded-full text-sm font-bold bg-[#FCD34D] text-[#5B5C35]">
                    {{ $donasi->jumlah ?? '3' }} porsi tersedia
                </span>
            </div>

            <h2 class="text-5xl font-extrabold text-[#7C7E3A] mb-6 leading-tight">{{ $donasi->nama_makanan ?? 'Nasi Goreng Spesial' }}</h2>
            
            <div class="flex flex-wrap gap-3 mb-8">
                <div class="flex items-center px-6 py-2 bg-[#E4E5C8] bg-opacity-40 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kadaluarsa: {{ \Carbon\Carbon::parse($donasi->tanggal_kadaluarsa ?? '2026-01-11')->format('d/m/Y') }}
                </div>
                <div class="flex items-center px-6 py-2 bg-[#E4E5C8] bg-opacity-40 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Jl. Dago, Bandung
                </div>
            </div>

            <div class="bg-white rounded-[32px] p-6 flex items-center shadow-sm border border-gray-50">
                <div class="w-14 h-14 rounded-full bg-[#FCD34D] flex items-center justify-center font-bold text-xl text-[#5B5C35] mr-4 shadow-inner">
                    BS
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Donatur</p>
                    <p class="text-xl font-bold text-gray-800">Budi Santoso</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Request Form (FR06) -->
        <div class="w-full lg:w-[450px] bg-white rounded-[48px] p-10 shadow-sm border border-gray-50">
            <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Informasi Permintaan</h3>
            <p class="text-gray-400 text-sm font-medium mb-8 leading-relaxed">Silakan lengkapi data di bawah ini untuk menerima donasi makanan ini.</p>

            <form action="{{ route('donasi.pesan', $donasi->id_donasi ?? 1) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Porsi yang Diminta</label>
                    <input type="number" name="jumlah_permintaan" placeholder="Contoh: 1" 
                        class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima</label>
                    <input type="text" name="nama_penerima" placeholder="Masukkan nama lengkap" 
                        class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="nomor_telepon" placeholder="08xx xxxx xxxx" 
                        class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                </div>

                <button type="submit" class="w-full py-4 bg-[#FCD34D] text-[#5B5C35] font-extrabold rounded-full hover:bg-[#fbc316] transition-all shadow-md mt-4">
                    Kirim Permintaan
                </button>
            </form>

            <div class="mt-8 flex items-start text-[11px] font-semibold text-gray-400 leading-relaxed">
                <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Donasi ini bersifat sukarela. Mohon segera ambil setelah permintaan disetujui untuk menjaga kualitas makanan.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 px-12 flex justify-between items-center text-[10px] font-bold text-gray-400 border-t border-[#E4E5C8] mt-12">
        <div>© 2026 FoodShare</div>
        <div class="flex space-x-6 uppercase tracking-widest">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>

</body>
</html>