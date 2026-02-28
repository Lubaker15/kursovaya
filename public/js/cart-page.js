let allProducts = [];


async function loadCartPage() {
    try {
        const response = await fetch('./products.json');
        allProducts = await response.json();
        
        renderCart();
        setupEventListeners();
        
    } catch (error) {
        console.error('Ошибка загрузки корзины:', error);
    }
}

function renderCart() {
    const cart = cartService.getCart();
    const cartContainer = document.querySelector('.cart-container');
    
    if (cart.items.length === 0) {
        showEmptyCart();
        return;
    }
    
    let cartHTML = `
        <div class="cart-header">
            <div>Товар</div>
            <div>Цена</div>
            <div>Количество</div>
            <div>Итого</div>
            <div></div>
        </div>
    `;
    
    let totalPrice = 0;
    
    cart.items.forEach(item => {
        const product = allProducts.find(p => p.product_id === item.product_id) || item.product_data;
        if (!product) return;
        
        const itemTotal = item.quantity * product.unit_price;
        totalPrice += itemTotal;
        
        cartHTML += `
            <div class="cart-item" data-product-id="${product.product_id}">
                <div class="product-info">
                    <img src="${product.image_url}" alt="${product.product_name}" class="product-image">
                    <div>
                        <div class="product-name">${product.product_name}</div>
                        <div class="product-category">${product.category_name}</div>
                    </div>
                </div>
                <div class="product-price">${product.unit_price.toLocaleString('ru-RU')} ₽</div>
                <div class="quantity-controls">
                    <button class="quantity-btn minus-btn">-</button>
                    <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="${product.stock_quantity}">
                    <button class="quantity-btn plus-btn">+</button>
                </div>
                <div class="item-total">${itemTotal.toLocaleString('ru-RU')} ₽</div>
                <button class="remove-btn">×</button>
            </div>
        `;
    });
    
    cartHTML += `
        <div class="cart-summary">
            <div class="total-price">Итого: ${totalPrice.toLocaleString('ru-RU')} ₽</div>
            <button class="checkout-btn">Оформить заказ</button>
        </div>
    `;
    
    cartContainer.innerHTML = cartHTML;
}

function showEmptyCart() {
    const cartContainer = document.querySelector('.cart-container');
    cartContainer.innerHTML = `
        <div class="empty-cart">
            <h2 class="empty-cart-title">Корзина пуста</h2>
            <p class="empty-cart-text">В вашей корзине пока нет товаров. Перейдите в каталог, чтобы добавить товары в корзину.</p>
            <a href="/catalog.html" class="btn-catalog">Перейти в каталог</a>
        </div>
    `;
}

function setupEventListeners() {
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('minus-btn')) {
            const input = e.target.nextElementSibling;
            const newQuantity = parseInt(input.value) - 1;
            if (newQuantity >= 1) {
                input.value = newQuantity;
                updateCartItem(e.target.closest('.cart-item'), newQuantity);
            }
        }
        
        if (e.target.classList.contains('plus-btn')) {
            const input = e.target.previousElementSibling;
            const productId = parseInt(e.target.closest('.cart-item').dataset.productId);
            const product = allProducts.find(p => p.product_id === productId);
            const newQuantity = parseInt(input.value) + 1;
            
            if (newQuantity <= product.stock_quantity) {
                input.value = newQuantity;
                updateCartItem(e.target.closest('.cart-item'), newQuantity);
            } else {
                showNotification('Нельзя добавить больше товара, чем есть в наличии', 'error');
            }
        }
        
        if (e.target.classList.contains('remove-btn')) {
            const cartItem = e.target.closest('.cart-item');
            const productId = parseInt(cartItem.dataset.productId);
            cartService.removeFromCart(productId);
            renderCart();
            showNotification('Товар удален из корзины', 'success');
        }
        
        if (e.target.classList.contains('checkout-btn')) {
            openCheckoutModal();
        }
    });
    
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('quantity-input')) {
            const newQuantity = parseInt(e.target.value);
            const productId = parseInt(e.target.closest('.cart-item').dataset.productId);
            const product = allProducts.find(p => p.product_id === productId);
            
            if (newQuantity < 1) {
                e.target.value = 1;
                updateCartItem(e.target.closest('.cart-item'), 1);
            } else if (newQuantity > product.stock_quantity) {
                showNotification('Нельзя добавить больше товара, чем есть в наличии', 'error');
                e.target.value = product.stock_quantity;
                updateCartItem(e.target.closest('.cart-item'), product.stock_quantity);
            } else {
                updateCartItem(e.target.closest('.cart-item'), newQuantity);
            }
        }
    });
    
    setupCheckoutModalHandlers();
}

