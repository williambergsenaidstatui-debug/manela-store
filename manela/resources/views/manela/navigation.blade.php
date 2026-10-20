@php
    $navigationLinks = [
        'home' => 'Início',
        'produtos' => 'Produtos',
        'sobre-nos' => 'Sobre nós',
        'encomendas' => 'Encomendas',
        'contato' => 'Contato',
    ];
    $orderUrl = request()->routeIs('san-doces.encomendas') ? '#formulario' : route('san-doces.encomendas');
@endphp

<header class="site-header">
    <div class="container topbar">
        <a class="brand" href="{{ route('san-doces.home') }}" aria-label="San Doces — início">SAN<br>DOCES<small>DOCERIA</small></a>
        <nav class="main-nav" aria-label="Navegação principal">
            @foreach ($navigationLinks as $page => $label)
                <a href="{{ route('san-doces.'.$page) }}" @if (request()->routeIs('san-doces.'.$page)) class="active" aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
        <a class="main-btn" href="{{ $orderUrl }}">Fazer pedido <span aria-hidden="true">→</span></a>
        <button class="mobile-menu-toggle" type="button" aria-label="Abrir menu" aria-controls="mobile-navigation" aria-expanded="false" hidden>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
</header>

<dialog id="mobile-navigation" class="mobile-navigation" aria-labelledby="mobile-menu-title">
    <div class="mobile-navigation-heading">
        <div><a class="mobile-navigation-logo" href="{{ route('san-doces.home') }}" aria-label="San Doces — início">SAN<br>DOCES<small>DOCERIA</small></a><h2 id="mobile-menu-title">Explore nossa doceria</h2></div>
        <button class="mobile-menu-close" type="button" aria-label="Fechar menu">×</button>
    </div>
    <nav class="mobile-navigation-links" aria-label="Navegação mobile">
        @foreach ($navigationLinks as $page => $label)
            <a href="{{ route('san-doces.'.$page) }}" @if (request()->routeIs('san-doces.'.$page)) aria-current="page" @endif><span aria-hidden="true">•</span><span>{{ $label }}</span></a>
        @endforeach
    </nav>
    <div class="mobile-navigation-contact">
        <p>Um doce para cada momento</p>
        <a class="mobile-navigation-order" href="{{ $orderUrl }}">Fazer minha encomenda</a>
        <a class="mobile-navigation-whatsapp" href="https://wa.me/5515991444740" target="_blank" rel="noopener">Fale pelo WhatsApp</a>
        <small>Tatuí · SP</small>
    </div>
</dialog>
