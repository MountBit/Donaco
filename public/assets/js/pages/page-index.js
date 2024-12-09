const form = {
    donation: $('#form-donation')
}

const modalBody = {
    payer: $("#modal-body-payer"),     // informações do doador
    payment: $('#modal-body-payment'),  // informações para o doador realizar o pagamento.
    approved: $('#modal-body-approved') // pagamento aprovado.
}

form.donation.on('submit', async function (e) {

    e.preventDefault();

    const data = {
        nickname: $(this).find('#nickname').val(),
        email: $(this).find('#email').val(),
        message: $(this).find('#message').val(),
        value: $(this).find('#value').val(),
        project_id: $(this).find('#project_id').val()
    }

    modalBody.payer.addClass('d-none');
    modalBody.payment.removeClass('d-none');

    const response = await sendFormDataToGeneratePixPayment(data);

    let status = response.status || "error";

    if (status !== "success") {
        $('#alert-donation').text(response.message || "erro desconhecido");
        $('#alert-donation').removeClass("d-none");

        modalBody.payer.removeClass('d-none');
        modalBody.payment.addClass('d-none');
        return;
    }

    showInformationToPay(response);

});

const sendFormDataToGeneratePixPayment = async (data) => {

    const response = await fetch(donationStoreUrl, {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    return response.json() || null;
}


const showInformationToPay = (information) => {

    const codePix = information.code;
    const QRCodePixBase64 = information.qr_code_base64;
    const externalReference = information.external_reference;

    $("#code-pix").val(codePix);
    $("#image-qrcode-pix").attr('src', 'data:image/jpeg;base64,' + QRCodePixBase64);

    $('#loading').addClass('d-none');
    $("#payment-content").removeClass('d-none');

    $("#copyButton").click(function() {
        var copyText = $("#code-pix");
        copyText.select();
        document.execCommand("copy");
        $('#copyButton').text("Copiado! :)");
    });


    showApprovedPaymentStatus(externalReference);
}


const showApprovedPaymentStatus = (externalReference) => {
    // Substitui o placeholder com o valor real do externalReference
    const eventSourceUrl = donationStatusRoute.replace(':externalReference', externalReference);

    // Função para fazer requisição ao controller
    const fetchPaymentStatus = () => {
        $.ajax({
            url: eventSourceUrl,
            type: 'GET',
            success: (response) => {
                if (response.status === 'approved') {
                    clearInterval(statusCheckInterval); // Para a verificação periódica
                    startConfettiEffect(); // Inicia o efeito de confete

                    // Atualiza a interface do modal com a confirmação de pagamento
                    $('#modal-title').text('Pagamento aprovado com sucesso! 🥳');
                    modalBody.payment.addClass('d-none');
                    modalBody.approved.removeClass('d-none');
                    $('.btn-close').on('click', function() {
                        $('#form-donation')[0].reset();
                        // Espera 5 segundos antes de recarregar a página
                        setTimeout(function() {
                            location.reload(); // Recarrega a página
                        }, 1000); // 1000 milissegundos = 5 segundos
                    });
                }
            },
            error: (xhr, status, error) => {
                console.error('Erro ao verificar status do pagamento:', error);
            }
        });
    };

    // Verifica o status de 5 em 5 segundos
    const statusCheckInterval = setInterval(fetchPaymentStatus, 15000);

    // Chama imediatamente a primeira vez
    fetchPaymentStatus();
};




const startConfettiEffect = () => {

    var end = Date.now() + (5 * 1000);
    var colors = ['#f05467', '#ffae62'];

    (function frame() {
        confetti({
            particleCount: 2,
            angle: 60,
            spread: 55,
            origin: { x: 0 },
            colors: colors
        });
        confetti({
            particleCount: 2,
            angle: 120,
            spread: 55,
            origin: { x: 1 },
            colors: colors
        });

        if (Date.now() < end) {
            requestAnimationFrame(frame);
        }
    }());
}
