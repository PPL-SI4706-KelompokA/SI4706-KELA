<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodShare - Beri Rating</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>


<body class="bg-[#F8F8E6] text-[#5B5C35] antialiased min-h-screen flex flex-col">



    <!-- Navbar -->
    <nav class="w-full py-6 px-8 flex items-center justify-between bg-transparent">
        <div class="text-2xl font-extrabold tracking-tight text-[#85884B]">FoodShare</div>
        <div class="hidden md:flex space-x-8 font-semibold text-sm">
            <a href="{{ route('donasi.daftar') }}" class="text-gray-500 hover:text-[#5B5C35] transition">Beranda</a>
            <a href="{{ route('donasi.cari') }}" class="text-[#5B5C35] border-b-2 border-[#5B5C35] pb-1">Donasi</a>
        </div>
        <x-navbar-icons />
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center py-10 px-4">
        
        <!-- Header Text -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-[#7C7E3A] mb-2 leading-tight">Beri Rating &<br>Ulasan</h1>
            <p class="text-sm text-gray-600">Bantu komunitas dengan membagikan pengalaman Anda.</p>
        </div>

        <!-- Rating Card -->
        <div class="bg-[#EEF0D5] w-full max-w-2xl rounded-[40px] p-8 md:p-10 shadow-sm">
            <form action="{{ route('rating.store', $donasi->id_donasi) }}" method="POST">

                @csrf
                {{-- Hidden field untuk id_permintaan --}}
                <input type="hidden" name="id_permintaan" value="{{ $permintaan->id_permintaan ?? '' }}">

                <!-- Food Item Info -->
                <div class="bg-white rounded-[30px] p-4 flex items-center space-x-5 mb-8 shadow-sm">
                    <img src="{{ $donasi->foto_url ?: 'https://via.placeholder.com/150' }}" alt="{{ $donasi->nama_makanan }}" class="w-24 h-24 rounded-2xl object-cover">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $donasi->nama_makanan }}</h2>
                        <div class="flex items-center text-xs text-gray-500 mt-1 space-x-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Kategori : {{ $donasi->kategori }}</span>
                        </div>
                    </div>
                </div>

                @php
                    $initialRating = $existingRating ? $existingRating->nilai_rating : 0;
                    $textOptions = ["Sangat Buruk", "Kurang Baik", "Cukup", "Sangat Enak!", "Sempurna!"];
                    $initialText = $initialRating > 0 ? $textOptions[$initialRating - 1] : 'Pilih Rating';
                @endphp

                <!-- Star Rating Interactive -->
                <div class="text-center mb-8">
                    <h3 class="text-lg font-bold mb-4">Bagaimana kualitas makanannya?</h3>
                    <div class="flex justify-center space-x-2" id="star-container">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg data-value="{{ $i }}" class="star w-12 h-12 cursor-pointer transition-colors duration-200 {{ $i <= $initialRating ? 'text-[#7C7E3A] fill-current' : 'text-gray-300 fill-current' }}" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-[#7C7E3A] font-bold mt-2" id="rating-text">{{ $initialText }}</p>
                    <input type="hidden" name="rating" id="rating-value" value="{{ $initialRating ?: '' }}">
                </div>

                <!-- Textarea Ulasan -->
                <div class="mb-6">
                    <label class="block text-xs font-bold mb-2">Tulis ulasan Anda</label>
                    <textarea name="review" rows="4" placeholder="Ceritakan pengalaman Anda menerima makanan ini... (Opsional)" class="w-full bg-white rounded-3xl px-6 py-4 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#FCD34D] border-none shadow-sm resize-none"></textarea>
                </div>

                <!-- Tags / Feedback Bullets -->
                <div class="mb-8">
                    <label class="block text-xs font-bold mb-3">Apa yang Anda sukai?</label>
                    <div class="flex flex-wrap gap-3">
                        <!-- Option 1 -->
                        <label class="cursor-pointer">
                            <input type="checkbox" name="tags[]" value="Kebersihan Baik" class="peer hidden" checked>
                            <div class="px-5 py-2 rounded-full text-xs font-bold bg-[#E4E5C8] text-[#5B5C35] peer-checked:bg-[#FCD34D] transition-colors">
                                Kebersihan Baik
                            </div>
                        </label>
                        <!-- Option 2 -->
                        <label class="cursor-pointer">
                            <input type="checkbox" name="tags[]" value="Porsi Besar" class="peer hidden">
                            <div class="px-5 py-2 rounded-full text-xs font-bold bg-[#E4E5C8] text-[#5B5C35] peer-checked:bg-[#FCD34D] transition-colors">
                                Porsi Besar
                            </div>
                        </label>
                        <!-- Option 3 -->
                        <label class="cursor-pointer">
                            <input type="checkbox" name="tags[]" value="Ramah" class="peer hidden">
                            <div class="px-5 py-2 rounded-full text-xs font-bold bg-[#E4E5C8] text-[#5B5C35] peer-checked:bg-[#FCD34D] transition-colors">
                                Ramah
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                @if($existingRating)
                    <div class="w-full bg-green-100 text-green-700 font-bold py-4 rounded-full text-center mb-2">
                        ✅ Anda sudah memberikan rating {{ $existingRating->nilai_rating }} bintang.
                    </div>
                @endif
                <button type="submit" class="w-full bg-[#FCD34D] hover:bg-[#fbc629] transition duration-200 text-[#5B5C35] font-bold py-4 rounded-full shadow-sm">
                    {{ $existingRating ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                </button>
            </form>
        </div>
    </main>




    
    <!-- Footer -->
    <footer class="w-full bg-[#EFF0E0] py-6 px-8 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 mt-auto">
        <div>© 2026 FoodShare</div>
        <div class="flex space-x-6 mt-4 md:mt-0">
            <a href="#" class="hover:text-gray-700">Kebijakan Privasi</a>
            <a href="#" class="hover:text-gray-700">Ketentuan Layanan</a>
            <a href="#" class="hover:text-gray-700">Kontak</a>
        </div>
    </footer>

    <!-- Script Interaktif Bintang -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // ⭐ SCRIPT RATING (punya kamu)
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating-value');
        const ratingText = document.getElementById('rating-text');
        
        const textOptions = ["Sangat Buruk", "Kurang Baik", "Cukup", "Sangat Enak!", "Sempurna!"];

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                ratingInput.value = value;
                ratingText.innerText = textOptions[value - 1];

                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= value) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-[#7C7E3A]');
                    } else {
                        s.classList.remove('text-[#7C7E3A]');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });

        // 🔔 SCRIPT NOTIF (DITAMBAHKAN DI SINI)
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');

        if (notifBtn && notifDropdown) {
            notifBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function () {
                notifDropdown.classList.add('hidden');
            });

            notifDropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

    });
    </script>
</body>
</html>