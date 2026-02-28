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
    <link rel="stylesheet" href="{{ asset('css/index.css')}}">
    <link rel="stylesheet" href="{{ asset('css/cart.css')}}">
    <script defer src="./js/cart.js"></script>
    <script defer src="{{ asset('js/main.js')}}"></script>
    <script defer src="./js/cart-page.js"></script>
    <script defer src="./js/search.js"></script>
    <title>Корзина</title>
</head>
<body>
    <x-navbar></x-navbar>

    <section class="cart-section">
        <div class="container">
            <h1 class="cart-title">Корзина</h1>
            
            <div class="cart-container">
            </div>
            
            <a href="/catalog.html" class="continue-shopping">← Продолжить покупки</a>
        </div>
    </section>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Оформление заказа</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="checkout-summary mb-4">
                        <h6>Содержимое заказа:</h6>
                        <div id="checkout-items-list">

                        </div>
                        <div class="checkout-total mt-3 pt-3 border-top">
                            <strong>Итого: <span id="checkout-total-price">0 ₽</span></strong>
                        </div>
                    </div>
                    
                    <form id="checkout-form">
                        <div class="mb-3">
                            <label for="delivery-address" class="form-label">Адрес доставки</label>
                            <textarea class="form-control" id="delivery-address" rows="3" placeholder="Введите адрес доставки" required></textarea>
                            <div class="form-text">Укажите полный адрес для доставки</div>
                        </div>
                        
                        <div id="saved-address-section" class="mb-3" style="display: none;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="use-saved-address">
                                <label class="form-check-label" for="use-saved-address">
                                    Использовать сохраненный адрес
                                </label>
                            </div>
                            <div id="saved-address-info" class="mt-2 p-2 bg-light rounded">

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-success" id="confirm-checkout">Подтвердить заказ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Заказ оформлен!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h6>Ваш заказ успешно оформлен</h6>
                    <p class="text-muted">Номер вашего заказа: <strong id="order-number">#0000</strong></p>
                    <p>Спасибо за покупку! Мы свяжемся с вами для уточнения деталей доставки.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" id="go-to-main">На главную</button>
                    <button type="button" class="btn btn-outline-primary" id="view-orders">Мои заказы</button>
                </div>
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