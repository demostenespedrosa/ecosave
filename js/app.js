// /js/app.js

document.addEventListener('DOMContentLoaded', () => {
    // Seleciona os elementos principais
    const pages = document.querySelectorAll('.page');
    const bottomNav = document.getElementById('bottom-nav');
    const navLinks = document.querySelectorAll('#bottom-nav .nav-link');
    const offerListDiv = document.getElementById('offers-list');
    const ordersListDiv = document.getElementById('orders-list');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const logoutButton = document.getElementById('logout-button');
    const linkShowRegister = document.getElementById('link-show-register');
    const linkShowLogin = document.getElementById('link-show-login');
    const profileName = document.getElementById('profile-name');
    const profileEmail = document.getElementById('profile-email');

    // Modal de Detalhes
    const offerModal = new bootstrap.Modal(document.getElementById('offerDetailsModal'));
    const offerDetailsBody = document.getElementById('offerDetailsBody');
    const reserveButton = document.getElementById('reserveButton');

    // --- Elementos do Estabelecimento ---
    const bottomNavBusiness = document.getElementById('bottom-nav-business');
    const navLinksBusiness = document.querySelectorAll('#bottom-nav-business .nav-link');
    const businessOffersListDiv = document.getElementById('business-offers-list');
    const businessReservationsListDiv = document.getElementById('business-reservations-list');
    const addOfferForm = document.getElementById('add-offer-form');
    const businessWelcomeName = document.getElementById('business-welcome-name');

    // --- Links entre paineis ---
    const businessDashboardLinks = document.querySelectorAll('#page-business-dashboard a');

    // --- Elementos de Edição ---
    const editOfferModalElement = document.getElementById('editOfferModal');
    const editOfferModal = new bootstrap.Modal(editOfferModalElement);
    const editOfferForm = document.getElementById('edit-offer-form');

    let currentOfferId = null; // Para saber qual oferta reservar

    // --- FUNÇÕES DE NAVEGAÇÃO ---

    function showPage(pageId) {
        pages.forEach(page => {
            page.classList.add('d-none'); // Esconde todas
        });
        const activePage = document.getElementById(pageId);
        if (activePage) {
            activePage.classList.remove('d-none'); // Mostra a desejada
        }

        // Seleciona a nav bar correta (consumer ou business)
        const currentNav = bottomNav.classList.contains('d-none') ? navLinksBusiness : navLinks;

        // Atualiza o link ativo na barra de navegação VISÍVEL
        currentNav.forEach(link => {
            if (link.dataset.page === pageId) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        // Carrega dados se necessário (ADICIONAR NOVAS CHAMADAS)
        if (pageId === 'page-discover') fetchOffers();
        if (pageId === 'page-orders') fetchOrders();
        if (pageId === 'page-business-offers') fetchBusinessOffers();
        if (pageId === 'page-business-reservations') fetchBusinessReservations();
    }

    function checkLoginStatus() {
        // Uma forma simples é tentar buscar algo que precise de login,
        // ou ter um endpoint 'check_session.php'. Por agora, vamos assumir
        // que se não der erro ao buscar perfil/pedidos, está logado.
        // Se estivermos logados (baseado em chamadas futuras ou uma sessão JS),
        // mostramos a nav e a página 'discover'. Senão, 'login'.
        // POR ENQUANTO: Vamos iniciar no login e ir para 'discover' após logar.
        showPage('page-login');
        bottomNav.classList.add('d-none'); // Esconde nav_bar se não logado
    }

    function navigateTo(pageId) {
        showPage(pageId);
    }

    // --- FUNÇÕES DE MENSAGEM ---
    function showMessage(elementId, message, type = 'danger') {
        const msgDiv = document.getElementById(elementId);
        if(msgDiv) {
            msgDiv.textContent = message;
            msgDiv.className = `mt-3 alert alert-${type}`; // Remove d-none e aplica estilo
        }
    }
     function hideMessage(elementId) {
        const msgDiv = document.getElementById(elementId);
        if(msgDiv) {
            msgDiv.className = 'mt-3 alert d-none'; // Adiciona d-none
        }
    }


    // --- FUNÇÕES DE API (FETCH) ---

    async function fetchOffers() {
        offerListDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-warning" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
        try {
            const response = await fetch('./api/get_offers.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.status === 'success') {
                renderOffers(result.data);
            } else {
                offerListDiv.innerHTML = `<p class="text-danger text-center">${result.message || 'Nenhuma oferta encontrada.'}</p>`;
            }
        } catch (error) {
            console.error('Erro ao buscar ofertas:', error);
            offerListDiv.innerHTML = '<p class="text-danger text-center">Erro ao carregar ofertas. Tente novamente.</p>';
        }
    }

     async function fetchOfferDetails(offerId) {
        offerDetailsBody.innerHTML = '<div class="text-center"><div class="spinner-border text-warning" role="status"></div></div>';
        currentOfferId = offerId; // Armazena o ID
        try {
            const response = await fetch(`./api/get_offer_details.php?id=${offerId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.status === 'success') {
                renderOfferDetails(result.data);
                offerModal.show();
            } else {
                 alert(result.message || 'Erro ao buscar detalhes.');
            }
        } catch (error) {
            console.error('Erro ao buscar detalhes da oferta:', error);
            alert('Erro ao carregar detalhes. Tente novamente.');
        }
    }

    async function reserveOffer() {
        if (!currentOfferId) return;
        hideMessage('reserve-message');
        reserveButton.disabled = true;

        try {
            const formData = new FormData();
            formData.append('offer_id', currentOfferId);

            const response = await fetch('./api/reserve_offer.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showMessage('reserve-message', `${result.message} Código: ${result.reservation_code}`, 'success');
                fetchOffers(); // Atualiza a lista
                // offerModal.hide(); // Opcional: fechar modal
            } else {
                 showMessage('reserve-message', result.message || 'Erro ao reservar.');
            }

        } catch (error) {
            console.error('Erro ao reservar:', error);
            showMessage('reserve-message', 'Erro de conexão ao reservar.');
        } finally {
             reserveButton.disabled = false;
        }
    }

    async function fetchOrders() {
         ordersListDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-warning" role="status"></div></div>';
         try {
            const response = await fetch('./api/get_my_orders.php');
            if (!response.ok) {
                if (response.status === 401) { // Não logado
                    navigateTo('page-login');
                    return;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const result = await response.json();

            if (result.status === 'success') {
                renderOrders(result.data);
            } else {
                ordersListDiv.innerHTML = `<p class="text-info text-center">${result.message || 'Nenhum pedido encontrado.'}</p>`;
            }
        } catch (error) {
            console.error('Erro ao buscar pedidos:', error);
            ordersListDiv.innerHTML = '<p class="text-danger text-center">Erro ao carregar pedidos. Tente novamente.</p>';
        }
    }


    // --- FUNÇÕES DE RENDERIZAÇÃO ---

    function renderOffers(offers) {
        offerListDiv.innerHTML = ''; // Limpa a lista
        if (offers.length === 0) {
            offerListDiv.innerHTML = '<p class="text-center text-muted">Nenhuma oferta disponível no momento. Volte mais tarde!</p>';
            return;
        }

        offers.forEach(offer => {
            const card = document.createElement('div');
            card.className = 'col-6 col-md-4 col-lg-3'; // Responsivo: 2 colunas no cel, mais em telas maiores
            card.innerHTML = `
                <div class="card offer-card h-100" data-id="${offer.offer_id}">
                    <img src="https://via.placeholder.com/300x150.png/FFC107/333333?Text=EcoSave" class="card-img-top" alt="${offer.title}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fs-6">${offer.title}</h5>
                        <p class="card-text small text-muted">${offer.business_name}</p>
                        <div class="mt-auto">
                           <p class="card-time small"><i class="bi bi-clock"></i> ${offer.pickup_window}</p>
                           <p class="card-price mb-0">R$ ${parseFloat(offer.price).toFixed(2).replace('.',',')}</p>
                           <span class="badge bg-success">${offer.quantity_available} disp.</span>
                        </div>
                    </div>
                </div>
            `;
            // Adiciona evento de clique para ver detalhes
            card.querySelector('.offer-card').addEventListener('click', () => {
                fetchOfferDetails(offer.offer_id);
            });
            offerListDiv.appendChild(card);
        });
    }

    function renderOfferDetails(offer) {
        offerDetailsBody.innerHTML = `
            <h4>${offer.title}</h4>
            <p><strong><i class="bi bi-shop"></i> Estabelecimento:</strong> ${offer.business_name}</p>
            <p><strong><i class="bi bi-geo-alt"></i> Endereço:</strong> ${offer.address}, ${offer.city}</p>
            <p><strong><i class="bi bi-card-text"></i> Descrição:</strong> ${offer.description || 'Nenhuma descrição fornecida.'}</p>
            <p><strong><i class="bi bi-clock-history"></i> Retirada:</strong> ${offer.pickup_window}</p>
            <p><strong><i class="bi bi-box-seam"></i> Disponível:</strong> ${offer.quantity_available} unidade(s)</p>
            <h3 class="text-danger"><strong>Preço: R$ ${parseFloat(offer.price).toFixed(2).replace('.',',')}</strong></h3>
        `;
        hideMessage('reserve-message'); // Limpa msg anterior
    }

     function renderOrders(orders) {
        ordersListDiv.innerHTML = ''; // Limpa a lista
        if (orders.length === 0) {
            ordersListDiv.innerHTML = '<p class="text-center text-muted">Você ainda não fez nenhum pedido.</p>';
            return;
        }

        orders.forEach(order => {
            const item = document.createElement('div');
            item.className = 'card mb-3';
            item.innerHTML = `
                <div class="card-body">
                    <h5 class="card-title">${order.title} <span class="badge bg-${order.order_status === 'reserved' ? 'warning' : 'success'} text-dark">${order.order_status}</span></h5>
                    <h6 class="card-subtitle mb-2 text-muted">${order.business_name}</h6>
                    <p class="card-text">
                        <strong>Código:</strong> ${order.reservation_code}<br>
                        <strong>Data:</strong> ${order.order_date_formatted}<br>
                        <strong>Retirada:</strong> ${order.pickup_window}<br>
                        <strong>Preço:</strong> R$ ${parseFloat(order.price).toFixed(2).replace('.',',')}
                    </p>
                </div>
            `;
            ordersListDiv.appendChild(item);
        });
    }

    function loadProfileData(user) {
        profileName.textContent = user.name;
        profileEmail.textContent = user.email;
    }


    // --- FUNÇÕES DE AUTENTICAÇÃO ---

    async function handleLogin(event) {
        event.preventDefault();
        hideMessage('login-message');
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;

        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);

        try {
            const response = await fetch('./api/user_login.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                loadProfileData(result.user); // Carrega dados no perfil

                // *** ATUALIZAÇÃO AQUI ***
                if (result.user.type === 'business') {
                    bottomNav.classList.add('d-none'); // Esconde nav consumidor
                    bottomNavBusiness.classList.remove('d-none'); // Mostra nav business
                    businessWelcomeName.textContent = `Bem-vindo, ${result.user.name}!`;
                    navigateTo('page-business-dashboard'); // Vai para painel business
                } else {
                    bottomNavBusiness.classList.add('d-none'); // Esconde nav business
                    bottomNav.classList.remove('d-none'); // Mostra nav consumidor
                    navigateTo('page-discover'); // Vai para descobrir (consumidor)
                }
                // *** FIM DA ATUALIZAÇÃO ***

            } else {
                showMessage('login-message', result.message || 'Erro no login.');
            }
        } catch (error) {
            console.error('Erro de login:', error);
            showMessage('login-message', 'Erro de conexão. Tente novamente.');
        }
    }

    async function handleRegister(event) {
        event.preventDefault();
        hideMessage('register-message');
        const name = document.getElementById('register-name').value;
        const email = document.getElementById('register-email').value;
        const password = document.getElementById('register-password').value;

        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('password', password);
        // formData.append('user_type', 'consumer'); // Adicionar se tiver opção

        try {
            const response = await fetch('./api/user_register.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                 showMessage('register-message', 'Cadastro realizado! Faça login agora.', 'success');
                 registerForm.reset();
                 setTimeout(() => navigateTo('page-login'), 1500); // Volta pro login
            } else {
                showMessage('register-message', result.message || 'Erro no cadastro.');
            }
        } catch (error) {
            console.error('Erro de registro:', error);
            showMessage('register-message', 'Erro de conexão. Tente novamente.');
        }
    }

    async function handleLogout() {
         try {
            await fetch('./api/user_logout.php');
            bottomNav.classList.add('d-none'); // Esconde nav consumidor
            bottomNavBusiness.classList.add('d-none'); // Esconde nav business
            navigateTo('page-login'); // Volta pro login
        } catch (error) {
            console.error('Erro no logout:', error);
            alert('Erro ao sair.');
        }
    }


    // --- EVENT LISTENERS ---

    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            navigateTo(link.dataset.page);
        });
    });

    loginForm.addEventListener('submit', handleLogin);
    registerForm.addEventListener('submit', handleRegister);
    logoutButton.addEventListener('click', handleLogout);

    linkShowRegister.addEventListener('click', (e) => {
        e.preventDefault();
        navigateTo('page-register');
    });
     linkShowLogin.addEventListener('click', (e) => {
        e.preventDefault();
        navigateTo('page-login');
    });

    reserveButton.addEventListener('click', reserveOffer);


    // --- INICIALIZAÇÃO ---
    checkLoginStatus(); // Define a página inicial (login)

}); // Fim do DOMContentLoaded

// --- FUNÇÕES DE API (FETCH) - ESTABELECIMENTO ---

    async function fetchBusinessOffers() {
        businessOffersListDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-warning" role="status"></div></div>';
        try {
            const response = await fetch('./api/get_my_business_offers.php');
            if (!response.ok) {
                 if (response.status === 403) { navigateTo('page-login'); return; }
                 throw new Error(`HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            if (result.status === 'success') {
                renderBusinessOffers(result.data);
            } else {
                businessOffersListDiv.innerHTML = `<p class="text-danger">${result.message}</p>`;
            }
        } catch (error) {
             console.error('Erro ao buscar ofertas do BIZ:', error);
             businessOffersListDiv.innerHTML = '<p class="text-danger">Erro ao carregar suas ofertas.</p>';
        }
    }

    async function fetchBusinessReservations() {
        businessReservationsListDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-warning" role="status"></div></div>';
        try {
            const response = await fetch('./api/get_reservations.php');
             if (!response.ok) {
                 if (response.status === 403) { navigateTo('page-login'); return; }
                 throw new Error(`HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            if (result.status === 'success') {
                renderBusinessReservations(result.data);
            } else {
                businessReservationsListDiv.innerHTML = `<p class="text-danger">${result.message}</p>`;
            }
        } catch (error) {
             console.error('Erro ao buscar reservas do BIZ:', error);
             businessReservationsListDiv.innerHTML = '<p class="text-danger">Erro ao carregar suas reservas.</p>';
        }
    }

    async function handleAddOffer(event) {
        event.preventDefault();
        hideMessage('add-offer-message');
        const formData = new FormData();
        formData.append('title', document.getElementById('offer-title').value);
        formData.append('description', document.getElementById('offer-description').value);
        formData.append('price', document.getElementById('offer-price').value);
        formData.append('quantity', document.getElementById('offer-quantity').value);
        formData.append('pickup_start', document.getElementById('offer-start').value);
        formData.append('pickup_end', document.getElementById('offer-end').value);

        try {
             const response = await fetch('./api/add_offer.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                showMessage('add-offer-message', 'Oferta adicionada!', 'success');
                addOfferForm.reset();
                fetchBusinessOffers(); // Atualiza a lista
            } else {
                 showMessage('add-offer-message', result.message || 'Erro ao adicionar.');
            }
        } catch (error) {
             console.error('Erro ao adicionar oferta:', error);
             showMessage('add-offer-message', 'Erro de conexão.');
        }
    }

    async function handleMarkCollected(orderId) {
        const btn = document.querySelector(`.btn-collect[data-order-id="${orderId}"]`);
        if (btn) btn.disabled = true; // Desabilita o botão

        try {
             const formData = new FormData();
             formData.append('order_id', orderId);

             const response = await fetch('./api/mark_order_collected.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                fetchBusinessReservations(); // Atualiza a lista
            } else {
                 alert(result.message || 'Erro ao marcar como coletado.');
                 if (btn) btn.disabled = false; // Reabilita se der erro
            }

        } catch (error) {
             console.error('Erro ao marcar:', error);
             alert('Erro de conexão ao marcar.');
             if (btn) btn.disabled = false;
        }
    }

    // --- FUNÇÕES DE RENDERIZAÇÃO - ESTABELECIMENTO ---

    function renderBusinessOffers(offers) {
        businessOffersListDiv.innerHTML = '';
        if (offers.length === 0) { /* ... */ return; }
        const list = document.createElement('ul');
        list.className = 'list-group';
        offers.forEach(offer => {
            const item = document.createElement('li');
            item.className = 'list-group-item d-flex justify-content-between align-items-center';
            item.innerHTML = `
                <div>
                    <strong>${offer.title}</strong> (R$ ${parseFloat(offer.price).toFixed(2).replace('.',',')})<br>
                    <small>Qtd: ${offer.quantity_available} | Status: <span class="badge bg-${offer.status === 'active' ? 'success' : (offer.status === 'sold_out' ? 'warning' : 'secondary')}">${offer.status}</span></small>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary btn-edit-offer" data-id="${offer.offer_id}" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-delete-offer" data-id="${offer.offer_id}" title="Desativar">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            list.appendChild(item);
        });
        businessOffersListDiv.appendChild(list);

        // *** NOVO: Adiciona listeners aos novos botões ***
        document.querySelectorAll('.btn-edit-offer').forEach(button => {
            button.addEventListener('click', (e) => {
                showEditModal(e.currentTarget.dataset.id);
            });
        });
        document.querySelectorAll('.btn-delete-offer').forEach(button => {
            button.addEventListener('click', (e) => {
                handleDeleteOffer(e.currentTarget.dataset.id);
            });
        });
    }

     function renderBusinessReservations(reservations) {
        businessReservationsListDiv.innerHTML = '';
        if (reservations.length === 0) {
            businessReservationsListDiv.innerHTML = '<p class="text-muted">Nenhuma reserva encontrada.</p>';
            return;
        }
        const table = document.createElement('table');
        table.className = 'table table-striped table-hover';
        table.innerHTML = `
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Oferta</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody></tbody>
        `;
        const tbody = table.querySelector('tbody');
        reservations.forEach(res => {
            const row = document.createElement('tr');
            const canCollect = res.order_status === 'reserved';
            row.innerHTML = `
                <td>${res.reservation_code}</td>
                <td>${res.offer_title}</td>
                <td>${res.user_name}</td>
                <td><span class="badge bg-${res.order_status === 'reserved' ? 'warning' : 'success'} text-dark">${res.order_status}</span></td>
                <td>
                    <button class="btn btn-sm btn-success btn-collect" data-order-id="${res.order_id}" ${canCollect ? '' : 'disabled'}>
                       <i class="bi bi-check-lg"></i> Coletado
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
        businessReservationsListDiv.appendChild(table);

        // Adiciona listeners aos botões "Coletado"
        document.querySelectorAll('.btn-collect').forEach(button => {
            if (!button.disabled) {
                 button.addEventListener('click', (e) => {
                    handleMarkCollected(e.currentTarget.dataset.orderId);
                });
            }
        });
    }

    // --- EVENT LISTENERS - ESTABELECIMENTO ---

    navLinksBusiness.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            navigateTo(link.dataset.page);
        });
    });

    addOfferForm.addEventListener('submit', handleAddOffer);

    // Adiciona listeners para os links no dashboard do business
    businessDashboardLinks.forEach(link => {
         link.addEventListener('click', (event) => {
            event.preventDefault();
            navigateTo(link.dataset.page);
        });
    });

    // --- FUNÇÕES DE EDIÇÃO/EXCLUSÃO ---

    async function showEditModal(offerId) {
        hideMessage('edit-offer-message');
        editOfferForm.reset(); // Limpa o form

        try {
            const response = await fetch(`./api/get_offer_for_edit.php?id=${offerId}`);
            if (!response.ok) throw new Error('Não foi possível carregar a oferta.');
            const result = await response.json();

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
                editOfferModal.show(); // Mostra o modal
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error("Erro ao carregar oferta para editar:", error);
            alert("Erro ao carregar dados da oferta.");
        }
    }

    async function handleEditOffer(event) {
        event.preventDefault();
        hideMessage('edit-offer-message');
        const formData = new FormData(editOfferForm); // Pega todos os dados do form

        try {
            const response = await fetch('./api/update_offer.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                 showMessage('edit-offer-message', 'Oferta atualizada!', 'success');
                 fetchBusinessOffers(); // Atualiza a lista
                 setTimeout(() => editOfferModal.hide(), 1000); // Fecha o modal após 1s
            } else {
                showMessage('edit-offer-message', result.message || 'Erro ao atualizar.');
            }
        } catch (error) {
             console.error('Erro ao editar oferta:', error);
             showMessage('edit-offer-message', 'Erro de conexão.');
        }
    }

    async function handleDeleteOffer(offerId) {
        if (!confirm('Tem certeza que deseja desativar esta oferta? Ela não aparecerá mais para os consumidores.')) {
            return; // Não faz nada se o usuário cancelar
        }

        try {
            const formData = new FormData();
            formData.append('offer_id', offerId);

            const response = await fetch('./api/delete_offer.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert('Oferta desativada com sucesso!');
                fetchBusinessOffers(); // Atualiza a lista
            } else {
                alert(result.message || 'Erro ao desativar.');
            }
        } catch (error) {
            console.error('Erro ao desativar oferta:', error);
            alert('Erro de conexão.');
        }
    }