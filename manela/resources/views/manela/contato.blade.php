<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato | San Doces</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&family=Sacramento&display=swap"
        rel="stylesheet">
    @vite(['resources/css/san-doces.css', 'resources/js/san-doces.js'])
</head>

<body id="top">
     @include('san-doces.navigation')
    <main>
        <section class="hero">
            <div class="container">
                <div class="eyebrow">Entre em contato</div>
                <h1>Estamos aqui<br>para te atender ♡</h1>
                <p>Tem alguma dúvida, sugestão ou quer fazer seu pedido? Fale com a gente! Será um prazer te atender e
                    tornar seu momento ainda mais doce.</p>
                <div class="script">Doce é sempre<br>melhor quando compartilhado! ♡</div>
            </div>
        </section>
        <div class="scroll-content">
        <section class="container contact">
            <div>
                <h2>Nossos contatos　♡</h2>
                <div class="contact-list"><a class="contact-card" href="https://wa.me/5515991444740" target="_blank"
                        rel="noopener"><span class="icon">◉</span>
                        <div><b>WhatsApp<br>(15) 99144-4740</b>
                            <p>Fale conosco pelo WhatsApp e faça seu pedido de forma rápida e prática!</p>
                        </div><span class="arrow">→</span>
                    </a><a class="contact-card" href="#"><span class="icon">◎</span>
                        <div><b>Instagram<br>@sandoces_confeitaria</b>
                            <p>Acompanhe nossos doces, novidades e muito mais!</p>
                        </div><span class="arrow">→</span>
                    </a><a class="contact-card" href="https://maps.app.goo.gl/aGw49bdpHnLDqECd8" target="_blank"
                        rel="noopener"><span class="icon">⌖</span>
                        <div><b>Endereço<br>Tatuí - SP</b>
                            <p>Estamos localizados em Tatuí - SP. Venha nos visitar!</p>
                        </div><span class="arrow">→</span>
                    </a>
                    <div class="contact-card"><span class="icon">◷</span>
                        <div><b>Horário de funcionamento<br>Segunda a Sábado</b>
                            <p>Das 14:30 às 23:30</p>
                        </div><span class="arrow">→</span>
                    </div>
                </div>
            </div>
            <form id="mensagem" class="form">
                <h2>Envie uma mensagem</h2>
                <p>Preencha os campos abaixo e teremos o maior prazer em responder o seu contato.</p>
                <div class="two"><input id="contato_nome" name="nome" required placeholder="Nome completo *"><input id="contato_email" name="email" type="email" required
                        placeholder="E-mail *"></div>
                <div class="two"><input id="contato_whatsapp" name="whatsapp" required placeholder="WhatsApp *"><select id="contato_assunto" name="assunto" required>
                        <option value="">Assunto *</option>
                        <option>Dúvida</option>
                        <option>Encomenda</option>
                        <option>Sugestão</option>
                    </select></div><textarea id="contato_mensagem" name="mensagem" required
                    placeholder="Sua mensagem *&#10;Escreva aqui sua mensagem..."></textarea><button class="button"
                    type="submit">⌁　Enviar mensagem</button>
                <div class="secure">♙　 Seus dados estão seguros conosco.<br>&nbsp;&nbsp;&nbsp;&nbsp; Não compartilhamos
                    suas informações.</div>
            </form>
        </section>
        <section class="container visit">
            <div class="visit-text">
                <div class="eyebrow" style="color:#fff">Venha nos visitar</div>
                <h2>Estamos te esperando! ♡</h2>
                <p>Nossa loja física está localizada em Tatuí - SP. Venha conhecer de perto nossos doces e se encantar
                    com cada detalhe!</p><a class="button" href="https://maps.app.goo.gl/aGw49bdpHnLDqECd8"
                    target="_blank" rel="noopener">⌖　Ver no mapa　→</a>
            </div>
            <div class="map"><iframe title="Mapa San Doce Doceria em Tatuí"
                    src="https://www.google.com/maps?q=San%20Doce%20Doceria%20Tatui%20SP&output=embed"
                    loading="lazy"></iframe></div>
        </section>
        </div>
    </main>
    <footer class="sd-footer"><div class="sd-footer-main"><div class="sd-footer-intro"><a class="sd-footer-brand" href="{{ route('san-doces.home') }}">SAN<br>DOCES<small>DOCERIA</small></a><p>Doces artesanais feitos com carinho para tornar cada momento ainda mais especial.</p></div><div><h4>Navegação</h4><nav class="sd-footer-links" aria-label="Navegação do rodapé"><a href="{{ route('san-doces.home') }}">Início</a><a href="{{ route('san-doces.produtos') }}">Produtos</a><a href="{{ route('san-doces.sobre-nos') }}">Sobre nós</a><a href="{{ route('san-doces.encomendas') }}">Encomendas</a><a href="{{ route('san-doces.contato') }}">Contato</a></nav></div><div class="sd-footer-contact"><h4>Fale conosco</h4><p>(15) 99144-4740</p><p>@sandoces_confeitaria</p><p>Tatuí — SP</p></div><div class="sd-footer-contact"><h4>Horário</h4><p>Segunda a sábado</p><p>Das 14:30 às 23:30</p><a class="sd-footer-cta" href="https://wa.me/5515991444740" target="_blank" rel="noopener">Fazer pedido pelo WhatsApp →</a></div></div><div class="sd-footer-bottom"><span>© 2026 San Doces. Todos os direitos reservados.</span><span>Doces que tornam a vida mais doce! ♡</span><span>Desenvolvido por <a href="https://brasildash.com.br" target="_blank" rel="noopener noreferrer">Brasildash</a></span><a href="#top">Voltar ao topo ↑</a></div></footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

</html>
