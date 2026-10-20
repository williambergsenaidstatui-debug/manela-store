const estadoEncomenda = {
    produtos: [],
    itens: [],
};

document.addEventListener('DOMContentLoaded', () => {
    carregarProdutosEncomenda();

    const form = document.querySelector('#formulario');
    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        sincronizarCamposPedido();

        if (!estadoEncomenda.itens.length) {
            mostrarErro('Escolha pelo menos um produto antes de enviar o pedido.');
            document.querySelector('#order-products')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }

        const dados = {
            nome: document.querySelector('#nome')?.value.trim(),
            whatsapp: document.querySelector('#whatsapp')?.value.trim(),
            data_encomenda: document.querySelector('#data_encomenda')?.value,
            categoria: document.querySelector('#categoria')?.value,
            produto: document.querySelector('#produto')?.value.trim(),
            observacao: document.querySelector('#observacao')?.value.trim(),
        };

        try {
            const response = await fetch('/api/pedido_doces', {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dados),
            });
            const resultado = await response.json();

            if (!response.ok || resultado.erro === 's') {
                throw new Error(resultado.mensagem || resultado.message || 'Nao foi possivel enviar o pedido.');
            }

            if (window.Swal) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Pedido recebido!',
                    text: resultado.mensagem,
                });
            }

            form.reset();
            estadoEncomenda.itens = [];
            atualizarPedidoSelecionado();
            window.open(resultado.whatsapp_url, '_blank');
        } catch (error) {
            mostrarErro(error.message);
        }
    });
});

async function carregarProdutosEncomenda() {
    const container = document.querySelector('#order-products');
    const categorias = document.querySelector('#order-category-list');
    if (!container || !categorias) {
        return;
    }

    try {
        const response = await fetch('/api/cardapio', { headers: { Accept: 'application/json' } });
        const produtos = await response.json();

        if (!response.ok) {
            throw new Error('Nao foi possivel carregar os produtos.');
        }

        estadoEncomenda.produtos = Array.isArray(produtos) ? produtos : [];
        renderizarCategorias(estadoEncomenda.produtos, categorias, container);
        renderizarProdutos(estadoEncomenda.produtos, container);
        selecionarProdutoDaUrl(estadoEncomenda.produtos);
    } catch (error) {
        container.innerHTML = `<p class="order-products-empty">${escapeHtml(error.message)}</p>`;
    }
}

