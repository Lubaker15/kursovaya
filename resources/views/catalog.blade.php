<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/catalog.css')}}">
    <script defer src="{{ asset('js/main.js')}}"></script>
    <title>Байт</title>
</head>

<body>
    <x-navbar></x-navbar>

    <div class="first-section-full-bg">
        <div class="container first-section">
        <h1 class="title_catalog">Каталог</h1>
            <div id="catalog-container" class="first-section__card-section">
                @foreach($categories as $category)
                    <div class="category-card" data-category-id="{{ $category->id }}" data-category-name="{{ $category->category_name }}">
                        <div class="category-card__img">
                            <img src="{{ asset($category->random_image) }}" alt="{{ $category->category_name }}">
                        </div>
                        <h3 class="category-card__title">{{ $category->category_name }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-footer></x-footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('click', function(e) {
            const card = e.target.closest('.category-card');
            if (card) {
                const categoryId = card.dataset.categoryId;
                window.location.href = `/category/${categoryId}`;
            }
        });
    </script>
</body>

</html>