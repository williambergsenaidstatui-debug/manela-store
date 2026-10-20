document.addEventListener('DOMContentLoaded', () => {
    if (!document.body.classList.contains('dashboard-page')) {
        return;
    }

    const token = sessionStorage.getItem('tif_token');
    if (!token) {
        window.location.href = '/login-dashboard';
        return;
    }

    const api = criarApi(token);

    inicializarNavegacao();
    inicializarLogout(api);
    inicializarEnderecoLoja();
    inicializarCadastroProduto(api);
    inicializarEdicaoHorarios(api);
    document.querySelector('#dash-refresh-products')?.addEventListener('click', () => carregarDashboard(api));
    document.querySelector('#dash-refresh-orders')?.addEventListener('click', () => carregarDashboard(api));

    carregarUsuario(api);
    carregarDashboard(api);
});

function criarApi(token) {
    return async function api(url, options = {}) {
        const isFormData = options.body instanceof FormData;
        const response = await fetch(url, {
            ...options,
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${token}`,
                ...(!isFormData ? { 'Content-Type': 'application/json' } : {}),
                ...(options.headers || {}),
            },
        });

        if (response.status === 401 || response.status === 403) {
            sessionStorage.removeItem('tif_token');
            sessionStorage.removeItem('san_doces_usuario');
            window.location.href = '/login-dashboard';
            throw new Error('Sessao expirada.');
        }

        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw new Error(data.mensagem || data.message || 'Nao foi possivel concluir a acao.');
        }

        return data;
    };
}

function inicializarNavegacao() {
    document.querySelectorAll('[data-dashboard-link]').forEach((link) => {
        link.addEventListener('click', () => {
            document.querySelectorAll('.dash-menu a').forEach((item) => item.classList.remove('active'));
            link.classList.add('active');
        });
    });
}

function inicializarLogout(api) {
    document.querySelector('#dash-logout')?.addEventListener('click', async () => {
        try {
            await api('/api/logout', { method: 'POST' });
        } catch (error) {
            console.warn(error.message);
        } finally {
            sessionStorage.removeItem('tif_token');
            sessionStorage.removeItem('san_doces_usuario');
            window.location.href = '/login-dashboard';
        }
    });
}

function inicializarEnderecoLoja() {
    const form = document.querySelector('#dash-address-form');
    const toggle = document.querySelector('#dash-toggle-address');
    const cepInput = document.querySelector('#dash-address-cep-input');
    const numeroInput = document.querySelector('#dash-address-number');
    const streetInput = document.querySelector('#dash-address-street');
    const neighborhoodInput = document.querySelector('#dash-address-neighborhood');
    const cityInput = document.querySelector('#dash-address-city-input');
    const stateInput = document.querySelector('#dash-address-state');
    const searchButton = document.querySelector('#dash-search-address');

    if (!form || !cepInput) {
        return;
    }

    const enderecoSalvo = carregarEnderecoSalvo();
    if (enderecoSalvo) {
        preencherFormularioEndereco(enderecoSalvo);
        atualizarEnderecoLoja(enderecoSalvo);
    }

    toggle?.addEventListener('click', () => {
        form.classList.toggle('is-hidden');
    });

    cepInput.addEventListener('input', () => {
        cepInput.value = formatarCep(cepInput.value);
    });

    searchButton?.addEventListener('click', async () => {
        await buscarCepBrasilApi();
    });

    cepInput.addEventListener('blur', async () => {
        if (somenteDigitos(cepInput.value).length === 8) {
            await buscarCepBrasilApi(false);
        }
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const endereco = {
            cep: cepInput.value.trim(),
            numero: numeroInput?.value.trim() || '',
            street: streetInput?.value.trim() || '',
            neighborhood: neighborhoodInput?.value.trim() || '',
            city: cityInput?.value.trim() || '',
            state: stateInput?.value.trim().toUpperCase() || '',
        };

        if (!endereco.cep || !endereco.numero || !endereco.street || !endereco.neighborhood || !endereco.city || !endereco.state) {
            avisar('error', 'Endereco incompleto', 'Preencha CEP, numero, rua, bairro, cidade e UF.');
            return;
        }

        localStorage.setItem('san_doces_endereco_loja', JSON.stringify(endereco));
        atualizarEnderecoLoja(endereco);
        form.classList.add('is-hidden');
        avisar('success', 'Endereco salvo!', 'O endereco da loja foi atualizado no dashboard.');
    });

    async function buscarCepBrasilApi(mostrarAviso = true) {
        const cep = somenteDigitos(cepInput.value);

        if (cep.length !== 8) {
            avisar('error', 'CEP invalido', 'Informe um CEP com 8 numeros.');
            return;
        }

        const textoOriginal = searchButton?.textContent;
        if (searchButton) {
            searchButton.disabled = true;
            searchButton.textContent = 'Buscando...';
        }

        try {
            const response = await fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`, {
                headers: { Accept: 'application/json' },
            });
            const dados = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(dados.message || 'Nao foi possivel encontrar este CEP.');
            }

            streetInput.value = dados.street || '';
            neighborhoodInput.value = dados.neighborhood || '';
            cityInput.value = dados.city || '';
            stateInput.value = dados.state || '';

            if (mostrarAviso) {
                avisar('success', 'CEP encontrado!', 'Confira o numero e salve o endereco da loja.');
            }
        } catch (error) {
            avisar('error', 'Erro ao buscar CEP', error.message);
        } finally {
            if (searchButton) {
                searchButton.disabled = false;
                searchButton.textContent = textoOriginal;
            }
        }
    }
}