function selecionarProdutoDaUrl(produtos) {
    const productId = new URLSearchParams(window.location.search).get('produto');
    if (!productId) {
        return;
    }

    const produto = produtos.find((item) => String(item.id) === productId);
    if (!produto) {
        return;
    }

    adicionarProdutoAoPedido(produto);
    document.querySelector('#formulario')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function renderizarCategorias(produtos, categorias, container) {
    const grupos = gruposDeCategoria(produtos);
    categorias.innerHTML = [
        '<button class="category active" type="button" data-category="all">Todos &rsaquo;</button>',
        ...grupos.map((grupo) => `<button class="category" type="button" data-category="${escapeHtml(grupo.key)}">${escapeHtml(grupo.name)} &rsaquo;</button>`),
    ].join('');

    categorias.querySelectorAll('.category').forEach((button) => {
        button.addEventListener('click', () => {
            categorias.querySelectorAll('.category').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
            const categoria = button.dataset.category;
            renderizarProdutos(
                categoria === 'all' ? produtos : produtos.filter((produto) => chaveCategoria(produto.categoria) === categoria),
                container,
            );
        });
    });
}

function renderizarProdutos(produtos, container) {
    if (!produtos.length) {
        container.innerHTML = '<p class="order-products-empty">Nenhum produto cadastrado ainda.</p>';
        return;
    }

    container.innerHTML = produtos.map((produto) => `
        <article class="card order-product-card ${produtoJaAdicionado(produto.id) ? 'selected' : ''}" data-product-id="${produto.id}">
            <img src="${imagemProduto(produto)}" alt="${escapeHtml(produto.nome)}">
            <div>
                <small>${escapeHtml(produto.categoria || 'Cardapio')}</small>
                <b>${escapeHtml(produto.nome)}</b>
                <p>${escapeHtml(produto.sobre || produto.descricao || '')}</p>
                <strong>R$ ${Number(produto.preco || 0).toFixed(2).replace('.', ',')}</strong>
                <button class="order-select-button" type="button">${produtoJaAdicionado(produto.id) ? 'Adicionado' : 'Adicionar'}</button>
            </div>
        </article>
    `).join('');

    container.querySelectorAll('.order-product-card').forEach((card) => {
        card.addEventListener('click', () => {
            const produto = estadoEncomenda.produtos.find((item) => String(item.id) === card.dataset.productId);
            if (!produto) {
                return;
            }

            if (produtoJaAdicionado(produto.id)) {
                removerProdutoDoPedido(produto.id);
                return;
            }

            adicionarProdutoAoPedido(produto);
        });
    });
}

function adicionarProdutoAoPedido(produto) {
    if (produtoJaAdicionado(produto.id)) {
        return;
    }

    estadoEncomenda.itens.push(produto);
    atualizarPedidoSelecionado();
}

function removerProdutoDoPedido(produtoId) {
    estadoEncomenda.itens = estadoEncomenda.itens.filter((produto) => String(produto.id) !== String(produtoId));
    atualizarPedidoSelecionado();
}

function atualizarPedidoSelecionado() {
    atualizarResumoSelecionado();
    renderizarItensSelecionados();
    sincronizarCamposPedido();
    atualizarCardsSelecionados();
}

function atualizarResumoSelecionado() {
    const summary = document.querySelector('#order-selected-summary');
    if (!summary) {
        return;
    }

    const total = estadoEncomenda.itens.reduce((soma, produto) => soma + Number(produto.preco || 0), 0);

    if (!estadoEncomenda.itens.length) {
        summary.classList.remove('has-product');
        summary.querySelector('strong').textContent = 'Nenhum produto escolhido ainda';
        summary.querySelector('small').textContent = 'Clique em um produto do cardapio para adicionar ao pedido.';
        return;
    }

    summary.classList.add('has-product');
    summary.querySelector('strong').textContent = `${estadoEncomenda.itens.length} item(ns) no pedido`;
    summary.querySelector('small').textContent = `Total estimado: R$ ${total.toFixed(2).replace('.', ',')}`;
}

function renderizarItensSelecionados() {
    const lista = document.querySelector('#order-items-list');
    if (!lista) {
        return;
    }

    if (!estadoEncomenda.itens.length) {
        lista.innerHTML = '';
        return;
    }

    lista.innerHTML = estadoEncomenda.itens.map((produto) => `
        <li>
            <span><strong>${escapeHtml(produto.nome)}</strong><small>${escapeHtml(produto.categoria || 'Cardapio')} - R$ ${Number(produto.preco || 0).toFixed(2).replace('.', ',')}</small></span>
            <button type="button" data-remove-product="${produto.id}" aria-label="Remover ${escapeHtml(produto.nome)}">Remover</button>
        </li>
    `).join('');

    lista.querySelectorAll('[data-remove-product]').forEach((button) => {
        button.addEventListener('click', () => removerProdutoDoPedido(button.dataset.removeProduct));
    });
}

function sincronizarCamposPedido() {
    const categoria = document.querySelector('#categoria');
    const produto = document.querySelector('#produto');
    if (!categoria || !produto) {
        return;
    }

    categoria.value = [...new Set(estadoEncomenda.itens.map((item) => item.categoria || 'Cardapio'))].join(', ');
    produto.value = estadoEncomenda.itens
        .map((item) => `${item.nome} - R$ ${Number(item.preco || 0).toFixed(2).replace('.', ',')}`)
        .join('; ');
}

function atualizarCardsSelecionados() {
    document.querySelectorAll('.order-product-card').forEach((card) => {
        const selecionado = produtoJaAdicionado(card.dataset.productId);
        card.classList.toggle('selected', selecionado);
        const button = card.querySelector('.order-select-button');
        if (button) {
            button.textContent = selecionado ? 'Adicionado' : 'Adicionar';
        }
    });
}

function produtoJaAdicionado(produtoId) {
    return estadoEncomenda.itens.some((produto) => String(produto.id) === String(produtoId));
}

function mostrarErro(message) {
    if (window.Swal) {
        Swal.fire({ icon: 'error', title: 'Erro!', text: message });
    } else {
        alert(message);
    }
}

function imagemProduto(produto) {
    if (produto.imagem) {
        return produto.imagem.startsWith('http') || produto.imagem.startsWith('/')
            ? produto.imagem
            : `/${produto.imagem}`;
    }

    return '/assets/images/bolo-morango.jpg';
}

function gruposDeCategoria(produtos) {
    const grupos = new Map();

    produtos.forEach((produto) => {
        const key = chaveCategoria(produto.categoria);
        if (!grupos.has(key)) {
            grupos.set(key, {
                key,
                name: nomeCategoria(produto.categoria),
            });
        }
    });

    return [...grupos.values()];
}

function chaveCategoria(categoria) {
    return String(categoria || 'Outros')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/\s+/g, ' ');
}

function nomeCategoria(categoria) {
    const nome = String(categoria || 'Outros').trim().replace(/\s+/g, ' ');
    if (!nome) return 'Outros';
    return nome.charAt(0).toUpperCase() + nome.slice(1);
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[char]));
}
