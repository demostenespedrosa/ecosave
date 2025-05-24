<?php
$page_title = "EcoSave - Cadastro Consumidor";
require_once 'includes/header.php';

// Se já estiver logado, redireciona
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<div class="card shadow-sm">
    <div class="card-body p-5">
        <h2 class="text-center mb-4" style="color: #FF4500;">Cadastro de Consumidor</h2>
        <form id="register-form">
            <input type="hidden" name="user_type" value="consumer"> <div class="form-floating mb-3">
                <input type="text" class="form-control" id="register-name" name="name" placeholder="Seu Nome" required>
                <label for="register-name">Nome Completo</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="register-email" name="email" placeholder="nome@exemplo.com" required>
                <label for="register-email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="register-password" name="password" placeholder="Senha" required>
                <label for="register-password">Senha (mín. 6 caracteres)</label>
            </div>
            <button class="w-100 btn btn-lg btn-warning" type="submit" style="background-color: #FFC107; color: #333;">Cadastrar</button>
            <p class="mt-3 text-center">Já tem conta? <a href="login.php">Faça Login</a></p>
            <div id="register-message" class="mt-3 alert d-none"></div>
        </form>
    </div>
</div>

<script>
    document.getElementById('register-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const msgDiv = document.getElementById('register-message');
        msgDiv.className = 'mt-3 alert d-none';

        const formData = new FormData(event.target); // Pega todos os dados do form

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
            console.error('Erro de registro:', error);
            msgDiv.textContent = 'Erro de conexão. Tente novamente.';
            msgDiv.className = 'mt-3 alert alert-danger';
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>