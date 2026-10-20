const cartState = {
    products: [],
    items: [],
    deliveryFee: 12,
};

document.addEventListener('DOMContentLoaded', () => {
    if (!document.body.classList.contains('cart-page')) return;

    setupChoices();
    setupCashChange();
    setupSubmit();
    loadCartProducts();
});

async function loadCartProducts() {
    try {
        const response = await fetch('/api/cardapio', { headers: { Accept: 'application/json' } });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Nao foi possivel carregar os produtos.');

        cartState.products = Array.isArray(data) ? data : [];
        restoreCart();
        addProductFromUrl();
        renderProductSelect();
        renderCart();
    } catch (error) {
        document.querySelector('#cart-items').innerHTML = `<p class="cart-empty">${escapeHtml(error.message)}</p>`;
        document.querySelector('#cart-product-select').innerHTML = '<option value="">Produtos indisponiveis</option>';
    }
}

function setupChoices() {
    document.querySelectorAll('.cart-choice, .cart-payment').forEach((choice) => {
        choice.addEventListener('click', () => {
            const input = choice.querySelector('input');
            if (!input) return;
            document.querySelectorAll(`input[name="${input.name}"]`).forEach((radio) => {
                radio.closest('.cart-choice, .cart-payment')?.classList.toggle('is-selected', radio === input);
            });
            renderCart();
        });
    });

    document.querySelector('#cart-add-product')?.addEventListener('click', () => {
        const select = document.querySelector('#cart-product-select');
        const product = cartState.products.find((item) => String(item.id) === String(select?.value));
        if (!product) return;
        addItem(product.id);
        select.value = '';
    });
}

function setupCashChange() {
    const panel = document.querySelector('#cash-change-options');
    const valueWrapper = document.querySelector('.cash-change-value');
    const valueInput = document.querySelector('#troco_para');
    if (!panel) return;

    const sync = () => {
        const payment = document.querySelector('input[name="pagamento"]:checked')?.value || 'dinheiro';
        const needsChange = document.querySelector('input[name="precisa_troco"]:checked')?.value === 'sim';
        panel.classList.toggle('is-hidden', payment !== 'dinheiro');
        valueWrapper?.classList.toggle('is-hidden', payment !== 'dinheiro' || !needsChange);
        if (valueInput) {
            valueInput.disabled = payment !== 'dinheiro' || !needsChange;
            valueInput.required = payment === 'dinheiro' && needsChange;
            if (valueInput.disabled) valueInput.value = '';
        }
    };

    document.querySelectorAll('input[name="pagamento"], input[name="precisa_troco"]').forEach((input) => {
        input.addEventListener('change', sync);
    });
    sync();
}
function setupSubmit() {
    document.querySelector('#checkout-form')?.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!cartState.items.length) {
            alert('Adicione pelo menos um produto ao carrinho.');
            return;
        }

        syncPayload();
        const form = event.currentTarget;
        const submit = form.querySelector('.cart-submit');
        const originalHtml = submit?.innerHTML;

        if (submit) {
            submit.disabled = true;
            submit.innerHTML = 'Processando pedido...';
        }

        try {
            const response = await fetch('/api/pagamentos/checkout', {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formPayload(form)),
            });
            const result = await response.json().catch(() => ({}));

            if (!response.ok || result.erro === 's') {
                throw new Error(result.mensagem || result.message || 'Nao foi possivel finalizar o pedido.');
            }

            showCheckoutResult(result);
            document.dispatchEvent(new CustomEvent('cart:checkout', { detail: result }));
        } catch (error) {
            showCheckoutResult({ erro: 's', mensagem: error.message });
        } finally {
            if (submit) {
                submit.disabled = false;
                submit.innerHTML = originalHtml || 'Finalizar pedido';
            }
        }
    });
}

function formPayload(form) {
    const data = Object.fromEntries(new FormData(form).entries());
    data.cart_payload = document.querySelector('#cart-payload')?.value || JSON.stringify(currentPayload());
    return data;
}

