<?php
// /login.php
// Gerado em: 24/05/2025, 11:45:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Entrar"; // Título curto para a barra de título do navegador
require_once 'includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redireciona para o roteador principal
    exit;
}
?>
<style>
    body {
        display: flex;
        align-items: center; /* Centraliza verticalmente */
        justify-content: center;
        min-height: 100vh; /* Ocupa toda a altura da viewport */
        padding-bottom: 0 !important; /* Remove padding da nav, pois não há nav aqui */
    }
    main.container-fluid { /* O main em si não precisa de flex aqui */
        display: block;
    }
    .login-wrapper {
        max-width: 400px;
        width: 100%;
        padding: 2rem; /* Padding interno */
    }
    .login-logo {
        font-size: 4.5rem; /* Ícone grande */
        color: var(--color-primary-yellow);
        margin-bottom: 0.5rem;
    }
    .login-title {
        font-size: 1.75rem; /* 28px */
        font-weight: 700; /* Bold */
        color: var(--color-primary-red);
        margin-bottom: 0.5rem;
    }
    .login-subtitle {
        font-size: 1rem; /* 16px */
        color: var(--color-text-secondary);
        margin-bottom: 2rem;
    }
    .form-floating label {
        padding-left: 0.5rem; /* Ajuste para label flutuante */
    }
    .form-control {
        height: calc(3.5rem + 2px); /* Altura maior para campos de formulário */
        line-height: 1.25;
    }
    .btn-login {
        padding-top: 0.875rem; /* 14px */
        padding-bottom: 0.875rem;
        font-size: 1.125rem; /* 18px */
    }
    .link-container {
        margin-top: 1.5rem;
    }
    .link-container p {
        margin-bottom: 0.5rem;
        font-size: 0.9375rem; /* 15px */
    }
    .link-container .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
</style>

<div class="login-wrapper text-center">
    <i class="bi bi-egg-fried login-logo"></i>
    <h1 class="login-title">EcoSave</h1>
    <p class="login-subtitle">Salve comida, economize dinheiro!</p>

    <form id="login-form">
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="login-email" name="email" placeholder="seu@email.com" required>
            <label for="login-email">Email</label>
        </div>
        <div class="form-floating mb-4">
            <input type="password" class="form-control" id="login-password" name="password" placeholder="Senha" required>
            <label for="login-password">Senha</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary-red btn-login" type="submit">
            Entrar
        </button>
        <div id="login-message" class="mt-3 alert d-none"></div>
    </form>

    <div class="link-container text-center">
        <p class="text-muted">Ainda não tem conta?</p>
        <a href="cadastro_consumidor.php" class="btn btn-subtle">Sou Consumidor</a>
        <a href="cadastro_estabelecimento.php" class="btn btn-subtle">Sou Estabelecimento</a>
    </div>
</div>

<script>
    // O JavaScript para o login.php permanece o mesmo que já tínhamos.
    // Ele fará a chamada AJAX para /api/user_login.php e redirecionará.
    document.getElementById('login-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const msgDiv = document.getElementById('login-message');
        const submitButton = event.target.querySelector('button[type="submit"]');
        msgDiv.className = 'mt-3 alert d-none';
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Entrando...';

        const formData = new FormData(event.target);

        try {
            const response = await fetch('./api/user_login.php', { method: 'POST', body: formData });
            const result = await response.json();

            if (result.status === 'success') {
                msgDiv.textContent = 'Login bem-sucedido! Redirecionando...';
                msgDiv.className = 'mt-3 alert alert-success show';
                window.location.href = 'index.php'; // Redireciona para o roteador
            } else {
                msgDiv.textContent = result.message || 'Erro no login. Verifique seus dados.';
                msgDiv.className = 'mt-3 alert alert-danger show';
                submitButton.disabled = false;
                submitButton.innerHTML = 'Entrar';
            }
        } catch (error) {
            console.error('Erro de login:', error);
            msgDiv.textContent = 'Erro de conexão. Tente novamente mais tarde.';
            msgDiv.className = 'mt-3 alert alert-danger show';
            submitButton.disabled = false;
            submitButton.innerHTML = 'Entrar';
        }
    });
</script>

<?php
// Não incluímos o footer.php aqui, pois o body tem estilo especial
// Se precisar de scripts globais, adicione-os diretamente ou no header.
// Para a página de login, geralmente é melhor mantê-la mais isolada.
// Mas se o footer.php só tiver o <script> do Bootstrap, pode incluir.
require_once 'includes/footer.php';
?>
</body> </html>
