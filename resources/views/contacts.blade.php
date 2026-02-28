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
            <h1>Контакты</h1>

            <div class="contacts-wrapper">

                <div class="contacts-info">
                    <p><strong>Телефон:</strong> +7 (925) 047-81-12</p>
                    <p><strong>Email:</strong> info@bytesp.ru</p>
                    <p><strong>Адрес:</strong> Сергиев Посад, ул. Вознесенская 53а, 2 этаж</p>
                    <p><strong>График работы:</strong></p>
                    <ul>
                        <li>Будни: 10:00 – 20:00</li>
                        <li>Суббота: 10:00 – 18:00</li>
                        <li>Воскресенье: 10:00 – 17:00</li>
                    </ul>
                </div>

                <div class="contacts-map">
                    <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Abe75c11f368eb181b21df87bb2b5ea4871681f656b6af54e92e9303f6e98733a&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>
                </div>

            </div>
        </main>
    </section>

    <footer class="footer">
        <div class="container footer__container">
            <div class="footer__column">
                <img src="./icons/logo.png" alt="Байт логотип" class="footer__logo">
                <p>Email: <a href="mailto:info@bytesp.ru">info@bytesp.ru</a></p>
                <p><img src="./icons/phone-black.svg" alt="Phone" class="icon"> 7-925-047-81-12</p>
                <p><img src="./icons/vk-black.svg" alt="VK" class="icon">
                    <a href="https://vk.com/bytesp">Мы в ВКонтакте</a>
                </p>
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

</body>
</html>
