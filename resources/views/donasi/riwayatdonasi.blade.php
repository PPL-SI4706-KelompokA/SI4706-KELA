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
            <button class="bg-[#FCD34D] text-[#5B5C35] px-6 py-2 rounded-full font-bold text-sm shadow-sm">Semua</button>
            <button class="bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4] transition px-6 py-2 rounded-full font-semibold text-sm">Selesai</button>
            <button class="bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4] transition px-6 py-2 rounded-full font-semibold text-sm">Diproses</button>
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
                        @if(\Carbon\Carbon::parse($item->tanggal_kadaluarsa)->isPast())
                            <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                Kadaluarsa
                            </span>
                        @elseif($item->status_donasi === 'Distributed' || $item->jumlah <= 0)
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                Habis
                            </span>
                        @else
                            <span class="bg-[#D1FAE5] text-[#065F46] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                {{ $item->status_donasi ?? 'Tersedia' }}
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

                    <div class="flex justify-between items-center mt-2">
                        @php
                            $latestPermintaan = $item->permintaans->sortByDesc('created_at')->first();
                        @endphp
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-600">
                            <div class="w-6 h-6 bg-[#FCD34D] rounded-full flex items-center justify-center text-[#5B5C35]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            @if($latestPermintaan)
                                @if(strtolower($latestPermintaan->status) === 'disetujui' || strtolower($latestPermintaan->status) === 'selesai')
                                    Penerima: {{ $latestPermintaan->user->nama ?? 'Penerima' }} ({{ $latestPermintaan->jumlah_permintaan }} Porsi didistribusikan)
                                @elseif(strtolower($latestPermintaan->status) === 'ditolak')
                                    Penerima: {{ $latestPermintaan->user->nama ?? 'Penerima' }} (Permintaan ditolak)
                                @else
                                    Penerima: {{ $latestPermintaan->user->nama ?? 'Penerima' }} ({{ $latestPermintaan->jumlah_permintaan }} Porsi menunggu)
                                @endif
                            @else
                                Penerima: Menunggu...
                            @endif
                        </div>
                        <a href="{{ route('donasi.detail', $item->id_donasi) }}" class="text-[#85884B] font-bold text-sm flex items-center gap-1 hover:text-[#5B5C35]">
                            Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
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

    <!-- Footer -->
    <footer class="mt-16 w-full max-w-7xl mx-auto px-8 py-6 border-t border-[#E4E7C4] flex flex-col md:flex-row justify-between items-center text-xs font-medium text-gray-500 gap-4">
        <div>&copy; 2026 FoodShare</div>
        <div class="flex space-x-6">
            <a href="#" class="hover:text-[#5B5C35]">Kebijakan Privasi</a>
            <a href="#" class="hover:text-[#5B5C35]">Ketentuan Layanan</a>
            <a href="#" class="hover:text-[#5B5C35]">Kontak</a>
        </div>
    </footer>

</body>
</html>