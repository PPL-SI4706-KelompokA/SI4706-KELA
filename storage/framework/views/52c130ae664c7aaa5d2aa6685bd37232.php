<?php
    $notifCount = auth()->check()
        ? \App\Models\Notifikasi::where('id_user', auth()->id())->where('status_baca', 0)->count()
        : 0;
    $notifList = auth()->check()
        ? \App\Models\Notifikasi::where('id_user', auth()->id())->latest()->take(6)->get()
        : collect();
    $iconMap = [
        'Permintaan Baru'       => ['emoji' => '👤', 'bg' => 'bg-[#FCF8E3] text-[#6B630C]'],
        'Permintaan Disetujui'  => ['emoji' => '✅', 'bg' => 'bg-green-50 text-green-700'],
        'Permintaan Ditolak'    => ['emoji' => '❌', 'bg' => 'bg-red-50 text-red-700'],
        'Maintenance'           => ['emoji' => '⚠️', 'bg' => 'bg-amber-100 text-amber-800'],
        'Informasi'             => ['emoji' => '📢', 'bg' => 'bg-blue-100 text-blue-800'],
    ];
?>
<div class="relative flex items-center" id="admin-notif-wrapper">
    <button type="button" onclick="document.getElementById('admin-notif-dropdown').classList.toggle('hidden'); event.stopPropagation();" class="relative text-gray-400 hover:text-[#6B630C] transition-colors bg-transparent border-none outline-none cursor-pointer p-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        <?php if($notifCount > 0): ?>
            <span id="admin-notif-badge" class="absolute -top-1 -right-1 min-w-[16px] h-[16px] bg-red-500 text-white text-[8px] font-bold rounded-full flex items-center justify-center px-1 shadow"><?php echo e($notifCount); ?></span>
        <?php else: ?>
            <span id="admin-notif-badge" class="hidden absolute -top-1 -right-1 min-w-[16px] h-[16px] bg-red-500 text-white text-[8px] font-bold rounded-full flex items-center justify-center px-1 shadow"></span>
        <?php endif; ?>
    </button>

    <!-- Dropdown Panel -->
    <div id="admin-notif-dropdown" class="hidden absolute right-0 top-full mt-3 w-80 bg-white rounded-3xl shadow-2xl border border-gray-100 z-50 overflow-hidden" style="box-shadow: 0 20px 60px rgba(0,0,0,0.12); color: #333;">
        <div class="px-6 pt-5 pb-4 border-b border-gray-50 text-left">
            <h3 class="font-extrabold text-sm text-gray-800">Notifikasi Admin</h3>
        </div>
        <div id="admin-notif-list" class="px-3 py-2 space-y-1 max-h-72 overflow-y-auto text-left">
            <?php $__empty_1 = true; $__currentLoopData = $notifList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $icon = $iconMap[$notif->tipe_notifikasi] ?? ['emoji' => '🔔', 'bg' => 'bg-gray-50 text-gray-500'];
            ?>
            <a href="<?php echo e($notif->id_permintaan ? route('notifikasi.redirect', $notif->id_notifikasi) : 'javascript:void(0)'); ?>" onclick="<?php echo e($notif->id_permintaan ? '' : 'markAdminAsRead(' . $notif->id_notifikasi . ', this)'); ?>" class="admin-notif-item flex items-start gap-3 px-3 py-3 rounded-2xl <?php echo e($notif->status_baca == 0 ? 'bg-[#FCF8E3]/40 admin-notif-unread' : ''); ?> hover:bg-gray-50 transition cursor-pointer">
                <div class="w-9 h-9 <?php echo e($icon['bg']); ?> rounded-xl flex items-center justify-center text-base flex-shrink-0">
                    <?php echo e($icon['emoji']); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-800 leading-snug"><?php echo e($notif->pesan); ?></p>
                    <p class="text-[9px] text-gray-400 mt-0.5 realtime-time" data-timestamp="<?php echo e($notif->created_at->toIso8601String()); ?>"><?php echo e($notif->created_at->diffForHumans()); ?></p>
                </div>
                <?php if($notif->status_baca == 0): ?>
                    <div class="w-1.5 h-1.5 bg-[#6B630C] rounded-full mt-1.5 flex-shrink-0"></div>
                <?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-8 text-center">
                <div class="text-2xl mb-2">🔔</div>
                <p class="text-xs text-gray-400 font-medium">Belum ada notifikasi baru</p>
            </div>
            <?php endif; ?>
        </div>
        <div class="p-4 bg-gray-50 text-center border-t border-gray-100">
            <a href="<?php echo e(route('admin.verifikasi')); ?>" class="text-[10px] font-bold text-[#6B630C] hover:underline uppercase tracking-wider">Buka Menu Verifikasi</a>
        </div>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('0664d830-4787-4c2b-a740-69dc3a2eb828')): $__env->markAsRenderedOnce('0664d830-4787-4c2b-a740-69dc3a2eb828'); ?>