function inicializarCadastroProduto(api) {
    const form = document.querySelector('#cadastro-produto-form');
    let productIdInput = document.querySelector('#produto_id');
    const formTitle = document.querySelector('#dash-product-form-title');
    const submitButton = document.querySelector('#dash-product-submit') || form?.querySelector('button[type= submit]');
    let cancelButton = document.querySelector('#dash-product-cancel');
    const imageInput = document.querySelector('#produto_imagem');
    const imageUrlInput = document.querySelector('#produto_imagem_url');
    const pasteZone = document.querySelector('#produto-imagem-colar');
    const preview = document.querySelector('#produto-imagem-preview');
    let previewUrl = null;

    if (!form) {
        return;
    }

    if (!productIdInput) {
        productIdInput = document.createElement('input');
        productIdInput.type = 'hidden';
        productIdInput.id = 'produto_id';
        form.prepend(productIdInput);
    }

    if (!cancelButton) {
        cancelButton = document.createElement('button');
        cancelButton.id = 'dash-product-cancel';
        cancelButton.className = 'dash-action muted is-hidden';
        cancelButton.type = 'button';
        cancelButton.textContent = 'Cancelar edicao';
        submitButton?.insertAdjacentElement('afterend', cancelButton);
    }

    const limparPreview = () => {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
        preview?.classList.add('is-hidden');
        preview?.querySelector('img')?.removeAttribute('src');
    };

    const mostrarPreview = (src) => {
        if (!preview || !src) {
            return;
        }
        preview.querySelector('img').src = src;
        preview.classList.remove('is-hidden');
    };

    const limparModoEdicao = () => {
        form.reset();
        productIdInput.value = '';
        form.dataset.mode = 'create';
        limparPreview();
        if (formTitle) {
            formTitle.textContent = 'Cadastrar Produto';
        }
        if (submitButton) {
            submitButton.textContent = 'Salvar produto';
        }
        cancelButton?.classList.add('is-hidden');
    };

    const preencherModoEdicao = (produto) => {
        productIdInput.value = produto.id || '';
        form.dataset.mode = 'edit';
        form.querySelector('#produto_nome').value = produto.nome || '';
        form.querySelector('#produto_categoria').value = produto.categoria || '';
        form.querySelector('#produto_preco').value = produto.preco ?? '';
        form.querySelector('#produto_sobre').value = produto.sobre || '';
        form.querySelector('#produto_descricao').value = produto.descricao || '';
        imageInput.value = '';
        imageUrlInput.value = /^https?:\/\//i.test(produto.imagem || '') ? produto.imagem : '';
        limparPreview();
        mostrarPreview(imagemProduto(produto));
        if (formTitle) {
            formTitle.textContent = 'Editar Produto';
        }
        if (submitButton) {
            submitButton.textContent = 'Salvar alteracoes';
        }
        cancelButton?.classList.remove('is-hidden');
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        form.querySelector('#produto_nome')?.focus({ preventScroll: true });
    };

    window.iniciarEdicaoProduto = preencherModoEdicao;

    imageInput?.addEventListener('change', () => {
        limparPreview();
        const arquivo = imageInput.files?.[0];
        if (!arquivo || !preview) {
            return;
        }
        imageUrlInput.value = '';
        previewUrl = URL.createObjectURL(arquivo);
        mostrarPreview(previewUrl);
    });

    imageUrlInput?.addEventListener('input', () => {
        limparPreview();
        imageInput.value = '';
        const url = imageUrlInput.value.trim();
        if (/^https?:\/\//i.test(url)) {
            mostrarPreview(url);
        }
    });

    form.addEventListener('paste', (event) => {
        const imageItem = [...(event.clipboardData?.items || [])]
            .find((item) => item.kind === 'file' && item.type.startsWith('image/'));

        if (imageItem) {
            const file = imageItem.getAsFile();
            if (!file) return;
            event.preventDefault();
            const transfer = new DataTransfer();
            transfer.items.add(file);
            imageInput.files = transfer.files;
            imageInput.dispatchEvent(new Event('change'));
            pasteZone?.focus();
            return;
        }

        if (event.target === imageUrlInput) return;
        const text = event.clipboardData?.getData('text')?.trim() || '';
        if (/^https?:\/\//i.test(text)) {
            event.preventDefault();
            imageUrlInput.value = text;
            imageUrlInput.dispatchEvent(new Event('input'));
            pasteZone?.focus();
        }
    });

    preview?.querySelector('button')?.addEventListener('click', () => {
        imageInput.value = '';
        imageUrlInput.value = '';
        limparPreview();
    });

    cancelButton?.addEventListener('click', limparModoEdicao);

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const dados = new FormData(form);
        dados.delete('id');
        const editandoId = productIdInput.value;
        const editando = form.dataset.mode === 'edit' && editandoId;

        if (editando && !imageInput.files?.length && !imageUrlInput.value.trim()) {
            dados.delete('imagem');
        }
        if (editando) {
            dados.append('_method', 'PUT');
        }

        try {
            await api(editando ? '/api/atualizar_doces/' + editandoId : '/api/cadastro_doces', {
                method: 'POST',
                body: dados,
            });
            limparModoEdicao();
            await carregarDashboard(api);
            avisar('success', editando ? 'Produto atualizado!' : 'Produto cadastrado!', editando ? 'As alteracoes ja aparecem na lista.' : 'O item ja aparece na lista do dashboard.');
        } catch (error) {
            avisar('error', editando ? 'Erro ao editar' : 'Erro ao cadastrar', error.message);
        }
    });
}
async function carregarUsuario(api) {
    try {
        const usuario = await api('/api/user');
        document.querySelector('#dash-admin-name').textContent = usuario.nome || 'Administrador';
        const titulo = document.querySelector('.dash-topbar h1');
        if (titulo) {
            titulo.textContent = `Ola, ${usuario.nome || 'Admin'}!`;
        }
    } catch (error) {
        console.warn(error.message);
    }
}

