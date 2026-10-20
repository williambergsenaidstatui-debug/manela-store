<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | San Doces</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&family=Sacramento&display=swap" rel="stylesheet">
    @vite(['resources/css/san-doces.css', 'resources/js/san-doces.js', 'resources/js/carrinho.js'])
</head>
<body id="top" class="cart-page">
    @include('san-doces.navigation')

    <main>
        <section class="cart-hero">
            <div class="container">
                <nav class="cart-breadcrumb" aria-label="Caminho da pagina">
                    <a href="{{ route('san-doces.home') }}">Inicio</a>
                    <span aria-hidden="true">/</span>
                    <a href="{{ route('san-doces.produtos') }}">Produtos</a>
                    <span aria-hidden="true">/</span>
                    <strong>Carrinho</strong>
                </nav>
                <h1>Finalizar pedido</h1>
                <p>Quase la! Confira seus doces, escolha a quantidade e deixe seus dados para receber tudo com carinho.</p>
            </div>
        </section>

        <div class="cart-scroll">
            <div class="container cart-layout">
                <section class="cart-main" aria-label="Dados do pedido">
                    <div class="cart-panel">
                        <div class="cart-panel-heading">
                            <span class="cart-icon icon-cart" aria-hidden="true"></span>
                            <h2>Seu carrinho <small id="cart-count">(0 itens)</small></h2>
                        </div>
                        <div id="cart-items" class="cart-items" aria-live="polite">
                            <p class="cart-empty">Carregando carrinho...</p>
                        </div>
                    </div>

                    <div class="cart-panel add-more-panel">
                        <div class="cart-panel-heading">
                            <span class="cart-icon icon-plus" aria-hidden="true"></span>
                            <h2>Adicionar mais produtos</h2>
                        </div>
                        <div class="cart-add-grid">
                            <label for="cart-product-select">
                                Escolha outro doce
                                <select id="cart-product-select">
                                    <option value="">Carregando produtos...</option>
                                </select>
                            </label>
                            <button id="cart-add-product" class="cart-secondary-button" type="button">Adicionar ao carrinho</button>
                        </div>
                    </div>

                    <form id="checkout-form" class="cart-form">
                        <input id="cart-payload" name="cart_payload" type="hidden">
                        <input id="cart-total-input" name="total" type="hidden">

                        <div class="cart-panel">
                            <div class="cart-panel-heading">
                                <span class="cart-icon icon-truck" aria-hidden="true"></span>
                                <h2>Tipo de pedido</h2>
                            </div>
                            <div class="cart-choice-grid compact" role="radiogroup" aria-label="Tipo de pedido">
                                <label class="cart-choice is-selected">
                                    <input type="radio" name="tipo_pedido" value="delivery" checked>
                                    <span class="cart-inline-icon icon-bike" aria-hidden="true"></span>
                                    <strong>Delivery</strong>
                                </label>
                                <label class="cart-choice">
                                    <input type="radio" name="tipo_pedido" value="retirada">
                                    <span class="cart-inline-icon icon-home" aria-hidden="true"></span>
                                    <strong>Retirada</strong>
                                </label>
                            </div>
                            <label class="cart-schedule-field" for="agendamento">
                                <span><span class="cart-label-icon icon-clock" aria-hidden="true"></span> Agende seu pedido <strong>(opcional)</strong></span>
                                <input id="agendamento" name="agendamento" type="datetime-local">
                            </label>
                        </div>

                        <div class="cart-panel">
                            <div class="cart-panel-heading">
                                <span class="cart-icon icon-card" aria-hidden="true"></span>
                                <h2>Forma de pagamento</h2>
                            </div>
                            <div class="cart-payment-list compact" role="radiogroup" aria-label="Forma de pagamento">
                                <label class="cart-payment is-selected">
                                    <input type="radio" name="pagamento" value="dinheiro" checked>
                                    <strong>Dinheiro</strong>
                                </label>
                                <label class="cart-payment">
                                    <input type="radio" name="pagamento" value="pix">
                                    <strong>PIX (chave exibida apos o envio)</strong>
                                </label>
                                <label class="cart-payment">
                                    <input type="radio" name="pagamento" value="cartao_credito_online">
                                    <strong>Cartao de Credito Online</strong>
                                </label>
                                <label class="cart-payment">
                                    <input type="radio" name="pagamento" value="cartao_debito_maquininha">
                                    <strong>Cartao de Debito - Maquininha</strong>
                                </label>
                                <label class="cart-payment">
                                    <input type="radio" name="pagamento" value="cartao_credito_maquininha">
                                    <strong>Cartao de Credito - Maquininha</strong>
                                </label>
                            </div>
                            <div id="cash-change-options" class="cash-change-options">
                                <strong>Vai precisar de troco?</strong>
                                <div class="cash-change-choice" role="radiogroup" aria-label="Troco para pagamento em dinheiro">
                                    <label><input type="radio" name="precisa_troco" value="nao" checked> Nao preciso de troco</label>
                                    <label><input type="radio" name="precisa_troco" value="sim"> Troco para quanto?</label>
                                </div>
                                <label class="cash-change-value is-hidden" for="troco_para">
                                    Informe o valor
                                    <input id="troco_para" name="troco_para" type="number" min="0" step="0.01" inputmode="decimal" placeholder="Ex: 100,00" disabled>
                                </label>
                            </div>
                        </div>

                        <div class="cart-panel">
                            <div class="cart-panel-heading">
                                <span class="cart-icon icon-user" aria-hidden="true"></span>
                                <h2>Seus dados</h2>
                            </div>
                            <div class="cart-fields two-columns">
                                <label>Nome (apenas 1 nome) *
                                    <input name="nome" autocomplete="given-name" required placeholder="Ex: Maria">
                                </label>
                                <label>CPF *
                                    <input name="cpf" inputmode="numeric" required placeholder="000.000.000-00">
                                </label>
                                <label>Seu WhatsApp (somente digitos) *
                                    <input name="whatsapp" type="tel" inputmode="tel" autocomplete="tel" required placeholder="(15) 99999-9999">
                                </label>
                                <label>Aniversario (opcional, para brindes)
                                    <input name="aniversario" type="date">
                                </label>
                            </div>
                        </div>

                        <div class="cart-panel">
                            <div class="cart-panel-heading">
                                <h2>Endereco de entrega</h2>
                            </div>
                            <div class="cart-fields address-grid address-stack">
                                <label>CEP (obrigatorio)
                                    <input name="cep" inputmode="numeric" autocomplete="postal-code" required placeholder="Digite o CEP">
                                </label>
                                <label>Endere&ccedil;o em Tatu&iacute;
                                    <input name="rua" autocomplete="street-address" required>
                                </label>
                                <label> N&uacute;mero
                                    <input name="numero" required>
                                </label>
                                <label> Escolha seu bairro/condom&iacute;nio
                                    <select name="bairro" required>
                                        <option value="">Selecione</option>
                                        <option value="Centro">Centro</option>
                                        <option value="Santa Rita">Santa Rita</option>
                                        <option value="Jardim Wanderley">Jardim Wanderley</option>
                                        <option value="Jardim Mantovani">Jardim Mantovani</option>
                                        <option value="Vila Dr. Laurindo">Vila Dr. Laurindo</option>
                                        <option value="Vila Esperanca">Vila Esperanca</option>
                                        <option value="Outro">Outro bairro/condominio</option>
                                    </select>
                                </label>
                                <label> APTO/Refer&ecirc;ncia/Complemento
                                    <input name="complemento">
                                </label>
                            </div>
                            <button class="cart-submit" type="submit">

                                Finalizar pedido

                            </button>
                        </div>
                    </form>
                </section>

                <aside class="cart-summary" aria-label="Resumo do pedido">
                    <div class="cart-summary-card">
                        <div class="cart-summary-title">
                            <span class="cart-icon icon-cart" aria-hidden="true"></span>
                            <h2>Resumo do pedido</h2>
                        </div>
                        <div id="cart-summary-items" class="cart-summary-items"></div>
                        <dl class="cart-totals">
                            <div><dt>Subtotal do pedido:</dt><dd id="cart-subtotal">R$ 0,00</dd></div>
                            <div><dt>Taxa de entrega:</dt><dd id="cart-delivery">R$ 12,00</dd></div>
                            <div class="cart-total"><dt>Total:</dt><dd id="cart-total">R$ 0,00</dd></div>
                        </dl>
                        <div class="cart-benefits">
                            <p><span class="icon-shield" aria-hidden="true"></span><strong>Compra segura</strong><small>Seus dados protegidos</small></p>
                            <p><span class="icon-truck" aria-hidden="true"></span><strong>Entrega com carinho</strong><small>Seus doces ate voce</small></p>
                            <p><span class="icon-heart" aria-hidden="true"></span><strong>Qualidade San Doces</strong><small>Doces feitos para momentos especiais</small></p>
                        </div>
                        <div class="cart-love-card">
                            <span class="icon-cupcake" aria-hidden="true"></span>
                            <strong>Mais que doces,<br>criamos momentos especiais</strong>
                            <small class="icon-heart" aria-hidden="true"></small>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <footer class="sd-footer">
        <div class="sd-footer-main">
            <div class="sd-footer-intro"><a class="sd-footer-brand" href="{{ route('san-doces.home') }}">SAN<br>DOCES<small>DOCERIA</small></a><p>Doces artesanais feitos com carinho para tornar cada momento ainda mais especial.</p></div>
            <div><h4>Navegacao</h4><nav class="sd-footer-links" aria-label="Navegacao do rodape"><a href="{{ route('san-doces.home') }}">Inicio</a><a href="{{ route('san-doces.produtos') }}">Produtos</a><a href="{{ route('san-doces.sobre-nos') }}">Sobre nos</a><a href="{{ route('san-doces.encomendas') }}">Encomendas</a><a href="{{ route('san-doces.contato') }}">Contato</a></nav></div>
            <div class="sd-footer-contact"><h4>Fale conosco</h4><p>(15) 99144-4740</p><p>@sandoces_confeitaria</p><p>Tatui - SP</p></div>
            <div class="sd-footer-contact"><h4>Horario</h4><p>Segunda a sabado</p><p>Das 14:30 as 23:30</p><a class="sd-footer-cta" href="https://wa.me/5515991444740" target="_blank" rel="noopener">Fazer pedido pelo WhatsApp</a></div>
        </div>
        <div class="sd-footer-bottom"><span>2026 San Doces. Todos os direitos reservados.</span><span>Doces que tornam a vida mais doce!</span><span>Desenvolvido por <a href="https://brasildash.com.br" target="_blank" rel="noopener noreferrer">Brasildash</a></span><a href="#top">Voltar ao topo</a></div>
    </footer>
</body>
</html>





    




