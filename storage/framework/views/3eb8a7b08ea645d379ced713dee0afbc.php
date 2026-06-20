<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Donasi - FoodShare</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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
            <a href="<?php echo e(route('donasi.daftar')); ?>" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="<?php echo e(route('donasi.cari')); ?>" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
            <a href="<?php echo e(route('pesan.index')); ?>" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
            <?php if(auth()->check() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'admin')): ?>
                <a href="<?php echo e(route('admin.statistik')); ?>" class="text-gray-500 hover:text-[#5B5C35] transition">Admin</a>
            <?php endif; ?>
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

    <!-- Search Section -->
    <header class="px-12 py-8">
        <h2 class="text-3xl font-extrabold mb-6">Cari Donasi Makanan</h2>
        
        <form action="<?php echo e(route('donasi.cari')); ?>" method="GET" class="relative max-w-full mb-6">
            <input type="text" name="q" value="<?php echo e($q ?? ''); ?>" placeholder="Cari Sekarang" 
                class="w-full py-4 px-6 rounded-2xl bg-white border-none shadow-sm focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
            <button type="submit" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>

        <div class="flex space-x-4 mb-10">
            <button onclick="toggleFilter()" class="px-8 py-2 bg-white rounded-full font-bold text-sm shadow-sm border border-gray-100 hover:bg-gray-50">
            Filter
            </button>
            <a href="<?php echo e(route('donasi.peta')); ?>" class="px-8 py-2 bg-white rounded-full font-bold text-sm shadow-sm border border-gray-100 flex items-center hover:bg-gray-50 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Lokasi
            </a>
        </div>
    </header>

    
    <?php if(!empty($kategori) || !empty($status)): ?>
    <div class="px-12 mb-4 flex flex-wrap gap-2 items-center">
        <span class="text-xs text-gray-400 font-semibold">Filter aktif:</span>
        <?php if(!empty($kategori)): ?>
            <span class="bg-[#FCD34D] text-[#5B5C35] text-xs font-bold px-4 py-1.5 rounded-full"><?php echo e($kategori); ?></span>
        <?php endif; ?>
        <?php $__currentLoopData = $status ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="bg-[#E4E5C8] text-[#5B5C35] text-xs font-bold px-4 py-1.5 rounded-full"><?php echo e($s); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('donasi.cari')); ?>" class="text-xs text-red-400 font-bold hover:underline ml-1">✕ Reset</a>
    </div>
    <?php endif; ?>

    <!-- Grid Hasil Pencarian -->
    <main class="px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php $__empty_1 = true; $__currentLoopData = $hasilPencarian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-50 flex flex-col">
            <div class="relative h-56">
                <img src="<?php echo e($item->foto_url ?: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'); ?>" class="w-full h-full object-cover">
                <?php if($item->jumlah <= 0 || $item->status_donasi === 'Distributed'): ?>
                    <span class="absolute top-4 right-4 px-4 py-1 rounded-full text-[10px] font-bold bg-gray-500 text-white uppercase">Habis</span>
                <?php else: ?>
                    <span class="absolute top-4 right-4 px-4 py-1 rounded-full text-[10px] font-bold bg-[#4CAF50] text-white uppercase">Tersedia</span>
                <?php endif; ?>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800 leading-tight"><?php echo e($item->nama_makanan); ?></h3>
                    <span class="text-sm font-bold text-[#7C7E3A]"><?php echo e($item->jumlah); ?> Porsi</span>
                </div>
                <div class="space-y-2 mb-6 text-[12px] font-medium text-gray-400 uppercase tracking-wide">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Exp: <?php echo e(\Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d M Y')); ?>

                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        Bandung
                    </div>
                </div>
                <a href="<?php echo e(route('donasi.pesan.form', $item->id_donasi)); ?>" class="block w-full py-3 text-center bg-[#FCD34D] text-[#5B5C35] font-bold rounded-2xl hover:bg-[#fbc316] transition-all shadow-sm">Lihat Detail</a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full py-20 text-center text-gray-400">Tidak ada hasil yang ditemukan untuk "<?php echo e($q); ?>".</div>
        <?php endif; ?>
    </main>

    <!-- Overlay Background (Blur) -->
<div id="filterModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-[#5B5C35] bg-opacity-40 backdrop-blur-sm px-6">
    
    <!-- Modal Box -->
    <div class="bg-[#F8F8E6] w-full max-w-md rounded-[48px] p-10 shadow-2xl relative border border-white border-opacity-50">
        
        <!-- Close Button -->
        <button onclick="toggleFilter()" class="px-8 py-2 bg-white rounded-full font-bold text-sm shadow-sm border border-gray-100">Filter</button>

        <h3 class="text-2xl font-extrabold text-[#5B5C35] mb-8 leading-tight">Filter<br>Makanan</h3>

        <form action="<?php echo e(route('donasi.cari')); ?>" method="GET">
            
            <input type="hidden" name="q" value="<?php echo e($q ?? ''); ?>">

            <!-- Kategori Makanan -->
            <div class="mb-8">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Kategori Makanan</p>
                <div class="flex flex-wrap gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="kategori" value="" class="hidden peer" <?php echo e(empty($kategori ?? '') ? 'checked' : ''); ?>>
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] peer-checked:text-[#5B5C35] transition-all block">Semua</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="kategori" value="Snack" class="hidden peer" <?php echo e(($kategori ?? '') === 'Snack' ? 'checked' : ''); ?>>
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] peer-checked:text-[#5B5C35] transition-all block">Snack</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="kategori" value="Makanan" class="hidden peer" <?php echo e(($kategori ?? '') === 'Makanan' ? 'checked' : ''); ?>>
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] peer-checked:text-[#5B5C35] transition-all block">Makanan</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="kategori" value="Minuman" class="hidden peer" <?php echo e(($kategori ?? '') === 'Minuman' ? 'checked' : ''); ?>>
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] peer-checked:text-[#5B5C35] transition-all block">Minuman</span>
                    </label>
                </div>
            </div>

            <!-- Status Donasi -->
            <div class="mb-12">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Status Donasi</p>
                <div class="flex flex-wrap gap-2">
                    <label class="cursor-pointer">
                        <input type="checkbox" name="status[]" value="Tersedia" class="hidden peer" <?php echo e(in_array('Tersedia', $status ?? []) ? 'checked' : ''); ?>>
                        <span class="px-6 py-2 rounded-full border-2 border-[#6B630C] text-[#6B630C] text-xs font-bold peer-checked:bg-[#F2F3E2] flex items-center transition-all">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Tersedia
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="status[]" value="Dipesan" class="hidden peer" <?php echo e(in_array('Dipesan', $status ?? []) ? 'checked' : ''); ?>>
                        <span class="px-6 py-2 rounded-full bg-[#E4E5C8] text-gray-500 text-xs font-bold peer-checked:bg-[#FCD34D] transition-all block border-2 border-transparent">Dipesan</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="status[]" value="Selesai" class="hidden peer" <?php echo e(in_array('Selesai', $status ?? []) ? 'checked' : ''); ?>>
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

    <!-- Floating Action Button (hanya untuk Donatur/non-Penerima) -->
    <?php if(!auth()->check() || auth()->user()->role !== 'Penerima'): ?>
    <a href="<?php echo e(route('donasi.tambah')); ?>" class="fixed bottom-10 right-12 px-8 py-4 bg-[#FCD34D] text-[#5B5C35] font-bold rounded-full shadow-lg flex items-center hover:scale-105 transition-transform">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
        Tambah Donasi
    </a>
    <?php endif; ?>

    <!-- Pagination Dinamis -->
    <?php if($hasilPencarian->lastPage() > 1): ?>
    <div class="flex justify-center mt-12 space-x-2">
        
        <?php if($hasilPencarian->onFirstPage()): ?>
            <span class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-300 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </span>
        <?php else: ?>
            <a href="<?php echo e($hasilPencarian->previousPageUrl()); ?>" class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </a>
        <?php endif; ?>

        
        <?php $__currentLoopData = $hasilPencarian->getUrlRange(1, $hasilPencarian->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($page == $hasilPencarian->currentPage()): ?>
                <span class="w-10 h-10 rounded-full bg-[#FCD34D] text-[#5B5C35] font-bold flex items-center justify-center"><?php echo e($page); ?></span>
            <?php else: ?>
                <a href="<?php echo e($url); ?>" class="w-10 h-10 rounded-full bg-white text-gray-500 font-bold border border-gray-100 flex items-center justify-center hover:bg-gray-50 transition"><?php echo e($page); ?></a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($hasilPencarian->hasMorePages()): ?>
            <a href="<?php echo e($hasilPencarian->nextPageUrl()); ?>" class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            </a>
        <?php else: ?>
            <span class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-300 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            </span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

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
</html><?php /**PATH C:\SI4706-KELA\resources\views/donasi/pencarian.blade.php ENDPATH**/ ?>