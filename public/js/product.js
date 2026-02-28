let currentProduct = null;

async function loadProductData() {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = parseInt(urlParams.get('id'));
        
        if (!productId) {
            console.error('ID товара не указан');
            return;
        }

        const response = await fetch('./products.json');
        const products = await response.json();
        
        currentProduct = products.find(p => p.product_id === productId);
        
        if (!currentProduct) {
            console.error('Товар не найден');
            return;
        }

        document.getElementById('product-title').textContent = currentProduct.product_name;
        document.getElementById('product-image').src = currentProduct.image_url;
        document.getElementById('product-image').alt = currentProduct.product_name;
        document.getElementById('product-price').textContent = `${currentProduct.unit_price.toLocaleString('ru-RU')} ₽`;
        document.getElementById('product-stock').textContent = `${currentProduct.stock_quantity} шт.`;
        document.getElementById('product-description').textContent = currentProduct.description;

        setupAddToCartButtons();

    } catch (error) {
        console.error('Ошибка загрузки данных товара:', error);
    }
}

function setupAddToCartButtons() {
    const buyButton = document.querySelector('.btn__btn-buy');
    const cartIcon = document.querySelector('.main__card-icon');
    
    if (buyButton) {
        buyButton.addEventListener('click', () => openCheckoutModalForSingleProduct());
    }
    
    if (cartIcon) {
        cartIcon.addEventListener('click', () => openAddToCartModal());
    }
}

function openCheckoutModalForSingleProduct() {
    if (!currentProduct) return;

    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) {
        showNotification('Для оформления заказа требуется авторизация', 'error');
        setTimeout(() => { window.location.href = 'auth.html'; }, 1200);
        return;
    }

    document.getElementById('checkout-product-image').src = currentProduct.image_url;
    document.getElementById('checkout-product-name').textContent = currentProduct.product_name;
    document.getElementById('checkout-product-price').textContent = `${currentProduct.unit_price.toLocaleString('ru-RU')} ₽`;

    const qtyInput = document.getElementById('checkout-quantity');
    qtyInput.value = 1;
    qtyInput.min = 1;
    qtyInput.max = Math.max(1, currentProduct.stock_quantity);

    const recalcTotal = () => {
        const q = parseInt(qtyInput.value) || 1;
        const total = q * currentProduct.unit_price;
        document.getElementById('checkout-total-price').textContent = `${total.toLocaleString('ru-RU')} ₽`;
    };

    qtyInput.addEventListener('input', () => {
        if (qtyInput.value === '' || parseInt(qtyInput.value) < 1) qtyInput.value = 1;
        if (parseInt(qtyInput.value) > currentProduct.stock_quantity) qtyInput.value = currentProduct.stock_quantity;
        recalcTotal();
    });

    recalcTotal();

    const addressField = document.getElementById('delivery-address');
    const savedSection = document.getElementById('saved-address-section');
    const savedInfo = document.getElementById('saved-address-info');
    const useSavedCheckbox = document.getElementById('use-saved-address');

    if (currentUser && currentUser.address) {
        savedSection.style.display = 'block';
        savedInfo.textContent = currentUser.address;
        addressField.value = currentUser.address;
        useSavedCheckbox.checked = true;
    } else {
        savedSection.style.display = 'none';
        addressField.value = '';
        useSavedCheckbox.checked = false;
    }

    useSavedCheckbox.onchange = (e) => {
        if (e.target.checked && currentUser && currentUser.address) {
            addressField.value = currentUser.address;
        } else {
            addressField.value = '';
        }
    };

    document.getElementById('confirm-checkout').onclick = () => {
        processSingleProductCheckout();
    };

    const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    checkoutModal.show();
}

