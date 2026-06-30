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
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
            @if(auth()->check() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'admin'))
                <a href="{{ route('admin.statistik') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Admin</a>
            @endif
        </div>
        <x-navbar-icons />
    </nav>

    <main class="flex-grow max-w-5xl mx-auto px-4 sm:px-8 w-full py-8">
        
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-[#85884B] mb-3">Riwayat Penerimaan</h1>
            <p class="text-gray-500 text-sm md:text-base max-w-lg">
                Lacak semua makanan yang telah Anda terima. Jangan lupa memberikan rating untuk mengapresiasi para donatur.
            </p>
        </div>

        <div class="flex space-x-3 mb-10">
            <a href="{{ route('penerima.riwayatpenerimaan') }}" class="{{ empty($statusAktif) ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Semua</a>
            <a href="{{ route('penerima.riwayatpenerimaan', ['status' => 'Selesai']) }}" class="{{ $statusAktif == 'Selesai' ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Selesai</a>
            <a href="{{ route('penerima.riwayatpenerimaan', ['status' => 'Diproses']) }}" class="{{ $statusAktif == 'Diproses' ? 'bg-[#FCD34D] text-[#5B5C35] shadow-sm' : 'bg-[#EEF0D5] text-[#85884B] hover:bg-[#e4e7c4]' }} transition px-6 py-2 rounded-full font-bold text-sm">Diproses</a>
        </div>

        {{-- Flash Message Sukses --}}
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 font-semibold px-6 py-4 rounded-2xl flex items-center gap-2">
                <span>⭐</span> {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse($riwayatPenerimaan as $item)
                <div class="bg-white p-3 rounded-[32px] shadow-sm flex flex-col md:flex-row gap-6">
                    
                    <div class="w-full md:w-64 h-48 relative shrink-0">
                        <img src="{{ $item->donasi->foto_url ?: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3' }}" alt="{{ $item->donasi->nama_makanan ?? 'Makanan' }}" class="w-full h-full object-cover rounded-[24px]">
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-bold px-3 py-1.5 rounded-full text-[#5B5C35] shadow-sm">
                            {{ $item->jumlah_permintaan ?? '-' }} Porsi
                        </div>
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-center py-2 pr-4">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-xl font-bold text-gray-800">{{ $item->donasi->nama_makanan ?? 'Nama Makanan' }}</h2>
                            
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
                            <div>Kedaluwarsa: {{ \Carbon\Carbon::parse($item->donasi->tanggal_kadaluarsa)->format('d M, H:i') }}</div>
                            <div>Lokasi: {{ $item->donasi->lokasi->alamat ?? 'Bandung' }}</div>
                            <div>Diminta: <span class="realtime-time" data-timestamp="{{ $item->created_at->toIso8601String() }}">{{ $item->created_at->diffForHumans() }}</span></div>
                        </div>



                        <hr class="border-gray-100 my-2">

                        <div class="flex justify-between items-center mt-2">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2 text-sm font-semibold text-gray-600">
                                    <div class="w-6 h-6 bg-[#FCD34D] rounded-full flex items-center justify-center text-[#5B5C35]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    {{-- Menampilkan nama pemberi/donatur --}}
                                    {{ $item->donasi->user->nama ?? 'Donatur' }}
                                </div>
                                {{-- Tampilkan bintang rating jika sudah dirating --}}
                                @if($item->rating)
                                    <div class="flex items-center gap-0.5 ml-1">
                                        @for($star = 1; $star <= 5; $star++)
                                            <svg class="w-4 h-4 {{ $star <= $item->rating->nilai_rating ? 'text-[#FCD34D]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="text-xs text-gray-400 ml-1 font-medium">{{ $item->rating->nilai_rating }}/5</span>
                                    </div>
                                @endif
                            </div>
                            @if(in_array(strtolower($item->status), ['selesai', 'disetujui']))
                                <a href="{{ route('rating.create', $item->id_donasi) }}" class="text-[#85884B] font-bold text-sm flex items-center gap-1 hover:text-[#5B5C35]">
                                    {{ $item->rating ? 'Edit Rating' : 'Rating' }} <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            @endif
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