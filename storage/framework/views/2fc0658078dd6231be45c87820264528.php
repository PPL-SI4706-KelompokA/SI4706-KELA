<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Daftar</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-[#F8F8E6] text-[#2E3015] antialiased min-h-screen flex flex-col">

    <nav class="w-full py-6 px-8 lg:px-16 flex items-center justify-between">
        <a href="<?php echo e(route('donasi.daftar')); ?>" class="text-2xl font-extrabold tracking-tight text-[#5B5C35]">FoodShare</a>
        <div class="flex items-center space-x-6 font-semibold text-sm">
            <a href="<?php echo e(route('login')); ?>" class="text-[#5B5C35] hover:text-black transition">Masuk</a>
            <a href="<?php echo e(route('register')); ?>" class="bg-[#FCD34D] text-[#5B5C35] px-6 py-2.5 rounded-full shadow-sm">Daftar</a>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4 md:p-8">
        <div class="max-w-5xl w-full bg-[#F3F4E0] rounded-[40px] shadow-sm flex flex-col md:flex-row overflow-hidden">
            
            <!-- Kiri -->
            <div class="w-full md:w-1/2 bg-[#EEF0D5] p-10 lg:p-14 flex flex-col justify-between hidden md:flex">
                <div>
                    <h2 class="text-4xl font-extrabold text-[#5B5C35] leading-tight mb-4">Savor the<br>Connection.</h2>
                    <p class="text-[#85884B] font-medium text-sm max-w-sm">Join our community of home chefs, urban gardeners, and food lovers sharing more than just a meal.</p>
                </div>
                <div class="mt-6">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Bread" class="w-full h-56 object-cover rounded-[32px] shadow-md mb-6">
                    <p class="text-center font-bold text-[#5B5C35] text-sm px-4">"A neighborhood kitchen project that feels like home."</p>
                </div>
            </div>

            <!-- Kanan (Form Registrasi) -->
            <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-center items-center py-12">
                <div class="w-12 h-12 bg-[#FCD34D] rounded-full flex items-center justify-center font-bold text-xl text-[#5B5C35] mb-4">F</div>
                <h3 class="text-2xl font-bold text-[#2E3015] mb-1">Membuat Akun</h3>
                <p class="text-[#85884B] text-sm mb-6 text-center">Bergabung dengan komunitas berbagi makanan kami</p>

                <!-- Menampilkan Error Validasi -->
                <?php if($errors->any()): ?>
                    <div class="w-full max-w-sm bg-red-100 text-red-700 p-4 rounded-xl mb-4 text-xs font-semibold border border-red-200">
                        <ul class="list-disc pl-5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(url('/register')); ?>" method="POST" class="w-full max-w-sm flex flex-col gap-3">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="name" placeholder="Nama Lengkap" class="w-full bg-white rounded-full px-5 py-3.5 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none" required>
                    
                    <input type="email" name="email" placeholder="Email Address" class="w-full bg-white rounded-full px-5 py-3.5 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none" required>
                    
                    <input type="tel" name="phone" placeholder="Nomor Telepon" class="w-full bg-white rounded-full px-5 py-3.5 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none" required>
                    
                    <input type="text" name="address" placeholder="Alamat" class="w-full bg-white rounded-full px-5 py-3.5 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none" required>
                    
                    <!-- Dropdown Peran -->
                    <div class="relative">
                        <select name="role" class="w-full bg-white rounded-full px-5 py-3.5 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none appearance-none text-gray-500 cursor-pointer" required>
                            <option value="" disabled selected>Peran</option>
                            <option value="Donatur">Donatur (Pemberi Makanan)</option>
                            <option value="Penerima">Penerima (Membutuhkan Makanan)</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <input type="password" name="password" placeholder="Password" class="w-full bg-white rounded-full px-5 py-3.5 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none" required>
                    
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full bg-white rounded-full px-5 py-3.5 mb-2 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none" required>
                    
                    <button type="submit" class="w-full bg-[#FCD34D] text-[#5B5C35] font-bold py-3.5 rounded-full hover:bg-yellow-400 transition shadow-sm mt-2">Registrasi</button>
                </form>

                <p class="text-sm font-medium text-gray-500 text-center mt-6">
                    Sudah Punya Akun?<br>
                    <a href="<?php echo e(route('login')); ?>" class="text-[#5B5C35] font-bold hover:underline">Login</a>
                </p>
            </div>
        </div>
    </main>

    <footer class="w-full py-6 px-8 lg:px-16 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 font-medium border-t border-[#EAEBCA]">
        <div>&copy; 2026 FoodShare</div>
        <div class="flex space-x-6 mt-4 md:mt-0">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>
</body>
</html><?php /**PATH D:\SI4706-KELA\SI4706-KELA\resources\views/auth/register.blade.php ENDPATH**/ ?>