<?php
// /meus_pedidos.php
// Gerado em: 24/05/2025, 15:40:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Meus Pedidos";
require_once 'includes/header.php';

// --- Validação de Sessão ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'consumer') {
    header("Location: login.php");
    exit;
}
// --- Fim da Validação ---
?>
<style>
    .page-header {
        background-color: var(--color-background-elevated);
        padding: 1rem;
        margin: -1rem -1rem 1.5rem -1rem; /* Estica para as bordas do content-wrapper */
        box-shadow: var(--box-shadow-light);
        position: sticky; /* Cabeçalho fixo no topo ao rolar */
        top: 0;
        z-index: 100;
    }
    .page-title {
        font-size: 1.125rem; /* 18px */
        font-weight: 600;
        margin-bottom: 0;
        text-align: center;
    }
    .order-card {
        margin-bottom: 1rem; /* Espaço entre os cards de pedido */
        border-radius: var(--border-radius-large);
        background-color: var(--color-background-elevated);
        box-shadow: var(--box-shadow-light);
        transition: box-shadow 0.2s ease;
    }
    .order-card:hover {
        box-shadow: var(--box-shadow-medium);
    }
    .order-card .card-body {
        padding: 1rem; /* 16px */
    }
    .order-card .order-title {
        font-size: 1.125rem; /* 18px */
        font-weight: 600;
        color: var(--color-text-primary);
        margin-bottom: 0.25rem;
    }
    .order-card .business-name {
        font-size: 0.9375rem; /* 15px */
        color: var(--color-text-secondary);
        margin-bottom: 0.75rem;
    }
    .order-card .order-detail {
        font-size: 0.875rem; /* 14px */
        color: var(--color-text-secondary);
        margin-bottom: 0.3rem;
        display: flex; /* Para alinhar ícone e texto */
        align-items: center;
    }
    .order-card .order-detail i {
        color: var(--color-text-placeholder);
        margin-right: 0.5rem;
        font-size: 1rem;
    }
    .order-card .order-detail strong {
        color: var(--color-text-primary);
        font-weight: 500;
    }
    .order-card .reservation-code {
        font-weight: 700;
        color: var(--color-primary-red);
        font-size: 1rem; /* 16px */
    }
    .order-card .order-status .badge {
        font-size: 0.8rem; /* 13px */
        padding: 0.5em 0.75em;
        font-weight: 600;
    }
    .order-card .order-price {
        font-size: 1.125rem; /* 18px */
        font-weight: 700;
        color: var(--color-text-primary);
        text-align: right;
    }
    .empty-orders-placeholder {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--color-text-secondary);
    }
    .empty-orders-placeholder i {
        font-size: 3rem;
        color: var(--color-text-placeholder);
        margin-bottom: 1rem;
    }
    .loading-placeholder { /* Reutilizando estilo do dashboard */
        text-align: center;
        padding: 3rem 0;
        color: var(--color-text-secondary);
    }
    .loading-placeholder .spinner-border {
        width: 2.5rem;
        height: 2.5rem;
        color: var(--color-primary-yellow);
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Meus Pedidos</h2>
    </div>

    <div id="orders-list">
        <div class="loading-placeholder">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2">Buscando seus pedidos...</p>
        </div>
    </div>
</div>

<?php require_once 'includes/nav_consumidor.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ordersListDiv = document.getElementById('orders-list');

    /**
     * Função genérica para chamadas Fetch API.
     * @param {string} url URL da API.
     * @param {object} options Opções do Fetch (method, body, etc.).
     * @returns {Promise<object>} O resultado da API em JSON.
     * @throws {Error} Lança um erro se a resposta não for OK ou se o JSON for inválido.
     */
    async function apiCall(url, options = {}) {
        try {
            const response = await fetch(url, options);

            if (response.status === 403 || response.status === 401) {
                alert("Sessão expirada ou acesso negado. Por favor, faça login novamente.");
                window.location.href = 'login.php';
                throw new Error("Acesso Negado/Sessão Expirada"); // Interrompe execução
            }

            // Tenta obter o JSON. Se a resposta não for OK, um erro será lançado.
            // Se a resposta for OK mas não for JSON, response.json() lançará um erro.
            const result = await response.json();

            if (!response.ok) {
                // Se a API retornou um JSON de erro com uma mensagem, usa essa mensagem.
                // Caso contrário, usa o statusText.
                throw new Error(result.message || `Erro HTTP: ${response.status} ${response.statusText}`);
            }
            return result; // Retorna o JSON (que deve ter 'status' e 'data' ou 'message')
        } catch (error) {
            console.error(`Erro na chamada API para ${url}:`, error.message);
            // Propaga o erro para ser tratado na função que chamou apiCall.
            // É importante que a função chamadora espere um erro aqui.
            throw error;
        }
    }

    async function fetchMyOrders() {
        ordersListDiv.innerHTML = `
            <div class="loading-placeholder">
                <div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div>
                <p class="mt-2">Buscando seus pedidos...</p>
            </div>`;
        try {
            // A apiCall vai lançar um erro se a resposta não for OK, se o JSON for inválido,
            // ou se o status for 401/403.
            const result = await apiCall('./api/get_my_orders.php');

            // Se chegamos aqui, a chamada foi "OK" e temos um JSON.
            // Agora verificamos o 'status' DENTRO do JSON.
            if (result && result.status === 'success') { // Verifica se result e result.status existem
                renderOrders(result.data || []); // Passa array vazio se data for undefined
            } else {
                // A API retornou um JSON, mas com status de erro interno dela, ou result é inesperado
                ordersListDiv.innerHTML = `<p class="text-danger text-center p-3">${(result && result.message) ? result.message : 'Nenhum pedido encontrado ou falha ao carregar.'}</p>`;
            }
        } catch (error) {
            // Este catch pega erros da apiCall (rede, JSON inválido, status HTTP não-OK, 401/403)
            console.error("Erro capturado em fetchMyOrders:", error.message);
            // Se o erro for de "Acesso Negado", o redirecionamento já ocorreu na apiCall.
            // Para outros erros, exibe a mensagem.
            if (error.message !== "Acesso Negado/Sessão Expirada") {
                ordersListDiv.innerHTML = `<p class="text-danger text-center p-3">Erro ao carregar pedidos: ${error.message}</p>`;
            }
        }
    }

    function renderOrders(orders) {
        ordersListDiv.innerHTML = '';
        if (orders.length === 0) {
            ordersListDiv.innerHTML = `
                <div class="empty-orders-placeholder">
                    <i class="bi bi-bag-x"></i>
                    <h5>Você ainda não fez nenhum pedido.</h5>
                    <p>Explore as ofertas e salve sua primeira sacola!</p>
                    <a href="dashboard_consumidor.php" class="btn btn-primary-red">Ver Ofertas</a>
                </div>`;
            return;
        }

        orders.forEach(order => {
            const item = document.createElement('div');
            item.className = 'card order-card';
            const price = parseFloat(order.price).toFixed(2).replace('.', ',');
            let statusBadgeClass = '';
            let statusText = order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1);

            switch(order.order_status) {
                case 'reserved': statusBadgeClass = 'bg-warning text-dark'; statusText = 'Reservado'; break;
                case 'collected': statusBadgeClass = 'bg-success'; statusText = 'Coletado'; break;
                case 'cancelled': statusBadgeClass = 'bg-danger'; statusText = 'Cancelado'; break;
                default: statusBadgeClass = 'bg-secondary';
            }

            item.innerHTML = `
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="order-title">${order.title}</h5>
                            <p class="business-name mb-0">${order.business_name}</p>
                        </div>
                        <div class="order-status">
                            <span class="badge ${statusBadgeClass}">${statusText}</span>
                        </div>
                    </div>
                    <p class="order-detail"><i class="bi bi-receipt"></i><strong>Código:</strong> <span class="reservation-code">${order.reservation_code}</span></p>
                    <p class="order-detail"><i class="bi bi-geo-alt"></i><strong>Endereço:</strong> ${order.address}</p>
                    <p class="order-detail"><i class="bi bi-calendar-check"></i><strong>Data do Pedido:</strong> ${order.order_date_formatted}</p>
                    <p class="order-detail"><i class="bi bi-clock-history"></i><strong>Período de Retirada:</strong> ${order.pickup_window}</p>
                    <hr class="my-2">
                    <div class="d-flex justify-content-end align-items-center">
                        <p class="order-price mb-0">R$ ${price}</p>
                    </div>
                </div>
            `;
            ordersListDiv.appendChild(item);
        });
    }
    fetchMyOrders();
});
</script>

<?php require_once 'includes/footer.php'; ?>
