<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Riwayat Penerimaan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">

    <nav class="w-full py-6 px-8 flex items-center justify-between">
        <div class="text-2xl font-extrabold tracking-tight text-[#85884B]">FoodShare</div>
        <div class="hidden md:flex space-x-8 font-semibold text-sm">
            <a href="#" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="#" class="text-gray-500 hover:text-[#5B5C35] transition">Donasi</a>
            <a href="#" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Pesan</a>
        </div>
        <div class="flex items-center space-x-6 text-[#85884B]">
            <svg class="w-6 h-6 cursor-pointer hover:text-[#5B5C35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <svg class="w-6 h-6 cursor-pointer text-[#FCD34D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center overflow-hidden border border-gray-200">
                <img src="https://ui-avatars.com/api/?name=Tony+Stark&background=EBF4FF&color=3B82F6" alt="Profile" class="w-full h-full object-cover">
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-5xl mx-auto px-4 sm:px-8 w-full py-8">
        
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-[#85884B] mb-3">Riwayat Penerimaan</h1>
            <p class="text-gray-500 text-sm md:text-base max-w-lg">
                Lacak semua makanan yang telah Anda terima. Jangan lupa memberikan rating untuk mengapresiasi para donatur.
            </p>
        </div>

        <div class="flex space-x-3 mb-10">
            <a href="{{ route('penerimaan.riwayat') }}" class="{{ empty($statusAktif) ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Semua</a>
            <a href="{{ route('penerimaan.riwayat', ['status' => 'Selesai']) }}" class="{{ $statusAktif == 'Selesai' ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Selesai</a>
            <a href="{{ route('penerimaan.riwayat', ['status' => 'Diproses']) }}" class="{{ $statusAktif == 'Diproses' ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Diproses</a>
        </div>

        <div class="space-y-6">
            @forelse($riwayatPenerimaan as $item)
                <div class="bg-white p-3 rounded-[32px] shadow-sm flex flex-col md:flex-row gap-6">
                    
                    <div class="w-full md:w-64 h-48 relative shrink-0">
                        <img src="{{ $item->foto_makanan ? asset('storage/' . $item->foto_makanan) : 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3' }}" alt="{{ $item->nama_makanan }}" class="w-full h-full object-cover rounded-[24px]">
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-bold px-3 py-1.5 rounded-full text-[#5B5C35] shadow-sm">
                            {{ $item->jumlah_porsi }} Porsi
                        </div>
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-center py-2 pr-4">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-xl font-bold text-gray-800">{{ $item->nama_makanan }}</h2>
                            
                            {{-- Status Badge --}}
                            @if($item->status == 'Selesai')
                                <span class="bg-[#D1FAE5] text-[#065F46] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Selesai
                                </span>
                            @else
                                <span class="bg-[#FFEDD5] text-[#C2410C] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                    <span class="w-2 h-2 bg-[#F97316] rounded-full animate-pulse"></span>
                                    {{ $item->status ?? 'Diproses' }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-500 font-medium mb-4">
                            <div>Kedaluwarsa: {{ \Carbon\Carbon::parse($item->waktu_kadaluarsa)->format('d M, H:i') }}</div>
                            <div>Lokasi: {{ $item->lokasi }}</div>
                        </div>

                        {{-- Tombol Rating (Hanya Muncul Jika Selesai) --}}
                        @if($item->status == 'Selesai')
                            <div class="mb-2">
                                <a href="#" class="inline-flex items-center gap-1.5 bg-[#F9F9E0] border border-[#E4E7C4] text-[#5B5C35] text-xs font-bold px-4 py-1.5 rounded-full hover:bg-[#EEF0D5] transition">
                                    <svg class="w-3.5 h-3.5 text-[#FCD34D]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    Rating
                                </a>
                            </div>
                        @endif

                        <hr class="border-gray-100 my-2">

                        <div class="flex justify-between items-center mt-2">
                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-600">
                                <div class="w-6 h-6 bg-[#FCD34D] rounded-full flex items-center justify-center text-[#5B5C35]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                {{-- Menampilkan nama pemberi/donatur --}}
                                {{ $item->donatur_nama ?? 'Restoran Budi' }}
                            </div>
                            <a href="#" class="text-[#85884B] font-bold text-sm flex items-center gap-1 hover:text-[#5B5C35]">
                                Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-gray-500 font-medium">Belum ada riwayat penerimaan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10 flex justify-center">
            {{ $riwayatPenerimaan->links() }}
        </div>

    </main>
</body>
</html>