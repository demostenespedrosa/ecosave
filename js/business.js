/**
 * /js/business.js
 * Lógica JavaScript para as páginas do Estabelecimento no EcoSave.
 * Gerado em: 23/05/2025, 20:14:07 (Horário de Brasília)
 * Localização: Cabo de Santo Agostinho, Pernambuco, Brasil
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- Seletores de Elementos ---
    // Página: gerenciar_ofertas.php
    const addOfferForm = document.getElementById('add-offer-form');
    const businessOffersListDiv = document.getElementById('business-offers-list');
    const editOfferModalElement = document.getElementById('editOfferModal');
    const editOfferForm = document.getElementById('edit-offer-form');
    const addOfferCollapse = document.getElementById('addOfferCollapse');

    // Página: ver_reservas.php
    const businessReservationsListDiv = document.getElementById('business-reservations-list');

    // Instância do Modal (se existir)
    let editOfferModal = editOfferModalElement ? new bootstrap.Modal(editOfferModalElement) : null;

    // --- === Funções Auxiliares === ---

    /**
     * Exibe uma mensagem em um elemento de alerta.
     * @param {string} elementId ID do elemento de alerta.
     * @param {string} message A mensagem a ser exibida.
     * @param {string} type Tipo do alerta ('success', 'danger', 'warning').
     */
    function showMessage(elementId, message, type = 'danger') {
        const msgDiv = document.getElementById(elementId);
        if (msgDiv) {
            msgDiv.textContent = message;
            msgDiv.className = `mt-3 alert alert-${type} show`;
        }
    }

    /**
     * Esconde uma mensagem em um elemento de alerta.
     * @param {string} elementId ID do elemento de alerta.
     */
    function hideMessage(elementId) {
        const msgDiv = document.getElementById(elementId);
        if (msgDiv) {
            msgDiv.className = 'mt-3 alert d-none';
        }
    }

    /**
     * Função genérica para chamadas Fetch API.
     * @param {string} url URL da API.
     * @param {object} options Opções do Fetch (method, body, etc.).
     * @returns {Promise<object>} O resultado da API em JSON.
     */
    async function apiCall(url, options = {}) {
        try {
            const response = await fetch(url, options);
            if (response.status === 403 || response.status === 401) {
                alert("Sessão expirada ou acesso negado. Faça login novamente.");
                window.location.href = 'login.php';
                throw new Error("Acesso Negado");
            }
            if (!response.ok) {
                const errorResult = await response.json().catch(() => ({ message: `Erro HTTP: ${response.statusText}` }));
                throw new Error(errorResult.message || `Erro HTTP: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error(`Erro na chamada API para ${url}:`, error);
            throw error; // Propaga o erro para ser tratado onde a função foi chamada
        }
    }


    // --- === Funções de Categorias === ---

    async function loadCategoriesIntoSelects() {
        const selects = document.querySelectorAll('select[name="category_id"]');
        if (selects.length === 0) return;

        try {
            const result = await apiCall('./api/get_categories.php');
            if (result.status === 'success') {
                selects.forEach(select => {
                    select.innerHTML = '<option value="">-- Selecione uma Categoria --</option>';
                    result.data.forEach(cat => {
                        const option = new Option(cat.name, cat.category_id);
                        select.appendChild(option);
                    });
                });
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            selects.forEach(select => select.innerHTML = `<option value="">${error.message}</option>`);
        }
    }

    // --- === Funções de Ofertas === ---

    async function fetchBusinessOffers() {
        if (!businessOffersListDiv) return;
        businessOffersListDiv.innerHTML = '<div class="list-group-item text-center"><div class="spinner-border text-warning" role="status"></div></div>';
        try {
            const result = await apiCall('./api/get_my_business_offers.php');
            renderBusinessOffers(result.data || []);
        } catch (error) {
            businessOffersListDiv.innerHTML = `<p class="list-group-item text-danger">Erro ao carregar ofertas: ${error.message}</p>`;
        }
    }

    function renderBusinessOffers(offers) {
        if (!businessOffersListDiv) return;
        businessOffersListDiv.innerHTML = '';
        if (offers.length === 0) {
            businessOffersListDiv.innerHTML = '<p class="list-group-item text-muted">Nenhuma oferta cadastrada.</p>';
            return;
        }
        offers.forEach(offer => {
            const item = document.createElement('div');
            item.className = 'list-group-item d-flex justify-content-between align-items-center flex-wrap'; // Adicionado flex-wrap
            const price = parseFloat(offer.price).toFixed(2).replace('.', ',');
            const status = offer.status;
            const badgeClass = status === 'active' ? 'success' : (status === 'sold_out' ? 'warning' : 'secondary');

            item.innerHTML = `
                <div class="me-3 mb-2 mb-md-0"> <strong>${offer.title}</strong> (R$ ${price})<br>
                    <small>Qtd: ${offer.quantity_available} | Status: <span class="badge bg-${badgeClass}">${status}</span></small>
                </div>
                <div class="btn-group" role="group"> <button class="btn btn-sm btn-outline-primary btn-edit-offer" data-id="${offer.offer_id}" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-delete-offer" data-id="${offer.offer_id}" title="Desativar">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>`;
            businessOffersListDiv.appendChild(item);
        });

        document.querySelectorAll('.btn-edit-offer').forEach(b => b.addEventListener('click', e => showEditModal(e.currentTarget.dataset.id)));
        document.querySelectorAll('.btn-delete-offer').forEach(b => b.addEventListener('click', e => handleDeleteOffer(e.currentTarget.dataset.id)));
    }

    async function handleAddOffer(event) {
        event.preventDefault();
        hideMessage('add-offer-message');
        const formData = new FormData(addOfferForm);
        const submitButton = addOfferForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';

        try {
            const result = await apiCall('./api/add_offer.php', { method: 'POST', body: formData });
            if (result.status === 'success') {
                showMessage('add-offer-message', 'Oferta adicionada!', 'success');
                addOfferForm.reset();
                fetchBusinessOffers();
                const bsCollapse = bootstrap.Collapse.getInstance(addOfferCollapse) || new bootstrap.Collapse(addOfferCollapse, { toggle: false });
                bsCollapse.hide();
            } else {
                showMessage('add-offer-message', result.message);
            }
        } catch (error) {
            showMessage('add-offer-message', error.message);
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Salvar Nova Oferta';
        }
    }

    async function showEditModal(offerId) {
        if (!editOfferModal) return;
        hideMessage('edit-offer-message');
        editOfferForm.reset();
        document.getElementById('current-image-info').innerHTML = ''; // Limpa info da imagem

        try {
            const result = await apiCall(`./api/get_offer_for_edit.php?id=${offerId}`);
            if (result.status === 'success') {
                const offer = result.data;
                document.getElementById('edit-offer-id').value = offer.offer_id;
                document.getElementById('edit-offer-title').value = offer.title;
                document.getElementById('edit-offer-description').value = offer.description || '';
                document.getElementById('edit-offer-price').value = offer.price;
                document.getElementById('edit-offer-quantity').value = offer.quantity_available;
                document.getElementById('edit-offer-start').value = offer.pickup_start_time;
                document.getElementById('edit-offer-end').value = offer.pickup_end_time;
                document.getElementById('edit-offer-status').value = offer.status;
                document.getElementById('edit-offer-category').value = offer.category_id;
                if (offer.image_path) {
                     document.getElementById('current-image-info').innerHTML = `Imagem atual: <a href="${offer.image_path}" target="_blank">Ver</a>`;
                } else {
                     document.getElementById('current-image-info').innerHTML = 'Nenhuma imagem atual.';
                }
                editOfferModal.show();
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert(`Erro ao carregar dados: ${error.message}`);
        }
    }

    async function handleEditOffer(event) {
        event.preventDefault();
        hideMessage('edit-offer-message');
        const formData = new FormData(editOfferForm);
        const submitButton = editOfferForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';

        try {
            const result = await apiCall('./api/update_offer.php', { method: 'POST', body: formData });
            if (result.status === 'success') {
                showMessage('edit-offer-message', 'Oferta atualizada!', 'success');
                fetchBusinessOffers();
                setTimeout(() => editOfferModal.hide(), 1500);
            } else {
                showMessage('edit-offer-message', result.message);
            }
        } catch (error) {
            showMessage('edit-offer-message', error.message);
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Salvar Alterações';
        }
    }

    async function handleDeleteOffer(offerId) {
        if (!confirm('Tem certeza que deseja DESATIVAR esta oferta?')) return;
        try {
            const formData = new FormData();
            formData.append('offer_id', offerId);
            const result = await apiCall('./api/delete_offer.php', { method: 'POST', body: formData });
            if (result.status === 'success') {
                alert('Oferta desativada!');
                fetchBusinessOffers();
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert(`Erro ao desativar: ${error.message}`);
        }
    }

    // --- === Funções de Reservas === ---

    async function fetchBusinessReservations() {
        if (!businessReservationsListDiv) return;
        businessReservationsListDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-warning" role="status"></div></div>';
        try {
            const result = await apiCall('./api/get_reservations.php');
            renderBusinessReservations(result.data || []);
        } catch (error) {
            businessReservationsListDiv.innerHTML = `<p class="text-danger">Erro ao carregar reservas: ${error.message}</p>`;
        }
    }

    function renderBusinessReservations(reservations) {
        if (!businessReservationsListDiv) return;
        businessReservationsListDiv.innerHTML = '';
        if (reservations.length === 0) {
            businessReservationsListDiv.innerHTML = '<p class="text-muted text-center">Nenhuma reserva encontrada.</p>';
            return;
        }
        const table = document.createElement('table');
        table.className = 'table table-striped table-hover table-sm align-middle'; // Adicionado align-middle
        table.innerHTML = `
            <thead>
                <tr><th>Código</th><th>Oferta</th><th>Cliente</th><th>Data</th><th>Status</th><th>Ação</th></tr>
            </thead>
            <tbody></tbody>`;
        const tbody = table.querySelector('tbody');
        reservations.forEach(res => {
            const row = document.createElement('tr');
            const canCollect = res.order_status === 'reserved';
            const date = new Date(res.created_at);
            const formattedDate = date.toLocaleDateString('pt-BR');
            const statusClass = res.order_status === 'reserved' ? 'warning text-dark' : 'success';

            row.innerHTML = `
                <td><span class="badge bg-secondary">${res.reservation_code}</span></td>
                <td>${res.offer_title}</td>
                <td>${res.user_name}</td>
                <td>${formattedDate}</td>
                <td><span class="badge bg-${statusClass}">${res.order_status}</span></td>
                <td>
                    <button class="btn btn-sm btn-success btn-collect" data-order-id="${res.order_id}" ${canCollect ? '' : 'disabled'}>
                       <i class="bi bi-check-lg"></i> Marcar Coletado
                    </button>
                </td>`;
            tbody.appendChild(row);
        });
        businessReservationsListDiv.appendChild(table);

        document.querySelectorAll('.btn-collect').forEach(button => {
            if (!button.disabled) {
                button.addEventListener('click', e => handleMarkCollected(e.currentTarget.dataset.orderId));
            }
        });
    }

     async function handleMarkCollected(orderId) {
        const btn = document.querySelector(`.btn-collect[data-order-id="${orderId}"]`);
        if (btn) btn.disabled = true;
        try {
            const formData = new FormData();
            formData.append('order_id', orderId);
            const result = await apiCall('./api/mark_order_collected.php', { method: 'POST', body: formData });
            if (result.status === 'success') {
                fetchBusinessReservations();
            } else {
                alert(result.message);
                if (btn) btn.disabled = false;
            }
        } catch (error) {
            alert(`Erro: ${error.message}`);
            if (btn) btn.disabled = false;
        }
    }

    // --- === INICIALIZAÇÃO E EVENT LISTENERS === ---

    // Se estivermos na página de Gerenciar Ofertas
    if (addOfferForm && businessOffersListDiv && editOfferModalElement) {
        addOfferForm.addEventListener('submit', handleAddOffer);
        editOfferForm.addEventListener('submit', handleEditOffer);
        loadCategoriesIntoSelects();
        fetchBusinessOffers();
    }

    // Se estivermos na página de Ver Reservas
    if (businessReservationsListDiv) {
        fetchBusinessReservations();
    }

}); // Fim do DOMContentLoaded