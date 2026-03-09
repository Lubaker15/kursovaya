document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtn = document.getElementById('addToCartBtn');
    const addToCartModal = document.getElementById('addToCartModal');
    const modalImage = document.getElementById('modal-product-image');
    const modalName = document.getElementById('modal-product-name');
    const modalPrice = document.getElementById('modal-product-price');
    const modalStock = document.getElementById('modal-stock-info');
    const modalProductId = document.getElementById('modal-product-id');
    const quantityInput = document.getElementById('quantity-input');

    const quickBuyBtn = document.getElementById('quickBuyBtn');
    const quickModal = document.getElementById('quickCheckoutModal');
    const quickImage = document.getElementById('quick-product-image');
    const quickName = document.getElementById('quick-product-name');
    const quickPrice = document.getElementById('quick-product-price');
    const quickProductId = document.getElementById('quick-product-id');
    const quickQuantityInput = document.getElementById('quick-quantity-input');
    const quickQuantityHidden = document.getElementById('quick-quantity');
    const quickTotalSpan = document.getElementById('quick-total');
    const deliveryAddress = document.getElementById('delivery-address');
    const useSavedCheckbox = document.getElementById('use-saved-address');

    window.updateQuickTotal = function() {
        const price = parseFloat(quickPrice.dataset.price || 0);
        const qty = parseInt(quickQuantityInput.value) || 1;
        const total = price * qty;
        quickTotalSpan.textContent = new Intl.NumberFormat('ru-RU').format(total) + ' ₽';
        quickQuantityHidden.value = qty;
    };

    addToCartBtn.addEventListener('click', function() {
        const id = this.dataset.productId;
        const name = this.dataset.productName;
        const price = this.dataset.productPrice;
        const image = this.dataset.productImage;
        const stock = this.dataset.productStock;

        modalProductId.value = id;
        modalImage.src = image;
        modalName.textContent = name;
        modalPrice.textContent = new Intl.NumberFormat('ru-RU').format(price) + ' ₽';
        modalStock.textContent = `В наличии: ${stock} шт.`;
        quantityInput.max = stock;
        quantityInput.value = 1;

        new bootstrap.Modal(addToCartModal).show();
    });

    quickBuyBtn.addEventListener('click', function() {
        if (!window.isAuthenticated) {
            window.location.href = '/auth?message=login_required';
            return;
        }

        const id = this.dataset.productId;
        const name = this.dataset.productName;
        const price = this.dataset.productPrice;
        const image = this.dataset.productImage;
        const stock = this.dataset.productStock;

        quickProductId.value = id;
        quickImage.src = image;
        quickName.textContent = name;
        quickPrice.textContent = new Intl.NumberFormat('ru-RU').format(price) + ' ₽';
        quickPrice.dataset.price = price;
        quickQuantityInput.max = stock;
        quickQuantityInput.value = 1;
        quickQuantityHidden.value = 1;

        if (window.userAddress) {
            deliveryAddress.value = window.userAddress;
        } else {
            deliveryAddress.value = '';
        }

        updateQuickTotal();

        new bootstrap.Modal(quickModal).show();
    });


    quickQuantityInput.addEventListener('input', function() {
        let val = parseInt(this.value) || 1;
        const max = parseInt(this.max);
        if (val < 1) val = 1;
        if (val > max) val = max;
        this.value = val;
        quickQuantityHidden.value = val;
        updateQuickTotal();
    });

    if (useSavedCheckbox) {
        useSavedCheckbox.addEventListener('change', function() {
            if (this.checked) {
                deliveryAddress.value = window.userAddress;
            } else {
                deliveryAddress.value = '';
            }
        });
    }
});