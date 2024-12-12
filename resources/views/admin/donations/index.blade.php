<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Doações') }}
        </h2>
    </x-slot>

    <x-notification />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div x-data="{ 
                        show: false, 
                        donationId: null, 
                        donationName: null,
                        openModal(id, name) {
                            this.donationId = id;
                            this.donationName = name;
                            this.show = true;
                        }
                    }">
                        <!-- Header com Ações -->
                        <div class="mb-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <!-- Botão Nova Doação -->
                                <div x-data="{ open: false }">
                                    <button @click="open = true" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        + Nova Doação
                                    </button>

                                    <div x-show="open" x-cloak class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="open = false"></div>

                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 relative">
                                                <button @click="open = false" class="absolute top-0 right-0 mt-3 mr-3 inline-flex items-center justify-center p-1.5 bg-white text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 rounded-full">
                                                    <span class="sr-only">Fechar</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <div>
                                                    <div class="mt-3 text-center sm:mt-5">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                            Nova Doação
                                                        </h3>
                                                        <div class="mt-2">
                                                            <form id="donationForm">
                                                                <div class="mb-4">
                                                                    <label for="donorName" class="block text-sm font-medium text-gray-700">Nome do Doador</label>
                                                                    <input type="text" name="donorName" id="donorName" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label for="donorEmail" class="block text-sm font-medium text-gray-700">Email</label>
                                                                    <input type="email" name="donorEmail" id="donorEmail" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label for="donorPhone" class="block text-sm font-medium text-gray-700">Telefone</label>
                                                                    <div class="relative mt-1">
                                                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 pointer-events-none">
                                                                            +55
                                                                        </span>
                                                                        <input type="text"
                                                                            name="donorPhone"
                                                                            id="donorPhone"
                                                                            class="pl-12 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                                            maxlength="15">
                                                                    </div>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label for="donationAmount" class="block text-sm font-medium text-gray-700">Valor</label>
                                                                    <input type="text" name="donationAmount" id="donationAmount" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium text-gray-700">Tipo de Pagamento</label>
                                                                    <div class="mt-2 space-y-2">
                                                                        <div class="flex items-center">
                                                                            <input id="paymentCash" name="paymentType" type="radio" value="cash" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                                                            <label for="paymentCash" class="ml-3 block text-sm font-medium text-gray-700">
                                                                                Dinheiro
                                                                            </label>
                                                                        </div>
                                                                        <div class="flex items-center">
                                                                            <input id="paymentPix" name="paymentType" type="radio" value="pix" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                                                            <label for="paymentPix" class="ml-3 block text-sm font-medium text-gray-700">
                                                                                Pix
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-5 sm:mt-6">
                                                                    <button type="button"
                                                                        @click="submitDonation"
                                                                        class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                                                        Confirmar Doação
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Barra de Pesquisa -->
                                <div class="w-full sm:w-auto flex-1 sm:max-w-md"
                                    x-data="{ 
                                        search: '{{ $searchTerm }}',
                                        timeout: null,
                                        performSearch() {
                                            if (this.search.length >= 3 || this.search.length === 0) {
                                                window.location.href = '{{ route('donations.index') }}?search=' + this.search + '{{ $currentStatus ? '&status=' . $currentStatus : '' }}';
                                            }
                                        }
                                    }">
                                    <div class="relative">
                                        <input type="text"
                                            x-model="search"
                                            @input="clearTimeout(timeout); timeout = setTimeout(() => performSearch(), 800)"
                                            @keydown.enter="performSearch()"
                                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white dark:bg-gray-800 dark:border-gray-700 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm"
                                            placeholder="Pesquisar doações (mínimo 3 caracteres)...">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtros de Status -->
                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('donations.index', ['search' => request('search')]) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ !$currentStatus ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' : 'text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                    Todas
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-white dark:bg-gray-800">{{ $counts['all'] }}</span>
                                </a>
                                <a href="{{ route('donations.index', ['status' => 'pending', 'search' => request('search')]) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $currentStatus === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                    Pendentes
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-white dark:bg-gray-800">{{ $counts['pending'] }}</span>
                                </a>
                                <a href="{{ route('donations.index', ['status' => 'approved', 'search' => request('search')]) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $currentStatus === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                    Confirmadas
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-white dark:bg-gray-800">{{ $counts['approved'] }}</span>
                                </a>
                            </div>
                        </div>

                        @if($donations->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Nenhuma doação encontrada</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ request('search') ? 'Tente buscar com outros termos.' : 'Comece criando uma nova doação.' }}
                            </p>
                        </div>
                        @else
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <span class="sr-only">Avatar</span>
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Doador
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Valor
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Status
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Data
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            <span class="sr-only">Ações</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donations as $donation)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="w-4 p-4">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                <span class="text-indigo-700 dark:text-indigo-300 font-medium text-sm">
                                                    {{ strtoupper(substr($donation->nickname, 0, 2)) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $donation->nickname }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $donation->email }}
                                                </div>
                                                @if($donation->phone)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $donation->phone }}
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                            R$ {{ number_format($donation->value, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-full 
                                                        {{ $donation->status === 'approved' 
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ $donation->status === 'approved' ? 'Confirmada' : 'Pendente' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if ($donation->created_at)
                                            {{ $donation->created_at->format('d/m/Y H:i') }}
                                            @else
                                            Data não disponível
                                            @endif

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('donations.show', $donation) }}"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-all duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    {{ __('Visualizar') }}
                                                </a>
                                                <a href="{{ route('donations.edit', $donation) }}"
                                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-all duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    {{ __('Editar') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $donations->appends(request()->query())->links() }}
                        </div>
                        @endif

                        <!-- Modal de Aprovação -->
                        <div x-show="show"
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
                            x-cloak
                            @keydown.escape.window="show = false">
                            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6"
                                @click.away="show = false">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                            Confirmar Aprovação Manual
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Deseja aprovar manualmente o pagamento de <strong x-text="donationName"></strong>?
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <form :action="'/admin/donations/' + donationId" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-green-500 dark:hover:bg-green-600">
                                            Confirmar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                                <div class="mt-3 text-center">
                                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mt-4" id="modalTitle"></h3>
                                    <div class="mt-2 px-7 py-3">
                                        <p class="text-sm text-gray-500 dark:text-gray-400" id="modalMessage"></p>
                                    </div>
                                    <div class="items-center px-4 py-3">
                                        <button id="deleteButton" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-32 shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                            {{ __('Excluir') }}
                                        </button>
                                        <button onclick="closeModal()" class="ml-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-32 shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                            {{ __('Cancelar') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Máscara para valor em reais
        const donationAmountInput = document.getElementById('donationAmount');
        donationAmountInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2) + '';
            value = value.replace('.', ',');
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            e.target.value = value;
        });

        // Máscara para telefone
        const phoneInput = document.getElementById('donorPhone');
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            // Limita o tamanho para 11 dígitos (DDD + número)
            if (value.length > 11) {
                value = value.slice(0, 11);
            }

            // Aplica a máscara para visualização
            if (value.length <= 2) {
                if (value.length > 0) {
                    value = '(' + value;
                }
            } else if (value.length <= 6) {
                value = '(' + value.slice(0, 2) + ') ' + value.slice(2);
            } else if (value.length <= 10) {
                value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 6) + '-' + value.slice(6);
            } else {
                value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7);
            }

            e.target.value = value;
        });

        // Função para obter o valor do telefone formatado para o banco
        function getFormattedPhoneForDB() {
            const phone = phoneInput.value.replace(/\D/g, '');
            return phone ? '55' + phone : '';
        }

        // Função para submeter o formulário
        window.submitDonation = function() {
            const form = document.getElementById('donationForm');
            const formData = new FormData(form);

            // Substitui o valor do telefone pelo formato correto (com DDI, sem máscara)
            formData.set('donorPhone', getFormattedPhoneForDB());

            const paymentType = formData.get('paymentType');

            if (paymentType === 'cash') {
                // Lógica para salvar doação em dinheiro
                // Enviar requisição para o backend com status aprovado
            } else if (paymentType === 'pix') {
                // Lógica para iniciar o processo de pagamento via Pix
                // Usar a API do Mercado Pago
            }
        }
    });
</script>

@push('scripts')
<script>
    // Fechar modal ao clicar fora
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('fixed')) {
            event.target.classList.add('hidden');
        }
    });

    // Fechar modal com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('[id^="modal"]');
            modals.forEach(modal => modal.classList.add('hidden'));
        }
    });
</script>

<script>
    let currentDonationId = null;

    function confirmDelete(donationId, donationNickname, donationValue) {
        currentDonationId = donationId;
        const modal = document.getElementById('deleteModal');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');

        title.textContent = 'Confirmar Exclusão da Doação';
        message.textContent = `Tem certeza que deseja excluir a doação de R$ ${donationValue} feita por "${donationNickname}"? Esta ação não pode ser desfeita.`;

        modal.classList.remove('hidden');

        const deleteButton = document.getElementById('deleteButton');
        deleteButton.onclick = function() {
            document.getElementById(`delete-form-${donationId}`).submit();
        }
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        currentDonationId = null;
    }

    // Fechar modal ao clicar fora dele
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            closeModal();
        }
    }

    // Fechar modal com a tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush
