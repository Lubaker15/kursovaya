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
            <h1>Доставка и оплата</h1>
            <section class="info-block">
                <h2>Доставка</h2>
                <p>Мы осуществляем доставку по всей России. Срок доставки зависит от региона и составляет от 2 до 10 дней.</p>
                <ul>
                    <li>Курьером по Москве — от 300 ₽</li>
                    <li>Пункты выдачи заказов — от 150 ₽</li>
                    <li>Почта России / СДЭК — по тарифам перевозчика</li>
                </ul>
            </section>
            <section class="info-block">
                <h2>Оплата</h2>
                <ul>
                    <li>Онлайн-оплата картой</li>
                    <li>Оплата при получении</li>
                    <li>Безналичный расчет для юр. лиц</li>
                </ul>
            </section>
        </main>
    </section>

    <x-footer></x-footer>

</body>
</html>
