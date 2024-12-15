// Adicionar este estilo CSS no início do arquivo, junto com os outros estilos
const style = document.createElement('style');
style.textContent = `
.payment-option label {
    cursor: pointer;
}

.payment-option input[type="radio"] {
    cursor: pointer;
}

.copy-tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 5px 10px;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    border-radius: 4px;
    font-size: 12px;
    margin-bottom: 5px;
    white-space: nowrap;
    pointer-events: none;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translate(-50%, 10px); }
    to { opacity: 1; transform: translate(-50%, 0); }
}

.btn-outline-secondary.copied {
    background-color: #198754;
    color: white;
    border-color: #198754;
}

.btn-outline-secondary.copied:hover {
    background-color: #157347;
    border-color: #157347;
}

/* Estilização da barra de rolagem para o modal */
.modal-body {
    scrollbar-width: thin; /* Para Firefox */
    scrollbar-color: rgba(0, 0, 0, 0.2) transparent; /* Para Firefox */
}

.modal-body::-webkit-scrollbar {
    width: 6px; /* Largura da barra de rolagem */
}

.modal-body::-webkit-scrollbar-track {
    background: transparent; /* Fundo da trilha */
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2); /* Cor da barra */
    border-radius: 10px;
    border: 2px solid transparent;
    background-clip: padding-box;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.3); /* Cor da barra ao passar o mouse */
}

/* Garantir que o modal tenha altura máxima adequada */
.modal-dialog {
    max-height: 90vh;
    margin: 1.75rem auto;
}

.modal-content {
    max-height: calc(90vh - 3.5rem);
}

.modal-body {
    max-height: calc(90vh - 12rem);
    overflow-y: auto;
    overflow-x: hidden;
}
`;
document.head.appendChild(style);

// Project Stats Carousel
function initProjectStatsCarousel() {
    const projects = document.querySelectorAll('.project-stats');
    if (projects.length <= 1) return;

    let currentIndex = 0;
    projects[currentIndex].classList.add('active');

    setInterval(() => {
        projects[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % projects.length;
        projects[currentIndex].classList.add('active');
    }, 8000);
}

// Money Mask
function initMoneyMask() {
    const moneyInputs = document.querySelectorAll('.money');
    moneyInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remover tudo que não é número
            value = value.replace(/\D/g, '');
            
            // Se o valor estiver vazio, retornar
            if (value === '') {
                e.target.value = '';
                return;
            }
            
            // Converter para decimal mantendo os centavos
            value = (parseFloat(value) / 100).toFixed(2);
            
            // Formatar com pontos e vírgulas para exibição
            value = value.replace('.', ',');
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            
            e.target.value = value;
        });
    });
}

// Payment Status Check
async function checkDonationStatus(externalReference) {
    try {
        const response = await fetch(`/donations/status/${externalReference}`);
        const result = await response.json();

        return result;
    } catch (error) {
        console.error('Error checking donation status:', error);
        return null;
    }
}

// Start periodic status check
function startPaymentStatusCheck(externalReference) {
    const statusCheckInterval = 15000; // 15 seconds
    const maxAttempts = 40; // Total check time: 10 minutes
    let attempts = 0;

    const statusCheckTimer = setInterval(async () => {
        attempts++;

        try {
            const statusResult = await checkDonationStatus(externalReference);

            if (statusResult && statusResult.status === 'approved') {
                // Payment approved
                clearInterval(statusCheckTimer);
                handleSuccessfulPayment(statusResult);
                return;
            }

            // Stop checking after max attempts
            if (attempts >= maxAttempts) {
                clearInterval(statusCheckTimer);
                handlePaymentTimeout();
            }
        } catch (error) {
            console.error('Status check error:', error);
        }
    }, statusCheckInterval);
}

// Handle successful payment
function handleSuccessfulPayment(statusResult) {
    const modalBodyPayment = document.getElementById('modal-body-payment');
    const modalBodyApproved = document.getElementById('modal-body-approved');

    // Hide payment content
    if (modalBodyPayment) {
        modalBodyPayment.classList.add('d-none');
    }

    // Show approved content
    if (modalBodyApproved) {
        modalBodyApproved.classList.remove('d-none');
    }
}

// Handle payment timeout
function handlePaymentTimeout() {
    const timeoutModal = document.getElementById('payment-timeout-modal');
    
    if (timeoutModal) {
        new bootstrap.Modal(timeoutModal).show();
    }
}

