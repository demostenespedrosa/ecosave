<?php
// /gerenciar_ofertas.php
// Gerado em: 24/05/2025, 17:45:00 (Horário de Brasília)
// Localização: Cabo de Santo Agostinho, Pernambuco, Brasil

$page_title = "Gerenciar Ofertas";
require_once 'includes/header.php';

// --- Validação de Sessão ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'business' || !isset($_SESSION['business_id'])) {
    header("Location: login.php");
    exit;
}
// --- Fim da Validação ---
?>
<style>
    /* --- Estilos Gerais da Página (mantidos e ajustados) --- */
    .page-header {
        background-color: var(--color-background-elevated);
        padding: 1rem;
        margin: -1rem -1rem 1.5rem -1rem;
        box-shadow: var(--box-shadow-light);
        position: sticky; top: 0; z-index: 1020;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .page-title { font-size: 1.125rem; font-weight: 600; margin-bottom: 0; }
    .btn-add-new {
        font-size: 1.5rem; padding: 0.5rem 0.75rem; line-height: 1;
        border-radius: var(--border-radius-standard);
    }
    .form-add-offer-card {
        border-radius: var(--border-radius-large);
        box-shadow: var(--box-shadow-light);
        border: none;
    }
    .list-group-item .offer-info strong { font-weight: 500; color: var(--color-text-primary); }
    .list-group-item .offer-info small { color: var(--color-text-secondary); }
    .list-group-item .btn-group .btn { padding: 0.3rem 0.6rem; }
    .list-group-item .btn-group .btn i { font-size: 0.9rem; }

    .custom-file-upload-wrapper { margin-bottom: 1rem; }
    .custom-file-upload {
        border: 2px dashed var(--color-separator-dark); border-radius: var(--border-radius-standard);
        padding: 1.5rem; text-align: center; cursor: pointer;
        transition: border-color 0.2s ease, background-color 0.2s ease;
        background-color: #fdfdfd;
    }
    .custom-file-upload:hover { border-color: var(--color-primary-red); background-color: #fff7f7; }
    .custom-file-upload i { font-size: 2.5rem; color: var(--color-primary-red); margin-bottom: 0.75rem; }
    .custom-file-upload .file-upload-text { font-weight: 500; color: var(--color-text-primary); display: block; margin-bottom: 0.25rem; }
    .custom-file-upload .file-upload-hint { font-size: 0.875rem; color: var(--color-text-secondary); }
    .custom-file-upload-wrapper input[type="file"] { display: none; }
    .image-preview {
        max-width: 150px; max-height: 150px; border-radius: var(--border-radius-standard);
        margin-top: 10px; border: 1px solid var(--color-separator-light); object-fit: cover;
    }
    #current-image-info img {
        max-width: 100px; max-height: 100px; border-radius: var(--border-radius-standard);
        margin-top: 5px; border: 1px solid var(--color-separator-light);
    }

    /* --- Estilos para Modal Deslizante Estilo iOS (COM CORREÇÃO DE SCROLL) --- */
    .modal.modal-bottom-sheet .modal-dialog {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 0;
        max-width: 100%;
        width: 100%;
        transform: translateY(100%);
        transition: transform 0.3s ease-out;
        /* Importante: altura do dialog para o conteúdo interno poder rolar */
        height: 90vh; /* Ou um valor um pouco menor, ex: 85vh */
        max-height: 90vh;
        display: flex; /* Para alinhar o modal-content no final (bottom) */
        flex-direction: column;
        justify-content: flex-end; /* Alinha o modal-content na parte de baixo */
    }
    .modal.modal-bottom-sheet.show .modal-dialog {
        transform: translateY(0);
    }
    .modal.modal-bottom-sheet .modal-content {
        border-radius: var(--border-radius-large) var(--border-radius-large) 0 0;
        border: none;
        box-shadow: 0 -5px 20px rgba(0,0,0,0.15);
        /* --- MUDANÇAS AQUI para o scroll --- */
        display: flex;
        flex-direction: column;
        /* A altura máxima é controlada pelo modal-dialog agora,
           mas podemos definir uma max-height aqui também se necessário,
           ou deixar que o flexbox cuide disso. */
        width: 100%; 
        /* max-height: 100%; */ /* Ocupa a altura do modal-dialog */
        overflow: hidden; /* Evita scroll no modal-content, o body fará isso */
    }
    .modal.modal-bottom-sheet .modal-header {
        border-bottom: 1px solid var(--color-separator-light);
        padding: 0.75rem 1.25rem;
        position: relative;
        flex-shrink: 0; /* Não encolhe */
    }
    .modal.modal-bottom-sheet .modal-header::before {
        content: ''; position: absolute; top: 8px; left: 50%;
        transform: translateX(-50%); width: 40px; height: 5px;
        background-color: var(--color-separator-dark); border-radius: 2.5px;
    }
    .modal.modal-bottom-sheet .modal-title {
        font-size: 1.125rem; font-weight: 600; text-align: center;
        width: 100%; padding-top: 10px;
    }
    .modal.modal-bottom-sheet .btn-close {
        position: absolute; top: 10px; right: 10px;
        font-size: 0.8rem; z-index: 10;
    }
    .modal.modal-bottom-sheet .modal-body {
        padding: 1.25rem;
        /* --- MUDANÇAS AQUI para o scroll --- */
        overflow-y: auto; /* Permite scroll vertical APENAS no body */
        flex-grow: 1; /* Faz o body ocupar o espaço restante */
        -webkit-overflow-scrolling: touch; /* Scroll suave no iOS */
    }
    .modal.modal-bottom-sheet .modal-footer {
        border-top: 1px solid var(--color-separator-light);
        padding: 1rem 1.25rem;
        flex-shrink: 0; /* Não encolhe */
    }
    .modal-backdrop.show {
        opacity: 0.3;
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">Minhas Ofertas</h2>
        <button class="btn btn-primary-red btn-add-new" type="button" data-bs-toggle="collapse" data-bs-target="#addOfferCollapse" aria-expanded="false" aria-controls="addOfferCollapse" title="Adicionar Nova Oferta">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>

    <div class="collapse" id="addOfferCollapse">
      <div class="card card-body mb-3 form-add-offer-card">
          <h4 class="mb-3 fw-semibold">Nova Oferta</h4>
          <form id="add-offer-form" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="offer-title" class="form-label fw-medium">Título da Oferta</label>
                    <input type="text" class="form-control" id="offer-title" name="title" placeholder="Ex: Sacola Surpresa do Dia" required>
                </div>
                <div class="mb-3">
                    <label for="offer-description" class="form-label fw-medium">Descrição</label>
                    <textarea class="form-control" id="offer-description" name="description" rows="3" placeholder="Detalhes sobre o que pode conter na sacola..."></textarea>
                </div>
                <div class="custom-file-upload-wrapper">
                    <label for="offer-image" class="form-label fw-medium">Foto da Oferta</label>
                    <label for="offer-image" class="custom-file-upload d-block">
                        <i class="bi bi-camera-fill"></i>
                        <span class="file-upload-text">Clique para escolher uma foto</span>
                        <span class="file-upload-hint">PNG, JPG, GIF, WEBP até 5MB</span>
                    </label>
                    <input class="form-control" type="file" id="offer-image" name="offer_image" accept="image/*" onchange="previewImage(event, 'add-preview-img')">
                    <img id="add-preview-img" src="#" alt="Pré-visualização" class="image-preview d-none"/>
                </div>
                 <div class="mb-3">
                    <label for="offer-category" class="form-label fw-medium">Categoria</label>
                    <select id="offer-category" name="category_id" class="form-select" required>
                        <option value="">Carregando...</option>
                    </select>
                </div>
                 <div class="row g-3">
                    <div class="col-md-6 mb-3"><label for="offer-price" class="form-label fw-medium">Preço (R$)</label><input type="number" step="0.01" min="0" class="form-control" id="offer-price" name="price" placeholder="Ex: 9.99" required></div>
                    <div class="col-md-6 mb-3"><label for="offer-quantity" class="form-label fw-medium">Quantidade</label><input type="number" min="1" class="form-control" id="offer-quantity" name="quantity" placeholder="Ex: 5" required></div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 mb-3"><label for="offer-start" class="form-label fw-medium">Início da Coleta</label><input type="datetime-local" class="form-control" id="offer-start" name="pickup_start" required></div>
                    <div class="col-md-6 mb-3"><label for="offer-end" class="form-label fw-medium">Fim da Coleta</label><input type="datetime-local" class="form-control" id="offer-end" name="pickup_end" required></div>
                </div>
                <button type="submit" class="btn btn-primary-red w-100 btn-lg mt-2"><i class="bi bi-check-circle-fill"></i> Salvar Nova Oferta</button>
                <div id="add-offer-message" class="mt-3 alert d-none"></div>
          </form>
      </div>
    </div>

    <div id="business-offers-list" class="list-group">
        <div class="list-group-item text-center loading-placeholder">
            <div class="spinner-border" style="color: var(--color-primary-yellow);" role="status"></div>
            <p class="mt-2">Carregando suas ofertas...</p>
        </div>
    </div>
</div>

<div class="modal fade modal-bottom-sheet" id="editOfferModal" tabindex="-1" aria-labelledby="editOfferModalLabel" aria-hidden="true" data-bs-backdrop="true">
    <div class="modal-dialog">
        <div class="modal-content"> <form id="edit-offer-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOfferModalLabel">Editar Oferta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> <input type="hidden" id="edit-offer-id" name="offer_id">
                    <div class="mb-3"><label for="edit-offer-title" class="form-label fw-medium">Título</label><input type="text" class="form-control" id="edit-offer-title" name="title" required></div>
                    <div class="mb-3"><label for="edit-offer-description" class="form-label fw-medium">Descrição</label><textarea class="form-control" id="edit-offer-description" name="description" rows="3"></textarea></div>
                    <div class="custom-file-upload-wrapper">
                        <label for="edit-offer-image-input" class="form-label fw-medium">Nova Foto (Opcional)</label>
                        <label for="edit-offer-image-input" class="custom-file-upload d-block">
                            <i class="bi bi-arrow-repeat"></i>
                            <span class="file-upload-text">Clique para trocar a foto</span>
                            <span class="file-upload-hint">Deixe em branco para manter a atual</span>
                        </label>
                        <input class="form-control" type="file" id="edit-offer-image-input" name="offer_image" accept="image/*" onchange="previewImage(event, 'edit-preview-img')">
                        <div id="current-image-info" class="mt-2 text-center"></div>
                        <img id="edit-preview-img" src="#" alt="Nova pré-visualização" class="image-preview d-none mx-auto"/>
                    </div>
                    <div class="mb-3"><label for="edit-offer-category" class="form-label fw-medium">Categoria</label><select id="edit-offer-category" name="category_id" class="form-select" required><option value="">Carregando...</option></select></div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3"><label for="edit-offer-price" class="form-label fw-medium">Preço</label><input type="number" step="0.01" min="0" class="form-control" id="edit-offer-price" name="price" required></div>
                        <div class="col-md-6 mb-3"><label for="edit-offer-quantity" class="form-label fw-medium">Qtd.</label><input type="number" min="0" class="form-control" id="edit-offer-quantity" name="quantity" required></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3"><label for="edit-offer-start" class="form-label fw-medium">Início</label><input type="datetime-local" class="form-control" id="edit-offer-start" name="pickup_start" required></div>
                        <div class="col-md-6 mb-3"><label for="edit-offer-end" class="form-label fw-medium">Fim</label><input type="datetime-local" class="form-control" id="edit-offer-end" name="pickup_end" required></div>
                    </div>
                    <div class="mb-3"><label for="edit-offer-status" class="form-label fw-medium">Status</label><select id="edit-offer-status" name="status" class="form-select"><option value="active">Ativa</option><option value="inactive">Inativa</option><option value="sold_out">Esgotada</option></select></div>
                    <div id="edit-offer-message" class="mt-3 alert d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-subtle" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-red">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/nav_estabelecimento.php'; ?>
<script>
    function previewImage(event, previewElementId) { /* ...código da função como antes... */ }
</script>
<script src="js/business.js?v=<?php echo time(); ?>"></script>
<?php require_once 'includes/footer.php'; ?>
