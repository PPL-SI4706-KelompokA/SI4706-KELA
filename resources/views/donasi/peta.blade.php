<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Lokasi Donasi - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Leaflet CSS untuk Peta -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #map { height: 100vh; width: 100%; z-index: 0; }
        /* Custom Marker Style */
        .custom-marker {
            background-color: #FCD34D;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
    </style>
</head>
<body class="bg-[#F8F8E6] antialiased overflow-hidden">

    <!-- Overlay Navbar -->
    <nav class="absolute top-0 left-0 w-full py-6 px-12 flex items-center justify-between z-20 bg-gradient-to-b from-[#F8F8E6] to-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
        <div class="flex space-x-8 font-semibold text-sm text-[#5B5C35]">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Donasi</a>
            <a href="{{ route('pesan.index') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
            @if(auth()->check() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'admin'))
                <a href="{{ route('admin.statistik') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Admin</a>
            @endif
        </div>
        <x-navbar-icons />
    </nav>



    <!-- Overlay Search & Filter -->
    <div class="absolute top-28 left-1/2 -translate-x-1/2 z-10 w-full max-w-2xl px-6">
        <div class="relative mb-4">
            <input type="text" placeholder="Cari titik donasi terdekat..." 
                class="w-full py-4 pl-14 pr-6 rounded-2xl bg-white bg-opacity-90 backdrop-blur-md border-none shadow-xl focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
            <svg class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex justify-center space-x-2">
            <button class="px-6 py-2 rounded-full bg-[#FCD34D] text-[#5B5C35] font-bold text-xs shadow-md">Semua</button>
            <button class="px-6 py-2 rounded-full bg-white bg-opacity-80 text-gray-600 font-bold text-xs shadow-sm hover:bg-white transition-all">Makanan</button>
            <button class="px-6 py-2 rounded-full bg-white bg-opacity-80 text-gray-600 font-bold text-xs shadow-sm hover:bg-white transition-all">Snack</button>
            <button class="px-6 py-2 rounded-full bg-white bg-opacity-80 text-gray-600 font-bold text-xs shadow-sm hover:bg-white transition-all">Minuman</button>
        </div>
    </div>

    <!-- Peta -->
    <div id="map"></div>

    <!-- Floating Info Card (Bottom Left) -->
    <div class="absolute bottom-10 left-12 z-10 w-80" id="donation-info-card">
        <div class="bg-white rounded-[32px] overflow-hidden shadow-2xl border border-gray-100 transition-all transform hover:-translate-y-1">
            <div class="relative h-40">
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c" class="w-full h-full object-cover">
                <span class="category-tag absolute top-3 left-4 px-3 py-1 rounded-full text-[9px] font-extrabold bg-white bg-opacity-90 text-[#7C7E3A] uppercase tracking-tighter italic">Makanan Berat</span>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="donation-title text-md font-extrabold text-gray-800 leading-tight">Nasi Kotak Ayam Bakar</h3>
                    <span class="donation-distance text-[10px] font-bold text-gray-400 flex items-center shrink-0 ml-2">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        Bandung
                    </span>
                </div>
                <p class="donation-owner text-[11px] font-medium text-gray-500 mb-4 flex items-center">
                    <span class="w-5 h-5 rounded-full bg-[#FCD34D] flex items-center justify-center text-[9px] font-bold text-[#5B5C35] mr-2">BH</span>
                    Oleh: Bpk. Heru • 10 porsi
                </p>
                <div class="flex items-center justify-between">
                    <p class="donation-expiry text-[10px] font-bold text-gray-400 flex items-center uppercase">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Hingga 20:00
                    </p>
                    <a href="#" class="order-btn px-5 py-2 bg-[#FCD34D] text-[#5B5C35] font-extrabold text-[10px] rounded-xl hover:bg-[#fbc316] shadow-sm">Ambil Donasi</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Controls (Bottom Right) -->
    <div class="absolute bottom-10 right-12 z-10 flex flex-col space-y-3">
        <button onclick="map.panTo([-6.917464, 107.619123])" class="w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-[#7C7E3A] border-none outline-none cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </button>
        <div class="flex flex-col bg-white rounded-2xl shadow-lg divide-y divide-gray-100 overflow-hidden border border-gray-100">
            <button onclick="map.zoomIn()" class="w-12 h-12 flex items-center justify-center text-gray-600 font-bold text-xl hover:bg-gray-50 border-none outline-none cursor-pointer">+</button>
            <button onclick="map.zoomOut()" class="w-12 h-12 flex items-center justify-center text-gray-600 font-bold text-xl hover:bg-gray-50 border-none outline-none cursor-pointer">−</button>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi Peta (Default ke Bandung)
        const map = L.map('map', { zoomControl: false }).setView([-6.917464, 107.619123], 14);

        // Menggunakan gaya peta Dark/Muted
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Data donasi dari database
        const dbDonasi = @json($donasis);

        const foodIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div class="flex items-center justify-center h-full"><svg class="w-6 h-6 text-[#5B5C35]" fill="currentColor" viewBox="0 0 20 20"><path d="M13.5 3c-.41 0-.75.34-.75.75V11H11V3.75c0-.41-.34-.75-.75-.75s-.75.34-.75.75V11H8V3.75c0-.41-.34-.75-.75-.75s-.75.34-.75.75V11c0 1.1.9 2 2 2h3c1.1 0 2-.9 2-2V3.75c0-.41-.34-.75-.75-.75zM15 11c0 1.1.9 2 2 2h.5c.28 0 .5-.22.5-.5V3.75c0-.41-.34-.75-.75-.75S16.5 3.34 16.5 3.75V11zM3.5 18c0 .55.45 1 1 1h11c.55 0 1-.45 1-1v-2H3.5v2z"></path></svg></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        // Function to update floating card
        function showDonationDetail(donasi) {
            const card = document.getElementById('donation-info-card');
            if (!card) return;
            
            card.classList.remove('hidden');
            
            // Image
            const img = card.querySelector('img');
            img.src = donasi.foto_url || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c';
            
            // Category
            const cat = card.querySelector('.category-tag');
            cat.textContent = donasi.kategori || 'Makanan';
            
            // Title
            const title = card.querySelector('.donation-title');
            title.textContent = donasi.nama_makanan;
            
            // Location
            const dist = card.querySelector('.donation-distance');
            const locationStr = donasi.lokasi ? (donasi.lokasi.alamat || 'Bandung') : 'Bandung';
            dist.innerHTML = `<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>${locationStr}`;
            
            // Owner Info
            const owner = card.querySelector('.donation-owner');
            const ownerName = donasi.user ? donasi.user.nama : 'Donatur';
            const initials = ownerName.substring(0, 2).toUpperCase();
            owner.innerHTML = `<span class="w-5 h-5 rounded-full bg-[#FCD34D] flex items-center justify-center text-[9px] font-bold text-[#5B5C35] mr-2">${initials}</span>Oleh: ${ownerName} • ${donasi.jumlah} porsi`;
            
            // Expiration
            const expiry = card.querySelector('.donation-expiry');
            const expDate = new Date(donasi.tanggal_kadaluarsa.replace(' ', 'T'));
            let formattedTime = 'Hari ini';
            if (!isNaN(expDate.getTime())) {
                formattedTime = expDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }) + ', ' + expDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }
            expiry.innerHTML = `<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Hingga ${formattedTime}`;
            
            // Order Button
            const btn = card.querySelector('.order-btn');
            btn.href = `{{ url('/donasi') }}/${donasi.id_donasi}/pesan`;
        }

        // Loop dan render marker
        if(dbDonasi && dbDonasi.length > 0) {
            dbDonasi.forEach(donasi => {
                if (donasi.lokasi) {
                    const marker = L.marker([donasi.lokasi.latitude, donasi.lokasi.longitude], { icon: foodIcon })
                    .addTo(map)
                    .bindTooltip(donasi.nama_makanan + ' - ' + (donasi.lokasi.alamat || ''));
                    
                    marker.on('click', () => {
                        showDonationDetail(donasi);
                    });
                }
            });
            
            // Show the first one by default
            showDonationDetail(dbDonasi[0]);
        } else {
            // Hide card if no donations
            const card = document.getElementById('donation-info-card');
            if (card) card.classList.add('hidden');
        }
    </script>
</body>
</html>