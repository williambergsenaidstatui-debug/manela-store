const catalogState = { products: [], category: 'all', query: '' };

document.addEventListener('DOMContentLoaded', () => {
    if (!document.body.classList.contains('products-page')) return;

    document.querySelector('#catalog-search')?.addEventListener('input', (event) => {
        catalogState.query = normalizeText(event.target.value);
        renderCatalog();
    });
    loadCatalog();
});

async function loadCatalog() {
    const container = document.querySelector('#catalog-products');
    try {
        const response = await fetch('/api/cardapio', { headers: { Accept: 'application/json' } });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Nao foi possivel carregar o cardapio.');

        catalogState.products = Array.isArray(data) ? data : [];
        renderCategories();
        renderCatalog();
    } catch (error) {
        container.innerHTML = `<div class="catalog-empty error">${escapeHtml(error.message)}</div>`;
    }
}

function renderCategories() {
    const container = document.querySelector('#catalog-categories');
    if (!container) return;

    const categories = categoryGroups(catalogState.products);
    container.innerHTML = `
        <button class="category active" type="button" data-category="all"><span class="category-icon">▦</span><span>Todos os produtos<small>${catalogState.products.length} ${catalogState.products.length === 1 ? 'produto' : 'produtos'}</small></span></button>
        ${categories.map((category) => {
            const count = category.products.length;
            return `<button class="category" type="button" data-category="${escapeHtml(category.key)}"><span class="category-icon">${categoryIcon(category.name)}</span><span>${escapeHtml(category.name)}<small>${count} ${count === 1 ? 'produto' : 'produtos'}</small></span></button>`;
        }).join('')}
        <div class="love-note">Feito<br>com<br>amor ♡</div>`;

    container.querySelectorAll('[data-category]').forEach((button) => {
        button.addEventListener('click', () => {
            catalogState.category = button.dataset.category;
            container.querySelectorAll('[data-category]').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
            renderCatalog();
        });
    });
}

function renderCatalog() {
    const container = document.querySelector('#catalog-products');
    if (!container) return;

    const filtered = catalogState.products.filter((product) => {
        const sameCategory = catalogState.category === 'all' || categoryKey(product.categoria) === catalogState.category;
        const searchable = normalizeText([product.nome, product.categoria, product.sobre, product.descricao].filter(Boolean).join(' '));
        return sameCategory && searchable.includes(catalogState.query);
    });

    if (!filtered.length) {
        container.innerHTML = `<div class="catalog-empty">${catalogState.products.length ? 'Nenhum produto encontrado.' : 'Nenhum produto cadastrado ainda.'}</div>`;
        return;
    }

    container.innerHTML = categoryGroups(filtered).map((category) => `
        <section class="product-group">
            <div class="group-title"><h3>${escapeHtml(category.name)}</h3><span>${category.products.length} ${category.products.length === 1 ? 'opcao' : 'opcoes'}</span></div>
            <div class="card-grid">${category.products.map(productCard).join('')}</div>
        </section>`).join('');
}

function productCard(product) {
    return `<article class="product-card" data-name="${escapeHtml(product.nome)}">
        <div class="product-image"><img src="${escapeHtml(productImage(product))}" alt="${escapeHtml(product.nome)}" loading="lazy"><b class="heart" aria-hidden="true">♡</b></div>
        <div class="product-info"><h4>${escapeHtml(product.nome)}</h4><p>${escapeHtml(product.sobre || product.descricao || '')}</p><div class="price">R$ ${formatPrice(product.preco)}</div><a class="details" href="/carrinho?produto=${encodeURIComponent(product.id)}">Comprar →</a></div>
    </article>`;
}

function categoryGroups(products) {
    const groups = new Map();

    products.forEach((product) => {
        const key = categoryKey(product.categoria);
        if (!groups.has(key)) {
            groups.set(key, {
                key,
                name: displayCategory(product.categoria),
                products: [],
            });
        }

        groups.get(key).products.push(product);
    });

    return [...groups.values()];
}

function categoryIcon(category) {
    const value = normalizeText(category);
    if (value.includes('bolo')) return '♕';
    if (value.includes('doce') || value.includes('brigadeiro') || value.includes('bombom')) return '●';
    if (value.includes('sobremesa') || value.includes('taca') || value.includes('copo')) return '◇';
    return '♡';
}

function productImage(product) {
    if (!product.imagem) return '/assets/images/bolo-morango.jpg';
    return product.imagem.startsWith('http') || product.imagem.startsWith('/') ? product.imagem : `/${product.imagem}`;
}

function formatPrice(value) {
    return Number(value || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function normalizeText(value) {
    return String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
}

function categoryKey(category) {
    return normalizeText(category || 'Outros').replace(/\s+/g, ' ');
}

function displayCategory(category) {
    const name = String(category || 'Outros').trim().replace(/\s+/g, ' ');
    if (!name) return 'Outros';
    return name.charAt(0).toUpperCase() + name.slice(1);
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
}
