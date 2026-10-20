@php
    $adminName = $adminName ?? 'Admin';
    $metrics = $metrics ?? [
        ['label' => 'Pedidos Realizados', 'value' => 48, 'change' => '+12% em relacao ao mes passado', 'icon' => 'bag'],
        ['label' => 'Mensagens no Contato', 'value' => 23, 'change' => '+8% em relacao ao mes passado', 'icon' => 'message'],
        ['label' => 'Encomendas Agendadas', 'value' => 12, 'change' => '+20% em relacao ao mes passado', 'icon' => 'calendar'],
        ['label' => 'Clientes Ativos', 'value' => 156, 'change' => '+15% em relacao ao mes passado', 'icon' => 'star'],
    ];
    $endereco = $endereco ?? [
        'linha1' => 'Rua da Matriz, 123',
        'linha2' => 'Centro - Tatui, SP',
        'cep' => 'CEP: 18270-000',
    ];
    $horarioFuncionamento = $horarioFuncionamento ?? [
        'dias' => 'Segunda a Sabado',
        'horario' => 'Das 14:30 as 23:30',
        'observacao' => 'Nossa loja funciona todos os dias para melhor atender voce!',
    ];
    $horariosAgenda = $horariosAgenda ?? [
        ['dia' => 'Segunda', 'horario' => '14:30 - 20:00'],
        ['dia' => 'Terca', 'horario' => '14:30 - 20:00'],
        ['dia' => 'Quarta', 'horario' => '14:30 - 20:00'],
        ['dia' => 'Quinta', 'horario' => '14:30 - 20:00'],
        ['dia' => 'Sexta', 'horario' => '14:30 - 21:00'],
        ['dia' => 'Sabado', 'horario' => '14:30 - 21:00'],
    ];
    $pedidosRecentes = $pedidosRecentes ?? [
        ['id' => '#1048', 'cliente' => 'Ana Souza', 'produtos' => '1x Bolo de Morango', 'total' => 'R$ 89,90', 'status' => 'Em preparo', 'data' => '21/06 - 14:32'],
        ['id' => '#1047', 'cliente' => 'Lucas Ferreira', 'produtos' => '2x Brigadeiro no copo', 'total' => 'R$ 32,00', 'status' => 'Entregue', 'data' => '21/06 - 13:47'],
        ['id' => '#1046', 'cliente' => 'Juliana Costa', 'produtos' => '1x Torta de Banana', 'total' => 'R$ 75,00', 'status' => 'Em preparo', 'data' => '21/06 - 12:20'],
        ['id' => '#1045', 'cliente' => 'Rafael Martins', 'produtos' => '4x Doces variados', 'total' => 'R$ 48,00', 'status' => 'Entregue', 'data' => '20/06 - 11:10'],
        ['id' => '#1044', 'cliente' => 'Camila Alves', 'produtos' => '1x Bolo Personalizado', 'total' => 'R$ 120,00', 'status' => 'Em preparo', 'data' => '20/06 - 10:05'],
    ];
    $mensagensContato = $mensagensContato ?? [
        ['nome' => 'Mariana Silva', 'mensagem' => 'Gostaria de saber se voces fazem bolos...', 'data' => 'Hoje, 10:24'],
        ['nome' => 'Carlos Henrique', 'mensagem' => 'Quais sao os sabores de brigadeiro no copo?', 'data' => 'Ontem, 17:32'],
        ['nome' => 'Fernanda Lima', 'mensagem' => 'Tem como agendar uma encomenda para o dia 28?', 'data' => 'Ontem, 14:18'],
        ['nome' => 'Beatriz Alves', 'mensagem' => 'Qual o valor do delivery para o bairro Centro?', 'data' => '20/06, 19:45'],
        ['nome' => 'Paulo Santos', 'mensagem' => 'Voces aceitam cartao de credito?', 'data' => '20/06, 16:20'],
    ];
    $heroIcon = function (string $name, string $class = '') {
        $icons = [
            'home' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.592 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />',
            'cube' => '<path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25m0-9L3 7.5m9 5.25v9M3 7.5v9l9 5.25" />',
            'shopping-bag' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.452 1.243-1.121 1.243H4.252a1.125 1.125 0 0 1-1.12-1.243l1.262-12A1.125 1.125 0 0 1 5.514 7.5h12.972c.576 0 1.059.435 1.12 1.007Z" />',
            'chat-bubble' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.199 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />',
            'cog' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 .12 1.334l-1.1 1.594a1.125 1.125 0 0 0 .223 1.493l1.836 1.414c.43.331 1.04.307 1.44-.06l1.295-1.19a7.473 7.473 0 0 0 1.436.59l.49 1.852c.132.5.585.848 1.102.848h2.316c.517 0 .97-.348 1.102-.848l.49-1.852a7.473 7.473 0 0 0 1.436-.59l1.295 1.19c.4.367 1.01.391 1.44.06l1.836-1.414c.432-.333.526-.95.223-1.493l-1.1-1.594A7.5 7.5 0 1 0 4.5 12Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
            'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25m10.5-2.25v2.25M3.75 8.25h16.5M5.25 5.25h13.5c.828 0 1.5.672 1.5 1.5v12A1.5 1.5 0 0 1 18.75 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5v-12c0-.828.672-1.5 1.5-1.5Z" />',
            'star' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.563.563 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />',
            'bell' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022 23.848 23.848 0 0 0 5.455 1.31m5.714 0a3 3 0 0 1-5.714 0" />',
            'user' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
            'chevron-down' => '<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />',
            'map-pin' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />',
            'clock' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
            'clipboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4.5h6M9.75 4.5h4.5A2.25 2.25 0 0 1 16.5 6.75V7.5h1.125A2.625 2.625 0 0 1 20.25 10.125v8.25A2.625 2.625 0 0 1 17.625 21H6.375A2.625 2.625 0 0 1 3.75 18.375v-8.25A2.625 2.625 0 0 1 6.375 7.5H7.5v-.75A2.25 2.25 0 0 1 9.75 4.5Z" />',
        ];
        $svgClass = trim('hero-icon ' . $class);
        return '<svg class="' . e($svgClass) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">' . ($icons[$name] ?? $icons['star']) . '</svg>';
    };
