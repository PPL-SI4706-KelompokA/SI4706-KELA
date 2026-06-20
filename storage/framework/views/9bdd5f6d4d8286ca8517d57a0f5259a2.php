<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Makanan - FoodShare</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full py-6 px-12 flex items-center justify-between bg-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
        <div class="flex space-x-8 font-semibold text-sm">
            <a href="<?php echo e(route('donasi.daftar')); ?>" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="<?php echo e(route('donasi.cari')); ?>" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
            <a href="#" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
        </div>
        <div class="flex items-center space-x-6">
            <!-- Ikon Notifikasi -->
            <?php
                $notifCount = auth()->check()
                    ? \App\Models\Notifikasi::where('id_user', auth()->id())->where('status_baca', 0)->count()
                    : 0;
                $notifList = auth()->check()
                    ? \App\Models\Notifikasi::where('id_user', auth()->id())->latest()->take(6)->get()
                    : collect();
                $iconMap = [
                    'Permintaan Baru'       => ['emoji' => '💬', 'bg' => 'bg-blue-50'],
                    'Permintaan Disetujui'  => ['emoji' => '🎁', 'bg' => 'bg-yellow-50'],
                    'Permintaan Ditolak'    => ['emoji' => '❌', 'bg' => 'bg-red-50'],
                ];
            ?>
            <div class="relative" id="notif-wrapper">
                <button type="button" onclick="document.getElementById('notif-dropdown').classList.toggle('hidden'); event.stopPropagation();" class="relative text-gray-500 hover:text-[#5B5C35] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <?php if($notifCount > 0): ?>
                        <span id="notif-badge" class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center px-1 shadow"><?php echo e($notifCount); ?></span>
                    <?php else: ?>
                        <span id="notif-badge" class="hidden absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center px-1 shadow"></span>
                    <?php endif; ?>
                </button>

                <!-- Dropdown Panel -->
                <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-3xl shadow-2xl border border-gray-100 z-50 overflow-hidden" style="box-shadow: 0 20px 60px rgba(0,0,0,0.12);">
                    <div class="px-6 pt-5 pb-4">
                        <h3 class="font-extrabold text-lg text-gray-800">Notifikasi</h3>
                    </div>
                    <div id="notif-list" class="px-3 pb-4 space-y-1 max-h-72 overflow-y-auto">
                        <?php $__empty_1 = true; $__currentLoopData = $notifList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $icon = $iconMap[$notif->tipe_notifikasi] ?? ['emoji' => '🔔', 'bg' => 'bg-gray-50'];
                        ?>
                        <div class="flex items-start gap-3 px-3 py-3 rounded-2xl <?php echo e($notif->status_baca == 0 ? 'bg-[#FAFAEB]' : ''); ?> hover:bg-gray-50 transition cursor-pointer">
                            <div class="w-10 h-10 <?php echo e($icon['bg']); ?> rounded-2xl flex items-center justify-center text-lg flex-shrink-0">
                                <?php echo e($icon['emoji']); ?>

                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 leading-snug"><?php echo e($notif->pesan); ?></p>
                                <p class="text-[10px] text-gray-400 mt-0.5"><?php echo e(\Carbon\Carbon::parse($notif->tanggal_notifikasi)->diffForHumans()); ?></p>
                            </div>
                            <?php if($notif->status_baca == 0): ?>
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="py-8 text-center">
                            <div class="text-3xl mb-2">🔔</div>
                            <p class="text-xs text-gray-400 font-medium">Belum ada notifikasi</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if(auth()->check() && auth()->user()->role === 'Penerima'): ?>
                <a href="<?php echo e(route('penerima.riwayatpenerimaan')); ?>" class="hover:opacity-80 transition-opacity" title="Riwayat Penerimaan">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('donasi.riwayat')); ?>" class="hover:opacity-80 transition-opacity" title="Riwayat Donasi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </a>
            <?php endif; ?>
            <div class="w-10 h-10 rounded-full border-2 border-[#FCD34D] overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=<?php echo e(auth()->user()->nama ?? 'User'); ?>&background=FCD34D&color=5B5C35" class="w-full h-full object-cover">
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow px-12 py-8 flex flex-col lg:flex-row gap-12 items-start justify-center">
        
        <!-- Left Side: Food Detail -->
        <div class="w-full lg:w-1/2 max-w-2xl">
            <div class="relative rounded-[40px] overflow-hidden shadow-sm mb-8 bg-white h-[450px]">
                <img src="<?php echo e($donasi->foto_url ?? 'https://via.placeholder.com/800x600'); ?>" alt="Food Image" class="w-full h-full object-cover">
                <span class="absolute top-6 left-6 px-6 py-2.5 rounded-full text-sm font-bold bg-[#FCD34D] text-[#5B5C35]">
                    <?php echo e($donasi->jumlah ?? '3'); ?> porsi tersedia
                </span>
            </div>

            <h2 class="text-5xl font-extrabold text-[#7C7E3A] mb-6 leading-tight"><?php echo e($donasi->nama_makanan ?? 'Nasi Goreng Spesial'); ?></h2>
            
            <div class="flex flex-wrap gap-3 mb-8">
                <div class="flex items-center px-6 py-2 bg-[#E4E5C8] bg-opacity-40 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kadaluarsa: <?php echo e(\Carbon\Carbon::parse($donasi->tanggal_kadaluarsa ?? '2026-01-11')->format('d/m/Y')); ?>

                </div>
                <div class="flex items-center px-6 py-2 bg-[#E4E5C8] bg-opacity-40 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Jl. Dago, Bandung
                </div>
            </div>

            <div class="bg-white rounded-[32px] p-6 flex items-center shadow-sm border border-gray-50">
                <div class="w-14 h-14 rounded-full bg-[#FCD34D] flex items-center justify-center font-bold text-xl text-[#5B5C35] mr-4 shadow-inner">
                    BS
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Donatur</p>
                    <p class="text-xl font-bold text-gray-800">Budi Santoso</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Action Form -->
        <div class="w-full lg:w-[450px] bg-white rounded-[48px] p-10 shadow-sm border border-gray-50">
            <?php if(!auth()->check() || auth()->user()->role !== 'Penerima'): ?>
                <h3 class="text-2xl font-extrabold text-[#6B6D2F] mb-6">Status Donasi</h3>

                <form action="<?php echo e(route('donasi.update-status', $donasi->id_donasi ?? 1)); ?>" method="POST" id="statusForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    
                    <!-- Input Hidden untuk menyimpan status yang dipilih -->
                    <input type="hidden" name="status_donasi" id="selectedStatus" value="<?php echo e($donasi->status_donasi ?? 'Available'); ?>">

                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3">Status Donasi</label>

                    <!-- Pilihan Status (Pills) -->
                    <div class="flex items-center space-x-2 mb-10">
                        <!-- Option: Tersedia -->
                        <button type="button" onclick="selectStatus(this, 'Available')" id="btn-Available" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                            <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Tersedia
                        </button>
                        
                        <!-- Option: Dipesan -->
                        <button type="button" onclick="selectStatus(this, 'Booked')" id="btn-Booked" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                            <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Dipesan
                        </button>

                        <!-- Option: Selesai -->
                        <button type="button" onclick="selectStatus(this, 'Distributed')" id="btn-Distributed" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                            <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Selesai
                        </button>
                    </div>

                    <!-- Footer Action -->
                    <div class="flex items-center justify-between mt-4">
                        <button type="button" class="text-xs font-bold text-gray-500 hover:text-gray-800 transition pl-2">Reset</button>
                        <button type="submit" class="bg-[#6B6D2F] hover:bg-[#5a5c27] text-white px-10 py-3.5 rounded-full text-sm font-bold transition-colors shadow-md">
                            Terapkan
                        </button>
                    </div>
                </form>

                <script>
                    function selectStatus(clickedElement, statusValue) {
                        document.getElementById('selectedStatus').value = statusValue;
                        const allBtns = document.querySelectorAll('.status-btn');
                        allBtns.forEach(btn => {
                            btn.className = "status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all";
                            btn.querySelector('.check-icon').classList.add('hidden');
                        });
                        clickedElement.className = "status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-[#6B6D2F] text-[#6B6D2F] bg-transparent text-xs font-bold transition-all";
                        clickedElement.querySelector('.check-icon').classList.remove('hidden');
                    }
                    
                    // Set active status on load
                    document.addEventListener("DOMContentLoaded", function() {
                        const currentStatus = document.getElementById('selectedStatus').value || 'Available';
                        const activeBtn = document.getElementById('btn-' + currentStatus);
                        if (activeBtn) selectStatus(activeBtn, currentStatus);
                    });
                </script>

                <div class="mt-8 flex items-start text-[11px] font-semibold text-gray-400 leading-relaxed">
                    <svg class="w-5 h-5 mr-2 text-[#71B58C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>Status "Distributed" berarti makanan telah sukses diambil oleh penerima.</p>
                </div>

            <?php else: ?>
                <!-- Tampilan Khusus Penerima: Form Pemesanan (FR06) -->
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Informasi Permintaan</h3>
                <p class="text-gray-400 text-sm font-medium mb-8 leading-relaxed">Silakan lengkapi data di bawah ini untuk menerima donasi makanan ini.</p>

                <form action="<?php echo e(route('donasi.pesan', $donasi->id_donasi ?? 1)); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Porsi yang Diminta</label>
                        <input type="number" name="jumlah_permintaan" placeholder="Contoh: 1" 
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima</label>
                        <input type="text" name="nama_penerima" placeholder="Masukkan nama lengkap" 
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" placeholder="08xx xxxx xxxx" 
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#FCD34D] text-[#5B5C35] font-extrabold rounded-full hover:bg-[#fbc316] transition-all shadow-md mt-4">
                        Kirim Permintaan
                    </button>
                </form>

                <div class="mt-8 flex items-start text-[11px] font-semibold text-gray-400 leading-relaxed">
                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>Donasi ini bersifat sukarela. Mohon segera ambil setelah permintaan disetujui untuk menjaga kualitas makanan.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 px-12 flex justify-between items-center text-[10px] font-bold text-gray-400 border-t border-[#E4E5C8] mt-12">
        <div>© 2026 FoodShare</div>
        <div class="flex space-x-6 uppercase tracking-widest">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>


<script>
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notif-dropdown');
        const wrapper = document.getElementById('notif-wrapper');
        if (dropdown && wrapper && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Polling notifikasi setiap 15 detik
    <?php if(auth()->guard()->check()): ?>
    const iconMap = {
        'Permintaan Baru': { emoji: '💬', bg: 'bg-blue-50' },
        'Permintaan Disetujui': { emoji: '🎁', bg: 'bg-yellow-50' },
        'Permintaan Ditolak': { emoji: '❌', bg: 'bg-red-50' },
    };

    function timeAgo(dateStr) {
        const diff = Math.floor((Date.now() - new Date(dateStr)) / 60000);
        if (diff < 1) return 'Baru saja';
        if (diff < 60) return diff + ' menit lalu';
        if (diff < 1440) return Math.floor(diff/60) + ' jam lalu';
        return Math.floor(diff/1440) + ' hari lalu';
    }

    function pollNotifikasi() {
        fetch('/notifikasi/check')
            .then(r => r.json())
            .then(data => {
                // Update badge
                const badge = document.getElementById('notif-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                // Update daftar item
                const list = document.getElementById('notif-list');
                if (list && data.items.length > 0) {
                    list.innerHTML = data.items.map(n => {
                        const icon = iconMap[n.tipe_notifikasi] || { emoji: '🔔', bg: 'bg-gray-50' };
                        const unread = n.status_baca == 0;
                        return `<div class="flex items-start gap-3 px-3 py-3 rounded-2xl ${unread ? 'bg-[#FAFAEB]' : ''} hover:bg-gray-50 transition cursor-pointer">
                            <div class="w-10 h-10 ${icon.bg} rounded-2xl flex items-center justify-center text-lg flex-shrink-0">${icon.emoji}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 leading-snug">${n.pesan}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">${timeAgo(n.tanggal_notifikasi)}</p>
                            </div>
                            ${unread ? '<div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>' : ''}
                        </div>`;
                    }).join('');
                }
            });
    }

    // Poll langsung saat load, lalu tiap 15 detik
    pollNotifikasi();
    setInterval(pollNotifikasi, 15000);
    <?php endif; ?>
</script>
</body>
</html><?php /**PATH C:\SI4706-KELA\resources\views/penerima/create.blade.php ENDPATH**/ ?>