async function carregarDashboard(api) {
    try {
        const dados = await api('/api/dashboard/resumo');
        atualizarMetricas(dados.metrics || []);
        atualizarPedidos(dados.pedidosRecentes || [], api);
        atualizarProdutos(dados.produtos || [], api);
        atualizarHorarioFuncionamento(dados.horarioFuncionamento);
        atualizarHorariosAgenda(dados.horariosAgenda || []);
        atualizarMensagens(dados.mensagensContato || []);
        const pendentes = (dados.pedidosRecentes || []).filter((pedido) => pedido.status !== 'Entregue').length;
        document.querySelector('#dash-alert-count').textContent = pendentes;
    } catch (error) {
        avisar('error', 'Erro no dashboard', error.message);
    }
}

function inicializarEdicaoHorarios(api) {
    const hoursForm = document.querySelector('#dash-hours-form');
    const scheduleForm = document.querySelector('#dash-schedule-form');

    document.querySelector('#dash-toggle-hours')?.addEventListener('click', () => {
        hoursForm?.classList.toggle('is-hidden');
    });

    document.querySelector('#dash-toggle-schedule')?.addEventListener('click', () => {
        scheduleForm?.classList.toggle('is-hidden');
    });

    document.querySelectorAll('[data-close-panel]').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelector(`#${button.dataset.closePanel}`)?.classList.add('is-hidden');
        });
    });

    hoursForm?.addEventListener('submit', async (event) => {
        event.preventDefault();

        const dias = document.querySelector('#dash-hours-days')?.value.trim();
        const horario = document.querySelector('#dash-hours-range')?.value.trim();
        const observacao = document.querySelector('#dash-hours-note')?.value.trim();

        if (!dias || !horario) {
            avisar('error', 'Horario incompleto', 'Informe os dias e o horario de funcionamento.');
            return;
        }

        try {
            await api('/api/horario_funcionamento', {
                method: 'PUT',
                body: JSON.stringify({ dias, horario, observacao }),
            });
            await carregarDashboard(api);
            hoursForm.classList.add('is-hidden');
            avisar('success', 'Horario salvo!', 'O dashboard foi atualizado.');
        } catch (error) {
            avisar('error', 'Erro ao salvar horario', error.message);
        }
    });

    scheduleForm?.addEventListener('submit', async (event) => {
        event.preventDefault();

        const texto = document.querySelector('#dash-schedule-lines')?.value || '';

        const horarios = texto.split('\n')
            .map((linha) => linha.trim())
            .filter(Boolean)
            .map((linha) => {
                const [dia, ...resto] = linha.split(':');
                return { dia: dia.trim(), horario: resto.join(':').trim() };
            })
            .filter((item) => item.dia && item.horario);

        if (!horarios.length) {
            avisar('error', 'Formato invalido', 'Use uma linha por horario. Ex: Segunda: 14:30 - 20:00');
            return;
        }

        try {
            await api('/api/horarios_encomenda', {
                method: 'PUT',
                body: JSON.stringify({ horarios }),
            });
            await carregarDashboard(api);
            scheduleForm.classList.add('is-hidden');
            avisar('success', 'Agenda salva!', 'Os horarios para encomenda foram atualizados.');
        } catch (error) {
            avisar('error', 'Erro ao salvar horarios', error.message);
        }
    });
}

function atualizarMetricas(metrics) {
    const container = document.querySelector('.dash-metrics');
    if (!container || !metrics.length) {
        return;
    }

    container.innerHTML = metrics.map((metric) => `
        <article class="dash-metric">
            <span class="metric-icon ${metric.icon || 'star'}"></span>
            <div>
                <h2>${escapeHtml(metric.label)}</h2>
                <strong>${escapeHtml(String(metric.value))}</strong>
                <p>${escapeHtml(metric.change || '')}</p>
            </div>
        </article>
    `).join('');
}

function atualizarPedidos(pedidos, api) {
    const table = document.querySelector('.orders-table');
    if (!table) {
        return;
    }

    if (!pedidos.length) {
        table.innerHTML = '<p class="dash-empty">Nenhum pedido recebido ainda.</p>';
        return;
    }

    table.innerHTML = `
        <div class="orders-head">
            <span>#</span><span>Cliente</span><span>Pedido</span><span>Total</span><span>Status</span><span>Data</span>
        </div>
        ${pedidos.map((pedido) => `
            <div class="orders-row">
                <span>${escapeHtml(pedido.id)}</span>
                <span>${escapeHtml(pedido.cliente)}</span>
                <span>${escapeHtml(pedido.produtos)}</span>
                <span>${escapeHtml(pedido.total)}</span>
                <span>
                    <select class="dash-status ${classeStatus(pedido.status)}" data-pedido-id="${pedido.raw_id}" data-order-source="${escapeHtml(pedido.source || 'pedido')}">
                        ${['Em preparo', 'Entregue', 'Cancelado'].map((status) => `
                            <option value="${status}" ${status === pedido.status ? 'selected' : ''}>${status}</option>
                        `).join('')}
                    </select>
                </span>
                <span class="orders-date-actions">
                    ${escapeHtml(pedido.data || '')}
                    <button class="dash-mini-button" type="button" data-order-details="${escapeHtml((pedido.source || 'pedido') + '-' + pedido.raw_id)}">Detalhes</button>
                </span>
            </div>
            <div class="orders-detail is-hidden" data-order-detail-row="${escapeHtml((pedido.source || 'pedido') + '-' + pedido.raw_id)}">
                <strong>Contato</strong>
                <span>${escapeHtml(pedido.whatsapp || 'Sem WhatsApp')}</span>
                <span>${escapeHtml(pedido.email || 'Sem e-mail')}</span>
                <strong>Observacao</strong>
                <p>${escapeHtml(pedido.observacao || 'Nenhuma observacao enviada.')}</p>
            </div>
        `).join('')}
    `;

    table.querySelectorAll('[data-order-details]').forEach((button) => {
        button.addEventListener('click', () => {
            const details = table.querySelector(`[data-order-detail-row="${button.dataset.orderDetails}"]`);
            details?.classList.toggle('is-hidden');
        });
    });

    table.querySelectorAll('.dash-status').forEach((select) => {
        select.addEventListener('change', async () => {
            try {
                const endpoint = select.dataset.orderSource === 'pagamento'
                    ? `/api/pagamentos/pedidos/${select.dataset.pedidoId}/status`
                    : `/api/pedidos/${select.dataset.pedidoId}/status`;
                await api(endpoint, {
                    method: 'PATCH',
                    body: JSON.stringify({ status: select.value }),
                });
                await carregarDashboard(api);
            } catch (error) {
                avisar('error', 'Erro ao atualizar pedido', error.message);
            }
        });
    });
}

