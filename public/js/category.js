class CategoryPage {
    constructor() {
        this.container = document.getElementById("products-container");
        this.categoryTitle = document.createElement("h1");
        this.categoryTitle.classList.add("title_catalog");
        this.container.before(this.categoryTitle);

        this.paginationContainer = document.createElement("div");
        this.paginationContainer.classList.add("pagination");
        this.container.after(this.paginationContainer);

        this.params = new URLSearchParams(window.location.search);
        this.selectedCategory = this.params.get("category");

        this.productsPerPage = 9;
        this.currentPage = 1;
        this.filteredProducts = [];
        this.allProducts = [];

        this.brandKeywords = [
            'Lenovo', 'Razer', 'Samsung', 'HyperX', 'NVIDIA', 'AMD', 
            'Kingston', 'DXRacer', 'Seagate', 'ASUS', 'Corsair', 
            'Deepcool', 'Logitech', 'Edifier', 'MSI', 'Gigabyte',
            'Intel', 'Sony', 'LG', 'Dell', 'HP', 'Acer'
        ];

        this.currentFilters = {
            priceMin: '',
            priceMax: '',
            brand: '',
            availability: ''
        };

        this.currentSort = 'default';

        this.init();
    }

    extractBrandFromName = (productName) => {
        const lowerName = productName.toLowerCase();
        
        for (const brand of this.brandKeywords) {
            if (lowerName.includes(brand.toLowerCase())) {
                return brand;
            }
        }
        return 'Другой';
    }

    enrichProductsWithBrands = (products) => {
        return products.map(product => ({
            ...product,
            brand: this.extractBrandFromName(product.product_name)
        }));
    }

    init = () => {
        this.loadProducts();
        this.setupEventListeners();
        this.setupMobileEventListeners();
    }

    loadProducts = () => {
        fetch("products.json")
            .then(response => response.json())
            .then(products => {
                this.allProducts = this.enrichProductsWithBrands(products);
                
                this.filteredProducts = this.allProducts.filter(
                    product => product.category_name === this.selectedCategory
                );

                this.categoryTitle.textContent = this.selectedCategory || "Категория";

                this.populateBrandsFilter();

                this.renderProducts();
                this.renderPagination();
                this.updateResultsCount();
            })
            .catch(error => {
                console.error("Ошибка при загрузке товаров:", error);
                this.container.innerHTML = "<p>Не удалось загрузить товары</p>";
            });
    }

    populateBrandsFilter = () => {
        const brandFilter = document.getElementById('brandFilter');
        const brands = [...new Set(this.filteredProducts.map(product => product.brand))].sort();
        
        while (brandFilter.children.length > 1) {
            brandFilter.removeChild(brandFilter.lastChild);
        }
        
        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            brandFilter.appendChild(option);
        });
    }

    applyFilters = () => {
        let filtered = [...this.allProducts].filter(product => 
            product.category_name === this.selectedCategory
        );

        if (this.currentFilters.priceMin) {
            filtered = filtered.filter(product => 
                product.unit_price >= parseFloat(this.currentFilters.priceMin)
            );
        }

        if (this.currentFilters.priceMax) {
            filtered = filtered.filter(product => 
                product.unit_price <= parseFloat(this.currentFilters.priceMax)
            );
        }

        if (this.currentFilters.brand) {
            filtered = filtered.filter(product => 
                product.brand === this.currentFilters.brand
            );
        }

        if (this.currentFilters.availability === 'in_stock') {
            filtered = filtered.filter(product => product.stock_quantity > 0);
        } else if (this.currentFilters.availability === 'out_of_stock') {
            filtered = filtered.filter(product => product.stock_quantity === 0);
        }

        this.filteredProducts = filtered;
        this.currentPage = 1;
        this.renderProducts();
        this.renderPagination();
        this.updateResultsCount();
        
        this.closeMobileFilters();
    }

    sortProducts = (products) => {
        const sorted = [...products];
        
        switch (this.currentSort) {
            case 'price_asc':
                return sorted.sort((a, b) => a.unit_price - b.unit_price);
            case 'price_desc':
                return sorted.sort((a, b) => b.unit_price - a.unit_price);
            case 'name_asc':
                return sorted.sort((a, b) => a.product_name.localeCompare(b.product_name));
            case 'name_desc':
                return sorted.sort((a, b) => b.product_name.localeCompare(a.product_name));
            default:
                return sorted;
        }
    }

    renderProducts = () => {
        this.container.innerHTML = "";

        const startIndex = (this.currentPage - 1) * this.productsPerPage;
        const endIndex = startIndex + this.productsPerPage;
        
        const sortedProducts = this.sortProducts(this.filteredProducts);
        const pageProducts = sortedProducts.slice(startIndex, endIndex);

        if (pageProducts.length === 0) {
            this.container.innerHTML = `
                <div class="text-center py-5">
                    <h4>Товары не найдены</h4>
                    <p>Попробуйте изменить параметры фильтрации</p>
                    <button class="btn btn-primary" id="resetFiltersBtn">Сбросить фильтры</button>
                </div>
            `;
            
            document.getElementById('resetFiltersBtn')?.addEventListener('click', this.resetFilters);
            return;
        }

        pageProducts.forEach(product => {
            const card = document.createElement("div");
            card.classList.add("product-card");

            card.innerHTML = `
                <img src="${product.image_url}" alt="${product.product_name}" class="product-image">
                <div class="product-info">
                    <div class="product-price">${product.unit_price.toLocaleString()} ₽</div>
                    <div class="product-title">${product.product_name}</div>
                    <div class="product-availability ${product.stock_quantity > 0 ? 'in-stock' : 'out-of-stock'}">
                        ${product.stock_quantity > 0 ? 'В наличии' : 'Нет в наличии'}
                    </div>
                    <div class="product-actions">
                        <a href="product.html?id=${product.product_id}" class="btn btn__btn-basket-card">Перейти</a>
                        <button class="btn-add-to-cart" data-product-id="${product.product_id}" ${product.stock_quantity === 0 ? 'disabled' : ''}>
                            <img src="./img/cart.png" alt="В корзину" class="cart-icon">
                        </button>
                    </div>
                </div>
            `;

            this.container.appendChild(card);
        });

        this.setupAddToCartHandlers();
    }

    setupAddToCartHandlers = () => {
        document.querySelectorAll('.btn-add-to-cart').forEach(button => {
            button.onclick = (e) => {
                e.preventDefault();
                const productId = e.currentTarget.dataset.productId;
                const product = this.allProducts.find(p => String(p.product_id) === String(productId));
                if (product) {
                    this.openAddToCartModal(product);
                }
            };
        });
    }

    openAddToCartModal = (product) => {
        document.getElementById('modal-product-image-cat').src = product.image_url;
        document.getElementById('modal-product-name-cat').textContent = product.product_name;
        document.getElementById('modal-product-price-cat').textContent = `${product.unit_price.toLocaleString('ru-RU')} ₽`;
        document.getElementById('modal-stock-info-cat').textContent = `В наличии: ${product.stock_quantity} шт.`;

        const quantityInput = document.getElementById('quantity-input-cat');
        const decBtn = document.getElementById('decrease-quantity-cat');
        const incBtn = document.getElementById('increase-quantity-cat');
        const confirmBtn = document.getElementById('confirm-add-to-cart-cat');

        quantityInput.min = 1;
        quantityInput.max = Math.max(1, product.stock_quantity);
        quantityInput.value = 1;

        quantityInput.oninput = () => {
            let v = quantityInput.value.replace(/[^\d]/g, '');
            if (v === '') v = '1';
            let num = parseInt(v, 10);
            if (isNaN(num)) num = 1;
            if (num < 1) num = 1;
            const max = parseInt(quantityInput.max, 10);
            if (!isNaN(max) && num > max) num = max;
            quantityInput.value = String(num);
        };

        quantityInput.onblur = () => {
            let num = parseInt(quantityInput.value, 10) || 1;
            const max = parseInt(quantityInput.max, 10);
            if (!isNaN(max) && num > max) num = max;
            if (num < 1) num = 1;
            quantityInput.value = String(num);
        };

        decBtn.onclick = () => {
            let val = parseInt(quantityInput.value, 10) || 1;
            if (val > 1) quantityInput.value = val - 1;
        };

        incBtn.onclick = () => {
            let val = parseInt(quantityInput.value, 10) || 1;
            const max = parseInt(quantityInput.max, 10);
            if (isNaN(max) || val < max) {
                quantityInput.value = val + 1;
            } else {
                quantityInput.value = max;
            }
        };

        confirmBtn.onclick = () => {
            const qty = parseInt(quantityInput.value, 10) || 1;
            const safeQty = Math.max(1, Math.min(qty, product.stock_quantity || qty));

            if (typeof cartService !== 'undefined') {
                cartService.addToCart(product.product_id, safeQty, product);
                this.showNotification('Товар добавлен в корзину', 'success');
                if (typeof cartService.updateCartCounter === 'function') cartService.updateCartCounter();
                const modalInst = bootstrap.Modal.getInstance(document.getElementById('addToCartModalCat'));
                if (modalInst) modalInst.hide();
            } else {
                console.error('cartService не найден');
                this.showNotification('Ошибка: сервис корзины не доступен', 'error');
            }
        };

        const modal = new bootstrap.Modal(document.getElementById('addToCartModalCat'));
        modal.show();
    }

    addToCart = (productId) => {
        const product = this.allProducts.find(p => p.product_id == productId);
        if (product && product.stock_quantity > 0) {
            if (typeof cartService !== 'undefined') {
                cartService.addToCart(product);
                this.showNotification('Товар добавлен в корзину', 'success');
                
                this.updateCartCounter();
            } else {
                console.error('Cart service not available');
            }
        }
    }

    updateCartCounter = () => {
        if (typeof cartService !== 'undefined') {
            const cart = cartService.getCart();
            const totalItems = cart.items.reduce((sum, item) => sum + item.quantity, 0);
            
            const counters = document.querySelectorAll('.cart-counter');
            counters.forEach(counter => {
                if (totalItems > 0) {
                    counter.textContent = totalItems;
                    counter.style.display = 'inline-block';
                } else {
                    counter.style.display = 'none';
                }
            });
        }
    }

    showNotification = (message, type = 'info') => {
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

    renderPagination = () => {
        this.paginationContainer.innerHTML = "";

        const totalPages = Math.ceil(this.filteredProducts.length / this.productsPerPage);
        if (totalPages <= 1) return;

        const prevBtn = document.createElement("button");
        prevBtn.textContent = "← Назад";
        prevBtn.disabled = this.currentPage === 1;
        prevBtn.addEventListener("click", () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.renderProducts();
                this.renderPagination();
                window.scrollTo({ top: 0, behavior: "smooth" });
            }
        });
        this.paginationContainer.appendChild(prevBtn);

        let startPage = Math.max(1, this.currentPage - 1);
        let endPage = Math.min(totalPages, this.currentPage + 1);

        if (this.currentPage === 1) {
            endPage = Math.min(totalPages, 3);
        } else if (this.currentPage === totalPages) {
            startPage = Math.max(1, totalPages - 2);
        }

        if (startPage > 1) {
            const firstPageBtn = document.createElement("button");
            firstPageBtn.textContent = "1";
            firstPageBtn.addEventListener("click", () => {
                this.currentPage = 1;
                this.renderProducts();
                this.renderPagination();
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
            this.paginationContainer.appendChild(firstPageBtn);

            if (startPage > 2) {
                const ellipsis = document.createElement("span");
                ellipsis.textContent = "...";
                ellipsis.classList.add("pagination-ellipsis");
                this.paginationContainer.appendChild(ellipsis);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement("button");
            pageBtn.textContent = i;
            if (i === this.currentPage) pageBtn.classList.add("active");
            pageBtn.addEventListener("click", () => {
                this.currentPage = i;
                this.renderProducts();
                this.renderPagination();
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
            this.paginationContainer.appendChild(pageBtn);
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement("span");
                ellipsis.textContent = "...";
                ellipsis.classList.add("pagination-ellipsis");
                this.paginationContainer.appendChild(ellipsis);
            }

            const lastPageBtn = document.createElement("button");
            lastPageBtn.textContent = totalPages;
            lastPageBtn.addEventListener("click", () => {
                this.currentPage = totalPages;
                this.renderProducts();
                this.renderPagination();
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
            this.paginationContainer.appendChild(lastPageBtn);
        }

        const nextBtn = document.createElement("button");
        nextBtn.textContent = "Вперёд →";
        nextBtn.disabled = this.currentPage === totalPages;
        nextBtn.addEventListener("click", () => {
            if (this.currentPage < totalPages) {
                this.currentPage++;
                this.renderProducts();
                this.renderPagination();
                window.scrollTo({ top: 0, behavior: "smooth" });
            }
        });
        this.paginationContainer.appendChild(nextBtn);
    }

    updateResultsCount = () => {
        const resultsCount = document.getElementById('resultsCount');
        if (resultsCount) {
            resultsCount.textContent = `Найдено товаров: ${this.filteredProducts.length}`;
        }
    }

    resetFilters = () => {
        document.getElementById('priceMin').value = '';
        document.getElementById('priceMax').value = '';
        document.getElementById('brandFilter').value = '';
        document.getElementById('availabilityFilter').value = '';
        document.getElementById('sortSelect').value = 'default';
        
        this.currentFilters = {
            priceMin: '',
            priceMax: '',
            brand: '',
            availability: ''
        };
        
        this.currentSort = 'default';
        
        this.filteredProducts = this.allProducts.filter(
            product => product.category_name === this.selectedCategory
        );
        
        this.currentPage = 1;
        this.renderProducts();
        this.renderPagination();
        this.updateResultsCount();
        
        this.closeMobileFilters();
    }

    openMobileFilters = () => {
        document.getElementById('filtersSidebar').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeMobileFilters = () => {
        document.getElementById('filtersSidebar').classList.remove('active');
        document.body.style.overflow = '';
    }

    openMobileSort = () => {
        document.getElementById('mobileSortModal').classList.add('active');
        document.body.style.overflow = 'hidden';
        
        const currentSort = document.querySelector(`input[name="sort"][value="${this.currentSort}"]`);
        if (currentSort) {
            currentSort.checked = true;
        }
    }

    closeMobileSort = () => {
        document.getElementById('mobileSortModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    applyMobileSort = () => {
        const selectedSort = document.querySelector('input[name="sort"]:checked').value;
        this.currentSort = selectedSort;
        document.getElementById('sortSelect').value = selectedSort;
        this.currentPage = 1;
        this.renderProducts();
        this.renderPagination();
        this.closeMobileSort();
    }

    setupEventListeners = () => {
        document.getElementById('applyFilters').addEventListener('click', () => {
            this.currentFilters = {
                priceMin: document.getElementById('priceMin').value,
                priceMax: document.getElementById('priceMax').value,
                brand: document.getElementById('brandFilter').value,
                availability: document.getElementById('availabilityFilter').value
            };
            
            this.applyFilters();
        });

        document.getElementById('resetFilters').addEventListener('click', this.resetFilters);

        document.getElementById('sortSelect').addEventListener('change', (e) => {
            this.currentSort = e.target.value;
            this.currentPage = 1;
            this.renderProducts();
            this.renderPagination();
        });

        document.getElementById('priceMin').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                document.getElementById('applyFilters').click();
            }
        });

        document.getElementById('priceMax').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                document.getElementById('applyFilters').click();
            }
        });
    }

    setupMobileEventListeners = () => {
        document.getElementById('mobileFilterBtn').addEventListener('click', this.openMobileFilters);
        
        document.getElementById('closeFilters').addEventListener('click', this.closeMobileFilters);
        
        document.getElementById('mobileSortBtn').addEventListener('click', this.openMobileSort);
        
        document.getElementById('closeSortModal').addEventListener('click', this.closeMobileSort);
        
        document.getElementById('applySort').addEventListener('click', this.applyMobileSort);
        
        document.getElementById('mobileSortModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('mobileSortModal')) {
                this.closeMobileSort();
            }
        });
        
        document.getElementById('filtersSidebar').addEventListener('click', (e) => {
            if (e.target === document.getElementById('filtersSidebar')) {
                this.closeMobileFilters();
            }
        });
    }
}

new CategoryPage();