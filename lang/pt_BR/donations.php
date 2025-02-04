<?php

return [
    'headers' => [
        'title' => 'Doações',
        'create' => 'Nova Doação',
        'edit' => 'Editar Doação',
        'show' => 'Detalhes da Doação'
    ],
    'forms' => [
        'external_reference' => 'Referência Externa',
        'nickname' => 'Nome',
        'email' => 'Email',
        'phone' => 'Telefone',
        'value' => 'Valor',
        'project' => 'Projeto',
        'message' => 'Mensagem',
        'status' => 'Status',
        'payment_method' => 'Método de Pagamento',
        'date' => 'Data',
        'proof_file' => 'Comprovante de Pagamento',
        'not_informed' => 'Não informado',
        'back_to_list' => 'Voltar para lista',
        'download_proof' => 'Baixar Comprovante',
        'no_proof_file' => 'Nenhum comprovante foi enviado para esta doação.'
    ],
    'status' => [
        'pending' => 'Pendente',
        'approved' => 'Aprovado',
        'rejected' => 'Rejeitado'
    ],
    'payment_method' => [
        'manual' => 'PIX Manual',
        'mercadopago' => 'Mercado Pago'
    ],
    'messages' => [
        'created_success' => 'Doação registrada com sucesso!',
        'updated_success' => 'Doação atualizada com sucesso!',
        'deleted_success' => 'Doação excluída com sucesso!',
        'approved_success' => 'Doação aprovada com sucesso!',
        'rejected_success' => 'Doação rejeitada com sucesso!',
        'errors' => [
            'proof_file_required' => 'O comprovante de pagamento é obrigatório para doações manuais.',
            'proof_file_invalid' => 'O arquivo de comprovante deve ser PDF, PNG, JPG ou JPEG com no máximo 2MB.',
            'payment_failed' => 'Erro ao processar o pagamento. Por favor, tente novamente.',
            'donation_failed' => 'Erro ao registrar a doação. Por favor, tente novamente.',
            'upload_failed' => 'Erro ao fazer upload do comprovante. Por favor, tente novamente.'
        ]
    ],
    'search' => [
        'placeholder' => 'Pesquisar doações (mínimo 3 caracteres)...',
        'placeholder_mobile' => 'Pesquisar doações...'
    ],
    'filters' => [
        'all' => 'Todas',
        'pending' => 'Pendentes',
        'approved' => 'Aprovadas'
    ],
    'proof' => [
        'view_pdf' => 'Visualizar PDF',
        'download' => 'Download',
        'unsupported_format' => 'Formato de arquivo não suportado.'
    ]
];
