<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Permintaan - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8F8E6] text-[#1A1A1A] antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="w-full py-6 px-12 flex items-center justify-between bg-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
        <div class="flex space-x-8 font-semibold text-sm text-[#5B5C35]">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Donasi</a>
            <a href="{{ route('pesan.index') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
        </div>
        <x-navbar-icons />
    </nav>

    <!-- Header -->
    <header class="px-12 pt-12 pb-8">
        @if(auth()->check() && auth()->user()->role === 'Penerima')
            <h2 class="text-4xl font-extrabold text-[#333333] mb-2">Detail Pesanan Anda</h2>
            <p class="text-gray-500 font-medium">Informasi lengkap mengenai status pesanan donasi makanan Anda.</p>
        @else
            <h2 class="text-4xl font-extrabold text-[#333333] mb-2">Permintaan Donasi</h2>
            <p class="text-gray-500 font-medium">Detail permohonan bantuan makanan dari komunitas Anda.</p>
        @endif
    </header>

    <!-- Main Card Container -->
    <main class="px-12 pb-20">
        <div class="bg-white rounded-[48px] overflow-hidden shadow-sm flex flex-col md:flex-row max-w-6xl mx-auto">
            
            <!-- Left Side: Food Image -->
            <div class="md:w-5/12 p-8">
                <div class="relative rounded-[32px] overflow-hidden h-full min-h-[500px]">
                    <img src="{{ $permintaan->donasi->foto_url ?: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }}" alt="Food" class="w-full h-full object-cover">
                    <div class="absolute top-6 left-6 px-5 py-2 bg-[#FCD34D] rounded-full text-[12px] font-bold text-[#5B5C35] flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 17a1 1 0 01-1.447.894L5 15.618V10a1 1 0 012 0v4.382l3.553 1.776A1 1 0 0111 17z"></path></svg>
                        Food
                    </div>
                </div>
            </div>

            <!-- Right Side: Details & Actions -->
            <div class="md:w-7/12 p-12 flex flex-col justify-center">
                <h3 class="text-3xl font-extrabold text-gray-800 mb-8">{{ $permintaan->donasi->nama_makanan ?? 'Nasi Goreng Spesial' }}</h3>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-10">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-[#F2F3E2] flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-[#7C7E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 118 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Tersedia</p>
                            <p class="text-sm font-bold text-gray-700">{{ $permintaan->donasi->jumlah ?? '3' }} porsi tersedia</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-[#F2F3E2] flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-[#7C7E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Kadaluarsa</p>
                            <p class="text-sm font-bold text-gray-700">{{ $permintaan->donasi->tanggal_kadaluarsa ?? '11 Jan 2026' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center col-span-2">
                        <div class="w-10 h-10 rounded-full bg-[#F2F3E2] flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-[#7C7E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Lokasi Penjemputan</p>
                            <p class="text-sm font-bold text-gray-700">{{ $permintaan->donasi->lokasi->alamat ?? 'Jl. Dago, Bandung' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recipient Card -->
                <div class="bg-[#F2F3E2] rounded-[32px] p-8 mb-10 relative">
                    <h4 class="text-sm font-bold text-[#7C7E3A] mb-6">Detail Penerima</h4>
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 rounded-full border-2 border-white overflow-hidden mr-4">
                            <img src="https://ui-avatars.com/api/?name={{ $permintaan->user->nama ?? 'Penerima' }}&background=FCD34D&color=5B5C35" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-800">{{ $permintaan->user->nama ?? 'Nama Tidak Diketahui' }}</p>
                            <p class="text-xs font-semibold text-[#7C7E3A]">Meminta {{ $permintaan->jumlah_permintaan ?? 1 }} Porsi</p>
                        </div>
                        <div class="absolute right-8 top-16 flex items-center gap-3">
                            <a href="tel:{{ $permintaan->user->no_telp ?? '' }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 hover:bg-gray-50 transition-colors" title="Hubungi Penerima">
                                <svg class="w-5 h-5 text-[#5B5C35]" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 2V3z"></path></svg>
                            </a>
                            @if(auth()->check() && auth()->id() !== $permintaan->id_user)
                                <a href="{{ route('pesan.index', ['user' => $permintaan->id_user]) }}" class="w-10 h-10 bg-[#FCD34D] rounded-full flex items-center justify-center shadow-sm hover:bg-[#e2bd45] transition-colors" title="Chat Penerima">
                                    <svg class="w-5 h-5 text-[#5B5C35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Note Bubble -->
                    <div class="bg-white rounded-2xl p-4 mb-4 relative shadow-sm">
                        <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest mb-1">Catatan</p>
                        <p class="text-sm font-medium italic text-gray-600">"{{ $permintaan->catatan ?? 'Tidak ada catatan.' }}"</p>
                    </div>

                    <div class="flex justify-between text-[11px] font-bold text-gray-400 uppercase tracking-wider px-2">
                        <span>{{ $permintaan->user->no_telp ?? 'Tidak ada nomor telepon' }}</span>
                        <span class="realtime-time" data-timestamp="{{ $permintaan->created_at->toIso8601String() }}">{{ $permintaan->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Action Buttons (FR07) -->
                @if(auth()->check() && auth()->user()->role === 'Donatur' && $permintaan->status === 'Pending')
                <div class="flex gap-4">
                    <form action="{{ route('permintaan.konfirmasi', $permintaan->id_permintaan) }}" method="POST" class="w-1/2">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Ditolak">
                        <button type="submit" class="w-full py-4 rounded-full border-2 border-gray-200 font-bold text-gray-500 hover:bg-gray-50 transition-colors">
                            Tolak
                        </button>
                    </form>
                    <form action="{{ route('permintaan.konfirmasi', $permintaan->id_permintaan) }}" method="POST" class="w-1/2">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Disetujui">
                        <button type="submit" class="w-full py-4 rounded-full bg-[#6B630C] text-white font-bold shadow-lg shadow-[#6b630c33] flex items-center justify-center hover:bg-[#524d0a] transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Terima Permintaan
                        </button>
                    </form>
                </div>
                @else
                <div class="mt-4 p-4 rounded-2xl {{ $permintaan->status == 'Disetujui' ? 'bg-green-50 text-green-700' : ($permintaan->status == 'Ditolak' ? 'bg-red-50 text-red-700' : 'bg-gray-50 text-gray-600') }} font-bold text-center">
                    Status Permintaan: {{ $permintaan->status }}
                </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-8 px-12 flex justify-between items-center text-[10px] font-bold text-gray-400 border-t border-[#E4E5C8]">
        <div>© 2026 FoodShare</div>
        <div class="flex space-x-6 uppercase tracking-widest">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>

</body>
</html>