@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | San Doces</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&family=Sacramento&display=swap" rel="stylesheet">
    @vite(['resources/css/san-doces.css', 'resources/js/san-doces.js', 'resources/js/dashboard.js'])
</head>

<body class="dashboard-page">
    <aside class="dash-sidebar">
        <a class="dash-logo" href="{{ route('san-doces.home') }}">SAN<br>DOCE<small>DOCERIA</small></a>
        <nav class="dash-menu" aria-label="Menu administrativo">
            <a class="active" href="{{ route('san-doces.dashboard') }}"><span class="dash-ico">{!! $heroIcon('home') !!}</span>Inicio</a>
            <a href="#produtos-admin" data-dashboard-link="produtos"><span class="dash-ico">{!! $heroIcon('cube') !!}</span>Produtos</a>
            <a href="#pedidos-admin" data-dashboard-link="pedidos"><span class="dash-ico">{!! $heroIcon('shopping-bag') !!}</span>Pedidos</a>
            <a href="#mensagens-admin" data-dashboard-link="mensagens"><span class="dash-ico">{!! $heroIcon('chat-bubble') !!}</span>Mensagens</a>
        </nav>
        <div class="dash-sidebar-decoration" aria-hidden="true">
            <div class="dash-sidebar-note">Doce e sempre<br>uma boa ideia!</div>
        </div>
    </aside>

    <div class="dash-shell">
        <header class="dash-topbar">
            <div>
                <h1>Ola, {{ $adminName }}!</h1>
                <p>Seja bem-vindo(a) ao painel da San Doce.</p>
            </div>
            <div class="dash-admin">
                <button class="dash-alert" type="button" aria-label="Notificacoes">{!! $heroIcon('bell') !!}<span id="dash-alert-count">0</span></button>
                <span class="dash-avatar">{!! $heroIcon('user') !!}</span>
                <strong id="dash-admin-name">Administrador</strong>
                <span class="dash-chevron">{!! $heroIcon('chevron-down') !!}</span>
                <button id="dash-logout" class="dash-logout" type="button">Sair</button>
            </div>
        </header>

        <main class="dash-main">
            <section class="dash-metrics" aria-label="Resumo">
                @foreach ($metrics as $metric)
                    <article class="dash-metric">
                        <span class="metric-icon">{!! $heroIcon(match ($metric['icon'] ?? 'star') {
                            'bag' => 'shopping-bag',
                            'message' => 'chat-bubble',
                            'calendar' => 'calendar',
                            default => 'star',
                        }) !!}</span>
                        <div>
                            <h2>{{ $metric['label'] }}</h2>
                            <strong>{{ $metric['value'] }}</strong>
                            <p>{{ $metric['change'] }}</p>
                        </div>
                    </article>
                @endforeach
            </section>

            <section class="dash-grid">
                <article class="dash-panel dash-address">
                    <div class="panel-title">
                        <h2><span class="dash-pin">{!! $heroIcon('map-pin') !!}</span>Endereco da Loja</h2>
                        <button id="dash-toggle-address" class="dash-link-button" type="button">Editar</button>
                    </div>
                    <div class="address-body">
                        <div class="address-card">
                            <p id="dash-address-line-1">{{ $endereco['linha1'] }}</p>
                            <p id="dash-address-line-2">{{ $endereco['linha2'] }}</p>
                            <p id="dash-address-cep">{{ $endereco['cep'] }}</p>
                        </div>
                        <div class="mini-map">
                            <span class="map-pin">San Doce Doceria</span>
                            <strong id="dash-address-city">Tatui</strong>
                        </div>
                    </div>
                    <form id="dash-address-form" class="dash-inline-form is-hidden">
                        <div class="dash-address-form-grid">
                            <label>CEP
                                <input id="dash-address-cep-input" name="cep" inputmode="numeric" maxlength="9" required placeholder="18270-000">
                            </label>
                            <label>Numero
                                <input id="dash-address-number" name="numero" required placeholder="123">
                            </label>
                            <label>Rua
                                <input id="dash-address-street" name="street" required placeholder="Rua da Matriz">
                            </label>
                            <label>Bairro
                                <input id="dash-address-neighborhood" name="neighborhood" required placeholder="Centro">
                            </label>
                            <label>Cidade
                                <input id="dash-address-city-input" name="city" required placeholder="Tatui">
                            </label>
                            <label>UF
                                <input id="dash-address-state" name="state" maxlength="2" required placeholder="SP">
                            </label>
                        </div>
                        <small>Digite o CEP para preencher automaticamente pela BrasilAPI.</small>
                        <div class="dash-inline-actions">
                            <button id="dash-search-address" class="dash-action muted" type="button">Buscar CEP</button>
                            <button class="dash-action primary" type="submit">Salvar endereco</button>
                            <button class="dash-action muted" type="button" data-close-panel="dash-address-form">Cancelar</button>
                        </div>
                    </form>
                    <p class="panel-note">Este endereco sera utilizado para o calculo automatico do frete no momento do pedido.</p>
                </article>

                <article class="dash-panel">
                    <div class="panel-title">
                        <h2><span class="dash-clock">{!! $heroIcon('clock') !!}</span>Horario de Funcionamento</h2>
                        <button id="dash-toggle-hours" class="dash-link-button" type="button">Editar</button>
                    </div>
                    <div class="hours-card">
                        <strong>{{ $horarioFuncionamento['dias'] }}</strong>
                        <span>{{ $horarioFuncionamento['horario'] }}</span>
                    </div>
                    <p class="hours-note">{{ $horarioFuncionamento['observacao'] }}</p>
                    <form id="dash-hours-form" class="dash-inline-form is-hidden">
                        <label>Dias
                            <input id="dash-hours-days" name="dias" required value="{{ $horarioFuncionamento['dias'] }}">
                        </label>
                        <label>Horario
                            <input id="dash-hours-range" name="horario" required value="{{ $horarioFuncionamento['horario'] }}">
                        </label>
                        <label>Observacao
                            <textarea id="dash-hours-note" name="observacao">{{ $horarioFuncionamento['observacao'] }}</textarea>
                        </label>
                        <div class="dash-inline-actions">
                            <button class="dash-action primary" type="submit">Salvar horario</button>
                            <button class="dash-action muted" type="button" data-close-panel="dash-hours-form">Cancelar</button>
                        </div>
                    </form>
                </article>

                <article class="dash-panel dash-schedule">
                    <div class="panel-title">
                        <h2><span class="dash-calendar">{!! $heroIcon('calendar') !!}</span>Horarios Disponiveis para Agendar Encomendas</h2>
                        <button id="dash-toggle-schedule" class="dash-link-button" type="button">Editar</button>
                    </div>
                    <div class="schedule-list">
                        @foreach ($horariosAgenda as $item)
                            <div><span>{{ $item['dia'] }}</span><strong>{{ $item['horario'] }}</strong></div>
                        @endforeach
                    </div>
                    <form id="dash-schedule-form" class="dash-inline-form is-hidden">
                        <label>Horarios de encomenda
                            <textarea id="dash-schedule-lines" name="horarios" rows="6">@foreach ($horariosAgenda as $item){{ $item['dia'] }}: {{ $item['horario'] }}
