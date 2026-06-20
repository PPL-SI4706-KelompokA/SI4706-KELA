<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Riwayat Donasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full py-6 px-8 flex items-center justify-between">
        <div class="text-2xl font-extrabold tracking-tight text-[#85884B]">FoodShare</div>
        <div class="hidden md:flex space-x-8 font-semibold text-sm">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
            <a href="{{ route('pesan.index') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
            @if(auth()->check() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'admin'))
                <a href="{{ route('admin.statistik') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Admin</a>
            @endif
        </div>
        <x-navbar-icons />
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-5xl mx-auto px-4 sm:px-8 w-full py-8">


        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-[#85884B] mb-3">Riwayat Donasi</h1>
            <p class="text-gray-500 text-sm md:text-base max-w-lg">
                Lacak semua kebaikan yang telah Anda bagikan. Setiap porsi sangat berarti bagi mereka yang membutuhkan.
            </p>
        </div>

        <!-- Filter Buttons -->
        <div class="flex space-x-3 mb-10">
            <a href="{{ route('donasi.riwayat') }}" class="{{ empty($statusAktif) ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Semua</a>
            <a href="{{ route('donasi.riwayat', ['status' => 'Tersedia']) }}" class="{{ in_array($statusAktif, ['Tersedia', 'Available']) ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Tersedia</a>
            <a href="{{ route('donasi.riwayat', ['status' => 'Habis']) }}" class="{{ in_array($statusAktif, ['Habis', 'Distributed']) ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Habis</a>
        </div>

        <!-- Cards Container -->
        <div class="space-y-6">
            @forelse($riwayatDonasi as $item)
            <div class="bg-white p-3 rounded-[32px] shadow-sm flex flex-col md:flex-row gap-6">
                <!-- Image -->
                <div class="w-full md:w-64 h-48 relative shrink-0">
                    <img src="{{ $item->foto_url ?: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }}" alt="{{ $item->nama_makanan }}" class="w-full h-full object-cover rounded-[24px]">
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-bold px-3 py-1.5 rounded-full text-[#5B5C35] shadow-sm">
                        {{ $item->jumlah ?? 0 }} Porsi
                    </div>
                </div>
                <!-- Content -->
                <div class="flex-1 flex flex-col justify-center py-2 pr-4">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-bold text-gray-800">{{ $item->nama_makanan ?? 'Donasi Makanan' }}</h2>
                        @if($item->status_donasi == 'Available' || $item->status_donasi == 'Booked')
                            <span class="bg-[#D1FAE5] text-[#065F46] text-xs font-bold py-1 rounded-full inline-flex items-center justify-center w-24 text-center">
                                Tersedia
                            </span>
                        @elseif($item->status_donasi == 'Distributed')
                            <span class="bg-[#DBEAFE] text-[#1E40AF] text-xs font-bold py-1 rounded-full inline-flex items-center justify-center w-24 text-center">
                                Habis
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-xs font-bold py-1 rounded-full inline-flex items-center justify-center w-24 text-center">
                                {{ $item->status_donasi }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-500 font-medium mb-4">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Kedaluwarsa: {{ isset($item->tanggal_kadaluarsa) ? \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d M, H:i') : '-' }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Didonasikan: <span class="realtime-time" data-timestamp="{{ isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->toIso8601String() : '' }}">{{ isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->diffForHumans() : '-' }}</span>
                        </div>
                    </div>

                    <hr class="border-gray-100 my-2">

                    <div class="flex justify-end items-center mt-2">
                        <button type="button" onclick="openDetailModal({{ $item->id_donasi }})" class="text-[#85884B] font-bold text-sm flex items-center gap-1 hover:text-[#5B5C35] transition cursor-pointer bg-transparent border-none">
                            Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            @php
                $permintaansData = $item->permintaans->map(function($p) {
                    return [
                        'nama' => $p->user->nama ?? 'Tidak diketahui',
                        'jumlah' => $p->jumlah_permintaan ?? 1,
                        'status' => $p->status ?? 'Pending',
                        'catatan' => $p->catatan ?? '-',
                        'created_at' => $p->created_at ? $p->created_at->format('d M Y, H:i') : '-',
                        'avatar' => ($p->user && $p->user->foto_profil) ? asset('storage/' . $p->user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($p->user->nama ?? 'U') . '&background=FCD34D&color=5B5C35&size=64'
                    ];
                })->toArray();
            @endphp

            <!-- Hidden Detail Data for Modal -->
            <div id="detail-data-{{ $item->id_donasi }}" class="hidden"
                data-nama="{{ $item->nama_makanan }}"
                data-jumlah="{{ $item->jumlah }}"
                data-kategori="{{ $item->kategori ?? '-' }}"
                data-status="{{ $item->status_donasi }}"
                data-deskripsi="{{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}"
                data-kadaluarsa="{{ isset($item->tanggal_kadaluarsa) ? \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d M Y, H:i') : '-' }}"
                data-lokasi="{{ $item->lokasi->alamat ?? '-' }}"
                data-foto="{{ $item->foto_url ?: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }}"
                data-created="{{ isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') : '-' }}"
                data-permintaans="{{ json_encode($permintaansData) }}"
            ></div>
            @empty
            <div class="text-center py-10">
                <p class="text-gray-500 font-medium">Belum ada riwayat donasi dari Anda.</p>
            </div>
            @endforelse
        </div>

        <!-- Load More -->
        <div class="mt-10 flex justify-center">
            <button class="flex flex-col items-center text-[#85884B] font-bold text-sm hover:text-[#5B5C35] transition">
                Lihat Lebih Banyak
                <svg class="w-5 h-5 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>

    </main>

    <!-- DETAIL MODAL -->
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm hidden transition-opacity" style="font-family: 'Poppins', sans-serif;">
        <div class="bg-white w-full max-w-2xl rounded-[32px] shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <!-- Modal Header with Image -->
            <div class="relative h-48 shrink-0">
                <img id="modal-foto" src="" alt="Food" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <button onclick="closeDetailModal()" class="absolute top-4 right-4 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-600 hover:text-gray-900 transition cursor-pointer border-none shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="absolute bottom-4 left-6 right-6">
                    <h2 id="modal-nama" class="text-2xl font-extrabold text-white mb-1"></h2>
                    <span id="modal-status" class="text-xs font-bold px-3 py-1 rounded-full"></span>
                </div>
            </div>

            <!-- Modal Body (scrollable) -->
            <div class="overflow-y-auto flex-1 p-6 space-y-6" style="scrollbar-width: thin; scrollbar-color: #E4E5C8 transparent;">

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-[#F8F8EC] rounded-2xl p-4">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori</p>
                        <p id="modal-kategori" class="text-sm font-bold text-gray-700"></p>
                    </div>
                    <div class="bg-[#F8F8EC] rounded-2xl p-4">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jumlah</p>
                        <p id="modal-jumlah" class="text-sm font-bold text-gray-700"></p>
                    </div>
                    <div class="bg-[#F8F8EC] rounded-2xl p-4">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kedaluwarsa</p>
                        <p id="modal-kadaluarsa" class="text-sm font-bold text-gray-700"></p>
                    </div>
                    <div class="bg-[#F8F8EC] rounded-2xl p-4">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal Donasi</p>
                        <p id="modal-created" class="text-sm font-bold text-gray-700"></p>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="bg-[#F8F8EC] rounded-2xl p-4">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Lokasi Penjemputan</p>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#85884B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <p id="modal-lokasi" class="text-sm font-semibold text-gray-700"></p>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Deskripsi</p>
                    <p id="modal-deskripsi" class="text-sm text-gray-600 leading-relaxed font-medium"></p>
                </div>

                <!-- Daftar Permintaan Penerima -->
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-3">Daftar Permintaan Penerima</p>
                    <div id="modal-permintaans" class="space-y-3">
                        <!-- Populated by JS -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-16 w-full max-w-7xl mx-auto px-8 py-6 border-t border-[#E4E7C4] flex flex-col md:flex-row justify-between items-center text-xs font-medium text-gray-500 gap-4">
        <div>&copy; 2026 FoodShare</div>
        <div class="flex space-x-6">
            <a href="#" class="hover:text-[#5B5C35]">Kebijakan Privasi</a>
            <a href="#" class="hover:text-[#5B5C35]">Ketentuan Layanan</a>
            <a href="#" class="hover:text-[#5B5C35]">Kontak</a>
        </div>
    </footer>

    <script>
        function openDetailModal(donasiId) {
            const dataEl = document.getElementById('detail-data-' + donasiId);
            if (!dataEl) return;

            document.getElementById('modal-foto').src = dataEl.dataset.foto;
            document.getElementById('modal-nama').textContent = dataEl.dataset.nama;
            document.getElementById('modal-kategori').textContent = dataEl.dataset.kategori;
            document.getElementById('modal-jumlah').textContent = dataEl.dataset.jumlah + ' Porsi';
            document.getElementById('modal-kadaluarsa').textContent = dataEl.dataset.kadaluarsa;
            document.getElementById('modal-created').textContent = dataEl.dataset.created;
            document.getElementById('modal-lokasi').textContent = dataEl.dataset.lokasi;
            document.getElementById('modal-deskripsi').textContent = dataEl.dataset.deskripsi;

            // Status badge
            const statusEl = document.getElementById('modal-status');
            const status = dataEl.dataset.status;
            let displayStatus = status;
            if (status === 'Available' || status === 'Booked' || status === 'Tersedia' || status === 'Dipesan' || status === 'Diproses') {
                displayStatus = 'Tersedia';
                statusEl.className = 'text-xs font-bold py-1 rounded-full bg-[#D1FAE5] text-[#065F46] inline-flex items-center justify-center w-24 text-center';
            } else if (status === 'Distributed' || status === 'Selesai' || status === 'Habis') {
                displayStatus = 'Habis';
                statusEl.className = 'text-xs font-bold py-1 rounded-full bg-[#DBEAFE] text-[#1E40AF] inline-flex items-center justify-center w-24 text-center';
            } else {
                statusEl.className = 'text-xs font-bold py-1 rounded-full bg-gray-100 text-gray-600 inline-flex items-center justify-center w-24 text-center';
            }
            statusEl.textContent = displayStatus;

            // Permintaans list
            const container = document.getElementById('modal-permintaans');
            container.innerHTML = '';
            try {
                const permintaans = JSON.parse(dataEl.dataset.permintaans);
                if (permintaans.length === 0) {
                    container.innerHTML = '<div class="text-center py-6 text-gray-400 font-semibold text-sm">Belum ada permintaan dari penerima.</div>';
                } else {
                    permintaans.forEach(p => {
                        let statusColor = 'bg-gray-100 text-gray-600';
                        if (p.status === 'Disetujui') statusColor = 'bg-[#D1FAE5] text-[#065F46]';
                        else if (p.status === 'Ditolak') statusColor = 'bg-red-50 text-red-600';
                        else if (p.status === 'Pending') statusColor = 'bg-[#FEF3C7] text-[#92400E]';

                        const card = document.createElement('div');
                        card.className = 'bg-[#F8F8EC] rounded-2xl p-4 flex items-start gap-3';
                        card.innerHTML = `
                            <img src="${p.avatar}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-100" alt="${p.nama}">
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-sm font-bold text-gray-800 truncate">${p.nama}</h4>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full ${statusColor} shrink-0 ml-2">${p.status}</span>
                                </div>
                                <div class="flex gap-4 text-[11px] text-gray-500 font-medium">
                                    <span>${p.jumlah} porsi diminta</span>
                                    <span>${p.created_at}</span>
                                </div>
                                ${p.catatan && p.catatan !== '-' ? `<p class="text-xs text-gray-400 italic mt-1">"${p.catatan}"</p>` : ''}
                            </div>
                        `;
                        container.appendChild(card);
                    });
                }
            } catch(e) {
                container.innerHTML = '<div class="text-center py-6 text-gray-400 font-semibold text-sm">Belum ada permintaan dari penerima.</div>';
            }

            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal on backdrop click
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetailModal();
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDetailModal();
        });
    </script>

</body>
</html>