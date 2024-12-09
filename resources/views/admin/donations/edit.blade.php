<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Doação') }}
        </h2>
    </x-slot>

    <x-notification />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('donations.update', $donation) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informações Básicas -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="external_reference" :value="__('Referência Externa')" />
                                    <x-text-input id="external_reference" name="external_reference" type="text" class="mt-1 block w-full" :value="old('external_reference', $donation->external_reference)" required />
                                    <x-input-error :messages="$errors->get('external_reference')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="nickname" :value="__('Nome')" />
                                    <x-text-input id="nickname" name="nickname" type="text" class="mt-1 block w-full" :value="old('nickname', $donation->nickname)" required />
                                    <x-input-error :messages="$errors->get('nickname')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $donation->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phone" :value="__('Telefone')" />
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $donation->phone)" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Informações da Doação -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="value" :value="__('Valor')" />
                                    <x-text-input id="value" name="value" type="number" step="0.01" class="mt-1 block w-full" :value="old('value', $donation->value)" required />
                                    <x-input-error :messages="$errors->get('value')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="pending" {{ old('status', $donation->status) === 'pending' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                                        <option value="approved" {{ old('status', $donation->status) === 'approved' ? 'selected' : '' }}>{{ __('Aprovado') }}</option>
                                        <option value="rejected" {{ old('status', $donation->status) === 'rejected' ? 'selected' : '' }}>{{ __('Rejeitado') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="project_id" :value="__('Projeto')" />
                                    <select id="project_id" name="project_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $donation->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Mensagem e Moderação -->
                        <div class="mt-6 space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="mb-4">
                                    <x-input-label for="message" :value="__('Mensagem do Doador')" />
                                    <textarea id="message" name="message" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('message', $donation->message) }}</textarea>
                                    <x-input-error :messages="$errors->get('message')" class="mt-2" />
                                </div>

                                <div class="border-t dark:border-gray-600 pt-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Moderação da Mensagem') }}</h3>
                                    
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" id="message_hidden" name="message_hidden" value="1" 
                                               class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                               {{ old('message_hidden', $donation->message_hidden) ? 'checked' : '' }}>
                                        <label for="message_hidden" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Ocultar esta mensagem') }}
                                        </label>
                                    </div>

                                    <div id="message_hidden_reason_container" class="space-y-2">
                                        <x-input-label for="message_hidden_reason" :value="__('Motivo da Ocultação')" class="text-sm font-medium text-gray-900 dark:text-gray-100" />
                                        <select id="message_hidden_reason" name="message_hidden_reason" 
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">{{ __('Selecione um motivo') }}</option>
                                            <option value="violence" {{ old('message_hidden_reason', $donation->message_hidden_reason) === 'violence' ? 'selected' : '' }}>
                                                {{ __('Conteúdo violento') }}
                                            </option>
                                            <option value="hate_speech" {{ old('message_hidden_reason', $donation->message_hidden_reason) === 'hate_speech' ? 'selected' : '' }}>
                                                {{ __('Discurso de ódio') }}
                                            </option>
                                            <option value="inappropriate" {{ old('message_hidden_reason', $donation->message_hidden_reason) === 'inappropriate' ? 'selected' : '' }}>
                                                {{ __('Conteúdo impróprio/sexual') }}
                                            </option>
                                            <option value="illegal" {{ old('message_hidden_reason', $donation->message_hidden_reason) === 'illegal' ? 'selected' : '' }}>
                                                {{ __('Conteúdo ilegal') }}
                                            </option>
                                            <option value="spam" {{ old('message_hidden_reason', $donation->message_hidden_reason) === 'spam' ? 'selected' : '' }}>
                                                {{ __('Spam/Propaganda não autorizada') }}
                                            </option>
                                            <option value="other" {{ old('message_hidden_reason', $donation->message_hidden_reason) === 'other' ? 'selected' : '' }}>
                                                {{ __('Outro motivo') }}
                                            </option>
                                        </select>
                                        <p id="reason_error" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ __('Por favor, selecione um motivo para ocultar a mensagem.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Salvar Alterações') }}</x-primary-button>
                            <a href="{{ route('donations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-white focus:bg-gray-600 dark:focus:bg-white active:bg-gray-700 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Função para validar o motivo da ocultação
            function validateReason() {
                const hiddenReason = $('#message_hidden_reason');
                if ($('#message_hidden').is(':checked')) {
                    hiddenReason.prop('required', true);
                    $('#message_hidden_reason_container').show();
                } else {
                    hiddenReason.prop('required', false);
                    $('#message_hidden_reason_container').hide();
                }
            }

            // Event listener para o checkbox
            $('#message_hidden').change(function() {
                validateReason();
            });

            // Inicialização
            if ($('#message_hidden').is(':checked')) {
                validateReason();
            }
        });
    </script>
    @endpush
</x-app-layout>
