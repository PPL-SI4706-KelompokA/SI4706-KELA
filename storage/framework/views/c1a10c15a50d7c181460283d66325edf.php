<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Masuk</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-[#F8F8E6] text-[#2E3015] antialiased min-h-screen flex flex-col">

    <nav class="w-full py-6 px-8 lg:px-16 flex items-center justify-between">
        <a href="<?php echo e(route('home')); ?>" class="text-2xl font-extrabold tracking-tight text-[#5B5C35]">FoodShare</a>
        <div class="flex items-center space-x-6 font-semibold text-sm">
            <a href="<?php echo e(route('login')); ?>" class="bg-[#FCD34D] text-[#5B5C35] px-6 py-2.5 rounded-full shadow-sm">Masuk</a>
            <a href="<?php echo e(route('register')); ?>" class="text-[#5B5C35] hover:text-black transition">Daftar</a>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4 md:p-8">
        <div class="max-w-5xl w-full bg-[#F3F4E0] rounded-[40px] shadow-sm flex flex-col md:flex-row overflow-hidden">
            
            <!-- Kiri -->
            <div class="w-full md:w-1/2 bg-[#EEF0D5] p-10 lg:p-14 flex flex-col justify-between">
                <div>
                    <h2 class="text-4xl font-extrabold text-[#5B5C35] leading-tight mb-4">Savor the<br>Connection.</h2>
                    <p class="text-[#85884B] font-medium text-sm max-w-sm">Join our community of home chefs, urban gardeners, and food lovers sharing more than just a meal.</p>
                </div>
                <div class="mt-10">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Bread" class="w-full h-64 object-cover rounded-[32px] shadow-md mb-6">
                    <p class="text-center font-bold text-[#5B5C35] text-sm px-4">"A neighborhood kitchen project that feels like home."</p>
                </div>
            </div>

            <!-- Kanan (Form Login) -->
            <div class="w-full md:w-1/2 p-10 lg:p-14 flex flex-col justify-center items-center">
                <div class="w-12 h-12 bg-[#FCD34D] rounded-full flex items-center justify-center font-bold text-xl text-[#5B5C35] mb-6">F</div>
                <h3 class="text-2xl font-bold text-[#2E3015] mb-1">Masukan Akun</h3>
                <p class="text-[#85884B] text-sm mb-6">Gunakan akun yang sudah terdaftar</p>
                
                <?php if(session('success')): ?>
                    <div class="w-full max-w-sm bg-[#D1FAE5] text-[#065F46] p-4 rounded-2xl mb-6 text-sm font-bold text-center border border-[#34D399]">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="w-full max-w-sm bg-red-100 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold text-center border border-red-200">
                        <?php echo e($errors->first()); ?>

                    </div>
                <?php endif; ?>

                <form action="<?php echo e(url('/login')); ?>" method="POST" class="w-full max-w-sm">
                    <?php echo csrf_field(); ?>
                    <input type="email" name="email" placeholder="Email Address" class="w-full bg-white rounded-full px-6 py-4 mb-4 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none" required>
                    <input type="password" name="password" placeholder="Password" class="w-full bg-white rounded-full px-6 py-4 mb-8 text-sm font-medium focus:ring-2 focus:ring-[#FCD34D] outline-none" required>
                    
                    <button type="submit" class="w-full bg-[#FCD34D] text-[#5B5C35] font-bold py-4 rounded-full hover:bg-yellow-400 transition shadow-sm mb-6">Masuk</button>
                </form>

                <p class="text-sm font-medium text-gray-500 text-center">
                    Belum Punya Akun?<br>
                    <a href="<?php echo e(route('register')); ?>" class="text-[#5B5C35] font-bold hover:underline">Registrasi</a>
                </p>
            </div>
        </div>
    </main>

    <footer class="w-full py-6 px-8 lg:px-16 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 font-medium">
        <div>&copy; 2026 FoodShare</div>
        <div class="flex space-x-6 mt-4 md:mt-0">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>
</body>
</html><?php /**PATH D:\SI4706-KELA\SI4706-KELA\resources\views/auth/login.blade.php ENDPATH**/ ?>