fetch('products.json')
    .then(response => response.json())
    .then(products => {
        const catalogContainer = document.getElementById('catalog-container');

        const categories = {};
        products.forEach(product => {
            if (!categories[product.category_name]) {
                categories[product.category_name] = [];
            }
            categories[product.category_name].push(product);
        });

        Object.keys(categories).forEach(categoryName => {
            const items = categories[categoryName];

            const randomProduct = items[Math.floor(Math.random() * items.length)];

            const categoryCard = document.createElement('div');
            categoryCard.classList.add('category-card');
            categoryCard.dataset.category = categoryName;

            categoryCard.innerHTML = `
        <div class="category-card__img">
            <img src="${randomProduct.image_url}" alt="${categoryName}">
        </div>
        <h3 class="category-card__title">${categoryName}</h3>
        `;

            catalogContainer.appendChild(categoryCard);
        });
    })
    .catch(error => {
        console.error('Ошибка при загрузке товаров:', error);
    });


document.addEventListener('click', (e) => {
    const card = e.target.closest('.category-card');
    if (card) {
        const category = card.dataset.category;
        window.location.href = `category.html?category=${encodeURIComponent(category)}`;
    }
});