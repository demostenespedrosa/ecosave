<?php
// /detalhe_oferta.php
// Gerado em: 24/05/2025, 18:15:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Detalhes da Oferta";
require_once 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'consumer') {
    header("Location: login.php");
    exit;
}

$offer_id_from_url = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$offer_id_from_url) {
    echo "<div class='content-wrapper p-5 text-center'><div class='alert alert-danger'>Oferta não especificada ou ID inválido.</div><a href='dashboard_consumidor.php' class='btn btn-primary-red mt-3'>Voltar ao Início</a></div>";
    require_once 'includes/footer.php';
    exit;
}
?>
<style>
    /* --- Estilos Gerais da Página (mantidos e ajustados) --- */
    .offer-detail-page { padding-bottom: calc(80px + env(safe-area-inset-bottom)); padding-top: 56px; }
    .detail-page-top-nav {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 0.75rem; height: 56px;
        background-color: rgba(249, 249, 249, 0.9); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        border-bottom: 0.5px solid var(--color-separator-light);
        position: fixed; top: 0; left: 0; right: 0; z-index: 1030;
    }
    .detail-page-top-nav .back-button {
        font-size: 1rem; color: var(--color-primary-red); text-decoration: none;
        display: flex; align-items: center; font-weight: 500;
        padding: 0.5rem 0.75rem; margin-left: -0.75rem;
    }
    .detail-page-top-nav .back-button i { font-size: 1.5rem; margin-right: 0.125rem; }
    .detail-page-top-nav .page-title-dynamic {
        font-size: 1.0625rem; font-weight: 600; color: var(--color-text-primary);
        position: absolute; left: 50%; transform: translateX(-50%);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 60%;
    }
    .detail-page-top-nav .action-placeholder-right { width: 60px; height: 10px; }
    .offer-image-hero { width: 100%; height: 300px; object-fit: cover; background-color: #e9ecef; }
    .offer-content {
        background-color: var(--color-background-elevated);
        border-radius: var(--border-radius-large) var(--border-radius-large) 0 0;
        margin-top: -20px; position: relative; z-index: 10;
        padding: 1.5rem; box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
    }
    .offer-title-main { font-size: 1.75rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 0.5rem; }
    .business-name-link { font-size: 1.125rem; font-weight: 500; color: var(--color-text-secondary); margin-bottom: 1.5rem; display: block; }
    .business-name-link i { margin-right: 0.3rem; }
    .detail-section { margin-bottom: 1.5rem; }
    .detail-section h5 { font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7; }
    .detail-section p, .detail-section ul li { font-size: 1rem; color: var(--color-text-secondary); margin-bottom: 0.3rem; }
    .detail-section p i, .detail-section ul li i { color: var(--color-primary-red); margin-right: 0.75rem; width: 20px; text-align: center; }
    .detail-section ul { list-style: none; padding-left: 0; }
    .fixed-bottom-action {
        position: fixed; bottom: 0; left: 0; right: 0;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        padding: 1rem; padding-bottom: calc(1rem + env(safe-area-inset-bottom));
        border-top: 1px solid var(--color-separator-light);
        box-shadow: 0 -2px 10px rgba(0,0,0,0.08);
        z-index: 1000; display: flex; justify-content: space-between; align-items: center;
    }
    .fixed-bottom-action .price-display { font-size: 1.75rem; font-weight: 700; color: var(--color-primary-red); }
    .fixed-bottom-action .btn-reserve { padding-left: 2rem; padding-right: 2rem; }
    .loading-placeholder-detail {
        min-height: 50vh; /* Ajustado */
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        color: var(--color-text-secondary);
    }
    .loading-placeholder-detail .spinner-border { width: 3rem; height: 3rem; color: var(--color-primary-yellow); }
    #reserve-message-page .alert { margin-bottom: 0; }
</style>

<div class="detail-page-top-nav">
    <a href="#" class="back-button" id="backButton"><i class="bi bi-chevron-left"></i> Voltar</a>
    <h1 class="page-title-dynamic" id="offerPageTitle">Detalhes</h1>
    <div class="action-placeholder-right"></div>
</div>

<div class="offer-detail-page">
    <img src="https://via.placeholder.com/600x300.png/f0f0f0/cccccc?Text=Carregando..." alt="Carregando oferta..." class="offer-image-hero" id="offer-detail-image">
    <div class="offer-content">
        <div id="offer-details-container">
            <div class="loading-placeholder-detail">
                <div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div>
                <p class="mt-3">A carregar os detalhes da oferta...</p>
            </div>
        </div>
    </div>
</div>

<div class="fixed-bottom-action">
    <span class="price-display" id="offer-detail-price">R$ --,--</span>
    <button type="button" class="btn btn-primary-red btn-lg btn-reserve" id="reserveButtonPage" disabled>
        <i class="bi bi-bag-check-fill"></i> Reservar
    </button>
</div>
<div id="reserve-message-page" class="position-fixed start-50 translate-middle-x p-2" style="bottom: calc(70px + env(safe-area-inset-bottom) + 1rem); z-index: 1100; min-width: 300px; max-width: 90%;"></div>

<?php require_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const offerIdFromPHP = <?php echo json_encode($offer_id_from_url); ?>;
    const detailsContainer = document.getElementById('offer-details-container');
    const offerImageElement = document.getElementById('offer-detail-image');
    const priceDisplayElement = document.getElementById('offer-detail-price');
    const reserveButtonPage = document.getElementById('reserveButtonPage');
    const reserveMessagePage = document.getElementById('reserve-message-page');
    const backButton = document.getElementById('backButton');
    const offerPageTitleElement = document.getElementById('offerPageTitle');

    if (backButton) {
        backButton.addEventListener('click', (e) => {
            e.preventDefault();
            window.history.back(); // Ou window.location.href = 'dashboard_consumidor.php';
        });
    }

    function showPageMessage(message, type = 'danger') {
        const alertId = `alert-${Date.now()}`;
        const alertDiv = document.createElement('div');
        alertDiv.id = alertId;
        alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
        reserveMessagePage.innerHTML = '';
        reserveMessagePage.appendChild(alertDiv);
        setTimeout(() => {
            const activeAlert = document.getElementById(alertId);
            if (activeAlert) {
                const alertInstance = bootstrap.Alert.getInstance(activeAlert);
                if (alertInstance) alertInstance.close();
                else activeAlert.remove();
            }
        }, 4000);
    }

    async function apiCall(url, options = {}) {
        console.log(`[apiCall] Chamando: ${url}`, options);
        try {
            const response = await fetch(url, options);
            console.log(`[apiCall] Status para ${url}: ${response.status}`);

            if (response.status === 403 || response.status === 401) {
                alert("Sessão expirada ou acesso negado. Faça login novamente.");
                window.location.href = 'login.php';
                throw new Error("Acesso Negado/Sessão Expirada");
            }

            const responseText = await response.text();
            console.log(`[apiCall] Resposta (texto) para ${url}:`, responseText.substring(0, 500) + (responseText.length > 500 ? "..." : ""));

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error("[apiCall] Falha ao processar JSON da API:", e);
                console.error("[apiCall] Resposta recebida (texto completo):", responseText);
                throw new Error(`Resposta inválida da API (não é JSON). Status: ${response.status} ${response.statusText}.`);
            }
            
            if (!response.ok) {
                throw new Error(result.message || `Erro HTTP: ${response.status} ${response.statusText}`);
            }
            return result;
        } catch (error) {
            console.error(`[apiCall] Erro final na chamada API para ${url}:`, error.message);
            throw error;
        }
    }

    function renderOfferDetailsPage(offer) {
        console.log("[renderOfferDetailsPage] Renderizando oferta:", offer);
        document.title = `${offer.title || 'Detalhes'} - EcoSave`;
        if (offerPageTitleElement) {
            offerPageTitleElement.textContent = offer.title || 'Detalhes da Oferta';
        }
        offerImageElement.src = offer.image_url || 'https://via.placeholder.com/600x300.png/f0f0f0/cccccc?Text=Imagem+Indispon%C3%ADvel';
        offerImageElement.alt = offer.title || 'Imagem da oferta';
        priceDisplayElement.textContent = `R$ ${parseFloat(offer.price).toFixed(2).replace('.', ',')}`;

        detailsContainer.innerHTML = `
            <h1 class="offer-title-main">${offer.title || 'Oferta Sem Título'}</h1>
            <a href="#" class="business-name-link text-decoration-none">
                <i class="bi bi-shop"></i> ${offer.business_name || 'Estabelecimento Desconhecido'}
            </a>
            <div class="detail-section">
                <h5><i class="bi bi-info-circle-fill"></i> Sobre esta oferta</h5>
                <p>${offer.description || 'Nenhuma descrição detalhada fornecida.'}</p>
            </div>
            <div class="detail-section">
                <h5><i class="bi bi-clock-history"></i> Coleta</h5>
                <p><i class="bi bi-calendar-event"></i> ${offer.pickup_window || 'Horário não definido'}</p>
                <p><i class="bi bi-geo-alt"></i> ${offer.address || 'Endereço não informado'}, ${offer.city || ''}</p>
            </div>
            <div class="detail-section">
                <h5><i class="bi bi-box-seam"></i> Disponibilidade</h5>
                <p><i class="bi bi-boxes"></i> ${offer.quantity_available !== undefined ? offer.quantity_available : '?'} unidade(s) restante(s).</p>
            </div>
            ${(offer.avg_rating > 0 || offer.rating_count > 0) ? `
            <div class="detail-section">
                <h5><i class="bi bi-star-fill"></i> Avaliação do Estabelecimento</h5>
                <p><i class="bi bi-star-half"></i> ${parseFloat(offer.avg_rating).toFixed(1)} de 5 (${offer.rating_count} avaliações)</p>
            </div>` : ''}
            ${offer.phone ? `
            <div class="detail-section">
                <h5><i class="bi bi-telephone-fill"></i> Contato</h5>
                <p><i class="bi bi-telephone"></i> ${offer.phone}</p>
            </div>` : ''}
        `;
        reserveButtonPage.disabled = !(offer.quantity_available > 0 && offer.status === 'active');
    }

    async function fetchFullOfferDetails() {
        console.log("[fetchFullOfferDetails] Buscando detalhes para ID:", offerIdFromPHP);
        if (!offerIdFromPHP) {
            detailsContainer.innerHTML = "<p class='text-danger text-center p-3'>ID da oferta inválido na URL.</p>";
            if(offerPageTitleElement) offerPageTitleElement.textContent = "Erro";
            offerImageElement.src = 'https://via.placeholder.com/600x300.png/f0f0f0/cccccc?Text=Erro';
            priceDisplayElement.textContent = 'Erro';
            return;
        }
        detailsContainer.innerHTML = `
            <div class="loading-placeholder-detail">
                <div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div>
                <p class="mt-3">A carregar os detalhes da oferta...</p>
            </div>`;

        try {
            const result = await apiCall(`./api/get_offer_details.php?id=${offerIdFromPHP}`);
            console.log("[fetchFullOfferDetails] Resultado da API:", result);
            if (result && result.status === 'success' && result.data) {
                renderOfferDetailsPage(result.data);
            } else {
                const errorMessage = (result && result.message) ? result.message : 'Não foi possível carregar os detalhes da oferta.';
                console.error("[fetchFullOfferDetails] Erro no status da API ou dados inválidos:", errorMessage, result);
                detailsContainer.innerHTML = `<p class="text-danger text-center p-3">${errorMessage}</p>`;
                if(offerPageTitleElement) offerPageTitleElement.textContent = "Erro";
                offerImageElement.src = 'https://via.placeholder.com/600x300.png/f0f0f0/cccccc?Text=Erro+ao+Carregar';
                priceDisplayElement.textContent = 'Erro';
            }
        } catch (error) {
            console.error("[fetchFullOfferDetails] Erro capturado:", error.message);
            if (error.message !== "Acesso Negado/Sessão Expirada") {
                detailsContainer.innerHTML = `<p class="text-danger text-center p-3">Erro crítico ao carregar detalhes: ${error.message}</p>`;
                if(offerPageTitleElement) offerPageTitleElement.textContent = "Erro";
                offerImageElement.src = 'https://via.placeholder.com/600x300.png/f0f0f0/cccccc?Text=Erro+Grave';
                priceDisplayElement.textContent = 'Erro';
            }
        }
    }

    async function reserveOfferOnPage() {
        console.log("[reserveOfferOnPage] Tentando reservar oferta ID:", offerIdFromPHP);
        if (!offerIdFromPHP) return;
        reserveButtonPage.disabled = true;
        reserveButtonPage.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Reservando...';
        try {
            const formData = new FormData();
            formData.append('offer_id', offerIdFromPHP);
            const result = await apiCall('./api/reserve_offer.php', { method: 'POST', body: formData });
            console.log("[reserveOfferOnPage] Resultado da reserva:", result);
            if (result && result.status === 'success') {
                showPageMessage(`Reserva feita! Código: ${result.reservation_code}`, 'success');
                fetchFullOfferDetails(); // Re-busca para atualizar
            } else {
                showPageMessage((result && result.message) ? result.message : 'Erro ao reservar.');
                reserveButtonPage.disabled = false;
            }
        } catch (error) {
            console.error("[reserveOfferOnPage] Erro capturado:", error.message);
            if (error.message !== "Acesso Negado/Sessão Expirada") {
                showPageMessage(error.message);
            }
            reserveButtonPage.disabled = false;
        } finally {
            reserveButtonPage.innerHTML = '<i class="bi bi-bag-check-fill"></i> Reservar';
        }
    }

    if (reserveButtonPage) {
        reserveButtonPage.addEventListener('click', reserveOfferOnPage);
    }
    
    if (offerIdFromPHP) {
        fetchFullOfferDetails();
    } else {
        console.error("ID da oferta não encontrado na URL ou inválido ao iniciar.");
        // A mensagem de erro já deve ter sido mostrada pelo PHP, mas podemos logar.
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
