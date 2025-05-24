<?php
// /perfil_consumidor.php
// Gerado em: 24/05/2025, 14:20:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Meu Perfil";
require_once 'includes/header.php';

// --- Validação de Sessão ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'consumer') {
    header("Location: login.php");
    exit;
}

require_once 'includes/db_connect.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name, email, address, number, neighborhood, city, cep FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$user_name = $user['name'] ?? 'Consumidor';
$user_email = $user['email'] ?? 'email@dominio.com';
$user_address_display = 'Endereço não cadastrado';
if ($user && !empty($user['address'])) {
    $user_address_display = htmlspecialchars($user['address'] . ', ' . $user['number']);
    if (!empty($user['neighborhood'])) $user_address_display .= ' - ' . htmlspecialchars($user['neighborhood']);
    if (!empty($user['city'])) $user_address_display .= '<br>' . htmlspecialchars($user['city']);
    if (!empty($user['cep'])) $user_address_display .= ' - CEP: ' . htmlspecialchars($user['cep']);
}

?>
<style>
    .profile-page-header {
        background-color: var(--color-background-elevated); /* Fundo branco para o header da página */
        padding: 1rem;
        text-align: center;
        margin: -1rem -1rem 0 -1rem; /* Estica para as bordas do content-wrapper */
        border-bottom: 1px solid var(--color-separator-light);
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .profile-page-title {
        font-size: 1.125rem; /* 18px */
        font-weight: 600; /* Semibold, padrão iOS para títulos de navegação */
        margin-bottom: 0;
    }
    .profile-info-card {
        background-color: var(--color-background-elevated);
        border-radius: var(--border-radius-large);
        padding: 1.5rem; /* 24px */
        text-align: center;
        margin-top: 1.5rem; /* Espaço do header da página */
        box-shadow: var(--box-shadow-light);
    }
    .profile-avatar-initials {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: var(--color-primary-red);
        color: white;
        font-size: 2.5rem; /* 40px */
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
    }
    .profile-name {
        font-size: 1.5rem; /* 24px */
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: 0.25rem;
    }
    .profile-email {
        font-size: 1rem; /* 16px */
        color: var(--color-text-secondary);
        margin-bottom: 1.5rem;
    }

    .profile-options-list .list-group-item {
        padding-top: 1rem; /* 16px */
        padding-bottom: 1rem;
        font-size: 1rem; /* 16px */
        font-weight: 500;
    }
    .profile-options-list .list-group-item i {
        font-size: 1.25rem; /* 20px */
    }
    .profile-options-list .list-group-item .bi-chevron-right { /* Ícone de seta */
        color: var(--color-text-placeholder);
        font-size: 1rem;
    }
    .btn-logout {
        margin-top: 2rem;
        font-weight: 500;
    }
    .list-group { /* Para remover bordas do grupo se o card já tiver */
        border-radius: var(--border-radius-large);
        overflow: hidden; /* Garante que os itens filhos respeitem o border-radius */
    }
</style>

<div class="content-wrapper">
    <div class="profile-page-header">
        <h2 class="profile-page-title">Meu Perfil</h2>
    </div>

    <div class="profile-info-card">
        <div class="profile-avatar-initials">
            <?php echo strtoupper(substr($user_name, 0, 1)); // Pega a primeira letra do nome ?>
        </div>
        <h4 class="profile-name"><?php echo htmlspecialchars($user_name); ?></h4>
        <p class="profile-email"><?php echo htmlspecialchars($user_email); ?></p>
    </div>

    <div class="list-group profile-options-list mt-3 shadow-card">
        <a href="meus_pedidos.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div><i class="bi bi-receipt"></i> Meus Pedidos</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
            <div><i class="bi bi-pencil-square"></i> Editar Perfil</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
            <div><i class="bi bi-geo-alt-fill"></i> Meus Endereços</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
            <div><i class="bi bi-heart-fill"></i> Favoritos</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
            <div><i class="bi bi-shield-lock-fill"></i> Segurança e Privacidade</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
            <div><i class="bi bi-question-circle-fill"></i> Ajuda e Suporte</div>
            <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <a href="logout.php" class="btn btn-subtle w-100 btn-logout">
        <i class="bi bi-box-arrow-right"></i> Sair da Conta
    </a>
</div>

<?php require_once 'includes/nav_consumidor.php'; ?>
<?php require_once 'includes/footer.php'; ?>