function setupCheckoutModalHandlers() {
    const confirmCheckoutBtn = document.getElementById('confirm-checkout');
    if (confirmCheckoutBtn) {
        confirmCheckoutBtn.addEventListener('click', () => {
            processCheckout();
        });
    }
    
    const useSavedAddressCheckbox = document.getElementById('use-saved-address');
    if (useSavedAddressCheckbox) {
        useSavedAddressCheckbox.addEventListener('change', (e) => {
            const addressField = document.getElementById('delivery-address');
            const currentUser = JSON.parse(localStorage.getItem('currentUser'));
            
            if (e.target.checked && currentUser && currentUser.address) {
                addressField.value = currentUser.address;
            }
        });
    }
    
    const goToMainBtn = document.getElementById('go-to-main');
    const viewOrdersBtn = document.getElementById('view-orders');
    
    if (goToMainBtn) {
        goToMainBtn.addEventListener('click', () => {
            window.location.href = 'index.html';
        });
    }
    
    if (viewOrdersBtn) {
        viewOrdersBtn.addEventListener('click', () => {
            window.location.href = 'account.html';
        });
    }
}

function openCheckoutModal() {
    const cart = cartService.getCart();
    
    if (cart.items.length === 0) {
        showNotification('Корзина пуста', 'error');
        return;
    }
    
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) {
        showNotification('Для оформления заказа необходимо авторизоваться', 'error');
        setTimeout(() => {
            window.location.href = 'auth.html';
        }, 2000);
        return;
    }
    
    const checkoutItemsList = document.getElementById('checkout-items-list');
    const checkoutTotalPrice = document.getElementById('checkout-total-price');
    
    let itemsHTML = '';
    let totalPrice = 0;
    
    cart.items.forEach(item => {
        const product = allProducts.find(p => p.product_id === item.product_id) || item.product_data;
        if (!product) return;
        
        const itemTotal = item.quantity * product.unit_price;
        totalPrice += itemTotal;
        
        itemsHTML += `
            <div class="checkout-item d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center">
                    <img src="${product.image_url}" alt="${product.product_name}" 
                         class="me-2" style="width: 30px; height: 30px; object-fit: cover;">
                    <span class="small">${product.product_name}</span>
                </div>
                <div class="text-end">
                    <div class="small">${item.quantity} × ${product.unit_price.toLocaleString('ru-RU')} ₽</div>
                    <div class="small fw-bold">${itemTotal.toLocaleString('ru-RU')} ₽</div>
                </div>
            </div>
        `;
    });
    
    checkoutItemsList.innerHTML = itemsHTML;
    checkoutTotalPrice.textContent = `${totalPrice.toLocaleString('ru-RU')} ₽`;
    
    const addressField = document.getElementById('delivery-address');
    const savedAddressSection = document.getElementById('saved-address-section');
    const savedAddressInfo = document.getElementById('saved-address-info');
    const useSavedAddressCheckbox = document.getElementById('use-saved-address');
    
    document.getElementById('checkout-form').reset();
    useSavedAddressCheckbox.checked = false;
    
    if (currentUser && currentUser.address) {
        savedAddressSection.style.display = 'block';
        savedAddressInfo.innerHTML = `
            <small><strong>Сохраненный адрес:</strong> ${currentUser.address}</small>
        `;
        addressField.value = currentUser.address;
    } else {
        savedAddressSection.style.display = 'none';
        addressField.value = '';
    }
    
    const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    checkoutModal.show();
}

function processCheckout() {
    const modal = document.getElementById('checkoutModal');
    const addressField = document.getElementById('delivery-address');
    
    const notesField = document.getElementById('customer-notes');
    
    const address = addressField.value.trim();
    
    if (!address) {
        showNotification('Пожалуйста, укажите адрес доставки', 'error');
        addressField.focus();
        return;
    }
    
    const cart = cartService.getCart();
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    
    const order = {
        order_id: Date.now(),
        user_id: currentUser.user_id,
        cart_id: cart.cart_id,
        order_date: new Date().toISOString(),
        delivery_address: address,
        customer_notes: notesField ? notesField.value.trim() : '', 
        payment_status: 'ожидает оплаты',
        items: [...cart.items] 
    };
    
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    orders.push(order);
    localStorage.setItem('orders', JSON.stringify(orders));
    
    cartService.clearCart();
    
    const checkoutModal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
    checkoutModal.hide();
    
    showSuccessModal(order.order_id);
}

function showSuccessModal(orderId) {
    const orderNumberElement = document.getElementById('order-number');
    orderNumberElement.textContent = `#${orderId}`;
    
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
}

function updateCartItem(cartItemElement, newQuantity) {
    const productId = parseInt(cartItemElement.dataset.productId);
    const product = allProducts.find(p => p.product_id === productId);
    
    cartService.updateQuantity(productId, newQuantity, product.unit_price);
    
    const itemTotalElement = cartItemElement.querySelector('.item-total');
    itemTotalElement.textContent = `${(newQuantity * product.unit_price).toLocaleString('ru-RU')} ₽`;
    
    updateTotalPrice();
}

function updateTotalPrice() {
    const totalPrice = cartService.getTotalPrice();
    const totalPriceElement = document.querySelector('.total-price');
    if (totalPriceElement) {
        totalPriceElement.textContent = `Итого: ${totalPrice.toLocaleString('ru-RU')} ₽`;
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

document.addEventListener('DOMContentLoaded', loadCartPage);