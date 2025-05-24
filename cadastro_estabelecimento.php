<?php
$page_title = "EcoSave - Cadastro Estabelecimento";
require_once 'includes/header.php';

// Se já estiver logado, redireciona
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<div class="card shadow-sm">
    <div class="card-body p-5">
        <h2 class="text-center mb-4" style="color: #FF4500;">Cadastro de Estabelecimento</h2>
        <form id="register-form-biz">
            <input type="hidden" name="user_type" value="business"> <h5 class="mb-3">Dados de Login</h5>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="register-name" name="name" placeholder="Seu Nome (Responsável)" required>
                <label for="register-name">Nome do Responsável</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="register-email" name="email" placeholder="email@estabelecimento.com" required>
                <label for="register-email">Email de Contato</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="register-password" name="password" placeholder="Senha" required>
                <label for="register-password">Senha</label>
            </div>

            <h5 class="mb-3 mt-4">Dados do Estabelecimento</h5>
             <div class="form-floating mb-3">
                <input type="text" class="form-control" id="register-biz-name" name="business_name" placeholder="Nome Fantasia" required>
                <label for="register-biz-name">Nome do Estabelecimento</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="register-address" name="address" placeholder="Rua, Número, Bairro" required>
                <label for="register-address">Endereço Completo</label>
            </div>
            <button class="w-100 btn btn-lg btn-warning" type="submit" style="background-color: #FFC107; color: #333;">Cadastrar Estabelecimento</button>
            <p class="mt-3 text-center">Já tem conta? <a href="login.php">Faça Login</a></p>
            <div id="register-message-biz" class="mt-3 alert d-none"></div>
        </form>
    </div>
</div>

<script>
    document.getElementById('register-form-biz').addEventListener('submit', async (event) => {
        event.preventDefault();
        const msgDiv = document.getElementById('register-message-biz');
        msgDiv.className = 'mt-3 alert d-none';
        const formData = new FormData(event.target);

        try {
            const response = await fetch('./api/user_register.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                msgDiv.textContent = 'Cadastro realizado! Redirecionando para o login...';
                msgDiv.className = 'mt-3 alert alert-success';
                setTimeout(() => window.location.href = 'login.php', 2000);
            } else {
                msgDiv.textContent = result.message || 'Erro no cadastro.';
                msgDiv.className = 'mt-3 alert alert-danger';
            }
        } catch (error) {
            console.error('Erro de registro BIZ:', error);
            msgDiv.textContent = 'Erro de conexão. Tente novamente.';
            msgDiv.className = 'mt-3 alert alert-danger';
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>