<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan - FoodShare</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F8EC; color: #333; }
        .chat-scroll::-webkit-scrollbar { width: 6px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #E4E5C8; border-radius: 10px; }
        .chat-scroll::-webkit-scrollbar-thumb:hover { background: #dcdcaa; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <?php if(auth()->user()->role === 'Admin' || auth()->user()->role === 'admin'): ?>
        <!-- Admin Layout Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between py-6 shrink-0 h-screen">
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
                    <a href="<?php echo e(route('admin.statistik')); ?>" class="flex items-center px-8 py-3 text-gray-500 hover:bg-[#F8F8EC] hover:text-[#6B630C] font-semibold text-sm transition-colors">
                        <svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        Statistik
                    </a>
                </nav>
            </div>
            <div class="px-8">
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden"><?php echo csrf_field(); ?></form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center text-gray-400 hover:text-gray-600 text-sm font-semibold mb-8">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Sign Out
                </a>
            </div>
        </aside>
    <?php endif; ?>

    <div class="flex-grow flex flex-col h-screen overflow-hidden w-full">
        <?php if(auth()->user()->role !== 'Admin' && auth()->user()->role !== 'admin'): ?>
            <!-- User Layout Navbar -->
            <nav class="w-full py-6 px-12 flex items-center justify-between shrink-0 bg-transparent">
                <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]"><a href="<?php echo e(route('donasi.daftar')); ?>">FoodShare</a></div>
                <div class="hidden md:flex space-x-8 font-semibold text-sm">
                    <a href="<?php echo e(route('donasi.daftar')); ?>" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
                    <a href="<?php echo e(route('donasi.cari')); ?>" class="text-gray-500 hover:text-[#5B5C35] transition">Donasi</a>
                    <a href="<?php echo e(route('pesan.index')); ?>" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Pesan</a>
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
        <?php else: ?>
            <!-- Admin Topbar -->
            <header class="flex items-center justify-between px-10 py-6 shrink-0 bg-transparent">
                <div class="text-2xl font-extrabold text-gray-800 tracking-tight">FoodShare Admin Portal</div>
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
                    <a href="<?php echo e(route('profile.show')); ?>">
                        <img src="<?php echo e(auth()->user()->foto_profil ? asset('storage/' . auth()->user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=FCD34D&color=6B630C&size=128'); ?>" class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                    </a>
                </div>
            </header>
        <?php endif; ?>

        <!-- Main Chat Wrapper -->
        <div class="flex-1 flex px-10 pb-8 overflow-hidden gap-6">
            
            <!-- Contact Sidebar -->
            <div class="w-80 bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 flex flex-col shrink-0">
                <h3 class="text-xl font-extrabold text-gray-800 tracking-tight mb-4">Pesan</h3>
                
                <!-- Search input -->
                <div class="relative mb-6">
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" id="contact-search" placeholder="Cari percakapan..." class="w-full bg-[#F8F8EC] rounded-full py-2.5 pl-11 pr-4 text-xs font-semibold border-none focus:ring-2 focus:ring-[#FCD34D] outline-none">
                </div>

                <!-- Contacts list -->
                <div class="flex-1 overflow-y-auto chat-scroll space-y-2" id="contacts-container">
                    <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="contact-item flex items-center justify-between p-3.5 rounded-2xl cursor-pointer hover:bg-[#F8F8EC]/50 transition duration-200 border border-transparent <?php echo e($activeContact && $activeContact->id_user == $contact->id_user ? 'bg-[#FCF8E3] border-[#FCD34D]/30 active-contact' : ''); ?>" 
                             data-id="<?php echo e($contact->id_user); ?>"
                             data-nama="<?php echo e($contact->nama); ?>"
                             data-role="<?php echo e($contact->role); ?>"
                             data-avatar="<?php echo e($contact->foto_profil ? asset('storage/' . $contact->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($contact->nama) . '&background=FCD34D&color=6B630C&size=128'); ?>">
                            <div class="flex items-center min-w-0 flex-1">
                                <img src="<?php echo e($contact->foto_profil ? asset('storage/' . $contact->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($contact->nama) . '&background=FCD34D&color=6B630C&size=128'); ?>" class="w-11 h-11 rounded-full object-cover mr-3 shrink-0">
                                <div class="min-w-0 flex-1">
                                    <div class="flex justify-between items-start mb-0.5">
                                        <h4 class="text-sm font-bold text-gray-800 truncate mr-1"><?php echo e($contact->nama); ?></h4>
                                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-wide px-1.5 py-0.5 rounded bg-gray-50 border border-gray-100 shrink-0"><?php echo e($contact->role); ?></span>
                                    </div>
                                    <p class="text-xs text-gray-400 truncate font-semibold pr-4">
                                        <?php echo e($contact->last_message ? $contact->last_message->pesan : 'Mulai chat baru...'); ?>

                                    </p>
                                </div>
                            </div>
                            <!-- Unread dot or counter -->
                            <?php if($contact->unread_count > 0): ?>
                                <span class="w-5 h-5 bg-[#D97706] text-white text-[9px] font-bold rounded-full flex items-center justify-center shrink-0 ml-1 shadow-sm"><?php echo e($contact->unread_count); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="py-12 text-center text-gray-400 font-semibold text-xs">
                            Tidak ada percakapan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Chat Room Window -->
            <div class="flex-1 bg-white rounded-[32px] shadow-sm border border-gray-100 flex flex-col overflow-hidden relative">
                
                <?php if($activeContact): ?>
                    <!-- Active Chat Header -->
                    <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between shrink-0">
                        <div class="flex items-center">
                            <img id="chat-header-avatar" src="<?php echo e($activeContact->foto_profil ? asset('storage/' . $activeContact->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($activeContact->nama) . '&background=FCD34D&color=6B630C&size=128'); ?>" class="w-12 h-12 rounded-full object-cover mr-4">
                            <div>
                                <h3 id="chat-header-nama" class="text-base font-extrabold text-gray-800 leading-tight"><?php echo e($activeContact->nama); ?></h3>
                                <span id="chat-header-role" class="text-[9px] font-extrabold text-[#6B630C] bg-[#FCF8E3] border border-[#FCD34D]/30 px-2.5 py-0.5 rounded-full uppercase tracking-wider block mt-1 w-max"><?php echo e($activeContact->role); ?></span>
                            </div>
                        </div>
                        
                        <!-- Delete Conversation Button -->
                        <button type="button" id="delete-conversation-btn" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-xl hover:bg-red-50 flex items-center justify-center cursor-pointer border-none bg-transparent" title="Hapus Percakapan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>

                    <!-- Chat History Pane -->
                    <div id="chat-messages-pane" class="flex-grow p-8 overflow-y-auto chat-scroll space-y-4 bg-[#F9F9FA]/40">
                        <!-- Messages populated dynamically via JS -->
                    </div>

                    <!-- Chat Input form -->
                    <form id="chat-send-form" class="p-6 border-t border-gray-50 flex items-center gap-4 shrink-0 bg-white">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id_penerima" id="chat-recipient-id" value="<?php echo e($activeContact->id_user); ?>">
                        <input type="text" name="pesan" id="chat-message-input" autocomplete="off" placeholder="Tulis pesan Anda..." required class="flex-grow bg-[#F8F8EC] rounded-2xl py-4 px-6 text-sm font-semibold border-none focus:ring-2 focus:ring-[#FCD34D] outline-none">
                        
                        <button type="submit" class="bg-[#6B630C] text-white hover:opacity-95 p-4 rounded-2xl shadow-md transition shrink-0 border-none cursor-pointer flex items-center justify-center">
                            <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Placeholder Welcome screen -->
                    <div class="flex-grow flex flex-col items-center justify-center p-12 text-center select-none">
                        <div class="w-20 h-20 bg-[#F8F8EC] rounded-full flex items-center justify-center text-4xl mb-4 border border-gray-100 shadow-inner">💬</div>
                        <h3 class="text-lg font-extrabold text-gray-800 tracking-tight">Kotak Masuk Percakapan</h3>
                        <p class="text-xs text-gray-400 font-medium max-w-sm mt-1 leading-relaxed">Pilih percakapan dari panel kiri untuk mengirim pesan atau hubungi pengguna melalui detail donasi mereka.</p>
                    </div>
                <?php endif; ?>
                
            </div>
            
        </div>
    </div>

    <?php if($activeContact): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentUserId = <?php echo e(auth()->id()); ?>;
            const chatMessagesPane = document.getElementById('chat-messages-pane');
            const chatSendForm = document.getElementById('chat-send-form');
            const chatMessageInput = document.getElementById('chat-message-input');
            const chatRecipientInput = document.getElementById('chat-recipient-id');
            const contactSearch = document.getElementById('contact-search');
            
            let currentRecipientId = chatRecipientInput ? parseInt(chatRecipientInput.value) : null;
            let pollingInterval = null;

            // Scroll helper
            function scrollToBottom() {
                chatMessagesPane.scrollTop = chatMessagesPane.scrollHeight;
            }

            // Client-side contacts search filter
            if (contactSearch) {
                contactSearch.addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    document.querySelectorAll('.contact-item').forEach(item => {
                        const name = item.getAttribute('data-nama').toLowerCase();
                        if (name.includes(query)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Render message bubble helper
            function appendBubble(msg, isSender) {
                const bubble = document.createElement('div');
                bubble.className = `flex ${isSender ? 'justify-end' : 'justify-start'}`;
                
                const time = new Date(msg.created_at);
                const formatTime = time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
                
                bubble.innerHTML = `
                    <div class="max-w-[70%] rounded-2xl px-5 py-3.5 shadow-sm text-sm font-semibold transition ${
                        isSender 
                            ? 'bg-[#FCF8E3] text-[#6B630C] border border-[#FCD34D]/30 rounded-tr-none' 
                            : 'bg-white text-gray-700 border border-gray-100 rounded-tl-none'
                    }">
                        <p class="break-all whitespace-pre-wrap leading-relaxed">${escapeHtml(msg.pesan)}</p>
                        <span class="text-[9px] text-gray-400 mt-1.5 block text-right font-bold tracking-wider">${formatTime}</span>
                    </div>
                `;
                chatMessagesPane.appendChild(bubble);
            }

            function escapeHtml(text) {
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Fetch and render messages
            let lastMessageId = 0;
            function loadMessages(silent = false) {
                if (!currentRecipientId) return;

                fetch(`/pesan/conversation/${currentRecipientId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const messages = data.messages;
                            
                            // If silent poll, check if we got new messages
                            if (silent) {
                                if (messages.length > 0 && messages[messages.length - 1].id_pesan !== lastMessageId) {
                                    chatMessagesPane.innerHTML = '';
                                    messages.forEach(msg => {
                                        appendBubble(msg, msg.id_pengirim === currentUserId);
                                    });
                                    lastMessageId = messages[messages.length - 1].id_pesan;
                                    scrollToBottom();
                                }
                            } else {
                                chatMessagesPane.innerHTML = '';
                                messages.forEach(msg => {
                                    appendBubble(msg, msg.id_pengirim === currentUserId);
                                });
                                if (messages.length > 0) {
                                    lastMessageId = messages[messages.length - 1].id_pesan;
                                }
                                scrollToBottom();
                            }
                        }
                    });
            }

            // Swap active chat from sidebar clicks
            document.querySelectorAll('.contact-item').forEach(item => {
                item.addEventListener('click', function() {
                    const contactId = parseInt(this.getAttribute('data-id'));
                    const name = this.getAttribute('data-nama');
                    const role = this.getAttribute('data-role');
                    const avatar = this.getAttribute('data-avatar');

                    // Highlight active contact
                    document.querySelectorAll('.contact-item').forEach(ci => ci.classList.remove('bg-[#FCF8E3]', 'border-[#FCD34D]/30', 'active-contact'));
                    this.classList.add('bg-[#FCF8E3]', 'border-[#FCD34D]/30', 'active-contact');

                    // Update route URL parameter without reloading
                    history.pushState(null, '', `/pesan?user=${contactId}`);

                    // Update Chat Header details
                    document.getElementById('chat-header-avatar').src = avatar;
                    document.getElementById('chat-header-nama').textContent = name;
                    document.getElementById('chat-header-role').textContent = role;
                    chatRecipientInput.value = contactId;

                    currentRecipientId = contactId;
                    lastMessageId = 0;
                    loadMessages(false);
                });
            });

            // Send message form handler
            if (chatSendForm) {
                chatSendForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const msgText = chatMessageInput.value.trim();
                    if (!msgText || !currentRecipientId) return;

                    const formData = new FormData(this);

                    // Clear input instantly
                    chatMessageInput.value = '';
                    
                    // Optimistic rendering
                    const tempMsg = {
                        id_pesan: Date.now(),
                        id_pengirim: currentUserId,
                        id_penerima: currentRecipientId,
                        pesan: msgText,
                        created_at: new Date().toISOString()
                    };
                    appendBubble(tempMsg, true);
                    scrollToBottom();

                    fetch('/pesan/send', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(async response => {
                        if (!response.ok) {
                            if (response.status === 419) {
                                throw new Error('Sesi Anda telah kedaluwarsa atau berubah (mungkin Anda login ke akun lain di tab lain). Silakan muat ulang halaman.');
                            }
                            try {
                                const errData = await response.json();
                                if (errData.errors) {
                                    const firstKey = Object.keys(errData.errors)[0];
                                    throw new Error(errData.errors[firstKey][0]);
                                }
                                if (errData.message) {
                                    throw new Error(errData.message);
                                }
                            } catch (e) {
                                throw new Error(e.message || `Gagal mengirim pesan (Server Error: ${response.status})`);
                            }
                        }
                        return response.json();
                    })

                    .then(data => {
                        if (data.success) {
                            // Update lastMessageId with server value
                            lastMessageId = data.message.id_pesan;
                        } else {
                            throw new Error('Gagal mengirim pesan.');
                        }
                    })
                    .catch(err => {
                        alert(err.message);
                        window.location.reload();
                    });

                });
            }

            // Delete conversation handler
            const deleteConversationBtn = document.getElementById('delete-conversation-btn');
            if (deleteConversationBtn) {
                deleteConversationBtn.addEventListener('click', function() {
                    const recipientName = document.getElementById('chat-header-nama').textContent;
                    if (confirm(`Apakah Anda yakin ingin menghapus seluruh percakapan dengan ${recipientName}? Semua pesan akan dihapus secara permanen.`)) {
                        fetch(`/pesan/conversation/${currentRecipientId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal menghapus percakapan.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                window.location.href = '/pesan';
                            } else {
                                throw new Error(data.message || 'Gagal menghapus percakapan.');
                            }
                        })
                        .catch(err => {
                            alert(err.message);
                        });
                    }
                });
            }


            // Initial load & launch Polling Loop
            loadMessages(false);
            pollingInterval = setInterval(() => {
                loadMessages(true);
            }, 2500);

            // Clean up polling interval if page is navigated away
            window.addEventListener('beforeunload', function() {
                if (pollingInterval) clearInterval(pollingInterval);
            });
        });
    </script>
    <?php endif; ?>

</body>
</html>
<?php /**PATH C:\SI4706-KELA\resources\views/pesan/index.blade.php ENDPATH**/ ?>