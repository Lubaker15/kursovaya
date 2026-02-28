class CartService {
    constructor() {
        this.currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
        this.updateCartKey();
    }

    updateCartKey() {
        this.currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
        this.cartKey = this.currentUser ? `cart_${this.currentUser.user_id}` : 'guest_cart';
    }

    getCart() {
        return JSON.parse(localStorage.getItem(this.cartKey)) || {
            cart_id: this.cartKey,
            user_id: this.currentUser ? this.currentUser.user_id : null,
            is_guest: !this.currentUser,
            status: 'active',
            created_at: new Date().toISOString(),
            items: []
        };
    }

    saveCart(cart) {
        localStorage.setItem(this.cartKey, JSON.stringify(cart));
    }

    addToCart(productId, quantity, productData) {
        const cart = this.getCart();
        const existingItem = cart.items.find(item => item.product_id === productId);

        if (existingItem) {
            existingItem.quantity += quantity;
            existingItem.total_price = existingItem.quantity * productData.unit_price;
        } else {
            cart.items.push({
                cart_item_id: Date.now(),
                product_id: productId,
                quantity: quantity,
                total_price: quantity * productData.unit_price,
                product_data: productData 
            });
        }

        this.saveCart(cart);
        this.updateCartCounter();
        return cart;
    }

    updateQuantity(productId, newQuantity, productPrice) {
        const cart = this.getCart();
        const item = cart.items.find(item => item.product_id === productId);
        
        if (item) {
            item.quantity = newQuantity;
            item.total_price = newQuantity * productPrice;
            this.saveCart(cart);
            this.updateCartCounter();
        }
        
        return cart;
    }

    removeFromCart(productId) {
        const cart = this.getCart();
        cart.items = cart.items.filter(item => item.product_id !== productId);
        this.saveCart(cart);
        this.updateCartCounter();
        return cart;
    }

    clearCart() {
        const cart = this.getCart();
        cart.items = [];
        this.saveCart(cart);
        this.updateCartCounter();
    }

    getTotalItems() {
        const cart = this.getCart();
        return cart.items.reduce((total, item) => total + item.quantity, 0);
    }

    getTotalPrice() {
        const cart = this.getCart();
        return cart.items.reduce((total, item) => total + item.total_price, 0);
    }


    updateCartCounter() {
        const totalItems = this.getTotalItems();
        const counterElements = document.querySelectorAll('.cart-counter');
        
        counterElements.forEach(element => {
            element.textContent = totalItems;
            element.style.display = totalItems > 0 ? 'inline' : 'none';
        });
    }

    mergeGuestCart(userId) {
        const guestCart = JSON.parse(localStorage.getItem('guest_cart'));
        if (guestCart && guestCart.items.length > 0) {
            this.cartKey = `cart_${userId}`;
            const userCart = this.getCart();

            guestCart.items.forEach(guestItem => {
                const existingItem = userCart.items.find(item => item.product_id === guestItem.product_id);
                if (existingItem) {
                    existingItem.quantity += guestItem.quantity;
                    existingItem.total_price = existingItem.quantity * guestItem.product_data.unit_price;
                } else {
                    userCart.items.push(guestItem);
                }
            });

            this.saveCart(userCart);
            localStorage.removeItem('guest_cart');
            this.updateCartCounter();
        }
    }
}


const cartService = new CartService();