<script>
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('admin-notif-dropdown');
        const wrapper = document.getElementById('admin-notif-wrapper');
        if (dropdown && wrapper && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    <?php if(auth()->guard()->check()): ?>
    const adminIconMap = {
        'Permintaan Baru': { emoji: '👤', bg: 'bg-[#FCF8E3] text-[#6B630C]' },
        'Permintaan Disetujui': { emoji: '✅', bg: 'bg-green-50 text-green-700' },
        'Permintaan Ditolak': { emoji: '❌', bg: 'bg-red-50 text-red-700' },
        'Maintenance': { emoji: '⚠️', bg: 'bg-amber-100 text-amber-800' },
        'Informasi': { emoji: '📢', bg: 'bg-blue-100 text-blue-800' },
    };

    function adminTimeAgo(dateStr) {
        const diff = Math.floor((Date.now() - new Date(dateStr)) / 60000);
        if (diff < 1) return 'Baru saja';
        if (diff < 60) return diff + ' menit lalu';
        if (diff < 1440) return Math.floor(diff/60) + ' jam lalu';
        return Math.floor(diff/1440) + ' hari lalu';
    }

    function markAdminAsRead(id, element) {
        if (!element.classList.contains('admin-notif-unread')) return;

        fetch('/notifikasi/' + id + '/read')
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    element.classList.remove('bg-[#FCF8E3]/40');
                    element.classList.remove('admin-notif-unread');
                    
                    const dot = element.querySelector('.bg-\\[\\#6B630C\\]');
                    if (dot) dot.remove();
                    
                    const badge = document.getElementById('admin-notif-badge');
                    if (badge) {
                        let count = parseInt(badge.textContent) - 1;
                        if (count > 0) {
                            badge.textContent = count;
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                }
            });
    }

    function pollAdminNotifikasi() {
        fetch('/notifikasi/check')
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('admin-notif-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                const list = document.getElementById('admin-notif-list');
                if (list && data.items.length > 0) {
                    list.innerHTML = data.items.map(n => {
                        const icon = adminIconMap[n.tipe_notifikasi] || { emoji: '🔔', bg: 'bg-gray-50 text-gray-500' };
                        const unread = n.status_baca == 0;
                        const href = n.id_permintaan ? `/notifikasi/${n.id_notifikasi}/redirect` : 'javascript:void(0)';
                        const onclick = n.id_permintaan ? '' : `markAdminAsRead(${n.id_notifikasi}, this)`;
                        return `<a href="${href}" onclick="${onclick}" class="admin-notif-item flex items-start gap-3 px-3 py-3 rounded-2xl ${unread ? 'bg-[#FCF8E3]/40 admin-notif-unread' : ''} hover:bg-gray-50 transition cursor-pointer">
                            <div class="w-9 h-9 ${icon.bg} rounded-xl flex items-center justify-center text-base flex-shrink-0">${icon.emoji}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-800 leading-snug">${n.pesan}</p>
                                <p class="text-[9px] text-gray-400 mt-0.5 realtime-time" data-timestamp="${n.created_at}">${adminTimeAgo(n.created_at)}</p>
                            </div>
                            ${unread ? '<div class="w-1.5 h-1.5 bg-[#6B630C] rounded-full mt-1.5 flex-shrink-0"></div>' : ''}
                        </a>`;
                    }).join('');
                    if (window.updateRealTimeTimestamps) {
                        window.updateRealTimeTimestamps();
                    }
                }
            });
    }

    pollAdminNotifikasi();
    setInterval(pollAdminNotifikasi, 15000);
    <?php endif; ?>
</script>
<?php endif; ?>
<?php /**PATH D:\SI4706-KELA\SI4706-KELA\resources\views/components/admin-notifications.blade.php ENDPATH**/ ?>