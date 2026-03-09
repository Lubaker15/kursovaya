@php
    $cart = session()->get('cart', []);
    $cartCount = array_sum($cart); 
@endphp
<script defer src="{{ asset('js/search.js') }}"></script>
<nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="logo">
                <img src="{{ asset('icons/logo.png') }}" alt="Логотип">
            </a>
            
            <button class="btn-catalog">
                <img src="{{ asset('icons/catalog-svgrepo-com.svg') }}" alt="Каталог">
                <a href="/catalog">Каталог</a>
            </button>
            
            <div class="search-container">
                <img src="{{ asset('icons/lupa.png') }}" alt="Поиск">
                <input type="text" placeholder="Поиск по каталогу">
            </div>
            
            <a href="/cart" class="btn-basket">
                <img src=" {{ asset('icons/cart-shopping.svg') }}" alt="Корзина">
                <span>Перейти в корзину</span>
                @if($cartCount > 0)
                <span class="cart-counter">{{ $cartCount }}</span>
                @else
                <span class="cart-counter" style="display: none;">0</span>
                @endif
            </a>
            @auth
            <a href="{{ route('account.orders') }}" class="reg-account account-link" id="account-link">
                <img class="icon-user account-icon" src="{{ asset('icons/ls.svg') }}" alt="Пользователь">
                <h6 class="account-text">{{ auth()->user()->first_name }}</h6>
            </a>
            @endauth
            @guest
            <a href="/auth" class="reg-account account-link" id="account-link">
                <h6 class="account-text">Зарегистрироваться</h6>
            </a>
            @endguest      
            <div class="nav-links">
                <a href="/delivery">Доставка и оплата</a>
                <a href="/service">Сервисный центр</a>
                <a href="/contacts">Контакты</a>
            </div>
            
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        
        <div class="mobile-menu" id="mobileMenu">
            <a href="/delivery">Доставка и оплата</a>
            <a href="/service">Сервисный центр</a>
            <a href="/contacts">Контакты</a>
            @auth
            <a href="{{ route('account.orders') }}" class="reg-account account-link" id="account-link">
                <img class="icon-user account-icon" src="{{ asset('icons/ls.svg') }}" alt="Пользователь">
                <h6 class="account-text">{{ auth()->user()->first_name }}</h6>
            </a>
            @endauth
            @guest
            <a href="/auth" class="reg-account account-link" id="account-link">
                <h6 class="account-text">Зарегистрироваться</h6>
            </a>
            @endguest
        </div>
</nav>