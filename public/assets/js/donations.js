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
    }, 5000);
}

// Money Mask
function initMoneyMask() {
    const moneyInputs = document.querySelectorAll('.money');
    moneyInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value;
            value = value.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
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

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processando...';

        const modalBodyPayer = document.getElementById('modal-body-payer');
        const modalBodyPayment = document.getElementById('modal-body-payment');
        const qrCodeImage = document.getElementById('image-qrcode-pix');

        try {
            const formData = new FormData(form);
            // Convert value from BR format to EN format for backend
            let value = formData.get('value');
            value = value.replace(/\./g, '').replace(',', '.');
            formData.set('value', value);

            const response = await fetch(donationStoreUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const result = await response.json();

            if (result.status === 'success') {
                // Hide payer form and show payment details
                modalBodyPayer.classList.add('d-none');
                modalBodyPayment.classList.remove('d-none');
                
                // Show loading spinner initially
                if (loadingSpinner) {
                    loadingSpinner.classList.remove('d-none');
                }
                if (paymentContent) {
                    paymentContent.classList.add('d-none');
                }

                // Set QR code and PIX details
                displayQRCode(result.qr_code_base64);
                if (pixCodeTextarea) {
                    pixCodeTextarea.value = result.code;
                }

                // Hide loading, show payment content
                if (loadingSpinner) {
                    loadingSpinner.classList.add('d-none');
                }
                if (paymentContent) {
                    paymentContent.classList.remove('d-none');
                }

                // Copy PIX code functionality
                initCopyButton();

                // Start periodic status check
                if (result.external_reference) {
                    startPaymentStatusCheck(result.external_reference);
                }
            } else {
                throw new Error(result.message || 'Erro ao processar doação');
            }
        } catch (error) {
            console.error('Erro:', error);
            const alertDiv = document.getElementById('alert-donation');
            alertDiv.textContent = error.message;
            alertDiv.classList.remove('d-none');
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    });
}

function adjustTextareaHeight(textarea) {
    // Use CSS-defined height instead of dynamic resizing
    textarea.style.height = '100px';
}

function initCopyButton() {
    const pixCodeTextarea = document.getElementById('code-pix');
    const copyButton = document.getElementById('copyButton');

    if (!pixCodeTextarea || !copyButton) return;

    // Adjust textarea height when content is set
    adjustTextareaHeight(pixCodeTextarea);

    copyButton.innerHTML = '<i class="fas fa-copy"></i>';
    
    copyButton.onclick = function() {
        pixCodeTextarea.select();
        document.execCommand('copy');
        
        // Visual feedback
        copyButton.classList.add('copied');
        copyButton.innerHTML = '<i class="fas fa-check"></i>';
        
        // Reset after 2 seconds
        setTimeout(() => {
            copyButton.classList.remove('copied');
            copyButton.innerHTML = '<i class="fas fa-copy"></i>';
        }, 2000);
    };
}

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
    
    if (donationModal && approvedModal) {
        // Add event listener for modal close, but only refresh when approved modal is shown
        donationModal.addEventListener('hidden.bs.modal', function () {
            // Check if approved modal was the last visible section
            if (!approvedModal.classList.contains('d-none')) {
                window.location.reload();
            }
        });
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initProjectStatsCarousel();
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
