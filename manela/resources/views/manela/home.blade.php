<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LM Estilo Personalizado. Roupas que expressam quem você é, com qualidade, conforto e estilo em cada detalhe.">
    <title>LM Estilo Personalizado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/lm-home.css', 'resources/js/lm-home.js'])
</head>
<body id="top">
    <a class="skip-link" href="#conteudo">Pular para o conteúdo</a>
    <div class="wood hero-wrap">
        <header class="site-header container">
            <a class="brand" href="{{ route('san-doces.home') }}" aria-label="LM Estilo Personalizado — início">@include('manela.logo')</a>
            <nav class="main-nav" id="main-navigation" aria-label="Navegação principal">
                <a class="active" href="#inicio" aria-current="page">Início</a>
                <a href="#linhas">Masculino</a><a href="#linhas">Infantil</a><a href="#colecoes">Coleções</a><a href="#personalizacao">Sobre</a>
            </nav>
            <div class="header-actions">
                <button class="icon-button" type="button" data-search-toggle aria-label="Buscar produtos" aria-controls="home-search" aria-expanded="false">@include('manela.icon', ['name' => 'search'])</button>
                <a class="icon-button" href="{{ route('san-doces.login') }}" aria-label="Minha conta">@include('manela.icon', ['name' => 'user'])</a>
                <a class="icon-button" href="{{ route('san-doces.carrinho') }}" aria-label="Carrinho de compras">@include('manela.icon', ['name' => 'bag'])</a>
                <button class="icon-button menu-toggle" type="button" aria-label="Abrir menu" aria-controls="main-navigation" aria-expanded="false">@include('manela.icon', ['name' => 'menu'])</button>
            </div>
        </header>
        <section class="hero container" id="inicio" aria-labelledby="hero-title">
            <div class="hero-copy"><p class="eyebrow">Qualidade · Conforto · Estilo</p><h1 id="hero-title">LM ESTILO<br>PERSONALIZADO</h1><p>Roupas que expressam quem você é.<br>Personalização com qualidade e estilo<br>para o seu dia a dia.</p><a class="button button-outline" href="#colecoes">Ver Coleções <span aria-hidden="true">→</span></a></div>
            <div class="hero-visual hero-shirt" data-shirt-viewer data-model="{{ asset('assets/tshirt.glb') }}" aria-label="Apresentação da camiseta em 3D">
                <div class="shirt-stage" data-shirt-stage aria-label="Camisa em 3D. Arraste para girar e use os botões para controlar a apresentação.">
                    <img class="shirt-fallback" src="{{ asset('assets/images/lm-camiseta-preta.png') }}" alt="Camiseta preta LM com o brasão da marca" width="223" height="312" fetchpriority="high">
                </div>
                <p class="shirt-status" data-shirt-status role="status">Preparando a camiseta em 3D…</p>
                <div class="shirt-toolbar" data-shirt-toolbar hidden>
                    <span class="shirt-hint">Arraste para girar · 360°</span>
                    <button type="button" data-shirt-pause aria-pressed="false">Pausar rotação</button>
                    <button type="button" data-shirt-reset aria-label="Restaurar a posição inicial da camiseta">↺</button>
                    <div class="shirt-colors" role="group" aria-label="Escolher cor da camiseta">
                        <button type="button" class="shirt-swatch" data-shirt-color="#000000" style="--shirt-swatch:#000000" aria-label="Preta" aria-pressed="true"></button>
                        <button type="button" class="shirt-swatch" data-shirt-color="#eae8e2" style="--shirt-swatch:#eae8e2" aria-label="Branca" aria-pressed="false"></button>
                        <button type="button" class="shirt-swatch" data-shirt-color="#04194a" style="--shirt-swatch:#04194a" aria-label="Azul-marinho" aria-pressed="false"></button>
                        <button type="button" class="shirt-swatch" data-shirt-color="#a05d33" style="--shirt-swatch:#a05d33" aria-label="Caramelo" aria-pressed="false"></button>
                        <button type="button" class="shirt-swatch" data-shirt-color="#b78232" style="--shirt-swatch:#b78232" aria-label="Mostarda" aria-pressed="false"></button>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <main id="conteudo">
    <section class="products-section container section-space" id="produtos" aria-labelledby="products-title">
        <div class="section-heading"><div><p class="eyebrow">Nossos produtos</p><h2 id="products-title">Qualidade em cada detalhe.</h2><p>Peças exclusivas, com design moderno e personalização que valoriza o seu estilo.</p></div><a class="button" href="#colecoes">Ver todos <span aria-hidden="true">→</span></a></div>
        <form id="home-search" class="search-form" role="search" hidden><label for="product-search">Encontre seu estilo</label><div><input type="search" id="product-search" placeholder="Busque pelo nome da peça…" autocomplete="off"><button class="icon-button" type="button" data-search-close aria-label="Fechar busca">×</button></div></form>
        <div class="product-filters" role="group" aria-label="Filtrar produtos"><button class="active" type="button" data-filter="all" aria-pressed="true">Destaques</button><button type="button" data-filter="masculino" aria-pressed="false">Masculino</button><button type="button" data-filter="infantil" aria-pressed="false">Infantil</button><button type="button" data-filter="acessorios" aria-pressed="false">Acessórios</button></div>
        @php
            $products = [
                ['name' => 'Camiseta LM Preta', 'category' => 'masculino', 'image' => 'lm-camiseta-preta.png', 'colors' => ['#171715']],
                ['name' => 'Camiseta LM Branca', 'category' => 'masculino', 'image' => 'lm-camiseta-branca.png', 'colors' => ['#f5f3ed']],
                ['name' => 'Camiseta LM Areia', 'category' => 'masculino', 'image' => 'lm-camiseta-areia.png', 'colors' => ['#b9aa91']],
                ['name' => 'Camiseta LM Caramelo', 'category' => 'masculino', 'image' => 'lm-camiseta-caramelo.png', 'colors' => ['#a05d33']],
            ];
        @endphp
        <div class="product-grid">
            @foreach ($products as $product)
                <article class="product-card" data-category="{{ $product['category'] }}" data-name="{{ $product['name'] }}">
                    <div class="product-photo photo-slot" data-photo="produto-{{ $loop->iteration }}"><img src="{{ asset('assets/images/'.$product['image']) }}" alt="{{ $product['name'] }} dobrada com o brasão oficial LM" loading="lazy" decoding="async"><button class="favorite-button" type="button" aria-label="Favoritar {{ $product['name'] }}" aria-pressed="false">@include('manela.icon', ['name' => 'heart'])</button></div>
                    <div class="product-info"><h3>{{ $product['name'] }}</h3><p class="product-price">Em breve</p><div class="product-colors" aria-label="Cores da coleção">@foreach ($product['colors'] as $color)<span style="--swatch: {{ $color }}" aria-hidden="true"></span>@endforeach</div><a class="product-more" href="#personalizacao" aria-label="Saiba mais sobre {{ $product['name'] }}">+</a></div>
                </article>
            @endforeach
        </div>
        <p class="empty-state" role="status" hidden>Novas peças estão chegando. Explore as outras categorias e encontre seu estilo.</p>
    </section>
    <section class="wood personalization" id="personalizacao" aria-labelledby="personalization-title">
        <div class="personalization-inner container">
            <div class="personalization-photo photo-slot" data-photo="personalizacao"><img src="{{ asset('assets/images/lm-personalizacao-banner.png') }}" alt="Modelo de costas vestindo camiseta preta com o brasão LM em destaque, sob luz dourada e fundo de madeira" width="2012" height="782" loading="lazy" decoding="async"></div>
            <div class="personalization-copy"><p class="eyebrow">Personalização</p><h2 id="personalization-title">Mais que roupas,<br>identidade.</h2><p>Crie peças únicas com a sua identidade. Personalização com qualidade e acabamento premium, do seu jeito.</p><a class="button button-light" href="{{ route('san-doces.encomendas') }}">Saiba mais <span aria-hidden="true">→</span></a></div>
            <div class="qualities"><div>@include('manela.icon', ['name' => 'diamond'])<p><strong>Qualidade</strong>em cada detalhe</p></div><div>@include('manela.icon', ['name' => 'shirt'])<p><strong>Estilo que</strong>combina com você</p></div><div>@include('manela.icon', ['name' => 'star'])<p><strong>Personalização</strong>do seu jeito</p></div></div>
        </div>
    </section>
    <section class="collections section-space" id="linhas" aria-label="Nossas linhas e coleções">
        <div class="collection-grid">
            <article class="collection-card collection-men"><div class="collection-copy"><p class="eyebrow">Linha</p><h2>Masculino</h2><p>Estilo e conforto<br>para o seu dia a dia.</p><a href="#produtos" class="button button-outline" data-select-filter="masculino">Ver Coleção <span aria-hidden="true">→</span></a></div><div class="collection-photo photo-slot" data-photo="masculino"><img src="{{ asset('assets/images/lm-camiseta-preta.png') }}" alt="Camiseta preta da coleção masculina LM" loading="lazy" decoding="async"></div></article>
            <article class="collection-card collection-kids"><div class="collection-copy"><p class="eyebrow">Linha</p><h2>Infantil</h2><p>Pequenos no estilo,<br>grandes na atitude.</p><a href="#produtos" class="button" data-select-filter="infantil">Ver Coleção <span aria-hidden="true">→</span></a></div><div class="collection-photo photo-slot" data-photo="infantil" aria-label="Espaço reservado para foto da linha infantil"></div></article>
        </div>
        <div class="essential-collection container" id="colecoes"><div class="essential-copy"><p class="eyebrow">Coleção exclusiva</p><h2>Coleção<br>Essencial</h2><p>Peças que combinam com todas as ocasiões. Minimalismo, qualidade e muito estilo.</p><a class="button" href="#produtos" data-select-filter="all">Ver Coleção <span aria-hidden="true">→</span></a></div><div class="essential-gallery">@foreach (['preta' => 'Preta', 'branca' => 'Branca', 'areia' => 'Areia', 'mostarda' => 'Mostarda'] as $photo => $color)<a class="essential-photo photo-slot" href="#produtos" data-select-filter="all" data-photo="colecao-{{ $photo }}" aria-label="Conhecer a coleção essencial — {{ $color }}"><img src="{{ asset('assets/images/lm-camiseta-'.$photo.'.png') }}" alt="Camiseta LM {{ $color }}" loading="lazy" decoding="async"></a>@endforeach</div></div>
    </section>
    <div class="collection-transition" aria-hidden="true"><span class="collection-brush collection-brush-left"></span><span class="collection-brush collection-brush-right"></span></div>
    <section class="benefits" aria-label="Vantagens LM"><div class="container benefits-grid"><div>@include('manela.icon', ['name' => 'truck'])<p>Envio para<br>todo o Brasil</p></div><div>@include('manela.icon', ['name' => 'card'])<p>Pagamento<br>seguro</p></div><div>@include('manela.icon', ['name' => 'headset'])<p>Atendimento<br>personalizado</p></div><div>@include('manela.icon', ['name' => 'award'])<p>Qualidade<br>garantida</p></div></div></section>
    <section class="wood instagram-section" aria-labelledby="instagram-title"><div class="container instagram-inner"><div class="instagram-copy"><p class="eyebrow">Nosso estilo</p><h2 id="instagram-title">Acompanhe<br>no Instagram.</h2><p>Confira novidades, lançamentos e inspirações de quem faz parte dessa história.</p><a class="button" href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Ver no Instagram <span aria-hidden="true">↗</span></a></div><div class="instagram-gallery">@foreach (['lm-camiseta-preta.png' => 'Camiseta preta LM', 'lm-camisetas-colecao.png' => 'Camisetas LM em cinco cores', 'lm-camiseta-caramelo.png' => 'Camiseta caramelo LM', 'lm-personalizacao.png' => 'Estilo e identidade com o brasão LM'] as $photo => $description)<div class="instagram-photo photo-slot" data-photo="instagram-{{ $loop->iteration }}"><img src="{{ asset('assets/images/'.$photo) }}" alt="{{ $description }}" loading="lazy" decoding="async"></div>@endforeach</div></div></section>
    </main>
    <footer class="site-footer"><div class="container footer-main"><div class="footer-brand"><a class="brand" href="#top" aria-label="LM — voltar ao início">@include('manela.logo')</a><p class="brand-name">LM ESTILO PERSONALIZADO</p><p>Roupas que expressam<br>quem você é.</p><a class="social-link" href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">@include('manela.icon', ['name' => 'instagram'])</a></div><div><h3>Navegação</h3><nav aria-label="Navegação do rodapé"><a href="#inicio">Início</a><a href="#linhas">Masculino</a><a href="#linhas">Infantil</a><a href="#colecoes">Coleções</a><a href="#produtos" data-select-filter="acessorios">Acessórios</a><a href="#personalizacao">Sobre a marca</a><a href="{{ route('san-doces.contato') }}">Contato</a></nav></div><div><h3>Ajuda</h3><nav aria-label="Ajuda"><a href="{{ route('san-doces.contato') }}">Perguntas frequentes</a><a href="{{ route('san-doces.contato') }}">Trocas e devoluções</a><a href="{{ route('san-doces.contato') }}">Informações de entrega</a><a href="{{ route('san-doces.contato') }}">Fale com a gente</a></nav></div><div class="footer-contact"><h3>Atendimento</h3><a href="{{ route('san-doces.contato') }}">@include('manela.icon', ['name' => 'headset'])<span>Fale conosco<small>Atendimento personalizado</small></span></a><p>@include('manela.icon', ['name' => 'pin'])<span>Tatuí · SP<small>Atendemos todo o Brasil</small></span></p></div><div class="footer-news"><h3>Vista sua identidade</h3><p>Qualidade, conforto e personalidade em cada peça.</p><a class="button button-outline" href="#colecoes">Explore as coleções <span aria-hidden="true">→</span></a><div class="payment-methods" aria-label="Meios de pagamento">VISA <span>●●</span> elo <span>pix</span></div></div></div><div class="container footer-bottom"><span>© {{ date('Y') }} LM Estilo Personalizado. Todos os direitos reservados.</span><span>Modelo 3D: <a href="https://sketchfab.com/3d-models/tshirt-5a21282b2e454d1696547148f617d3d0" target="_blank" rel="noopener noreferrer">Tabbuso</a> · <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener noreferrer">CC BY 4.0</a> · cor adaptada</span><a href="#top">Voltar ao topo ↑</a></div></footer>
</body>
</html>
