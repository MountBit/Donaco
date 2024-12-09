<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes da Doação') }}
        </h2>
    </x-slot>

    <x-notification />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ __('Detalhes da Doação') }}</h1>
                        <a href="{{ route('donations.index') }}" class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-150 ease-in-out">
                            {{ __('Voltar') }}
                        </a>
                    </div>

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
                                <span class="font-semibold">{{ __('Status') }}:</span> 
                                <span class="px-2 py-1 rounded-full text-sm 
                                    @if($donation->status === 'approved') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @elseif($donation->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @elseif($donation->status === 'rejected') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                    @endif">
                                    {{ $donation->status }}
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

                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('donations.edit', $donation) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded-lg transition-colors duration-150 ease-in-out">
                            {{ __('Editar') }}
                        </a>
                        <button onclick="confirmDelete({{ $donation->id }}, '{{ $donation->nickname }}', '{{ $donation->value }}')"
                                class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-150 ease-in-out">
                            {{ __('Excluir') }}
                        </button>
                        <form id="delete-form-{{ $donation->id }}" action="{{ route('donations.destroy', $donation) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
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

    @push('scripts')
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
</x-app-layout>
