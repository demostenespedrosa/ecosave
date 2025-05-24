<?php
// /dashboard_consumidor.php
// Gerado em: 24/05/2025, 18:00:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Descobrir";
require_once 'includes/header.php';

// --- Validação de Sessão ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'consumer') {
    header("Location: login.php");
    exit;
}
$user_name = $_SESSION['user_name'] ?? 'Usuário';
// --- Fim da Validação ---
?>

<style>
    /* --- Estilos Gerais da Página (mantidos da versão anterior) --- */
    body { background-color: #f9f9f9; }
    .main-container { padding-top: 1rem; padding-bottom: 5rem; }
    .content-wrapper { padding: 1rem; flex-grow: 1; }
    .welcome-text { font-size: 1.375rem; font-weight: 300; color: var(--color-text-secondary); margin-bottom: 1.5rem; }
    .welcome-text strong { font-weight: 600; color: var(--color-text-primary); }
    .category-section { margin-bottom: 2rem; }
    .category-title-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
    .category-title { font-size: 1.375rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 0; }
    .category-see-all { font-size: 0.9375rem; font-weight: 500; color: var(--color-primary-red); }
    .horizontal-scroll-wrapper {
        display: flex; overflow-x: auto; white-space: nowrap;
        padding: 0.25rem 0 1rem 0; /* Ajustado para não ter padding lateral extra se content-wrapper já tem */
        -webkit-overflow-scrolling: touch; scrollbar-width: none;
    }
    .horizontal-scroll-wrapper::-webkit-scrollbar { display: none; }
    .offer-card-h {
        flex: 0 0 75%; max-width: 280px; margin-right: 1rem;
        white-space: normal; display: inline-block; vertical-align: top;
        border-radius: var(--border-radius-large); background-color: var(--color-background-elevated);
        box-shadow: var(--box-shadow-light); cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease; overflow: hidden;
    }
    .offer-card-h:last-child { margin-right: 0; }
    .offer-card-h:hover { transform: translateY(-4px); box-shadow: var(--box-shadow-medium); }
    .offer-card-h .card-img-top-wrapper { width: 100%; height: 150px; overflow: hidden; }
    .offer-card-h .card-img-top { width: 100%; height: 100%; object-fit: cover; }
    .offer-card-h .card-body { padding: 0.875rem; }
    .offer-card-h .card-title { font-size: 1rem; font-weight: 600; color: var(--color-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.25rem; }
    .offer-card-h .card-text.business-name { font-size: 0.875rem; color: var(--color-text-secondary); margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .offer-card-h .card-price { font-size: 1.25rem; font-weight: 700; color: var(--color-primary-red); }
    .offer-card-h .card-details-row { display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; }
    .offer-card-h .card-pickup-time, .offer-card-h .card-quantity-badge { font-size: 0.75rem; color: var(--color-text-secondary); }
    .offer-card-h .card-quantity-badge .badge { font-size: 0.75rem; padding: 0.3em 0.5em; font-weight: 500; }
    .offer-card-h .card-rating-badge .badge { background-color: var(--color-primary-yellow) !important; color: var(--color-text-primary) !important; font-weight: 600; }
    .loading-placeholder { text-align: center; padding: 3rem 0; color: var(--color-text-secondary); }
    .loading-placeholder .spinner-border { width: 3rem; height: 3rem; color: var(--color-primary-yellow); }
    .modal-content { border-radius: var(--border-radius-large); border: none; }
    .modal-header { border-bottom: 1px solid var(--color-separator-light); padding: 1rem 1.25rem; }
    .modal-header .modal-title { font-size: 1.25rem; font-weight: 600; }
    .modal-body { padding: 1.25rem; }
    .modal-body .img-fluid { border-radius: var(--border-radius-standard); margin-bottom: 1rem; max-height: 280px; object-fit: cover; }
    .modal-body p { font-size: 1rem; color: var(--color-text-secondary); margin-bottom: 0.75rem; }
    .modal-body p strong { color: var(--color-text-primary); font-weight: 500; }
    .modal-body p i { color: var(--color-primary-red); margin-right: 0.5rem; width: 20px; text-align: center; }
    .modal-footer { border-top: 1px solid var(--color-separator-light); padding: 1rem 1.25rem; }
    .modal-footer #modal-price { font-size: 1.5rem; }
    .no-offers-placeholder {
        text-align: center; padding: 2rem 1rem;
        background-color: var(--color-background-elevated);
        border-radius: var(--border-radius-large);
        box-shadow: var(--box-shadow-light); margin-top: 2rem;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .no-offers-placeholder .lottie-animation-wrapper { width: 180px; height: 180px; margin-bottom: 1rem; }
    .no-offers-placeholder h4 { font-size: 1.25rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.5rem; }
    .no-offers-placeholder p { font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 1.25rem; max-width: 320px; margin-left: auto; margin-right: auto; }
    .no-offers-placeholder .btn-refresh { font-weight: 500; padding: 0.6rem 1.5rem; }
</style>

<div class="content-wrapper">
    <h3 class="welcome-text">Olá, <strong><?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?></strong>! 👋</h3>
    <div id="categorized-offers-area">
        <div class="loading-placeholder">
            <div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div>
            <p class="mt-2">Buscando as melhores ofertas para você...</p>
        </div>
    </div>
</div>

<?php require_once 'includes/nav_consumidor.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const categorizedOffersArea = document.getElementById('categorized-offers-area');
        // As variáveis do modal não são mais necessárias aqui

        /**
         * Função genérica para chamadas Fetch API.
         */
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

                const responseText = await response.text(); // Pega o texto da resposta primeiro
                console.log(`[apiCall] Resposta (texto) para ${url}:`, responseText.substring(0, 500) + (responseText.length > 500 ? "..." : "")); // Loga apenas parte se for muito grande

                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (e) {
                    console.error("[apiCall] Falha ao processar JSON da API:", e);
                    console.error("[apiCall] Resposta recebida (texto completo):", responseText); // Loga o texto completo se o parse falhar
                    throw new Error(`Resposta inválida da API (não é JSON). Status: ${response.status} ${response.statusText}.`);
                }
                
                if (!response.ok) { // Verifica se o status HTTP é 2xx
                    throw new Error(result.message || `Erro HTTP: ${response.status} ${response.statusText}`);
                }
                return result; // Retorna o JSON parseado
            } catch (error) {
                console.error(`[apiCall] Erro final na chamada API para ${url}:`, error.message);
                throw error; // Propaga o erro
            }
        }

        function renderCategorizedOffers(categories) {
            console.log("[renderCategorizedOffers] Recebeu categorias:", categories);
            categorizedOffersArea.innerHTML = '';

            if (!categories || categories.length === 0) {
                console.log("[renderCategorizedOffers] Nenhuma categoria para renderizar, mostrando placeholder.");
                categorizedOffersArea.innerHTML = `
                    <div class="no-offers-placeholder">
                        <div class="lottie-animation-wrapper">
                            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_p9bndp2a.json" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></lottie-player>
                        </div>
                        <h4>Ops, nada por aqui ainda!</h4>
                        <p>Nossos parceiros estão a preparar delícias. Que tal voltar em breve ou tentar atualizar?</p>
                        <button class="btn btn-primary-yellow btn-refresh" id="refresh-offers-btn"><i class="bi bi-arrow-clockwise"></i> Tentar Novamente</button>
                    </div>`;
                const refreshBtn = document.getElementById('refresh-offers-btn');
                if (refreshBtn) { refreshBtn.addEventListener('click', fetchCategorizedOffers); }
                return;
            }

            categories.forEach(category => {
                const section = document.createElement('section');
                section.className = 'category-section';
                const titleWrapper = document.createElement('div');
                titleWrapper.className = 'category-title-wrapper';
                titleWrapper.innerHTML = `<h2 class="category-title">${category.category_name}</h2><a href="categoria.php?id=${category.category_id}" class="category-see-all">Ver todas <i class="bi bi-arrow-right-short"></i></a>`;
                section.appendChild(titleWrapper);

                const wrapper = document.createElement('div');
                wrapper.className = 'horizontal-scroll-wrapper';
                category.offers.forEach(offer => {
                    const card = document.createElement('div');
                    card.className = 'card offer-card-h';
                    card.dataset.id = offer.offer_id;
                    const imageUrl = offer.image_url;
                    const price = parseFloat(offer.price).toFixed(2).replace('.', ',');
                    let quantityBadgeClass = 'bg-success';
                    if (offer.quantity_available <= 2) quantityBadgeClass = 'bg-danger';
                    else if (offer.quantity_available <= 5) quantityBadgeClass = 'bg-warning text-dark';
                    const rating = parseFloat(offer.avg_rating);
                    const ratingText = rating > 0 ? rating.toFixed(1) : '';
                    const ratingBadgeHtml = (rating > 0 || offer.rating_count > 0) ? `<span class="badge card-rating-badge"><i class="bi bi-star-fill"></i> ${ratingText}</span>` : '';
                    
                    card.innerHTML = `
                        <div class="card-img-top-wrapper"><img src="${imageUrl}" class="card-img-top" alt="${offer.title}"></div>
                        <div class="card-body">
                            <h5 class="card-title">${offer.title}</h5>
                            <p class="card-text business-name">${offer.business_name}</p>
                            <div class="card-details-row"><p class="card-price mb-0">R$ ${price}</p>${ratingBadgeHtml}</div>
                            <div class="card-details-row mt-1">
                                <span class="card-pickup-time"><i class="bi bi-clock"></i> ${offer.pickup_window}</span>
                                <span class="card-quantity-badge"><span class="badge ${quantityBadgeClass}">${offer.quantity_available}</span></span>
                            </div>
                        </div>`;
                    card.addEventListener('click', () => {
                        window.location.href = `detalhe_oferta.php?id=${offer.offer_id}`;
                    });
                    wrapper.appendChild(card);
                });
                section.appendChild(wrapper);
                categorizedOffersArea.appendChild(section);
            });
        }

        async function fetchCategorizedOffers() {
            console.log("[fetchCategorizedOffers] Iniciando busca...");
            categorizedOffersArea.innerHTML = `
                <div class="loading-placeholder">
                    <div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div>
                    <p class="mt-2">Buscando as melhores ofertas para você...</p>
                </div>`;
            try {
                const result = await apiCall('./api/get_categorized_offers.php');
                console.log("[fetchCategorizedOffers] Resultado da API:", result);
                if (result && result.status === 'success') {
                    renderCategorizedOffers(result.data || []);
                } else {
                    const errorMessage = (result && result.message) ? result.message : 'Falha ao carregar ofertas (resposta da API não foi sucesso).';
                    console.error("[fetchCategorizedOffers] Erro no status da API:", errorMessage, result);
                    categorizedOffersArea.innerHTML = `<p class="text-danger text-center p-5">${errorMessage}</p>`;
                }
            } catch (error) {
                console.error("[fetchCategorizedOffers] Erro capturado:", error.message);
                if (error.message !== "Acesso Negado/Sessão Expirada") {
                    categorizedOffersArea.innerHTML = `<p class="text-danger text-center p-5">Falha crítica ao carregar ofertas. Por favor, tente novamente mais tarde ou contacte o suporte se o problema persistir. Detalhe: ${error.message}</p>`;
                }
                // Se for "Acesso Negado/Sessão Expirada", o redirecionamento já ocorreu na apiCall
            }
        }
        
        fetchCategorizedOffers(); // Inicia o carregamento
    });
</script>

<?php require_once 'includes/footer.php'; ?>
