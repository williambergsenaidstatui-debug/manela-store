<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Encomendas | San Doces</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&family=Sacramento&display=swap"
    rel="stylesheet">
  @vite(['resources/css/san-doces.css', 'resources/js/san-doces.js', 'resources/js/pedidos.js'])
</head>

<body id="top" class="orders-page">
  @include('manela.navigation')
  <main>
    <section class="hero">
      <div class="container">
        <div class="eyebrow">Encomendas</div>
        <h1>Seu momento<br>mais doce comeca<br>aqui!</h1>
        <p>Faca sua encomenda e deixe cada ocasiao ainda mais especial com os nossos doces gourmet!</p>
      </div>
    </section>
    <div class="scroll-content">
      <section class="container steps">
        <div class="title">Como fazer sua encomenda?</div>
        <div class="steps-grid">
          <article class="step"><b>1</b>
            <h3>Escolha os produtos</h3>
            <p>Veja nosso cardapio e selecione o que deseja.</p>
          </article>
          <article class="step"><b>2</b>
            <h3>Monte seu pedido</h3>
            <p>Adicione um ou mais itens antes de enviar.</p>
          </article>
          <article class="step"><b>3</b>
            <h3>Combinamos os detalhes</h3>
            <p>Data, horario e forma de pagamento.</p>
          </article>
          <article class="step"><b>4</b>
            <h3>Seu pedido e preparado</h3>
            <p>Tudo feito com muito amor e carinho!</p>
          </article>
        </div>
      </section>
      <section class="container order-area">
        <div class="products">
          <h2>Nossos produtos</h2>
          <p>Escolha a categoria e adicione uma ou mais opcoes ao seu pedido.</p>
          <div class="selection">
            <div id="order-category-list" class="category-list">
              <button class="category active" type="button" data-category="all">Todos &rsaquo;</button>
            </div>
            <div id="order-products" class="cards">
              <p class="order-products-empty">Carregando produtos...</p>
            </div>
          </div>
        </div>
        <form id="formulario" class="form">
          <div class="eyebrow">Faca sua encomenda</div>
          <h2>Conte com a San Doces</h2>
          <p>Preencha o formulario abaixo e nos conte o que voce deseja. Vamos adorar preparar algo especial para voce!</p>
          <div id="order-selected-summary" class="order-selected-summary">
            <span>Itens do pedido</span>
            <strong>Nenhum produto escolhido ainda</strong>
            <small>Clique em um produto do cardapio para adicionar ao pedido.</small>
          </div>
          <ul id="order-items-list" class="order-items-list" aria-live="polite"></ul>
          <input id="categoria" name="categoria" type="hidden" required>
          <input id="produto" name="produto" type="hidden" required>
          <div class="order-form-fields">
            <label for="nome">Nome completo *</label>
            <input id="nome" name="nome" autocomplete="name" required placeholder="Como podemos te chamar?">
            <label for="whatsapp">WhatsApp *</label>
            <input id="whatsapp" name="whatsapp" type="tel" inputmode="tel" autocomplete="tel" required placeholder="(15) 99999-9999">
            <label for="data_encomenda">Data e hora da encomenda *</label>
            <input id="data_encomenda" name="data_encomenda" type="datetime-local" required>
            <label for="observacao">Observacoes <span>(opcional)</span></label>
            <textarea id="observacao" name="observacao" placeholder="Conte sobre sabores, quantidades ou detalhes da ocasiao..."></textarea>
          </div>
          <button class="button" type="submit">Enviar pedido pelo WhatsApp</button>
          <div class="secure">Seus dados estao seguros conosco.<br>&nbsp;&nbsp;&nbsp;&nbsp;Nao compartilhamos suas informacoes.</div>
        </form>
      </section>
    </div>
  </main>
  <footer class="sd-footer">
    <div class="sd-footer-main">
      <div class="sd-footer-intro"><a class="sd-footer-brand" href="{{ route('manela.home') }}">SAN<br>DOCES<small>DOCERIA</small></a><p>Doces artesanais feitos com carinho para tornar cada momento ainda mais especial.</p></div>
      <div><h4>Navegacao</h4><nav class="sd-footer-links" aria-label="Navegacao do rodape"><a href="{{ route('manela.home') }}">Inicio</a><a href="{{ route('manela.produtos') }}">Produtos</a><a href="{{ route('manela.sobre-nos') }}">Sobre nos</a><a href="{{ route('manela.encomendas') }}">Encomendas</a><a href="{{ route('manela.contato') }}">Contato</a></nav></div>
      <div class="sd-footer-contact"><h4>Fale conosco</h4><p>(15) 99144-4740</p><p>@sandoces_confeitaria</p><p>Tatui - SP</p></div>
      <div class="sd-footer-contact"><h4>Horario</h4><p>Segunda a sabado</p><p>Das 14:30 as 23:30</p><a class="sd-footer-cta" href="https://wa.me/5515991444740" target="_blank" rel="noopener">Fazer pedido pelo WhatsApp</a></div>
    </div>
    <div class="sd-footer-bottom"><span>2026 San Doces. Todos os direitos reservados.</span><span>Doces que tornam a vida mais doce!</span><span>Desenvolvido por <a href="https://brasildash.com.br" target="_blank" rel="noopener noreferrer">Brasildash</a></span><a href="#top">Voltar ao topo</a></div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
