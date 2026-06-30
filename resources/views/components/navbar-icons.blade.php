<div class="flex items-center space-x-6 text-[#85884B]">
    <!-- Ikon Notifikasi -->
    @php
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
            'Maintenance'           => ['emoji' => '⚠️', 'bg' => 'bg-amber-50'],
            'Informasi'             => ['emoji' => '📢', 'bg' => 'bg-blue-50'],
        ];
    @endphp
    <div class="relative" id="notif-wrapper">
        <button type="button" onclick="document.getElementById('notif-dropdown').classList.toggle('hidden'); event.stopPropagation();" class="relative text-gray-500 hover:text-[#5B5C35] transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            @if($notifCount > 0)
                <span id="notif-badge" class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center px-1 shadow">{{ $notifCount }}</span>
            @else
                <span id="notif-badge" class="hidden absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center px-1 shadow"></span>
            @endif
        </button>

        <!-- Dropdown Panel -->
        <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-3xl shadow-2xl border border-gray-100 z-50 overflow-hidden" style="box-shadow: 0 20px 60px rgba(0,0,0,0.12);">
            <div class="px-6 pt-5 pb-4">
                <h3 class="font-extrabold text-lg text-gray-800">Notifikasi</h3>
            </div>
            <div id="notif-list" class="px-3 pb-4 space-y-1 max-h-72 overflow-y-auto">
                @forelse($notifList as $notif)
                @php
                    $icon = $iconMap[$notif->tipe_notifikasi] ?? ['emoji' => '🔔', 'bg' => 'bg-gray-50'];
                    $redirectUrl = $notif->id_permintaan 
                        ? route('permintaan.show', $notif->id_permintaan) 
                        : (($notif->tipe_notifikasi === 'Permintaan Baru' && auth()->user() && auth()->user()->role === 'Admin') 
                            ? route('admin.verifikasi') 
                            : 'javascript:void(0)');
                @endphp
                <a href="javascript:void(0)" onclick="markAsRead({{ $notif->id_notifikasi }}, this, '{{ $redirectUrl }}')" class="notif-item flex items-start gap-3 px-3 py-3 rounded-2xl {{ $notif->status_baca == 0 ? 'bg-[#FAFAEB] notif-unread' : '' }} hover:bg-gray-50 transition cursor-pointer">
                    <div class="w-10 h-10 {{ $icon['bg'] }} rounded-2xl flex items-center justify-center text-lg flex-shrink-0">
                        {{ $icon['emoji'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $notif->pesan }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5 realtime-time" data-timestamp="{{ $notif->created_at->toIso8601String() }}">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    @if($notif->status_baca == 0)
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    @endif
                </a>
                @empty
                <div class="py-8 text-center">
                    <div class="text-3xl mb-2">🔔</div>
                    <p class="text-xs text-gray-400 font-medium">Belum ada notifikasi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- History Icon -->
    @if(auth()->check() && auth()->user()->role === 'Penerima')
        <a href="{{ route('penerima.riwayatpenerimaan') }}" class="hover:opacity-80 transition-opacity" title="Riwayat Penerimaan">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </a>
    @else
        <a href="{{ route('donasi.riwayat') }}" class="hover:opacity-80 transition-opacity" title="Riwayat Donasi">
            <svg class="w-6 h-6 text-[#FCD34D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </a>
    @endif
    <!-- User Profile -->
    <a href="{{ route('profile.show') }}" class="w-10 h-10 rounded-full border-2 border-[#FCD34D] overflow-hidden cursor-pointer block">
        <img src="{{ auth()->user()->foto_url ?: 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama ?? 'User') . '&background=FCD34D&color=5B5C35' }}" alt="Profile" class="w-full h-full object-cover">
    </a>
</div>

@once
<script>
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notif-dropdown');
        const wrapper = document.getElementById('notif-wrapper');
        if (dropdown && wrapper && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    @auth
    const iconMap = {
        'Permintaan Baru': { emoji: '💬', bg: 'bg-blue-50' },
        'Permintaan Disetujui': { emoji: '🎁', bg: 'bg-yellow-50' },
        'Permintaan Ditolak': { emoji: '❌', bg: 'bg-red-50' },
        'Maintenance': { emoji: '⚠️', bg: 'bg-amber-50' },
        'Informasi': { emoji: '📢', bg: 'bg-blue-50' },
    };

    function timeAgo(dateStr) {
        const diff = Math.floor((Date.now() - new Date(dateStr)) / 60000);
        if (diff < 1) return 'Baru saja';
        if (diff < 60) return diff + ' menit lalu';
        if (diff < 1440) return Math.floor(diff/60) + ' jam lalu';
        return Math.floor(diff/1440) + ' hari lalu';
    }

    function markAsRead(id, element, redirectUrl) {
        if (!element.classList.contains('notif-unread')) {
            if (redirectUrl && redirectUrl !== 'javascript:void(0)') {
                window.location.href = redirectUrl;
            }
            return;
        }

        fetch('/notifikasi/' + id + '/read')
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    element.classList.remove('bg-[#FAFAEB]');
                    element.classList.remove('notif-unread');
                    
                    const blueDot = element.querySelector('.bg-blue-500');
                    if (blueDot) blueDot.remove();
                    
                    const badge = document.getElementById('notif-badge');
                    if (badge) {
                        let count = parseInt(badge.textContent) - 1;
                        if (count > 0) {
                            badge.textContent = count;
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                }
                if (redirectUrl && redirectUrl !== 'javascript:void(0)') {
                    window.location.href = redirectUrl;
                }
            })
            .catch(err => {
                if (redirectUrl && redirectUrl !== 'javascript:void(0)') {
                    window.location.href = redirectUrl;
                }
            });
    }

    function pollNotifikasi() {
        fetch('/notifikasi/check')
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('notif-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                const list = document.getElementById('notif-list');
                if (list) {
                    if (data.items.length > 0) {
                        list.innerHTML = data.items.map(n => {
                            const icon = iconMap[n.tipe_notifikasi] || { emoji: '🔔', bg: 'bg-gray-50' };
                            const unread = n.status_baca == 0;
                            let redirectUrl = 'javascript:void(0)';
                            if (n.id_permintaan) {
                                redirectUrl = '/permintaan/' + n.id_permintaan;
                            } else if (n.tipe_notifikasi === 'Permintaan Baru' && {{ auth()->user() && auth()->user()->role === 'Admin' ? 'true' : 'false' }}) {
                                redirectUrl = '{{ route('admin.verifikasi') }}';
                            }
                            return `<a href="javascript:void(0)" onclick="markAsRead(${n.id_notifikasi}, this, '${redirectUrl}')" class="notif-item flex items-start gap-3 px-3 py-3 rounded-2xl ${unread ? 'bg-[#FAFAEB] notif-unread' : ''} hover:bg-gray-50 transition cursor-pointer">
                                <div class="w-10 h-10 ${icon.bg} rounded-2xl flex items-center justify-center text-lg flex-shrink-0">${icon.emoji}</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 leading-snug">${n.pesan}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5 realtime-time" data-timestamp="${n.created_at}">${timeAgo(n.created_at)}</p>
                                </div>
                                ${unread ? '<div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>' : ''}
                            </a>`;
                        }).join('');
                        if (window.updateRealTimeTimestamps) {
                            window.updateRealTimeTimestamps();
                        }
                    } else {
                        list.innerHTML = `
                            <div class="py-8 text-center">
                                <div class="text-3xl mb-2">🔔</div>
                                <p class="text-xs text-gray-400 font-medium">Belum ada notifikasi</p>
                            </div>
                        `;
                    }
                }
            });
    }

    pollNotifikasi();
    setInterval(pollNotifikasi, 4000);
    @endauth
</script>
@endonce
