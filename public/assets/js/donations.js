// Namespace para doações
const DonationHandler = {
    modal: null,
    currentPaymentMethod: null,
    templates: {
        manual: `
            <div class="pix-info-box p-3 bg-light rounded mb-3">
                <h6 class="mb-2">Dados para transferência PIX</h6>
                <p class="mb-1"><strong>Chave PIX:</strong> ${PIX_KEY}</p>
                <p class="mb-1"><strong>Banco:</strong> ${PIX_BANK}</p>
                <p class="mb-0"><strong>Beneficiário:</strong> ${PIX_BENEFICIARY}</p>
                <div class="d-flex items-center justify-between"><img src="${PIX_KEY_QR_CODE}" alt="qr code" class="mx-auto"/></div>
            </div>

            <div class="mb-3">
                <label for="nickname" class="form-label">Nome/Apelido</label>
                <input type="text" class="form-control" id="nickname" name="nickname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="form-text">Seu email não será compartilhado.</div>
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Valor da Doação</label>
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="text" class="form-control money" id="value" name="value" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Mensagem (opcional)</label>
                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="proof_file" class="form-label">Comprovante de Pagamento</label>
                <input type="file" class="form-control" id="proof_file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf" required>
                <div class="form-text">Formatos aceitos: JPG, PNG, PDF. Tamanho máximo: 2MB</div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Enviar Doação</button>
            </div>
        `,
        mercadopago: `
            <div class="mb-3">
                <label for="nickname" class="form-label">Nome/Apelido</label>
                <input type="text" class="form-control" id="nickname" name="nickname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="form-text">Seu email não será compartilhado.</div>
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Valor da Doação</label>
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="text" class="form-control money" id="value" name="value" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Mensagem (opcional)</label>
                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Continuar para Pagamento</button>
            </div>
        `
    },

    init() {
        this.modal = new bootstrap.Modal(document.getElementById('donationModal'));
        const modalHeader = document.querySelector('.modal-header');
        modalHeader.className = 'modal-header bg-warning text-dark rounded-top';
        const modalTitle = modalHeader.querySelector('.modal-title');
        modalTitle.innerHTML = '<i class="fas fa-hand-holding-heart"></i> Faça sua Doação';
        this.setupEventListeners();
        this.initializeMasks();
    },

    setupEventListeners() {
        const form = document.getElementById('donationForm');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        const modal = document.getElementById('donationModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => this.resetModalState());
        }
    },

    initializeMasks() {
        $('.money').mask('000.000.000,00', {reverse: true});
    },

    selectPaymentMethod(method) {
        // Limpar seleções anteriores
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });

        document.querySelectorAll('.payment-content').forEach(el => {
            el.style.display = 'none';
            el.innerHTML = '';
        });

        // Selecionar novo método
        const selectedMethod = document.querySelector(`.payment-method[data-method="${method}"]`);
        const contentElement = document.getElementById(`${method}-content`);

        if (selectedMethod && contentElement) {
            selectedMethod.classList.add('selected');
            document.getElementById('selected_payment_method').value = method;
            this.currentPaymentMethod = method;
            contentElement.innerHTML = this.templates[method];
            contentElement.style.display = 'block';

            // Reinicializar máscaras
            this.initializeMasks();
        }
    },

    resetModalState() {
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });
        document.querySelectorAll('.payment-content').forEach(el => {
            el.style.display = 'none';
            el.innerHTML = '';
        });
        const paymentMethodInput = document.getElementById('selected_payment_method');
        if (paymentMethodInput) {
            paymentMethodInput.value = '';
            this.currentPaymentMethod = null;
        }

        const form = document.getElementById('donationForm');
        if (form) form.reset();
    },

    handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data.qr_code) {
                    this.showMercadoPagoPixInfo(data.data);
                } else {
                    this.showSuccessModal();
                    // Adicionar evento para recarregar a página ao fechar o modal para PIX Manual
                    if (this.currentPaymentMethod === 'manual') {
                        const modal = document.getElementById('donationModal');
                        if (modal) {
                            const reloadHandler = () => {
                                window.location.reload();
                                modal.removeEventListener('hidden.bs.modal', reloadHandler);
                            };
                            modal.addEventListener('hidden.bs.modal', reloadHandler);
                        }
                    }
                }
            } else {
                throw new Error(data.error || 'Erro ao processar doação');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger mt-3';
            alertDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${error.message}`;
            form.insertBefore(alertDiv, form.firstChild);
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    },

    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
        errorDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const form = document.getElementById('donationForm');
        form.insertBefore(errorDiv, form.firstChild);

        setTimeout(() => errorDiv.remove(), 5000);
    },

    // Método para mostrar informações do PIX
    showPixInfo(pixData) {
        const modalBody = document.querySelector('.modal-body');
        modalBody.innerHTML = `
            <div class="text-center">
                <h4 class="mb-4">Escaneie o QR Code para pagar</h4>
                <div class="qr-code-container mb-4">
                    <img src="data:image/png;base64,${pixData.qr_code_base64}" alt="QR Code PIX">
                </div>
                <div class="pix-code-container">
                    <div class="input-group mb-3">
                        <textarea class="form-control" rows="3" readonly>${pixData.qr_code}</textarea>
                        <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText('${pixData.qr_code}')">
                            Copiar
                        </button>
                    </div>
                </div>
                <p class="text-muted mt-3">
                    Após o pagamento, você receberá a confirmação por e-mail.
                </p>
            </div>
        `;
    },

    showSuccessModal() {
        // Preservar classes originais do modal
        const modalDialog = document.querySelector('.modal-dialog');
        const originalDialogClasses = modalDialog.className;
        const modalContent = document.querySelector('.modal-content');
        const originalContentClasses = modalContent.className;

        // Verificar o método de pagamento
        const successMessage = this.currentPaymentMethod === 'mercadopago'
            ? `
                <h4 class="mb-3">Obrigado pela sua doação!</h4>
                <p class="mb-4">
                    Sua doação foi registrada e confirmada com sucesso.
                    Em breve aparecerá na lista de doações do site.
                </p>
            `
            : `
                <h4 class="mb-3">Obrigado pela sua doação!</h4>
                <p class="mb-4">
                    Sua doação foi registrada com sucesso e está pendente de aprovação.
                    Assim que for aprovada, aparecerá na lista de doações do site.
                </p>
            `;

        // Limpar o conteúdo do modal atual
        const modalBody = document.querySelector('.modal-body');
        modalBody.innerHTML = `
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-heart text-danger" style="font-size: 48px;"></i>
                </div>
                ${successMessage}
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    Em caso de dúvidas, entre em contato conosco através do e-mail de suporte.
                </div>
            </div>
        `;

        // Atualizar o header do modal
        const modalHeader = document.querySelector('.modal-header');
        modalHeader.className = 'modal-header bg-success text-white rounded-top';
        const modalTitle = modalHeader.querySelector('.modal-title');
        modalTitle.innerHTML = '<i class="fas fa-check-circle"></i> Doação Registrada!';

        // Restaurar classes originais
        modalDialog.className = originalDialogClasses;
        modalContent.className = originalContentClasses;

        // Ajustar botão close para ser branco
        const closeButton = modalHeader.querySelector('.btn-close');
        closeButton.classList.add('btn-close-white');
    },

    showMercadoPagoPixInfo(pixData) {
        // Preservar classes originais do modal
        const modalDialog = document.querySelector('.modal-dialog');
        const originalDialogClasses = modalDialog.className;
        const modalContent = document.querySelector('.modal-content');
        const originalContentClasses = modalContent.className;

        const modalBody = document.querySelector('.modal-body');
        modalBody.innerHTML = `
            <div class="text-center">
                <h4 class="mb-4">Escaneie o QR Code para pagar</h4>
                <div class="qr-code-container mb-4">
                    <img src="data:image/png;base64,${pixData.qr_code_base64}" alt="QR Code PIX" class="img-fluid">
                </div>
                <div class="pix-code-container">
                    <div class="input-group mb-3">
                        <textarea class="form-control" rows="3" readonly>${pixData.qr_code}</textarea>
                        <button class="btn btn-outline-secondary" type="button" onclick="DonationHandler.copyToClipboard(this, '${pixData.qr_code}')">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                </div>
                <div class="alert alert-info mt-4" role="alert">
                    <i class="fas fa-info-circle"></i>
                    Aguardando pagamento...
                </div>
            </div>
        `;

        // Atualizar o header do modal
        const modalHeader = document.querySelector('.modal-header');
        modalHeader.className = 'modal-header bg-primary text-white rounded-top';
        const modalTitle = modalHeader.querySelector('.modal-title');
        modalTitle.innerHTML = '<i class="fas fa-qrcode"></i> PIX Mercado Pago';

        // Restaurar classes originais
        modalDialog.className = originalDialogClasses;
        modalContent.className = originalContentClasses;

        // Ajustar botão close para ser branco
        const closeButton = modalHeader.querySelector('.btn-close');
        closeButton.classList.add('btn-close-white');

        // Iniciar verificação de pagamento
        if (pixData.external_reference) {
            this.startPaymentCheck(pixData.external_reference);
        }
    },

    startPaymentCheck(externalReference) {
        let checkCount = 0;
        const maxChecks = paymentCheckConfig.maxTime / paymentCheckConfig.interval;

        const checkInterval = setInterval(() => {
            checkCount++;

            fetch(donationStatusRoute.replace(':externalReference', externalReference))
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'approved') {
                        clearInterval(checkInterval);
                        this.showSuccessModal();
                        // Adicionar evento para recarregar a página ao fechar o modal
                        const modal = document.getElementById('donationModal');
                        if (modal) {
                            const reloadHandler = () => {
                                window.location.reload();
                                modal.removeEventListener('hidden.bs.modal', reloadHandler);
                            };
                            modal.addEventListener('hidden.bs.modal', reloadHandler);
                        }
                    } else if (checkCount >= maxChecks) {
                        clearInterval(checkInterval);
                        const alertDiv = document.querySelector('.alert-info');
                        if (alertDiv) {
                            alertDiv.className = 'alert alert-warning mt-4';
                            alertDiv.innerHTML = `
                                <i class="fas fa-exclamation-triangle"></i>
                                Tempo de espera excedido. Se você já realizou o pagamento,
                                aguarde alguns instantes e atualize a página.
                            `;
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro ao verificar status:', error);
                    clearInterval(checkInterval);
                });
        }, paymentCheckConfig.interval);
    },

    copyToClipboard(button, text) {
        navigator.clipboard.writeText(text).then(() => {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copiado!';
            button.classList.add('btn-success');
            button.disabled = true;

            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.disabled = false;
            }, 2000);
        });
    }
};

// Inicialização
document.addEventListener('DOMContentLoaded', () => DonationHandler.init());

// Funções globais
window.openDonationModal = () => DonationHandler.modal.show();
window.selectPaymentMethod = (method) => DonationHandler.selectPaymentMethod(method);