function atualizarProdutos(produtos, api) {
    const container = document.querySelector('#dash-products-list');
    if (!container) {
        return;
    }

    container.replaceChildren();

    if (!produtos.length) {
        const empty = document.createElement('p');
        empty.className = 'dash-empty';
        empty.textContent = 'Nenhum produto cadastrado ainda.';
        container.append(empty);
        return;
    }

    produtos.forEach((produto) => {
        const item = document.createElement('article');
        item.className = 'dash-product-item';

        const image = document.createElement('img');
        image.src = imagemProduto(produto);
        image.alt = produto.nome || '';

        const info = document.createElement('div');
        const name = document.createElement('strong');
        name.textContent = produto.nome || '';
        const price = document.createElement('span');
        price.textContent = (produto.categoria || '') + ' - R$ ' + Number(produto.preco || 0).toFixed(2).replace('.', ',');
        const description = document.createElement('p');
        description.textContent = produto.sobre || produto.descricao || '';
        info.append(name, price, description);

        const actions = document.createElement('div');
        actions.className = 'dash-product-actions';

        const editButton = document.createElement('button');
        editButton.className = 'dash-action muted';
        editButton.type = 'button';
        editButton.textContent = 'Editar';
        editButton.addEventListener('click', async () => {
            try {
                const produtoAtualizado = await api('/api/buscar_doces/' + produto.id);
                window.iniciarEdicaoProduto?.(produtoAtualizado);
            } catch (error) {
                avisar('error', 'Erro ao carregar produto', error.message);
            }
        });

        const deleteButton = document.createElement('button');
        deleteButton.className = 'dash-action danger';
        deleteButton.type = 'button';
        deleteButton.textContent = 'Excluir';
        deleteButton.addEventListener('click', async () => {
            const confirmar = window.confirm('Deseja excluir este produto?');
            if (!confirmar) {
                return;
            }

            try {
                await api('/api/excluir_doces/' + produto.id, { method: 'DELETE' });
                await carregarDashboard(api);
            } catch (error) {
                avisar('error', 'Erro ao excluir produto', error.message);
            }
        });

        actions.append(editButton, deleteButton);
        item.append(image, info, actions);
        container.append(item);
    });
}
function atualizarHorarioFuncionamento(horario) {
    if (!horario) {
        return;
    }

    const card = document.querySelector('.hours-card');
    const note = document.querySelector('.hours-note');
    if (card) {
        card.querySelector('strong').textContent = horario.dias || '';
        card.querySelector('span').textContent = horario.horario || '';
    }
    if (note) {
        note.textContent = horario.observacao || '';
    }

    const daysInput = document.querySelector('#dash-hours-days');
    const rangeInput = document.querySelector('#dash-hours-range');
    const noteInput = document.querySelector('#dash-hours-note');
    if (daysInput) {
        daysInput.value = horario.dias || '';
    }
    if (rangeInput) {
        rangeInput.value = horario.horario || '';
    }
    if (noteInput) {
        noteInput.value = horario.observacao || '';
    }
}

