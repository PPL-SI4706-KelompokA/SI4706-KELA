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

    <!-- Navbar -->
    <nav class="w-full py-6 px-12 flex items-center justify-between bg-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#7C7E3A]">FoodShare</div>
        <div class="flex space-x-8 font-semibold text-sm">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
        </div>
        <x-navbar-icons />
    </nav>

    <!-- Main Content -->
    <main class="flex-grow px-12 py-8 flex flex-col lg:flex-row gap-12 items-start justify-center">
        
        <!-- Left Side: Food Detail -->
        <div class="w-full lg:w-1/2 max-w-2xl">
            <div class="relative rounded-[40px] overflow-hidden shadow-sm mb-8 bg-white h-[450px]">
                <img src="{{ $donasi->foto_url ?: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }}" alt="Food Image" class="w-full h-full object-cover">
                <span class="absolute top-6 left-6 px-6 py-2.5 rounded-full text-sm font-bold bg-[#FCD34D] text-[#5B5C35]">
                    @if(\Carbon\Carbon::parse($donasi->tanggal_kadaluarsa)->isPast())
                        0 porsi tersedia (Kadaluarsa)
                    @else
                        {{ $donasi->jumlah ?? '3' }} porsi tersedia
                    @endif
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
                    {{ $donasi->lokasi->alamat ?? ($donasi->lokasi->kota ?? 'Bandung') }}
                </div>
            </div>

            @php
                $donaturNama = $donasi->user->nama ?? 'Budi Santoso';
                $words = explode(' ', $donaturNama);
                $initials = '';
                if (count($words) >= 2) {
                    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                } else {
                    $initials = strtoupper(substr($donaturNama, 0, min(2, strlen($donaturNama))));
                }
            @endphp
            <div class="bg-white rounded-[32px] p-6 flex items-center shadow-sm border border-gray-50">
                @if($donasi->user && $donasi->user->foto_url)
                    <img src="{{ $donasi->user->foto_url }}" class="w-14 h-14 rounded-full object-cover mr-4 shadow-sm">
                @else
                    <div class="w-14 h-14 rounded-full bg-[#FCD34D] flex items-center justify-center font-bold text-xl text-[#5B5C35] mr-4 shadow-inner">
                        {{ $initials }}
                    </div>
                @endif
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Donatur</p>
                    <p class="text-xl font-bold text-gray-800">{{ $donaturNama }}</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Action Form -->
        <div class="w-full lg:w-[450px] bg-white rounded-[48px] p-10 shadow-sm border border-gray-50">
            @if(!auth()->check() || auth()->user()->role !== 'Penerima')
                @if(auth()->check() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'admin' || $donasi->id_user === auth()->id()))
                    <h3 class="text-2xl font-extrabold text-[#6B6D2F] mb-6">Status Donasi</h3>

                    <form action="{{ route('donasi.update-status', $donasi->id_donasi ?? 1) }}" method="POST" id="statusForm">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Input Hidden untuk menyimpan status yang dipilih -->
                        <input type="hidden" name="status_donasi" id="selectedStatus" value="{{ $donasi->status_donasi ?? 'Available' }}">

                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-3">Status Donasi</label>

                        <!-- Pilihan Status (Pills) -->
                        <div class="flex items-center space-x-2 mb-10">
                            <!-- Option: Tersedia -->
                            <button type="button" onclick="selectStatus(this, 'Available')" id="btn-Available" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                                <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Tersedia
                            </button>
                            
                            <!-- Option: Dipesan -->
                            <button type="button" onclick="selectStatus(this, 'Booked')" id="btn-Booked" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                                <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Dipesan
                            </button>

                            <!-- Option: Selesai -->
                            <button type="button" onclick="selectStatus(this, 'Distributed')" id="btn-Distributed" class="status-btn flex items-center justify-center px-5 py-2.5 rounded-full border-2 border-transparent bg-[#E4E5C8] text-gray-500 text-xs font-semibold hover:bg-[#d8d9b9] transition-all">
                                <svg class="w-3.5 h-3.5 mr-1 check-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Selesai
                            </button>
                        </div>

                        <!-- Footer Action -->
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
                        
                        // Set active status on load
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
                    <h3 class="text-2xl font-extrabold text-[#6B6D2F] mb-6">Status Donasi</h3>
                    <div class="p-6 bg-gray-50 rounded-[32px] text-center border border-gray-100 flex flex-col items-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Status Saat Ini</p>
                        <span class="inline-block px-6 py-2.5 rounded-full border-2 border-[#6B6D2F] text-[#6B6D2F] text-xs font-bold bg-transparent">
                            {{ $donasi->status_donasi }}
                        </span>
                    </div>
                @endif
            @else
                <!-- Tampilan Khusus Penerima: Form Pemesanan (FR06) -->
                <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Informasi Permintaan</h3>
                <p class="text-gray-400 text-sm font-medium mb-8 leading-relaxed">Silakan lengkapi data di bawah ini untuk menerima donasi makanan ini.</p>

                <!-- Pesan Error Global -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6 text-xs font-semibold">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('donasi.pesan', $donasi->id_donasi ?? 1) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Porsi yang Diminta</label>
                        <input type="number" name="jumlah_permintaan" placeholder="Contoh: 1" 
                            min="1" max="{{ $donasi->jumlah }}" required
                            value="{{ old('jumlah_permintaan') }}"
                            {{ \Carbon\Carbon::parse($donasi->tanggal_kadaluarsa)->isPast() ? 'disabled' : '' }}
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                        @error('jumlah_permintaan')
                            <p class="text-red-500 text-xs font-semibold mt-1.5 pl-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima</label>
                        <input type="text" name="nama_penerima" placeholder="Masukkan nama lengkap" required
                            value="{{ old('nama_penerima', auth()->check() ? auth()->user()->nama : '') }}"
                            {{ \Carbon\Carbon::parse($donasi->tanggal_kadaluarsa)->isPast() ? 'disabled' : '' }}
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                        @error('nama_penerima')
                            <p class="text-red-500 text-xs font-semibold mt-1.5 pl-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" placeholder="08xx xxxx xxxx" required
                            pattern="^08[0-9]{9,11}$"
                            title="Nomor telepon harus berawalan 08 dan memiliki panjang 11-13 digit."
                            minlength="11" maxlength="13"
                            value="{{ old('nomor_telepon', auth()->check() ? auth()->user()->no_telp : '') }}"
                            {{ \Carbon\Carbon::parse($donasi->tanggal_kadaluarsa)->isPast() ? 'disabled' : '' }}
                            class="w-full py-4 px-6 rounded-3xl bg-[#F9FAFB] border-none focus:ring-2 focus:ring-[#FCD34D] text-gray-600 font-medium">
                        @error('nomor_telepon')
                            <p class="text-red-500 text-xs font-semibold mt-1.5 pl-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                        {{ \Carbon\Carbon::parse($donasi->tanggal_kadaluarsa)->isPast() ? 'disabled' : '' }}
                        class="w-full py-4 bg-[#FCD34D] text-[#5B5C35] font-extrabold rounded-full hover:bg-[#fbc316] transition-all shadow-md mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ \Carbon\Carbon::parse($donasi->tanggal_kadaluarsa)->isPast() ? 'Donasi Kadaluarsa' : 'Kirim Permintaan' }}
                    </button>
                </form>

                <div class="mt-8 flex items-start text-[11px] font-semibold text-gray-400 leading-relaxed">
                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>Donasi ini bersifat sukarela. Mohon segera ambil setelah permintaan disetujui untuk menjaga kualitas makanan.</p>
                </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
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