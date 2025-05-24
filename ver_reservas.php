<?php
// /ver_reservas.php
// Gerado em: 24/05/2025, 14:35:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Reservas Recebidas";
require_once 'includes/header.php';

// --- Validação de Sessão ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'business' || !isset($_SESSION['business_id'])) {
    header("Location: login.php");
    exit;
}
// --- Fim da Validação ---
?>
<style>
    .page-header { /* Reutilizando estilo */
        background-color: var(--color-background-elevated); padding: 1rem;
        margin: -1rem -1rem 1.5rem -1rem; box-shadow: var(--box-shadow-light);
        position: sticky; top: 0; z-index: 100;
    }
    .page-title { font-size: 1.125rem; font-weight: 600; margin-bottom: 0; text-align: center; }

    .reservation-table {
        background-color: var(--color-background-elevated);
        border-radius: var(--border-radius-large);
        box-shadow: var(--box-shadow-light);
        overflow: hidden; /* Para o border-radius da tabela */
    }
    .reservation-table th {
        background-color: #f8f9fa; /* Um cinza leve para o cabeçalho da tabela */
        font-weight: 600;
        font-size: 0.875rem; /* 14px */
        color: var(--color-text-primary);
        padding: 0.75rem 1rem; /* 12px 16px */
    }
    .reservation-table td {
        font-size: 0.9375rem; /* 15px */
        vertical-align: middle;
        padding: 0.75rem 1rem;
    }
    .reservation-table .badge { font-size: 0.8rem; padding: 0.4em 0.6em; }
    .reservation-table .btn-collect { font-size: 0.875rem; padding: 0.375rem 0.75rem; }
    .reservation-table .btn-collect i { margin-right: 0.25rem; }

    .empty-reservations-placeholder {
        text-align: center; padding: 3rem 1rem; color: var(--color-text-secondary);
    }
    .empty-reservations-placeholder i {
        font-size: 3rem; color: var(--color-text-placeholder); margin-bottom: 1rem;
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Reservas Recebidas</h2>
    </div>

    <div id="business-reservations-list" class="table-responsive reservation-table">
        <div class="loading-placeholder p-4">
            <div class="spinner-border" style="color: var(--color-primary-yellow);" role="status"></div>
            <p class="mt-2">Carregando reservas...</p>
        </div>
    </div>
</div>

<?php require_once 'includes/nav_estabelecimento.php'; ?>
<script src="js/business.js?v=<?php echo time(); ?>"></script>
<?php require_once 'includes/footer.php'; ?>
