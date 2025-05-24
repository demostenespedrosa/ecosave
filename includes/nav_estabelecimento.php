<?php
// /includes/nav_estabelecimento.php
// Gerado em: 24/05/2025, 11:45:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar fixed-bottom app-bottom-nav" id="bottom-nav-business">
    <div class="container-fluid d-flex justify-content-around">
        <a class="nav-link text-center <?php echo ($current_page == 'dashboard_estabelecimento.php') ? 'active' : ''; ?>" href="dashboard_estabelecimento.php">
            <i class="bi bi-speedometer2"></i>
            <span>Painel</span>
        </a>
        <a class="nav-link text-center <?php echo ($current_page == 'gerenciar_ofertas.php') ? 'active' : ''; ?>" href="gerenciar_ofertas.php">
            <i class="bi bi-tags-fill"></i>
            <span>Ofertas</span>
        </a>
        <a class="nav-link text-center <?php echo ($current_page == 'ver_reservas.php') ? 'active' : ''; ?>" href="ver_reservas.php">
            <i class="bi bi-card-checklist"></i>
            <span>Reservas</span>
        </a>
        <a class="nav-link text-center <?php echo ($current_page == 'perfil_estabelecimento.php') ? 'active' : ''; ?>" href="perfil_estabelecimento.php">
            <i class="bi bi-person-fill"></i>
            <span>Perfil</span>
        </a>
    </div>
</nav>
