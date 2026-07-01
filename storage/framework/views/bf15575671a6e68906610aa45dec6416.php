<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare Admin - Statistik Donasi</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F8EC; color: #333; }</style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between py-6">
        <div>
            <div class="px-8 mb-10">
                <a href="<?php echo e(route('donasi.daftar')); ?>"><h1 class="text-xl font-extrabold text-[#6B630C] hover:opacity-80">FoodShare</h1></a>
                <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Editorial Admin</p>
            </div>
            <nav class="space-y-1">
                <a href="<?php echo e(route('admin.manajemen')); ?>" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Manajemen
                </a>
                <a href="<?php echo e(route('admin.laporan')); ?>" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Distribusi
                </a>
                <a href="<?php echo e(route('admin.verifikasi')); ?>" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi
                </a>
                <a href="<?php echo e(route('admin.pemberitahuan')); ?>" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    Pemberitahuan
                </a>
                <a href="<?php echo e(route('admin.statistik')); ?>" class="flex items-center px-8 py-3 bg-[#FCF8E3] text-[#6B630C] border-r-4 border-[#FCD34D] font-bold text-sm">
                    <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    Statistik
                </a>
            </nav>
        </div>
        <div class="px-8 mt-6">
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden"><?php echo csrf_field(); ?></form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center text-gray-400 hover:text-gray-600 text-sm font-semibold transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <!-- Topbar -->
        <header class="flex items-center justify-between px-10 py-6">
            <div class="relative w-96">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search user by name or email..." class="w-full bg-white rounded-full py-3 pl-12 pr-6 text-sm font-medium border-none shadow-sm focus:ring-2 focus:ring-[#FCD34D] outline-none">
            </div>
            <div class="flex items-center space-x-8">
                <div class="flex items-center space-x-4 text-gray-400">
                    <?php if (isset($component)) { $__componentOriginalce5a81bbdd9362f7b33481de91d221e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce5a81bbdd9362f7b33481de91d221e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-notifications','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-notifications'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce5a81bbdd9362f7b33481de91d221e2)): ?>
<?php $attributes = $__attributesOriginalce5a81bbdd9362f7b33481de91d221e2; ?>
<?php unset($__attributesOriginalce5a81bbdd9362f7b33481de91d221e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce5a81bbdd9362f7b33481de91d221e2)): ?>
<?php $component = $__componentOriginalce5a81bbdd9362f7b33481de91d221e2; ?>
<?php unset($__componentOriginalce5a81bbdd9362f7b33481de91d221e2); ?>
<?php endif; ?>
                    <button><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>
                    <a href="<?php echo e(route('profile.show')); ?>">
                        <img src="<?php echo e(auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama ?? 'Admin') . '&background=F97316&color=fff'); ?>" class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm hover:opacity-90 transition">
                    </a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="px-10 py-4">
            <!-- Page Title -->
            <div class="mb-8">
                <div class="flex items-center text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-1">
                    <span>Admin</span>
                    <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-[#6B630C]">Statistics</span>
                </div>
                <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Statistik<br>Donasi</h2>
            </div>

            <!-- Top Row: Total Donasi + Donatur -->
            <div class="grid grid-cols-3 gap-6 mb-6">

                <!-- Total Donasi Terkumpul (spans 2 cols) -->
                <div class="col-span-2 bg-[#6B630C] rounded-[32px] p-10 text-white relative overflow-hidden">
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                    <div class="flex items-center gap-3 mb-8 relative z-10">
                        <div class="w-10 h-10 bg-white/15 rounded-2xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="font-bold tracking-wide text-sm">Total Donasi Terkumpul</span>
                    </div>
                    <div class="flex items-end gap-4 mb-12 relative z-10">
                        <h3 class="text-8xl font-extrabold leading-none"><?php echo e(number_format($totalDonasiTerkumpul, 0, ',', '.')); ?></h3>
                        <span class="px-3 py-1.5 bg-white/20 rounded-full text-xs font-bold flex items-center mb-3">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> <?php echo e($growthText); ?>

                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-8 relative z-10 border-t border-white/20 pt-6">
                        <div>
                            <p class="text-[10px] font-bold text-white/50 tracking-widest uppercase mb-1">Target Bulanan</p>
                            <p class="text-lg font-bold"><?php echo e(number_format($targetBulanan, 0, ',', '.')); ?> <span class="text-xs font-normal text-white/70">Paket</span></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-white/50 tracking-widest uppercase mb-1">Status Keberhasilan</p>
                            <p class="text-lg font-bold"><?php echo e($statusKeberhasilan); ?>%</p>
                        </div>
                    </div>
                </div>

                <!-- Donatur Card -->
                <div class="col-span-1 bg-white rounded-[32px] p-8 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div class="w-12 h-12 bg-[#FCD34D] rounded-2xl flex items-center justify-center text-[#6B630C]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-bold text-gray-500 tracking-wider">DONATUR</span>
                    </div>
                    <div>
                        <h3 class="text-5xl font-extrabold text-gray-800 mt-6 mb-2"><?php echo e(number_format($totalDonatur, 0, ',', '.')); ?></h3>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium mb-6"><strong class="text-[#4CAF50]">+<?php echo e($donaturBaru); ?></strong> pendaftar baru yang bergabung dengan komunitas minggu ini.</p>
                        <div class="flex -space-x-2">
                            <?php $__currentLoopData = $topDonators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $donor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($i < 3): ?>
                                <?php
                                    $bubbleParts = explode(' ', trim($donor['nama']));
                                    $bubbleInit = count($bubbleParts) >= 2
                                        ? strtoupper(substr($bubbleParts[0], 0, 1) . substr($bubbleParts[1], 0, 1))
                                        : strtoupper(substr($donor['nama'], 0, 2));
                                    $bubbleColors = ['#8DB33A', '#FCD34D', '#E8934A'];
                                    $bubbleBg = $bubbleColors[$i];
                                    $bubbleText = ($bubbleBg === '#FCD34D') ? '#6B630C' : '#fff';
                                ?>
                                <?php if(!empty($donor['foto_profil'])): ?>
                                    <img src="<?php echo e(asset('storage/' . $donor['foto_profil'])); ?>"
                                         class="w-8 h-8 rounded-full object-cover border-2 border-white shrink-0">
                                <?php else: ?>
                                    <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center text-[9px] font-extrabold shrink-0"
                                         style="background-color: <?php echo e($bubbleBg); ?>; color: <?php echo e($bubbleText); ?>;">
                                        <?php echo e($bubbleInit); ?>

                                    </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donatur Teratas - Full Width -->
            <div class="bg-[#EAEBCA] rounded-[32px] p-8">
                <div class="flex justify-between items-center mb-6 px-2">
                    <h3 class="text-xl font-bold text-gray-800">Donatur Teratas</h3>
                    <div class="flex items-center gap-3">
                        <form method="GET" action="<?php echo e(route('admin.statistik')); ?>" class="flex items-center gap-3 bg-white rounded-full px-4 py-2 text-xs font-semibold shadow-sm border border-gray-100">
                            <div class="flex items-center gap-1.5">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[9px]">Dari</span>
                                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="bg-transparent border-none p-0 text-gray-700 outline-none w-28 focus:ring-0 text-xs">
                            </div>
                            <div class="h-4 w-px bg-gray-200"></div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[9px]">Sampai</span>
                                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="bg-transparent border-none p-0 text-gray-700 outline-none w-28 focus:ring-0 text-xs">
                            </div>
                            <button type="submit" class="bg-[#6B630C] text-white px-5 py-1.5 rounded-full font-bold hover:bg-opacity-90 transition text-xs">
                                Filter
                            </button>
                            <?php if(request('start_date') || request('end_date')): ?>
                                <a href="<?php echo e(route('admin.statistik')); ?>" class="text-gray-400 hover:text-red-500 font-bold text-xs">Reset</a>
                            <?php endif; ?>
                        </form>
                        <a href="<?php echo e(route('admin.manajemen')); ?>" class="text-sm font-bold text-[#6B630C] hover:underline flex items-center shrink-0">Lihat Semua <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></a>
                    </div>
                </div>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $topDonators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isTop = in_array($donor['rank'], ['01', '02']);
                        $bgClass = $isTop ? 'bg-white shadow-sm' : 'bg-white/60';
                        $statusColor = $donor['status'] === 'Sangat Aktif' ? '#4CAF50' : ($donor['status'] === 'Aktif Berkala' ? '#F59E0B' : '#9CA3AF');

                        // Generate 2-letter initials from name
                        $nameParts = explode(' ', trim($donor['nama']));
                        $initials = count($nameParts) >= 2
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                            : strtoupper(substr($donor['nama'], 0, 2));

                        // Avatar colors per rank
                        $avatarPalette = [
                            '01' => ['bg' => '#8DB33A', 'text' => '#fff'],
                            '02' => ['bg' => '#FCD34D', 'text' => '#6B630C'],
                            '03' => ['bg' => '#E8934A', 'text' => '#fff'],
                            '04' => ['bg' => '#4A90D9', 'text' => '#fff'],
                        ];
                        $avatarStyle = $avatarPalette[$donor['rank']] ?? ['bg' => '#9CA3AF', 'text' => '#fff'];
                        $hasFoto = !empty($donor['foto_profil']);
                    ?>
                    <div class="<?php echo e($bgClass); ?> rounded-[20px] p-4 px-6 flex items-center">
                        <span class="text-2xl font-bold text-gray-200 w-12"><?php echo e($donor['rank']); ?></span>

                        
                        <?php if($hasFoto): ?>
                            <img src="<?php echo e(asset('storage/' . $donor['foto_profil'])); ?>"
                                 class="w-11 h-11 rounded-full object-cover mr-4 shrink-0 border-2 border-white shadow-sm">
                        <?php else: ?>
                            <div class="w-11 h-11 rounded-full flex items-center justify-center mr-4 text-xs font-extrabold shrink-0"
                                 style="background-color: <?php echo e($avatarStyle['bg']); ?>; color: <?php echo e($avatarStyle['text']); ?>;">
                                <?php echo e($initials); ?>

                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm"><?php echo e($donor['nama']); ?></h4>
                            <p class="text-[10px] font-bold flex items-center" style="color: <?php echo e($statusColor); ?>;">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background-color: <?php echo e($statusColor); ?>;"></span>
                                <?php echo e($donor['status']); ?>

                            </p>
                        </div>
                        <div class="text-right">
                            <h4 class="font-extrabold text-xl text-gray-800"><?php echo e(number_format($donor['total_donasi'], 0, ',', '.')); ?></h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Paket Donasi</p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="bg-white/40 rounded-[20px] p-8 text-center text-gray-500 font-semibold text-sm">
                        Belum ada data donasi di dalam database.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
<?php /**PATH C:\SI4706-KELA\resources\views/admin/statistik.blade.php ENDPATH**/ ?>