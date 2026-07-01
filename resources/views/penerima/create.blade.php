<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Makanan - FoodShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">

    <nav class="w-full py-6 px-12 flex items-center justify-between bg-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
        <div class="flex space-x-8 font-semibold text-sm">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
            <a href="{{ route('pesan.index') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Pesan</a>
        </div>
        <x-navbar-icons />
    </nav>

    <main class="flex-grow px-12 py-8 flex flex-col lg:flex-row gap-12 items-start justify-center">
        
        <div class="w-full lg:w-1/2 max-w-2xl">
            <div class="relative rounded-[40px] overflow-hidden shadow-sm mb-8 bg-white h-[450px]">
                <img src="{{ $donasi->foto_url ?: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }}" alt="Food Image" class="w-full h-full object-cover">
                <span class="absolute top-6 left-6 px-6 py-2.5 rounded-full text-sm font-bold bg-[#FCD34D] text-[#5B5C35]">
                    {{ $donasi->jumlah ?? '3' }} porsi tersedia
                </span>
            </div>

            <h2 class="text-5xl font-extrabold text-[#7C7E3A] mb-6 leading-tight">{{ $donasi->nama_makanan ?? 'Nasi Goreng Spesial' }}</h2>
            
            <div class="flex flex-wrap gap-3 mb-8">
                <div class="flex items-center px-6 py-2 bg-[#E4E5C8] bg-opacity-40 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kadaluarsa: {{ \Carbon\Carbon::parse($donasi->tanggal_kadaluarsa ?? '2026-01-11')->format('d/m/Y') }}
                </div>
                <div class="flex items-center px-6 py-2 bg-[#E4E5C8] bg-opacity-40 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $donasi->lokasi->alamat ?? 'Jl. Dago, Bandung' }}
                </div>
            </div>

            <div class="bg-white rounded-[32px] p-6 flex items-center justify-between shadow-sm border border-gray-50">
                <div class="flex items-center">
                    <div class="w-14 h-14 rounded-full border border-gray-100 overflow-hidden mr-4">
                        <img src="{{ ($donasi->user && $donasi->user->foto_profil) ? asset('storage/' . $donasi->user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($donasi->user->nama ?? 'Donatur') . '&background=FCD34D&color=5B5C35&size=128' }}" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Donatur</p>
                        <p class="text-xl font-bold text-gray-800">{{ $donasi->user->nama ?? 'Budi Santoso' }}</p>
                    </div>
                </div>
                @if(auth()->check() && auth()->id() !== $donasi->id_user)
                    <a href="{{ route('pesan.index', ['user' => $donasi->id_user]) }}" class="w-10 h-10 bg-[#FCD34D] rounded-full flex items-center justify-center shadow-sm hover:bg-[#e2bd45] transition-colors" title="Chat Donatur">
                        <svg class="w-5 h-5 text-[#5B5C35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="w-full lg:w-[450px] bg-white rounded-[48px] p-10 shadow-sm border border-gray-50">
            @if(!auth()->check() || auth()->user()->role !== 'Penerima')
                <h3 class="text-2xl font-extrabold text-[#6B6D2F] mb-6">Status Donasi</h3>

                <form action="{{ route('donasi.update-status', $donasi->id_donasi ?? 1) }}" method="POST" id="statusForm">
                    @csrf
                    @method('PATCH')
                    
                    <input type="hidden" name="status_donasi" id="selectedStatus" value="{{ $donasi->status_donasi ?? 'Available' }}">

                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3">Status Donasi</label>

                    <div class="flex items-center space-x-2 mb-10">
                        <button type="button" onclick="selectStatus(this, 'Available')" id="btn-Available" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                            <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Tersedia
                        </button>
                        
                        <button type="button" onclick="selectStatus(this, 'Booked')" id="btn-Booked" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                            <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Dipesan
                        </button>

                        <button type="button" onclick="selectStatus(this, 'Distributed')" id="btn-Distributed" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                            <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Selesai
                        </button>
                    </div>

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

            @else
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Informasi Permintaan</h3>
                <p class="text-gray-400 text-sm font-medium mb-8 leading-relaxed">Silakan lengkapi data di bawah ini untuk menerima donasi makanan ini.</p>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl font-semibold text-sm shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('donasi.pesan', $donasi->id_donasi ?? 1) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Porsi yang Diminta</label>
                        <input type="number" 
                            name="jumlah_permintaan" 
                            placeholder="Contoh: 1" 
                            value="{{ old('jumlah_permintaan') }}"
                            required 
                            min="1"
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                        
                        @error('jumlah_permintaan')
                            <p class="text-red-500 text-sm mt-2 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima</label>
                        <input type="text" 
                            name="nama_penerima" 
                            placeholder="Masukkan nama lengkap" 
                            value="{{ old('nama_penerima') }}"
                            required
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                        
                        @error('nama_penerima')
                            <p class="text-red-500 text-sm mt-2 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" 
                            name="nomor_telepon" 
                            placeholder="08xx xxxx xxxx" 
                            value="{{ old('nomor_telepon') }}"
                            required
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                        
                        @error('nomor_telepon')
                            <p class="text-red-500 text-sm mt-2 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#FCD34D] text-[#5B5C35] font-extrabold rounded-full hover:bg-[#fbc316] transition-all shadow-md mt-4">
                        Kirim Permintaan
                    </button>
                </form>

                <div class="mt-8 flex items-start text-[11px] font-semibold text-gray-400 leading-relaxed">
                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>Donasi ini bersifat sukarela. Mohon segera ambil setelah permintaan disetujui untuk menjaga kualitas makanan.</p>
                </div>
            @endif
        </div>
    </main>

    <footer class="py-8 px-12 flex justify-between items-center text-[10px] font-bold text-gray-400 border-t border-[#E4E5C8] mt-12">
        <div>© 2026 FoodShare</div>
        <div class="flex space-x-6 uppercase tracking-widest">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kontak</a>
        </div>
    </footer>

</body>
</html>