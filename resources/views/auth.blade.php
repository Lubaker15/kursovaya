<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Вход и регистрация</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css')}}">
    <link rel="stylesheet" href="{{ asset('css/auth.css')}}" />
    <script defer src="{{ asset('js/auth.js')}}"></script>
</head>
<body class="page">
    <div class="auth">
        <a class="logo" href="/"><img src="./icons/logo.png" alt=""></a>

        <div class="auth__tabs">
            <button class="auth__tab auth__tab--active" data-tab="login">Вход</button>
            <button class="auth__tab" data-tab="register">Регистрация</button>
        </div>

        <div class="auth__content">
            <!-- Вход -->
            <form class="auth__form auth__form--active" id="login-form" method='POST' action="{{ route('login.post')}}">
                @csrf
                <div class="auth__field">
                    <label class="auth__label" for="login-email">E-mail</label>
                    <input value="{{ old('email') }}" name="email" class="auth__input" type="email" id="login-email" required />
                    <span class="auth__error" id="login-email-error"></span>
                </div>
                <div class="auth__field">
                    <label class="auth__label" for="login-password">Пароль</label>
                    <input name="password" class="auth__input" type="password" id="login-password" required />
                    <span class="auth__error" id="login-password-error"></span>
                </div>
                <button type="submit" class="auth__button">Войти</button>
                @error('email')
                    <span style="color:red;" class="error">{{ $message }}</span>
                @enderror
                @error('login')
                    <span style="color:red;" class="error">{{ $message }}</span>
                @enderror
            </form>

            <!-- Регистрация -->
            <form class="auth__form" id="register-form" method='POST' action="{{ route('register') }}">
                @csrf
                <div class="auth__field">
                    <label class="auth__label" for="register-login">Логин *</label>
                    <input name="login" class="auth__input" type="text" id="register-login" required
                        placeholder="От 3 символов, только буквы и цифры" />
                    <span class="auth__error" id="register-login-error"></span>
                </div>
                <div class="auth__field">
                    <label class="auth__label" for="register-password">Пароль *</label>
                    <input name="password" class="auth__input" type="password" id="register-password" required
                        placeholder="Минимум 6 символов" />
                    <span class="auth__error" id="register-password-error"></span>
                    <div class="password-strength" id="password-strength"></div>
                </div>
                <div class="auth__field">
                    <label class="auth__label" for="register-first">Имя *</label>
                    <input name="first_name" class="auth__input" type="text" id="register-first" required />
                    <span class="auth__error" id="register-first-error"></span>
                </div>
                <div class="auth__field">
                    <label class="auth__label" for="register-last">Фамилия *</label>
                    <input name="last_name" class="auth__input" type="text" id="register-last" required />
                    <span class="auth__error" id="register-last-error"></span>
                </div>
                <div class="auth__field">
                    <label class="auth__label" for="register-email">E-mail *</label>
                    <input name="email" class="auth__input" type="email" id="register-email" required />
                    <span class="auth__error" id="register-email-error"></span>
                </div>
                <div class="auth__field">
                    <label class="auth__label" for="register-phone">Телефон *</label>
                    <input name="phone" class="auth__input" type="tel" id="register-phone" required placeholder="+7 XXX XXX XX XX" />
                    <span class="auth__error" id="register-phone-error"></span>
                    <div class="phone-hint">Формат: +7XXXXXXXXXX</div>
                </div>
                <button type="submit" class="auth__button">Зарегистрироваться</button>
            </form>
        </div>
    </div>
</body>

</html>