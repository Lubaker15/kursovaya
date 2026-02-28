<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Товар</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/index.css')}}">
    <script defer src="./js/cart.js"></script>
    <script defer src="./js/product.js"></script>
    <script defer src="{{ asset('js/main.js')}}"></script>
    <script defer src="./js/search.js"></script>
</head>

<body>
    <x-navbar></x-navbar>


    <main class="main">
        <h1 id="product-title" class="main__title"></h1>
        <section class="main__wrapper">
            <div id="img-wrapper">
                <img id="product-image" src="" class="main__img" alt="">
            </div>
            <div class="main__description--wrapper">
                <div class="main__button-wrapper">
                    <button class="btn btn__btn-buy">Купить</button>
                    <img src="./img/cart.png" class="main__card-icon" alt="Купить товар">
                </div>
                <div class="main__text-wrapper">
                    <div>
                        <span class="main__text--upper">ЦЕНА -</span>
                        <span id="product-price"></span>
                    </div>
                    <div>
                        <span class="main__text--upper">В наличии -</span>
                        <span id="product-stock"></span>
                    </div>
                </div>
                <p id="product-description" class="main__description"></p>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer__container">
            <div class="footer__column">
                <img src="./icons/logo.png" alt="Байт логотип" class="footer__logo" />
                <p>Email: <a href="mailto:info@bytesp.ru">info@bytesp.ru</a></p>
                <p><img src="./icons/phone-black.svg" alt="Phone" class="icon" /> 7-925-047-81-12</p>
                <p><img src="./icons/vk-black.svg" alt="VK" class="icon" /> <a href="https://vk.com/bytesp">Мы в
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


        <!-- Модальное окно добавления в корзину -->
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Добавить в корзину</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="product-info-modal">
                    <img id="modal-product-image" src="" alt="" class="product-image-modal">
                    <div class="product-details-modal">
                        <h6 id="modal-product-name"></h6>
                        <p id="modal-product-price" class="price-modal"></p>
                        <p id="modal-stock-info" class="stock-info"></p>
                    </div>
                </div>
                <div class="quantity-selector">
                    <label for="quantity-input">Количество:</label>
                    <div class="quantity-controls">
                        <button type="button" class="quantity-btn" id="decrease-quantity">-</button>
                        <input type="number" id="quantity-input" value="1" min="1" max="1">
                        <button type="button" class="quantity-btn" id="increase-quantity">+</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="confirm-add-to-cart">Добавить в корзину</button>
            </div>
        </div>
    </div>
</div>

<!-- Модалка оформления заказа (для кнопки "Купить" на странице товара) -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Оформление заказа</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="checkout-single-item" class="d-flex align-items-center mb-3">
                    <img id="checkout-product-image" src="" alt="" style="width:64px;height:64px;object-fit:cover;" class="me-3">
                    <div>
                        <div id="checkout-product-name" class="fw-bold"></div>
                        <div id="checkout-product-price" class="text-muted"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="checkout-quantity" class="form-label">Количество</label>
                    <input type="number" id="checkout-quantity" class="form-control" value="1" min="1">
                </div>

                <div class="checkout-total mb-3">
                    <strong>Итого: <span id="checkout-total-price">0 ₽</span></strong>
                </div>

                <form id="checkout-form">
                    <div class="mb-3">
                        <label for="delivery-address" class="form-label">Адрес доставки</label>
                        <textarea class="form-control" id="delivery-address" rows="2" required></textarea>
                    </div>

                    <div id="saved-address-section" class="mb-3" style="display:none;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use-saved-address">
                            <label class="form-check-label" for="use-saved-address">Использовать сохраненный адрес</label>
                        </div>
                        <div id="saved-address-info" class="mt-2 small text-muted"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-success" id="confirm-checkout">Оформить заказ</button>
            </div>
        </div>
    </div>
</div>

<!-- Модалка успеха оформления -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title">Заказ оформлен!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Ваш заказ успешно оформлен.</p>
                <p>Номер заказа: <strong id="order-number">#0000</strong></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
</body>

</html>