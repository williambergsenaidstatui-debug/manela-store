if (document.querySelector('[data-shirt-viewer]')) {
    import('./lm-shirt-viewer.js').catch(() => {
        document.querySelector('[data-shirt-status]').textContent = 'Camiseta LM · Estilo em cada detalhe';
    });
}

const menuToggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('#main-navigation');
const closeMenu = () => {
    navigation.classList.remove('is-open');
    menuToggle.setAttribute('aria-expanded', 'false');
    menuToggle.setAttribute('aria-label', 'Abrir menu');
};

menuToggle.addEventListener('click', () => {
    const expanded = menuToggle.getAttribute('aria-expanded') !== 'true';
    navigation.classList.toggle('is-open', expanded);
    menuToggle.setAttribute('aria-expanded', String(expanded));
    menuToggle.setAttribute('aria-label', expanded ? 'Fechar menu' : 'Abrir menu');
});
navigation.addEventListener('click', (event) => {
    if (event.target.closest('a')) closeMenu();
});
document.addEventListener('click', (event) => {
    if (!event.target.closest('.site-header')) closeMenu();
});
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        if (navigation.classList.contains('is-open')) {
            closeMenu();
            menuToggle.focus();
        }
        if (!searchForm.hidden) closeSearch();
    }
});
window.matchMedia('(min-width: 701px)').addEventListener('change', closeMenu);

const filters = document.querySelectorAll('[data-filter]');
const products = document.querySelectorAll('.product-card');
const searchForm = document.querySelector('#home-search');
const searchInput = document.querySelector('#product-search');
const searchToggle = document.querySelector('[data-search-toggle]');
const emptyState = document.querySelector('.empty-state');
const normalize = (value) => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase('pt-BR');
let selectedFilter = 'all';

const updateProducts = () => {
    let visible = 0;
    products.forEach((product) => {
        const matchesCategory = selectedFilter === 'all' || product.dataset.category === selectedFilter;
        const matchesSearch = normalize(product.dataset.name).includes(normalize(searchInput.value.trim()));
        product.hidden = !matchesCategory || !matchesSearch;
        if (!product.hidden) visible++;
    });
    emptyState.hidden = visible > 0;
};
const selectFilter = (filter) => {
    selectedFilter = filter;
    filters.forEach((button) => {
        const active = button.dataset.filter === filter;
        button.classList.toggle('active', active);
        button.setAttribute('aria-pressed', String(active));
    });
    updateProducts();
};
filters.forEach((button) => button.addEventListener('click', () => selectFilter(button.dataset.filter)));
document.querySelectorAll('[data-select-filter]').forEach((link) => {
    link.addEventListener('click', () => {
        searchInput.value = '';
        selectFilter(link.dataset.selectFilter);
    });
});
const closeSearch = () => {
    searchForm.hidden = true;
    searchToggle.setAttribute('aria-expanded', 'false');
    searchInput.value = '';
    updateProducts();
    searchToggle.focus({ preventScroll: true });
};
searchToggle.addEventListener('click', () => {
    if (!searchForm.hidden) return closeSearch();
    closeMenu();
    searchForm.hidden = false;
    searchToggle.setAttribute('aria-expanded', 'true');
    selectFilter('all');
    searchForm.scrollIntoView({ block: 'center' });
    searchInput.focus({ preventScroll: true });
});
document.querySelector('[data-search-close]').addEventListener('click', closeSearch);
searchForm.addEventListener('submit', (event) => event.preventDefault());
searchInput.addEventListener('input', updateProducts);

document.querySelectorAll('.favorite-button').forEach((button) => {
    const name = button.closest('.product-card').dataset.name;
    const key = `lm-favorite:${name}`;
    const setFavorite = (favorite) => {
        button.setAttribute('aria-pressed', String(favorite));
        button.setAttribute('aria-label', `${favorite ? 'Remover dos favoritos:' : 'Favoritar'} ${name}`);
    };
    try { setFavorite(localStorage.getItem(key) === 'true'); } catch { /* Permite favoritar nesta visita. */ }
    button.addEventListener('click', () => {
        const favorite = button.getAttribute('aria-pressed') !== 'true';
        setFavorite(favorite);
        try { localStorage.setItem(key, String(favorite)); } catch { /* Permite favoritar nesta visita. */ }
    });
});
