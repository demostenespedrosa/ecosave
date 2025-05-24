<?php
// /perfil_estabelecimento.php
// Gerado em: 24/05/2025, 14:35:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Perfil do Estabelecimento";
require_once 'includes/header.php';

// --- Validação de Sessão ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'business' || !isset($_SESSION['business_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/db_connect.php';
$business_id = $_SESSION['business_id'];
$stmt = $pdo->prepare("SELECT b.business_name, b.address, b.number, b.neighborhood, b.city, b.cep, b.phone, u.email 
                       FROM businesses b 
                       JOIN users u ON b.user_id = u.user_id 
                       WHERE b.business_id = ?");
$stmt->execute([$business_id]);
$biz = $stmt->fetch(PDO::FETCH_ASSOC);

$biz_name = $biz['business_name'] ?? 'Meu Estabelecimento';
$user_email = $biz['email'] ?? 'email@dominio.com'; // Email do usuário associado
$biz_address_display = 'Endereço não cadastrado';
if ($biz && !empty($biz['address'])) {
    $biz_address_display = htmlspecialchars($biz['address'] . ', ' . $biz['number']);
    if (!empty($biz['neighborhood'])) $biz_address_display .= ' - ' . htmlspecialchars($biz['neighborhood']);
    if (!empty($biz['city'])) $biz_address_display .= '<br>' . htmlspecialchars($biz['city']);
    if (!empty($biz['cep'])) $biz_address_display .= ' - CEP: ' . htmlspecialchars($biz['cep']);
}
$biz_phone_display = $biz['phone'] ? htmlspecialchars($biz['phone']) : 'Telefone não cadastrado';

?>
<style>
    /* Estilos já definidos em perfil_consumidor.php e style.css podem ser reutilizados.
       Se houver necessidade de estilos específicos para esta página, adicione aqui.
       Por exemplo, a cor do avatar pode ser diferente. */
    .profile-avatar-initials.business {
        background-color: var(--color-accent-orange); /* Laranja para diferenciar */
    }
    .profile-options-list .list-group-item i {
        color: var(--color-accent-orange); /* Ícones em laranja */
    }
</style>

<div class="content-wrapper">
    <div class="profile-page-header"> <h2 class="profile-page-title">Perfil do Estabelecimento</h2>
    </div>

    <div class="profile-info-card"> <div class="profile-avatar-initials business"> <i class="bi bi-shop-window" style="font-size: 2rem;"></i> </div>
        <h4 class="profile-name"><?php echo htmlspecialchars($biz_name); ?></h4>
        <p class="profile-email"><?php echo htmlspecialchars($user_email); ?></p>
    </div>

    <div class="list-group profile-options-list mt-3 shadow-card">
        <a href="dashboard_estabelecimento.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div><i class="bi bi-speedometer2"></i> Painel Principal</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="gerenciar_ofertas.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div><i class="bi bi-tags-fill"></i> Gerenciar Minhas Ofertas</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="ver_reservas.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div><i class="bi bi-card-checklist"></i> Ver Reservas Recebidas</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
            <div><i class="bi bi-pencil-square"></i> Editar Dados do Estabelecimento</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <div class="list-group-item"> <div><i class="bi bi-geo-alt-fill"></i> <?php echo $biz_address_display; ?></div>
        </div>
        <div class="list-group-item">
            <div><i class="bi bi-telephone-fill"></i> <?php echo $biz_phone_display; ?></div>
        </div>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
            <div><i class="bi bi-graph-up-arrow"></i> Relatórios e Estatísticas</div>
            <i class="bi bi-chevron-right"></i>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
            <div><i class="bi bi-shield-lock-fill"></i> Segurança da Conta</div>
            <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <a href="logout.php" class="btn btn-subtle w-100 btn-logout">
        <i class="bi bi-box-arrow-right"></i> Sair da Conta
    </a>
</div>

<?php require_once 'includes/nav_estabelecimento.php'; ?>
<?php require_once 'includes/footer.php'; ?>
