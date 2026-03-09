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
    <script defer src="{{ asset('js/main.js')}}"></script>
    <title>Байт - {{ $category->category_name }}</title>
</head>

<body>
    <x-navbar></x-navbar>

    <div class="first-section-full-bg">
        <div class="container first-section">
            <h1 class="title_catalog" id="category-title">{{ $category->category_name }}</h1>
            
            <div class="mobile-controls-panel" id="mobileControlsPanel">
                <button class="mobile-filter-btn" id="mobileFilterBtn">
                    Фильтры
                </button>
                <button class="mobile-sort-btn" id="mobileSortBtn">
                    Сортировка
                </button>
            </div>
            
            <div class="category-content-wrapper">
                <div class="filters-sidebar" id="filtersSidebar">
                    <div class="filters-header">
                        <h3>Фильтры</h3>
                        <button class="close-filters" id="closeFilters">&times;</button>
                    </div>
                    
                    <div class="filters-content">
                        <form method="GET" action="{{ route('category.show', $category->id) }}" id="filter-form">
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            
                            <div class="filter-group">
                                <label for="priceMin">Цена от:</label>
                                <input type="number" name="price_min" id="priceMin" class="filter-input" placeholder="0 ₽" value="{{ request('price_min') }}">
                            </div>
                            
                            <div class="filter-group">
                                <label for="priceMax">Цена до:</label>
                                <input type="number" name="price_max" id="priceMax" class="filter-input" placeholder="100000 ₽" value="{{ request('price_max') }}">
                            </div>
                            
                            <div class="filter-group">
                                <label for="availabilityFilter">Наличие:</label>
                                <select name="in_stock" id="availabilityFilter" class="filter-select">
                                    <option value="">Все товары</option>
                                    <option value="1" {{ request('in_stock') == '1' ? 'selected' : '' }}>В наличии</option>
                                </select>
                            </div>
                            
                            <div class="filter-actions">
                                <button type="submit" class="btn-apply" id="applyFilters">Применить</button>
                                <a href="{{ route('category.show', $category->id) }}" class="btn-reset">Сбросить</a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="products-main-content">
                    <div class="sort-section">
                        <div class="results-count" id="resultsCount">Найдено: {{ $products->total() }} товаров</div>
                        <div class="sort-controls">
                            <select class="sort-select" id="sortSelect" onchange="this.form.submit()">
                                <option value="default" {{ request('sort') == 'default' || !request('sort') ? 'selected' : '' }}>По умолчанию</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена по возрастанию</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена по убыванию</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Название А-Я</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Название Я-А</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="products-container" class="first-section__card-section">
                        @forelse($products as $product)
                            <div class="product-card">
                                <img src="{{ asset($product->image_url ?? 'images/no_image.png') }}" alt="{{ $product->product_name }}" class="product-image">
                                <div class="product-info">
                                    <div class="product-price">{{ number_format($product->unit_price, 0, '.', ' ') }} ₽</div>
                                    <div class="product-title">{{ $product->product_name }}</div>
                                    <div class="product-actions">
                                        <a href="{{ route('product.show', $product->id) }}" class="btn btn__btn-basket-card">Перейти</a>
                                        <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" style="border: none; background: none;">
                                                <img src="{{ asset('img/cart.png') }}" alt="В корзину" class="cart-icon">
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Товары не найдены</p>
                        @endforelse
                    </div>
                    
                <div class="pagination">
                    {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-filters-modal" id="mobileFiltersModal" style="display: none;">
        <div class="mobile-modal-content">
            <div class="mobile-modal-header">
                <h3>Фильтры</h3>
                <button class="close-modal" id="closeFiltersModal">&times;</button>
            </div>
            <div class="mobile-modal-body">
                <form method="GET" action="{{ route('category.show', $category->id) }}" id="mobile-filter-form">
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    
                    <div class="filter-group">
                        <label for="mobilePriceMin">Цена от:</label>
                        <input type="number" name="price_min" id="mobilePriceMin" class="filter-input" value="{{ request('price_min') }}">
                    </div>
                    <div class="filter-group">
                        <label for="mobilePriceMax">Цена до:</label>
                        <input type="number" name="price_max" id="mobilePriceMax" class="filter-input" value="{{ request('price_max') }}">
                    </div>
                    <div class="filter-group">
                        <label for="mobileAvailability">Наличие:</label>
                        <select name="in_stock" id="mobileAvailability" class="filter-select">
                            <option value="">Все</option>
                            <option value="1" {{ request('in_stock') == '1' ? 'selected' : '' }}>В наличии</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-apply">Применить</button>
                </form>
            </div>
        </div>
    </div>

    <div class="mobile-sort-modal" id="mobileSortModal" style="display: none;">
        <div class="mobile-modal-content">
            <div class="mobile-modal-header">
                <h3>Сортировка</h3>
                <button class="close-modal" id="closeSortModal">&times;</button>
            </div>
            <div class="mobile-modal-body">
                <div class="sort-options">
                    <label class="sort-option">
                        <input type="radio" name="mobileSort" value="default" {{ request('sort') == 'default' || !request('sort') ? 'checked' : '' }}>
                        <span>По умолчанию</span>
                    </label>
                    <label class="sort-option">
                        <input type="radio" name="mobileSort" value="price_asc" {{ request('sort') == 'price_asc' ? 'checked' : '' }}>
                        <span>Цена по возрастанию</span>
                    </label>
                    <label class="sort-option">
                        <input type="radio" name="mobileSort" value="price_desc" {{ request('sort') == 'price_desc' ? 'checked' : '' }}>
                        <span>Цена по убыванию</span>
                    </label>
                    <label class="sort-option">
                        <input type="radio" name="mobileSort" value="name_asc" {{ request('sort') == 'name_asc' ? 'checked' : '' }}>
                        <span>Название А-Я</span>
                    </label>
                    <label class="sort-option">
                        <input type="radio" name="mobileSort" value="name_desc" {{ request('sort') == 'name_desc' ? 'checked' : '' }}>
                        <span>Название Я-А</span>
                    </label>
                </div>
                <button class="btn-apply-sort" id="applyMobileSort">Применить</button>
            </div>
        </div>
    </div>

    <x-footer></x-footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileFilterBtn = document.getElementById('mobileFilterBtn');
            const mobileSortBtn = document.getElementById('mobileSortBtn');
            const filtersModal = document.getElementById('mobileFiltersModal');
            const sortModal = document.getElementById('mobileSortModal');
            const closeFilters = document.getElementById('closeFiltersModal');
            const closeSort = document.getElementById('closeSortModal');
            
            if (mobileFilterBtn) {
                mobileFilterBtn.addEventListener('click', function() {
                    filtersModal.style.display = 'block';
                });
            }
            if (mobileSortBtn) {
                mobileSortBtn.addEventListener('click', function() {
                    sortModal.style.display = 'block';
                });
            }
            if (closeFilters) {
                closeFilters.addEventListener('click', function() {
                    filtersModal.style.display = 'none';
                });
            }
            if (closeSort) {
                closeSort.addEventListener('click', function() {
                    sortModal.style.display = 'none';
                });
            }
            
            const applySortBtn = document.getElementById('applyMobileSort');
            if (applySortBtn) {
                applySortBtn.addEventListener('click', function() {
                    const selected = document.querySelector('input[name="mobileSort"]:checked');
                    if (selected) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('sort', selected.value);
                        window.location.href = url.toString();
                    }
                });
            }
            
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('sort', this.value);
                    window.location.href = url.toString();
                });
            }
        });
    </script>
</body>
</html>