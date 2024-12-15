<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes da Doação') }}
            </h2>
            <a href="{{ route('admin.donations.index') }}" 
               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                Voltar para lista
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Referência Externa') }}:</span> 
                                {{ $donation->external_reference }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Nome') }}:</span> 
                                {{ $donation->nickname }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Email') }}:</span> 
                                {{ $donation->email }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Telefone') }}:</span> 
                                {{ $donation->phone ?? __('Não informado') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Valor') }}:</span> 
                                R$ {{ number_format($donation->value, 2, ',', '.') }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Método de Pagamento') }}:</span> 
                                <span class="px-2 py-1 rounded-full text-sm 
                                    {{ $donation->payment_method === 'manual' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' }}">
                                    {{ DonationHelper::getPaymentMethodLabel($donation->payment_method) }}
                                </span>
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Status') }}:</span> 
                                <span class="px-2 py-1 rounded-full text-sm {{ DonationHelper::getStatusClasses($donation->status) }}">
                                    {!! DonationHelper::getStatusIcon($donation->status) !!}{{ DonationHelper::getStatusLabel($donation->status) }}
                                </span>
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Projeto') }}:</span> 
                                {{ $donation->project->name ?? __('Não associado') }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mb-2">
                                <span class="font-semibold">{{ __('Data') }}:</span> 
                                {{ $donation->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($donation->message)
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('Mensagem') }}</h2>
                        <p class="text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">{{ $donation->message }}</p>
                    </div>
                    @endif

                    @if($donation->payment_method === 'manual')
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                {{ __('Comprovante de Pagamento') }}
                            </h2>
                            
                            @if($proofFileExists && $proofFileUrl)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    @php
                                        $extension = !empty($donation->proof_file) ? strtolower(pathinfo($donation->proof_file, PATHINFO_EXTENSION)) : '';
                                    @endphp

                                    @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                        <div class="flex flex-col items-center">
                                            <img src="{{ $proofFileUrl }}" 
                                                 alt="Comprovante" 
                                                 class="max-w-md mx-auto rounded-lg shadow-lg mb-2">
                                            <a href="{{ $proofFileUrl }}" 
                                               download 
                                               class="mt-2 text-blue-500 hover:text-blue-600">
                                                <i class="fas fa-download mr-1"></i> Download da Imagem
                                            </a>
                                        </div>
                                    @elseif($extension === 'pdf')
                                        <div class="flex flex-col items-center">
                                            <div class="mb-3">
                                                <i class="fas fa-file-pdf text-red-500 text-4xl"></i>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ $proofFileUrl }}" 
                                                   target="_blank" 
                                                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                                                    <i class="fas fa-eye mr-1"></i> Visualizar PDF
                                                </a>
                                                <a href="{{ $proofFileUrl }}" 
                                                   download 
                                                   class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-yellow-700">
                                                        Formato de arquivo não suportado.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                @if(empty($donation->proof_file))
                                                    Nenhum comprovante foi enviado para esta doação.
                                                @else
                                                    O arquivo do comprovante não foi encontrado no servidor.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6 flex gap-4 items-center">
                        @if($donation->payment_method === 'manual')
                            @if($donation->status === 'pending')
                                <span class="text-gray-700 dark:text-gray-300 mr-2">Ações disponíveis:</span>
                                <a href="#" 
                                   onclick="event.preventDefault(); showConfirmationModal('approve')"
                                   class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                    Aprovar
                                </a>
                                <span class="text-gray-400">|</span>
                                <a href="#" 
                                   onclick="event.preventDefault(); showConfirmationModal('reject')"
                                   class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    Rejeitar
                                </a>
                            @elseif($donation->status === 'approved')
                                <span class="px-4 py-2 {{ DonationHelper::getStatusClasses($donation->status) }} rounded-lg">
                                    {!! DonationHelper::getStatusIcon($donation->status) !!}Doação {{ DonationHelper::getStatusLabel($donation->status) }}
                                </span>
                            @elseif($donation->status === 'rejected')
                                <span class="px-4 py-2 {{ DonationHelper::getStatusClasses($donation->status) }} rounded-lg">
                                    {!! DonationHelper::getStatusIcon($donation->status) !!}Doação {{ DonationHelper::getStatusLabel($donation->status) }}
                                </span>
                            @endif
                        @else
                            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg">
                                <i class="fas fa-info-circle mr-2"></i>Pagamento via Mercado Pago
                            </span>
                        @endif

                        <span class="text-gray-400">|</span>
                        
                        <a href="{{ route('admin.donations.edit', $donation) }}" 
                           class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                            Editar
                        </a>
                    </div>

                    <form id="approve-form" action="{{ route('admin.donations.update', $donation) }}" method="POST" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="approved">
                    </form>

                    <form id="reject-form" action="{{ route('admin.donations.update', $donation) }}" method="POST" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmation-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden" style="z-index: 50;">
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div id="modal-icon" class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Ícone será inserido via JavaScript -->
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 id="modal-title" class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                <!-- Título será inserido via JavaScript -->
                            </h3>
                            <div class="mt-2">
                                <p id="modal-message" class="text-sm text-gray-500 dark:text-gray-400">
                                    <!-- Mensagem será inserida via JavaScript -->
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                        <button type="button" 
                                id="confirm-button"
                                class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto">
                            <!-- Texto do botão será inserido via JavaScript -->
                        </button>
                        <button type="button" 
                                onclick="hideConfirmationModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    const modalConfig = {
        approve: {
            title: 'Aprovar Doação',
            message: 'Tem certeza que deseja aprovar esta doação? Esta ação não pode ser desfeita.',
            buttonText: 'Aprovar',
            buttonClass: 'bg-green-600 hover:bg-green-700',
            iconClass: 'bg-green-100 dark:bg-green-900',
            iconHtml: '<i class="fas fa-check text-green-600 dark:text-green-200"></i>',
            formId: 'approve-form'
        },
        reject: {
            title: 'Rejeitar Doação',
            message: 'Tem certeza que deseja rejeitar esta doação? Esta ação não pode ser desfeita.',
            buttonText: 'Rejeitar',
            buttonClass: 'bg-red-600 hover:bg-red-700',
            iconClass: 'bg-red-100 dark:bg-red-900',
            iconHtml: '<i class="fas fa-times text-red-600 dark:text-red-200"></i>',
            formId: 'reject-form'
        }
    };

    function showConfirmationModal(action) {
        const config = modalConfig[action];
        const modal = document.getElementById('confirmation-modal');
        const modalIcon = document.getElementById('modal-icon');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const confirmButton = document.getElementById('confirm-button');

        // Configurar o modal
        modalIcon.className = `mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10 ${config.iconClass}`;
        modalIcon.innerHTML = config.iconHtml;
        modalTitle.textContent = config.title;
        modalMessage.textContent = config.message;
        
        // Configurar o botão de confirmação
        confirmButton.className = `inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto ${config.buttonClass}`;
        confirmButton.textContent = config.buttonText;
        confirmButton.onclick = () => {
            document.getElementById(config.formId).submit();
        };

        // Mostrar o modal com animação
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.transform').classList.add('sm:scale-100');
            modal.querySelector('.transform').classList.remove('sm:scale-95');
        }, 10);

        // Adicionar listener para fechar com ESC
        document.addEventListener('keydown', handleEscKey);
    }

    function hideConfirmationModal() {
        const modal = document.getElementById('confirmation-modal');
        modal.querySelector('.transform').classList.add('sm:scale-95');
        modal.querySelector('.transform').classList.remove('sm:scale-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);

        // Remover listener do ESC
        document.removeEventListener('keydown', handleEscKey);
    }

    function handleEscKey(e) {
        if (e.key === 'Escape') {
            hideConfirmationModal();
        }
    }

    // Fechar modal ao clicar fora
    document.getElementById('confirmation-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideConfirmationModal();
        }
    });
    </script>
    @endpush
</x-app-layout>
