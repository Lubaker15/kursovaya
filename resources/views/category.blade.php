<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/catalog.css')}}">
    <script defer src="./js/cart.js"></script>
    <script defer src="./js/category.js"></script>
    <script defer src="{{ asset('js/main.js')}}"></script>
    <script defer src="./js/search.js"></script>
    <title>Байт - Категория</title>
</head>

<body>
    <x-navbar></x-navbar>

    <div class="first-section-full-bg">
        <div class="container first-section">
            <h1 class="title_catalog" id="category-title"></h1>
            
            <!-- Мобильная панель фильтров и сортировки -->
            <div class="mobile-controls-panel" id="mobileControlsPanel">
                <button class="mobile-filter-btn" id="mobileFilterBtn">
                    Фильтры
                </button>
                <button class="mobile-sort-btn" id="mobileSortBtn">
                    Сортировка
                </button>
            </div>
            
            <div class="category-content-wrapper">
                <!-- Секция фильтров -->
                <div class="filters-sidebar" id="filtersSidebar">
                    <div class="filters-header">
                        <h3>Фильтры</h3>
                        <button class="close-filters" id="closeFilters">&times;</button>
                    </div>
                    
                    <div class="filters-content">
                        <div class="filter-group">
                            <label for="priceMin">Цена от:</label>
                            <input type="number" id="priceMin" class="filter-input" placeholder="0 ₽">
                        </div>
                        
                        <div class="filter-group">
                            <label for="priceMax">Цена до:</label>
                            <input type="number" id="priceMax" class="filter-input" placeholder="100000 ₽">
                        </div>
                        
                        <div class="filter-group">
                            <label for="brandFilter">Бренд:</label>
                            <select id="brandFilter" class="filter-select">
                                <option value="">Все бренды</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="availabilityFilter">Наличие:</label>
                            <select id="availabilityFilter" class="filter-select">
                                <option value="">Все товары</option>
                                <option value="in_stock">В наличии</option>
                                <option value="out_of_stock">Нет в наличии</option>
                            </select>
                        </div>
                        
                        <div class="filter-actions">
                            <button class="btn-apply" id="applyFilters">Применить</button>
                            <button class="btn-reset" id="resetFilters">Сбросить</button>
                        </div>
                    </div>
                </div>
                
                <!-- Основной контент -->
                <div class="products-main-content">
                    <!-- Секция сортировки -->
                    <div class="sort-section">
                        <div class="results-count" id="resultsCount"></div>
                        <div class="sort-controls">
                            <select class="sort-select" id="sortSelect">
                                <option value="default">По умолчанию</option>
                                <option value="price_asc">Цена по возрастанию</option>
                                <option value="price_desc">Цена по убыванию</option>
                                <option value="name_asc">Название А-Я</option>
                                <option value="name_desc">Название Я-А</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Товары -->
                    <div id="products-container" class="first-section__card-section"></div>
                    
                    <!-- Пагинация -->
                    <div class="pagination"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно сортировки для мобильных -->
    <div class="mobile-sort-modal" id="mobileSortModal">
        <div class="mobile-modal-content">
            <div class="mobile-modal-header">
                <h3>Сортировка</h3>
                <button class="close-modal" id="closeSortModal">&times;</button>
            </div>
            <div class="mobile-modal-body">
                <div class="sort-options">
                    <label class="sort-option">
                        <input type="radio" name="sort" value="default" checked>
                        <span>По умолчанию</span>
                    </label>
                    <label class="sort-option">
                        <input type="radio" name="sort" value="price_asc">
                        <span>Цена по возрастанию</span>
                    </label>
                    <label class="sort-option">
                        <input type="radio" name="sort" value="price_desc">
                        <span>Цена по убыванию</span>
                    </label>
                    <label class="sort-option">
                        <input type="radio" name="sort" value="name_asc">
                        <span>Название А-Я</span>
                    </label>
                    <label class="sort-option">
                        <input type="radio" name="sort" value="name_desc">
                        <span>Название Я-А</span>
                    </label>
                </div>
                <button class="btn-apply-sort" id="applySort">Применить</button>
            </div>
        </div>
    </div>

    <!-- Добавлена модалка "Добавить в корзину" (для карточек на странице категории) -->
    <div class="modal fade" id="addToCartModalCat" tabindex="-1" aria-labelledby="addToCartModalCatLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="addToCartModalCatLabel" class="modal-title">Добавить в корзину</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body d-flex gap-3 align-items-start">
                    <img id="modal-product-image-cat" src="" alt="" style="width:96px;height:96px;object-fit:cover">
                    <div style="flex:1">
                        <div id="modal-product-name-cat" class="fw-bold mb-1"></div>
                        <div id="modal-product-price-cat" class="text-muted mb-2"></div>
                        <div id="modal-stock-info-cat" class="small text-muted mb-3"></div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-count" id="decrease-quantity-cat">−</button>
                            <input id="quantity-input-cat" type="number" value="1" min="1" style="width:72px;text-align:center" class="form-control">
                            <button type="button" class="btn btn-count" id="increase-quantity-cat">+</button>
                            <div class="ms-auto">
                                <button id="confirm-add-to-cart-cat" class="btn btn-primary">Добавить</button>
                            </div>
                        </div>
                    </div>
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