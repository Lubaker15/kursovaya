<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->product_name }} — Байт</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script defer src="{{ asset('js/product.js') }}"></script>
</head>
<body>
    <x-navbar></x-navbar>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <main class="main">
        <h1 class="main__title">{{ $product->product_name }}</h1>
        <section class="main__wrapper">
            <div id="img-wrapper">
                <img src="{{ asset($product->image_url ?? 'images/no_image.png') }}" class="main__img" alt="{{ $product->product_name }}">
            </div>
            <div class="main__description--wrapper">
                <div class="main__button-wrapper">
                    {{-- Кнопка быстрого заказа "Купить" --}}
                    <button class="btn btn__btn-buy" id="quickBuyBtn"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->product_name }}"
                            data-product-price="{{ $product->unit_price }}"
                            data-product-image="{{ asset($product->image_url ?? 'images/no_image.png') }}"
                            data-product-stock="{{ $product->stock_quantity }}">
                        Купить
                    </button>

                    {{-- Иконка добавления в корзину --}}
                    <button class="main__card-icon" id="addToCartBtn"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->product_name }}"
                            data-product-price="{{ $product->unit_price }}"
                            data-product-image="{{ asset($product->image_url ?? 'images/no_image.png') }}"
                            data-product-stock="{{ $product->stock_quantity }}"
                            style="border: none; background: none; cursor: pointer;">
                        <img src="{{ asset('img/cart.png') }}" alt="Добавить в корзину">
                    </button>
                </div>
                <div class="main__text-wrapper">
                    <div>
                        <span class="main__text--upper">ЦЕНА -</span>
                        <span>{{ number_format($product->unit_price, 0, '.', ' ') }} ₽</span>
                    </div>
                    <div>
                        <span class="main__text--upper">В наличии -</span>
                        <span>{{ $product->stock_quantity }} шт.</span>
                    </div>
                </div>
                <p class="main__description">{{ $product->description }}</p>
            </div>
        </section>
    </main>

    <x-footer></x-footer>

    {{-- Модальное окно добавления в корзину --}}
    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" id="modal-product-id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить в корзину</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-3 align-items-start">
                            <img id="modal-product-image" src="" alt="" style="width:96px;height:96px;object-fit:cover;">
                            <div style="flex:1">
                                <div id="modal-product-name" class="fw-bold mb-1"></div>
                                <div id="modal-product-price" class="text-muted mb-2"></div>
                                <div id="modal-stock-info" class="small text-muted mb-3"></div>

                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('quantity-input').stepDown()">−</button>
                                    <input type="number" name="quantity" id="quantity-input" value="1" min="1" class="form-control" style="width:70px; text-align:center;">
                                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('quantity-input').stepUp()">+</button>
                                    <button type="submit" class="btn btn-primary ms-auto">Добавить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Модальное окно быстрого заказа --}}
    <div class="modal fade" id="quickCheckoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('order.quick') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" id="quick-product-id" value="">
                    <input type="hidden" name="quantity" id="quick-quantity" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title">Оформление заказа</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center mb-3">
                            <img id="quick-product-image" src="" alt="" style="width:64px;height:64px;object-fit:cover;" class="me-3">
                            <div>
                                <div id="quick-product-name" class="fw-bold"></div>
                                <div id="quick-product-price" class="text-muted"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Количество</label>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('quick-quantity-input').stepDown(); updateQuickTotal();">−</button>
                                <input type="number" id="quick-quantity-input" class="form-control" value="1" min="1" style="width:70px; text-align:center;">
                                <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('quick-quantity-input').stepUp(); updateQuickTotal();">+</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="delivery-address" class="form-label">Адрес доставки</label>
                            <textarea name="address" id="delivery-address" class="form-control" rows="2" required></textarea>
                        </div>

                        @auth
                            @if(auth()->user()->address)
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="use-saved-address" onclick="document.getElementById('delivery-address').value = this.checked ? '{{ auth()->user()->address }}' : ''">
                                    <label class="form-check-label" for="use-saved-address">
                                        Использовать сохранённый адрес ({{ auth()->user()->address }})
                                    </label>
                                </div>
                            @endif
                        @endauth

                        <div class="checkout-total mt-3 pt-3 border-top">
                            <strong>Итого: <span id="quick-total">0 ₽</span></strong>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-success">Оформить заказ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Модальное окно успеха (показывается только после редиректа с flash) --}}
    @if(session('order_success'))
        <div class="modal fade show" id="successModal" tabindex="-1" style="display: block;" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-header">
                        <h5 class="modal-title">Заказ оформлен!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Ваш заказ успешно оформлен.</p>
                        <p>Номер заказа: <strong>#{{ session('order_id') }}</strong></p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('successModal')).show();
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.isAuthenticated = @json(auth()->check());
        window.userAddress = @json(auth()->user()->address ?? '');
    </script>
    <script src="{{ asset('js/product.js') }}"></script>
</body>
</html>