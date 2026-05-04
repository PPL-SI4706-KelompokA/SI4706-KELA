<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Donasi - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full py-6 px-12 flex items-center justify-between bg-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
        <div class="flex space-x-8 font-semibold text-sm">
            <a href="{{ route('donasi.daftar') }}" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Donasi</a>
            <a href="#" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
        </div>
        <div class="flex items-center space-x-6">
            <button class="text-gray-600 hover:text-[#5B5C35]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button>
            <a href="{{ route('donasi.riwayat') }}" class="hover:opacity-80 transition-opacity">
                <svg class="w-6 h-6 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </a>
            <div class="w-10 h-10 rounded-full border-2 border-[#FCD34D] overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->nama ?? 'User' }}&background=FCD34D&color=5B5C35" alt="Profile" class="w-full h-full object-cover">
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <header class="px-12 py-10">
        <h1 class="text-5xl font-extrabold leading-[1.1] mb-4">
            Berbagi Kebahagiaan,<br>
            <span class="text-[#7C7E3A]">Satu Porsi Sekaligus.</span>
        </h1>
        <p class="text-gray-500 text-lg max-w-2xl mb-10">
            Temukan berbagai donasi makanan dari tetangga sekitar yang siap dibagikan untuk yang membutuhkan.
        </p>

        <!-- Category Filter (FR18) -->
        <div class="flex space-x-3 overflow-x-auto pb-4">
            <a href="{{ route('donasi.daftar') }}" class="px-8 py-2.5 rounded-full font-bold text-sm bg-[#FCD34D] text-[#5B5C35] shadow-sm">Semua</a>
            <a href="{{ route('donasi.filter', ['kategori' => 'Makanan']) }}" class="px-8 py-2.5 rounded-full font-bold text-sm bg-[#E4E5C8] text-gray-500 hover:bg-[#D4D5B8]">Makanan</a>
            <a href="{{ route('donasi.filter', ['kategori' => 'Snack']) }}" class="px-8 py-2.5 rounded-full font-bold text-sm bg-[#E4E5C8] text-gray-500 hover:bg-[#D4D5B8]">Snack</a>
            <a href="{{ route('donasi.filter', ['kategori' => 'Minuman']) }}" class="px-8 py-2.5 rounded-full font-bold text-sm bg-[#E4E5C8] text-gray-500 hover:bg-[#D4D5B8]">Minuman</a>
        </div>
    </header>

    <!-- Grid Donasi (FR04) -->
    <main class="px-12 pb-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($donasis as $item)
        <div class="bg-white rounded-[32px] overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col">
            <!-- Image Container -->
            <div class="relative h-60">
                <img src="{{ $item->foto_url ?? 'https://via.placeholder.com/400x300?text=FoodShare' }}" class="w-full h-full object-cover" alt="{{ $item->nama_makanan }}">
                <span class="absolute top-4 right-4 px-4 py-1.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-[#71B58C] text-white">
                    {{ $item->status_donasi }}
                </span>
            </div>

            <!-- Content Container -->
            <div class="p-6 flex-grow">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ $item->nama_makanan }}</h3>
                    <span class="px-3 py-1 bg-[#F2F3E2] text-gray-500 text-[10px] font-bold rounded-full uppercase leading-tight text-center">
                        {{ $item->kategori }}
                    </span>
                </div>

                <div class="space-y-2.5 mb-8 text-sm font-medium text-gray-500">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        {{ $item->jumlah }} Porsi
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Exp: {{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d M Y') }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Bandung, Jawa Barat {{-- Nanti bisa ambil dari relasi lokasi --}}
                    </div>
                </div>

                <a href="#" class="block w-full py-3 bg-[#E4E5C8] text-gray-600 text-center font-bold text-sm rounded-2xl hover:bg-[#D4D5B8] transition-colors">
                    Lihat Detail
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <p class="text-gray-400">Belum ada donasi makanan tersedia saat ini.</p>
        </div>
        @endforelse
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