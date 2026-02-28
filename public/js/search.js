class ProductSearch {
    constructor() {
        this.allProducts = [];
        this.searchResults = [];
        this.isSearchActive = false;
        this.searchContainer = null;
        this.searchInput = null;
        this.resultsDropdown = null;
        
        this.init();
    }

    init = async () => {
        await this.loadProducts();
        this.setupSearchContainers();
        this.setupEventListeners();
    }

    loadProducts = async () => {
        try {
            const response = await fetch('products.json');
            this.allProducts = await response.json();
        } catch (error) {
            console.error('Ошибка загрузки товаров для поиска:', error);
        }
    }

    setupSearchContainers = () => {
        const searchContainers = document.querySelectorAll('.search-container');
        
        searchContainers.forEach(container => {
            const input = container.querySelector('input[type="text"]');
            if (input) {
                this.createSearchDropdown(container, input);
            }
        });
    }

    createSearchDropdown = (container, input) => {
        const dropdown = document.createElement('div');
        dropdown.className = 'search-results-dropdown';
        dropdown.style.display = 'none';
        
        container.style.position = 'relative';
        container.appendChild(dropdown);
        
        this.searchContainer = container;
        this.searchInput = input;
        this.resultsDropdown = dropdown;
    }

    setupEventListeners = () => {
        document.addEventListener('input', this.handleSearchInput);
        document.addEventListener('click', this.handleClickOutside);
        document.addEventListener('keydown', this.handleKeyboardNavigation);
    }

    handleSearchInput = (e) => {
        if (e.target.matches('.search-container input[type="text"]')) {
            const query = e.target.value.trim();
            
            if (query.length >= 2) {
                this.performSearch(query);
                this.showDropdown();
            } else {
                this.hideDropdown();
            }
        }
    }

    performSearch = (query) => {
        const searchTerm = query.toLowerCase();
        
        this.searchResults = this.allProducts.filter(product => {
            const searchFields = [
                product.product_name,
                product.category_name,
                product.description,
                product.brand || ''
            ];
            
            return searchFields.some(field => 
                field && field.toLowerCase().includes(searchTerm)
            );
        }).slice(0, 10); 
        
        this.renderSearchResults();
    }

    renderSearchResults = () => {
        if (!this.resultsDropdown) return;

        if (this.searchResults.length === 0) {
            this.resultsDropdown.innerHTML = `
                <div class="search-result-item no-results">
                    <span>Товары не найдены</span>
                </div>
            `;
            return;
        }

        let resultsHTML = '';
        
        this.searchResults.forEach((product, index) => {
            resultsHTML += `
                <div class="search-result-item" data-product-id="${product.product_id}" data-index="${index}">
                    <div class="search-result-info">
                        <div class="search-result-name">${product.product_name}</div>
                        <div class="search-result-category">${product.category_name}</div>
                        <div class="search-result-price">${product.unit_price.toLocaleString()} ₽</div>
                    </div>
                </div>
            `;
        });

        this.resultsDropdown.innerHTML = resultsHTML;
        
        this.setupResultClickHandlers();
    }

    setupResultClickHandlers = () => {
        const resultItems = this.resultsDropdown.querySelectorAll('.search-result-item');
        
        resultItems.forEach(item => {
            if (!item.classList.contains('no-results')) {
                item.addEventListener('click', (e) => {
                    const productId = item.dataset.productId;
                    this.navigateToProduct(productId);
                });
            }
        });
    }

    navigateToProduct = (productId) => {
        window.location.href = `product.html?id=${productId}`;
    }

    showDropdown = () => {
        if (this.resultsDropdown) {
            this.resultsDropdown.style.display = 'block';
            this.isSearchActive = true;
        }
    }

    hideDropdown = () => {
        if (this.resultsDropdown) {
            this.resultsDropdown.style.display = 'none';
            this.isSearchActive = false;
        }
    }

    handleClickOutside = (e) => {
        if (!e.target.closest('.search-container')) {
            this.hideDropdown();
        }
    }

    handleKeyboardNavigation = (e) => {
        if (!this.isSearchActive) return;

        const results = this.resultsDropdown.querySelectorAll('.search-result-item:not(.no-results)');
        if (results.length === 0) return;

        let currentIndex = -1;
        
        results.forEach((item, index) => {
            if (item.classList.contains('selected')) {
                currentIndex = index;
                item.classList.remove('selected');
            }
        });

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                currentIndex = (currentIndex + 1) % results.length;
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                currentIndex = currentIndex <= 0 ? results.length - 1 : currentIndex - 1;
                break;
                
            case 'Enter':
                e.preventDefault();
                if (currentIndex >= 0) {
                    const productId = results[currentIndex].dataset.productId;
                    this.navigateToProduct(productId);
                }
                return;
                
            case 'Escape':
                this.hideDropdown();
                return;
                
            default:
                return;
        }

        if (currentIndex >= 0) {
            results[currentIndex].classList.add('selected');
            this.scrollToSelected(results[currentIndex]);
        }
    }

    scrollToSelected = (selectedElement) => {
        if (this.resultsDropdown && selectedElement) {
            selectedElement.scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }

    search = (query) => {
        if (this.searchInput) {
            this.searchInput.value = query;
            this.performSearch(query);
            this.showDropdown();
            this.searchInput.focus();
        }
    }

    clearSearch = () => {
        if (this.searchInput) {
            this.searchInput.value = '';
            this.hideDropdown();
        }
    }
}

let globalSearch = null;

document.addEventListener('DOMContentLoaded', () => {
    globalSearch = new ProductSearch();
});

window.ProductSearch = {
    getInstance: () => globalSearch,
    search: (query) => globalSearch?.search(query),
    clear: () => globalSearch?.clearSearch()
};