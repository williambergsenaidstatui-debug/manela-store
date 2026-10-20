<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | San Doce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&family=Sacramento&display=swap" rel="stylesheet">
    @vite(['resources/css/san-doces.css', 'resources/js/login.js'])
</head>

<body class="auth-page auth-login-page">
    <main class="auth-shell">
        <section class="auth-visual" aria-label="San Doce">
            <div class="auth-logo">SAN<br>DOCE<small>DOCERIA</small></div>
            <div class="auth-welcome">
                <p>Seja bem-vindo(a)!</p>
                <span>Entre na sua conta e continue cuidando dos momentos mais doces.</span>
            </div>
            <div class="auth-benefits">
                <span><b class="auth-cup"></b>Doces artesanais<br>com muito amor</span>
                <span><b class="auth-heart"></b>Qualidade e<br>sabor em cada detalhe</span>
                <span><b class="auth-star"></b>Momentos especiais<br>comecam aqui</span>
            </div>
        </section>

        <section class="auth-content">
            <a class="auth-back" href="{{ route('san-doces.home') }}">Voltar ao site</a>
            <div class="auth-note">Porque todo<br>doce comeca<br>com voce!</div>

            <div class="auth-heading">
                <span>ACESSE SUA CONTA</span>
                <h1>Vamos entrar?</h1>
                <p>Informe seus dados para acessar o painel da San Doce.</p>
            </div>

            <form class="auth-card" id="login-form" method="POST" action="/api/login">
                @csrf
                <label class="auth-field">
                    <span><b class="field-icon email"></b>E-mail *</span>
                    <input id="email" name="email" type="email" autocomplete="email" placeholder="seu@email.com" required>
                    <small>Use o e-mail cadastrado no sistema.</small>
                </label>

                <label class="auth-field">
                    <span><b class="field-icon lock"></b>Senha *</span>
                    <span class="auth-password">
                        <input id="senha" name="senha" type="password" autocomplete="current-password" placeholder="Digite sua senha" required>
                        <button id="toggle-password" type="button" aria-label="Mostrar senha"></button>
                    </span>
                    <small>Minimo de 6 caracteres.</small>
                </label>

                <div class="auth-options">
                    <label><input type="checkbox" name="lembrar"> Lembrar acesso</label>
                    <a href="#">Esqueci minha senha</a>
                </div>

                <button class="auth-submit" id="entrar" type="submit"><span class="field-icon user"></span>Entrar</button>

                <div class="auth-divider"><span>ou</span></div>
                <p class="auth-switch">Ainda nao tem uma conta? <a href="{{ route('san-doces.cadastro_usuario') }}">Faca seu cadastro</a></p>
            </form>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
