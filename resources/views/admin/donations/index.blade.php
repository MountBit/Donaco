<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('donations.headers.title') }}
        </h2>
    </x-slot>

    <x-notification />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cabeçalho com Botão e Pesquisa -->
            <div class="mb-6">
                <!-- Container para Desktop e Tablet -->
                <div class="hidden sm:flex sm:justify-between sm:items-center">
                    <button type="button" 
                            onclick="showDonationModal()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        {{ __('donations.headers.create') }}
                    </button>

                    <div class="relative">
                        <input type="text" 
                               id="search-desktop"
                               name="search"
                               placeholder="{{ __('donations.search.placeholder') }}"
                               class="w-96 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Container para Mobile -->
                <div class="sm:hidden space-y-4">
                    <!-- Botão Nova Doação -->
                    <div class="flex justify-center">
                        <button type="button" 
                                onclick="showDonationModal()"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i>
                            {{ __('donations.headers.create') }}
                        </button>
                    </div>

                    <!-- Campo de Pesquisa -->
                    <div class="relative">
                        <input type="text" 
                               id="search-mobile"
                               name="search"
                               placeholder="{{ __('donations.search.placeholder_mobile') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Filtros - Responsivo -->
                <div class="mt-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-wrap gap-2 sm:gap-4 justify-center sm:justify-start">
                            <a href="{{ route('admin.donations.index', array_merge(request()->except('status'), ['status' => null])) }}" 
                               class="px-3 sm:px-4 py-2 rounded-full text-sm {{ !request('status') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                {{ __('donations.filters.all') }} ({{ $counts['all'] }})
                            </a>
                            <a href="{{ route('admin.donations.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" 
                               class="px-3 sm:px-4 py-2 rounded-full text-sm {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                {{ __('donations.status.pending') }} ({{ $counts['pending'] }})
                            </a>
                            <a href="{{ route('admin.donations.index', array_merge(request()->except('status'), ['status' => 'approved'])) }}" 
                               class="px-3 sm:px-4 py-2 rounded-full text-sm {{ request('status') === 'approved' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                {{ __('donations.status.approved') }} ({{ $counts['approved'] }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Doações -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('donations.forms.date') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('donations.forms.nickname') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('donations.forms.value') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('donations.forms.payment_method') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('donations.forms.status') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('general.actions.title') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($donations as $donation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $donation->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $donation->nickname }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $donation->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ DonationHelper::formatMoneyValue($donation->value) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $donation->payment_method === 'manual' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                            {{ __('donations.payment_method.' . $donation->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full 
                                            {{ $donation->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                            {{ $donation->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                            {{ $donation->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                            @if($donation->status === 'pending')
                                                <i class="fas fa-clock mr-2"></i>
                                            @elseif($donation->status === 'approved')
                                                <i class="fas fa-check-circle mr-2"></i>
                                            @elseif($donation->status === 'rejected')
                                                <i class="fas fa-times-circle mr-2"></i>
                                            @endif
                                            {{ __('donations.status.' . $donation->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.donations.show', $donation) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                            {{ __('general.actions.view') }}
                                        </a>
                                        <a href="{{ route('admin.donations.edit', $donation) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                            {{ __('general.actions.edit') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $donations->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Nova Doação -->
    <div id="donation-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden" style="z-index: 50;">
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <!-- Cabeçalho do Modal -->
                    <div class="sm:flex sm:items-start mb-4">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-hand-holding-usd text-indigo-600 dark:text-indigo-300"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-grow">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                Nova Doação
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Preencha os dados abaixo para registrar uma nova doação.
                            </p>
                        </div>
                        <!-- Botão Fechar -->
                        <button type="button" 
                                onclick="hideDonationModal()"
                                class="absolute right-4 top-4 text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Fechar</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div id="donation-form-alert" class="hidden mb-4 p-4 rounded-md bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-200 text-sm"></div>

                    <form id="donation-form" action="{{ route('admin.donations.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <!-- Projeto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Projeto</label>
                            <select name="project_id" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecione um projeto</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Doador -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doador</label>
                            <input type="text" name="nickname" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Nome do doador">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="email@exemplo.com">
                        </div>

                        <!-- Valor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">R$</span>
                                </div>
                                <input type="text" 
                                       name="value" 
                                       required 
                                       class="money pl-8 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="0,00"
                                       style="padding-left: 2.5rem;"
                                       >
                            </div>
                        </div>

                        <!-- Mensagem -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensagem (opcional)</label>
                            <textarea name="message" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Mensagem do doador"></textarea>
                        </div>

                        <!-- Botão Submit -->
                        <div class="mt-5">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                                Salvar Doação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Função para mostrar o modal
        function showDonationModal() {
            const modal = document.getElementById('donation-modal');
            modal.classList.remove('hidden');
        }

        // Função para esconder o modal
        function hideDonationModal() {
            const modal = document.getElementById('donation-modal');
            modal.classList.add('hidden');
        }

        // Funções de Máscara Monetária
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
                    
                    // Formatar com pontos e vírgulas
                    value = value.replace('.', ',');
                    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
                    
                    e.target.value = value;
                });
            });
        }

        // Inicializar quando o documento estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            initMoneyMask();
        });

        // Pesquisa com debounce
        function setupSearch(elementId) {
            const searchInput = document.getElementById(elementId);
            if (!searchInput) return;

            let timeoutId;

            searchInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                
                timeoutId = setTimeout(() => {
                    const value = this.value;
                    if (value.length >= 3 || value.length === 0) {
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('search', value);
                        window.location.href = currentUrl.toString();
                    }
                }, 500);
            });
        }

        setupSearch('search-desktop');
        setupSearch('search-mobile');
    </script>
    @endpush
</x-app-layout>
