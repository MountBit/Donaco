// Funções de Máscara Monetária
function initMoneyMask() {
    const moneyInputs = document.querySelectorAll('.money');
    if (!moneyInputs.length) return;

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
            
            // Formatar com pontos e vírgulas
            value = value.replace('.', ',');
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            
            e.target.value = value;
        });

        // Adicionar tratamento para o envio do formulário
        input.form?.addEventListener('submit', function(e) {
            const value = input.value;
            // Converter de volta para o formato que o backend espera
            const numericValue = value.replace(/\./g, '').replace(',', '.');
            input.value = numericValue;
        });
    });
}

// Funções do Modal
const DonationModal = {
    modal: null,
    form: null,
    initialized: false,

    init() {
        // Evitar inicialização múltipla
        if (this.initialized) return;

        // Verificar se estamos na página correta e se o modal existe
        const modal = document.getElementById('donation-modal');
        const form = document.getElementById('donation-form');

        // Se não encontrar os elementos necessários, não inicializa
        if (!modal || !form) return;

        this.modal = modal;
        this.form = form;
        this.initialized = true;

        this.setupEventListeners();
        this.setupFormValidation();
    },

    show() {
        if (!this.modal) return; // Verificação de segurança
        this.modal.classList.remove('hidden');
        const nicknameInput = this.modal.querySelector('input[name="nickname"]');
        if (nicknameInput) nicknameInput.focus();
        document.body.style.overflow = 'hidden';
    },

    hide() {
        if (!this.modal) return; // Verificação de segurança
        this.modal.classList.add('hidden');
        if (this.form) this.form.reset();
        document.body.style.overflow = '';
    },

    setupEventListeners() {
        // Fechar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal && !this.modal.classList.contains('hidden')) {
                this.hide();
            }
        });

        // Fechar ao clicar fora
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.hide();
                }
            });
        }

        // Inicializar máscara monetária
        if (typeof initMoneyMask === 'function') {
            initMoneyMask();
        }
    },

    setupFormValidation() {
        if (!this.form) return;

        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (!this.form.checkValidity()) {
                e.stopPropagation();
                this.form.classList.add('was-validated');
                return;
            }

            const submitButton = this.form.querySelector('button[type="submit"]');
            if (!submitButton) return;

            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';

            const formData = new FormData(this.form);
            
            // Converter valor para formato correto
            let value = formData.get('value');
            if (value) {
                value = value.replace(/\./g, '').replace(',', '.');
                formData.set('value', value);
            }

            fetch(this.form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json();
                
                if (!response.ok || data.status === 'error') {
                    throw new Error(data.message || 'Erro ao salvar doação');
                }
                
                // Sucesso
                this.hide();
                
                // Mostrar notificação usando o componente de notificação
                if (typeof showNotification === 'function') {
                    showNotification(data.message, 'success');
                }

                // Recarregar a página após um pequeno delay
                setTimeout(() => {
                    window.location.href = '/admin/donations';
                }, 1000);
            })
            .catch(error => {
                const alertElement = document.getElementById('donation-form-alert');
                if (alertElement) {
                    alertElement.textContent = error.message;
                    alertElement.classList.remove('hidden');
                }

                // Mostrar erro usando o componente de notificação
                if (typeof showNotification === 'function') {
                    showNotification(error.message, 'error');
                }

                console.error('Erro ao salvar doação:', error);
            })
            .finally(() => {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            });
        });
    }
};

// Inicialização condicional
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar máscara monetária se houver campos que precisem
    const moneyInputs = document.querySelectorAll('.money');
    if (moneyInputs.length > 0) {
        initMoneyMask();
    }
});

// Funções globais para o modal com inicialização sob demanda
window.showDonationModal = () => {
    // Inicializar o modal apenas quando for necessário
    if (!DonationModal.initialized) {
        DonationModal.init();
    }

    if (DonationModal.modal) {
        DonationModal.show();
    }
};

window.hideDonationModal = () => {
    if (DonationModal.initialized && DonationModal.modal) {
        DonationModal.hide();
    }
}; 