function atualizarHorariosAgenda(horarios) {
    const container = document.querySelector('.schedule-list');
    if (!container) {
        return;
    }

    if (!horarios.length) {
        container.innerHTML = '<p class="dash-empty">Nenhum horario disponivel cadastrado.</p>';
        return;
    }

    container.innerHTML = horarios.map((item) => `
        <div><span>${escapeHtml(item.dia)}</span><strong>${escapeHtml(item.horario)}</strong></div>
    `).join('');

    const textarea = document.querySelector('#dash-schedule-lines');
    if (textarea) {
        textarea.value = horarios.map((item) => `${item.dia}: ${item.horario}`).join('\n');
    }
}

function atualizarMensagens(mensagens) {
    const container = document.querySelector('.message-list');
    if (!container) {
        return;
    }

    if (!mensagens.length) {
        container.innerHTML = '<p class="dash-empty">Nenhuma mensagem recebida ainda.</p>';
        return;
    }

    container.innerHTML = mensagens.map((mensagem) => `
        <a class="message-item" href="mailto:${escapeHtml(mensagem.email)}">
            <span>${escapeHtml((mensagem.nome || '?').slice(0, 1))}</span>
            <div>
                <strong>${escapeHtml(mensagem.nome)} - ${escapeHtml(mensagem.assunto)}</strong>
                <p>${escapeHtml(mensagem.mensagem)}${mensagem.whatsapp ? ` - ${escapeHtml(mensagem.whatsapp)}` : ''}</p>
            </div>
            <time>${escapeHtml(mensagem.data || '')}</time>
        </a>
    `).join('');
}

function imagemProduto(produto) {
    if (produto.imagem) {
        return produto.imagem.startsWith('http') || produto.imagem.startsWith('/')
            ? produto.imagem
            : `/${produto.imagem}`;
    }

    return '/assets/images/bolo-morango.jpg';
}

function classeStatus(status) {
    if (status === 'Entregue') {
        return 'done';
    }

    if (status === 'Cancelado') {
        return 'cancel';
    }

    return 'prep';
}

function atualizarEnderecoLoja(endereco) {
    const numero = endereco.numero ? `, ${endereco.numero}` : '';
    const cidadeUf = [endereco.city, endereco.state].filter(Boolean).join(', ');
    const linha1 = `${endereco.street || 'Endereco nao informado'}${numero}`;
    const linha2 = [endereco.neighborhood, cidadeUf].filter(Boolean).join(' - ');

    document.querySelector('#dash-address-line-1').textContent = linha1;
    document.querySelector('#dash-address-line-2').textContent = linha2 || 'Cidade nao informada';
    document.querySelector('#dash-address-cep').textContent = endereco.cep ? `CEP: ${formatarCep(endereco.cep)}` : 'CEP nao informado';
    document.querySelector('#dash-address-city').textContent = endereco.city || 'Tatui';
}

function preencherFormularioEndereco(endereco) {
    const campos = {
        '#dash-address-cep-input': formatarCep(endereco.cep || ''),
        '#dash-address-number': endereco.numero || '',
        '#dash-address-street': endereco.street || '',
        '#dash-address-neighborhood': endereco.neighborhood || '',
        '#dash-address-city-input': endereco.city || '',
        '#dash-address-state': endereco.state || '',
    };

    Object.entries(campos).forEach(([seletor, valor]) => {
        const campo = document.querySelector(seletor);
        if (campo) {
            campo.value = valor;
        }
    });
}

function carregarEnderecoSalvo() {
    try {
        const endereco = JSON.parse(localStorage.getItem('san_doces_endereco_loja') || 'null');
        return endereco && typeof endereco === 'object' ? endereco : null;
    } catch (error) {
        console.warn('Nao foi possivel carregar o endereco salvo.', error);
        return null;
    }
}

function formatarCep(valor) {
    const cep = somenteDigitos(valor).slice(0, 8);
    return cep.length > 5 ? `${cep.slice(0, 5)}-${cep.slice(5)}` : cep;
}

function somenteDigitos(valor) {
    return String(valor || '').replace(/\D/g, '');
}

function avisar(icon, title, text) {
    if (window.Swal) {
        Swal.fire({ icon, title, text });
        return;
    }

    if (icon === 'error') {
        alert(`${title}: ${text}`);
    }
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

