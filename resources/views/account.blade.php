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
    <link rel="stylesheet" href="{{ asset('css/account.css')}}">
    <script defer src="./js/cart.js"></script>
    <script defer src="{{ asset('js/main.js')}}"></script>
    <!-- <script defer src="./js/account.js"></script> -->
    <script defer src="./js/search.js"></script>
    <title>Личный кабинет - Байт</title>
</head>

<body>
    <x-navbar></x-navbar>

    <div class="first-section-full-bg">
        <main class="lk-main container">
            <h1 class="lk-main__title">Личный кабинет</h1>
            <div class="lk-main__content-wrapper">
                <aside class="lk-sidebar">
                    <div class="lk-sidebar__user-info">
                        <p class="lk-sidebar__username">Иван Иванов</p>
                        <p class="lk-sidebar__useremail">ivan.ivanov@example.com</p>
                    </div>
                    <nav class="lk-sidebar__nav">
                        <a href="#" class="lk-sidebar__nav-item active">
                            Персональные данные
                        </a>
                        <a href="#" class="lk-sidebar__nav-item">
                            История заказов
                        </a>
                        <a href="#" class="lk-sidebar__nav-item">
                            Уведомления
                            <span class="badge bg-danger" style="display: none;">0</span>
                        </a>
                        <a href="#" class="lk-sidebar__nav-item">
                            Сохраненная корзина
                        </a>
                        <a href="#" class="lk-sidebar__nav-item lk-sidebar__nav-item--logout">
                            Выйти из системы
                        </a>
                    </nav>
                </aside>
                <section class="lk-content">
                    <div id="personal-data" class="lk-content__section active">
                        <h2>Персональные данные</h2>
                        <form class="lk-form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first-name" class="form-label">Имя</label>
                                    <input type="text" class="form-control" id="first-name" value="Иван">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last-name" class="form-label">Фамилия</label>
                                    <input type="text" class="form-control" id="last-name" value="Иванов">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="tel" class="form-control" id="phone" value="7-925-047-81-12">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Адрес</label>
                                <input type="text" class="form-control" id="address"
                                    value="Сергиев Посад, ул. Вознесенская 53а">
                            </div>
                            <div class="mb-3">
                                <label for="new-password" class="form-label">Новый пароль</label>
                                <input type="password" class="form-control" id="new-password"
                                    placeholder="Введите новый пароль">
                                <div class="form-text">Оставьте пустым, чтобы не менять.</div>
                            </div>
                            <button type="submit" class="btn btn-success">Сохранить изменения</button>
                        </form>
                        <div class="lk-profile-actions mt-4">
                            <button class="btn btn-outline-danger" id="delete-profile-btn">Удалить профиль</button>
                        </div>
                    </div>
                    <div id="order-history" class="lk-content__section">
                        <h2>История заказов</h2>
                        <table class="table table-striped lk-orders-table">
                            <thead>
                                <tr>
                                    <th scope="col">Номер заказа</th>
                                    <th scope="col">Дата</th>
                                    <th scope="col">Сумма</th>
                                    <th scope="col">Статус</th>
                                    <th scope="col">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div id="notifications" class="lk-content__section">
                        <h2>Уведомления
                            <span class="badge bg-danger" id="unread-notifications-count"
                                style="display: none;">0</span>
                        </h2>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary active"
                                    data-filter="all">Все</button>
                                <button type="button" class="btn btn-outline-primary"
                                    data-filter="unread">Непрочитанные</button>
                                <button type="button" class="btn btn-outline-primary"
                                    data-filter="read">Прочитанные</button>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="mark-all-read">
                                Отметить все как прочитанные
                            </button>
                        </div>

                        <div class="list-group" id="notifications-list">
                        </div>

                        <div id="no-notifications" class="text-center py-4" style="display: none;">
                            <div class="text-muted">
                                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                <p>У вас нет уведомлений</p>
                            </div>
                        </div>

                        <nav aria-label="Навигация по уведомлениям" id="notifications-pagination"
                            style="display: none;">
                            <ul class="pagination justify-content-center mt-4">
                                <li class="page-item disabled" id="prev-page">
                                    <a class="page-link" href="#" tabindex="-1">Назад</a>
                                </li>
                                <li class="page-item">
                                    <span class="page-link text-muted" id="page-info">Страница 1 из 1</span>
                                </li>
                                <li class="page-item" id="next-page">
                                    <a class="page-link" href="#">Вперед</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div id="saved-basket" class="lk-content__section">
                        <h2>Сохраненная корзина</h2>
                        <div class="alert alert-info" role="alert">
                        </div>
                        <a href="./cart.html" class="btn btn-primary">Перейти к корзине</a>
                    </div>
                    <div id="admin-panel" class="lk-content__section">
                        <h2>Панель администратора</h2>

                        <!-- Статистика -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary">
                                    <div class="card-body">
                                        <h5 class="card-title" id="total-users">0</h5>
                                        <p class="card-text">Пользователей</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title" id="total-orders">0</h5>
                                        <p class="card-text">Всего заказов</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning">
                                    <div class="card-body">
                                        <h5 class="card-title" id="pending-orders">0</h5>
                                        <p class="card-text">Ожидают оплаты</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Управление пользователями -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Управление пользователями</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="users-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Логин</th>
                                                <th>Имя</th>
                                                <th>Email</th>
                                                <th>Роль</th>
                                                <th>Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutConfirmModalLabel">Подтверждение выхода</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">Вы уверены, что хотите выйти?</h6>
                            <p class="text-muted mb-0">Для доступа к личному кабинету потребуется снова войти в систему.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-warning" id="confirm-logout">Выйти</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления профиля -->
    <div class="modal fade" id="deleteProfileConfirmModal" tabindex="-1"
        aria-labelledby="deleteProfileConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProfileConfirmModalLabel">Удаление профиля</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">Вы уверены, что хотите удалить профиль?</h6>
                            <p class="text-muted mb-0">Это действие нельзя отменить. Все ваши данные, включая историю
                                заказов, будут безвозвратно удалены.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-profile">Удалить профиль</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно уведомления -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Уведомление</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="notificationModalBody">
                    <!-- Текст уведомления будет вставлен сюда -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
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

    <!-- Подключение Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>