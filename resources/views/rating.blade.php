<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FoodShare - Rating</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f1e6;
            font-family: sans-serif;
        }
        .card-rating {
            max-width: 600px;
            margin: auto;
            background: #f0ecdc;
            border-radius: 20px;
            padding: 30px;
        }
        .star {
            font-size: 30px;
            cursor: pointer;
            color: #ccc;
        }
        .star.active {
            color: #c8a200;
        }
        .btn-submit {
            background-color: #f1c94a;
            border-radius: 25px;
            padding: 10px;
            font-weight: bold;
        }
        .tag {
            border-radius: 20px;
            padding: 5px 15px;
            margin: 5px;
            border: none;
        }
        .tag.active {
            background-color: #f1c94a;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3 class="text-center mb-3">Beri Rating & Ulasan</h3>

    <div class="card-rating">

        <!-- Info makanan -->
        <div class="d-flex align-items-center mb-3">
            <img src="{{ asset('images/ayam.jpg') }}" width="80" class="me-3 rounded">
            <div>
                <h5>Ayam Bakar Madu</h5>
                <small>Donasi: Budi Santoso</small>
            </div>
        </div>

        <form action="{{ route('rating.store') }}" method="POST">
            @csrf

            <!-- Rating -->
            <p>Bagaimana kualitas makanannya?</p>
            <div id="stars">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star" data-value="{{ $i }}">★</span>
                @endfor
            </div>

            <input type="hidden" name="rating" id="rating">

            <!-- Ulasan -->
            <div class="mt-3">
                <textarea name="ulasan" class="form-control" placeholder="Tulis ulasan Anda (Opsional)"></textarea>
            </div>

            <!-- Tags -->
            <div class="mt-3">
                <p>Apa yang Anda sukai?</p>

                <input type="hidden" name="tags[]" id="tags">

                <button type="button" class="tag btn btn-light" onclick="toggleTag(this, 'Kebersihan Baik')">Kebersihan Baik</button>
                <button type="button" class="tag btn btn-light" onclick="toggleTag(this, 'Porsi Besar')">Porsi Besar</button>
                <button type="button" class="tag btn btn-light" onclick="toggleTag(this, 'Ramah')">Ramah</button>
            </div>

            <button class="btn btn-submit w-100 mt-4">Kirim Ulasan</button>
        </form>

    </div>
</div>

<script>
    let stars = document.querySelectorAll('.star');
    let ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('click', function () {
            let value = this.getAttribute('data-value');
            ratingInput.value = value;

            stars.forEach(s => s.classList.remove('active'));
            for (let i = 0; i < value; i++) {
                stars[i].classList.add('active');
            }
        });
    });

    let selectedTags = [];

    function toggleTag(el, tag) {
        el.classList.toggle('active');

        if (selectedTags.includes(tag)) {
            selectedTags = selectedTags.filter(t => t !== tag);
        } else {
            selectedTags.push(tag);
        }

        document.getElementById('tags').value = selectedTags;
    }
</script>

</body>
</html>