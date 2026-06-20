<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Dari Kelebihan Menjadi Harapan</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-[#F8F8E6] text-[#2E3015] antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full py-6 px-8 lg:px-16 flex items-center justify-between z-10">
        <div class="text-2xl font-extrabold tracking-tight text-[#5B5C35]">FoodShare</div>
        <div class="flex items-center space-x-6 font-semibold text-sm">
            <a href="<?php echo e(route('login')); ?>" class="text-[#5B5C35] hover:text-black transition">Masuk</a>
            <a href="<?php echo e(route('register')); ?>" class="bg-[#FCD34D] text-[#5B5C35] px-6 py-2.5 rounded-full hover:bg-yellow-400 transition shadow-sm">Daftar</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow flex flex-col lg:flex-row items-center justify-between max-w-7xl mx-auto px-8 lg:px-16 py-12 gap-12">
        
        <!-- Kiri: Teks -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center">
            
            <?php if(session('success')): ?>
                <div class="w-full bg-[#D1FAE5] text-[#065F46] p-4 rounded-2xl mb-6 text-sm font-bold border border-[#34D399] shadow-sm animate-pulse">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="inline-flex items-center gap-2 bg-[#EAEBCA] text-[#5B5C35] text-xs font-bold px-4 py-1.5 rounded-full w-max mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path></svg>
                KOMUNITAS BERBAGI TERPERCAYA
            </div>
            
            <h1 class="text-5xl lg:text-7xl font-extrabold leading-[1.1] tracking-tight mb-6 text-[#2E3015]">
                DARI KELEBIHAN<br>MENJADI HARAPAN
            </h1>
            
            <p class="text-[#5B5C35] text-lg mb-10 max-w-lg leading-relaxed font-medium">
                Rasakan makna berbagi makanan kepada mereka yang membutuhkan. Mudah, bermanfaat, dan membantu mengurangi pemborosan makanan di lingkungan kita.
            </p>
            
            <div class="flex flex-wrap gap-4">
                <a href="<?php echo e(route('donasi.tambah')); ?>" class="bg-[#FCD34D] text-[#5B5C35] font-bold px-8 py-3.5 rounded-full hover:bg-yellow-400 transition shadow-sm flex items-center gap-2">
                    DONASI MAKANAN <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="<?php echo e(route('donasi.cari')); ?>" class="border-2 border-[#5B5C35] text-[#5B5C35] font-bold px-8 py-3.5 rounded-full hover:bg-[#EAEBCA] transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    SEARCH
                </a>
            </div>
        </div>

        <!-- Kanan: Gambar -->
        <div class="w-full lg:w-1/2 relative flex justify-center lg:justify-end mt-10 lg:mt-0">
            <!-- Lingkaran Background Dekorasi -->
            <div class="absolute w-[450px] h-[450px] bg-[#EEF0D5] rounded-full -z-10 blur-xl opacity-60"></div>
            
            <!-- Gambar Utama -->
            <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Salad" class="w-[400px] h-[400px] object-cover rounded-full shadow-2xl border-8 border-white/50">
            
            <!-- Floating Card -->
            <div class="absolute bottom-4 left-4 lg:-left-10 bg-white p-5 rounded-3xl shadow-xl max-w-[220px]">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-[#FCD34D] p-2 rounded-full text-[#5B5C35]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                    </div>
                    <h3 class="font-bold text-sm text-[#2E3015] leading-tight">Donasi Hari Ini</h3>
                </div>
                <p class="text-[11px] text-gray-500 font-medium">Menu salad sehat telah didonasikan oleh Resto Hijau.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 px-8 lg:px-16 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 font-medium border-t border-[#EAEBCA] mt-auto">
        <div>&copy; 2026 FoodShare</div>
        <div class="flex space-x-6 mt-4 md:mt-0">
            <a href="#" class="hover:text-[#5B5C35]">Kebijakan Privasi</a>
            <a href="#" class="hover:text-[#5B5C35]">Ketentuan Layanan</a>
            <a href="#" class="hover:text-[#5B5C35]">Kontak</a>
        </div>
    </footer>
</body>
</html><?php /**PATH C:\SI4706-KELA\resources\views/welcome.blade.php ENDPATH**/ ?>