@endforeach</textarea>
                        </label>
                        <small>Use uma linha por dia. Ex: Segunda: 14:30 - 20:00</small>
                        <div class="dash-inline-actions">
                            <button class="dash-action primary" type="submit">Salvar agenda</button>
                            <button class="dash-action muted" type="button" data-close-panel="dash-schedule-form">Cancelar</button>
                        </div>
                    </form>
                </article>
            </section>

            <section id="produtos-admin" class="dash-manage">
                <article class="dash-panel">
                    <div class="panel-title">
                        <h2><span class="dash-ico">{!! $heroIcon('cube') !!}</span>Cadastrar Produto</h2>
                    </div>
                    <form id="cadastro-produto-form" class="dash-product-form" enctype="multipart/form-data">
                        <div class="dash-form-grid">
                            <label>Nome<input id="produto_nome" name="nome" required placeholder="Ex: Bolo de Morango"></label>
                            <label>Categoria<input id="produto_categoria" name="categoria" required placeholder="Ex: Bolos"></label>
                            <label>Preco<input id="produto_preco" name="preco" type="number" min="0" step="0.01" required placeholder="89.90"></label>
                            <label class="dash-image-field">Arquivo da imagem
                                <input id="produto_imagem" name="imagem_upload" type="file" accept="image/jpeg,image/png,image/webp">
                                <span>JPG, PNG ou WEBP, ate 5 MB</span>
                            </label>
                            <label>URL da imagem
                                <input id="produto_imagem_url" name="imagem" type="url" maxlength="255" placeholder="https://site.com/imagem.jpg">
                            </label>
                            <label>Sobre<input id="produto_sobre" name="sobre" required placeholder="Resumo curto do produto"></label>
                            <label>Descricao<textarea id="produto_descricao" name="descricao" required placeholder="Descricao completa"></textarea></label>
                        </div>
                        <div id="produto-imagem-colar" class="dash-paste-zone" tabindex="0">
                            <strong>Colar imagem</strong>
                            <span>Copie uma imagem ou uma URL e pressione Ctrl+V aqui.</span>
                        </div>
                        <div id="produto-imagem-preview" class="dash-image-preview is-hidden">
                            <img alt="Previa da imagem selecionada">
                            <button type="button" aria-label="Remover imagem selecionada" title="Remover imagem">&times;</button>
                        </div>
                        <button class="dash-action primary" type="submit">Salvar produto</button>
                    </form>
                </article>

                <article class="dash-panel">
                    <div class="panel-title">
                        <h2><span class="dash-ico">{!! $heroIcon('cube') !!}</span>Produtos Cadastrados</h2>
                        <button id="dash-refresh-products" class="dash-link-button" type="button">Atualizar</button>
                    </div>
                    <div id="dash-products-list" class="dash-products-list">
                        <p class="dash-empty">Carregando produtos...</p>
                    </div>
                </article>
            </section>

            <section id="pedidos-admin" class="dash-bottom">
                <article class="dash-panel">
                    <div class="panel-title">
                        <h2><span class="dash-orders">{!! $heroIcon('clipboard') !!}</span>Pedidos Recentes</h2>
                        <button id="dash-refresh-orders" class="dash-link-button" type="button">Atualizar</button>
                    </div>
                    <div class="orders-table">
                        <div class="orders-head">
                            <span>#</span><span>Cliente</span><span>Produto(s)</span><span>Total</span><span>Status</span><span>Data</span>
                        </div>
                        @foreach ($pedidosRecentes as $pedido)
                            <div class="orders-row">
                                <span>{{ $pedido['id'] }}</span>
                                <span>{{ $pedido['cliente'] }}</span>
                                <span>{{ $pedido['produtos'] }}</span>
                                <span>{{ $pedido['total'] }}</span>
                                <span><b class="{{ $pedido['status'] === 'Entregue' ? 'done' : 'prep' }}">{{ $pedido['status'] }}</b></span>
                                <span>{{ $pedido['data'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article id="mensagens-admin" class="dash-panel">
                    <div class="panel-title">
                        <h2><span class="dash-chat">{!! $heroIcon('chat-bubble') !!}</span>Mensagens do Contato</h2>
                        <a href="#">Ver todas</a>
                    </div>
                    <div class="message-list">
                        @foreach ($mensagensContato as $mensagem)
                            <a class="message-item" href="#">
                                <span>{{ mb_substr($mensagem['nome'], 0, 1) }}</span>
                                <div>
                                    <strong>{{ $mensagem['nome'] }}</strong>
                                    <p>{{ $mensagem['mensagem'] }}</p>
                                </div>
                                <time>{{ $mensagem['data'] }}</time>
                            </a>
                        @endforeach
                    </div>
                </article>
            </section>

           
        </main>

        <footer class="dash-footer">
            <a class="dash-logo small" href="{{ route('san-doces.home') }}">SAN<br>DOCE<small>DOCERIA</small></a>
            <p>Obrigada por fazer parte da nossa historia!</p>
            <div>
                <span>@sandoce_confeitaria</span>
                <span>(15) 99144-4740</span>
                <span>Tatui - SP</span>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