// Form Validation and Submission
function initDonationForm() {
    const form = document.getElementById('donationForm');
    if (!form) return;

    const loadingSpinner = document.getElementById('loading');
    const paymentContent = document.getElementById('payment-content');
    const pixCodeTextarea = document.getElementById('code-pix');
    const copyButton = document.getElementById('copyButton');

    // Validação do tamanho do arquivo
    const proofFileInput = document.getElementById('proof_file');
    if (proofFileInput) {
        proofFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB em bytes

            if (file && file.size > maxSize) {
                alert('O arquivo não pode ter mais que 2MB');
                e.target.value = '';
            }
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        // Garantir que o método de pagamento está correto
        let paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        let paymentMethodValue = 'mercadopago'; // Valor padrão

        // Se PIX Manual estiver habilitado, validar a seleção
        if (manualPaymentEnabled) {
            if (!paymentMethod) {
                const alertElement = document.getElementById('alert-donation');
                alertElement.classList.remove('d-none');
                alertElement.textContent = 'Por favor, selecione um método de pagamento.';
                return;
            }
            paymentMethodValue = paymentMethod.value;
        }
        
        // Remover e readicionar o método de pagamento para garantir o valor correto
        formData.delete('payment_method');
        formData.append('payment_method', paymentMethodValue);
        
        // Converter o valor corretamente
        let value = formData.get('value');
        if (value) {
            value = value.replace(/\./g, '').replace(',', '.');
            value = parseFloat(value);
            formData.set('value', value);
        }

        // Validar se é pagamento manual e tem comprovante
        if (paymentMethodValue === 'manual') {
            const proofFile = document.getElementById('proof_file').files[0];
            if (!proofFile) {
                const alertElement = document.getElementById('alert-donation');
                alertElement.classList.remove('d-none');
                alertElement.textContent = 'Por favor, envie um comprovante de pagamento.';
                return;
            }
        }

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processando...';

        fetch(donationStoreUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Resposta não é JSON válido');
            }
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Erro ao processar doação');
            }
            
            return data;
        })
        .then(response => {
            if (response.payment_method === 'manual') {
                document.getElementById('modal-body-payer').classList.add('d-none');
                const approvedModal = document.getElementById('modal-body-approved');
                approvedModal.classList.remove('d-none');
                approvedModal.innerHTML = `
                    <div class="text-center">
                        <div class="success-animation mb-3">
                            <i class="fas fa-check-circle text-success fa-3x"></i>
                        </div>
                        <h5 class="mb-3">Doação Registrada!</h5>
                        <p class="mb-0 text-muted">
                            Seu comprovante foi enviado com sucesso.<br>
                            Aguarde a aprovação da sua doação.
                        </p>
                    </div>
                `;
            } else {
                document.getElementById('modal-body-payer').classList.add('d-none');
                document.getElementById('modal-body-payment').classList.remove('d-none');
                
                // Exibir QR Code
                if (response.qr_code_base64) {
                    const qrCodeImg = document.getElementById('image-qrcode-pix');
                    qrCodeImg.src = response.qr_code_base64.startsWith('data:image') 
                        ? response.qr_code_base64 
                        : `data:image/png;base64,${response.qr_code_base64}`;
                    qrCodeImg.style.display = 'block';
                }
                
                // Definir código PIX
                if (response.code) {
                    document.getElementById('code-pix').value = response.code;
                }
                
                // Iniciar verificação de pagamento
                if (response.external_reference) {
                    startPaymentCheck(response.external_reference);
                }
            }
        })
        .catch(error => {
            console.error('Erro completo:', error);
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            const alertElement = document.getElementById('alert-donation');
            alertElement.classList.remove('d-none');
            alertElement.textContent = error.message || 'Erro ao processar a doação';
        });
    });
}

function adjustTextareaHeight(textarea) {
    // Use CSS-defined height instead of dynamic resizing
    textarea.style.height = '100px';
}

