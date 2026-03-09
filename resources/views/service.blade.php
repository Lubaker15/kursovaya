<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Доставка и оплата</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/account.css')}}">
    <script defer src="./js/cart.js"></script>
    <script defer src="{{ asset('js/main.js')}}"></script>
    <script defer src="./js/search.js"></script>
</head>
<body>
    <x-navbar></x-navbar>

    <section class="first-section-full-bg">
        <main class="container page">
            <h1>Сервисный центр</h1>

            <section class="info-block">
                <h2>Услуги ремонта</h2>
                <ul>
                    <li>Диагностика — бесплатно при ремонте</li>
                    <li>Ремонт ноутбуков — от 1500 ₽</li>
                    <li>Ремонт ПК — от 1200 ₽</li>
                    <li>Замена термопасты — от 500 ₽</li>
                    <li>Чистка от пыли — от 800 ₽</li>
                </ul>
            </section>

            <section class="info-block">
                <h2>Контакты сервисного центра</h2>
                <p><strong>Телефон:</strong> +7 (925) 047-81-12</p>
                <p><strong>Адрес:</strong>Сергиев Посад, ул. Вознесенская 53а, 2 этаж</p>
                <p><strong>График работы:</strong> ежедневно 10:00–19:00</p>
            </section>
        </main>
    </section>

    <x-footer></x-footer>
</body>
</html>