function processSingleProductCheckout() {
    const qty = parseInt(document.getElementById('checkout-quantity').value) || 1;
    const address = document.getElementById('delivery-address').value.trim();

    if (!address) {
        showNotification('Пожалуйста, укажите адрес доставки', 'error');
        document.getElementById('delivery-address').focus();
        return;
    }

    if (qty < 1 || qty > currentProduct.stock_quantity) {
        showNotification('Неверное количество', 'error');
        return;
    }

    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) {
        showNotification('Требуется авторизация', 'error');
        setTimeout(() => { window.location.href = 'auth.html'; }, 1000);
        return;
    }

    const orderId = Date.now();
    const orderItem = {
        cart_item_id: Date.now(),
        product_id: currentProduct.product_id,
        quantity: qty,
        total_price: qty * currentProduct.unit_price,
        product_data: currentProduct
    };

    const order = {
        order_id: orderId,
        user_id: currentUser.user_id,
        order_date: new Date().toISOString(),
        delivery_address: address,
        payment_status: 'ожидает оплаты',
        items: [orderItem]
    };

    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    orders.push(order);
    localStorage.setItem('orders', JSON.stringify(orders));

    const checkoutModalInstance = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
    if (checkoutModalInstance) checkoutModalInstance.hide();

    document.getElementById('order-number').textContent = `#${orderId}`;
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();

    if (currentProduct.stock_quantity >= qty) {
        currentProduct.stock_quantity -= qty;
        document.getElementById('product-stock').textContent = `${currentProduct.stock_quantity} шт.`;
    }
}

function openAddToCartModal() {
    if (!currentProduct) return;
    
    document.getElementById('modal-product-image').src = currentProduct.image_url;
    document.getElementById('modal-product-name').textContent = currentProduct.product_name;
    document.getElementById('modal-product-price').textContent = `${currentProduct.unit_price.toLocaleString('ru-RU')} ₽`;
    document.getElementById('modal-stock-info').textContent = `В наличии: ${currentProduct.stock_quantity} шт.`;
    
    const quantityInput = document.getElementById('quantity-input');
    const maxQuantity = Math.max(1, currentProduct.stock_quantity);
    
    quantityInput.value = 1;
    quantityInput.max = maxQuantity;
    quantityInput.min = 1;

    const validateQuantity = () => {
        let num = parseInt(quantityInput.value, 10);
        
        if (isNaN(num) || quantityInput.value === '') {
            num = 1;
        }
        
        if (num < 1) num = 1;
        if (num > maxQuantity) num = maxQuantity;
        
        quantityInput.value = num;
    };

    quantityInput.oninput = () => {
        if (quantityInput.value !== '') {
            quantityInput.value = quantityInput.value.replace(/[^\d]/g, '');
        }
    };

    quantityInput.onblur = validateQuantity;

    const decBtn = document.getElementById('decrease-quantity');
    const incBtn = document.getElementById('increase-quantity');

    decBtn.onclick = null;
    incBtn.onclick = null;
    
    decBtn.onclick = () => {
        let val = parseInt(quantityInput.value, 10) || 1;
        if (val > 1) {
            quantityInput.value = val - 1;
        }
    };
    
    incBtn.onclick = () => {
        let val = parseInt(quantityInput.value, 10) || 1;
        if (val < maxQuantity) {
            quantityInput.value = val + 1;
        } else {
            quantityInput.value = maxQuantity;
        }
    };
    
    document.getElementById('confirm-add-to-cart').onclick = null;
    document.getElementById('confirm-add-to-cart').onclick = () => {
        validateQuantity(); 
        const quantity = parseInt(quantityInput.value, 10) || 1;
        addToCart(quantity);
    };
    
    const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
    modal.show();
}

function addToCart(quantity) {
    if (!currentProduct) return;
    
    cartService.addToCart(currentProduct.product_id, quantity, currentProduct);
    
    showNotification(`Товар "${currentProduct.product_name}" добавлен в корзину`, 'success');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('addToCartModal'));
    if (modal) {
        modal.hide();
    }
    

}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
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

document.addEventListener('DOMContentLoaded', loadProductData);