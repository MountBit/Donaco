<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Excluir Conta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir sua conta, faça o download de quaisquer dados ou informações que deseja manter.') }}
        </p>
    </header>

    <button onclick="confirmDelete('{{ Auth::user()->name }}')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
        {{ __('Excluir Conta') }}
    </button>

    <form id="delete-user-form" method="post" action="{{ route('profile.destroy') }}" class="hidden">
        @csrf
        @method('delete')

        <div>
            <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

            <x-text-input
                id="password"
                name="password"
                type="password"
                class="mt-1 block w-3/4"
                placeholder="{{ __('Password') }}"
            />

            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>
    </form>

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
                    <div class="mt-4">
                        <x-text-input
                            id="modal-password"
                            type="password"
                            class="mt-1 block w-full"
                            placeholder="{{ __('Digite sua senha para confirmar') }}"
                        />
                    </div>
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
</section>

@push('scripts')
<script>
    function confirmDelete(userName) {
        const modal = document.getElementById('deleteModal');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const modalPassword = document.getElementById('modal-password');
        
        title.textContent = 'Confirmar Exclusão de Conta';
        message.textContent = `Tem certeza que deseja excluir sua conta "${userName}"? Esta ação é irreversível e todos os seus dados serão permanentemente excluídos.`;
        
        modal.classList.remove('hidden');
        modalPassword.value = '';
        modalPassword.focus();
        
        const deleteButton = document.getElementById('deleteButton');
        deleteButton.onclick = function() {
            const password = modalPassword.value;
            if (!password) {
                alert('Por favor, digite sua senha para confirmar a exclusão.');
                return;
            }
            
            document.getElementById('password').value = password;
            document.getElementById('delete-user-form').submit();
        }
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        document.getElementById('modal-password').value = '';
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

    // Submit form quando pressionar Enter no campo de senha
    document.getElementById('modal-password').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            document.getElementById('deleteButton').click();
        }
    });
</script>
@endpush