function showCheckoutResult(result) {
    let panel = document.querySelector('#cart-checkout-result');
    const submit = document.querySelector('.cart-submit');

    if (!panel) {
        panel = document.createElement('div');
        panel.id = 'cart-checkout-result';
        panel.className = 'cart-checkout-result';
        submit?.insertAdjacentElement('afterend', panel);
    }

    if (result.erro === 's') {
        panel.className = 'cart-checkout-result error';
        panel.innerHTML = `<strong>Ops, nao foi possivel finalizar.</strong><p>${escapeHtml(result.mensagem || 'Tente novamente em instantes.')}</p>`;
        panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    const pedido = result.pedido || {};
    const pixImage = pedido.pix_encoded_image ? `<img src="data:image/png;base64,${escapeHtml(pedido.pix_encoded_image)}" alt="QR Code Pix">` : '';
    const pixPayload = pedido.pix_payload ? `<label>Pix copia e cola<textarea readonly>${escapeHtml(pedido.pix_payload)}</textarea></label>` : '';
    const paymentLink = pedido.invoice_url ? `<a href="${escapeHtml(pedido.invoice_url)}" target="_blank" rel="noopener">Abrir link de pagamento</a>` : '';

    panel.className = 'cart-checkout-result success';
    panel.innerHTML = `
        <strong>${escapeHtml(result.mensagem || 'Pedido criado com sucesso.')}</strong>
        <p>Codigo do pedido: ${escapeHtml(pedido.code || '')}</p>
        ${paymentLink}
        ${pixImage}
        ${pixPayload}
    `;
    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function restoreCart() {
    try {
        const saved = JSON.parse(localStorage.getItem('sanDocesCart') || '[]');
        cartState.items = Array.isArray(saved)
            ? saved.filter((item) => findProduct(item.id)).map((item) => ({ id: item.id, quantity: Math.max(1, Number(item.quantity) || 1) }))
            : [];
    } catch {
        cartState.items = [];
    }
}

function addProductFromUrl() {
    const productId = new URLSearchParams(window.location.search).get('produto');
    if (productId && findProduct(productId) && !cartState.items.some((item) => String(item.id) === String(productId))) {
        cartState.items.push({ id: productId, quantity: 1 });
    }
    saveCart();
}

function renderProductSelect() {
    const select = document.querySelector('#cart-product-select');
    if (!select) return;

    if (!cartState.products.length) {
        select.innerHTML = '<option value="">Nenhum produto cadastrado</option>';
        return;
    }

    select.innerHTML = [
        '<option value="">Selecione um produto</option>',
        ...cartState.products.map((product) => `<option value="${escapeHtml(product.id)}">${escapeHtml(product.nome)} - R$ ${formatPrice(product.preco)}</option>`),
    ].join('');
}

function renderCart() {
    renderItems();
    renderSummary();
    syncPayload();
    saveCart();
}

function renderItems() {
    const container = document.querySelector('#cart-items');
    const count = document.querySelector('#cart-count');
    if (!container) return;

    const totalQuantity = cartState.items.reduce((sum, item) => sum + item.quantity, 0);
    if (count) count.textContent = `(${totalQuantity} ${totalQuantity === 1 ? 'item' : 'itens'})`;

    if (!cartState.items.length) {
        container.innerHTML = `
            <div class="cart-empty">
                <strong>Seu carrinho esta vazio.</strong>
                <span>Escolha um produto abaixo ou volte ao cardapio para comecar seu pedido.</span>
            </div>`;
        return;
    }

    container.innerHTML = cartState.items.map((item) => {
        const product = findProduct(item.id);
        return `
            <article class="cart-item" data-cart-product="${escapeHtml(item.id)}">
                <img src="${escapeHtml(productImage(product))}" alt="${escapeHtml(product.nome)}">
                <div class="cart-item-info">
                    <h3>${escapeHtml(product.nome)}</h3>
                    <p>${escapeHtml(product.sobre || product.descricao || 'Doce artesanal San Doces.')}</p>
                </div>
                <button class="cart-remove" type="button" data-action="remove" aria-label="Remover ${escapeHtml(product.nome)}">&times;</button>
                <div class="cart-quantity" aria-label="Quantidade de ${escapeHtml(product.nome)}">
                    <button type="button" data-action="decrease" aria-label="Diminuir quantidade">-</button>
                    <span>${item.quantity}</span>
                    <button type="button" data-action="increase" aria-label="Aumentar quantidade">+</button>
                </div>
                <strong>R$ ${formatPrice(Number(product.preco || 0) * item.quantity)}</strong>
            </article>`;
    }).join('');

    container.querySelectorAll('[data-action]').forEach((button) => {
        button.addEventListener('click', () => updateItem(button.closest('[data-cart-product]')?.dataset.cartProduct, button.dataset.action));
    });
}

function renderSummary() {
    const summary = document.querySelector('#cart-summary-items');
    if (!summary) return;

    const subtotal = cartState.items.reduce((sum, item) => {
        const product = findProduct(item.id);
        return sum + Number(product?.preco || 0) * item.quantity;
    }, 0);
    const delivery = selectedOrderType() === 'delivery' && cartState.items.length ? cartState.deliveryFee : 0;
    const total = subtotal + delivery;

    summary.innerHTML = cartState.items.length
        ? cartState.items.map((item) => {
            const product = findProduct(item.id);
            return `
                <article>
                    <img src="${escapeHtml(productImage(product))}" alt="${escapeHtml(product.nome)}">
                    <span><strong>${escapeHtml(product.nome)}</strong><small>${item.quantity} unidade(s)</small></span>
                    <b>R$ ${formatPrice(Number(product.preco || 0) * item.quantity)}</b>
                </article>`;
        }).join('')
        : '<p class="cart-summary-empty">Nenhum item adicionado.</p>';

    document.querySelector('#cart-subtotal').textContent = `R$ ${formatPrice(subtotal)}`;
    document.querySelector('#cart-delivery').textContent = delivery ? `R$ ${formatPrice(delivery)}` : 'Gratis';
    document.querySelector('#cart-total').textContent = `R$ ${formatPrice(total)}`;
    document.querySelector('#cart-total-input').value = total.toFixed(2);
}

function addItem(productId) {
    const current = cartState.items.find((item) => String(item.id) === String(productId));
    if (current) {
        current.quantity += 1;
    } else {
        cartState.items.push({ id: productId, quantity: 1 });
    }
    renderCart();
}

function updateItem(productId, action) {
    const item = cartState.items.find((cartItem) => String(cartItem.id) === String(productId));
    if (!item) return;

    if (action === 'remove') {
        cartState.items = cartState.items.filter((cartItem) => String(cartItem.id) !== String(productId));
    }
    if (action === 'increase') item.quantity += 1;
    if (action === 'decrease') item.quantity -= 1;
    cartState.items = cartState.items.filter((cartItem) => cartItem.quantity > 0);
    renderCart();
}

function currentPayload() {
    return {
        items: cartState.items.map((item) => {
            const product = findProduct(item.id);
            return {
                id: item.id,
                nome: product?.nome,
                preco: Number(product?.preco || 0),
                quantidade: item.quantity,
                subtotal: Number(product?.preco || 0) * item.quantity,
            };
        }),
        tipo_pedido: selectedOrderType(),
        pagamento: document.querySelector('input[name="pagamento"]:checked')?.value || 'dinheiro',
        agendamento: document.querySelector('#agendamento')?.value || '',
        precisa_troco: document.querySelector('input[name="precisa_troco"]:checked')?.value || 'nao',
        troco_para: document.querySelector('#troco_para')?.value || '',
    };
}

function syncPayload() {
    const input = document.querySelector('#cart-payload');
    if (input) input.value = JSON.stringify(currentPayload());
}

function selectedOrderType() {
    return document.querySelector('input[name="tipo_pedido"]:checked')?.value || 'delivery';
}

function saveCart() {
    localStorage.setItem('sanDocesCart', JSON.stringify(cartState.items));
}

function findProduct(productId) {
    return cartState.products.find((product) => String(product.id) === String(productId));
}

function productImage(product) {
    if (product?.imagem) {
        return product.imagem.startsWith('http') || product.imagem.startsWith('/') ? product.imagem : `/${product.imagem}`;
    }
    return '/assets/images/bolo-morango.jpg';
}

function formatPrice(value) {
    return Number(value || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
}






