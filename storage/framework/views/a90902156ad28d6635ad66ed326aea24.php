<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare Admin - Manajemen Pengguna</title>
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
                <a href="<?php echo e(route('admin.manajemen')); ?>" class="flex items-center px-8 py-3 bg-[#FCF8E3] text-[#6B630C] border-r-4 border-[#FCD34D] font-bold text-sm">
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
                <a href="<?php echo e(route('admin.statistik')); ?>" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
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
            <form action="<?php echo e(route('admin.manajemen')); ?>" method="GET" class="relative w-96">
                <?php if(request('role')): ?>
                    <input type="hidden" name="role" value="<?php echo e(request('role')); ?>">
                <?php endif; ?>
                <?php if(request('status')): ?>
                    <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                <?php endif; ?>
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search user by name or email..." class="w-full bg-white rounded-full py-3 pl-12 pr-6 text-sm font-medium border-none shadow-sm focus:ring-2 focus:ring-[#FCD34D] outline-none">
            </form>
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
        <div class="px-10 py-4 flex-1 flex flex-col">
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl flex items-center gap-3 animate-fade-in shadow-sm">
                    <span class="text-xl">✅</span>
                    <span class="font-semibold text-sm"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-center gap-3 animate-fade-in shadow-sm">
                    <span class="text-xl">⚠️</span>
                    <span class="font-semibold text-sm"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>
            <div class="mb-10">
                <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight mb-2">Manajemen Pengguna</h2>
                <p class="text-gray-500 text-sm font-medium">Monitor and manage the heartbeat of our community. Oversee profiles for both food donors and recipients.</p>
            </div>

            <!-- Filters -->
            <div class="flex justify-between items-center mb-10">
                <div class="bg-white rounded-full p-1.5 flex shadow-sm border border-gray-50 animate-fade-in">
                    <a href="<?php echo e(route('admin.manajemen', array_merge(request()->query(), ['role' => 'Donatur']))); ?>" 
                       class="px-8 py-2 font-bold text-sm rounded-full transition <?php echo e(request('role', 'Donatur') === 'Donatur' ? 'bg-[#FCF8E3] text-[#6B630C] shadow-sm border border-gray-100' : 'text-gray-500 hover:bg-gray-50'); ?>">
                       Donatur
                    </a>
                    <a href="<?php echo e(route('admin.manajemen', array_merge(request()->query(), ['role' => 'Penerima']))); ?>" 
                       class="px-8 py-2 font-bold text-sm rounded-full transition <?php echo e(request('role') === 'Penerima' ? 'bg-[#FCF8E3] text-[#6B630C] shadow-sm border border-gray-100' : 'text-gray-500 hover:bg-gray-50'); ?>">
                       Penerima
                    </a>
                </div>
                <div class="flex items-center space-x-3 text-sm font-bold">
                    <span class="text-gray-400 mr-2 tracking-widest text-[10px] uppercase">Filter:</span>
                    <a href="<?php echo e(route('admin.manajemen', array_merge(request()->query(), ['status' => 'all']))); ?>" 
                       class="px-6 py-2 rounded-full transition <?php echo e(request('status', 'all') === 'all' ? 'bg-[#FCD34D] text-[#6B630C] shadow-sm' : 'bg-[#EAEBCA] text-gray-500 hover:bg-[#dcdcaa]'); ?>">
                       Semua Status
                    </a>
                    <a href="<?php echo e(route('admin.manajemen', array_merge(request()->query(), ['status' => 'aktif']))); ?>" 
                       class="px-6 py-2 rounded-full transition <?php echo e(request('status') === 'aktif' ? 'bg-[#FCD34D] text-[#6B630C] shadow-sm' : 'bg-[#EAEBCA] text-gray-500 hover:bg-[#dcdcaa]'); ?>">
                       Aktif
                    </a>
                    <a href="<?php echo e(route('admin.manajemen', array_merge(request()->query(), ['status' => 'nonaktif']))); ?>" 
                       class="px-6 py-2 rounded-full transition <?php echo e(request('status') === 'nonaktif' ? 'bg-[#FCD34D] text-[#6B630C] shadow-sm' : 'bg-[#EAEBCA] text-gray-500 hover:bg-[#dcdcaa]'); ?>">
                       Nonaktif
                    </a>
                </div>
            </div>

            <!-- User Grid -->
            <div class="grid grid-cols-3 gap-6 mb-10">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="user-card bg-white rounded-[32px] p-6 shadow-sm border border-gray-50 hover:border-[#FCD34D] hover:shadow-md hover:scale-[1.01] transition-all cursor-pointer"
                     data-id="<?php echo e($user->id_user); ?>"
                     data-nama="<?php echo e($user->nama); ?>"
                     data-email="<?php echo e($user->email); ?>"
                     data-role="<?php echo e($user->role); ?>"
                     data-telp="<?php echo e($user->no_telp ?? '-'); ?>"
                     data-alamat="<?php echo e($user->alamat ?? '-'); ?>"
                     data-status="<?php echo e($user->status_verifikasi === 'Sudah Verifikasi' ? 'Aktif' : ($user->status_verifikasi === 'Diblokir' ? 'Diblokir' : 'Nonaktif')); ?>">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->nama)); ?>&background=FCD34D&color=6B630C" class="w-14 h-14 rounded-full mr-4 border-2 border-white shadow-sm">
                            <div>
                                <h4 class="font-extrabold text-gray-800"><?php echo e($user->nama); ?></h4>
                                <p class="text-xs font-medium text-gray-400"><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                        <div class="relative">
                            <button class="gear-btn text-gray-300 hover:text-gray-500 focus:outline-none p-1 rounded-lg hover:bg-gray-50 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></button>
                            <!-- Dropdown Menu -->
                            <div class="gear-dropdown hidden absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-20 transition-all duration-200">
                                <?php if($user->id_user != auth()->id()): ?>
                                    <?php if($user->status_verifikasi === 'Diblokir'): ?>
                                        <form action="<?php echo e(route('admin.manajemen.unban', $user->id_user)); ?>" method="POST" class="w-full m-0">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-green-600 hover:bg-green-50 font-bold transition flex items-center gap-2 border-none bg-transparent cursor-pointer">
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Aktifkan Akun
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('admin.manajemen.ban', $user->id_user)); ?>" method="POST" class="w-full m-0">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-[#E53E3E] hover:bg-red-50 font-bold transition flex items-center gap-2 border-none bg-transparent cursor-pointer">
                                                <svg class="w-4 h-4 text-[#E53E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                Ban Akun
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="px-4 py-2 text-xs font-semibold text-gray-400">Akun Anda Sendiri</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-50 pt-4">
                        <span class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Peran: <?php echo e($user->role); ?></span>
                        <?php if($user->status_verifikasi === 'Sudah Verifikasi'): ?>
                            <span class="text-[10px] font-bold text-[#4CAF50] bg-[#E8F5E9] px-3 py-1 rounded-full uppercase tracking-wider">Aktif</span>
                        <?php elseif($user->status_verifikasi === 'Diblokir'): ?>
                            <span class="text-[10px] font-bold text-red-500 bg-red-50 px-3 py-1 rounded-full uppercase tracking-wider">Diblokir</span>
                        <?php else: ?>
                            <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full uppercase tracking-wider">Nonaktif</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-3 bg-white/40 rounded-[32px] p-12 text-center text-gray-500 font-semibold text-sm">
                    Tidak ada pengguna yang ditemukan.
                </div>
                <?php endif; ?>
            </div>
 
            <!-- Pagination & Footer -->
            <div class="flex flex-col items-center mt-auto pb-4">
                <div class="mb-8">
                    <?php echo e($users->appends(request()->query())->links()); ?>

                </div>
                <p class="text-[10px] text-gray-400 font-medium tracking-wide">© 2026 FoodShare Admin Portal. Building stronger communities through shared tables.</p>
            </div>
        </div>
    </main>

    <!-- User Detail Modal -->
    <div id="user-detail-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" id="modal-backdrop"></div>
        
        <!-- Modal Content Container -->
        <div class="relative bg-white rounded-[32px] w-full max-w-md p-8 shadow-2xl z-10 transform scale-95 opacity-0 transition-all duration-300" id="modal-content">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Detail Pengguna</h3>
                <button id="close-modal-btn" class="p-2 hover:bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition border-none outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="flex flex-col items-center mb-8">
                <img id="modal-avatar" src="" class="w-24 h-24 rounded-full border-4 border-[#FCD34D]/20 shadow-md mb-4">
                <h4 id="modal-nama" class="text-2xl font-extrabold text-gray-800 text-center"></h4>
                <div id="modal-role-container">
                    <span id="modal-role-badge" class="inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-2"></span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">📧</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat Email</p>
                        <p id="modal-email" class="text-sm font-semibold text-gray-700 break-all"></p>
                    </div>
                </div>
                
                <div class="bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">📞</div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor Telepon</p>
                        <p id="modal-telp" class="text-sm font-semibold text-gray-700"></p>
                    </div>
                </div>
                
                <div class="bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">📍</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat Lengkap</p>
                        <p id="modal-alamat" class="text-sm font-semibold text-gray-700 leading-relaxed"></p>
                    </div>
                </div>

                <div class="bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm shadow-sm shrink-0">🛡️</div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Status Akun</p>
                        <span id="modal-status" class="inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1"></span>
                    </div>
                </div>
            </div>

            <!-- Delete Form -->
            <form id="delete-user-form" action="" method="POST" class="mt-6 border-t border-gray-100 pt-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="button" id="delete-user-btn" class="w-full py-3 px-6 rounded-2xl bg-red-50 text-red-600 hover:bg-red-100 font-bold text-sm tracking-wide transition border-none cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1H10a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus Pengguna
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('user-detail-modal');
            const backdrop = document.getElementById('modal-backdrop');
            const modalContent = document.getElementById('modal-content');
            const closeBtn = document.getElementById('close-modal-btn');
            
            // Delete Form elements
            const deleteForm = document.getElementById('delete-user-form');
            const deleteBtn = document.getElementById('delete-user-btn');
            const currentUserId = <?php echo e(auth()->id() ?? 'null'); ?>;
            
            // Modal elements to fill
            const mAvatar = document.getElementById('modal-avatar');
            const mNama = document.getElementById('modal-nama');
            const mEmail = document.getElementById('modal-email');
            const mTelp = document.getElementById('modal-telp');
            const mAlamat = document.getElementById('modal-alamat');
            const mRoleBadge = document.getElementById('modal-role-badge');
            const mStatus = document.getElementById('modal-status');
            
            function openModal(data) {
                // Populate fields
                mNama.textContent = data.nama;
                mEmail.textContent = data.email;
                mTelp.textContent = data.telp || '-';
                mAlamat.textContent = data.alamat || '-';
                
                // Avatar
                mAvatar.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.nama)}&background=FCD34D&color=6B630C&size=128`;
                
                // Role Badge styling
                mRoleBadge.textContent = data.role;
                if (data.role === 'Donatur') {
                    mRoleBadge.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-2 bg-amber-100 text-amber-800 border border-amber-200';
                } else if (data.role === 'Penerima') {
                    mRoleBadge.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-2 bg-blue-100 text-blue-800 border border-blue-200';
                } else {
                    mRoleBadge.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-2 bg-gray-100 text-gray-800 border border-gray-200';
                }
                
                // Status Badge styling
                mStatus.textContent = data.status;
                if (data.status === 'Aktif') {
                    mStatus.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1 bg-green-100 text-[#4CAF50]';
                } else if (data.status === 'Diblokir') {
                    mStatus.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1 bg-red-100 text-red-600';
                } else {
                    mStatus.className = 'inline-block text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1 bg-gray-200 text-gray-600';
                }
                
                // Manage delete form action and visibility
                if (data.id && parseInt(data.id) === currentUserId) {
                    deleteForm.classList.add('hidden');
                } else {
                    deleteForm.classList.remove('hidden');
                    deleteForm.action = `/admin/manajemen/user/${data.id}`;
                }
                
                // Show modal with animation
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
            
            function closeModal() {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
            
            // Toggle dropdowns
            document.querySelectorAll('.gear-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = this.nextElementSibling;
                    // Close all other dropdowns
                    document.querySelectorAll('.gear-dropdown').forEach(d => {
                        if (d !== dropdown) d.classList.add('hidden');
                    });
                    dropdown.classList.toggle('hidden');
                });
            });

            // Close dropdowns on click outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.relative')) {
                    document.querySelectorAll('.gear-dropdown').forEach(d => {
                        d.classList.add('hidden');
                    });
                }
            });
            
            // Card clicks
            document.querySelectorAll('.user-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Prevent trigger when clicking inner buttons or dropdowns
                    if (e.target.closest('button') || e.target.closest('a') || e.target.closest('.gear-dropdown')) {
                        return;
                    }
                    
                    const data = {
                        id: this.getAttribute('data-id'),
                        nama: this.getAttribute('data-nama'),
                        email: this.getAttribute('data-email'),
                        role: this.getAttribute('data-role'),
                        telp: this.getAttribute('data-telp'),
                        alamat: this.getAttribute('data-alamat'),
                        status: this.getAttribute('data-status')
                    };
                    
                    openModal(data);
                });
            });
            
            // Delete confirmation
            deleteBtn.addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin menghapus pengguna ini? Semua data terkait (donasi, permintaan) akan dihapus secara permanen.')) {
                    deleteForm.submit();
                }
            });
            
            // Close handlers
            closeBtn.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>>
<?php /**PATH C:\SI4706-KELA\resources\views/admin/manajemen.blade.php ENDPATH**/ ?>