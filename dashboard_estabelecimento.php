<?php
// /dashboard_estabelecimento.php
// Gerado em: 24/05/2025, 14:35:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Painel";
require_once 'includes/header.php';

// --- Validação de Sessão ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'business' || !isset($_SESSION['business_id'])) {
    header("Location: login.php");
    exit;
}
$user_name = $_SESSION['user_name'] ?? 'Estabelecimento'; // Nome do responsável
$business_name_session = $_SESSION['business_name'] ?? $user_name; // Se tivermos o nome do negócio na sessão

// Poderia buscar o nome do estabelecimento do banco para garantir que está atualizado
require_once 'includes/db_connect.php';
$stmt_biz_name = $pdo->prepare("SELECT business_name FROM businesses WHERE business_id = ?");
$stmt_biz_name->execute([$_SESSION['business_id']]);
$biz_data = $stmt_biz_name->fetch();
$display_business_name = $biz_data['business_name'] ?? $business_name_session;

?>
<style>
    .dashboard-header {
        padding: 1.5rem 0; /* Padding vertical, horizontal virá do content-wrapper */
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .dashboard-header .icon-display {
        font-size: 3rem;
        color: var(--color-primary-yellow);
        margin-bottom: 0.5rem;
    }
    .dashboard-header h2 {
        font-size: 1.75rem; /* 28px */
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .dashboard-header p {
        font-size: 1rem; /* 16px */
        color: var(--color-text-secondary);
    }
    .action-grid .btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1rem; /* Mais padding interno */
        height: 150px; /* Altura fixa para os botões de ação */
        font-size: 1rem; /* 16px */
        font-weight: 500;
        text-align: center;
        border-radius: var(--border-radius-large);
        box-shadow: var(--box-shadow-light);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .action-grid .btn:hover {
        transform: translateY(-5px);
        box-shadow: var(--box-shadow-medium);
    }
    .action-grid .btn i {
        font-size: 2.5rem; /* 40px */
        margin-bottom: 0.75rem; /* 12px */
    }
    .btn-manage-offers {
        background-color: var(--color-primary-red);
        color: white;
    }
    .btn-manage-offers:hover {
        background-color: var(--color-primary-red-dark);
        color: white;
    }
    .btn-view-reservations {
        background-color: var(--color-accent-orange);
        color: white;
    }
    .btn-view-reservations:hover {
        background-color: #E88000; /* Laranja mais escuro */
        color: white;
    }
    .btn-view-profile, .btn-view-stats {
        background-color: var(--color-background-elevated);
        color: var(--color-text-primary);
        border: 1px solid var(--color-separator-light);
    }
    .btn-view-profile:hover, .btn-view-stats:hover {
        background-color: #f8f9fa; /* Um cinza muito leve */
    }

</style>

<div class="content-wrapper">
    <div class="dashboard-header">
        <div class="icon-display"><i class="bi bi-shop-window"></i></div>
        <h2><?php echo htmlspecialchars($display_business_name); ?></h2>
        <p>Bem-vindo(a) ao seu painel EcoSave!</p>
    </div>

    <div class="row g-3 action-grid">
        <div class="col-6">
            <a href="gerenciar_ofertas.php" class="btn btn-manage-offers w-100">
                <i class="bi bi-tags-fill"></i>
                Gerenciar Ofertas
            </a>
        </div>
        <div class="col-6">
            <a href="ver_reservas.php" class="btn btn-view-reservations w-100">
                <i class="bi bi-card-checklist"></i>
                Ver Reservas
            </a>
        </div>
        <div class="col-6">
            <a href="perfil_estabelecimento.php" class="btn btn-view-profile w-100">
                <i class="bi bi-person-gear"></i>
                Meu Perfil
            </a>
        </div>
        <div class="col-6">
            <a href="#" class="btn btn-view-stats w-100 disabled">
                <i class="bi bi-graph-up"></i>
                Estatísticas
            </a>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body text-center">
            <h5 class="card-title fw-semibold">Dica Rápida</h5>
            <p class="text-small text-secondary">Mantenha suas ofertas atualizadas e com fotos atraentes para aumentar suas vendas e ajudar a combater o desperdício!</p>
        </div>
    </div>

</div>

<?php require_once 'includes/nav_estabelecimento.php'; ?>
<?php require_once 'includes/footer.php'; ?>
