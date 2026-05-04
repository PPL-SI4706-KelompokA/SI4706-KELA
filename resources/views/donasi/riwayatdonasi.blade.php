<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Riwayat Donasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full py-6 px-8 flex items-center justify-between">
        <div class="text-2xl font-extrabold tracking-tight text-[#85884B]">FoodShare</div>
        <div class="hidden md:flex space-x-8 font-semibold text-sm">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
            <a href="#" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
        </div>
        <div class="flex items-center space-x-6 text-[#85884B]">
            <!-- Bell Icon -->
            <svg class="w-6 h-6 cursor-pointer hover:text-[#5B5C35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <!-- History Icon -->
            <a href="{{ route('donasi.riwayat') }}" class="hover:opacity-80 transition-opacity">
                <svg class="w-6 h-6 cursor-pointer text-[#FCD34D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </a>
            <!-- User Profile (Placeholder) -->
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center overflow-hidden border border-gray-200">
                <img src="https://ui-avatars.com/api/?name=Tony+Stark&background=EBF4FF&color=3B82F6" alt="Profile" class="w-full h-full object-cover">
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-5xl mx-auto px-4 sm:px-8 w-full py-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-[#85884B] mb-3">Riwayat Donasi</h1>
            <p class="text-gray-500 text-sm md:text-base max-w-lg">
                Lacak semua kebaikan yang telah Anda bagikan. Setiap porsi sangat berarti bagi mereka yang membutuhkan.
            </p>
        </div>

        <!-- Filter Buttons -->
        <div class="flex space-x-3 mb-10">
            <button class="bg-[#FCD34D] text-[#5B5C35] px-6 py-2 rounded-full font-bold text-sm shadow-sm">Semua</button>
            <button class="bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4] transition px-6 py-2 rounded-full font-semibold text-sm">Selesai</button>
            <button class="bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4] transition px-6 py-2 rounded-full font-semibold text-sm">Diproses</button>
        </div>

        <!-- Cards Container -->
        <div class="space-y-6">
            
            <!-- Card 1: Selesai -->
            <div class="bg-white p-3 rounded-[32px] shadow-sm flex flex-col md:flex-row gap-6">
                <!-- Image -->
                <div class="w-full md:w-64 h-48 relative shrink-0">
                    <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Nasi Box" class="w-full h-full object-cover rounded-[24px]">
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-bold px-3 py-1.5 rounded-full text-[#5B5C35] shadow-sm">
                        5 Porsi
                    </div>
                </div>
                <!-- Content -->
                <div class="flex-1 flex flex-col justify-center py-2 pr-4">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-bold text-gray-800">Nasi Box Ayam Bakar</h2>
                        <span class="bg-[#D1FAE5] text-[#065F46] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Selesai
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-500 font-medium mb-4">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Kedaluwarsa: 24 Okt, 20:00
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Jakarta Selatan, Tebet
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Didonasikan: 24 Okt, 14:20
                        </div>
                    </div>

                    <hr class="border-gray-100 my-2">

                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-600">
                            <div class="w-6 h-6 bg-[#FCD34D] rounded-full flex items-center justify-center text-white">
                                <svg class="w-3.5 h-3.5 text-[#5B5C35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Penerima: Yayasan Kasih Ibu
                        </div>
                        <a href="#" class="text-[#85884B] font-bold text-sm flex items-center gap-1 hover:text-[#5B5C35]">
                            Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Diproses -->
            <div class="bg-white p-3 rounded-[32px] shadow-sm flex flex-col md:flex-row gap-6">
                <!-- Image -->
                <div class="w-full md:w-64 h-48 relative shrink-0">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Aneka Roti" class="w-full h-full object-cover rounded-[24px]">
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-bold px-3 py-1.5 rounded-full text-[#5B5C35] shadow-sm">
                        12 Pcs
                    </div>
                </div>
                <!-- Content -->
                <div class="flex-1 flex flex-col justify-center py-2 pr-4">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-bold text-gray-800">Aneka Roti Manis</h2>
                        <span class="bg-[#FFEDD5] text-[#C2410C] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                            <span class="w-2 h-2 bg-[#F97316] rounded-full animate-pulse"></span>
                            Diproses
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-500 font-medium mb-4">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Kedaluwarsa: 25 Okt, 10:00
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Jakarta Barat, Puri
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Didonasikan: 24 Okt, 18:45
                        </div>
                    </div>

                    <hr class="border-gray-100 my-2">

                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-600">
                            <div class="w-6 h-6 bg-[#FCD34D] rounded-full flex items-center justify-center text-white">
                                <svg class="w-3.5 h-3.5 text-[#5B5C35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </div>
                            Status: Menunggu Kurir
                        </div>
                        <a href="#" class="text-[#85884B] font-bold text-sm flex items-center gap-1 hover:text-[#5B5C35]">
                            Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3: Selesai -->
            <div class="bg-white p-3 rounded-[32px] shadow-sm flex flex-col md:flex-row gap-6">
                <!-- Image -->
                <div class="w-full md:w-64 h-48 relative shrink-0">
                    <img src="https://images.unsplash.com/photo-1582281298055-e25b84a30b0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Buah Segar" class="w-full h-full object-cover rounded-[24px]">
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-bold px-3 py-1.5 rounded-full text-[#5B5C35] shadow-sm">
                        8 Porsi
                    </div>
                </div>
                <!-- Content -->
                <div class="flex-1 flex flex-col justify-center py-2 pr-4">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-bold text-gray-800">Buah Segar Potong</h2>
                        <span class="bg-[#D1FAE5] text-[#065F46] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Selesai
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-500 font-medium mb-4">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Kedaluwarsa: 23 Okt, 16:00
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Jakarta Pusat, Menteng
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Didonasikan: 23 Okt, 09:15
                        </div>
                    </div>

                    <hr class="border-gray-100 my-2">

                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-600">
                            <div class="w-6 h-6 bg-[#FCD34D] rounded-full flex items-center justify-center text-white">
                                <svg class="w-3.5 h-3.5 text-[#5B5C35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            Penerima: Komunitas Belajar
                        </div>
                        <a href="#" class="text-[#85884B] font-bold text-sm flex items-center gap-1 hover:text-[#5B5C35]">
                            Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Load More -->
        <div class="mt-10 flex justify-center">
            <button class="flex flex-col items-center text-[#85884B] font-bold text-sm hover:text-[#5B5C35] transition">
                Lihat Lebih Banyak
                <svg class="w-5 h-5 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-16 w-full max-w-7xl mx-auto px-8 py-6 border-t border-[#E4E7C4] flex flex-col md:flex-row justify-between items-center text-xs font-medium text-gray-500 gap-4">
        <div>&copy; 2026 FoodShare</div>
        <div class="flex space-x-6">
            <a href="#" class="hover:text-[#5B5C35]">Kebijakan Privasi</a>
            <a href="#" class="hover:text-[#5B5C35]">Ketentuan Layanan</a>
            <a href="#" class="hover:text-[#5B5C35]">Kontak</a>
        </div>
    </footer>

</body>
</html>