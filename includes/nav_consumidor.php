<?php
// /includes/nav_consumidor.php
// Gerado em: 24/05/2025, 11:45:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar fixed-bottom app-bottom-nav" id="bottom-nav">
    <div class="container-fluid d-flex justify-content-around">
        <a class="nav-link text-center <?php echo ($current_page == 'dashboard_consumidor.php') ? 'active' : ''; ?>" href="dashboard_consumidor.php">
            <i class="bi bi-compass-fill"></i>
            <span>Descobrir</span>
        </a>
        <a class="nav-link text-center <?php echo ($current_page == 'meus_pedidos.php') ? 'active' : ''; ?>" href="meus_pedidos.php">
            <i class="bi bi-bag-check-fill"></i> <span>Pedidos</span>
        </a>
        <a class="nav-link text-center <?php echo ($current_page == 'perfil_consumidor.php') ? 'active' : ''; ?>" href="perfil_consumidor.php">
            <i class="bi bi-person-fill"></i> <span>Perfil</span>
        </a>
    </div>
</nav>
