<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Donasi - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen relative">

    <!-- Latar Belakang (Dibuat blur saat modal aktif) -->
    <div id="main-content" class="filter blur-sm pointer-events-none transition duration-300">
        <!-- Navbar -->
        <nav class="w-full py-6 px-8 flex items-center justify-between">
            <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
            <div class="hidden md:flex space-x-8 font-semibold text-sm">
                <a href="#" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Beranda</a>
                <a href="#" class="text-gray-500">Donasi</a>
                <a href="#" class="text-gray-500">Pesan</a>
            </div>
            <div class="flex items-center space-x-6 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <div class="w-8 h-8 rounded-full bg-gray-300 border-2 border-[#FCD34D] overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=User&background=FCD34D&color=5B5C35" alt="User" class="w-full h-full object-cover">
                </div>
            </div>
        </nav>

        <!-- Dummy Content Background -->
        <main class="max-w-7xl mx-auto px-8 py-8">
            <h1 class="text-4xl font-extrabold mb-2">Berbagi Kebahagiaan,<br><span class="text-[#7C7E3A]">Satu Porsi Sekaligus.</span></h1>
            <p class="text-gray-500 text-sm mb-8">Temukan berbagai donasi makanan dari tetangga sekitar yang<br>siap dibagikan untuk yang membutuhkan.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card Dummy -->
                <div class="bg-white rounded-3xl p-4 shadow-sm h-72 border border-gray-100"></div>
                <div class="bg-white rounded-3xl p-4 shadow-sm h-72 border border-gray-100"></div>
                <div class="bg-white rounded-3xl p-4 shadow-sm h-72 border border-gray-100"></div>
            </div>
        </main>
    </div>

    <!-- MODAL OVERLAY -->
    <div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm transition-opacity">
        
        <!-- MODAL BOX -->
        <div class="bg-[#F2F3E2] w-full max-w-md rounded-[32px] p-8 shadow-2xl relative border border-white/50">
            
            <!-- Tombol Close -->
            <button id="closeBtn" class="absolute top-6 right-6 text-gray-500 hover:text-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Title -->
            <h2 class="text-xl font-extrabold text-[#6B6D2F] mb-6">Status Donasi</h2>

            <!-- Form -->
            <form action="#" method="POST" id="statusForm">
                @csrf
                @method('PATCH')
                {{-- Nanti action="#" diganti menjadi route() oleh tim programmer --}}
                
                <!-- Input Hidden untuk menyimpan status yang dipilih -->
                <input type="hidden" name="status" id="selectedStatus" value="Tersedia">

                <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3">Status Donasi</label>

                <!-- Pilihan Status (Pills) -->
                <div class="flex items-center space-x-2 mb-10">
                    <!-- Option: Tersedia (Active) -->
                    <button type="button" onclick="selectStatus(this, 'Tersedia')" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-[#6B6D2F] text-[#6B6D2F] bg-transparent text-xs font-bold transition-all">
                        <svg class="w-3.5 h-3.5 mr-1 check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        Tersedia
                    </button>
                    
                    <!-- Option: Dipesan -->
                    <button type="button" onclick="selectStatus(this, 'Dipesan')" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                        <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        Dipesan
                    </button>

                    <!-- Option: Selesai -->
                    <button type="button" onclick="selectStatus(this, 'Selesai')" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                        <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        Selesai
                    </button>
                </div>

                <!-- Footer Modal (Aksi) -->
                <div class="flex items-center justify-between mt-4">
                    <button type="button" class="text-xs font-bold text-gray-500 hover:text-gray-800 transition pl-2">Reset</button>
                    <button type="submit" class="bg-[#6B6D2F] hover:bg-[#5a5c27] text-white px-8 py-3 rounded-full text-xs font-bold transition-colors shadow-md">
                        Terapkan
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Script untuk interaksi Modal -->
    <script>
        // Fungsi untuk mengganti status aktif pada Pill Buttons
        function selectStatus(clickedElement, statusValue) {
            // Update input hidden untuk form submit
            document.getElementById('selectedStatus').value = statusValue;

            // Reset semua tombol ke style inactive
            const allBtns = document.querySelectorAll('.status-btn');
            allBtns.forEach(btn => {
                btn.className = "status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all";
                btn.querySelector('.check-icon').classList.add('hidden'); // Sembunyikan centang
            });

            // Set tombol yang diklik ke style active
            clickedElement.className = "status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-[#6B6D2F] text-[#6B6D2F] bg-transparent text-xs font-bold transition-all";
            clickedElement.querySelector('.check-icon').classList.remove('hidden'); // Munculkan centang
        }

        // Fungsi untuk menutup modal sementara (Simulasi)
        document.getElementById('closeBtn').addEventListener('click', function() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('main-content').classList.remove('blur-sm', 'pointer-events-none');
        });
    </script>
</body>
</html>     