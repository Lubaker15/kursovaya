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
    <script defer src="./js/catalog.js"></script>
    <script defer src="./js/cart.js"></script>
    <script defer src="{{ asset('js/main.js')}}"></script>
    <script defer src="./js/search.js"></script>
    <title>Байт</title>
</head>

<body>
    <x-navbar></x-navbar>

    <div class="first-section-full-bg">
        <div class="container first-section">
            <h1 class="title_catalog">Каталог</h1>
            <div id="catalog-container" class="first-section__catalog-container">
                
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container footer__container">
            <div class="footer__column">
                <img src="./icons/logo.png" alt="Байт логотип" class="footer__logo">
                <p>Email: <a href="mailto:info@bytesp.ru">info@bytesp.ru</a></p>
                <p><img src="./icons/phone-black.svg" alt="Phone" class="icon"> 7-925-047-81-12</p>
                <p><img src="./icons/vk-black.svg" alt="VK" class="icon"> <a href="https://vk.com/bytesp">Мы в
                        ВКонтакте</a></p>
            </div>
            <div class="footer__column">
                <h4>Компания</h4>
                <ul>
                    <li><a href="#">Новости</a></li>
                    <li><a href="#">О компании</a></li>
                </ul>
            </div>
            <div class="footer__column">
                <h4>Информация</h4>
                <ul>
                    <li><a href="#">Услуги по ремонту</a></li>
                    <li><a href="#">Обслуживание</a></li>
                </ul>
            </div>
            <div class="footer__column">
                <h4>Помощь</h4>
                <ul>
                    <li><a href="#">Условия доставки</a></li>
                    <li><a href="#">Условия оплаты</a></li>
                </ul>
            </div>
            <div class="footer__column">
                <h4>Режим работы</h4>
                <p>Будни: с 10:00 до 20:00</p>
                <p>Суббота: с 10:00 до 18:00</p>
                <p>Воскресенье: с 10:00 до 17:00</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>