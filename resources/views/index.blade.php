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
    <script defer src="./js/products.js"></script>
    <script defer src="./js/cart.js"></script>
    <script defer src="{{ asset('js/main.js')}}"></script>
    <script defer src="./js/search.js"></script>
    <title>Байт</title>
</head>

<body>
    <x-navbar></x-navbar>

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./img/carousel.png" class="d-block w-100" alt="...">
                <div class="carousel-caption-custom">
                    <div class="text">
                        <h2>Игровые ноутбуки</h2>
                        <span>Подойдут для игр и профессиональных задач</span>
                    </div>
                    <a href="catalog.html" class="btn btn__btn-transition">Перейти</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./img/carousel.png" class="d-block w-100" alt="...">
                <div class="carousel-caption-custom">
                    <div class="text">
                        <h2>Игровые ноутбуки</h2>
                        <span>Подойдут для игр и профессиональных задач</span>
                    </div>
                    <a href="catalog.html" class="btn btn__btn-transition">Перейти</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./img/carousel.png" class="d-block w-100" alt="...">
                <div class="carousel-caption-custom">
                    <div class="text">
                        <h2>Игровые ноутбуки</h2>
                        <span>Подойдут для игр и профессиональных задач</span>
                    </div>
                    <a href="catalog.html" class="btn btn__btn-transition">Перейти</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="first-section-full-bg">
        <div class="container first-section">
            <div class="first-section__popular">
                <span class="active">Хиты</span>
                <span>Новинки</span>
                <span>Акции</span>
            </div>
            <div id="products-container" class="first-section__card-section">

            </div>
        </div>
    </div>
    <!-- 2 секция -->
    <div class="transition-between-section"></div>
    <section class="second-section-full-bg">
        <div class="container second-section">
            <div class="second-section__left-wrapper">
                <img class="about-photo" src="./img/about.png" alt="">
                <img src="./img/ellipse.png" alt="декоративный элемент" class="elipse-decor ellipse-first">
                <img src="./img/ellipse.png" alt="декоративный элемент" class="elipse-decor ellipse-second">
            </div>
            <div class="second-section__right-wrapper">
                <h1>Немного о нас</h1>
                <p class="description">Компьютерный магазин "байт" в сергиевом Посаде специализируется на розничной
                    продаже компьютерных
                    комплектующих и периферии, оргтехники, сетевого оборудования, расходных материалов. в нашем магазине
                    можно купить компьютер любой конфигурации, или же купить комплектующие для компьютера и собрать
                    компьютер самостоятельно. У нас вы можете купить ноутбук от бюджетного до премиум класса.  А также
                    произвести квалифицированный ремонт компьютера или ноутбука в Сергиевом Посаде. мы производим
                    заправку лазерных картриджей. Огромный перечень оказываемых нами услуг, внимательное отношение к
                    проблемам наших клиентов и приемлемые цены выгодно отличают нас от всех конкурентов, представленных
                    на рынке.</p>
                <p>
                    Вы можете найти нас здесь:<span> Cергиев Посад, ул. Вознесенская 53а, 2 этаж</span>
                </p>
            </div>
        </div>
    </section>
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