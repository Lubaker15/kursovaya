const container = document.getElementById("products-container");

const maxProductsToShow = 9;

fetch("/products.json")
    .then((response) => {
        if (!response.ok) {
            throw new Error("Ошибка загрузки JSON: " + response.status);
        }
        return response.json();
    })
    .then((products) => {
        const limitedProducts = products.slice(0, maxProductsToShow);

        limitedProducts.forEach((product) => {
            const card = document.createElement("div");
            card.classList.add("product-card");

            card.innerHTML = `
                <img src="${product.image_url}" alt="${product.product_name}" class="product-image">
                <div class="product-info">
                    <div class="product-price">${product.unit_price.toLocaleString()} <span>₽</span></div>
                    <div class="product-title">${product.product_name}</div>
                    <div class="product-actions">
                        <a href="product.html?id=${product.product_id}" class="btn btn__btn-basket-card">Перейти</a>
                        <img src="./img/cart.png" alt="В корзину" class="cart-icon">
                    </div>
                </div>
            `;
            container.appendChild(card);
        });

        if (limitedProducts.length === 0) {
            container.innerHTML = "<p>Нет доступных товаров</p>";
        }
    })
    .catch((error) => {
        console.error("Ошибка при загрузке товаров:", error);
        container.innerHTML = "<p>Не удалось загрузить товары</p>";
    });
