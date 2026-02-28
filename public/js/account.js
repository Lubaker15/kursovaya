class AccountService {
    constructor() {
        this.currentUser = JSON.parse(localStorage.getItem('currentUser'));
        this.orders = JSON.parse(localStorage.getItem('orders')) || [];
        this.users = JSON.parse(localStorage.getItem('registeredUsers')) || [];
        this.notifications = JSON.parse(localStorage.getItem(`notifications_${this.currentUser?.user_id}`)) || [];
        
        this.notificationsPerPage = 5;
        this.currentNotificationsPage = 1;
        this.totalNotificationsPages = 1;
        
        this.isAdmin = this.currentUser?.role === 'Администратор';
        
        this.init();
    }

    init = () => {
        if (!this.currentUser) {
            window.location.href = 'auth.html';
            return;
        }

        this.loadUserData();
        this.loadOrderHistory();
        this.setupEventListeners();
        this.updateSavedCartInfo();
        this.loadNotifications();
        
        if (this.isAdmin) {
            this.loadAdminPanel();
            this.setupAdminEventListeners();
        }
    }

    loadUserData = () => {
        const usernameElement = document.querySelector('.lk-sidebar__username');
        const useremailElement = document.querySelector('.lk-sidebar__useremail');
        
        if (usernameElement) {
            usernameElement.textContent = `${this.currentUser.first_name} ${this.currentUser.last_name}`;
            if (this.isAdmin) {
                usernameElement.innerHTML += ' <span class="badge bg-danger">Администратор</span>';
            }
        }
        
        if (useremailElement) {
            useremailElement.textContent = this.currentUser.email;
        }

        document.getElementById('first-name').value = this.currentUser.first_name;
        document.getElementById('last-name').value = this.currentUser.last_name || '';
        document.getElementById('phone').value = this.currentUser.phone || '';
        
        const userAddress = this.currentUser.address || 'Сергиев Посад, ул. Вознесенская 53а';
        document.getElementById('address').value = userAddress;
    }

    loadAdminPanel = () => {
        this.createAdminPanel();
        this.updateAdminStatistics();
        this.loadUsersTable();
        this.addAdminMenuItem();
    }

    createAdminPanel = () => {
        const adminPanelHTML = `
            <div id="admin-panel" class="lk-content__section">
                <h2>Панель администратора</h2>
                
                <!-- Статистика -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title" id="total-users">0</h5>
                                <p class="card-text">Всего пользователей</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title" id="total-orders">0</h5>
                                <p class="card-text">Всего заказов</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h5 class="card-title" id="pending-orders">0</h5>
                                <p class="card-text">Ожидают оплаты</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Управление пользователями -->
                <div class="card">
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
                                    <!-- Данные будут заполнены через JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const lkContent = document.querySelector('.lk-content');
        if (lkContent) {
            lkContent.insertAdjacentHTML('beforeend', adminPanelHTML);
        }
    }

    addAdminMenuItem = () => {
        const sidebarNav = document.querySelector('.lk-sidebar__nav');
        const adminMenuItem = document.createElement('a');
        adminMenuItem.href = '#';
        adminMenuItem.className = 'lk-sidebar__nav-item';
        adminMenuItem.innerHTML = `
            Панель администратора
            <span class="badge bg-danger">ADMIN</span>
        `;
        
        const logoutItem = document.querySelector('.lk-sidebar__nav-item--logout');
        if (sidebarNav && logoutItem) {
            sidebarNav.insertBefore(adminMenuItem, logoutItem);
            
            adminMenuItem.addEventListener('click', (e) => {
                e.preventDefault();
                this.showAdminPanel();
            });
        }
    }

    showAdminPanel = () => {
        const navItems = document.querySelectorAll('.lk-sidebar__nav-item');
        const contentSections = document.querySelectorAll('.lk-content__section');
        
        navItems.forEach(i => i.classList.remove('active'));
        contentSections.forEach(s => s.classList.remove('active'));

        const adminNavItems = document.querySelectorAll('.lk-sidebar__nav-item');
        adminNavItems.forEach(item => {
            if (item.textContent.includes('Панель администратора')) {
                item.classList.add('active');
            }
        });
        
        const adminSection = document.getElementById('admin-panel');
        if (adminSection) {
            adminSection.classList.add('active');
        }
        
        this.updateAdminStatistics();
        this.loadUsersTable();
    }

    updateAdminStatistics = () => {
        if (!this.isAdmin) return;

        const totalUsersElement = document.getElementById('total-users');
        if (totalUsersElement) {
            totalUsersElement.textContent = this.users.length;
        }
        
        const totalOrdersElement = document.getElementById('total-orders');
        if (totalOrdersElement) {
            totalOrdersElement.textContent = this.orders.length;
        }
        
        const pendingOrdersElement = document.getElementById('pending-orders');
        if (pendingOrdersElement) {
            const pendingOrders = this.orders.filter(order => order.payment_status === 'ожидает оплаты');
            pendingOrdersElement.textContent = pendingOrders.length;
        }
    }

    loadUsersTable = () => {
        if (!this.isAdmin) return;

        const usersTable = document.querySelector('#users-table tbody');
        if (!usersTable) return;

        let usersHTML = '';
        
        this.users.forEach(user => {
            const isCurrentUser = user.user_id === this.currentUser.user_id;
            
            usersHTML += `
                <tr ${isCurrentUser ? 'class="table-warning"' : ''}>
                    <td>${user.user_id}</td>
                    <td>${user.login}</td>
                    <td>${user.first_name} ${user.last_name}</td>
                    <td>${user.email}</td>
                    <td>
                        <select class="form-select form-select-sm user-role-select" data-user-id="${user.user_id}">
                            <option value="Покупатель" ${user.role === 'Покупатель' ? 'selected' : ''}>Покупатель</option>
                            <option value="Администратор" ${user.role === 'Администратор' ? 'selected' : ''}>Администратор</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary view-user-orders" data-user-id="${user.user_id}">
                            Заказы
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-user-btn" 
                                data-user-id="${user.user_id}" 
                                data-user-name="${user.first_name} ${user.last_name}"
                                ${isCurrentUser ? 'data-self="true"' : ''}>
                            Удалить
                        </button>
                    </td>
                </tr>
            `;
        });

        usersTable.innerHTML = usersHTML;
        
        this.setupAdminUserHandlers();
    }

    setupAdminEventListeners = () => {
    }

    setupAdminUserHandlers = () => {
        const roleSelects = document.querySelectorAll('.user-role-select');
        roleSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                const userId = parseInt(e.target.dataset.userId);
                const newRole = e.target.value;
                this.changeUserRole(userId, newRole);
            });
        });

        const viewOrdersButtons = document.querySelectorAll('.view-user-orders');
        viewOrdersButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const userId = parseInt(e.target.dataset.userId);
                this.showUserOrders(userId);
            });
        });

        const deleteUserButtons = document.querySelectorAll('.delete-user-btn');
        deleteUserButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const userId = parseInt(e.target.dataset.userId);
                const userName = e.target.dataset.userName;
                const isSelf = e.target.dataset.self === 'true';
                this.showDeleteUserConfirm(userId, userName, isSelf);
            });
        });
    }

    changeUserRole = (userId, newRole) => {
        const userIndex = this.users.findIndex(u => u.user_id === userId);
        if (userIndex === -1) return;

        const user = this.users[userIndex];
        const oldRole = user.role;
        
        user.role = newRole;
        this.users[userIndex] = user;
        
        localStorage.setItem('registeredUsers', JSON.stringify(this.users));
        
        if (userId === this.currentUser.user_id) {
            this.currentUser.role = newRole;
            localStorage.setItem('currentUser', JSON.stringify(this.currentUser));
            this.isAdmin = newRole === 'Администратор';
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
        
        this.showModalNotification(
            `Роль пользователя ${user.first_name} ${user.last_name} изменена с "${oldRole}" на "${newRole}"`,
            'Роль изменена'
        );
    }

    changeOrderStatus = (orderId, newStatus) => {
        const orderIndex = this.orders.findIndex(o => o.order_id === orderId);
        if (orderIndex === -1) return;

        const order = this.orders[orderIndex];
        const oldStatus = order.payment_status;
        
        order.payment_status = newStatus;
        this.orders[orderIndex] = order;
        
        localStorage.setItem('orders', JSON.stringify(this.orders));
        
        this.showModalNotification(
            `Статус заказа #${orderId} изменен с "${oldStatus}" на "${newStatus}"`,
            'Статус изменен'
        );
    }

    showUserOrders = (userId) => {
        const user = this.users.find(u => u.user_id === userId);
        if (!user) return;

        const userOrders = this.orders.filter(order => order.user_id === userId);
        
        let ordersHTML = '';
        userOrders.forEach(order => {
            const orderDate = new Date(order.order_date).toLocaleDateString('ru-RU');
            const totalAmount = order.items.reduce((sum, item) => sum + item.total_price, 0);
            
            ordersHTML += `
                <tr>
                    <td>#${order.order_id}</td>
                    <td>${orderDate}</td>
                    <td>${totalAmount.toLocaleString('ru-RU')} ₽</td>
                    <td>
                        <select class="form-select form-select-sm order-status-select" data-order-id="${order.order_id}">
                            <option value="ожидает оплаты" ${order.payment_status === 'ожидает оплаты' ? 'selected' : ''}>Ожидает оплаты</option>
                            <option value="в обработке" ${order.payment_status === 'в обработке' ? 'selected' : ''}>В обработке</option>
                            <option value="оплачено" ${order.payment_status === 'оплачено' ? 'selected' : ''}>Оплачено</option>
                            <option value="доставляется" ${order.payment_status === 'доставляется' ? 'selected' : ''}>Доставляется</option>
                            <option value="отменен" ${order.payment_status === 'отменен' ? 'selected' : ''}>Отменен</option>
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>
            `;
        });

        const modalHTML = `
            <div class="modal fade" id="userOrdersModal" tabindex="-1" aria-labelledby="userOrdersModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userOrdersModalLabel">Заказы пользователя: ${user.first_name} ${user.last_name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Номер заказа</th>
                                            <th>Дата</th>
                                            <th>Сумма</th>
                                            <th>Статус</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${userOrders.length > 0 ? ordersHTML : `
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-shopping-bag fa-2x mb-2"></i>
                                                    <p>У пользователя нет заказов</p>
                                                </div>
                                            </td>
                                        </tr>
                                        `}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const existingModal = document.getElementById('userOrdersModal');
        if (existingModal) {
            existingModal.remove();
        }

        document.body.insertAdjacentHTML('beforeend', modalHTML);
    
        const statusSelects = document.querySelectorAll('.order-status-select');
        statusSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                const orderId = parseInt(e.target.dataset.orderId);
                const newStatus = e.target.value;
                this.changeOrderStatus(orderId, newStatus);
            });
        });

        this.setupOrderDetailsHandlers();
        
        const userOrdersModal = new bootstrap.Modal(document.getElementById('userOrdersModal'));
        userOrdersModal.show();
    }

    showDeleteUserConfirm = (userId, userName, isSelf) => {
        const modalHTML = `
            <div class="modal fade" id="deleteUserConfirmModal" tabindex="-1" aria-labelledby="deleteUserConfirmModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteUserConfirmModalLabel">Удаление пользователя</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1">Вы уверены, что хотите удалить пользователя ${userName}?</h6>
                                    <p class="text-muted mb-0">
                                        ${isSelf ? 
                                            'Это ваш собственный профиль. После удаления вы будете перенаправлены на главную страницу.' : 
                                            'Это действие нельзя отменить. Все данные пользователя будут безвозвратно удалены.'
                                        }
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="button" class="btn btn-danger" id="confirm-delete-user" data-user-id="${userId}" data-is-self="${isSelf}">
                                Удалить пользователя
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const existingModal = document.getElementById('deleteUserConfirmModal');
        if (existingModal) {
            existingModal.remove();
        }

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        const confirmDeleteBtn = document.getElementById('confirm-delete-user');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', () => {
                this.deleteUser(userId, isSelf);
            });
        }
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserConfirmModal'));
        deleteModal.show();
    }

    deleteUser = (userId, isSelf) => {
        const userToDelete = this.users.find(u => u.user_id === userId);
        const updatedUsers = this.users.filter(u => u.user_id !== userId);
        localStorage.setItem('registeredUsers', JSON.stringify(updatedUsers));
        
        localStorage.removeItem(`cart_${userId}`);
        
        localStorage.removeItem(`notifications_${userId}`);
        
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteUserConfirmModal'));
        if (deleteModal) {
            deleteModal.hide();
        }
        
        if (isSelf) {
            localStorage.removeItem('currentUser');
            this.showModalNotification('Ваш профиль был успешно удален', 'Профиль удален');
            
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 1500);
        } else {
            this.showModalNotification(`Пользователь ${userToDelete.first_name} ${userToDelete.last_name} удален`, 'Пользователь удален');
            this.loadUsersTable();
            this.updateAdminStatistics();
        }
    }

    loadOrderHistory = () => {
        const ordersTable = document.querySelector('.lk-orders-table tbody');
        if (!ordersTable) return;

        const userOrders = this.orders.filter(order => order.user_id === this.currentUser.user_id);
        
        if (userOrders.length === 0) {
            ordersTable.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-shopping-bag fa-2x mb-2"></i>
                            <p>У вас еще нет заказов</p>
                            <a href="/catalog.html" class="btn btn-primary btn-sm">Перейти в каталог</a>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        let ordersHTML = '';
        
        userOrders.forEach(order => {
            const orderDate = new Date(order.order_date).toLocaleDateString('ru-RU');
            const totalAmount = order.items.reduce((sum, item) => sum + item.total_price, 0);
            const statusBadge = this.getStatusBadge(order.payment_status);
            
            ordersHTML += `
                <tr>
                    <td>#${order.order_id}</td>
                    <td>${orderDate}</td>
                    <td>${totalAmount.toLocaleString('ru-RU')} ₽</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary view-order-details" data-order-id="${order.order_id}">
                            Подробнее
                        </button>
                    </td>
                </tr>
            `;
        });

        ordersTable.innerHTML = ordersHTML;
        this.setupOrderDetailsHandlers();
    }

    getStatusBadge = (status) => {
        const statusMap = {
            'оплачено': 'bg-success',
            'ожидает оплаты': 'bg-warning text-dark',
            'отменен': 'bg-danger',
            'в обработке': 'bg-info',
            'доставляется': 'bg-primary'
        };
        
        const badgeClass = statusMap[status] || 'bg-secondary';
        return `<span class="badge ${badgeClass}">${status}</span>`;
    }

    setupEventListeners = () => {
        const personalForm = document.querySelector('.lk-form');
        if (personalForm) {
            personalForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.savePersonalData();
            });
        }

        const logoutBtn = document.querySelector('.lk-sidebar__nav-item--logout');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showLogoutConfirm();
            });
        }

        const deleteProfileBtn = document.getElementById('delete-profile-btn');
        if (deleteProfileBtn) {
            deleteProfileBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showDeleteProfileConfirm();
            });
        }

        const refreshProfileBtn = document.getElementById('refresh-profile-btn');
        if (refreshProfileBtn) {
            refreshProfileBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.refreshProfileData();
            });
        }

        this.setupModalHandlers();
        this.setupNavigation();
        this.setupNotificationsHandlers();
        this.setupPaginationHandlers();
    }

    setupModalHandlers = () => {
        const confirmLogoutBtn = document.getElementById('confirm-logout');
        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', () => {
                this.logout();
            });
        }

        const confirmDeleteProfileBtn = document.getElementById('confirm-delete-profile');
        if (confirmDeleteProfileBtn) {
            confirmDeleteProfileBtn.addEventListener('click', () => {
                this.deleteProfile();
            });
        }
    }

    setupPaginationHandlers = () => {
        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        
        if (prevPageBtn) {
            prevPageBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.currentNotificationsPage > 1) {
                    this.currentNotificationsPage--;
                    this.loadNotifications();
                }
            });
        }
        
        if (nextPageBtn) {
            nextPageBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.currentNotificationsPage < this.totalNotificationsPages) {
                    this.currentNotificationsPage++;
                    this.loadNotifications();
                }
            });
        }
    }

    showLogoutConfirm = () => {
        const logoutModal = new bootstrap.Modal(document.getElementById('logoutConfirmModal'));
        logoutModal.show();
    }

    showDeleteProfileConfirm = () => {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteProfileConfirmModal'));
        deleteModal.show();
    }

    showModalNotification = (message, title = 'Уведомление') => {
        const notificationModal = document.getElementById('notificationModal');
        const notificationModalLabel = document.getElementById('notificationModalLabel');
        const notificationModalBody = document.getElementById('notificationModalBody');
        
        notificationModalLabel.textContent = title;
        notificationModalBody.textContent = message;
        
        const modal = new bootstrap.Modal(notificationModal);
        modal.show();
    }

    setupNavigation = () => {
        const navItems = document.querySelectorAll('.lk-sidebar__nav-item');
        const contentSections = document.querySelectorAll('.lk-content__section');

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();

                if (item.classList.contains('lk-sidebar__nav-item--logout')) {
                    return;
                }

                navItems.forEach(i => i.classList.remove('active'));
                contentSections.forEach(s => s.classList.remove('active'));

                item.classList.add('active');
                
                let targetId;
                if (item.textContent.includes('Персональные')) targetId = 'personal-data';
                else if (item.textContent.includes('История')) targetId = 'order-history';
                else if (item.textContent.includes('Уведомления')) targetId = 'notifications';
                else if (item.textContent.includes('Сохраненная')) targetId = 'saved-basket';
                else if (item.textContent.includes('Панель администратора')) targetId = 'admin-panel';

                if (targetId) {
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.classList.add('active');
                        
                        if (targetId === 'order-history') {
                            this.loadOrderHistory();
                        }
                        
                        if (targetId === 'saved-basket') {
                            this.updateSavedCartInfo();
                        }
                        
                        if (targetId === 'notifications') {
                            this.loadNotifications();
                        }
                        
                        if (targetId === 'admin-panel' && this.isAdmin) {
                            this.updateAdminStatistics();
                            this.loadUsersTable();
                        }
                    }
                }
            });
        });
    }

    savePersonalData = () => {
        const firstName = document.getElementById('first-name').value;
        const lastName = document.getElementById('last-name').value;
        const phone = document.getElementById('phone').value;
        const address = document.getElementById('address').value;
        const newPassword = document.getElementById('new-password').value;

        this.currentUser.first_name = firstName;
        this.currentUser.last_name = lastName;
        this.currentUser.phone = phone;
        this.currentUser.address = address;

        if (newPassword) {
            this.currentUser.password = newPassword;
        }

        localStorage.setItem('currentUser', JSON.stringify(this.currentUser));

        const userIndex = this.users.findIndex(u => u.user_id === this.currentUser.user_id);
        if (userIndex !== -1) {
            this.users[userIndex] = this.currentUser;
            localStorage.setItem('registeredUsers', JSON.stringify(this.users));
        }

        this.loadUserData();

        this.showModalNotification('Данные успешно сохранены!', 'Успех');
    }

    refreshProfileData = () => {
        const updatedUser = JSON.parse(localStorage.getItem('currentUser'));
        if (updatedUser) {
            this.currentUser = updatedUser;
            this.loadUserData();
            this.showModalNotification('Данные профиля обновлены', 'Обновление');
        } else {
            this.showModalNotification('Не удалось обновить данные профиля', 'Ошибка');
        }
    }

    logout = () => {
        localStorage.removeItem('currentUser');
        
        const logoutModal = bootstrap.Modal.getInstance(document.getElementById('logoutConfirmModal'));
        if (logoutModal) {
            logoutModal.hide();
        }
        
        this.showModalNotification('Вы успешно вышли из системы', 'Выход выполнен');
        
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1500);
    }

    deleteProfile = () => {
        const updatedUsers = this.users.filter(u => u.user_id !== this.currentUser.user_id);
        localStorage.setItem('registeredUsers', JSON.stringify(updatedUsers));
        
        localStorage.removeItem('currentUser');
        
        localStorage.removeItem(`cart_${this.currentUser.user_id}`);
        
        localStorage.removeItem(`notifications_${this.currentUser.user_id}`);
        
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteProfileConfirmModal'));
        if (deleteModal) {
            deleteModal.hide();
        }
        
        this.showModalNotification('Ваш профиль был успешно удален', 'Профиль удален');
        
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1500);
    }

    setupOrderDetailsHandlers = () => {
        const viewButtons = document.querySelectorAll('.view-order-details');
        
        viewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const orderId = parseInt(e.target.dataset.orderId);
                this.showOrderDetails(orderId);
            });
        });
    }

    showOrderDetails = (orderId) => {
        const order = this.orders.find(o => o.order_id === orderId);
        
        if (!order) {
            this.showModalNotification('Заказ не найден', 'Ошибка');
            return;
        }

        const orderDate = new Date(order.order_date).toLocaleDateString('ru-RU');
        const totalAmount = order.items.reduce((sum, item) => sum + item.total_price, 0);
        
        let itemsHTML = '';
        order.items.forEach(item => {
            const productData = item.product_data;
            itemsHTML += `
                <div class="order-item-detail d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div class="d-flex align-items-center">
                        <img src="${productData.image_url}" alt="${productData.product_name}" 
                             class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <div class="fw-bold">${productData.product_name}</div>
                            <div class="text-muted">${productData.category_name}</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div>${item.quantity} × ${productData.unit_price.toLocaleString('ru-RU')} ₽</div>
                        <div class="fw-bold">${item.total_price.toLocaleString('ru-RU')} ₽</div>
                    </div>
                </div>
            `;
        });

        const showPaymentButton = order.payment_status === 'ожидает оплаты';

        const modalHTML = `
            <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderDetailsModalLabel">Детали заказа #${order.order_id}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Дата заказа:</strong> ${orderDate}
                                </div>
                                <div class="col-md-6">
                                    <strong>Статус:</strong> ${this.getStatusBadge(order.payment_status)}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Адрес доставки:</strong> ${order.delivery_address || 'Не указан'}
                                </div>
                            </div>
                            ${showPaymentButton ? `
                            <div class="row mb-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-payment" data-order-id="${order.order_id}">
                                        Оплатить заказ
                                    </button>
                                </div>
                            </div>
                            ` : ''}
                            <h6 class="mt-4 mb-3">Состав заказа:</h6>
                            ${itemsHTML}
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <strong>Итого:</strong>
                                <strong class="fs-5">${totalAmount.toLocaleString('ru-RU')} ₽</strong>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const existingModal = document.getElementById('orderDetailsModal');
        if (existingModal) {
            existingModal.remove();
        }

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        if (showPaymentButton) {
            const paymentBtn = document.querySelector('.btn-payment');
            if (paymentBtn) {
                paymentBtn.addEventListener('click', () => {
                    this.processPayment(order.order_id);
                });
            }
        }
        
        const orderModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        orderModal.show();
    }

    processPayment = (orderId) => {
        const orderIndex = this.orders.findIndex(o => o.order_id === orderId);
        if (orderIndex === -1) {
            this.showModalNotification('Заказ не найден', 'Ошибка');
            return;
        }

        this.orders[orderIndex].payment_status = 'оплачено';
        
        localStorage.setItem('orders', JSON.stringify(this.orders));
        
        const orderModal = bootstrap.Modal.getInstance(document.getElementById('orderDetailsModal'));
        if (orderModal) {
            orderModal.hide();
        }
        
        this.showModalNotification('Заказ успешно оплачен!', 'Оплата выполнена');
        
        this.loadOrderHistory();
        
        this.generatePaymentNotification(orderId);
    }

    generatePaymentNotification = (orderId) => {
        const paymentNotification = {
            id: `payment_${orderId}_${Date.now()}`,
            user_id: this.currentUser.user_id,
            type: 'payment_success',
            title: 'Оплата заказа',
            message: `Заказ #${orderId} успешно оплачен. Статус заказа изменен на "Оплачено".`,
            order_id: orderId,
            is_read: false,
            created_at: new Date().toISOString()
        };

        this.notifications.push(paymentNotification);
        this.saveNotifications();
        
        this.loadNotifications();
    }

    updateSavedCartInfo = () => {
        const savedBasketSection = document.getElementById('saved-basket');
        if (!savedBasketSection) return;

        const cartKey = `cart_${this.currentUser.user_id}`;
        const cart = JSON.parse(localStorage.getItem(cartKey)) || { items: [] };
        
        const totalItems = cart.items.reduce((sum, item) => sum + item.quantity, 0);
        const totalPrice = cart.items.reduce((sum, item) => sum + item.total_price, 0);
        
        const alertElement = savedBasketSection.querySelector('.alert');
        if (alertElement) {
            if (totalItems > 0) {
                alertElement.innerHTML = `
                    Ваша корзина сохраняется автоматически между сессиями. 
                    Сейчас в ней <strong>${totalItems}</strong> товара(ов) на сумму 
                    <strong>${totalPrice.toLocaleString('ru-RU')} ₽</strong>.
                `;
                alertElement.classList.remove('alert-warning');
                alertElement.classList.add('alert-info');
            } else {
                alertElement.innerHTML = `
                    <strong>Ваша корзина пуста</strong><br>
                    Добавьте товары в корзину, и они будут автоматически сохраняться между сессиями.
                `;
                alertElement.classList.remove('alert-info');
                alertElement.classList.add('alert-warning');
            }
        }
    }

    setupNotificationsHandlers = () => {
        const filterButtons = document.querySelectorAll('[data-filter]');
        filterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const filter = e.target.dataset.filter;
                
                filterButtons.forEach(btn => btn.classList.remove('active'));
                e.target.classList.add('active');
                
                this.filterNotifications(filter);
            });
        });

        const markAllReadBtn = document.getElementById('mark-all-read');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', () => {
                this.markAllNotificationsAsRead();
            });
        }
    }

    loadNotifications = () => {
        this.generateOrderNotifications();
        
        const notificationsList = document.getElementById('notifications-list');
        const noNotifications = document.getElementById('no-notifications');
        const pagination = document.getElementById('notifications-pagination');
        
        if (!notificationsList) return;

        const userNotifications = this.notifications
            .filter(notification => notification.user_id === this.currentUser.user_id)
            .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        if (userNotifications.length === 0) {
            notificationsList.style.display = 'none';
            noNotifications.style.display = 'block';
            pagination.style.display = 'none';
            this.updateUnreadCount();
            return;
        }

        notificationsList.style.display = 'block';
        noNotifications.style.display = 'none';

        this.totalNotificationsPages = Math.ceil(userNotifications.length / this.notificationsPerPage);
        const startIndex = (this.currentNotificationsPage - 1) * this.notificationsPerPage;
        const endIndex = startIndex + this.notificationsPerPage;
        const paginatedNotifications = userNotifications.slice(startIndex, endIndex);

        let notificationsHTML = '';
        
        paginatedNotifications.forEach(notification => {
            const notificationDate = new Date(notification.created_at).toLocaleString('ru-RU');
            const isUnreadClass = notification.is_read ? '' : 'list-group-item-warning';
            const readBadge = notification.is_read ? 
                '<span class="badge bg-secondary float-end">Прочитано</span>' : 
                '<span class="badge bg-primary float-end">Новое</span>';
            
            notificationsHTML += `
                <div class="list-group-item list-group-item-action notification-item ${isUnreadClass}" 
                     data-notification-id="${notification.id}" data-read="${notification.is_read}">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${notification.title}</h6>
                        <small>${notificationDate}</small>
                    </div>
                    <p class="mb-1">${notification.message}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">${this.getNotificationTypeText(notification.type)}</small>
                        ${readBadge}
                    </div>
                </div>
            `;
        });

        notificationsList.innerHTML = notificationsHTML;
        
        this.updatePagination();
        this.setupNotificationClickHandlers();
        this.updateUnreadCount();
        this.applyCurrentFilter();
    }

    updatePagination = () => {
        const pagination = document.getElementById('notifications-pagination');
        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        const pageInfo = document.getElementById('page-info');
        
        if (this.totalNotificationsPages > 1) {
            pagination.style.display = 'block';
            
            pageInfo.textContent = `Страница ${this.currentNotificationsPage} из ${this.totalNotificationsPages}`;
            
            if (this.currentNotificationsPage === 1) {
                prevPageBtn.classList.add('disabled');
            } else {
                prevPageBtn.classList.remove('disabled');
            }
            
            if (this.currentNotificationsPage === this.totalNotificationsPages) {
                nextPageBtn.classList.add('disabled');
            } else {
                nextPageBtn.classList.remove('disabled');
            }
        } else {
            pagination.style.display = 'none';
        }
    }

    generateOrderNotifications = () => {
        const userOrders = this.orders.filter(order => order.user_id === this.currentUser.user_id);
        let hasNewNotifications = false;

        userOrders.forEach(order => {
            const orderCreatedNotification = {
                id: `order_created_${order.order_id}`,
                user_id: this.currentUser.user_id,
                type: 'order_created',
                title: 'Заказ оформлен',
                message: `Ваш заказ №${order.order_id} успешно оформлен и передан в обработку.`,
                order_id: order.order_id,
                is_read: false,
                created_at: order.order_date
            };

            let statusNotification = null;
            if (order.payment_status === 'оплачено') {
                statusNotification = {
                    id: `status_${order.order_id}`,
                    user_id: this.currentUser.user_id,
                    type: 'status_changed',
                    title: 'Статус заказа изменен',
                    message: `Статус вашего заказа №${order.order_id} изменен на "Оплачено".`,
                    order_id: order.order_id,
                    is_read: false,
                    created_at: new Date().toISOString()
                };
            } else if (order.payment_status === 'отменен') {
                statusNotification = {
                    id: `status_${order.order_id}`,
                    user_id: this.currentUser.user_id,
                    type: 'status_changed',
                    title: 'Заказ отменен',
                    message: `Ваш заказ №${order.order_id} был отменен.`,
                    order_id: order.order_id,
                    is_read: false,
                    created_at: new Date().toISOString()
                };
            }

            if (!this.notifications.find(n => n.id === orderCreatedNotification.id)) {
                this.notifications.push(orderCreatedNotification);
                hasNewNotifications = true;
            }

            if (statusNotification && !this.notifications.find(n => n.id === statusNotification.id)) {
                this.notifications.push(statusNotification);
                hasNewNotifications = true;
            }
        });

        const cartKey = `cart_${this.currentUser.user_id}`;
        const cart = JSON.parse(localStorage.getItem(cartKey)) || { items: [] };
        
        if (cart.items.length > 0) {
            const oneDayAgo = new Date(Date.now() - 24 * 60 * 60 * 1000);
            const recentCartNotification = this.notifications.find(n => 
                n.type === 'cart_saved' && 
                new Date(n.created_at) > oneDayAgo
            );
            
            if (!recentCartNotification) {
                const cartNotification = {
                    id: `cart_saved_${Date.now()}`,
                    user_id: this.currentUser.user_id,
                    type: 'cart_saved',
                    title: 'Корзина сохранена',
                    message: 'Ваша корзина была автоматически сохранена. Вы можете продолжить покупки в любое время.',
                    is_read: false,
                    created_at: new Date().toISOString()
                };

                if (!this.notifications.find(n => n.id === cartNotification.id)) {
                    this.notifications.push(cartNotification);
                    hasNewNotifications = true;
                }
            }
        }

        if (hasNewNotifications) {
            this.saveNotifications();
        }
    }

    saveNotifications = () => {
        localStorage.setItem(`notifications_${this.currentUser.user_id}`, JSON.stringify(this.notifications));
    }

    setupNotificationClickHandlers = () => {
        const notificationItems = document.querySelectorAll('.notification-item');
        
        notificationItems.forEach(item => {
            item.addEventListener('click', (e) => {
                const notificationId = e.currentTarget.dataset.notificationId;
                const isRead = e.currentTarget.dataset.read === 'true';
                
                if (!isRead) {
                    this.markNotificationAsRead(notificationId);
                }
                
                this.handleNotificationClick(notificationId);
            });
        });
    }

    markNotificationAsRead = (notificationId) => {
        const notification = this.notifications.find(n => n.id === notificationId);
        if (notification && !notification.is_read) {
            notification.is_read = true;
            this.saveNotifications();
            
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('list-group-item-warning');
                notificationElement.dataset.read = 'true';
                
                const badge = notificationElement.querySelector('.badge');
                if (badge) {
                    badge.className = 'badge bg-secondary float-end';
                    badge.textContent = 'Прочитано';
                }
            }
            
            this.updateUnreadCount();
        }
    }

    markAllNotificationsAsRead = () => {
        this.notifications.forEach(notification => {
            if (notification.user_id === this.currentUser.user_id && !notification.is_read) {
                notification.is_read = true;
            }
        });
        
        this.saveNotifications();
        this.loadNotifications();
        this.showModalNotification('Все уведомления отмечены как прочитанные', 'Успех');
    }

    handleNotificationClick = (notificationId) => {
        const notification = this.notifications.find(n => n.id === notificationId);
        
        if (notification && notification.order_id) {
            this.showOrderDetails(notification.order_id);
        }
    }

    filterNotifications = (filter) => {
        const notificationItems = document.querySelectorAll('.notification-item');
        
        notificationItems.forEach(item => {
            switch (filter) {
                case 'all':
                    item.style.display = 'block';
                    break;
                case 'unread':
                    item.style.display = item.dataset.read === 'false' ? 'block' : 'none';
                    break;
                case 'read':
                    item.style.display = item.dataset.read === 'true' ? 'block' : 'none';
                    break;
            }
        });
    }

    applyCurrentFilter = () => {
        const activeFilter = document.querySelector('[data-filter].active');
        if (activeFilter) {
            this.filterNotifications(activeFilter.dataset.filter);
        }
    }

    updateUnreadCount = () => {
        const unreadCount = this.notifications.filter(
            n => n.user_id === this.currentUser.user_id && !n.is_read
        ).length;
        
        const countElement = document.getElementById('unread-notifications-count');
        if (countElement) {
            countElement.textContent = unreadCount;
            countElement.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        }
        
        const sidebarBadge = document.querySelector('.lk-sidebar__nav-item .badge');
        if (sidebarBadge) {
            sidebarBadge.textContent = unreadCount;
            sidebarBadge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        }
    }

    getNotificationTypeText = (type) => {
        const typeMap = {
            'order_created': 'Создание заказа',
            'status_changed': 'Изменение статуса',
            'cart_saved': 'Сохранение корзины',
            'system': 'Системное уведомление',
            'payment_success': 'Оплата заказа'
        };
        
        return typeMap[type] || 'Уведомление';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new AccountService();
});