// Função para copiar o código PIX
function initCopyButton() {
    const pixCodeTextarea = document.getElementById('code-pix');
    const copyButton = document.getElementById('copyButton');

    if (!pixCodeTextarea || !copyButton) return;

    // Ajustar altura do textarea
    adjustTextareaHeight(pixCodeTextarea);

    copyButton.innerHTML = '<i class="fas fa-copy"></i>';
    
    copyButton.onclick = async function() {
        try {
            // Usar a nova API Clipboard
            await navigator.clipboard.writeText(pixCodeTextarea.value);
            
            // Feedback visual
        copyButton.classList.add('copied');
        copyButton.innerHTML = '<i class="fas fa-check"></i>';
        
            // Mostrar tooltip ou mensagem
            const tooltip = document.createElement('div');
            tooltip.className = 'copy-tooltip';
            tooltip.textContent = 'Código copiado!';
            copyButton.appendChild(tooltip);
            
            // Reset após 2 segundos
        setTimeout(() => {
            copyButton.classList.remove('copied');
                copyButton.innerHTML = '<i class="fas fa-copy"></i>';
                if (tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
            }, 2000);
        } catch (err) {
            // Fallback para o método antigo se a API Clipboard não estiver disponível
            pixCodeTextarea.select();
            try {
                document.execCommand('copy');
                copyButton.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
            copyButton.innerHTML = '<i class="fas fa-copy"></i>';
        }, 2000);
            } catch (e) {
                console.error('Erro ao copiar:', e);
                alert('Não foi possível copiar o código automaticamente. Por favor, copie manualmente.');
            }
        }
    };
}

// Garantir que o botão de copiar seja inicializado quando o modal de pagamento for mostrado
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-donation');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            initCopyButton();
        });
    }
    
    // Também inicializar normalmente
    initCopyButton();
});

function displayQRCode(base64Image) {
    const qrCodeImg = document.getElementById('image-qrcode-pix');
    
    // Ensure the base64 image has the correct data URL prefix
    const formattedBase64 = base64Image.startsWith('data:image') 
        ? base64Image 
        : `data:image/png;base64,${base64Image}`;
    
    qrCodeImg.src = formattedBase64;
    qrCodeImg.style.display = 'block';
}

// Initialize Modal with Refresh
function initDonationModal() {
    const approvedModal = document.getElementById('modal-body-approved');
    const donationModal = document.getElementById('modal-donation');
    
    if (donationModal) {
        // Reset form quando o modal for fechado
        donationModal.addEventListener('hidden.bs.modal', function () {
            // Verificar se o modal de aprovação estava visível
            const shouldRefresh = !approvedModal.classList.contains('d-none');
            
            // Resetar o formulário
            resetDonationForm();
            
            // Recarregar a página se necessário
            if (shouldRefresh) {
                window.location.reload();
            }
        });

        // Reset form quando o modal for aberto
        donationModal.addEventListener('show.bs.modal', function () {
            resetDonationForm();
        });
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initProjectStatsCarousel();
    initPaymentMethodSelector();
    initDonationForm();
    initDonationModal();
    initMoneyMask();

    // Check for external reference in URL
    const urlParams = new URLSearchParams(window.location.search);
    const externalReference = urlParams.get('external_reference');
    if (externalReference) {
        checkDonationStatus(externalReference);
    }
});

// Adicionar esta função no início do arquivo
function validateManualPayment(form) {
    const paymentMethod = form.querySelector('input[name="payment_method"]:checked').value;
    const proofFile = form.querySelector('#proof_file');
    
    if (paymentMethod === 'manual') {
        if (!proofFile.files || !proofFile.files[0]) {
            proofFile.setCustomValidity('Por favor, envie um comprovante de pagamento.');
            return false;
        }
        proofFile.setCustomValidity('');
    } else {
        proofFile.setCustomValidity('');
    }
    return true;
}

