<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Tambah Donasi</title>
    <!-- Memanggil Tailwind CSS hasil compile dari Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full py-6 px-8 flex items-center justify-between">
        <div class="text-2xl font-extrabold tracking-tight">FoodShare</div>
        <div class="hidden md:flex space-x-8 font-semibold text-sm">
            <!-- Pastikan route 'donasi.daftar' dan 'donasi.cari' ini sudah ada di web.php -->
            <a href="<?php echo e(route('donasi.daftar')); ?>" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="<?php echo e(route('donasi.cari')); ?>" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
            <a href="<?php echo e(route('pesan.index')); ?>" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
        </div>
        <?php if (isset($component)) { $__componentOriginal9cb2107a5d38b4b37edd0574b941dc2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9cb2107a5d38b4b37edd0574b941dc2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar-icons','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar-icons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9cb2107a5d38b4b37edd0574b941dc2f)): ?>
<?php $attributes = $__attributesOriginal9cb2107a5d38b4b37edd0574b941dc2f; ?>
<?php unset($__attributesOriginal9cb2107a5d38b4b37edd0574b941dc2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9cb2107a5d38b4b37edd0574b941dc2f)): ?>
<?php $component = $__componentOriginal9cb2107a5d38b4b37edd0574b941dc2f; ?>
<?php unset($__componentOriginal9cb2107a5d38b4b37edd0574b941dc2f); ?>
<?php endif; ?>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-8 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 py-8">
        
        <!-- Kolom Kiri: Teks & Tips -->
        <div class="lg:col-span-5 flex flex-col justify-center">
            <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                Berbagi<br>Kebaikan,<br>Mulai dari<br>Sini.
            </h1>
            <p class="text-gray-600 text-sm lg:text-base leading-relaxed mb-10 pr-4">
                Setiap porsi yang Anda bagikan membantu mengurangi limbah makanan dan memberi makan mereka yang membutuhkan di komunitas kita.
            </p>

            <!-- Card Tips Donasi -->
            <div class="bg-[#EEF0D5] p-6 rounded-3xl relative overflow-hidden max-w-sm">
                <div class="relative z-10">
                    <h3 class="text-xs font-bold tracking-widest text-[#85884B] uppercase mb-2">Tips Donasi</h3>
                    <p class="text-sm font-medium text-[#5B5C35]">
                        Pastikan makanan masih layak konsumsi dan dikemas dengan rapi untuk menjaga kebersihan.
                    </p>
                </div>
                <!-- Dekorasi Background Card -->
                <svg class="absolute -bottom-6 -right-6 w-32 h-32 text-[#E4E7C4] opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </div>
        </div>

        <!-- Kolom Kanan: Form Donasi -->
        <div class="lg:col-span-7">
            <div class="bg-[#F0F1D9] p-8 lg:p-10 rounded-[40px] shadow-sm">
                
                <!-- Pesan Error Validasi (Akan muncul kotak merah jika data ada yang salah) -->
                <?php if($errors->any()): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl mb-5">
                        <strong class="font-bold text-sm">Cek lagi datanya ya!</strong>
                        <ul class="mt-1 list-disc list-inside text-xs">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('donasi.tambah')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Foto Makanan -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-[#85884B] mb-2">Foto Makanan</label>
                        <div id="image-upload-box" class="w-full bg-white/60 border-2 border-dashed border-gray-300 rounded-3xl p-8 flex flex-col items-center justify-center cursor-pointer hover:bg-white transition relative min-h-[200px] overflow-hidden">
                            <!-- Placeholder Content -->
                            <div id="upload-placeholder" class="flex flex-col items-center justify-center text-center">
                                <div class="w-12 h-12 bg-[#FCD34D] rounded-full flex items-center justify-center mb-3 text-[#5B5C35]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <span class="font-bold text-sm text-[#5B5C35]">Klik untuk unggah foto</span>
                                <span class="text-xs text-gray-400 mt-1">Format JPG, PNG (Maks. 5MB)</span>
                            </div>
                            <!-- Preview Content -->
                            <img id="image-preview" src="" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview Foto Makanan">
                            <!-- Input file -->
                            <input type="file" name="foto_makanan" id="foto_makanan_input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                        </div>
                    </div>

                    <!-- Nama Makanan -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-[#85884B] mb-2">Nama Makanan</label>
                        <input type="text" name="nama_makanan" placeholder="Misal: Nasi Kotak Ayam Bakar" class="w-full bg-white rounded-2xl px-5 py-3.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] border-none shadow-sm" required>
                    </div>

                    <!-- Kategori Makanan -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-[#85884B] mb-2">Kategori Makanan</label>
                        <select name="kategori" class="w-full bg-white rounded-2xl px-5 py-3.5 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] border-none shadow-sm appearance-none cursor-pointer" required>
                            <option value="" disabled selected>Pilih kategori...</option>
                            <option value="Makanan Berat">Makanan Berat</option>
                            <option value="Cemilan / Snack">Cemilan / Snack</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Bahan Pokok">Bahan Pokok</option>
                        </select>
                    </div>

                    <!-- Jumlah Porsi & Kadaluarsa -->
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-[#85884B] mb-2">Jumlah Porsi</label>
                            <input type="number" name="jumlah" placeholder="0" class="w-full bg-white rounded-2xl px-5 py-3.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] border-none shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#85884B] mb-2">Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa" class="w-full bg-white rounded-2xl px-5 py-3.5 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] border-none shadow-sm" required>
                        </div>
                    </div>

                    <!-- Lokasi Pengambilan -->
                    <div class="mb-5 relative">
                        <label class="block text-xs font-bold text-[#85884B] mb-2">Lokasi Pengambilan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <input type="text" name="lokasi" placeholder="Masukkan alamat lengkap" class="w-full bg-white rounded-2xl pl-11 pr-5 py-3.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] border-none shadow-sm" required>
                        </div>
                    </div>

                    <!-- Deskripsi Tambahan -->
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-[#85884B] mb-2">Deskripsi Tambahan</label>
                        <textarea name="deskripsi" rows="3" placeholder="Ceritakan sedikit tentang donasi Anda (opsional)" class="w-full bg-white rounded-2xl px-5 py-3.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] border-none shadow-sm resize-none"></textarea>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="w-full bg-[#FCD34D] hover:bg-[#fbc629] transition duration-200 text-[#5B5C35] font-bold text-base py-4 rounded-full shadow-sm flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        <span>Kirim Donasi</span>
                    </button>
                    
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('foto_makanan_input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const placeholder = document.getElementById('upload-placeholder');
            const preview = document.getElementById('image-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });
    </script>
</body>
</html><?php /**PATH C:\SI4706-KELA\resources\views/donasi/create.blade.php ENDPATH**/ ?>