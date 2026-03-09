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
    <!-- <script defer src="./js/cart.js"></script> -->
    <script defer src="{{ asset('js/main.js')}}"></script>
    <!-- <script defer src="./js/cart-page.js"></script> -->
    <!-- <script defer src="./js/search.js"></script> -->
    <title>Корзина</title>
</head>
<body>
    <x-navbar></x-navbar>

    <section class="cart-section">
        <div class="container">
            <h1 class="cart-title">Корзина</h1>
            
            <div class="cart-container">
                @if(count($products) > 0)
                    <div class="cart-header">
                        <div>Товар</div>
                        <div>Цена</div>
                        <div>Количество</div>
                        <div>Итого</div>
                        <div></div>
                    </div>
                    @foreach($products as $item)
                        <div class="cart-item" data-product-id="{{ $item['product']->id }}">
                            <div class="product-info">
                                <img src="{{ asset($item['product']->image_url ?? 'images/no_image.png') }}" alt="{{ $item['product']->product_name }}" class="product-image">
                                <div>
                                    <div class="product-name">{{ $item['product']->product_name }}</div>
                                    <div class="product-category">{{ $item['product']->category->category_name ?? '' }}</div>
                                </div>
                            </div>
                            <div class="product-price">{{ number_format($item['product']->unit_price, 0, '.', ' ') }} ₽</div>
                            <div class="quantity-controls">
                                <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" style="display: flex; align-items: center;">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                        min="1" max="{{ $item['product']->stock_quantity }}" 
                                        class="quantity-input" style="width: 60px;" 
                                        onchange="this.form.submit()">
                                </form>
                            </div>
                            <div class="item-total">{{ number_format($item['subtotal'], 0, '.', ' ') }} ₽</div>
                            <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="remove-btn">×</button>
                            </form>
                        </div>
                    @endforeach
                    <div class="cart-summary">
                        <div class="total-price">Итого: {{ number_format($total, 0, '.', ' ') }} ₽</div>
                        @auth
                        <button type="button" class="checkout-btn" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            Оформить заказ
                        </button>
                        @else
                        <a href="/auth?message=login_required" class="checkout-btn">
                            Оформить заказ
                        </a>
                        @endauth
                    </div>
                @else
                    <div class="empty-cart">
                        <h2 class="empty-cart-title">Корзина пуста</h2>
                        <p class="empty-cart-text">В вашей корзине пока нет товаров. Перейдите в каталог, чтобы добавить товары в корзину.</p>
                        <a href="/catalog" class="btn-catalog">Перейти в каталог</a>
                    </div>
                @endif
            </div>
            
            <a href="/catalog" class="continue-shopping">← Продолжить покупки</a>
        </div>
    </section>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('order.place') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Оформление заказа</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">Адрес доставки</label>
                            <textarea name="address" id="address" class="form-control" rows="3" required>{{ auth()->user()->address ?? '' }}</textarea>
                        </div>
                        @if(auth()->check() && auth()->user()->address)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="useSavedAddress" checked 
                                    onclick="document.getElementById('address').value = this.checked ? '{{ auth()->user()->address }}' : ''">
                                <label class="form-check-label" for="useSavedAddress">
                                    Использовать сохранённый адрес
                                </label>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-success">Подтвердить заказ</button>
                    </div>
                </form>
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
                    <button type="button" class="btn btn-primary" id="go-to-main"><a href="/">На главную</a></button>
                    <button type="button" class="btn btn-outline-primary" id="view-orders"><a href="{{ route('account.orders') }}">Мои заказы</a></button>
                </div>
            </div>
        </div>
    </div>

    <x-footer></x-footer>



    <script>
    document.getElementById('useSavedAddress')?.addEventListener('change', function() {
        const addressField = document.getElementById('address');
        if (this.checked) {
            addressField.value = '{{ auth()->user()->address ?? '' }}';
        } else {
            addressField.value = '';
        }
    });
    </script>
    @if(session('order_success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('order-number').textContent = '#' + {{ session('order_id') }};
            
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>