// Adicionar função para gerenciar o método de pagamento
function initPaymentMethodSelector() {
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const manualInfo = document.getElementById('manual-payment-info');
    const mercadopagoInfo = document.querySelector('.mercadopago-info');
    const manualInfoText = document.querySelector('.manual-info');
    const proofFile = document.getElementById('proof_file');
    const alertElement = document.getElementById('alert-donation');
    const manualPaymentOption = document.querySelector('.payment-option[data-method="manual"]');
    const paymentMethodSelector = document.querySelector('.payment-method-selector');

    // Controlar visibilidade do PIX Manual baseado na configuração
    if (!manualPaymentEnabled) {
        // Ocultar todo o seletor de método de pagamento
        if (paymentMethodSelector) {
            paymentMethodSelector.style.display = 'none';
        }

        // Ocultar opção de PIX Manual
        if (manualPaymentOption) {
            manualPaymentOption.parentElement.style.display = 'none';
        }

        // Garantir que Mercado Pago esteja selecionado
        const mercadoPagoRadio = document.getElementById('mercadopago');
        if (mercadoPagoRadio) {
            mercadoPagoRadio.checked = true;
            updatePaymentView('mercadopago', false);
        }

        // Mostrar informações do Mercado Pago
        if (mercadopagoInfo) {
            mercadopagoInfo.classList.remove('d-none');
        }
    }

    // Função para atualizar a visualização baseada no método selecionado
    function updatePaymentView(method, showAlert = false) {
        if (method === 'manual') {
            // Mostrar alerta elegante apenas se showAlert for true
            if (showAlert) {
                Swal.fire({
                    title: 'Atenção!',
                    html: `
                        <div class="text-start">
                            <p>Para fazer uma doação PIX Manual, siga estes passos:</p>
                            <ol>
                                <li>Preencha o formulário.</li>
                                <li>A Chave PIX se encontra após "Valor da doação".</li>
                                <li>Realize a transferência PIX.</li>
                                <li>Salve o comprovante de pagamento.</li>
                                <li>Anexe o comprovante no formulário.</li>
                                <li>Clique em "Continuar" para finalizar a doação.</li>
                            </ol>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Entendi',
                    confirmButtonColor: '#ffc107',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            }

            manualInfo.classList.remove('d-none');
            mercadopagoInfo.classList.add('d-none');
            manualInfoText.classList.remove('d-none');
            proofFile.setAttribute('required', 'required');
        } else {
            manualInfo.classList.add('d-none');
            mercadopagoInfo.classList.remove('d-none');
            manualInfoText.classList.add('d-none');
            proofFile.removeAttribute('required');
        }
    }

    // Adicionar evento change para os radios
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            alertElement.classList.add('d-none');
            updatePaymentView(this.value, true);
        });
    });

    // Verificar estado inicial quando o modal é aberto
    const modal = document.getElementById('modal-donation');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            if (selectedMethod) {
                updatePaymentView(selectedMethod.value, false);
            }
        });
    }
}

// Payment Status Check
function startPaymentCheck(externalReference) {
    const checkInterval = paymentCheckConfig.interval || 5000; // Fallback para 5 segundos
    const maxTime = paymentCheckConfig.maxTime || 300000; // Fallback para 5 minutos
    const maxAttempts = Math.floor(maxTime / checkInterval);
    let attempts = 0;

    const checkStatus = async () => {
        try {
            const result = await checkDonationStatus(externalReference);
            
            if (result && result.status === 'approved') {
                // Doação aprovada
                document.getElementById('modal-body-payment').classList.add('d-none');
                const approvedModal = document.getElementById('modal-body-approved');
                approvedModal.classList.remove('d-none');
                approvedModal.innerHTML = `
                    <div class="text-center">
                        <div class="success-animation mb-3">
                            <i class="fas fa-check-circle text-success fa-3x"></i>
                        </div>
                        <h5 class="mb-3">Doação Aprovada!</h5>
                        <p class="mb-0 text-muted">
                            Obrigado por sua contribuição.<br>
                            Sua doação foi processada com sucesso.
                        </p>
                    </div>
                `;
                return;
            }

            attempts++;
            if (attempts < maxAttempts) {
                setTimeout(checkStatus, checkInterval);
            } else {
                // Opcional: mostrar mensagem para o usuário
                const approvedModal = document.getElementById('modal-body-approved');
                approvedModal.classList.remove('d-none');
                approvedModal.innerHTML = `
                    <div class="text-center">
                        <div class="warning-animation mb-3">
                            <i class="fas fa-clock text-warning fa-3x"></i>
                        </div>
                        <h5 class="mb-3">Aguardando Confirmação</h5>
                        <p class="mb-0 text-muted">
                            O status do seu pagamento ainda está sendo processado.<br>
                            Você receberá uma confirmação por email quando for aprovado.
                        </p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Erro ao verificar status:', error);
            // Continuar tentando mesmo com erro
            if (attempts < maxAttempts) {
                setTimeout(checkStatus, checkInterval);
            }
        }
    };

    // Iniciar verificação
    checkStatus();
}

// Adicionar esta função no início do arquivo
function resetDonationForm() {
    const form = document.getElementById('donationForm');
    const mercadoPagoRadio = document.getElementById('mercadopago');
    const alertElement = document.getElementById('alert-donation');
    
    if (form) {
        form.reset();
        form.classList.remove('was-validated');
    }
    
    // Sempre resetar para Mercado Pago se PIX Manual estiver desabilitado
    if (mercadoPagoRadio && !manualPaymentEnabled) {
        mercadoPagoRadio.checked = true;
        mercadoPagoRadio.dispatchEvent(new Event('change'));
    }
    
    if (alertElement) {
        alertElement.classList.add('d-none');
    }
}
