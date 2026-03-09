class ProductSearch {
    constructor() {
        this.searchInput = document.querySelector('.search-container input[type="text"]');
        this.searchContainer = document.querySelector('.search-container');
        this.resultsDropdown = null;
        this.isSearchActive = false;
        this.debounceTimer = null;

        if (!this.searchInput) return;

        this.createDropdown();
        this.setupEventListeners();
    }

    createDropdown() {
        this.resultsDropdown = document.createElement('div');
        this.resultsDropdown.className = 'search-results-dropdown';
        this.resultsDropdown.style.display = 'none';
        this.searchContainer.style.position = 'relative';
        this.searchContainer.appendChild(this.resultsDropdown);
    }

    setupEventListeners() {
        this.searchInput.addEventListener('input', (e) => this.handleInput(e));
        document.addEventListener('click', (e) => this.handleClickOutside(e));
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
    }

    handleInput(e) {
        clearTimeout(this.debounceTimer);
        const query = e.target.value.trim();

        if (query.length < 2) {
            this.hideDropdown();
            return;
        }

        this.debounceTimer = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }

    async performSearch(query) {
        try {
            const response = await fetch(`/search?q=${encodeURIComponent(query)}`);
            const products = await response.json();
            this.renderResults(products);
        } catch (error) {
            console.error('Ошибка поиска:', error);
        }
    }

    renderResults(products) {
        if (!this.resultsDropdown) return;

        if (products.length === 0) {
            this.resultsDropdown.innerHTML = `
                <div class="search-result-item no-results">
                    <span>Товары не найдены</span>
                </div>
            `;
            this.showDropdown();
            return;
        }

        let html = '';
        products.forEach(product => {
            html += `
                <div class="search-result-item" data-product-id="${product.id}">
                    <img src="${product.image}" alt="${product.name}" style="width:40px;height:40px;object-fit:cover;margin-right:10px;">
                    <div class="search-result-info">
                        <div class="search-result-name">${product.name}</div>
                        <div class="search-result-category">${product.category}</div>
                        <div class="search-result-price">${new Intl.NumberFormat('ru-RU').format(product.price)} ₽</div>
                    </div>
                </div>
            `;
        });

        this.resultsDropdown.innerHTML = html;
        this.setupClickHandlers();
        this.showDropdown();
    }

    setupClickHandlers() {
        this.resultsDropdown.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', () => {
                const productId = item.dataset.productId;
                if (productId) {
                    window.location.href = `/product/${productId}`;
                }
            });
        });
    }

    showDropdown() {
        this.resultsDropdown.style.display = 'block';
        this.isSearchActive = true;
    }

    hideDropdown() {
        this.resultsDropdown.style.display = 'none';
        this.isSearchActive = false;
    }

    handleClickOutside(e) {
        if (!this.searchContainer.contains(e.target)) {
            this.hideDropdown();
        }
    }

    handleKeyboard(e) {
        if (!this.isSearchActive) return;

        const items = this.resultsDropdown.querySelectorAll('.search-result-item:not(.no-results)');
        if (items.length === 0) return;

        let currentIndex = -1;
        items.forEach((item, index) => {
            if (item.classList.contains('selected')) {
                currentIndex = index;
                item.classList.remove('selected');
            }
        });

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                currentIndex = (currentIndex + 1) % items.length;
                break;
            case 'ArrowUp':
                e.preventDefault();
                currentIndex = currentIndex <= 0 ? items.length - 1 : currentIndex - 1;
                break;
            case 'Enter':
                e.preventDefault();
                if (currentIndex >= 0) {
                    const productId = items[currentIndex].dataset.productId;
                    window.location.href = `/product/${productId}`;
                }
                return;
            case 'Escape':
                this.hideDropdown();
                return;
            default:
                return;
        }

        if (currentIndex >= 0) {
            items[currentIndex].classList.add('selected');
            items[currentIndex].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ProductSearch();
});